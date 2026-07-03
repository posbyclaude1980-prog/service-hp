@extends('layouts.app')

@section('title', 'Spare Part')

@section('content')
@include('partials.page-header', [
    'title' => 'Data Spare Part',
    'breadcrumbs' => ['Master Data' => '#', 'Spare Part' => route('spare-parts.index')],
    'actions' => ''
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
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
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
                    <th>Status</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($spareParts as $part)
                <tr>
                    <td>{{ $part->code }}</td>
                    <td>{{ $part->name }}</td>
                    <td class="text-center">{{ $part->stock }}</td>
                    <td class="text-center">{{ $part->min_stock }}</td>
                    <td class="text-end">Rp {{ number_format($part->purchase_price, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($part->selling_price, 0, ',', '.') }}</td>
                    <td>
                        @if($part->isLowStock())
                            <span class="badge bg-danger">Menipis</span>
                        @else
                            <span class="badge bg-success">Tersedia</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('spare-parts.show', $part) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('spare-parts.edit', $part) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        @include('partials.delete-form', ['action' => route('spare-parts.destroy', $part), 'name' => $part->name])
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
