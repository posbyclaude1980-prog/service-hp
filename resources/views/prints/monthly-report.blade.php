@extends('layouts.dotmatrix')

@section('content')
@php
    $dm = app(\App\Services\DotMatrixPrintService::class);
    $monthName = $period->translatedFormat('F Y');
@endphp
@include('prints.partials.shop-header')

<div class="dm-title">LAPORAN BULANAN SERVICE</div>
<div class="dm-center">Periode: {{ $monthName }}</div>
<div class="dm-center dm-small">{{ $start->format('d/m/Y') }} s/d {{ $end->format('d/m/Y') }}</div>

<hr class="dm-line">

<div class="dm-bold">RINGKASAN BULANAN</div>
<table class="dm-kv">
    <tr><td class="dm-k">Total Service</td><td class="dm-c">:</td><td class="dm-v">{{ $summary['order_count'] }} unit</td></tr>
    <tr><td class="dm-k">Selesai</td><td class="dm-c">:</td><td class="dm-v">{{ $summary['completed'] }} unit</td></tr>
    <tr><td class="dm-k">Dibatalkan</td><td class="dm-c">:</td><td class="dm-v">{{ $summary['cancelled'] }} unit</td></tr>
    <tr><td class="dm-k">Nilai Service</td><td class="dm-c">:</td><td class="dm-v">{{ $dm->money($summary['order_value']) }}</td></tr>
    <tr><td class="dm-k">Total Pembayaran</td><td class="dm-c">:</td><td class="dm-v">{{ $summary['payment_count'] }} trx / {{ $dm->money($summary['payment_total']) }}</td></tr>
</table>

@if($dailyPayments->isNotEmpty())
<hr class="dm-line dm-line-dashed">
<div class="dm-bold">PENDAPATAN PER HARI</div>
<table class="dm-table dm-items">
    <thead><tr><th>Tanggal</th><th class="dm-right">Total</th></tr></thead>
    <tbody>
        @foreach($dailyPayments as $day => $total)
        <tr>
            <td>{{ \Carbon\Carbon::parse($day)->format('d/m/Y') }}</td>
            <td class="dm-right">{{ number_format($total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<hr class="dm-line">

<div class="dm-bold">DAFTAR SERVICE</div>
<table class="dm-table dm-items">
    <thead>
        <tr>
            <th>Tgl</th>
            <th>No.Tiket</th>
            <th>Pelanggan</th>
            <th>Status</th>
            <th class="dm-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>{{ $order->entry_date?->format('d/m') }}</td>
            <td>{{ $order->ticket_number }}</td>
            <td>{{ \Illuminate\Support\Str::limit($order->customer?->name, 16) }}</td>
            <td>{{ $order->status?->label() }}</td>
            <td class="dm-right">{{ number_format($order->total_cost, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr><td colspan="5" class="dm-center">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="dm-right dm-bold">GRAND TOTAL</td>
            <td class="dm-right dm-bold">{{ number_format($summary['order_value'], 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

<div class="dm-center dm-small" style="margin-top:12px">Dicetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
@endsection
