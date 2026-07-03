@extends('layouts.dotmatrix')

@push('styles')
<style>
    .label-box {
        border: 2px solid #000;
        padding: 8px;
        margin: 4px 0;
    }
    .label-ticket { font-size: 16pt; font-weight: bold; }
</style>
@endpush

@section('content')
<div class="label-box">
    <div class="label-ticket">{{ $serviceOrder->ticket_number }}</div>
    <div class="dm-bold">{{ $settings['shop_name'] ?? 'SERVICE HP' }}</div>
    <hr class="dm-line">
    <table class="dm-kv dm-small">
        <tr><td class="dm-k">Nama</td><td class="dm-c">:</td><td class="dm-v dm-bold">{{ $serviceOrder->customer?->name }}</td></tr>
        <tr><td class="dm-k">HP</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->contact_phone ?: $serviceOrder->customer?->phone }}</td></tr>
        <tr><td class="dm-k">Unit</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->device_brand }} {{ $serviceOrder->device_model }}</td></tr>
        <tr><td class="dm-k">IMEI</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->imei ?: '-' }}</td></tr>
        <tr><td class="dm-k">Masuk</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->entry_date?->format('d/m/Y') }}</td></tr>
        <tr><td class="dm-k">Status</td><td class="dm-c">:</td><td class="dm-v">{{ $serviceOrder->status?->label() }}</td></tr>
    </table>
    @if($serviceOrder->complaint)
    <hr class="dm-line dm-line-dashed">
    <div class="dm-small">{{ \Illuminate\Support\Str::limit($serviceOrder->complaint, 60) }}</div>
    @endif
</div>
<div class="dm-center dm-small">Tempel pada unit / amplop</div>
@endsection
