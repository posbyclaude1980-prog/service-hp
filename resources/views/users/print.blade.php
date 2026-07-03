@extends('layouts.print')

@section('title', 'Data Pengguna')

@section('content')
<div class="print-meta"><div><strong>Data Pengguna</strong></div><div>{{ now()->format('d/m/Y H:i') }}</div></div>
<table>
    <tr><th width="140">Nama</th><td>{{ $user->name }}</td></tr>
    <tr><th>Email</th><td>{{ $user->email }}</td></tr>
    <tr><th>Telepon</th><td>{{ $user->phone ?: '-' }}</td></tr>
    <tr><th>Role</th><td>{{ ucfirst($user->role) }}</td></tr>
    <tr><th>Status</th><td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td></tr>
</table>
@endsection
