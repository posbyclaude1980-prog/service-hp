

<?php $__env->startSection('content'); ?>
<?php
    $shop = $settings['shop_name'] ?? 'SERVICE HP';
    $address = $settings['shop_address'] ?? '';
    $phone = $settings['shop_phone'] ?? '';
    $logoPath = $settings['shop_logo'] ?? '';
?>

<?php if($logoPath): ?>
<div class="dm-center" style="margin-bottom:6px">
    <img src="<?php echo e(asset('storage/'.$logoPath)); ?>" alt="Logo" style="max-height:42px; max-width:70px; object-fit:contain;">
</div>
<?php endif; ?>
<div class="dm-center dm-bold"><?php echo e(strtoupper($shop)); ?></div>
<?php if($address): ?><div class="dm-center dm-small"><?php echo e($address); ?></div><?php endif; ?>
<?php if($phone): ?><div class="dm-center dm-small">Telp: <?php echo e($phone); ?></div><?php endif; ?>
<div class="dm-center dm-small">Nota Kasir</div>
<hr class="dm-line">

<table class="dm-kv">
    <tr><td class="dm-k">Tanggal</td><td class="dm-c">:</td><td class="dm-v"><?php echo e(now()->format('d/m/Y H:i')); ?></td></tr>
    <tr><td class="dm-k">Nama</td><td class="dm-c">:</td><td class="dm-v"><?php echo e($receipt['customer_name']); ?></td></tr>
    <tr><td class="dm-k">No HP</td><td class="dm-c">:</td><td class="dm-v"><?php echo e($receipt['customer_phone'] ?: '-'); ?></td></tr>
    <tr><td class="dm-k">Service</td><td class="dm-c">:</td><td class="dm-v"><?php echo e($receipt['service_description']); ?></td></tr>
</table>

<hr class="dm-line">
<div class="dm-bold">Biaya Service</div>
<div class="dm-title"><?php echo e('Rp '.number_format((float) $receipt['service_fee'], 0, ',', '.')); ?></div>
<hr class="dm-line">
<div class="dm-small">Terima kasih atas kepercayaan Anda.</div>
<div class="dm-sign">
    <table>
        <tr>
            <td>Pelanggan<br><br><br>________________</td>
            <td>Kasir<br><br><br>________________</td>
        </tr>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dotmatrix', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/prints/cashier-receipt.blade.php ENDPATH**/ ?>