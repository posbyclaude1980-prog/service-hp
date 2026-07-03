<?php

namespace App\Models;

use App\Enums\ServiceOrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'branch_id',
        'ticket_number',
        'customer_id',
        'contact_phone',
        'device_id',
        'device_brand',
        'device_model',
        'imei',
        'color',
        'device_password',
        'complaint',
        'completeness',
        'diagnosis',
        'service_fee',
        'estimated_cost',
        'parts_total',
        'discount',
        'tax',
        'total_cost',
        'paid_amount',
        'status',
        'technician_id',
        'created_by',
        'entry_date',
        'estimated_completion',
        'completion_date',
        'pickup_date',
        'warranty_start_date',
        'closed_date',
        'warranty_days',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => ServiceOrderStatus::class,
            'service_fee' => 'decimal:2',
            'estimated_cost' => 'decimal:2',
            'parts_total' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'entry_date' => 'date',
            'estimated_completion' => 'date',
            'completion_date' => 'date',
            'pickup_date' => 'date',
            'warranty_start_date' => 'date',
            'closed_date' => 'date',
            'warranty_days' => 'integer',
        ];
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn () => $this->status?->label() ?? '');
    }

    protected function remainingPayment(): Attribute
    {
        return Attribute::get(
            fn () => max(0, (float) $this->total_cost - (float) $this->paid_amount)
        );
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function phoneModel(): BelongsTo
    {
        return $this->belongsTo(PhoneModel::class, 'device_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ServiceOrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ServiceOrderStatusHistory::class)->orderByDesc('changed_at');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ServiceImage::class);
    }

    public function intakeImages(): HasMany
    {
        return $this->hasMany(ServiceImage::class)->whereIn('type', array_keys(ServiceImage::intakeTypes()));
    }

    public function workImages(): HasMany
    {
        return $this->hasMany(ServiceImage::class)->where('type', ServiceImage::TYPE_WORK);
    }

    public function workVideos(): HasMany
    {
        return $this->hasMany(ServiceImage::class)->where('type', ServiceImage::TYPE_VIDEO);
    }

    public function workLogs(): HasMany
    {
        return $this->hasMany(ServiceWorkLog::class)->orderByDesc('logged_at');
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ServiceInvoice::class);
    }

    public function isFullyPaid(): bool
    {
        return (float) $this->total_cost > 0
            && (float) $this->paid_amount >= (float) $this->total_cost;
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(
                $filters['status'] ?? null,
                fn (Builder $q, string|ServiceOrderStatus $status) => $q->where(
                    'status',
                    $status instanceof ServiceOrderStatus ? $status->value : $status
                )
            )
            ->when(
                $filters['technician_id'] ?? null,
                fn (Builder $q, int $technicianId) => $q->where('technician_id', $technicianId)
            )
            ->when(
                $filters['date_from'] ?? null,
                fn (Builder $q, string $dateFrom) => $q->whereDate('entry_date', '>=', $dateFrom)
            )
            ->when(
                $filters['date_to'] ?? null,
                fn (Builder $q, string $dateTo) => $q->whereDate('entry_date', '<=', $dateTo)
            )
            ->when(
                $filters['search'] ?? null,
                fn (Builder $q, string $search) => $q->where(function (Builder $q) use ($search) {
                    $q->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('device_brand', 'like', "%{$search}%")
                        ->orWhere('device_model', 'like', "%{$search}%")
                        ->orWhere('imei', 'like', "%{$search}%")
                        ->orWhere('contact_phone', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn (Builder $q) => $q->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%"));
                })
            );
    }
}
