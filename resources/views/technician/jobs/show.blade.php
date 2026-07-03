@extends('layouts.app')

@section('title', 'Kerjakan — '.$order->ticket_number)

@push('styles')
<style>
    .work-panel .nav-pills .nav-link { font-size: .85rem; }
    .work-media-thumb { max-width: 100px; max-height: 80px; object-fit: cover; }
</style>
@endpush

@section('content')
@include('partials.page-header', [
    'title' => 'Pekerjaan — '.$order->ticket_number,
    'breadcrumbs' => [
        'Pekerjaan' => route('technician.jobs.index'),
        $order->ticket_number => '#',
    ],
    'actions' => '<a href="'.route('service-orders.show', $order).'" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i> Detail Lengkap</a>'
])

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Informasi Perangkat</span>
                @include('partials.service-order-status-badge', ['serviceOrder' => $order])
            </div>
            <div class="card-body">
                <div class="row g-2 small">
                    <div class="col-md-6"><span class="text-muted">Pelanggan:</span> <strong>{{ $order->customer->name ?? '-' }}</strong></div>
                    <div class="col-md-6"><span class="text-muted">HP:</span> {{ $order->contact_phone ?: ($order->customer->phone ?? '-') }}</div>
                    <div class="col-md-6"><span class="text-muted">Perangkat:</span> {{ $order->device_brand }} {{ $order->device_model }}</div>
                    <div class="col-md-6"><span class="text-muted">IMEI:</span> {{ $order->imei ?: '-' }}</div>
                    <div class="col-12"><span class="text-muted">Keluhan:</span> {{ $order->complaint }}</div>
                    @if($order->diagnosis)
                        <div class="col-12"><span class="text-muted">Diagnosa saat ini:</span> <span class="text-info">{{ $order->diagnosis }}</span></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card work-panel mb-3">
            <div class="card-header">Panel Pekerjaan</div>
            <div class="card-body">
                @include('partials.form-errors')

                <ul class="nav nav-pills flex-wrap gap-1 mb-3" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-diagnosis" type="button">Diagnosa</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-status" type="button">Status</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-spare" type="button">Spare Part</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-fee" type="button">Biaya Jasa</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-photo" type="button">Foto</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-video" type="button">Video</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-note" type="button">Catatan</button></li>
                    @if($order->status === \App\Enums\ServiceOrderStatus::Qc)
                    <li class="nav-item"><button class="nav-link text-danger" data-bs-toggle="pill" data-bs-target="#tab-qc" type="button">QC</button></li>
                    @endif
                </ul>

                <div class="tab-content">
                    {{-- Diagnosa --}}
                    <div class="tab-pane fade show active" id="tab-diagnosis">
                        <form action="{{ route('technician.jobs.diagnosis', $order) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Hasil Diagnosa <span class="text-danger">*</span></label>
                                <textarea name="diagnosis" class="form-control" rows="4" required placeholder="Jelaskan hasil pemeriksaan...">{{ old('diagnosis', $order->diagnosis) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-info"><i class="bi bi-search"></i> Simpan Diagnosa</button>
                        </form>
                    </div>

                    {{-- Status --}}
                    <div class="tab-pane fade" id="tab-status">
                        @if(count($allowedStatuses) > 0)
                        <form action="{{ route('technician.jobs.status', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label">Status Baru <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="">— Pilih —</option>
                                    @foreach($allowedStatuses as $status)
                                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan <span class="text-danger">*</span></label>
                                <textarea name="notes" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto (opsional)</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Ubah Status</button>
                        </form>
                        @else
                            <p class="text-muted mb-0">Tidak ada perubahan status yang diizinkan saat ini.</p>
                        @endif
                    </div>

                    {{-- Spare Part --}}
                    <div class="tab-pane fade" id="tab-spare">
                        <form action="{{ route('technician.jobs.spare-parts', $order) }}" method="POST" id="techPartsForm">
                            @csrf
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered table-sm" id="techPartsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Spare Part</th>
                                            <th style="width:15%" class="text-center">Qty</th>
                                            <th style="width:20%" class="text-end">Harga</th>
                                            <th style="width:10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="techPartsBody">
                                        @php
                                            $existingItems = $order->items->isNotEmpty()
                                                ? $order->items
                                                : collect([(object)['sparepart_id' => '', 'quantity' => 1, 'unit_price' => 0]]);
                                        @endphp
                                        @foreach($existingItems as $idx => $item)
                                        <tr>
                                            <td>
                                                <select class="form-select form-select-sm" name="items[{{ $idx }}][spare_part_id]" required>
                                                    <option value="">Pilih</option>
                                                    @foreach($spareParts as $part)
                                                        <option value="{{ $part->id }}" data-price="{{ $part->selling_price }}"
                                                            @selected(($item->sparepart_id ?? '') == $part->id)>
                                                            {{ $part->code }} — {{ $part->name }} (Stok: {{ $part->stock }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" min="1" class="form-control form-control-sm text-center" name="items[{{ $idx }}][quantity]" value="{{ $item->quantity ?? 1 }}" required></td>
                                            <td><input type="number" min="0" step="0.01" class="form-control form-control-sm text-end part-price-input" name="items[{{ $idx }}][price]" value="{{ $item->unit_price ?? 0 }}"></td>
                                            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-tech-row"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="addTechPartRow"><i class="bi bi-plus"></i> Tambah Baris</button>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary"><i class="bi bi-box-seam"></i> Simpan Spare Part</button>
                        </form>
                    </div>

                    {{-- Biaya Jasa --}}
                    <div class="tab-pane fade" id="tab-fee">
                        <form action="{{ route('technician.jobs.service-fee', $order) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Biaya Jasa (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="service_fee" class="form-control" min="0" step="1000" value="{{ old('service_fee', $order->service_fee) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                            <p class="small text-muted">Total saat ini: <strong>Rp {{ number_format($order->total_cost, 0, ',', '.') }}</strong></p>
                            <button type="submit" class="btn btn-warning"><i class="bi bi-currency-dollar"></i> Simpan Biaya Jasa</button>
                        </form>
                    </div>

                    {{-- Foto --}}
                    <div class="tab-pane fade" id="tab-photo">
                        <form action="{{ route('technician.jobs.photo', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Foto <span class="text-danger">*</span></label>
                                <input type="file" name="photo" class="form-control" accept="image/*" required>
                                <div class="form-text">Otomatis diresize ke WebP max 1280px</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="caption" class="form-control" placeholder="Contoh: Kondisi setelah perbaikan">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark"><i class="bi bi-camera"></i> Upload Foto</button>
                        </form>
                    </div>

                    {{-- Video --}}
                    <div class="tab-pane fade" id="tab-video">
                        <form action="{{ route('technician.jobs.video', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Video <span class="text-danger">*</span></label>
                                <input type="file" name="video" class="form-control" accept="video/*" required>
                                <div class="form-text">Format: MP4, MOV, AVI, WebM. Maks 50 MB.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="caption" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark"><i class="bi bi-camera-video"></i> Upload Video</button>
                        </form>
                    </div>

                    {{-- Catatan --}}
                    <div class="tab-pane fade" id="tab-note">
                        <form action="{{ route('technician.jobs.note', $order) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Catatan Pekerjaan <span class="text-danger">*</span></label>
                                <textarea name="notes" class="form-control" rows="4" required placeholder="Tulis catatan progress pekerjaan..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-chat-left-text"></i> Tambah Catatan</button>
                        </form>
                    </div>

                    {{-- QC --}}
                    @if($order->status === \App\Enums\ServiceOrderStatus::Qc)
                    <div class="tab-pane fade" id="tab-qc">
                        <div class="alert alert-info small">Lakukan pemeriksaan kualitas sebelum menyerahkan ke pelanggan.</div>
                        <form action="{{ route('technician.jobs.qc', $order) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Hasil QC <span class="text-danger">*</span></label>
                                <select name="qc_result" class="form-select" required>
                                    <option value="">— Pilih —</option>
                                    <option value="pass">Lulus — Selesai</option>
                                    <option value="fail">Gagal — Kembali Proses Service</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan QC <span class="text-danger">*</span></label>
                                <textarea name="notes" class="form-control" rows="3" required placeholder="Hasil pemeriksaan QC..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success"><i class="bi bi-clipboard-check"></i> Simpan Hasil QC</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($order->workImages->isNotEmpty() || $order->workVideos->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header">Media Pekerjaan</div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($order->workImages as $img)
                    <div class="col-4 col-md-3">
                        <a href="{{ $img->url }}" target="_blank"><img src="{{ $img->url }}" class="img-thumbnail w-100 work-media-thumb" alt="{{ $img->caption }}"></a>
                        <div class="small text-muted text-truncate">{{ $img->caption }}</div>
                    </div>
                    @endforeach
                    @foreach($order->workVideos as $vid)
                    <div class="col-12 col-md-6">
                        <video controls class="w-100 rounded" style="max-height:200px">
                            <source src="{{ $vid->url }}">
                        </video>
                        <div class="small text-muted">{{ $vid->caption }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">Ringkasan Biaya</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->sparePart->name ?? '-' }} ×{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-light">
                            <td>Biaya Jasa</td>
                            <td class="text-end">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="table-primary">
                            <td class="fw-bold">Total</td>
                            <td class="text-end fw-bold">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Riwayat Pekerjaan</div>
            <div class="card-body" style="max-height:520px; overflow-y:auto">
                @include('partials.work-history-timeline', ['workLogs' => $order->workLogs])
            </div>
        </div>
    </div>
</div>

<template id="techPartRowTpl">
    <tr>
        <td>
            <select class="form-select form-select-sm" name="items[__IDX__][spare_part_id]" required>
                <option value="">Pilih</option>
                @foreach($spareParts as $part)
                    <option value="{{ $part->id }}" data-price="{{ $part->selling_price }}">{{ $part->code }} — {{ $part->name }} (Stok: {{ $part->stock }})</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" min="1" class="form-control form-control-sm text-center" name="items[__IDX__][quantity]" value="1" required></td>
        <td><input type="number" min="0" step="0.01" class="form-control form-control-sm text-end part-price-input" name="items[__IDX__][price]" value="0"></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger btn-remove-tech-row"><i class="bi bi-trash"></i></button></td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
$(function () {
    let techPartIdx = $('#techPartsBody tr').length;

    $('#addTechPartRow').on('click', function () {
        $('#techPartsBody').append($('#techPartRowTpl').html().replace(/__IDX__/g, techPartIdx++));
    });

    $(document).on('click', '.btn-remove-tech-row', function () {
        if ($('#techPartsBody tr').length > 1) {
            $(this).closest('tr').remove();
        }
    });

    $(document).on('change', '#techPartsBody select', function () {
        const price = $(this).find(':selected').data('price') || 0;
        $(this).closest('tr').find('.part-price-input').val(price);
    });
});
</script>
@endpush
