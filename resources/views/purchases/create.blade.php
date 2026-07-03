@extends('layouts.app')

@section('title', 'Tambah Pembelian')

@section('content')
@include('partials.page-header', [
    'title' => 'Tambah Pembelian',
    'breadcrumbs' => ['Pembelian' => route('purchases.index'), 'Tambah' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="invoice_number" class="form-label">No. Invoice <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $generatedInvoice ?? '') }}" required>
                    @error('invoice_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                        <option value="">Pilih Supplier</option>
                        @foreach($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="purchase_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('purchase_date') is-invalid @enderror" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                    @error('purchase_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-muted text-uppercase small fw-bold mb-0">Detail Barang</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addItemRow"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Spare Part</th><th class="text-center" style="width:12%">Qty</th><th class="text-end" style="width:18%">Harga</th><th class="text-end" style="width:18%">Subtotal</th><th style="width:10%"></th></tr>
                    </thead>
                    <tbody id="itemsBody">
                        @php $oldItems = old('items', [['spare_part_id' => '', 'quantity' => 1, 'price' => 0]]); @endphp
                        @foreach($oldItems as $index => $item)
                        <tr class="item-row">
                            <td>
                                <select class="form-select form-select-sm spare-part-select" name="items[{{ $index }}][spare_part_id]" required>
                                    <option value="">Pilih Spare Part</option>
                                    @foreach($spareParts ?? [] as $part)
                                        <option value="{{ $part->id }}" data-price="{{ $part->purchase_price }}" @selected(($item['spare_part_id'] ?? '') == $part->id)>{{ $part->code }} — {{ $part->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" min="1" class="form-control form-control-sm text-center item-qty" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] ?? 1 }}"></td>
                            <td><input type="number" min="0" step="0.01" class="form-control form-control-sm text-end item-price" name="items[{{ $index }}][price]" value="{{ $item['price'] ?? 0 }}"></td>
                            <td class="text-end item-subtotal align-middle">Rp 0</td>
                            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-trash"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-primary"><td colspan="3" class="text-end fw-bold">Grand Total</td><td class="text-end fw-bold fs-5" id="purchaseTotal">Rp 0</td><td></td></tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Pembelian</button>
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<template id="itemRowTemplate">
    <tr class="item-row">
        <td>
            <select class="form-select form-select-sm spare-part-select" name="items[__INDEX__][spare_part_id]" required>
                <option value="">Pilih Spare Part</option>
                @foreach($spareParts ?? [] as $part)
                    <option value="{{ $part->id }}" data-price="{{ $part->purchase_price }}">{{ $part->code }} — {{ $part->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" min="1" class="form-control form-control-sm text-center item-qty" name="items[__INDEX__][quantity]" value="1"></td>
        <td><input type="number" min="0" step="0.01" class="form-control form-control-sm text-end item-price" name="items[__INDEX__][price]" value="0"></td>
        <td class="text-end item-subtotal align-middle">Rp 0</td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item"><i class="bi bi-trash"></i></button></td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
$(function () {
    let idx = $('#itemsBody .item-row').length;
    function formatRp(n) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0); }
    function calc() {
        let total = 0;
        $('#itemsBody .item-row').each(function () {
            const sub = (parseFloat($(this).find('.item-qty').val()) || 0) * (parseFloat($(this).find('.item-price').val()) || 0);
            $(this).find('.item-subtotal').text(formatRp(sub));
            total += sub;
        });
        $('#purchaseTotal').text(formatRp(total));
    }
    $(document).on('change', '.spare-part-select', function () {
        $(this).closest('tr').find('.item-price').val($(this).find(':selected').data('price') || 0);
        calc();
    });
    $(document).on('input', '.item-qty, .item-price', calc);
    $('#addItemRow').on('click', () => { $('#itemsBody').append($('#itemRowTemplate').html().replace(/__INDEX__/g, idx++)); });
    $(document).on('click', '.btn-remove-item', function () {
        if ($('#itemsBody .item-row').length > 1) { $(this).closest('tr').remove(); calc(); }
    });
    calc();
});
</script>
@endpush
