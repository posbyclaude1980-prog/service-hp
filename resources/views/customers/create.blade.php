@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
@include('partials.page-header', [
    'title' => 'Tambah Pelanggan',
    'breadcrumbs' => ['Pelanggan' => route('customers.index'), 'Tambah' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')

        <form action="{{ route('customers.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="code" class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"
                           value="{{ old('code', $generatedCode ?? '') }}" required>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                           value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                           value="{{ old('phone') }}" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (() => { 'use strict'; document.querySelectorAll('.needs-validation').forEach(f => {
        f.addEventListener('submit', e => { if (!f.checkValidity()) { e.preventDefault(); e.stopPropagation(); } f.classList.add('was-validated'); });
    }); })();
</script>
@endpush
