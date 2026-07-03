<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return Customer::query()
            ->when(
                $this->filters['search'] ?? null,
                fn (Builder $q, string $search) => $q->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
            )
            ->latest();
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['Kode', 'Nama', 'Telepon', 'Alamat', 'Dibuat'];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($customer): array
    {
        return [
            $customer->code,
            $customer->name,
            $customer->phone,
            $customer->address,
            $customer->created_at?->format('d/m/Y H:i'),
        ];
    }
}
