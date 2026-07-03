@extends('layouts.pdf')

@section('title', 'Spare Part')

@section('content')
<table>
    <tr><th width="120">Kode</th><td>{{ $sparePart->code }}</td></tr>
    <tr><th>Nama</th><td>{{ $sparePart->name }}</td></tr>
    <tr><th>Stok</th><td>{{ $sparePart->stock }} (Min: {{ $sparePart->min_stock }})</td></tr>
    <tr><th>Harga Beli</th><td>Rp {{ number_format($sparePart->purchase_price, 0, ',', '.') }}</td></tr>
    <tr><th>Harga Jual</th><td>Rp {{ number_format($sparePart->selling_price, 0, ',', '.') }}</td></tr>
</table>
@endsection
