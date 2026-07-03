@extends('layouts.print')

@section('title', 'Label ' . $serviceOrder->ticket_number)

@push('styles')
<style>
    body { margin: 8px; }
    .label { border: 2px solid #000; padding: 12px; width: 280px; }
    .ticket { font-size: 18px; font-weight: bold; }
</style>
@endpush

@section('content')
    <div class="label">
        <div class="ticket">{{ $serviceOrder->ticket_number }}</div>
        <div>{{ $settings['shop_name'] ?? 'Service HP' }}</div>
        <hr>
        <div><strong>{{ $serviceOrder->customer?->name }}</strong></div>
        <div>{{ $serviceOrder->customer?->phone }}</div>
        <div>{{ $serviceOrder->device_brand }} {{ $serviceOrder->device_model }}</div>
        <div>IMEI: {{ $serviceOrder->imei ?? '-' }}</div>
        <div>Tgl: {{ $serviceOrder->entry_date?->format('d/m/Y') }}</div>
        <div>Status: {{ $serviceOrder->status?->label() }}</div>
    </div>
@endsection
