@extends('layouts.app')

@section('title', 'Detail Spare Part')

@section('content')
@include('partials.page-header', [
    'title' => $sparePart->name,
    'breadcrumbs' => ['Spare Part' => route('spare-parts.index'), $sparePart->name => '#'],
    'actions' => '
        <a href="'.route('spare-parts.print', $sparePart).'" class="btn btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i> Cetak</a>
        <a href="'.route('spare-parts.pdf', $sparePart).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> PDF</a>
        <a href="'.route('spare-parts.edit', $sparePart).'" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    '
])

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted" width="140">Kode</td><td class="fw-semibold">{{ $sparePart->code }}</td></tr>
                    <tr><td class="text-muted">Nama</td><td class="fw-semibold">{{ $sparePart->name }}</td></tr>
                    <tr><td class="text-muted">Stok</td><td>
                        {{ $sparePart->stock }}
                        @if($sparePart->isLowStock())<span class="badge bg-danger ms-2">Menipis</span>@endif
                    </td></tr>
                    <tr><td class="text-muted">Stok Minimum</td><td>{{ $sparePart->min_stock }}</td></tr>
                    <tr><td class="text-muted">Harga Beli</td><td>Rp {{ number_format($sparePart->purchase_price, 0, ',', '.') }}</td></tr>
                    <tr><td class="text-muted">Harga Jual</td><td>Rp {{ number_format($sparePart->selling_price, 0, ',', '.') }}</td></tr>
                    <tr><td class="text-muted">Deskripsi</td><td>{{ $sparePart->description ?: '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
