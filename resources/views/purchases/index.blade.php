@extends('layouts.app')

@section('title', 'Pembelian')

@section('content')
@include('partials.page-header', [
    'title' => 'Data Pembelian',
    'breadcrumbs' => ['Pembelian' => route('purchases.index')],
    'actions' => ''
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="No. invoice..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Supplier</label>
                <select name="supplier_id" class="form-select">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers ?? [] as $supplier)
                        <option value="{{ $supplier->id }}" @selected(request('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
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
                    <th>No. Invoice</th>
                    <th>Supplier</th>
                    <th>Tanggal</th>
                    <th>Petugas</th>
                    <th class="text-end">Total</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $purchase)
                <tr>
                    <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->invoice_number }}</a></td>
                    <td>{{ $purchase->supplier->name ?? '-' }}</td>
                    <td>{{ $purchase->purchase_date?->format('d/m/Y') }}</td>
                    <td>{{ $purchase->user->name ?? '-' }}</td>
                    <td class="text-end">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        @include('partials.delete-form', ['action' => route('purchases.destroy', $purchase), 'name' => $purchase->invoice_number])
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
