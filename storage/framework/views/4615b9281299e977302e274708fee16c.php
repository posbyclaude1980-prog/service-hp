<?php $__env->startSection('title', 'Model HP'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Data Model HP',
    'breadcrumbs' => ['Master Data' => '#', 'Model HP' => route('phone-models.index')],
    'actions' => ''
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama model..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Merek</label>
                <select name="brand_id" class="form-select">
                    <option value="">Semua Merek</option>
                    <?php $__currentLoopData = $brands ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($brand->id); ?>" <?php if(request('brand_id') == $brand->id): echo 'selected'; endif; ?>><?php echo e($brand->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover datatable w-100">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Merek</th>
                    <th>Nama Model</th>
                    <th>Dibuat</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $phoneModels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i + 1); ?></td>
                    <td><?php echo e($model->brand->name ?? '-'); ?></td>
                    <td><?php echo e($model->name); ?></td>
                    <td><?php echo e($model->created_at?->format('d/m/Y')); ?></td>
                    <td class="text-center">
                        <a href="<?php echo e(route('phone-models.show', $model)); ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="<?php echo e(route('phone-models.edit', $model)); ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <?php echo $__env->make('partials.delete-form', ['action' => route('phone-models.destroy', $model), 'name' => $model->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/phone-models/index.blade.php ENDPATH**/ ?>