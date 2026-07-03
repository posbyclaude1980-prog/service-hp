<?php

namespace App\Services;

use App\Enums\ServiceOrderStatus;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderStatusHistory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class ServiceOrderStatusHistoryService
{
    public function __construct(
        private readonly ServiceOrderPhotoService $photoService,
    ) {}

    public function record(
        ServiceOrder $order,
        ServiceOrderStatus|string $toStatus,
        int $userId,
        ?string $notes = null,
        ?UploadedFile $photo = null,
        ServiceOrderStatus|string|null $fromStatus = null,
        ?\DateTimeInterface $changedAt = null,
    ): ServiceOrderStatusHistory {
        $to = $toStatus instanceof ServiceOrderStatus
            ? $toStatus
            : ServiceOrderStatus::from($toStatus);

        $from = $fromStatus !== null
            ? ($fromStatus instanceof ServiceOrderStatus ? $fromStatus : ServiceOrderStatus::from($fromStatus))
            : null;

        $history = ServiceOrderStatusHistory::query()->create([
            'service_order_id' => $order->id,
            'from_status' => $from?->value,
            'to_status' => $to->value,
            'changed_at' => $changedAt ?? now(),
            'changed_by' => $userId,
            'notes' => $notes,
        ]);

        if ($photo instanceof UploadedFile) {
            $this->photoService->replacePhoto(
                $order,
                'status',
                $photo,
                $userId,
                $history->id,
            );
        }

        return $history;
    }

    public function transition(
        ServiceOrder $order,
        ServiceOrderStatus|string $toStatus,
        User $user,
        ?string $notes = null,
        ?UploadedFile $photo = null,
    ): ServiceOrder {
        $target = $toStatus instanceof ServiceOrderStatus
            ? $toStatus
            : ServiceOrderStatus::from($toStatus);

        $current = $order->status;

        if ($current === null) {
            throw new RuntimeException('Service order has no current status.');
        }

        if (! $current->canTransitionTo($target, $user->role)) {
            throw ValidationException::withMessages([
                'status' => "Transisi dari \"{$current->label()}\" ke \"{$target->label()}\" tidak diizinkan untuk role Anda.",
            ]);
        }

        return DB::transaction(function () use ($order, $current, $target, $user, $notes, $photo): ServiceOrder {
            $payload = ['status' => $target->value];

            if ($target === ServiceOrderStatus::Completed && $order->completion_date === null) {
                $payload['completion_date'] = now()->toDateString();
            }

            if ($target === ServiceOrderStatus::PickedUp && $order->pickup_date === null) {
                $payload['pickup_date'] = now()->toDateString();
            }

            if ($target === ServiceOrderStatus::Warranty && $order->warranty_start_date === null) {
                $payload['warranty_start_date'] = now()->toDateString();
            }

            if ($target === ServiceOrderStatus::Closed && $order->closed_date === null) {
                $payload['closed_date'] = now()->toDateString();
            }

            $order->update($payload);

            $this->record($order, $target, $user->id, $notes, $photo, $current);

            return $order->fresh(['statusHistories.user', 'statusHistories.images']);
        });
    }

    /**
     * @return array<int, ServiceOrderStatus>
     */
    public function allowedNextStatuses(ServiceOrder $order, User $user): array
    {
        $current = $order->status;

        if ($current === null || $current->isTerminal()) {
            return [];
        }

        $options = [];

        if ($next = $current->next()) {
            if ($current->canTransitionTo($next, $user->role)) {
                $options[] = $next;
            }
        }

        foreach (ServiceOrderStatus::workflow() as $status) {
            if ($status->order() > $current->order() && $current->canTransitionTo($status, $user->role)) {
                if (! collect($options)->contains(fn ($s) => $s === $status)) {
                    $options[] = $status;
                }
            }
        }

        if ($current->canTransitionTo(ServiceOrderStatus::Cancelled, $user->role)) {
            $options[] = ServiceOrderStatus::Cancelled;
        }

        usort($options, fn (ServiceOrderStatus $a, ServiceOrderStatus $b) => $a->order() <=> $b->order());

        return $options;
    }
}
