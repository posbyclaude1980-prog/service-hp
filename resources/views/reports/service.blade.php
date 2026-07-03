@extends('layouts.app')

@section('title', 'Laporan Service')

@section('content')
@include('partials.page-header', [
    'title' => 'Laporan Service',
    'breadcrumbs' => ['Laporan' => '#', 'Service' => route('reports.service')],
    'actions' => '
        <a href="'.route('print.reports.daily', ['date' => request('date_from', date('Y-m-d')), 'preview' => 1]).'" class="btn btn-outline-dark" target="_blank"><i class="bi bi-printer"></i> Cetak LX-300</a>
        <a href="'.route('reports.service.export.pdf', request()->query()).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> Export PDF</a>
    '
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(\App\Enums\ServiceOrderStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Teknisi</label>
                <select name="technician_id" class="form-select">
                    <option value="">Semua Teknisi</option>
                    @foreach($technicians ?? [] as $tech)
                        <option value="{{ $tech->id }}" @selected(request('technician_id') == $tech->id)>{{ $tech->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from', $dateFrom ?? '') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to', $dateTo ?? '') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Tampilkan</button>
                <a href="{{ route('reports.service') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Total Service</div>
            <div class="fs-4 fw-bold">{{ $summary['total'] ?? 0 }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Selesai</div>
            <div class="fs-4 fw-bold text-success">{{ $summary['completed'] ?? 0 }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Dalam Proses</div>
            <div class="fs-4 fw-bold text-primary">{{ $summary['in_progress'] ?? 0 }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Total Nilai</div>
            <div class="fs-5 fw-bold">Rp {{ number_format($summary['total_value'] ?? 0, 0, ',', '.') }}</div>
        </div></div>
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
                    <th>Teknisi</th>
                    <th>Status</th>
                    <th>Tgl Masuk</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders ?? [] as $order)
                <tr>
                    <td>{{ $order->ticket_number }}</td>
                    <td>{{ $order->customer->name ?? '-' }}</td>
                    <td>{{ $order->device_brand }} {{ $order->device_model }}</td>
                    <td>{{ $order->technician->name ?? '-' }}</td>
                    <td>{{ $order->status_label }}</td>
                    <td>{{ $order->entry_date?->format('d/m/Y') }}</td>
                    <td class="text-end">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')
