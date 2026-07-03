@extends('layouts.app')

@section('title', 'Tambah Pembayaran')

@section('content')
@include('partials.page-header', [
    'title' => 'Tambah Pembayaran',
    'breadcrumbs' => ['Pembayaran' => route('payments.index'), 'Tambah' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('payments.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="service_order_id" class="form-label">Service Order <span class="text-danger">*</span></label>
                    <select class="form-select @error('service_order_id') is-invalid @enderror" id="service_order_id" name="service_order_id" required>
                        <option value="">Pilih Service Order</option>
                        @foreach($serviceOrders ?? [] as $order)
                            <option value="{{ $order->id }}" data-remaining="{{ $order->remaining_payment }}"
                                @selected(old('service_order_id', request('service_order_id')) == $order->id)>
                                {{ $order->ticket_number }} — {{ $order->customer->name ?? '' }} (Sisa: Rp {{ number_format($order->remaining_payment, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('service_order_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="amount" class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" min="1" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="method" class="form-label">Metode <span class="text-danger">*</span></label>
                    <select class="form-select @error('method') is-invalid @enderror" id="method" name="method" required>
                        @foreach(\App\Enums\PaymentMethod::cases() as $method)
                            <option value="{{ $method->value }}" @selected(old('method') === $method->value)>{{ $method->label() }}</option>
                        @endforeach
                    </select>
                    @error('method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="payment_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                    @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">Cetak Nota Kasir</div>
    <div class="card-body">
        <p class="text-muted small">Jika Anda sudah memiliki ID pembayaran, gunakan form ini untuk mencetak nota kasir.</p>
        <form class="row g-2" id="paymentPrintForm">
            <div class="col-md-8">
                <input type="number" min="1" class="form-control" id="paymentId" placeholder="ID Pembayaran" required>
            </div>
            <div class="col-md-4 d-grid">
                <button type="submit" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak Nota</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#service_order_id').on('change', function () {
        const remaining = $(this).find(':selected').data('remaining');
        if (remaining) $('#amount').attr('max', remaining).val(remaining);
    }).trigger('change');

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
