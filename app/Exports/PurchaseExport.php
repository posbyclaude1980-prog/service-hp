<?php

namespace App\Exports;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchaseExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return Purchase::query()
            ->with(['supplier', 'user'])
            ->filter($this->filters)
            ->latest('purchase_date')
            ->latest('id');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['No. Invoice', 'Supplier', 'Tanggal', 'Total', 'Petugas', 'Catatan'];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($purchase): array
    {
        return [
            $purchase->invoice_number,
            $purchase->supplier?->name,
            $purchase->purchase_date?->format('d/m/Y'),
            $purchase->total,
            $purchase->user?->name,
            $purchase->notes,
        ];
    }
}
