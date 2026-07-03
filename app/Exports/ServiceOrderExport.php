<?php

namespace App\Exports;

use App\Models\ServiceOrder;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServiceOrderExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return ServiceOrder::query()
            ->with(['customer', 'technician', 'phoneModel'])
            ->filter($this->filters)
            ->latest('entry_date')
            ->latest('id');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'No. Tiket', 'Pelanggan', 'Telepon', 'Perangkat', 'Status',
            'Teknisi', 'Biaya Servis', 'Sparepart', 'Total', 'Dibayar', 'Tgl Masuk',
        ];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($order): array
    {
        $device = trim(($order->device_brand ?? '').' '.($order->device_model ?? ''));

        if ($device === '' && $order->phoneModel) {
            $device = $order->phoneModel->name;
        }

        return [
            $order->ticket_number,
            $order->customer?->name,
            $order->customer?->phone,
            $device,
            $order->status?->label(),
            $order->technician?->name,
            $order->service_fee,
            $order->parts_total,
            $order->total_cost,
            $order->paid_amount,
            $order->entry_date?->format('d/m/Y'),
        ];
    }
}
