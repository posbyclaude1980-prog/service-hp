@extends('layouts.app')

@section('title', 'Supplier')

@section('content')
@include('partials.page-header', [
    'title' => 'Data Supplier',
    'breadcrumbs' => ['Master Data' => '#', 'Supplier' => route('suppliers.index')],
    'actions' => ''
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, kode, kontak..." value="{{ request('search') }}">
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
                    <th>Kontak</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->code }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact ?: '-' }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>{{ Str::limit($supplier->address, 30) }}</td>
                    <td class="text-center">
                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        @include('partials.delete-form', ['action' => route('suppliers.destroy', $supplier), 'name' => $supplier->name])
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
