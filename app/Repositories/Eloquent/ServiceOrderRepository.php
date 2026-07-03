<?php

namespace App\Repositories\Eloquent;

use App\Models\ServiceOrder;
use App\Repositories\Contracts\ServiceOrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ServiceOrderRepository implements ServiceOrderRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function paginateWithFilters(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = ServiceOrder::query()
            ->with(['customer', 'technician.user'])
            ->latest('entry_date')
            ->latest('id');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($builder) use ($search): void {
                $builder->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('device_brand', 'like', "%{$search}%")
                    ->orWhere('device_model', 'like', "%{$search}%")
                    ->orWhere('imei', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search): void {
                        $customerQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (! empty($filters['technician_id'])) {
            $query->where('technician_id', $filters['technician_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('entry_date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('entry_date', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage);
    }

    /**
     * @param  array<int, string>  $relations
     */
    public function findWithRelations(int $id, array $relations = []): ?ServiceOrder
    {
        $defaultRelations = ['customer', 'technician.user', 'items.sparePart', 'intakeImages'];
        $relations = $relations === [] ? $defaultRelations : $relations;

        return ServiceOrder::query()
            ->with($relations)
            ->find($id);
    }
}
