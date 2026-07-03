@extends('layouts.app')

@section('title', 'Edit Pembayaran')

@section('content')
@include('partials.page-header', [
    'title' => 'Edit Pembayaran',
    'breadcrumbs' => ['Pembayaran' => route('payments.index'), 'Edit' => '#']
])

<div class="card">
    <div class="card-body">
        @include('partials.form-errors')
        <form action="{{ route('payments.update', $payment) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Service Order</label>
                    <input type="text" class="form-control" value="{{ $payment->serviceOrder->ticket_number ?? '-' }}" disabled>
                    <input type="hidden" name="service_order_id" value="{{ $payment->service_order_id }}">
                </div>
                <div class="col-md-6">
                    <label for="amount" class="form-label">Jumlah <span class="text-danger">*</span></label>
                    <input type="number" min="1" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $payment->amount) }}" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label for="method" class="form-label">Metode</label>
                    <select class="form-select" id="method" name="method" required>
                        @foreach(\App\Enums\PaymentMethod::cases() as $method)
                            <option value="{{ $method->value }}" @selected(old('method', $payment->method?->value) === $method->value)>{{ $method->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="payment_date" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old('payment_date', $payment->payment_date?->format('Y-m-d')) }}" required>
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $payment->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Perbarui</button>
                <a href="{{ route('payments.show', $payment) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
