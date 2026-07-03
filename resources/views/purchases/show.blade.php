@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
@include('partials.page-header', [
    'title' => 'Pembelian — '.$purchase->invoice_number,
    'breadcrumbs' => ['Pembelian' => route('purchases.index'), $purchase->invoice_number => '#'],
    'actions' => '
        <a href="'.route('purchases.print', $purchase).'" class="btn btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i> Cetak</a>
        <a href="'.route('purchases.pdf', $purchase).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> PDF</a>
        <a href="'.route('purchases.edit', $purchase).'" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    '
])

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted">No. Invoice</td><td class="fw-semibold">{{ $purchase->invoice_number }}</td></tr>
                    <tr><td class="text-muted">Supplier</td><td>{{ $purchase->supplier->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tanggal</td><td>{{ $purchase->purchase_date?->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted">Petugas</td><td>{{ $purchase->user->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Total</td><td class="fs-5 fw-bold text-primary">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td></tr>
                    <tr><td class="text-muted">Catatan</td><td>{{ $purchase->notes ?: '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Detail Barang</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Spare Part</th><th class="text-center">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($purchase->items as $item)
                        <tr>
                            <td>{{ $item->sparePart->name ?? '-' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr><td colspan="3" class="text-end fw-bold">Total</td><td class="text-end fw-bold">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
