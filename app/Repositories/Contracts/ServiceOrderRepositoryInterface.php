<?php

namespace App\Repositories\Contracts;

use App\Models\ServiceOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ServiceOrderRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array<int, string>  $relations
     */
    public function findWithRelations(int $id, array $relations = []): ?ServiceOrder;
}
