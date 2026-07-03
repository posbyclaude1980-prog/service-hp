@extends('layouts.print')

@section('title', 'Data Pelanggan')

@section('content')
<div class="print-meta">
    <div><strong>Data Pelanggan</strong></div>
    <div>Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
</div>

<table>
    <tr><th width="150">Kode</th><td>{{ $customer->code }}</td></tr>
    <tr><th>Nama</th><td>{{ $customer->name }}</td></tr>
    <tr><th>Telepon</th><td>{{ $customer->phone }}</td></tr>
    <tr><th>Alamat</th><td>{{ $customer->address ?: '-' }}</td></tr>
    <tr><th>Terdaftar</th><td>{{ $customer->created_at?->format('d/m/Y') }}</td></tr>
</table>

@if(($customer->serviceOrders ?? collect())->isNotEmpty())
<h4 class="mb-2 mt-3">Riwayat Service</h4>
<table>
    <thead>
        <tr>
            <th>No. Tiket</th>
            <th>Perangkat</th>
            <th>Status</th>
            <th>Tanggal Masuk</th>
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
