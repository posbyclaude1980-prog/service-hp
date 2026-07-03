@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('content')
@include('partials.page-header', [
    'title' => 'Laporan Stok',
    'breadcrumbs' => ['Laporan' => '#', 'Stok' => route('reports.stock')],
    'actions' => '
        <a href="'.route('reports.stock.export.pdf', request()->query()).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> Export PDF</a>
    '
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama atau kode..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Status Stok</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="low" @selected(request('status') === 'low')>Stok Menipis</option>
                    <option value="available" @selected(request('status') === 'available')>Tersedia</option>
                    <option value="out" @selected(request('status') === 'out')>Habis</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Tampilkan</button>
                <a href="{{ route('reports.stock') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Total Item</div>
            <div class="fs-4 fw-bold">{{ $summary['total_items'] ?? 0 }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Stok Menipis</div>
            <div class="fs-4 fw-bold text-warning">{{ $summary['low_stock'] ?? 0 }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Stok Habis</div>
            <div class="fs-4 fw-bold text-danger">{{ $summary['out_of_stock'] ?? 0 }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Nilai Stok</div>
            <div class="fs-5 fw-bold">Rp {{ number_format($summary['stock_value'] ?? 0, 0, ',', '.') }}</div>
        </div></div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover datatable w-100">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Min. Stok</th>
                    <th class="text-end">Harga Beli</th>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-end">Nilai Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($spareParts ?? [] as $part)
                <tr>
                    <td>{{ $part->code }}</td>
                    <td>{{ $part->name }}</td>
                    <td class="text-center">{{ $part->stock }}</td>
                    <td class="text-center">{{ $part->min_stock }}</td>
                    <td class="text-end">Rp {{ number_format($part->purchase_price, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($part->selling_price, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($part->stock * $part->purchase_price, 0, ',', '.') }}</td>
                    <td>
                        @if($part->stock <= 0)<span class="badge bg-danger">Habis</span>
                        @elseif($part->isLowStock())<span class="badge bg-warning">Menipis</span>
                        @else<span class="badge bg-success">Tersedia</span>@endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
