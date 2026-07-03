@extends('layouts.app')

@section('title', 'Edit Service Order')

@section('content')
@include('partials.page-header', [
    'title' => 'Edit Service Order — '.$serviceOrder->ticket_number,
    'breadcrumbs' => ['Service Order' => route('service-orders.index'), 'Edit' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')

        <form action="{{ route('service-orders.update', $serviceOrder) }}" method="POST" id="serviceOrderForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <h6 class="text-muted text-uppercase small fw-bold mb-3">Informasi Pelanggan & Perangkat</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="customer_id" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                    <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                        @foreach($customers ?? [] as $customer)
                            <option value="{{ $customer->id }}" data-phone="{{ $customer->phone }}" @selected(old('customer_id', $serviceOrder->customer_id) == $customer->id)>{{ $customer->name }} — {{ $customer->phone }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="contact_phone" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $serviceOrder->contact_phone) }}" required>
                    @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="brand_select" class="form-label">Merk (Referensi)</label>
                    <select class="form-select" id="brand_select">
                        <option value="">Pilih Merk</option>
                        @foreach($brands ?? [] as $brand)
                            <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="model_select" class="form-label">Model (Referensi)</label>
                    <select class="form-select" id="model_select">
                        <option value="">Pilih Model</option>
                        @foreach($phoneModels ?? [] as $model)
                            <option value="{{ $model->name }}" data-brand="{{ $model->brand->name ?? '' }}">{{ $model->brand->name ?? '' }} — {{ $model->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="device_brand" class="form-label">Merek Perangkat</label>
                    <input type="text" class="form-control" id="device_brand" name="device_brand" value="{{ old('device_brand', $serviceOrder->device_brand) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="device_model" class="form-label">Model Perangkat</label>
                    <input type="text" class="form-control" id="device_model" name="device_model" value="{{ old('device_model', $serviceOrder->device_model) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="imei" class="form-label">IMEI</label>
                    <input type="text" class="form-control" id="imei" name="imei" value="{{ old('imei', $serviceOrder->imei) }}">
                </div>
                <div class="col-md-4">
                    <label for="color" class="form-label">Warna</label>
                    <input type="text" class="form-control" id="color" name="color" value="{{ old('color', $serviceOrder->color) }}">
                </div>
                <div class="col-md-4">
                    <label for="device_password" class="form-label">Password HP</label>
                    <input type="text" class="form-control" id="device_password" name="device_password" value="{{ old('device_password', $serviceOrder->device_password) }}">
                </div>
                <div class="col-md-4">
                    <label for="estimated_cost" class="form-label">Estimasi (Rp)</label>
                    <input type="number" min="0" step="1000" class="form-control" id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost', $serviceOrder->estimated_cost) }}">
                </div>
                <div class="col-12">
                    <label for="complaint" class="form-label">Keluhan</label>
                    <textarea class="form-control" id="complaint" name="complaint" rows="3" required>{{ old('complaint', $serviceOrder->complaint) }}</textarea>
                </div>
                <div class="col-12">
                    <label for="completeness" class="form-label">Kelengkapan</label>
                    <textarea class="form-control" id="completeness" name="completeness" rows="2">{{ old('completeness', $serviceOrder->completeness) }}</textarea>
                </div>
                <div class="col-12">
                    <label for="diagnosis" class="form-label">Diagnosis</label>
                    <textarea class="form-control" id="diagnosis" name="diagnosis" rows="2">{{ old('diagnosis', $serviceOrder->diagnosis) }}</textarea>
                </div>
            </div>

            <h6 class="text-muted text-uppercase small fw-bold mb-3">Detail Service</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Status Saat Ini</label>
                    <div class="pt-1">
                        @include('partials.service-order-status-badge', ['serviceOrder' => $serviceOrder])
                        <a href="{{ route('service-orders.show', $serviceOrder) }}" class="small ms-2">Ubah via workflow</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="technician_id" class="form-label">Teknisi</label>
                    <select class="form-select" id="technician_id" name="technician_id">
                        <option value="">Pilih Teknisi</option>
                        @foreach($technicians ?? [] as $tech)
                            <option value="{{ $tech->id }}" @selected(old('technician_id', $serviceOrder->technician_id) == $tech->id)>{{ $tech->user->name ?? $tech->employee_code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="entry_date" class="form-label">Tanggal Masuk</label>
                    <input type="date" class="form-control" id="entry_date" name="entry_date" value="{{ old('entry_date', $serviceOrder->entry_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="warranty_days" class="form-label">Garansi (Hari)</label>
                    <input type="number" min="0" class="form-control" id="warranty_days" name="warranty_days" value="{{ old('warranty_days', $serviceOrder->warranty_days) }}">
                </div>
                <div class="col-md-4">
                    <label for="service_fee" class="form-label">Biaya Jasa</label>
                    <input type="number" min="0" step="0.01" class="form-control" id="service_fee" name="service_fee" value="{{ old('service_fee', $serviceOrder->service_fee) }}">
                </div>
                <div class="col-md-4">
                    <label for="completion_date" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="completion_date" name="completion_date" value="{{ old('completion_date', $serviceOrder->completion_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label for="pickup_date" class="form-label">Tanggal Diambil</label>
                    <input type="date" class="form-control" id="pickup_date" name="pickup_date" value="{{ old('pickup_date', $serviceOrder->pickup_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $serviceOrder->notes) }}</textarea>
                </div>
            </div>

            <h6 class="text-muted text-uppercase small fw-bold mb-3">Foto Penerimaan <small class="text-muted fw-normal">(kosongkan jika tidak diubah)</small></h6>
            <div class="row g-3 mb-4">
                @php
                    $existingPhotos = $serviceOrder->intakeImages->keyBy('type');
                @endphp
                @foreach([
                    'photo_front' => ['type' => 'front', 'label' => 'Foto Depan'],
                    'photo_back' => ['type' => 'back', 'label' => 'Foto Belakang'],
                    'photo_lcd' => ['type' => 'lcd', 'label' => 'Foto LCD'],
                    'photo_imei' => ['type' => 'imei', 'label' => 'Foto IMEI'],
                ] as $field => $meta)
                <div class="col-md-6 col-lg-3">
                    <label for="{{ $field }}" class="form-label">{{ $meta['label'] }}</label>
                    @if($existingPhotos->has($meta['type']))
                        <div class="mb-2"><img src="{{ $existingPhotos[$meta['type']]->url }}" class="img-thumbnail w-100" alt="{{ $meta['label'] }}"></div>
                    @endif
                    <input type="file" class="form-control photo-input" id="{{ $field }}" name="{{ $field }}" accept="image/*" data-preview="{{ $field }}_preview">
                    <img id="{{ $field }}_preview" class="img-thumbnail mt-2 w-100 d-none photo-preview" alt="{{ $meta['label'] }}">
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-muted text-uppercase small fw-bold mb-0">Spare Part</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addPartRow"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered" id="partsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Spare Part</th>
                            <th class="text-center" style="width:12%">Qty</th>
                            <th class="text-end" style="width:18%">Harga</th>
                            <th class="text-end" style="width:18%">Subtotal</th>
                            <th class="text-center" style="width:10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="partsBody">
                        @php
                            $oldItems = old('items');
                            if (!$oldItems) {
                                $oldItems = $serviceOrder->items->map(fn($i) => [
                                    'spare_part_id' => $i->spare_part_id,
                                    'quantity' => $i->quantity,
                                    'price' => $i->price,
                                ])->toArray();
                            }
                            if (empty($oldItems)) $oldItems = [['spare_part_id' => '', 'quantity' => 1, 'price' => 0]];
                        @endphp
                        @foreach($oldItems as $index => $item)
                        <tr class="part-row">
                            <td>
                                <select class="form-select form-select-sm spare-part-select" name="items[{{ $index }}][spare_part_id]">
                                    <option value="">Pilih Spare Part</option>
                                    @foreach($spareParts ?? [] as $part)
                                        <option value="{{ $part->id }}" data-price="{{ $part->selling_price }}"
                                            @selected(($item['spare_part_id'] ?? '') == $part->id)>{{ $part->code }} — {{ $part->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" min="1" class="form-control form-control-sm text-center part-qty" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] ?? 1 }}"></td>
                            <td><input type="number" min="0" step="0.01" class="form-control form-control-sm text-end part-price" name="items[{{ $index }}][price]" value="{{ $item['price'] ?? 0 }}"></td>
                            <td class="text-end part-subtotal align-middle">Rp 0</td>
                            <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="bi bi-trash"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr><td colspan="3" class="text-end fw-semibold">Total Spare Part</td><td class="text-end fw-bold" id="partsTotal">Rp 0</td><td></td></tr>
                        <tr><td colspan="3" class="text-end fw-semibold">Biaya Jasa</td><td class="text-end" id="serviceFeeDisplay">Rp 0</td><td></td></tr>
                        <tr class="table-primary"><td colspan="3" class="text-end fw-bold">Grand Total</td><td class="text-end fw-bold fs-5" id="grandTotal">Rp 0</td><td></td></tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('service-orders.show', $serviceOrder) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<template id="partRowTemplate">
    <tr class="part-row">
        <td>
            <select class="form-select form-select-sm spare-part-select" name="items[__INDEX__][spare_part_id]">
                <option value="">Pilih Spare Part</option>
                @foreach($spareParts ?? [] as $part)
                    <option value="{{ $part->id }}" data-price="{{ $part->selling_price }}">{{ $part->code }} — {{ $part->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" min="1" class="form-control form-control-sm text-center part-qty" name="items[__INDEX__][quantity]" value="1"></td>
        <td><input type="number" min="0" step="0.01" class="form-control form-control-sm text-end part-price" name="items[__INDEX__][price]" value="0"></td>
        <td class="text-end part-subtotal align-middle">Rp 0</td>
        <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"><i class="bi bi-trash"></i></button></td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
$(function () {
    let rowIndex = $('#partsBody .part-row').length;
    $('#phone_model_id').on('change', function () {
        const opt = $(this).find(':selected');
        if (opt.val()) { $('#device_brand').val(opt.data('brand')); $('#device_model').val(opt.data('name')); }
    });
    function formatRp(n) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0); }
    function calcRow($row) {
        const sub = (parseFloat($row.find('.part-qty').val()) || 0) * (parseFloat($row.find('.part-price').val()) || 0);
        $row.find('.part-subtotal').text(formatRp(sub));
        return sub;
    }
    function calcTotals() {
        let partsTotal = 0;
        $('#partsBody .part-row').each(function () { partsTotal += calcRow($(this)); });
        const fee = parseFloat($('#service_fee').val()) || 0;
        $('#partsTotal, #serviceFeeDisplay').each(function () {});
        $('#partsTotal').text(formatRp(partsTotal));
        $('#serviceFeeDisplay').text(formatRp(fee));
        $('#grandTotal').text(formatRp(partsTotal + fee));
    }
    $(document).on('change', '.spare-part-select', function () {
        $(this).closest('tr').find('.part-price').val($(this).find(':selected').data('price') || 0);
        calcTotals();
    });
    $(document).on('input', '.part-qty, .part-price, #service_fee', calcTotals);
    $('#addPartRow').on('click', function () { $('#partsBody').append($('#partRowTemplate').html().replace(/__INDEX__/g, rowIndex++)); });
    $(document).on('click', '.btn-remove-row', function () {
        if ($('#partsBody .part-row').length > 1) { $(this).closest('tr').remove(); calcTotals(); }
    });
    calcTotals();
});
</script>
@endpush
