@extends('layouts.app')

@section('title', 'Tambah Model HP')

@section('content')
@include('partials.page-header', [
    'title' => 'Tambah Model HP',
    'breadcrumbs' => ['Model HP' => route('phone-models.index'), 'Tambah' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('phone-models.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="brand_id" class="form-label">Merek <span class="text-danger">*</span></label>
                    <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                        <option value="">Pilih Merek</option>
                        @foreach($brands ?? [] as $brand)
                            <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Model <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('phone-models.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
