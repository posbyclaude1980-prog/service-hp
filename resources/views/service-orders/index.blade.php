@extends('layouts.app')

@section('title', 'Service Order')

@section('content')
@include('partials.page-header', [
    'title' => 'Data Service Order',
    'breadcrumbs' => ['Service Order' => route('service-orders.index')],
    'actions' => '<a href="'.route('service-orders.create').'" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Penerimaan Baru</a>'
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="No. tiket, nama, telepon..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    @foreach(\App\Enums\ServiceOrderStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
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
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('service-orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover datatable w-100">
            <thead>
                <tr>
                    <th>No. Tiket</th>
                    <th>Pelanggan</th>
                    <th>Perangkat</th>
                    <th>Status</th>
                    <th>Tgl Masuk</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Sisa</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($serviceOrders as $order)
                <tr>
                    <td><a href="{{ route('service-orders.show', $order) }}">{{ $order->ticket_number }}</a></td>
                    <td>{{ $order->customer->name ?? '-' }}</td>
                    <td>{{ $order->device_brand }} {{ $order->device_model }}</td>
                    <td>@include('partials.service-order-status-badge', ['serviceOrder' => $order])</td>
                    <td>{{ $order->entry_date?->format('d/m/Y') }}</td>
                    <td class="text-end">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</td>
                    <td class="text-end {{ $order->remaining_payment > 0 ? 'text-danger fw-semibold' : 'text-success' }}">
                        Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('service-orders.show', $order) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('service-orders.edit', $order) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        @include('partials.delete-form', ['action' => route('service-orders.destroy', $order), 'name' => $order->ticket_number])
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
