@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
@include('partials.page-header', [
    'title' => 'Detail Pembayaran',
    'breadcrumbs' => ['Pembayaran' => route('payments.index'), 'Detail' => '#'],
    'actions' => '
        <a href="'.route('payments.print', $payment).'" class="btn btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i> Cetak</a>
        <a href="'.route('payments.pdf', $payment).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> PDF</a>
        <a href="'.route('payments.edit', $payment).'" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    '
])

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><td class="text-muted" width="160">Tanggal Pembayaran</td><td class="fw-semibold">{{ $payment->payment_date?->format('d/m/Y') }}</td></tr>
            <tr><td class="text-muted">No. Tiket</td><td><a href="{{ route('service-orders.show', $payment->service_order_id) }}">{{ $payment->serviceOrder->ticket_number ?? '-' }}</a></td></tr>
            <tr><td class="text-muted">Pelanggan</td><td>{{ $payment->serviceOrder->customer->name ?? '-' }}</td></tr>
            <tr><td class="text-muted">Jumlah</td><td class="fs-5 fw-bold text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td></tr>
            <tr><td class="text-muted">Metode</td><td>{{ $payment->method?->label() }}</td></tr>
            <tr><td class="text-muted">Kasir</td><td>{{ $payment->user->name ?? '-' }}</td></tr>
            <tr><td class="text-muted">Catatan</td><td>{{ $payment->notes ?: '-' }}</td></tr>
        </table>
    </div>
</div>
@endsection
