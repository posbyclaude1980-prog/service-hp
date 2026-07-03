@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
@include('partials.page-header', [
    'title' => 'Manajemen Pengguna',
    'breadcrumbs' => ['Pengguna' => route('users.index')],
    'actions' => '<a href="'.route('users.create').'" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Pengguna</a>'
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama atau email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Role</label>
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="owner" @selected(request('role') === 'owner')>Owner</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    <option value="kasir" @selected(request('role') === 'kasir')>Kasir</option>
                    <option value="teknisi" @selected(request('role') === 'teknisi')>Teknisi</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover datatable w-100">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?: '-' }}</td>
                    <td><span class="badge bg-{{ match($user->role) { 'owner' => 'dark', 'admin' => 'danger', 'teknisi' => 'info', default => 'secondary' } }}">{{ $user->roleLabel() }}</span></td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at?->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        @if($user->id !== auth()->id())
                            @include('partials.delete-form', ['action' => route('users.destroy', $user), 'name' => $user->name])
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
