<?php $__env->startSection('title', 'Merek'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Data Merek',
    'breadcrumbs' => ['Master Data' => '#', 'Merek' => route('brands.index')],
    'actions' => ''
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama merek..." value="<?php echo e(request('search')); ?>">
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
                    <th>Nama Merek</th>
                    <th>Jumlah Model</th>
                    <th>Dibuat</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i + 1); ?></td>
                    <td><?php echo e($brand->name); ?></td>
                    <td><?php echo e($brand->phone_models_count ?? $brand->phoneModels()->count()); ?></td>
                    <td><?php echo e($brand->created_at?->format('d/m/Y')); ?></td>
                    <td class="text-center">
                        <a href="<?php echo e(route('brands.show', $brand)); ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="<?php echo e(route('brands.edit', $brand)); ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <?php echo $__env->make('partials.delete-form', ['action' => route('brands.destroy', $brand), 'name' => $brand->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/brands/index.blade.php ENDPATH**/ ?>