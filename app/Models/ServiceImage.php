<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ServiceImage extends Model
{
    public const TYPE_FRONT = 'front';

    public const TYPE_BACK = 'back';

    public const TYPE_LCD = 'lcd';

    public const TYPE_IMEI = 'imei';

    public const TYPE_GENERAL = 'general';

    public const TYPE_WORK = 'work';

    public const TYPE_VIDEO = 'video';

    public const TYPE_STATUS = 'status';

    protected $fillable = [
        'service_order_id',
        'service_status_history_id',
        'type',
        'path',
        'caption',
        'uploaded_by',
    ];

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function statusHistory(): BelongsTo
    {
        return $this->belongsTo(ServiceOrderStatusHistory::class, 'service_status_history_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public static function intakeTypes(): array
    {
        return [
            self::TYPE_FRONT => 'Foto Depan',
            self::TYPE_BACK => 'Foto Belakang',
            self::TYPE_LCD => 'Foto LCD',
            self::TYPE_IMEI => 'Foto IMEI',
        ];
    }

    public function isVideo(): bool
    {
        return $this->type === self::TYPE_VIDEO;
    }
}
