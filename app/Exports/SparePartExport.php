<?php

namespace App\Exports;

use App\Models\SparePart;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SparePartExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return SparePart::query()->filter($this->filters)->latest('name');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['Kode', 'Nama', 'Stok', 'Stok Min', 'Harga Beli', 'Harga Jual', 'Deskripsi'];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($sparePart): array
    {
        return [
            $sparePart->code,
            $sparePart->name,
            $sparePart->stock,
            $sparePart->min_stock,
            $sparePart->purchase_price,
            $sparePart->selling_price,
            $sparePart->description,
        ];
    }
}
