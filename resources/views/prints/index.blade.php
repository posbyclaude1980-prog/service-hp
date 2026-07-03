@extends('layouts.app')

@section('title', 'Cetak Dot Matrix')

@section('content')
@include('partials.page-header', [
    'title' => 'Cetak Dot Matrix (Epson LX-300)',
    'breadcrumbs' => ['Cetak' => '#'],
])

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Template Service Order</div>
            <div class="card-body">
                <p class="text-muted small">Buka dari halaman detail service order, atau masukkan ID order:</p>
                <form class="row g-2" method="GET" action="#" id="orderPrintForm">
                    <div class="col-8">
                        <input type="number" min="1" class="form-control" id="orderId" placeholder="ID Service Order" required>
                    </div>
                    <div class="col-4">
                        <select class="form-select" id="orderTemplate">
                            <option value="receipt">Bukti Penerimaan</option>
                            <option value="invoice">Invoice</option>
                            <option value="warranty">Kartu Garansi</option>
                            <option value="label">Label Service</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-printer"></i> Print Preview</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Template Kasir</div>
            <div class="card-body">
                <p class="text-muted small">Masukkan ID pembayaran untuk mencetak nota kasir.</p>
                <form class="row g-2" id="paymentPrintForm">
                    <div class="col-8">
                        <input type="number" min="1" class="form-control" id="paymentId" placeholder="ID Pembayaran" required>
                    </div>
                    <div class="col-4 d-grid">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">Laporan</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Laporan Harian</label>
                    <form method="GET" action="{{ route('print.reports.daily') }}" class="d-flex gap-2">
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                        <input type="hidden" name="preview" value="1">
                        <button type="submit" class="btn btn-outline-primary text-nowrap"><i class="bi bi-eye"></i> Preview</button>
                    </form>
                </div>
                <div>
                    <label class="form-label">Laporan Bulanan</label>
                    <form method="GET" action="{{ route('print.reports.monthly') }}" class="row g-2">
                        <div class="col-6">
                            <select name="month" class="form-select">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" @selected($m == (int) date('n'))>{{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <input type="number" name="year" class="form-control" value="{{ date('Y') }}" min="2020" max="2099">
                        </div>
                        <div class="col-12">
                            <input type="hidden" name="preview" value="1">
                            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-eye"></i> Preview Laporan Bulanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">Panduan Cetak Epson LX-300</div>
            <div class="card-body small">
                <ul class="mb-0">
                    <li>Gunakan font <strong>monospace (Courier New)</strong> — sudah diterapkan otomatis.</li>
                    <li>Pilih ukuran kertas: <strong>A4</strong>, <strong>F4</strong>, atau <strong>Continuous Form</strong> di toolbar preview.</li>
                    <li>Label service disarankan menggunakan <strong>Continuous Form</strong>.</li>
                    <li>Klik <strong>Cetak</strong> untuk print preview browser, atau <strong>Buka &amp; Cetak Langsung</strong>.</li>
                    <li>Pastikan printer Epson LX-300 terpilih di dialog cetak browser.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#orderPrintForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#orderId').val();
    const tpl = $('#orderTemplate').val();
    const routes = {
        receipt: '{{ url('print/service-orders') }}/' + id + '/receipt?preview=1',
        invoice: '{{ url('print/service-orders') }}/' + id + '/invoice?preview=1',
        warranty: '{{ url('print/service-orders') }}/' + id + '/warranty?preview=1',
        label: '{{ url('print/service-orders') }}/' + id + '/label?preview=1&paper=continuous',
    };
    window.open(routes[tpl], '_blank');
});

$('#paymentPrintForm').on('submit', function (e) {
    e.preventDefault();
    const id = $('#paymentId').val();
    if (!id) {
        return;
    }
    window.open('{{ url('print/payments') }}/' + id + '/cashier-receipt?preview=1', '_blank');
});
</script>
@endpush
