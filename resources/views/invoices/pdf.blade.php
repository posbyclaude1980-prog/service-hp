<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        .header-table { width: 100%; margin-bottom: 14px; }
        .header-table td { vertical-align: top; border: none; padding: 0; }
        .shop-name { font-size: 18px; font-weight: bold; color: #1e40af; }
        .invoice-title { font-size: 20px; font-weight: bold; text-align: right; }
        .invoice-meta { text-align: right; font-size: 10px; line-height: 1.5; }
        .logo { max-height: 60px; max-width: 140px; }
        .section-title { font-size: 11px; font-weight: bold; background: #e8eef7; padding: 4px 8px; margin: 10px 0 6px; }
        .info-table { width: 100%; margin-bottom: 8px; }
        .info-table td { padding: 2px 4px; vertical-align: top; border: none; }
        .info-label { width: 100px; color: #444; }
        .items-table { width: 100%; border-collapse: collapse; margin: 8px 0; }
        .items-table th, .items-table td { border: 1px solid #bbb; padding: 5px 6px; }
        .items-table th { background: #f3f4f6; font-weight: bold; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals-table { width: 45%; margin-left: auto; border-collapse: collapse; }
        .totals-table td { padding: 4px 8px; border: none; }
        .totals-table .grand { font-size: 13px; font-weight: bold; border-top: 2px solid #333; }
        .codes-table { width: 100%; margin-top: 14px; border-collapse: collapse; }
        .codes-table td { text-align: center; vertical-align: top; border: none; padding: 4px; width: 33%; }
        .code-img { width: 110px; height: 110px; }
        .barcode-img { max-width: 220px; height: 45px; }
        .code-label { font-size: 9px; color: #555; margin-top: 4px; }
        .warranty-box { border: 1px dashed #666; padding: 8px; margin: 10px 0; font-size: 10px; }
        .footer { margin-top: 16px; padding-top: 8px; border-top: 1px solid #ccc; text-align: center; font-size: 9px; color: #555; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width:55%">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo" alt="Logo"><br>
                @endif
                <div class="shop-name">{{ $settings['shop_name'] ?? 'Service HP' }}</div>
                <div>{{ $settings['shop_address'] ?? '' }}</div>
                <div>Telp: {{ $settings['shop_phone'] ?? '-' }}</div>
                @if(!empty($settings['shop_email']))
                    <div>Email: {{ $settings['shop_email'] }}</div>
                @endif
            </td>
            <td style="width:45%">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-meta">
                    <strong>No. Invoice</strong><br>{{ $invoiceNumber }}<br><br>
                    <strong>No. Service</strong><br>{{ $serviceOrder->ticket_number }}<br><br>
                    Tanggal: {{ $generatedAt->format('d/m/Y H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">DATA PELANGGAN</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Nama</td><td>: {{ $serviceOrder->customer?->name }}</td>
            <td class="info-label">Telepon</td><td>: {{ $serviceOrder->contact_phone ?: $serviceOrder->customer?->phone }}</td>
        </tr>
        <tr>
            <td class="info-label">Alamat</td><td colspan="3">: {{ $serviceOrder->customer?->address ?: '-' }}</td>
        </tr>
    </table>

    <div class="section-title">DATA PERANGKAT (HP)</div>
    <table class="info-table">
        <tr>
            <td class="info-label">Merk / Model</td><td>: {{ $serviceOrder->device_brand }} {{ $serviceOrder->device_model }}</td>
            <td class="info-label">Warna</td><td>: {{ $serviceOrder->color ?: '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">IMEI</td><td>: {{ $serviceOrder->imei ?: '-' }}</td>
            <td class="info-label">Teknisi</td><td>: {{ $serviceOrder->technician?->user?->name ?? '-' }}</td>
        </tr>
        @if($serviceOrder->diagnosis)
        <tr>
            <td class="info-label">Diagnosa</td><td colspan="3">: {{ $serviceOrder->diagnosis }}</td>
        </tr>
        @endif
    </table>

    <div class="section-title">RINCIAN BIAYA</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width:45%">Keterangan</th>
                <th style="width:10%" class="text-center">Qty</th>
                <th style="width:20%" class="text-right">Harga</th>
                <th style="width:25%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Biaya Jasa Service</td>
                <td class="text-center">1</td>
                <td class="text-right">{{ number_format($serviceOrder->service_fee, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($serviceOrder->service_fee, 0, ',', '.') }}</td>
            </tr>
            @foreach($serviceOrder->items as $item)
            <tr>
                <td>{{ $item->sparePart?->name ?? 'Spare Part' }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr><td>Subtotal</td><td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td></tr>
        <tr><td>Diskon</td><td class="text-right">- Rp {{ number_format($discount, 0, ',', '.') }}</td></tr>
        <tr><td>Pajak</td><td class="text-right">Rp {{ number_format($tax, 0, ',', '.') }}</td></tr>
        <tr class="grand"><td>TOTAL</td><td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td></tr>
        <tr><td>Dibayar</td><td class="text-right">Rp {{ number_format($paidAmount, 0, ',', '.') }}</td></tr>
        <tr><td>Sisa</td><td class="text-right">Rp {{ number_format(max(0, $total - $paidAmount), 0, ',', '.') }}</td></tr>
    </table>

    <div class="warranty-box">
        <strong>GARANSI:</strong> {{ $serviceOrder->warranty_days }} hari
        @if($serviceOrder->warranty_start_date)
            — mulai {{ $serviceOrder->warranty_start_date->format('d/m/Y') }}
        @else
            — berlaku sejak unit diambil pelanggan
        @endif
    </div>

    <table class="codes-table">
        <tr>
            <td>
                <img src="data:image/png;base64,{{ $qrTracking }}" class="code-img" alt="QR Tracking">
                <div class="code-label">QR Tracking Service</div>
            </td>
            <td>
                <img src="data:image/png;base64,{{ $qrInvoice }}" class="code-img" alt="QR Invoice">
                <div class="code-label">QR Invoice (Unduh PDF)</div>
            </td>
            <td>
                <img src="data:image/png;base64,{{ $barcode }}" class="barcode-img" alt="Barcode">
                <div class="code-label">Barcode Invoice</div>
                <div style="font-size:9px;margin-top:2px">{{ $invoiceNumber }}</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        @if(!empty($settings['invoice_footer']))
            {{ $settings['invoice_footer'] }}<br>
        @endif
        {{ $settings['shop_name'] ?? 'Service HP' }} — Invoice {{ $invoiceNumber }} — {{ $trackingUrl }}
    </div>
</body>
</html>
