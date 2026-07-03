@extends('layouts.app')

@section('title', 'Tambah Pengaturan')

@section('content')
@include('partials.page-header', [
    'title' => 'Tambah Pengaturan Kustom',
    'breadcrumbs' => ['Pengaturan' => route('settings.index'), 'Tambah' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('settings.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="key" class="form-label">Kunci (Key) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" name="key" value="{{ old('key') }}" placeholder="contoh: tax_rate" required>
                    @error('key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label for="value" class="form-label">Nilai <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}" required>
                    @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
