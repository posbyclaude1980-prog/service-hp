<?php $__env->startSection('title', 'Pengaturan'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Pengaturan Toko',
    'breadcrumbs' => ['Pengaturan' => route('settings.index')]
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Informasi Toko</div>
            <div class="card-body">
                <?php echo $__env->make('partials.form-errors', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <form action="<?php echo e(route('settings.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="shop_name" class="form-label">Nama Toko <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['shop_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shop_name" name="shop_name"
                                   value="<?php echo e(old('shop_name', $settings['shop_name'] ?? config('app.name'))); ?>" required>
                            <?php $__errorArgs = ['shop_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="shop_phone" class="form-label">Telepon</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['shop_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shop_phone" name="shop_phone"
                                   value="<?php echo e(old('shop_phone', $settings['shop_phone'] ?? '')); ?>">
                            <?php $__errorArgs = ['shop_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="shop_email" class="form-label">Email</label>
                            <input type="email" class="form-control <?php $__errorArgs = ['shop_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shop_email" name="shop_email"
                                   value="<?php echo e(old('shop_email', $settings['shop_email'] ?? '')); ?>">
                            <?php $__errorArgs = ['shop_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="default_warranty_days" class="form-label">Garansi Default (Hari)</label>
                            <input type="number" min="0" class="form-control <?php $__errorArgs = ['default_warranty_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="default_warranty_days" name="default_warranty_days"
                                   value="<?php echo e(old('default_warranty_days', $settings['default_warranty_days'] ?? 30)); ?>">
                            <?php $__errorArgs = ['default_warranty_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12">
                            <label for="shop_address" class="form-label">Alamat</label>
                            <textarea class="form-control <?php $__errorArgs = ['shop_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shop_address" name="shop_address" rows="3"><?php echo e(old('shop_address', $settings['shop_address'] ?? '')); ?></textarea>
                            <?php $__errorArgs = ['shop_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="ticket_prefix" class="form-label">Prefix No. Tiket</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['ticket_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="ticket_prefix" name="ticket_prefix"
                                   value="<?php echo e(old('ticket_prefix', $settings['ticket_prefix'] ?? 'SRV')); ?>" placeholder="SRV">
                            <?php $__errorArgs = ['ticket_prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="shop_logo" class="form-label">Logo Toko</label>
                            <input type="file" class="form-control <?php $__errorArgs = ['shop_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="shop_logo" name="shop_logo" accept="image/*">
                            <?php $__errorArgs = ['shop_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-muted text-uppercase small fw-bold mb-3">Nota & Cetak</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="receipt_footer" class="form-label">Footer Nota</label>
                            <textarea class="form-control" id="receipt_footer" name="receipt_footer" rows="2" placeholder="Terima kasih atas kepercayaan Anda..."><?php echo e(old('receipt_footer', $settings['receipt_footer'] ?? '')); ?></textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Pengaturan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">Logo Saat Ini</div>
            <div class="card-body text-center">
                <?php if(!empty($settings['shop_logo'])): ?>
                    <img src="<?php echo e(asset('storage/'.$settings['shop_logo'])); ?>" alt="Logo" class="img-fluid rounded" style="max-height:120px">
                <?php else: ?>
                    <div class="text-muted py-4"><i class="bi bi-image fs-1"></i><p class="mb-0 small">Belum ada logo</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/settings/index.blade.php ENDPATH**/ ?>