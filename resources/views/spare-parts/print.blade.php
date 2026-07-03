@extends('layouts.print')

@section('title', 'Spare Part')

@section('content')
<div class="print-meta"><div><strong>Spare Part: {{ $sparePart->name }}</strong></div><div>{{ now()->format('d/m/Y H:i') }}</div></div>
<table>
    <tr><th width="140">Kode</th><td>{{ $sparePart->code }}</td></tr>
    <tr><th>Nama</th><td>{{ $sparePart->name }}</td></tr>
    <tr><th>Stok</th><td>{{ $sparePart->stock }} (Min: {{ $sparePart->min_stock }})</td></tr>
    <tr><th>Harga Beli</th><td>Rp {{ number_format($sparePart->purchase_price, 0, ',', '.') }}</td></tr>
    <tr><th>Harga Jual</th><td>Rp {{ number_format($sparePart->selling_price, 0, ',', '.') }}</td></tr>
    <tr><th>Deskripsi</th><td>{{ $sparePart->description ?: '-' }}</td></tr>
</table>
@endsection
