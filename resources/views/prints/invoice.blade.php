@extends('layouts.dotmatrix')

@section('content')
@php $dm = app(\App\Services\DotMatrixPrintService::class); @endphp
@include('prints.partials.shop-header')

<div class="dm-title">INVOICE / NOTA SERVICE</div>
<table class="dm-kv" style="margin-top:8px">
    <tr>
        <td class="dm-k">No. Invoice</td><td class="dm-c">:</td><td class="dm-v dm-bold">{{ $serviceOrder->ticket_number }}</td>
        <td class="dm-k">Tanggal</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->entry_date?->format('d/m/Y') }}</td>
    </tr>
</table>

<hr class="dm-line">

<table class="dm-kv">
    <tr><td class="dm-k">Pelanggan</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->customer?->name }}</td></tr>
    <tr><td class="dm-k">Telepon</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->contact_phone ?: $serviceOrder->customer?->phone }}</td></tr>
    <tr><td class="dm-k">Perangkat</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->device_brand }} {{ $serviceOrder->device_model }}</td></tr>
    <tr><td class="dm-k">IMEI</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->imei ?: '-' }}</td></tr>
    <tr><td class="dm-k">Teknisi</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->technician?->user?->name ?? '-' }}</td></tr>
</table>

<hr class="dm-line">

<table class="dm-table dm-items">
    <thead>
        <tr>
            <th style="width:50%">Keterangan</th>
            <th style="width:10%" class="dm-center">Qty</th>
            <th style="width:20%" class="dm-right">Harga</th>
            <th style="width:20%" class="dm-right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Biaya Jasa Service</td>
            <td class="dm-center">1</td>
            <td class="dm-right">{{ number_format($serviceOrder->service_fee, 0, ',', '.') }}</td>
            <td class="dm-right">{{ number_format($serviceOrder->service_fee, 0, ',', '.') }}</td>
        </tr>
        @foreach($serviceOrder->items as $item)
        <tr>
            <td>{{ $item->sparePart?->name ?? 'Spare Part' }}</td>
            <td class="dm-center">{{ $item->quantity }}</td>
            <td class="dm-right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
            <td class="dm-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="dm-right dm-bold">TOTAL</td>
            <td class="dm-right dm-bold">{{ number_format($serviceOrder->total_cost, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" class="dm-right">Dibayar</td>
            <td class="dm-right">{{ number_format($serviceOrder->paid_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" class="dm-right dm-bold">SISA</td>
            <td class="dm-right dm-bold">{{ number_format($serviceOrder->remaining_payment, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

@if($serviceOrder->payments->isNotEmpty())
<hr class="dm-line dm-line-dashed">
<div class="dm-bold">RIWAYAT PEMBAYARAN:</div>
<table class="dm-table dm-items">
    <thead><tr><th>Tanggal</th><th>Metode</th><th class="dm-right">Jumlah</th></tr></thead>
    <tbody>
        @foreach($serviceOrder->payments as $payment)
        <tr>
            <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
            <td>{{ $payment->method?->label() ?? $payment->method }}</td>
            <td class="dm-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@if(!empty($settings['invoice_footer']))
<hr class="dm-line">
<div class="dm-center dm-small">{{ $settings['invoice_footer'] }}</div>
@endif

<div class="dm-sign">
    <table>
        <tr>
            <td>Pelanggan</td>
            <td>Kasir</td>
        </tr>
    </table>
</div>

<div class="dm-center dm-small">Dicetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
@endsection
