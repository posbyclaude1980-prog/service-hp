@extends('layouts.pdf')

@section('title', 'Laporan Pendapatan')

@section('content')
<div class="pdf-meta">
    <strong>LAPORAN PENDAPATAN</strong><br>
    Periode: {{ request('date_from', $dateFrom ?? '-') }} s/d {{ request('date_to', $dateTo ?? '-') }}<br>
    Total: Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }} | Transaksi: {{ $summary['total_transactions'] ?? 0 }}
</div>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>No. Tiket</th>
            <th>Pelanggan</th>
            <th>Metode</th>
            <th class="text-end">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments ?? [] as $payment)
        <tr>
            <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
            <td>{{ $payment->serviceOrder->ticket_number ?? '-' }}</td>
            <td>{{ $payment->serviceOrder->customer->name ?? '-' }}</td>
            <td>{{ $payment->method?->label() }}</td>
            <td class="text-end">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4" class="text-end fw-bold">Total</td>
            <td class="text-end fw-bold">Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>
@endsection
