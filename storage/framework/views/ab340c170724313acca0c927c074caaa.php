<?php $__env->startSection('title', 'Supplier'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Data Supplier',
    'breadcrumbs' => ['Master Data' => '#', 'Supplier' => route('suppliers.index')],
    'actions' => ''
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, kode, kontak..." value="<?php echo e(request('search')); ?>">
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
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($supplier->code); ?></td>
                    <td><?php echo e($supplier->name); ?></td>
                    <td><?php echo e($supplier->contact ?: '-'); ?></td>
                    <td><?php echo e($supplier->phone); ?></td>
                    <td><?php echo e(Str::limit($supplier->address, 30)); ?></td>
                    <td class="text-center">
                        <a href="<?php echo e(route('suppliers.show', $supplier)); ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="<?php echo e(route('suppliers.edit', $supplier)); ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <?php echo $__env->make('partials.delete-form', ['action' => route('suppliers.destroy', $supplier), 'name' => $supplier->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/suppliers/index.blade.php ENDPATH**/ ?>