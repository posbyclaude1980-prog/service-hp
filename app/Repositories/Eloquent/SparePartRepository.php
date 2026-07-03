<?php

namespace App\Repositories\Eloquent;

use App\Models\SparePart;
use App\Repositories\Contracts\SparePartRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SparePartRepository implements SparePartRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = SparePart::query()->latest('name');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search): void {
                $builder->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['low_stock']) && filter_var($filters['low_stock'], FILTER_VALIDATE_BOOL)) {
            $query->whereColumn('stock', '<=', 'min_stock');
        }

        if (isset($filters['out_of_stock']) && filter_var($filters['out_of_stock'], FILTER_VALIDATE_BOOL)) {
            $query->where('stock', '<=', 0);
        }

        return $query->paginate($perPage);
    }

    /**
     * @return Collection<int, SparePart>
     */
    public function getLowStock(): Collection
    {
        return SparePart::query()
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->orderBy('name')
            ->get();
    }
}
