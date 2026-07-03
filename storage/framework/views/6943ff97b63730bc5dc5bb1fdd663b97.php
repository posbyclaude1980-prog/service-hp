<?php $__env->startSection('title', 'Laporan Stok'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Laporan Stok',
    'breadcrumbs' => ['Laporan' => '#', 'Stok' => route('reports.stock')],
    'actions' => '
        <a href="'.route('reports.stock.export.pdf', request()->query()).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> Export PDF</a>
    '
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
                    <option value="out" <?php if(request('status') === 'out'): echo 'selected'; endif; ?>>Habis</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Tampilkan</button>
                <a href="<?php echo e(route('reports.stock')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Total Item</div>
            <div class="fs-4 fw-bold"><?php echo e($summary['total_items'] ?? 0); ?></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Stok Menipis</div>
            <div class="fs-4 fw-bold text-warning"><?php echo e($summary['low_stock'] ?? 0); ?></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Stok Habis</div>
            <div class="fs-4 fw-bold text-danger"><?php echo e($summary['out_of_stock'] ?? 0); ?></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Nilai Stok</div>
            <div class="fs-5 fw-bold">Rp <?php echo e(number_format($summary['stock_value'] ?? 0, 0, ',', '.')); ?></div>
        </div></div>
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
                    <th class="text-end">Nilai Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $spareParts ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($part->code); ?></td>
                    <td><?php echo e($part->name); ?></td>
                    <td class="text-center"><?php echo e($part->stock); ?></td>
                    <td class="text-center"><?php echo e($part->min_stock); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($part->purchase_price, 0, ',', '.')); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($part->selling_price, 0, ',', '.')); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($part->stock * $part->purchase_price, 0, ',', '.')); ?></td>
                    <td>
                        <?php if($part->stock <= 0): ?><span class="badge bg-danger">Habis</span>
                        <?php elseif($part->isLowStock()): ?><span class="badge bg-warning">Menipis</span>
                        <?php else: ?><span class="badge bg-success">Tersedia</span><?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/reports/stock.blade.php ENDPATH**/ ?>