@extends('layouts.app')

@section('title', 'Pekerjaan Teknisi')

@section('content')
@include('partials.page-header', [
    'title' => 'Daftar Pekerjaan',
    'breadcrumbs' => ['Pekerjaan Teknisi' => '#']
])

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="bi bi-tools text-primary fs-4"></i></div>
                <div>
                    <div class="text-muted small">Pekerjaan Aktif</div>
                    <div class="fs-4 fw-bold">{{ $stats['active'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3"><i class="bi bi-clipboard-check text-info fs-4"></i></div>
                <div>
                    <div class="text-muted small">Menunggu QC</div>
                    <div class="fs-4 fw-bold">{{ $stats['qc'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="No. tiket, pelanggan, perangkat...">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @foreach(\App\Enums\ServiceOrderStatus::workflow() as $status)
                        <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Tab</label>
                <select name="tab" class="form-select">
                    <option value="active" @selected(($filters['tab'] ?? 'active') === 'active')>Aktif</option>
                    <option value="completed" @selected(($filters['tab'] ?? '') === 'completed')>Selesai</option>
                    <option value="all" @selected(($filters['tab'] ?? '') === 'all')>Semua</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Service</th>
                        <th>Pelanggan</th>
                        <th>Perangkat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        @if($user->role !== 'teknisi')
                            <th>Teknisi</th>
                        @endif
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    <tr>
                        <td class="fw-semibold">{{ $job->ticket_number }}</td>
                        <td>{{ $job->customer->name ?? '-' }}</td>
                        <td>{{ $job->device_brand }} {{ $job->device_model }}</td>
                        <td>@include('partials.service-order-status-badge', ['serviceOrder' => $job])</td>
                        <td>{{ $job->entry_date?->format('d/m/Y') }}</td>
                        @if($user->role !== 'teknisi')
                            <td>{{ $job->technician?->user?->name ?? '-' }}</td>
                        @endif
                        <td class="text-center">
                            <a href="{{ route('technician.jobs.show', $job) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-wrench"></i> Kerjakan
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $user->role !== 'teknisi' ? 7 : 6 }}" class="text-center text-muted py-4">
                            Tidak ada pekerjaan ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($jobs->hasPages())
    <div class="card-footer">{{ $jobs->links() }}</div>
    @endif
</div>
@endsection
