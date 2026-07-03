@extends('layouts.pdf')

@section('title', 'Bukti Pembayaran')

@section('content')
<p class="fw-bold mb-1">BUKTI PEMBAYARAN</p>
<table>
    <tr><th width="120">Tanggal</th><td>{{ $payment->payment_date?->format('d/m/Y') }}</td></tr>
    <tr><th>No. Tiket</th><td>{{ $payment->serviceOrder->ticket_number ?? '-' }}</td></tr>
    <tr><th>Pelanggan</th><td>{{ $payment->serviceOrder->customer->name ?? '-' }}</td></tr>
    <tr><th>Jumlah</th><td class="fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td></tr>
    <tr><th>Metode</th><td>{{ $payment->method?->label() }}</td></tr>
    <tr><th>Kasir</th><td>{{ $payment->user->name ?? '-' }}</td></tr>
</table>
@endsection
