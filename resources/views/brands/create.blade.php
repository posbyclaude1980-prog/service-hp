@extends('layouts.app')

@section('title', 'Tambah Merek')

@section('content')
@include('partials.page-header', [
    'title' => 'Tambah Merek',
    'breadcrumbs' => ['Merek' => route('brands.index'), 'Tambah' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('brands.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Merek <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
