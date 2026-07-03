

<?php $__env->startSection('content'); ?>
<?php
    $description = $description ?: '........................................................................';
    $brand = $brand ?: '........................................................................';
    $type = $type ?: '........................................................................';
    $amount = number_format((float) $service_fee, 0, ',', '.');
    $isWarranty = $warranty === 'garansi';
?>

<div class="dm-header-row">
    <div class="dm-header-logo">
        <img src="<?php echo e(asset('images/klinik-gadget-logo.svg')); ?>" alt="Klinik Gadget Bandung" class="dm-logo">
    </div>
    <div class="dm-header-right">
        <div class="dm-bold dm-header-title">KLINIK GADGET BANDUNG CABANG BANDUNG</div>
        <div class="dm-header-tagline">SERVICE HANDPHONE TERMURUH DAN BERGARANSI</div>
        <div class="dm-small dm-header-meta">Jl. Rereng Wulung No.25, Sukaluyu, Kec. Cibeunying Kaler, Kota Bandung.</div>
        <div class="dm-small dm-header-meta">Whatsapp : 085355667769</div>
    </div>
</div>

<hr class="dm-line">

<table class="dm-kv">
    <tr><td class="dm-k">Nota Service</td><td class="dm-c">:</td><td class="dm-v"><?php echo e($nota_service); ?></td></tr>
    <tr><td class="dm-k">No. Nota</td><td class="dm-c">:</td><td class="dm-v"><?php echo e($nota_number); ?></td></tr>
</table>

<div class="dm-bold" style="margin-top:6px">Keterangan Service</div>
<div class="dm-preline" style="border:1px solid #000; padding:4px; min-height:2.5rem;"><?php echo e($description); ?></div>

<div class="dm-bold" style="margin-top:6px">Merk Handphone</div>
<div class="dm-preline" style="border:1px solid #000; padding:4px; min-height:2.5rem;"><?php echo e($brand); ?></div>

<div class="dm-bold" style="margin-top:6px">Type Handphone</div>
<div class="dm-preline" style="border:1px solid #000; padding:4px; min-height:2.5rem;"><?php echo e($type); ?></div>

<table class="dm-kv" style="margin-top:8px">
    <tr>
        <td class="dm-k">Tanggal</td><td class="dm-c">:</td><td class="dm-v"><?php echo e(\Carbon\Carbon::parse($tanggal)->format('d/m/Y')); ?></td>
        <td class="dm-k">Biaya Service</td><td class="dm-c">:</td><td class="dm-v">Rp <?php echo e($amount); ?></td>
    </tr>
    <tr>
        <td class="dm-k">Nama</td><td class="dm-c">:</td><td class="dm-v"><?php echo e($nama); ?></td>
        <td class="dm-k">Nomor HP</td><td class="dm-c">:</td><td class="dm-v"><?php echo e($phone ?: '-'); ?></td>
    </tr>
</table>

<div class="dm-bold" style="margin-top:8px">Garansi</div>
<table class="dm-kv">
    <tr>
        <td class="dm-v">[ <?php echo e($isWarranty ? 'x' : ' '); ?> ] Garansi</td>
        <td class="dm-v">[ <?php echo e($isWarranty ? ' ' : 'x'); ?> ] Tidak Garansi</td>
    </tr>
</table>

<hr class="dm-line dm-line-dashed">

<div class="dm-bold">Ketentuan service Garansi :</div>
<div class="dm-preline dm-small">Garansi service berlaku dikerusakan yang sama, berlaku selama jangka waktu yang telah ditentukan, untuk klaim garansi, pelanggan wajib menyertakan nota service.</div>

<div class="dm-bold" style="margin-top:6px">Pernyataan Pelanggan (Service Tidak Garansi) :</div>
<div class="dm-preline dm-small">Saya, ......................... memahami bahwa perbaikan handphone saya tidak termasuk garansi, dan saya bertanggung jawab atas biaya perbaikan jika terjadi kerusakan lagi. saya telah memahami dan menyetujui ketentuan ini.</div>

<div class="dm-sign" style="margin-top:12px">
    <table>
        <tr>
            <td>Tanda tangan</td>
        </tr>
        <tr>
            <td style="padding-top:28px">( ......................... )</td>
        </tr>
    </table>
</div>

<hr class="dm-line dm-line-dashed">

<div class="dm-small">
    Syarat dan Ketentuan : Nota ini berlaku selama 1 Bulan. Pengambilan barang servisan harus disertai nota. Jika tidak diambil dalam waktu 1 bulan, maka barang servisan diluar tanggung jawab kami. Kami tidak bertanggung jawab apabila dikemudian hari barang servisan terkait masalah hukum. Menerima nota ini berarti menerima syarat dan ketentuan yang berlaku.
</div>

<div class="dm-bold" style="margin-top:8px">PIN / POLA</div>
<div class="pin-grid" style="margin-top:6px">
    <div class="pin-row">
        <div class="pin-cell">1</div>
        <div class="pin-cell">2</div>
        <div class="pin-cell">3</div>
    </div>
    <div class="pin-row">
        <div class="pin-cell">4</div>
        <div class="pin-cell">5</div>
        <div class="pin-cell">6</div>
    </div>
    <div class="pin-row">
        <div class="pin-cell">7</div>
        <div class="pin-cell">8</div>
        <div class="pin-cell">9</div>
    </div>
    <div class="pin-checkbox-row">
        <div class="pin-checkbox"></div>
        <div class="pin-checkbox"></div>
        <div class="pin-checkbox"></div>
        <div class="pin-checkbox"></div>
        <div class="pin-checkbox"></div>
        <div class="pin-checkbox"></div>
    </div>
</div>

<table class="dm-kv" style="margin-top:8px">
    <tr>
        <td class="dm-v">Konsumen</td>
        <td class="dm-v" style="text-align:right">Counter</td>
    </tr>
    <tr>
        <td class="dm-preline" style="border:1px solid #000; min-height:2.5rem;"></td>
        <td class="dm-preline" style="border:1px solid #000; min-height:2.5rem;"></td>
    </tr>
</table>

<div class="dm-center dm-small" style="margin-top:10px">TERIMA KASIH ATAS KEPERCAYAAN ANDA, KEPUASAN KONSUMEN ADALAH PRIORITAS KAMI</div>
<div class="dm-center dm-small">POWERED BY : KLINIK GADGET BANDUNG</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dotmatrix', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/prints/cashier-receipt-custom.blade.php ENDPATH**/ ?>