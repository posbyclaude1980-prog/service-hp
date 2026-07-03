@extends('layouts.app')

@section('title', 'Edit Pembelian')

@section('content')
@include('partials.page-header', [
    'title' => 'Edit Pembelian — '.$purchase->invoice_number,
    'breadcrumbs' => ['Pembelian' => route('purchases.index'), 'Edit' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('purchases.update', $purchase) }}" method="POST" id="purchaseForm">
            @csrf
            @method('PUT')
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="invoice_number" class="form-label">No. Invoice</label>
                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $purchase->invoice_number) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        @foreach($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchase->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="purchase_date" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $purchase->purchase_date?->format('Y-m-d')) }}" required>
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $purchase->notes) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-muted text-uppercase small fw-bold mb-0">Detail Barang</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addItemRow"><i class="bi bi-plus-lg"></i> Tambah Baris</button>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th>Spare Part</th><th class="text-center">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th><th></th></tr>
                    </thead>
                    <tbody id="itemsBody">
                        @php
                            $oldItems = old('items');
                            if (!$oldItems) {
                                $oldItems = $purchase->items->map(fn($i) => ['spare_part_id' => $i->spare_part_id, 'quantity' => $i->quantity, 'price' => $i->price])->toArray();
                            }
                        @endphp
                        @foreach($oldItems as $index => $item)
                        <tr class="item-row">
                            <td>
                                <select class="form-select form-select-sm spare-part-select" name="items[{{ $index }}][spare_part_id]" required>
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
                    <tfoot><tr class="table-primary"><td colspan="3" class="text-end fw-bold">Grand Total</td><td class="text-end fw-bold fs-5" id="purchaseTotal">Rp 0</td><td></td></tr></tfoot>
                </table>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<template id="itemRowTemplate">
    <tr class="item-row">
        <td><select class="form-select form-select-sm spare-part-select" name="items[__INDEX__][spare_part_id]" required>
            <option value="">Pilih</option>@foreach($spareParts ?? [] as $part)<option value="{{ $part->id }}" data-price="{{ $part->purchase_price }}">{{ $part->code }} — {{ $part->name }}</option>@endforeach
        </select></td>
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
        let t = 0;
        $('#itemsBody .item-row').each(function () {
            const s = (parseFloat($(this).find('.item-qty').val())||0)*(parseFloat($(this).find('.item-price').val())||0);
            $(this).find('.item-subtotal').text(formatRp(s)); t += s;
        });
        $('#purchaseTotal').text(formatRp(t));
    }
    $(document).on('change', '.spare-part-select', function () { $(this).closest('tr').find('.item-price').val($(this).find(':selected').data('price')||0); calc(); });
    $(document).on('input', '.item-qty, .item-price', calc);
    $('#addItemRow').on('click', () => $('#itemsBody').append($('#itemRowTemplate').html().replace(/__INDEX__/g, idx++)));
    $(document).on('click', '.btn-remove-item', function () { if ($('#itemsBody .item-row').length>1) { $(this).closest('tr').remove(); calc(); } });
    calc();
});
</script>
@endpush
