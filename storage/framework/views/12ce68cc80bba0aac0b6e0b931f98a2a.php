<?php $__env->startSection('title', 'Spare Part'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Data Spare Part',
    'breadcrumbs' => ['Master Data' => '#', 'Spare Part' => route('spare-parts.index')],
    'actions' => ''
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama atau kode..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Status Stok</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="low" <?php if(request('status') === 'low'): echo 'selected'; endif; ?>>Stok Menipis</option>
                    <option value="available" <?php if(request('status') === 'available'): echo 'selected'; endif; ?>>Tersedia</option>
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
                    <th>Kode</th>
                    <th>Nama</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Min. Stok</th>
                    <th class="text-end">Harga Beli</th>
                    <th class="text-end">Harga Jual</th>
                    <th>Status</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $spareParts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($part->code); ?></td>
                    <td><?php echo e($part->name); ?></td>
                    <td class="text-center"><?php echo e($part->stock); ?></td>
                    <td class="text-center"><?php echo e($part->min_stock); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($part->purchase_price, 0, ',', '.')); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($part->selling_price, 0, ',', '.')); ?></td>
                    <td>
                        <?php if($part->isLowStock()): ?>
                            <span class="badge bg-danger">Menipis</span>
                        <?php else: ?>
                            <span class="badge bg-success">Tersedia</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="<?php echo e(route('spare-parts.show', $part)); ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="<?php echo e(route('spare-parts.edit', $part)); ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <?php echo $__env->make('partials.delete-form', ['action' => route('spare-parts.destroy', $part), 'name' => $part->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/spare-parts/index.blade.php ENDPATH**/ ?>