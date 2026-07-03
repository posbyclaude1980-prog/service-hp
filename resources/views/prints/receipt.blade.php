@extends('layouts.dotmatrix')

@section('content')
@include('prints.partials.shop-header')

<div class="dm-title">BUKTI PENERIMAAN SERVICE HP</div>
<div class="dm-center dm-small">No: {{ $serviceOrder->ticket_number }}</div>
<div class="dm-center dm-small">Tanggal: {{ $serviceOrder->entry_date?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</div>

<hr class="dm-line">

<table class="dm-kv">
    <tr><td class="dm-k">Pelanggan</td><td class="dm-c">:</td><td class="dm-v dm-bold">{{ $serviceOrder->customer?->name }}</td></tr>
    <tr><td class="dm-k">No. HP</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->contact_phone ?: $serviceOrder->customer?->phone }}</td></tr>
    <tr><td class="dm-k">Merk / Model</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->device_brand }} {{ $serviceOrder->device_model }}</td></tr>
    <tr><td class="dm-k">Warna</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->color ?: '-' }}</td></tr>
    <tr><td class="dm-k">IMEI</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->imei ?: '-' }}</td></tr>
    <tr><td class="dm-k">Password</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->device_password ?: '-' }}</td></tr>
    <tr><td class="dm-k">Teknisi</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->technician?->user?->name ?? '-' }}</td></tr>
    <tr><td class="dm-k">Status</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->status?->label() }}</td></tr>
</table>

<hr class="dm-line">

<div class="dm-bold">KELUHAN:</div>
<div class="dm-preline">{{ $serviceOrder->complaint }}</div>

@if($serviceOrder->completeness)
<div class="dm-bold" style="margin-top:8px">KELENGKAPAN:</div>
<div class="dm-preline">{{ $serviceOrder->completeness }}</div>
@endif

@if($serviceOrder->estimated_cost > 0)
<hr class="dm-line dm-line-dashed">
<div>Estimasi Biaya: <span class="dm-bold">{{ app(\App\Services\DotMatrixPrintService::class)->money($serviceOrder->estimated_cost) }}</span></div>
@endif

@if($serviceOrder->notes)
<div style="margin-top:8px" class="dm-small">Catatan: {{ $serviceOrder->notes }}</div>
@endif

<hr class="dm-line">

<div class="dm-small">
    * Simpan bukti ini sebagai tanda terima.<br>
    * Service belum dapat diambil sebelum status SELESAI.<br>
    * Garansi berlaku {{ $serviceOrder->warranty_days }} hari setelah pengambilan.
</div>

<div class="dm-sign">
    <table>
        <tr>
            <td>Pelanggan<br><br><br>________________</td>
            <td>Petugas<br><br><br>________________</td>
        </tr>
    </table>
</div>

<div class="dm-center dm-small" style="margin-top:12px">Dicetak: {{ $printedAt->format('d/m/Y H:i') }}</div>
@endsection
