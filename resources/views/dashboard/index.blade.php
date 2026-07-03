@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .dash-hero {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #06b6d4 100%);
        border-radius: 1rem;
        color: #fff;
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .dash-hero::after {
        content: '';
        position: absolute;
        right: -2rem;
        top: -2rem;
        width: 12rem;
        height: 12rem;
        background: rgba(255,255,255,.08);
        border-radius: 50%;
    }
    .dash-hero h2 { font-size: 1.35rem; font-weight: 700; margin: 0; }
    .dash-hero p { opacity: .85; margin: .35rem 0 0; font-size: .9rem; }

    .kpi-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(15,23,42,.06);
        transition: transform .2s ease, box-shadow .2s ease;
        height: 100%;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15,23,42,.1);
    }
    .kpi-card .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: .75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .kpi-card .kpi-value { font-size: 1.5rem; font-weight: 700; line-height: 1.2; color: #0f172a; }
    .kpi-card .kpi-label { font-size: .78rem; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: .03em; }
    .kpi-card .kpi-sub { font-size: .75rem; color: #94a3b8; }

    .chart-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(15,23,42,.06);
        height: 100%;
    }
    .chart-card .card-header {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 600;
        font-size: .9rem;
        color: #334155;
        padding: 1rem 1.25rem;
    }
    .chart-card .card-body { padding: 1rem 1.25rem 1.25rem; }

    .tech-card {
        background: linear-gradient(145deg, #fef3c7, #fffbeb);
        border: 1px solid #fde68a;
        border-radius: 1rem;
        padding: 1.25rem;
        height: 100%;
    }
    .tech-card .trophy { font-size: 2rem; color: #d97706; }

    .quick-menu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: .75rem;
    }
    @media (min-width: 576px) { .quick-menu-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 1200px) { .quick-menu-grid { grid-template-columns: repeat(2, 1fr); } }

    .quick-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        padding: 1rem .5rem;
        border-radius: .75rem;
        text-decoration: none;
        color: #334155;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all .15s ease;
        text-align: center;
        font-size: .78rem;
        font-weight: 500;
    }
    .quick-btn i { font-size: 1.35rem; }
    .quick-btn:hover { background: #eff6ff; border-color: #93c5fd; color: #1d4ed8; transform: scale(1.02); }

    .recent-table thead th {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #64748b;
        font-weight: 600;
        border-bottom-width: 1px;
        white-space: nowrap;
    }
    .recent-table tbody td { vertical-align: middle; font-size: .875rem; }

    .profit-positive { color: #16a34a; }
    .profit-negative { color: #dc2626; }

    @media (max-width: 575.98px) {
        .kpi-card .kpi-value { font-size: 1.25rem; }
        .dash-hero { padding: 1.25rem; }
        .dash-hero h2 { font-size: 1.15rem; }
    }
</style>
@endpush

@section('content')
@php
    $s = $stats;
    $profitClass = ($s['profit'] ?? 0) >= 0 ? 'profit-positive' : 'profit-negative';
@endphp

<div class="dash-hero">
    <div class="position-relative" style="z-index:1">
        <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>
        <p>Selamat datang, {{ auth()->user()->name }} — {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
</div>

{{-- KPI Row 1: Service --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="card kpi-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="kpi-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-check"></i></div>
                <div class="min-w-0">
                    <div class="kpi-label">Service Hari Ini</div>
                    <div class="kpi-value">{{ number_format($s['services_today'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card kpi-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="kpi-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
                <div class="min-w-0">
                    <div class="kpi-label">Service Pending</div>
                    <div class="kpi-value">{{ number_format($s['services_pending'] ?? 0) }}</div>
                    <div class="kpi-sub">Dalam proses</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card kpi-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="kpi-icon bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle"></i></div>
                <div class="min-w-0">
                    <div class="kpi-label">Service Selesai</div>
                    <div class="kpi-value">{{ number_format($s['services_completed'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card kpi-card">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="kpi-icon bg-info bg-opacity-10 text-info"><i class="bi bi-shield-check"></i></div>
                <div class="min-w-0">
                    <div class="kpi-label">Service Garansi</div>
                    <div class="kpi-value">{{ number_format($s['services_warranty'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- KPI Row 2: Financial --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl">
        <div class="card kpi-card">
            <div class="card-body p-3">
                <div class="kpi-label mb-1">Omzet Hari Ini</div>
                <div class="kpi-value text-success">Rp {{ number_format($s['revenue_today'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card kpi-card">
            <div class="card-body p-3">
                <div class="kpi-label mb-1">Omzet Bulan Ini</div>
                <div class="kpi-value text-primary">Rp {{ number_format($s['revenue_month'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="card kpi-card">
            <div class="card-body p-3">
                <div class="kpi-label mb-1">Profit Bulan Ini</div>
                <div class="kpi-value {{ $profitClass }}">Rp {{ number_format($s['profit'] ?? 0, 0, ',', '.') }}</div>
                <div class="kpi-sub">Setelah HPP & pengeluaran</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl">
        <div class="tech-card d-flex align-items-center gap-3">
            <div class="trophy"><i class="bi bi-trophy-fill"></i></div>
            <div>
                <div class="text-muted small text-uppercase fw-semibold">Teknisi Terbaik</div>
                <div class="fw-bold fs-5">{{ $s['top_technician_name'] ?? '-' }}</div>
                <div class="small text-muted">{{ number_format($s['top_technician_count'] ?? 0) }} service selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Charts --}}
    <div class="col-12 col-xl-8">
        <div class="row g-3">
            <div class="col-12">
                <div class="card chart-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-graph-up me-1"></i> Grafik Service Bulanan</span>
                        <span class="badge bg-light text-dark">12 bulan</span>
                    </div>
                    <div class="card-body">
                        <canvas id="serviceMonthlyChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card chart-card">
                    <div class="card-header"><i class="bi bi-cash-coin me-1"></i> Grafik Pendapatan</div>
                    <div class="card-body">
                        <canvas id="revenueMonthlyChart" height="140"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card chart-card">
                    <div class="card-header"><i class="bi bi-box-seam me-1"></i> Grafik Sparepart Terpakai</div>
                    <div class="card-body">
                        <canvas id="sparepartChart" height="140"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Menu --}}
    <div class="col-12 col-xl-4">
        <div class="card chart-card mb-3">
            <div class="card-header"><i class="bi bi-lightning-charge me-1"></i> Quick Menu</div>
            <div class="card-body">
                <div class="quick-menu-grid">
                    <a href="{{ route('service-orders.create') }}" class="quick-btn">
                        <i class="bi bi-plus-circle text-primary"></i>
                        Service Baru
                    </a>
                    <a href="{{ route('customers.create') }}" class="quick-btn">
                        <i class="bi bi-person-plus text-success"></i>
                        Pelanggan
                    </a>
                    <a href="{{ route('payments.create') }}" class="quick-btn">
                        <i class="bi bi-cash text-warning"></i>
                        Pembayaran
                    </a>
                    <a href="{{ route('spare-parts.index') }}" class="quick-btn">
                        <i class="bi bi-box-seam text-info"></i>
                        Spare Part
                    </a>
                    <a href="{{ route('purchases.create') }}" class="quick-btn">
                        <i class="bi bi-cart-plus text-secondary"></i>
                        Pembelian
                    </a>
                    <a href="{{ route('reports.revenue') }}" class="quick-btn">
                        <i class="bi bi-bar-chart text-danger"></i>
                        Laporan
                    </a>
                </div>
            </div>
        </div>

        <div class="card chart-card d-none d-xl-block">
            <div class="card-header">Ringkasan Keuangan</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Pendapatan</span>
                    <span class="fw-semibold text-success">Rp {{ number_format($s['revenue_month'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">HPP Sparepart</span>
                    <span class="fw-semibold text-danger">- Rp {{ number_format($s['cogs_month'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 small">
                    <span class="text-muted">Pengeluaran</span>
                    <span class="fw-semibold text-danger">- Rp {{ number_format($s['expenses_month'] ?? 0, 0, ',', '.') }}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Profit</span>
                    <span class="fw-bold {{ $profitClass }}">Rp {{ number_format($s['profit'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 10 Service Terbaru --}}
<div class="card chart-card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span><i class="bi bi-clock-history me-1"></i> 10 Service Terbaru</span>
        <a href="{{ route('service-orders.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-list-ul"></i> Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover recent-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Tiket</th>
                        <th class="d-none d-sm-table-cell">Pelanggan</th>
                        <th>Status</th>
                        <th class="d-none d-md-table-cell">Tgl Masuk</th>
                        <th class="text-end">Total</th>
                        <th class="text-center" style="width:50px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('service-orders.show', $order->id) }}" class="fw-semibold text-decoration-none">
                                {{ $order->ticket_number }}
                            </a>
                            <div class="d-sm-none small text-muted">{{ $order->customer_name ?? '-' }}</div>
                        </td>
                        <td class="d-none d-sm-table-cell">{{ $order->customer_name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $order->status_color ?? 'secondary' }}">{{ $order->status_label }}</span>
                        </td>
                        <td class="d-none d-md-table-cell">
                            {{ $order->entry_date ? \Carbon\Carbon::parse($order->entry_date)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="text-end fw-semibold">Rp {{ number_format($order->total_cost ?? 0, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <a href="{{ route('service-orders.show', $order->id) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                            Belum ada data service
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const fmtRp = v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v);
    const colors = {
        primary: '#3b82f6', success: '#22c55e', warning: '#f59e0b',
        info: '#06b6d4', purple: '#8b5cf6', pink: '#ec4899', orange: '#f97316'
    };
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } }
    };

    new Chart(document.getElementById('serviceMonthlyChart'), {
        type: 'line',
        data: {
            labels: @json($charts['month_labels'] ?? []),
            datasets: [{
                label: 'Service',
                data: @json($charts['service_monthly'] ?? []),
                borderColor: colors.primary,
                backgroundColor: 'rgba(59,130,246,.12)',
                fill: true,
                tension: .4,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            ...chartDefaults,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('revenueMonthlyChart'), {
        type: 'bar',
        data: {
            labels: @json($charts['month_labels'] ?? []),
            datasets: [{
                label: 'Pendapatan',
                data: @json($charts['revenue_monthly'] ?? []),
                backgroundColor: colors.success,
                borderRadius: 6,
                maxBarThickness: 40
            }]
        },
        options: {
            ...chartDefaults,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => fmtRp(v) }
                },
                x: { grid: { display: false } }
            }
        }
    });

    const spLabels = @json($charts['sparepart_labels'] ?? []);
    const spData = @json($charts['sparepart_qty'] ?? []);
    const spColors = [colors.primary, colors.success, colors.warning, colors.info, colors.purple, colors.pink, colors.orange, '#94a3b8'];

    new Chart(document.getElementById('sparepartChart'), {
        type: spLabels.length > 0 ? 'doughnut' : 'bar',
        data: spLabels.length > 0 ? {
            labels: spLabels,
            datasets: [{
                data: spData,
                backgroundColor: spColors.slice(0, spLabels.length),
                borderWidth: 0
            }]
        } : {
            labels: ['Belum ada data'],
            datasets: [{ data: [0], backgroundColor: ['#e2e8f0'] }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: spLabels.length > 0,
                    position: 'bottom',
                    labels: { boxWidth: 10, font: { size: 10 }, padding: 8 }
                }
            }
        }
    });
</script>
@endpush
