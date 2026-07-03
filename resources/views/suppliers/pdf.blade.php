@extends('layouts.pdf')

@section('title', 'Supplier')

@section('content')
<table>
    <tr><th width="100">Kode</th><td>{{ $supplier->code }}</td></tr>
    <tr><th>Nama</th><td>{{ $supplier->name }}</td></tr>
    <tr><th>Kontak</th><td>{{ $supplier->contact ?: '-' }}</td></tr>
    <tr><th>Telepon</th><td>{{ $supplier->phone }}</td></tr>
    <tr><th>Alamat</th><td>{{ $supplier->address ?: '-' }}</td></tr>
</table>
@endsection
