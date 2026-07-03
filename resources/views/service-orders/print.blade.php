@extends('layouts.print')

@section('title', 'Service Order — '.$serviceOrder->ticket_number)

@section('content')
<div class="print-meta">
    <div><strong>NOTA SERVICE — {{ $serviceOrder->ticket_number }}</strong></div>
    <div>{{ now()->format('d/m/Y H:i') }}</div>
</div>

<div class="row mb-2">
    <div class="col">
        <table style="border:none">
            <tr><td width="120"><strong>Pelanggan</strong></td><td>{{ $serviceOrder->customer->name ?? '-' }}</td></tr>
            <tr><td><strong>Telepon</strong></td><td>{{ $serviceOrder->customer->phone ?? '-' }}</td></tr>
            <tr><td><strong>Perangkat</strong></td><td>{{ $serviceOrder->device_brand }} {{ $serviceOrder->device_model }}</td></tr>
            <tr><td><strong>IMEI</strong></td><td>{{ $serviceOrder->imei ?: '-' }}</td></tr>
            <tr><td><strong>Status</strong></td><td>{{ $serviceOrder->status_label }}</td></tr>
        </table>
    </div>
    <div class="col">
        <table style="border:none">
            <tr><td width="120"><strong>Tgl Masuk</strong></td><td>{{ $serviceOrder->entry_date?->format('d/m/Y') }}</td></tr>
            <tr><td><strong>Teknisi</strong></td><td>{{ $serviceOrder->technician->name ?? '-' }}</td></tr>
            <tr><td><strong>Garansi</strong></td><td>{{ $serviceOrder->warranty_days }} hari</td></tr>
        </table>
    </div>
</div>

<p class="mb-2"><strong>Keluhan:</strong> {{ $serviceOrder->complaint }}</p>
@if($serviceOrder->diagnosis)<p class="mb-2"><strong>Diagnosis:</strong> {{ $serviceOrder->diagnosis }}</p>@endif

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
        <tr><td colspan="3" class="text-end fw-bold">Biaya Jasa</td><td class="text-end">Rp {{ number_format($serviceOrder->service_fee, 0, ',', '.') }}</td></tr>
        <tr><td colspan="3" class="text-end fw-bold">Total</td><td class="text-end fw-bold">Rp {{ number_format($serviceOrder->total_cost, 0, ',', '.') }}</td></tr>
        <tr><td colspan="3" class="text-end">Dibayar</td><td class="text-end">Rp {{ number_format($serviceOrder->paid_amount, 0, ',', '.') }}</td></tr>
        <tr><td colspan="3" class="text-end fw-bold">Sisa</td><td class="text-end fw-bold">Rp {{ number_format($serviceOrder->remaining_payment, 0, ',', '.') }}</td></tr>
    </tbody>
</table>

<div class="signature-area">
    <div class="signature-box"><div class="signature-line">Pelanggan</div></div>
    <div class="signature-box"><div class="signature-line">Petugas</div></div>
</div>
@endsection
