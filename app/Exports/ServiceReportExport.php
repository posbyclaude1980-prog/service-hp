<?php

namespace App\Exports;

use App\Models\ServiceOrder;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServiceReportExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return ServiceOrder::query()
            ->with(['customer', 'technician'])
            ->filter($this->filters)
            ->latest('entry_date');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['No. Tiket', 'Pelanggan', 'Status', 'Teknisi', 'Total', 'Tgl Masuk', 'Tgl Selesai'];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($order): array
    {
        return [
            $order->ticket_number,
            $order->customer?->name,
            $order->status?->label(),
            $order->technician?->name,
            $order->total_cost,
            $order->entry_date?->format('d/m/Y'),
            $order->completion_date?->format('d/m/Y'),
        ];
    }
}
