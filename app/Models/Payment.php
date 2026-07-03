<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'service_order_id',
        'payment_method_id',
        'branch_id',
        'received_by',
        'amount',
        'payment_date',
        'reference_number',
        'notes',
        'method',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
            'method' => PaymentMethod::class,
        ];
    }

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->usesLegacySchema()
            ? $this->belongsTo(User::class, 'user_id')
            : $this->belongsTo(User::class, 'received_by');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethodModel::class, 'payment_method_id');
    }

    public function getMethodLabelAttribute(): string
    {
        if ($this->usesLegacySchema()) {
            return $this->method?->label() ?? '-';
        }

        return $this->paymentMethod?->name ?? '-';
    }

    public function usesLegacySchema(): bool
    {
        return Schema::hasColumn($this->getTable(), 'method');
    }
}
