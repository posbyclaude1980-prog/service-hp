@extends('layouts.pdf')

@section('title', 'Data Pengguna')

@section('content')
<table>
    <tr><th width="100">Nama</th><td>{{ $user->name }}</td></tr>
    <tr><th>Email</th><td>{{ $user->email }}</td></tr>
    <tr><th>Role</th><td>{{ ucfirst($user->role) }}</td></tr>
    <tr><th>Status</th><td>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</td></tr>
</table>
@endsection
