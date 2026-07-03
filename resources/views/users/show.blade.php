@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
@include('partials.page-header', [
    'title' => $user->name,
    'breadcrumbs' => ['Pengguna' => route('users.index'), $user->name => '#'],
    'actions' => '
        <a href="'.route('users.print', $user).'" class="btn btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i> Cetak</a>
        <a href="'.route('users.pdf', $user).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> PDF</a>
        <a href="'.route('users.edit', $user).'" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    '
])

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3 mb-4">
            <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:64px;height:64px;font-size:1.5rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <span class="badge bg-{{ match($user->role) { 'owner' => 'dark', 'admin' => 'danger', 'teknisi' => 'info', default => 'secondary' } }}">{{ $user->roleLabel() }}</span>
                @if($user->is_active)<span class="badge bg-success ms-1">Aktif</span>@else<span class="badge bg-secondary ms-1">Nonaktif</span>@endif
            </div>
        </div>
        <table class="table table-borderless">
            <tr><td class="text-muted" width="160">Email</td><td>{{ $user->email }}</td></tr>
            <tr><td class="text-muted">Telepon</td><td>{{ $user->phone ?: '-' }}</td></tr>
            <tr><td class="text-muted">Terdaftar</td><td>{{ $user->created_at?->format('d/m/Y H:i') }}</td></tr>
            <tr><td class="text-muted">Terakhir Diperbarui</td><td>{{ $user->updated_at?->format('d/m/Y H:i') }}</td></tr>
        </table>
    </div>
</div>
@endsection
