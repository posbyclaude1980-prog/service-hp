<?php $__env->startSection('title', 'Pembelian'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Data Pembelian',
    'breadcrumbs' => ['Pembelian' => route('purchases.index')],
    'actions' => ''
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="No. invoice..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Supplier</label>
                <select name="supplier_id" class="form-select">
                    <option value="">Semua Supplier</option>
                    <?php $__currentLoopData = $suppliers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($supplier->id); ?>" <?php if(request('supplier_id') == $supplier->id): echo 'selected'; endif; ?>><?php echo e($supplier->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
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
                    <th>No. Invoice</th>
                    <th>Supplier</th>
                    <th>Tanggal</th>
                    <th>Petugas</th>
                    <th class="text-end">Total</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><a href="<?php echo e(route('purchases.show', $purchase)); ?>"><?php echo e($purchase->invoice_number); ?></a></td>
                    <td><?php echo e($purchase->supplier->name ?? '-'); ?></td>
                    <td><?php echo e($purchase->purchase_date?->format('d/m/Y')); ?></td>
                    <td><?php echo e($purchase->user->name ?? '-'); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($purchase->total, 0, ',', '.')); ?></td>
                    <td class="text-center">
                        <a href="<?php echo e(route('purchases.show', $purchase)); ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="<?php echo e(route('purchases.edit', $purchase)); ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <?php echo $__env->make('partials.delete-form', ['action' => route('purchases.destroy', $purchase), 'name' => $purchase->invoice_number], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/purchases/index.blade.php ENDPATH**/ ?>