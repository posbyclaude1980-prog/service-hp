@extends('layouts.print')

@section('title', 'Bukti Pembayaran')

@section('content')
<div class="print-meta"><div><strong>BUKTI PEMBAYARAN</strong></div><div>{{ now()->format('d/m/Y H:i') }}</div></div>
<table>
    <tr><th width="160">Tanggal</th><td>{{ $payment->payment_date?->format('d/m/Y') }}</td></tr>
    <tr><th>No. Tiket</th><td>{{ $payment->serviceOrder->ticket_number ?? '-' }}</td></tr>
    <tr><th>Pelanggan</th><td>{{ $payment->serviceOrder->customer->name ?? '-' }}</td></tr>
    <tr><th>Jumlah</th><td class="fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td></tr>
    <tr><th>Metode</th><td>{{ $payment->method?->label() }}</td></tr>
    <tr><th>Kasir</th><td>{{ $payment->user->name ?? '-' }}</td></tr>
    <tr><th>Catatan</th><td>{{ $payment->notes ?: '-' }}</td></tr>
</table>
<div class="signature-area"><div class="signature-box"><div class="signature-line">Kasir</div></div><div class="signature-box"><div class="signature-line">Pelanggan</div></div></div>
@endsection
