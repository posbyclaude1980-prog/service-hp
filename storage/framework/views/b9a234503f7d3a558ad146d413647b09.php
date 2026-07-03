

<?php $__env->startSection('title', 'Input Kwitansi'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Input Kwitansi',
    'breadcrumbs' => ['Cetak' => route('print.index'), 'Input Kwitansi' => '#'],
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('print.payments.cashier-receipt.preview')); ?>" target="_blank">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nota_service" class="form-label">Keterangan Service</label>
                    <input type="text" class="form-control" id="nota_service" name="nota_service" value="<?php echo e(old('nota_service')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="nota_number" class="form-label">No. Nota</label>
                    <input type="text" class="form-control" id="nota_number" name="nota_number" value="<?php echo e(old('nota_number', 'KW-'.\Carbon\Carbon::now()->format('YmdHis'))); ?>" required readonly>
                </div>
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo e(old('tanggal', date('Y-m-d'))); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="service_fee" class="form-label">Biaya Service</label>
                    <input type="number" min="0" step="0.01" class="form-control" id="service_fee" name="service_fee" value="<?php echo e(old('service_fee')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?php echo e(old('nama')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e(old('phone')); ?>">
                </div>
                <div class="col-md-6">
                    <label for="brand" class="form-label">Merk Handphone</label>
                    <input type="text" class="form-control" id="brand" name="brand" value="<?php echo e(old('brand')); ?>">
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-label">Type Handphone</label>
                    <input type="text" class="form-control" id="type" name="type" value="<?php echo e(old('type')); ?>">
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Keterangan Service</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description')); ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Garansi</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="warranty" id="garansi" value="garansi" <?php if(old('warranty') === 'garansi'): echo 'checked'; endif; ?>>
                        <label class="form-check-label" for="garansi">Garansi</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="warranty" id="tidak" value="tidak" <?php if(old('warranty') === 'tidak'): echo 'checked'; endif; ?>>
                        <label class="form-check-label" for="tidak">Tidak Garansi</label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-eye"></i> Preview Kwitansi</button>
                    <a href="<?php echo e(route('print.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/prints/cashier-receipt-form.blade.php ENDPATH**/ ?>