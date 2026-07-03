<?php

namespace App\Exports;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SupplierExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return Supplier::query()->filter($this->filters)->latest();
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['Kode', 'Nama', 'Kontak', 'Telepon', 'Alamat', 'Dibuat'];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($supplier): array
    {
        return [
            $supplier->code,
            $supplier->name,
            $supplier->contact,
            $supplier->phone,
            $supplier->address,
            $supplier->created_at?->format('d/m/Y H:i'),
        ];
    }
}
