@extends('layouts.app')

@section('title', 'Edit Pengaturan')

@section('content')
@include('partials.page-header', [
    'title' => 'Edit Pengaturan',
    'breadcrumbs' => ['Pengaturan' => route('settings.index'), 'Edit' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('settings.update-item', $setting) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kunci (Key)</label>
                    <input type="text" class="form-control" value="{{ $setting->key }}" disabled>
                </div>
                <div class="col-md-8">
                    <label for="value" class="form-label">Nilai <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('value') is-invalid @enderror" id="value" name="value" rows="3" required>{{ old('value', $setting->value) }}</textarea>
                    @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('settings.show') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
