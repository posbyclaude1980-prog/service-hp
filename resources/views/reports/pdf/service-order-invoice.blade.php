@extends('layouts.print')

@section('title', 'Invoice ' . $serviceOrder->ticket_number)

@section('content')
    <table style="border: none; margin-bottom: 16px;">
        <tr>
            <td style="border: none; width: 60%;">
                <h1>{{ $settings['shop_name'] ?? 'Service HP' }}</h1>
                <div>{{ $settings['shop_address'] ?? '' }}</div>
                <div>{{ $settings['shop_phone'] ?? '' }}</div>
            </td>
            <td style="border: none; text-align: right;">
                <strong>INVOICE</strong><br>
                {{ $serviceOrder->ticket_number }}<br>
                {{ $serviceOrder->entry_date?->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <table style="border: none; margin-bottom: 16px;">
        <tr>
            <td style="border: none; width: 50%;">
                <strong>Pelanggan</strong><br>
                {{ $serviceOrder->customer?->name }}<br>
                {{ $serviceOrder->customer?->phone }}<br>
                {{ $serviceOrder->customer?->address }}
            </td>
            <td style="border: none;">
                <strong>Perangkat</strong><br>
                {{ $serviceOrder->device_brand }} {{ $serviceOrder->device_model }}<br>
                IMEI: {{ $serviceOrder->imei ?? '-' }}<br>
                Teknisi: {{ $serviceOrder->technician?->name ?? '-' }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Biaya Servis</td>
                <td class="text-right">1</td>
                <td class="text-right">{{ number_format($serviceOrder->service_fee, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($serviceOrder->service_fee, 0, ',', '.') }}</td>
            </tr>
            @foreach($serviceOrder->items as $item)
                <tr>
                    <td>{{ $item->sparePart?->name }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th class="text-right">{{ number_format($serviceOrder->total_cost, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Dibayar</th>
                <th class="text-right">{{ number_format($serviceOrder->paid_amount, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Sisa</th>
                <th class="text-right">{{ number_format($serviceOrder->remaining_payment, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    @if(!empty($settings['invoice_footer']))
        <div class="meta" style="margin-top: 16px;">{{ $settings['invoice_footer'] }}</div>
    @endif
@endsection
