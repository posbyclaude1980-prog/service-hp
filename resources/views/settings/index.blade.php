@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
@include('partials.page-header', [
    'title' => 'Pengaturan Toko',
    'breadcrumbs' => ['Pengaturan' => route('settings.index')]
])

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Informasi Toko</div>
            <div class="card-body">
                @include('partials.form-errors')
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="shop_name" class="form-label">Nama Toko <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('shop_name') is-invalid @enderror" id="shop_name" name="shop_name"
                                   value="{{ old('shop_name', $settings['shop_name'] ?? config('app.name')) }}" required>
                            @error('shop_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="shop_phone" class="form-label">Telepon</label>
                            <input type="text" class="form-control @error('shop_phone') is-invalid @enderror" id="shop_phone" name="shop_phone"
                                   value="{{ old('shop_phone', $settings['shop_phone'] ?? '') }}">
                            @error('shop_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="shop_email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('shop_email') is-invalid @enderror" id="shop_email" name="shop_email"
                                   value="{{ old('shop_email', $settings['shop_email'] ?? '') }}">
                            @error('shop_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="default_warranty_days" class="form-label">Garansi Default (Hari)</label>
                            <input type="number" min="0" class="form-control @error('default_warranty_days') is-invalid @enderror" id="default_warranty_days" name="default_warranty_days"
                                   value="{{ old('default_warranty_days', $settings['default_warranty_days'] ?? 30) }}">
                            @error('default_warranty_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label for="shop_address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('shop_address') is-invalid @enderror" id="shop_address" name="shop_address" rows="3">{{ old('shop_address', $settings['shop_address'] ?? '') }}</textarea>
                            @error('shop_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ticket_prefix" class="form-label">Prefix No. Tiket</label>
                            <input type="text" class="form-control @error('ticket_prefix') is-invalid @enderror" id="ticket_prefix" name="ticket_prefix"
                                   value="{{ old('ticket_prefix', $settings['ticket_prefix'] ?? 'SRV') }}" placeholder="SRV">
                            @error('ticket_prefix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="shop_logo" class="form-label">Logo Toko</label>
                            <input type="file" class="form-control @error('shop_logo') is-invalid @enderror" id="shop_logo" name="shop_logo" accept="image/*">
                            @error('shop_logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-muted text-uppercase small fw-bold mb-3">Nota & Cetak</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="receipt_footer" class="form-label">Footer Nota</label>
                            <textarea class="form-control" id="receipt_footer" name="receipt_footer" rows="2" placeholder="Terima kasih atas kepercayaan Anda...">{{ old('receipt_footer', $settings['receipt_footer'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Pengaturan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">Logo Saat Ini</div>
            <div class="card-body text-center">
                @if(!empty($settings['shop_logo']))
                    <img src="{{ asset('storage/'.$settings['shop_logo']) }}" alt="Logo" class="img-fluid rounded" style="max-height:120px">
                @else
                    <div class="text-muted py-4"><i class="bi bi-image fs-1"></i><p class="mb-0 small">Belum ada logo</p></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
