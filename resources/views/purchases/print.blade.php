@extends('layouts.print')

@section('title', 'Pembelian')

@section('content')
<div class="print-meta"><div><strong>FAKTUR PEMBELIAN — {{ $purchase->invoice_number }}</strong></div><div>{{ now()->format('d/m/Y H:i') }}</div></div>
<table style="border:none;margin-bottom:15px">
    <tr><td width="120"><strong>Supplier</strong></td><td>{{ $purchase->supplier->name ?? '-' }}</td></tr>
    <tr><td><strong>Tanggal</strong></td><td>{{ $purchase->purchase_date?->format('d/m/Y') }}</td></tr>
    <tr><td><strong>Petugas</strong></td><td>{{ $purchase->user->name ?? '-' }}</td></tr>
</table>
<table>
    <thead><tr><th>Spare Part</th><th class="text-center">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr></thead>
    <tbody>
        @foreach($purchase->items as $item)
        <tr>
            <td>{{ $item->sparePart->name ?? '-' }}</td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr><td colspan="3" class="text-end fw-bold">Total</td><td class="text-end fw-bold">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td></tr>
    </tbody>
</table>
@endsection
