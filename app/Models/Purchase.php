<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'purchase_date',
        'total',
        'notes',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'total' => 'decimal:2',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(
                $filters['search'] ?? null,
                fn (Builder $q, string $search) => $q->where('invoice_number', 'like', "%{$search}%")
            )
            ->when(
                $filters['supplier_id'] ?? null,
                fn (Builder $q, int $supplierId) => $q->where('supplier_id', $supplierId)
            )
            ->when(
                $filters['date_from'] ?? null,
                fn (Builder $q, string $dateFrom) => $q->whereDate('purchase_date', '>=', $dateFrom)
            )
            ->when(
                $filters['date_to'] ?? null,
                fn (Builder $q, string $dateTo) => $q->whereDate('purchase_date', '<=', $dateTo)
            );
    }
}
