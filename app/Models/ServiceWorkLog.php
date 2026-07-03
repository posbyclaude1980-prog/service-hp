<?php

namespace App\Models;

use App\Enums\ServiceWorkAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceWorkLog extends Model
{
    protected $fillable = [
        'service_order_id',
        'user_id',
        'action',
        'title',
        'notes',
        'metadata',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'action' => ServiceWorkAction::class,
            'metadata' => 'array',
            'logged_at' => 'datetime',
        ];
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
