<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <h1><?php echo e($title); ?></h1>
        <?php if(isset($breadcrumbs)): ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($loop->last): ?>
                        <li class="breadcrumb-item active"><?php echo e(is_int($label) ? $url : $label); ?></li>
                    <?php else: ?>
                        <li class="breadcrumb-item"><a href="<?php echo e($url); ?>"><?php echo e($label); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ol>
        </nav>
        <?php endif; ?>
    </div>
    <?php if(isset($actions)): ?>
    <div class="d-flex gap-2 flex-wrap">
        <?php echo $actions; ?>

    </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\service-hp\resources\views/partials/page-header.blade.php ENDPATH**/ ?>