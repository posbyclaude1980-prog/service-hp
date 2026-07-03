@php
    $shop = $settings['shop_name'] ?? 'SERVICE HP';
    $addr = $settings['shop_address'] ?? '';
    $phone = $settings['shop_phone'] ?? '';
    $cols = $columns ?? 80;
    $dm = app(\App\Services\DotMatrixPrintService::class);
@endphp
<div class="dm-center dm-bold">{{ strtoupper($shop) }}</div>
@if($addr)<div class="dm-center dm-small">{{ $addr }}</div>@endif
@if($phone)<div class="dm-center dm-small">Telp: {{ $phone }}</div>@endif
<hr class="dm-line">
