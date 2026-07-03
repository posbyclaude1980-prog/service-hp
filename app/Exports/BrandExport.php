<?php

namespace App\Exports;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BrandExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return Brand::query()
            ->when(
                $this->filters['search'] ?? null,
                fn (Builder $q, string $search) => $q->where('name', 'like', "%{$search}%")
            )
            ->latest();
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['Nama', 'Jumlah Model', 'Dibuat'];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($brand): array
    {
        return [
            $brand->name,
            $brand->phone_models_count ?? $brand->phoneModels()->count(),
            $brand->created_at?->format('d/m/Y H:i'),
        ];
    }
}
