@extends('layouts.app')

@section('title', 'Detail Service Order')

@push('styles')
<style>
    .workflow-step { display: flex; flex-direction: column; align-items: center; flex: 1; min-width: 70px; position: relative; }
    .workflow-step .step-dot {
        width: 28px; height: 28px; border-radius: 50%; background: #e2e8f0; color: #64748b;
        display: flex; align-items: center; justify-content: center; font-size: .7rem; font-weight: 700;
        border: 2px solid #fff; box-shadow: 0 0 0 2px #e2e8f0; z-index: 1;
    }
    .workflow-step.completed .step-dot { background: #22c55e; color: #fff; box-shadow: 0 0 0 2px #22c55e; }
    .workflow-step.active .step-dot { background: #3b82f6; color: #fff; box-shadow: 0 0 0 2px #3b82f6; }
    .workflow-step.cancelled .step-dot { background: #ef4444; color: #fff; box-shadow: 0 0 0 2px #ef4444; }
    .workflow-step .step-label { font-size: .65rem; text-align: center; margin-top: .35rem; color: #64748b; line-height: 1.2; }
    .workflow-step.active .step-label { color: #1e40af; font-weight: 600; }
    .workflow-bar { display: flex; gap: 0; overflow-x: auto; padding: .5rem 0; position: relative; }
    .workflow-bar::before {
        content: ''; position: absolute; top: 14px; left: 5%; right: 5%; height: 2px; background: #e2e8f0; z-index: 0;
    }
    .history-timeline { position: relative; padding-left: 1.5rem; }
    .history-timeline::before { content: ''; position: absolute; left: .4rem; top: 0; bottom: 0; width: 2px; background: #e2e8f0; }
    .history-item { position: relative; padding-bottom: 1.25rem; }
    .history-item::before {
        content: ''; position: absolute; left: -1.35rem; top: .35rem;
        width: 10px; height: 10px; border-radius: 50%; background: #3b82f6; border: 2px solid #fff;
    }
    .history-photo { max-width: 120px; border-radius: .5rem; cursor: pointer; }
</style>
@endpush

@section('content')
@php
    $canWorkPanel = auth()->user()?->isTeknisi() || auth()->user()?->isOwner() || auth()->user()?->isAdmin();
    $headerActions = '
        <div class="btn-group">
            <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-printer"></i> Cetak LX-300</button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="'.route('print.service-orders.receipt', $serviceOrder).'?preview=1" target="_blank">Bukti Penerimaan</a></li>
                <li><a class="dropdown-item" href="'.route('print.service-orders.invoice', $serviceOrder).'?preview=1" target="_blank">Invoice</a></li>
                <li><a class="dropdown-item" href="'.route('print.service-orders.warranty', $serviceOrder).'?preview=1" target="_blank">Kartu Garansi</a></li>
                <li><a class="dropdown-item" href="'.route('print.service-orders.label', $serviceOrder).'?preview=1&paper=continuous" target="_blank">Label Service</a></li>
            </ul>
        </div>
        <a href="'.route('service-orders.export.pdf', $serviceOrder).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> PDF</a>';
    if ($canWorkPanel) {
        $headerActions .= ' <a href="'.route('technician.jobs.show', $serviceOrder).'" class="btn btn-primary"><i class="bi bi-wrench"></i> Panel Teknisi</a>';
    }
    if ($serviceOrder->invoice) {
        $headerActions .= ' <a href="'.route('invoices.pdf', $serviceOrder->invoice).'" class="btn btn-success" target="_blank"><i class="bi bi-file-pdf"></i> Invoice PDF</a>';
        $headerActions .= ' <a href="'.route('invoices.whatsapp', $serviceOrder->invoice).'" class="btn btn-success" target="_blank"><i class="bi bi-whatsapp"></i> Kirim WA</a>';
    }
    $headerActions .= ' <a href="'.route('service-orders.edit', $serviceOrder).'" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>';
@endphp
@include('partials.page-header', [
    'title' => 'Service Order — '.$serviceOrder->ticket_number,
    'breadcrumbs' => ['Service Order' => route('service-orders.index'), $serviceOrder->ticket_number => '#'],
    'actions' => $headerActions,
])

@if(session('invoice_generated'))
    @php $inv = \App\Models\ServiceInvoice::find(session('invoice_generated')); @endphp
    @if($inv)
    <div class="alert alert-success alert-dismissible fade show">
        <strong>Pembayaran lunas!</strong> Invoice <code>{{ $inv->invoice_number }}</code> telah digenerate otomatis.
        <a href="{{ route('invoices.pdf', $inv) }}" class="alert-link" target="_blank">Unduh PDF</a> |
        <a href="{{ route('invoices.whatsapp', $inv) }}" class="alert-link" target="_blank">Kirim via WhatsApp</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
@endif

@if(session('payment_created_id'))
    @php $payment = \App\Models\Payment::find(session('payment_created_id')); @endphp
    @if($payment)
    <div class="alert alert-info alert-dismissible fade show">
        <strong>Pembayaran berhasil dicatat!</strong> Cetak nota kasir sekarang untuk pembayaran ini.
        <a href="{{ route('print.payments.cashier-receipt', $payment) }}?preview=1" class="alert-link" target="_blank">Cetak Nota Kasir</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
@endif

<div class="card mb-3">
    <div class="card-body py-3">
        @php
            $workflow = \App\Enums\ServiceOrderStatus::workflow();
            $isCancelled = $serviceOrder->status === \App\Enums\ServiceOrderStatus::Cancelled;
            $currentOrder = $serviceOrder->status?->order() ?? 0;
        @endphp
        <div class="workflow-bar">
            @foreach($workflow as $step)
                @php
                    $class = '';
                    if ($isCancelled) {
                        $class = '';
                    } elseif ($step->order() < $currentOrder) {
                        $class = 'completed';
                    } elseif ($step === $serviceOrder->status) {
                        $class = 'active';
                    }
                @endphp
                <div class="workflow-step {{ $class }}">
                    <div class="step-dot">{{ $step->order() }}</div>
                    <div class="step-label">{{ $step->label() }}</div>
                </div>
            @endforeach
            @if($isCancelled)
                <div class="workflow-step cancelled ms-2">
                    <div class="step-dot"><i class="bi bi-x"></i></div>
                    <div class="step-label">Dibatalkan</div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Informasi Service</span>
                @include('partials.service-order-status-badge', ['serviceOrder' => $serviceOrder])
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm mb-0">
                            <tr><td class="text-muted" width="130">No. Tiket</td><td class="fw-semibold">{{ $serviceOrder->ticket_number }}</td></tr>
                            <tr><td class="text-muted">Pelanggan</td><td>{{ $serviceOrder->customer->name ?? '-' }}</td></tr>
                            <tr><td class="text-muted">Nomor HP</td><td>{{ $serviceOrder->contact_phone ?: ($serviceOrder->customer->phone ?? '-') }}</td></tr>
                            <tr><td class="text-muted">Perangkat</td><td>{{ $serviceOrder->device_brand }} {{ $serviceOrder->device_model }}</td></tr>
                            <tr><td class="text-muted">Warna</td><td>{{ $serviceOrder->color ?: '-' }}</td></tr>
                            <tr><td class="text-muted">IMEI</td><td>{{ $serviceOrder->imei ?: '-' }}</td></tr>
                            <tr><td class="text-muted">Password HP</td><td>{{ $serviceOrder->device_password ?: '-' }}</td></tr>
                            <tr><td class="text-muted">Teknisi</td><td>{{ $serviceOrder->technician?->user?->name ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm mb-0">
                            <tr><td class="text-muted" width="130">Tgl Masuk</td><td>{{ $serviceOrder->entry_date?->format('d/m/Y') }}</td></tr>
                            <tr><td class="text-muted">Estimasi</td><td>Rp {{ number_format($serviceOrder->estimated_cost ?? 0, 0, ',', '.') }}</td></tr>
                            <tr><td class="text-muted">Tgl Selesai</td><td>{{ $serviceOrder->completion_date?->format('d/m/Y') ?: '-' }}</td></tr>
                            <tr><td class="text-muted">Tgl Diambil</td><td>{{ $serviceOrder->pickup_date?->format('d/m/Y') ?: '-' }}</td></tr>
                            <tr><td class="text-muted">Mulai Garansi</td><td>{{ $serviceOrder->warranty_start_date?->format('d/m/Y') ?: '-' }}</td></tr>
                            <tr><td class="text-muted">Closed</td><td>{{ $serviceOrder->closed_date?->format('d/m/Y') ?: '-' }}</td></tr>
                            <tr><td class="text-muted">Garansi</td><td>{{ $serviceOrder->warranty_days }} hari</td></tr>
                        </table>
                    </div>
                    <div class="col-12">
                        <div class="mb-2"><strong>Keluhan:</strong> {{ $serviceOrder->complaint }}</div>
                        @if($serviceOrder->completeness)
                            <div class="mb-2"><strong>Kelengkapan:</strong> {{ $serviceOrder->completeness }}</div>
                        @endif
                        @if($serviceOrder->diagnosis)
                            <div class="mb-2"><strong>Diagnosa:</strong> {{ $serviceOrder->diagnosis }}</div>
                        @endif
                        @if($serviceOrder->notes)
                            <div><strong>Catatan:</strong> {{ $serviceOrder->notes }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($serviceOrder->intakeImages->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header">Foto Penerimaan</div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($serviceOrder->intakeImages as $image)
                    <div class="col-6 col-md-3">
                        <div class="text-muted small mb-1">{{ $image->caption ?? $image->type }}</div>
                        <a href="{{ $image->url }}" target="_blank">
                            <img src="{{ $image->url }}" alt="{{ $image->caption }}" class="img-thumbnail w-100">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="card mb-3">
            <div class="card-header">Riwayat Perubahan Status</div>
            <div class="card-body">
                @if($serviceOrder->statusHistories->isEmpty())
                    <p class="text-muted mb-0">Belum ada riwayat status.</p>
                @else
                    <div class="history-timeline">
                        @foreach($serviceOrder->statusHistories as $history)
                        <div class="history-item">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <span class="badge bg-{{ $history->to_status?->color() ?? 'secondary' }}">{{ $history->to_status_label }}</span>
                                    @if($history->from_status_label)
                                        <small class="text-muted ms-1">dari {{ $history->from_status_label }}</small>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i> {{ $history->changed_at->format('d/m/Y') }}
                                    <i class="bi bi-clock ms-1"></i> {{ $history->changed_at->format('H:i') }}
                                </small>
                            </div>
                            <div class="small mt-1">
                                <i class="bi bi-person"></i> {{ $history->user->name ?? '-' }}
                                <span class="text-muted">({{ $history->user->roleLabel() ?? '-' }})</span>
                            </div>
                            @if($history->notes)
                                <div class="mt-2 small bg-light rounded p-2">{{ $history->notes }}</div>
                            @endif
                            @if($history->photo_url)
                                <div class="mt-2">
                                    <a href="{{ $history->photo_url }}" target="_blank">
                                        <img src="{{ $history->photo_url }}" alt="Foto status" class="history-photo img-thumbnail">
                                    </a>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if(auth()->user()?->isOwner() || auth()->user()?->isAdmin())
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history"></i> Riwayat Pekerjaan Teknisi</span>
                <span class="badge bg-secondary">{{ $serviceOrder->workLogs->count() }} aktivitas</span>
            </div>
            <div class="card-body">
                @include('partials.work-history-timeline', ['workLogs' => $serviceOrder->workLogs])
            </div>
        </div>
        @endif

        <div class="card mb-3">
            <div class="card-header">Spare Part Digunakan</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Spare Part</th><th class="text-center">Qty</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                        @forelse($serviceOrder->items as $item)
                        <tr>
                            <td>{{ $item->sparePart->name ?? '-' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Tidak ada spare part</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr><td colspan="3" class="text-end">Biaya Jasa</td><td class="text-end">Rp {{ number_format($serviceOrder->service_fee, 0, ',', '.') }}</td></tr>
                        <tr><td colspan="3" class="text-end fw-bold">Total</td><td class="text-end fw-bold">Rp {{ number_format($serviceOrder->total_cost, 0, ',', '.') }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Riwayat Pembayaran</span>
                @if($serviceOrder->remaining_payment > 0 && !in_array($serviceOrder->status?->value, ['cancelled', 'closed']))
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
                    <i class="bi bi-cash"></i> Tambah Pembayaran
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Tanggal</th><th>Metode</th><th>Kasir</th><th>Catatan</th><th class="text-end">Jumlah</th></tr></thead>
                    <tbody>
                        @forelse($serviceOrder->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date?->format('d/m/Y') }}</td>
                            <td>{{ $payment->method_label }}</td>
                            <td>{{ $payment->user->name ?? '-' }}</td>
                            <td>{{ $payment->notes ?: '-' }}</td>
                            <td class="text-end">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pembayaran</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr><td colspan="4" class="text-end">Total Dibayar</td><td class="text-end fw-semibold text-success">Rp {{ number_format($serviceOrder->paid_amount, 0, ',', '.') }}</td></tr>
                        <tr><td colspan="4" class="text-end">Sisa Pembayaran</td><td class="text-end fw-bold {{ $serviceOrder->remaining_payment > 0 ? 'text-danger' : 'text-success' }}">Rp {{ number_format($serviceOrder->remaining_payment, 0, ',', '.') }}</td></tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($serviceOrder->invoice)
        <div class="card mb-3 border-success">
            <div class="card-header bg-success bg-opacity-10 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt"></i> Invoice — {{ $serviceOrder->invoice->invoice_number }}</span>
                <div class="d-flex gap-2">
                    <a href="{{ route('invoices.pdf', $serviceOrder->invoice) }}" class="btn btn-sm btn-success" target="_blank"><i class="bi bi-download"></i> PDF</a>
                    <a href="{{ route('invoices.whatsapp', $serviceOrder->invoice) }}" class="btn btn-sm btn-outline-success" target="_blank"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                    <a href="{{ route('invoices.track', $serviceOrder->uuid) }}" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="bi bi-qr-code"></i> Tracking</a>
                </div>
            </div>
            <div class="card-body small">
                <div class="row g-2">
                    <div class="col-md-4">Total: <strong>Rp {{ number_format($serviceOrder->invoice->total, 0, ',', '.') }}</strong></div>
                    <div class="col-md-4">Dibayar: <strong>Rp {{ number_format($serviceOrder->invoice->paid_amount, 0, ',', '.') }}</strong></div>
                    <div class="col-md-4">Digenerate: {{ $serviceOrder->invoice->generated_at?->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        @if(count($allowedStatuses) > 0)
        <div class="card mb-3">
            <div class="card-header">Ubah Status</div>
            <div class="card-body">
                @include('partials.form-errors')
                <form action="{{ route('service-orders.update-status', $serviceOrder) }}" method="POST" enctype="multipart/form-data" id="statusForm">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Status Baru <span class="text-danger">*</span></label>
                        <select name="status" id="statusSelect" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">— Pilih Status —</option>
                            @foreach($allowedStatuses as $status)
                                <option value="{{ $status->value }}" @selected(old('status') === $status->value)>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @if($serviceOrder->status === \App\Enums\ServiceOrderStatus::Diagnosis || $serviceOrder->status === \App\Enums\ServiceOrderStatus::Received)
                    <div class="mb-3" id="diagnosisField">
                        <label class="form-label">Diagnosa</label>
                        <textarea name="diagnosis" class="form-control" rows="2" placeholder="Hasil diagnosa...">{{ old('diagnosis', $serviceOrder->diagnosis) }}</textarea>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Catatan <span class="text-danger">*</span></label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" required placeholder="Catatan perubahan status...">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto <small class="text-muted">(opsional)</small></label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                        <div class="form-text">Unggah foto kondisi unit jika diperlukan (max 5 MB).</div>
                        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="btnSubmitStatus">
                        <i class="bi bi-arrow-right-circle"></i> Simpan Perubahan Status
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card mb-3">
            <div class="card-body text-center text-muted py-4">
                <i class="bi bi-check-circle fs-1 text-success d-block mb-2"></i>
                Service order dalam status <strong>{{ $serviceOrder->status_label }}</strong>.
                @if(!$serviceOrder->status?->isTerminal())
                    <div class="small mt-1">Tidak ada transisi status yang tersedia untuk role Anda.</div>
                @endif
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">Workflow Service</div>
            <div class="card-body small text-muted">
                <ol class="mb-0 ps-3">
                    @foreach(\App\Enums\ServiceOrderStatus::workflow() as $step)
                        <li class="mb-1 {{ $step === $serviceOrder->status ? 'fw-bold text-primary' : '' }}">{{ $step->label() }}</li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</div>

@if($serviceOrder->remaining_payment > 0)
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="service_order_id" value="{{ $serviceOrder->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small">Sisa pembayaran: <strong>Rp {{ number_format($serviceOrder->remaining_payment, 0, ',', '.') }}</strong></div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" min="1" max="{{ $serviceOrder->remaining_payment }}" step="0.01" value="{{ $serviceOrder->remaining_payment }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode <span class="text-danger">*</span></label>
                        <select name="method" class="form-select" required>
                            @foreach(\App\Enums\PaymentMethod::cases() as $method)
                                <option value="{{ $method->value }}">{{ $method->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-cash"></i> Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    $('#statusForm').on('submit', function (e) {
        e.preventDefault();
        const status = $('#statusSelect option:selected').text();
        Swal.fire({
            title: 'Ubah Status?',
            text: 'Ubah status menjadi "' + status + '"?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then(r => { if (r.isConfirmed) e.target.submit(); });
    });
</script>
@endpush
