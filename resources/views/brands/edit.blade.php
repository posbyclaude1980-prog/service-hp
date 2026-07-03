@extends('layouts.app')

@section('title', 'Edit Merek')

@section('content')
@include('partials.page-header', [
    'title' => 'Edit Merek',
    'breadcrumbs' => ['Merek' => route('brands.index'), 'Edit' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('brands.update', $brand) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Merek <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $brand->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('brands.show', $brand) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
