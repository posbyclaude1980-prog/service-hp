<?php

namespace App\Services;

use App\Enums\ServiceOrderStatus;
use App\Enums\ServiceWorkAction;
use App\Models\ServiceOrder;
use App\Models\ServiceWorkLog;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TechnicianWorkService
{
    public function __construct(
        private readonly ServiceOrderService $serviceOrderService,
        private readonly ServiceOrderStatusHistoryService $statusHistoryService,
        private readonly ServiceOrderPhotoService $photoService,
    ) {}

    public function log(
        ServiceOrder $order,
        User $user,
        ServiceWorkAction $action,
        string $title,
        ?string $notes = null,
        ?array $metadata = null,
    ): ServiceWorkLog {
        return ServiceWorkLog::query()->create([
            'service_order_id' => $order->id,
            'user_id' => $user->id,
            'action' => $action,
            'title' => $title,
            'notes' => $notes,
            'metadata' => $metadata,
            'logged_at' => now(),
        ]);
    }

    public function saveDiagnosis(ServiceOrder $order, User $user, string $diagnosis, ?string $notes = null): ServiceOrder
    {
        return DB::transaction(function () use ($order, $user, $diagnosis, $notes): ServiceOrder {
            $previous = $order->diagnosis;

            $order->update(['diagnosis' => $diagnosis]);

            $this->log(
                $order,
                $user,
                ServiceWorkAction::Diagnosis,
                'Diagnosa diperbarui',
                $notes,
                ['diagnosis' => $diagnosis, 'previous' => $previous],
            );

            if ($order->status === ServiceOrderStatus::Received) {
                $this->serviceOrderService->updateStatus(
                    $order->id,
                    ServiceOrderStatus::Diagnosis->value,
                    $user,
                    $notes ?? 'Memulai diagnosa',
                );
            }

            return $order->fresh(['workLogs.user', 'items.sparePart']);
        });
    }

    public function updateStatus(
        ServiceOrder $order,
        User $user,
        string $status,
        string $notes,
        ?UploadedFile $photo = null,
        ?string $diagnosis = null,
    ): ServiceOrder {
        return DB::transaction(function () use ($order, $user, $status, $notes, $photo, $diagnosis): ServiceOrder {
            $from = $order->status?->value;
            $target = ServiceOrderStatus::from($status);

            if ($diagnosis !== null && $diagnosis !== '') {
                $order->update(['diagnosis' => $diagnosis]);
            }

            $updated = $this->serviceOrderService->updateStatus(
                $order->id,
                $target->value,
                $user,
                $notes,
                $photo,
            );

            $this->log(
                $updated,
                $user,
                ServiceWorkAction::StatusChange,
                "Status: {$updated->status->label()}",
                $notes,
                [
                    'from_status' => $from,
                    'to_status' => $target->value,
                    'diagnosis' => $diagnosis,
                ],
            );

            return $updated->fresh(['workLogs.user', 'statusHistories.user', 'items.sparePart']);
        });
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    public function syncSpareParts(ServiceOrder $order, User $user, array $items, ?string $notes = null): ServiceOrder
    {
        return DB::transaction(function () use ($order, $user, $items, $notes): ServiceOrder {
            $before = $order->items->map(fn ($i) => [
                'sparepart_id' => $i->sparepart_id,
                'name' => $i->sparePart?->name,
                'quantity' => $i->quantity,
                'unit_price' => $i->unit_price,
            ])->values()->all();

            $updated = $this->serviceOrderService->syncItems($order, $items, $user->id);
            $updated->load('items.sparePart');

            $after = $updated->items->map(fn ($i) => [
                'sparepart_id' => $i->sparepart_id,
                'name' => $i->sparePart?->name,
                'quantity' => $i->quantity,
                'unit_price' => $i->unit_price,
            ])->values()->all();

            $this->log(
                $updated,
                $user,
                ServiceWorkAction::SparePart,
                'Spare part diperbarui',
                $notes,
                ['before' => $before, 'after' => $after],
            );

            return $updated->fresh(['workLogs.user', 'items.sparePart']);
        });
    }

    public function updateServiceFee(ServiceOrder $order, User $user, float $serviceFee, ?string $notes = null): ServiceOrder
    {
        return DB::transaction(function () use ($order, $user, $serviceFee, $notes): ServiceOrder {
            $previous = (float) $order->service_fee;

            $order->update(['service_fee' => $serviceFee]);
            $updated = $this->serviceOrderService->recalculateTotals($order);

            $this->log(
                $updated,
                $user,
                ServiceWorkAction::ServiceFee,
                'Biaya jasa diperbarui',
                $notes,
                [
                    'previous' => $previous,
                    'current' => $serviceFee,
                    'total_cost' => (float) $updated->total_cost,
                ],
            );

            return $updated->fresh(['workLogs.user', 'items.sparePart']);
        });
    }

    public function uploadPhoto(
        ServiceOrder $order,
        User $user,
        UploadedFile $photo,
        ?string $caption = null,
        ?string $notes = null,
    ): ServiceOrder {
        return DB::transaction(function () use ($order, $user, $photo, $caption, $notes): ServiceOrder {
            $image = $this->photoService->storeWorkPhoto($order, $photo, $user->id, $caption);

            $this->log(
                $order,
                $user,
                ServiceWorkAction::Photo,
                'Foto pekerjaan diunggah',
                $notes ?? $caption,
                ['image_id' => $image->id, 'path' => $image->path, 'caption' => $image->caption],
            );

            return $order->fresh(['workLogs.user', 'workImages']);
        });
    }

    public function uploadVideo(
        ServiceOrder $order,
        User $user,
        UploadedFile $video,
        ?string $caption = null,
        ?string $notes = null,
    ): ServiceOrder {
        return DB::transaction(function () use ($order, $user, $video, $caption, $notes): ServiceOrder {
            $media = $this->photoService->storeVideo($order, $video, $user->id, $caption);

            $this->log(
                $order,
                $user,
                ServiceWorkAction::Video,
                'Video pekerjaan diunggah',
                $notes ?? $caption,
                ['video_id' => $media->id, 'path' => $media->path, 'caption' => $media->caption],
            );

            return $order->fresh(['workLogs.user', 'workVideos']);
        });
    }

    public function addNote(ServiceOrder $order, User $user, string $notes): ServiceOrder
    {
        return DB::transaction(function () use ($order, $user, $notes): ServiceOrder {
            $combined = trim(($order->notes ? $order->notes."\n\n" : '').'['.now()->format('d/m/Y H:i')." {$user->name}] ".$notes);
            $order->update(['notes' => $combined]);

            $this->log($order, $user, ServiceWorkAction::Note, 'Catatan ditambahkan', $notes);

            return $order->fresh(['workLogs.user']);
        });
    }

    public function performQc(ServiceOrder $order, User $user, bool $passed, string $notes): ServiceOrder
    {
        if ($order->status !== ServiceOrderStatus::Qc) {
            throw new RuntimeException('QC hanya dapat dilakukan saat status QC.');
        }

        $target = $passed ? ServiceOrderStatus::Completed : ServiceOrderStatus::InService;
        $action = $passed ? ServiceWorkAction::QcPass : ServiceWorkAction::QcFail;

        return DB::transaction(function () use ($order, $user, $target, $action, $passed, $notes): ServiceOrder {
            $updated = $this->serviceOrderService->updateStatus(
                $order->id,
                $target->value,
                $user,
                $notes,
            );

            $this->log(
                $updated,
                $user,
                $action,
                $passed ? 'QC lulus — siap diserahkan' : 'QC gagal — kembali proses service',
                $notes,
                ['qc_result' => $passed ? 'pass' : 'fail', 'to_status' => $target->value],
            );

            return $updated->fresh(['workLogs.user', 'statusHistories.user']);
        });
    }
}
