<?php

namespace App\Repositories\Contracts;

use App\Models\SparePart;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SparePartRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * @return Collection<int, SparePart>
     */
    public function getLowStock(): Collection;
}
