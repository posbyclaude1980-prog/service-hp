<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePart extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spareparts';

    protected $fillable = [
        'uuid',
        'branch_id',
        'supplier_id',
        'code',
        'name',
        'description',
        'unit',
        'stock',
        'min_stock',
        'purchase_price',
        'selling_price',
        'location',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'min_stock' => 'integer',
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function serviceOrderItems(): HasMany
    {
        return $this->hasMany(ServiceOrderItem::class, 'sparepart_id');
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when(
            $filters['search'] ?? null,
            fn (Builder $q, string $search) => $q->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
        );
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stock', '<=', 'min_stock');
    }
}
