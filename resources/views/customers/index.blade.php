@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
@include('partials.page-header', [
    'title' => 'Data Pelanggan',
    'breadcrumbs' => ['Pelanggan' => route('customers.index')]
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('customers.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, kode, atau telepon..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable w-100" id="customersTable">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Total Service</th>
                        <th>Dibuat</th>
                        <th class="no-export text-center" style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->code }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ Str::limit($customer->address, 40) }}</td>
                        <td>{{ $customer->service_orders_count ?? $customer->serviceOrders()->count() }}</td>
                        <td>{{ $customer->created_at?->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            @include('partials.delete-form', ['action' => route('customers.destroy', $customer), 'name' => $customer->name])
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@include('partials.datatable-init')
