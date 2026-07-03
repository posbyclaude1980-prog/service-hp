<?php

namespace App\Exports;

use App\Models\PhoneModel;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PhoneModelExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return PhoneModel::query()
            ->with('brand')
            ->when(
                $this->filters['brand_id'] ?? null,
                fn (Builder $q, int $brandId) => $q->where('brand_id', $brandId)
            )
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
        return ['Merek', 'Model', 'Dibuat'];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($phoneModel): array
    {
        return [
            $phoneModel->brand?->name,
            $phoneModel->name,
            $phoneModel->created_at?->format('d/m/Y H:i'),
        ];
    }
}
