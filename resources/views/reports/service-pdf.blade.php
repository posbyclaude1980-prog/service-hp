@extends('layouts.pdf')

@section('title', 'Laporan Service')

@section('content')
<div class="pdf-meta">
    <strong>LAPORAN SERVICE</strong><br>
    Periode: {{ request('date_from', $dateFrom ?? '-') }} s/d {{ request('date_to', $dateTo ?? '-') }}<br>
    Dicetak: {{ now()->format('d/m/Y H:i') }}
</div>

<table class="summary-table mb-2" style="width:100%">
    <tr>
        <td>Total Service: <strong>{{ $summary['total'] ?? 0 }}</strong></td>
        <td>Selesai: <strong>{{ $summary['completed'] ?? 0 }}</strong></td>
        <td>Total Nilai: <strong>Rp {{ number_format($summary['total_value'] ?? 0, 0, ',', '.') }}</strong></td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>No. Tiket</th>
            <th>Pelanggan</th>
            <th>Perangkat</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th class="text-end">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders ?? [] as $order)
        <tr>
            <td>{{ $order->ticket_number }}</td>
            <td>{{ $order->customer->name ?? '-' }}</td>
            <td>{{ $order->device_brand }} {{ $order->device_model }}</td>
            <td>{{ $order->status_label }}</td>
            <td>{{ $order->entry_date?->format('d/m/Y') }}</td>
            <td class="text-end">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
