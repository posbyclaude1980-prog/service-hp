@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
@include('partials.page-header', [
    'title' => 'Edit Pengguna',
    'breadcrumbs' => ['Pengguna' => route('users.index'), 'Edit' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Telepon</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="col-md-6">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="owner" @selected(old('role', $user->role) === 'owner')>Owner</option>
                        <option value="kasir" @selected(old('role', $user->role) === 'kasir')>Kasir</option>
                        <option value="teknisi" @selected(old('role', $user->role) === 'teknisi')>Teknisi</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Kata Sandi Baru</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Kosongkan jika tidak diubah">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Akun Aktif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
