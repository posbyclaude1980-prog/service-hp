<?php if($errors->any()): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="bi bi-exclamation-triangle me-1"></i> Terdapat kesalahan:</strong>
    <ul class="mb-0 mt-2">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\service-hp\resources\views/partials/form-errors.blade.php ENDPATH**/ ?>