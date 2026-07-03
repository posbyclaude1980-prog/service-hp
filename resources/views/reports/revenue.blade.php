@extends('layouts.app')

@section('title', 'Laporan Pendapatan')

@section('content')
@include('partials.page-header', [
    'title' => 'Laporan Pendapatan',
    'breadcrumbs' => ['Laporan' => '#', 'Pendapatan' => route('reports.revenue')],
    'actions' => '
        <a href="'.route('reports.revenue.export.pdf', request()->query()).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> Export PDF</a>
    '
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from', $dateFrom ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to', $dateTo ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Metode Pembayaran</label>
                <select name="method" class="form-select">
                    <option value="">Semua</option>
                    @foreach(\App\Enums\PaymentMethod::cases() as $method)
                        <option value="{{ $method->value }}" @selected(request('method') === $method->value)>{{ $method->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Tampilkan</button>
                <a href="{{ route('reports.revenue') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Total Pendapatan</div>
            <div class="fs-4 fw-bold text-success">Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Jumlah Transaksi</div>
            <div class="fs-4 fw-bold">{{ $summary['total_transactions'] ?? 0 }}</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Rata-rata Transaksi</div>
            <div class="fs-5 fw-bold">Rp {{ number_format($summary['average'] ?? 0, 0, ',', '.') }}</div>
        </div></div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Grafik Pendapatan</div>
            <div class="card-body"><canvas id="revenueReportChart" height="100"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Per Metode</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Metode</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                        @foreach($byMethod ?? [] as $method => $total)
                        <tr>
                            <td>{{ \App\Enums\PaymentMethod::tryFrom($method)?->label() ?? $method }}</td>
                            <td class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
                </tr>
            </thead>
            <tbody>
                @foreach($payments ?? [] as $payment)
                <tr>
                    <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
                    <td>{{ $payment->serviceOrder->ticket_number ?? '-' }}</td>
                    <td>{{ $payment->serviceOrder->customer->name ?? '-' }}</td>
                    <td>{{ $payment->method_label }}</td>
                    <td>{{ $payment->user->name ?? '-' }}</td>
                    <td class="text-end">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@include('partials.datatable-init')

@push('scripts')
<script>
    new Chart(document.getElementById('revenueReportChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: [{ label: 'Pendapatan (Rp)', data: @json($chartData ?? []), backgroundColor: '#22c55e', borderRadius: 4 }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
</script>
@endpush
