@php
    $badge = $serviceOrder->status?->color() ?? 'secondary';
@endphp
<span class="badge bg-{{ $badge }} badge-status">{{ $serviceOrder->status_label }}</span>
