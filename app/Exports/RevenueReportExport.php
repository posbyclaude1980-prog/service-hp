<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueReportExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function __construct(private readonly array $filters = []) {}

    public function query(): Builder
    {
        return Payment::query()
            ->with(['serviceOrder.customer', 'user'])
            ->when(
                $this->filters['date_from'] ?? null,
                fn (Builder $q, string $dateFrom) => $q->whereDate('payment_date', '>=', $dateFrom)
            )
            ->when(
                $this->filters['date_to'] ?? null,
                fn (Builder $q, string $dateTo) => $q->whereDate('payment_date', '<=', $dateTo)
            )
            ->when(
                $this->filters['method'] ?? null,
                function (Builder $q, string $method): void {
                    if (Schema::hasColumn('payments', 'method')) {
                        $q->where('method', $method);
                        return;
                    }

                    $q->whereHas('paymentMethod', fn (Builder $subQ) => $subQ->where('code', $method));
                }
            )
            ->latest('payment_date');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['Tanggal', 'No. Tiket', 'Pelanggan', 'Jumlah', 'Metode', 'Petugas', 'Catatan'];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($payment): array
    {
        return [
            $payment->payment_date?->format('d/m/Y'),
            $payment->serviceOrder?->ticket_number,
            $payment->serviceOrder?->customer?->name,
            $payment->amount,
            $payment->method_label,
            $payment->user?->name,
            $payment->notes,
        ];
    }
}
