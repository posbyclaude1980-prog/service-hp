<?php
    $badge = $serviceOrder->status?->color() ?? 'secondary';
?>
<span class="badge bg-<?php echo e($badge); ?> badge-status"><?php echo e($serviceOrder->status_label); ?></span>
<?php /**PATH C:\laragon\www\service-hp\resources\views/partials/service-order-status-badge.blade.php ENDPATH**/ ?>