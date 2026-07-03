@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
@include('partials.page-header', [
    'title' => 'Data Pembayaran',
    'breadcrumbs' => ['Pembayaran' => route('payments.index')],
    'actions' => ''
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="No. tiket..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Metode</label>
                <select name="method" class="form-select">
                    <option value="">Semua</option>
                    @foreach(\App\Enums\PaymentMethod::cases() as $method)
                        <option value="{{ $method->value }}" @selected(request('method') === $method->value)>{{ $method->label() }}</option>
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
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover datatable w-100">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Tiket</th>
                    <th>Pelanggan</th>
                    <th>Metode</th>
                    <th>Kasir</th>
                    <th class="text-end">Jumlah</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
                    <td><a href="{{ route('service-orders.show', $payment->service_order_id) }}">{{ $payment->serviceOrder->ticket_number ?? '-' }}</a></td>
                    <td>{{ $payment->serviceOrder->customer->name ?? '-' }}</td>
                    <td>{{ $payment->method?->label() }}</td>
                    <td>{{ $payment->user->name ?? '-' }}</td>
                    <td class="text-end">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        @include('partials.delete-form', ['action' => route('payments.destroy', $payment), 'name' => 'pembayaran ini'])
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
