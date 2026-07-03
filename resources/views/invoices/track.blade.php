<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracking — {{ $order->ticket_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:520px">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h5 class="mb-1">{{ $settings['shop_name'] ?? 'Service HP' }}</h5>
            <p class="text-muted small mb-3">Lacak Status Service</p>
            <div class="display-6 fw-bold text-primary">{{ $order->ticket_number }}</div>
            <span class="badge bg-secondary mt-2">{{ $order->status?->label() }}</span>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between"><span>Pelanggan</span><strong>{{ $order->customer?->name }}</strong></li>
            <li class="list-group-item d-flex justify-content-between"><span>Perangkat</span><span>{{ $order->device_brand }} {{ $order->device_model }}</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>Tgl Masuk</span><span>{{ $order->entry_date?->format('d/m/Y') }}</span></li>
            @if($order->completion_date)
            <li class="list-group-item d-flex justify-content-between"><span>Tgl Selesai</span><span>{{ $order->completion_date->format('d/m/Y') }}</span></li>
            @endif
            @if($order->invoice)
            <li class="list-group-item d-flex justify-content-between"><span>Invoice</span><span>{{ $order->invoice->invoice_number }}</span></li>
            @endif
            <li class="list-group-item d-flex justify-content-between"><span>Garansi</span><span>{{ $order->warranty_days }} hari</span></li>
        </ul>
        <div class="card-body small text-muted text-center">
            Hubungi {{ $settings['shop_phone'] ?? 'toko' }} untuk informasi lebih lanjut.
        </div>
    </div>
</div>
</body>
</html>
