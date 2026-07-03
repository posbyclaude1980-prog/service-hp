@extends('layouts.pdf')

@section('title', 'Data Pelanggan')

@section('content')
<div class="pdf-meta">
    <strong>Data Pelanggan</strong> — Dicetak: {{ now()->format('d/m/Y H:i') }}
</div>

<table>
    <tr><th width="120">Kode</th><td>{{ $customer->code }}</td></tr>
    <tr><th>Nama</th><td>{{ $customer->name }}</td></tr>
    <tr><th>Telepon</th><td>{{ $customer->phone }}</td></tr>
    <tr><th>Alamat</th><td>{{ $customer->address ?: '-' }}</td></tr>
</table>

@if(($customer->serviceOrders ?? collect())->isNotEmpty())
<p class="fw-bold mt-2 mb-1">Riwayat Service</p>
<table>
    <thead>
        <tr>
            <th>No. Tiket</th>
            <th>Perangkat</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th class="text-end">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customer->serviceOrders as $order)
        <tr>
            <td>{{ $order->ticket_number }}</td>
            <td>{{ $order->device_brand }} {{ $order->device_model }}</td>
            <td>{{ $order->status_label }}</td>
            <td>{{ $order->entry_date?->format('d/m/Y') }}</td>
            <td class="text-end">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
