<?php

namespace App\Models;

use App\Enums\ServiceOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceOrderStatusHistory extends Model
{
    protected $table = 'service_status_histories';

    protected $fillable = [
        'service_order_id',
        'from_status',
        'to_status',
        'changed_at',
        'changed_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
            'from_status' => ServiceOrderStatus::class,
            'to_status' => ServiceOrderStatus::class,
        ];
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ServiceImage::class, 'service_status_history_id');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        $image = $this->images->first();

        return $image?->url;
    }

    public function getToStatusLabelAttribute(): string
    {
        return $this->to_status?->label() ?? (string) $this->getRawOriginal('to_status');
    }

    public function getFromStatusLabelAttribute(): ?string
    {
        if ($this->from_status === null) {
            return null;
        }

        return $this->from_status->label();
    }
}
