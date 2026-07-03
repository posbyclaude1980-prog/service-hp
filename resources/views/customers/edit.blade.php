@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
@include('partials.page-header', [
    'title' => 'Edit Pelanggan',
    'breadcrumbs' => ['Pelanggan' => route('customers.index'), 'Edit' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')

        <form action="{{ route('customers.update', $customer) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="code" class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"
                           value="{{ old('code', $customer->code) }}" required>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                           value="{{ old('name', $customer->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                           value="{{ old('phone', $customer->phone) }}" required>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('customers.show', $customer) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
