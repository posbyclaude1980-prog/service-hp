<?php

namespace App\Services;

use App\Models\ServiceImage;
use App\Models\ServiceOrder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ServiceOrderPhotoService
{
  /** @var array<string, string> */
    public const INTAKE_FIELDS = [
        'photo_front' => ServiceImage::TYPE_FRONT,
        'photo_back' => ServiceImage::TYPE_BACK,
        'photo_lcd' => ServiceImage::TYPE_LCD,
        'photo_imei' => ServiceImage::TYPE_IMEI,
    ];

    /**
     * @param  array<string, UploadedFile|null>  $photos
     */
    public function storeIntakePhotos(ServiceOrder $order, array $photos, ?int $userId = null): void
    {
        foreach (self::INTAKE_FIELDS as $field => $type) {
            $file = $photos[$field] ?? null;

            if (! $file instanceof UploadedFile) {
                continue;
            }

            $this->replacePhoto($order, $type, $file, $userId);
        }
    }

    public function replacePhoto(
        ServiceOrder $order,
        string $type,
        UploadedFile $file,
        ?int $userId = null,
        ?int $statusHistoryId = null,
    ): ServiceImage {
        $order->images()->where('type', $type)->get()->each(function (ServiceImage $existing): void {
            Storage::disk('public')->delete($existing->path);
            $existing->delete();
        });

        $path = $this->resizeAndStore($order, $type, $file);

        return $order->images()->create([
            'type' => $type,
            'path' => $path,
            'caption' => ServiceImage::intakeTypes()[$type] ?? $type,
            'uploaded_by' => $userId,
            'service_status_history_id' => $statusHistoryId,
        ]);
    }

    private function resizeAndStore(ServiceOrder $order, string $type, UploadedFile $file): string
    {
        $directory = "service-orders/{$order->id}/{$type}";
        $filename = now()->format('YmdHis').'_'.uniqid().'.webp';
        $path = "{$directory}/{$filename}";

        $image = Image::read($file->getRealPath())
            ->scaleDown(width: 1280, height: 1280)
            ->toWebp(quality: 85);

        Storage::disk('public')->put($path, (string) $image);

        return $path;
    }

    public function storeWorkPhoto(
        ServiceOrder $order,
        UploadedFile $file,
        ?int $userId = null,
        ?string $caption = null,
    ): ServiceImage {
        $path = $this->resizeAndStore($order, ServiceImage::TYPE_WORK, $file);

        return $order->images()->create([
            'type' => ServiceImage::TYPE_WORK,
            'path' => $path,
            'caption' => $caption ?? 'Foto Pekerjaan',
            'uploaded_by' => $userId,
        ]);
    }

    public function storeVideo(
        ServiceOrder $order,
        UploadedFile $file,
        ?int $userId = null,
        ?string $caption = null,
    ): ServiceImage {
        $directory = "service-orders/{$order->id}/videos";
        $extension = $file->getClientOriginalExtension() ?: 'mp4';
        $filename = now()->format('YmdHis').'_'.uniqid().'.'.$extension;
        $path = $file->storeAs($directory, $filename, 'public');

        return $order->images()->create([
            'type' => ServiceImage::TYPE_VIDEO,
            'path' => $path,
            'caption' => $caption ?? 'Video Pekerjaan',
            'uploaded_by' => $userId,
        ]);
    }
}
