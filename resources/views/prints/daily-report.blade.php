@extends('layouts.dotmatrix')

@section('content')
@php $dm = app(\App\Services\DotMatrixPrintService::class); @endphp
@include('prints.partials.shop-header')

<div class="dm-title">LAPORAN HARIAN SERVICE</div>
<div class="dm-center">Tanggal: {{ $date->format('d/m/Y') }} ({{ $date->translatedFormat('l') }})</div>

<hr class="dm-line">

<div class="dm-bold">RINGKASAN</div>
<table class="dm-kv">
    <tr><td class="dm-k">Service Masuk</td><td class="dm-c">:</td><td class="dm-v">{{ $summary['order_count'] }} unit</td></tr>
    <tr><td class="dm-k">Masih Proses</td><td class="dm-c">:</td><td class="dm-v">{{ $summary['in_progress'] }} unit</td></tr>
    <tr><td class="dm-k">Selesai</td><td class="dm-c">:</td><td class="dm-v">{{ $summary['completed'] }} unit</td></tr>
    <tr><td class="dm-k">Nilai Service</td><td class="dm-c">:</td><td class="dm-v">{{ $dm->money($summary['order_value']) }}</td></tr>
    <tr><td class="dm-k">Pembayaran</td><td class="dm-c">:</td><td class="dm-v">{{ $summary['payment_count'] }} trx / {{ $dm->money($summary['payment_total']) }}</td></tr>
</table>

<hr class="dm-line">

<div class="dm-bold">DETAIL SERVICE MASUK</div>
<table class="dm-table dm-items">
    <thead>
        <tr>
            <th>No.Tiket</th>
            <th>Pelanggan</th>
            <th>Perangkat</th>
            <th>Status</th>
            <th class="dm-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>{{ $order->ticket_number }}</td>
            <td>{{ \Illuminate\Support\Str::limit($order->customer?->name, 18) }}</td>
            <td>{{ \Illuminate\Support\Str::limit($order->device_brand.' '.$order->device_model, 16) }}</td>
            <td>{{ $order->status?->label() }}</td>
            <td class="dm-right">{{ number_format($order->total_cost, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="dm-center">Tidak ada service masuk.</td></tr>
        @endforelse
    </tbody>
</table>

@if($payments->isNotEmpty())
<hr class="dm-line">

<div class="dm-bold">DETAIL PEMBAYARAN</div>
<table class="dm-table dm-items">
    <thead>
        <tr>
            <th>No.Tiket</th>
            <th>Pelanggan</th>
            <th>Metode</th>
            <th class="dm-right">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->serviceOrder?->ticket_number ?? '-' }}</td>
            <td>{{ \Illuminate\Support\Str::limit($payment->serviceOrder?->customer?->name, 20) }}</td>
            <td>{{ $payment->method?->label() ?? $payment->method }}</td>
            <td class="dm-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="dm-right dm-bold">TOTAL</td>
            <td class="dm-right dm-bold">{{ number_format($summary['payment_total'], 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
@endif

<div class="dm-center dm-small" style="margin-top:12px">Dicetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
@endsection
