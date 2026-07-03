@extends('layouts.app')

@section('title', 'Detail Supplier')

@section('content')
@include('partials.page-header', [
    'title' => $supplier->name,
    'breadcrumbs' => ['Supplier' => route('suppliers.index'), $supplier->name => '#'],
    'actions' => '
        <a href="'.route('suppliers.print', $supplier).'" class="btn btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i> Cetak</a>
        <a href="'.route('suppliers.pdf', $supplier).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> PDF</a>
        <a href="'.route('suppliers.edit', $supplier).'" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    '
])

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted">Kode</td><td class="fw-semibold">{{ $supplier->code }}</td></tr>
                    <tr><td class="text-muted">Nama</td><td class="fw-semibold">{{ $supplier->name }}</td></tr>
                    <tr><td class="text-muted">Kontak</td><td>{{ $supplier->contact ?: '-' }}</td></tr>
                    <tr><td class="text-muted">Telepon</td><td>{{ $supplier->phone }}</td></tr>
                    <tr><td class="text-muted">Alamat</td><td>{{ $supplier->address ?: '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Riwayat Pembelian</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>No. Invoice</th><th>Tanggal</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                        @forelse($supplier->purchases ?? [] as $purchase)
                        <tr>
                            <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->invoice_number }}</a></td>
                            <td>{{ $purchase->purchase_date?->format('d/m/Y') }}</td>
                            <td class="text-end">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">Belum ada pembelian</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
