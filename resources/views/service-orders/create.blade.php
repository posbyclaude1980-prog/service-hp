@extends('layouts.app')

@section('title', 'Penerimaan Service HP')

@section('content')
@include('partials.page-header', [
    'title' => 'Form Penerimaan Service HP',
    'breadcrumbs' => ['Service HP' => route('service-orders.index'), 'Penerimaan Baru' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')

        <form action="{{ route('service-orders.store') }}" method="POST" enctype="multipart/form-data" id="intakeForm" class="needs-validation" novalidate>
            @csrf

            <h6 class="text-muted text-uppercase small fw-bold mb-3">Identitas Service</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Nomor Service</label>
                    <input type="text" class="form-control bg-light" value="{{ $previewTicketNumber }}" readonly>
                    <div class="form-text">Otomatis saat disimpan (contoh: SRV-20260701-000001)</div>
                </div>
                <div class="col-md-4">
                    <label for="entry_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('entry_date') is-invalid @enderror" id="entry_date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                    @error('entry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select bg-light" id="status" name="status" readonly disabled>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" selected>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="status" value="{{ \App\Enums\ServiceOrderStatus::Received->value }}">
                </div>
            </div>

            <h6 class="text-muted text-uppercase small fw-bold mb-3">Data Pelanggan & Perangkat</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="customer_id" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                    <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}" @selected(old('customer_id') == $customer->id)>
                                {{ $customer->name }} — {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="contact_phone" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="08xxxxxxxxxx" required>
                    @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="brand_select" class="form-label">Merk (Referensi)</label>
                    <select class="form-select" id="brand_select">
                        <option value="">Pilih Merk</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="model_select" class="form-label">Model (Referensi)</label>
                    <select class="form-select" id="model_select">
                        <option value="">Pilih Model</option>
                        @foreach($phoneModels as $model)
                            <option value="{{ $model->name }}" data-brand="{{ $model->brand->name ?? '' }}">{{ $model->brand->name ?? '' }} — {{ $model->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="technician_id" class="form-label">Teknisi</label>
                    <select class="form-select @error('technician_id') is-invalid @enderror" id="technician_id" name="technician_id">
                        <option value="">Pilih Teknisi</option>
                        @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}" @selected(old('technician_id') == $technician->id)>
                                {{ $technician->user->name ?? $technician->employee_code }}
                            </option>
                        @endforeach
                    </select>
                    @error('technician_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="device_brand" class="form-label">Merk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('device_brand') is-invalid @enderror" id="device_brand" name="device_brand" value="{{ old('device_brand') }}" required>
                    @error('device_brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="device_model" class="form-label">Model <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('device_model') is-invalid @enderror" id="device_model" name="device_model" value="{{ old('device_model') }}" required>
                    @error('device_model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="imei" class="form-label">IMEI</label>
                    <input type="text" class="form-control @error('imei') is-invalid @enderror" id="imei" name="imei" value="{{ old('imei') }}">
                    @error('imei')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="color" class="form-label">Warna</label>
                    <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color') }}">
                    @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="device_password" class="form-label">Password HP</label>
                    <input type="text" class="form-control @error('device_password') is-invalid @enderror" id="device_password" name="device_password" value="{{ old('device_password') }}" placeholder="PIN / pola / password">
                    @error('device_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="estimated_cost" class="form-label">Estimasi (Rp)</label>
                    <input type="number" min="0" step="1000" class="form-control @error('estimated_cost') is-invalid @enderror" id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost', 0) }}">
                    @error('estimated_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="complaint" class="form-label">Keluhan <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('complaint') is-invalid @enderror" id="complaint" name="complaint" rows="3" required>{{ old('complaint') }}</textarea>
                    @error('complaint')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="completeness" class="form-label">Kelengkapan</label>
                    <textarea class="form-control @error('completeness') is-invalid @enderror" id="completeness" name="completeness" rows="2" placeholder="Contoh: Unit, charger, kotak, SIM card">{{ old('completeness') }}</textarea>
                    @error('completeness')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <h6 class="text-muted text-uppercase small fw-bold mb-3">Dokumentasi Foto <small class="text-muted fw-normal">(otomatis diresize)</small></h6>
            <div class="row g-3 mb-4">
                @foreach([
                    'photo_front' => 'Foto Depan',
                    'photo_back' => 'Foto Belakang',
                    'photo_lcd' => 'Foto LCD',
                    'photo_imei' => 'Foto IMEI',
                ] as $field => $label)
                <div class="col-md-6 col-lg-3">
                    <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                    <input type="file" class="form-control @error($field) is-invalid @enderror photo-input" id="{{ $field }}" name="{{ $field }}" accept="image/*" data-preview="{{ $field }}_preview">
                    @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <img id="{{ $field }}_preview" class="img-thumbnail mt-2 w-100 d-none photo-preview" alt="{{ $label }}">
                </div>
                @endforeach
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Penerimaan</button>
                <a href="{{ route('service-orders.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#customer_id').on('change', function () {
        const phone = $(this).find(':selected').data('phone');
        if (phone && !$('#contact_phone').val()) {
            $('#contact_phone').val(phone);
        }
    }).trigger('change');

    $('#brand_select').on('change', function () {
        if ($(this).val()) {
            $('#device_brand').val($(this).val());
        }
    });

    $('#model_select').on('change', function () {
        const opt = $(this).find(':selected');
        if (opt.val()) {
            $('#device_model').val(opt.val());
            if (opt.data('brand')) {
                $('#device_brand').val(opt.data('brand'));
            }
        }
    });

    $('.photo-input').on('change', function () {
        const previewId = $(this).data('preview');
        const file = this.files[0];
        const $preview = $('#' + previewId);

        if (!file) {
            $preview.addClass('d-none').attr('src', '');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            $preview.attr('src', e.target.result).removeClass('d-none');
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
