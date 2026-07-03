@extends('layouts.print')

@section('title', 'Supplier')

@section('content')
<div class="print-meta"><div><strong>Supplier: {{ $supplier->name }}</strong></div><div>{{ now()->format('d/m/Y H:i') }}</div></div>
<table>
    <tr><th width="120">Kode</th><td>{{ $supplier->code }}</td></tr>
    <tr><th>Nama</th><td>{{ $supplier->name }}</td></tr>
    <tr><th>Kontak</th><td>{{ $supplier->contact ?: '-' }}</td></tr>
    <tr><th>Telepon</th><td>{{ $supplier->phone }}</td></tr>
    <tr><th>Alamat</th><td>{{ $supplier->address ?: '-' }}</td></tr>
</table>
@endsection
