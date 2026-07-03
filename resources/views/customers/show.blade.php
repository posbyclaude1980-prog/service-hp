@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
@include('partials.page-header', [
    'title' => 'Detail Pelanggan',
    'breadcrumbs' => ['Pelanggan' => route('customers.index'), $customer->name => '#'],
    'actions' => '
        <a href="'.route('customers.edit', $customer).'" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    '
])

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Informasi Pelanggan</div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted" width="100">Kode</td><td class="fw-semibold">{{ $customer->code }}</td></tr>
                    <tr><td class="text-muted">Nama</td><td class="fw-semibold">{{ $customer->name }}</td></tr>
                    <tr><td class="text-muted">Telepon</td><td>{{ $customer->phone }}</td></tr>
                    <tr><td class="text-muted">Alamat</td><td>{{ $customer->address ?: '-' }}</td></tr>
                    <tr><td class="text-muted">Terdaftar</td><td>{{ $customer->created_at?->format('d/m/Y H:i') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Riwayat Service</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No. Tiket</th>
                                <th>Perangkat</th>
                                <th>Status</th>
                                <th>Tanggal Masuk</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->serviceOrders ?? [] as $order)
                            <tr>
                                <td><a href="{{ route('service-orders.show', $order) }}">{{ $order->ticket_number }}</a></td>
                                <td>{{ $order->device_brand }} {{ $order->device_model }}</td>
                                <td><span class="badge bg-secondary">{{ $order->status_label }}</span></td>
                                <td>{{ $order->entry_date?->format('d/m/Y') }}</td>
                                <td class="text-end">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat service</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
