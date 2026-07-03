@extends('layouts.pdf')

@section('title', 'Pembelian')

@section('content')
<p class="fw-bold mb-1">FAKTUR PEMBELIAN — {{ $purchase->invoice_number }}</p>
<p class="pdf-meta mb-1">Supplier: {{ $purchase->supplier->name ?? '-' }} | {{ $purchase->purchase_date?->format('d/m/Y') }}</p>
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
    </tbody>
</table>
<table class="summary-table" style="width:40%;margin-left:auto">
    <tr><td class="fw-bold">Total</td><td class="fw-bold">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td></tr>
</table>
@endsection
