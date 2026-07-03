<?php

namespace App\Exports;

use App\Models\SparePart;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockReportExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        $query = SparePart::query()->filter($this->filters)->orderBy('name');

        if (isset($this->filters['low_stock']) && filter_var($this->filters['low_stock'], FILTER_VALIDATE_BOOL)) {
            $query->lowStock();
        }

        if (isset($this->filters['out_of_stock']) && filter_var($this->filters['out_of_stock'], FILTER_VALIDATE_BOOL)) {
            $query->where('stock', '<=', 0);
        }

        return $query;
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['Kode', 'Nama', 'Stok', 'Stok Min', 'Status', 'Harga Beli', 'Harga Jual'];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($sparePart): array
    {
        $status = 'Normal';

        if ($sparePart->stock <= 0) {
            $status = 'Habis';
        } elseif ($sparePart->isLowStock()) {
            $status = 'Stok Rendah';
        }

        return [
            $sparePart->code,
            $sparePart->name,
            $sparePart->stock,
            $sparePart->min_stock,
            $status,
            $sparePart->purchase_price,
            $sparePart->selling_price,
        ];
    }
}
