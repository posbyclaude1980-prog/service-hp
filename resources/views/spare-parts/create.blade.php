@extends('layouts.app')

@section('title', 'Tambah Spare Part')

@section('content')
@include('partials.page-header', [
    'title' => 'Tambah Spare Part',
    'breadcrumbs' => ['Spare Part' => route('spare-parts.index'), 'Tambah' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('spare-parts.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="code" class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $generatedCode ?? '') }}" required>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label for="name" class="form-label">Nama Spare Part <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                    @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label for="min_stock" class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                    <input type="number" min="0" class="form-control @error('min_stock') is-invalid @enderror" id="min_stock" name="min_stock" value="{{ old('min_stock', 5) }}" required>
                    @error('min_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label for="purchase_price" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                    <input type="number" min="0" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', 0) }}" required>
                    @error('purchase_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label for="selling_price" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                    <input type="number" min="0" step="0.01" class="form-control @error('selling_price') is-invalid @enderror" id="selling_price" name="selling_price" value="{{ old('selling_price', 0) }}" required>
                    @error('selling_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('spare-parts.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
