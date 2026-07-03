@extends('layouts.dotmatrix')

@section('content')
@include('prints.partials.shop-header')

<div class="dm-title">KARTU GARANSI SERVICE HP</div>
<div class="dm-center dm-small">No: {{ $serviceOrder->ticket_number }}</div>

<hr class="dm-line">

<table class="dm-kv">
    <tr><td class="dm-k">Pelanggan</td><td class="dm-c">:</td><td class="dm-v dm-bold">{{ $serviceOrder->customer?->name }}</td></tr>
    <tr><td class="dm-k">Telepon</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->contact_phone ?: $serviceOrder->customer?->phone }}</td></tr>
    <tr><td class="dm-k">Perangkat</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->device_brand }} {{ $serviceOrder->device_model }}</td></tr>
    <tr><td class="dm-k">IMEI</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->imei ?: '-' }}</td></tr>
    <tr><td class="dm-k">Tgl Masuk</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->entry_date?->format('d/m/Y') }}</td></tr>
    <tr><td class="dm-k">Tgl Selesai</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->completion_date?->format('d/m/Y') ?: '-' }}</td></tr>
    <tr><td class="dm-k">Tgl Diambil</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->pickup_date?->format('d/m/Y') ?: '-' }}</td></tr>
    <tr><td class="dm-k">Mulai Garansi</td><td class="dm-c">:</td><td class="dm-v dm-bold">{{ $serviceOrder->warranty_start_date?->format('d/m/Y') ?: '(saat diambil)' }}</td></tr>
    <tr><td class="dm-k">Masa Garansi</td><td class="dm-c">:</td><td class="dm-v dm-bold">{{ $serviceOrder->warranty_days }} HARI</td></tr>
    <tr><td class="dm-k">Teknisi</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->technician?->user?->name ?? '-' }}</td></tr>
</table>

@if($serviceOrder->diagnosis)
<hr class="dm-line dm-line-dashed">
<div class="dm-bold">PEKERJAAN / DIAGNOSA:</div>
<div class="dm-preline">{{ $serviceOrder->diagnosis }}</div>
@endif

<hr class="dm-line">

<div class="dm-small dm-preline">
SYARAT GARANSI:
- Kartu garansi wajib dibawa saat klaim.
- Garansi berlaku untuk kerusakan yang sama pada komponen yang diganti/diperbaiki.
- Garansi TIDAK berlaku untuk kerusakan akibat benturan, air, atau kesalahan pengguna.
- Garansi void jika segel / stiker rusak.
</div>

<div class="dm-sign">
    <table>
        <tr>
            <td>Customer Service<br><br><br>________________</td>
            <td>Pelanggan<br><br><br>________________</td>
        </tr>
    </table>
</div>

<div class="dm-center dm-small">Dicetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
@endsection
