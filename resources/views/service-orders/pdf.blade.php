@extends('layouts.pdf')

@section('title', 'Service Order — '.$serviceOrder->ticket_number)

@section('content')
<div class="pdf-meta">
    <strong>NOTA SERVICE — {{ $serviceOrder->ticket_number }}</strong><br>
    Pelanggan: {{ $serviceOrder->customer->name ?? '-' }} | {{ $serviceOrder->customer->phone ?? '-' }}<br>
    Perangkat: {{ $serviceOrder->device_brand }} {{ $serviceOrder->device_model }} | Status: {{ $serviceOrder->status_label }}
</div>

<p class="mb-1"><strong>Keluhan:</strong> {{ $serviceOrder->complaint }}</p>

<table>
    <thead>
        <tr><th>Spare Part</th><th class="text-center">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr>
    </thead>
    <tbody>
        @foreach($serviceOrder->items as $item)
        <tr>
            <td>{{ $item->sparePart->name ?? '-' }}</td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table class="summary-table" style="width:50%;margin-left:auto">
    <tr><td>Biaya Jasa</td><td>Rp {{ number_format($serviceOrder->service_fee, 0, ',', '.') }}</td></tr>
    <tr><td class="fw-bold">Total</td><td class="fw-bold">Rp {{ number_format($serviceOrder->total_cost, 0, ',', '.') }}</td></tr>
    <tr><td>Dibayar</td><td>Rp {{ number_format($serviceOrder->paid_amount, 0, ',', '.') }}</td></tr>
    <tr><td class="fw-bold">Sisa</td><td class="fw-bold">Rp {{ number_format($serviceOrder->remaining_payment, 0, ',', '.') }}</td></tr>
</table>
@endsection
