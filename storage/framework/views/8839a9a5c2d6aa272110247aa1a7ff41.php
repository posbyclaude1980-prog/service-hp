<?php $__env->startSection('title', 'Pelanggan'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Data Pelanggan',
    'breadcrumbs' => ['Pelanggan' => route('customers.index')]
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('customers.index')); ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama, kode, atau telepon..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
                <a href="<?php echo e(route('customers.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover datatable w-100" id="customersTable">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Total Service</th>
                        <th>Dibuat</th>
                        <th class="no-export text-center" style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($customer->code); ?></td>
                        <td><?php echo e($customer->name); ?></td>
                        <td><?php echo e($customer->phone); ?></td>
                        <td><?php echo e(Str::limit($customer->address, 40)); ?></td>
                        <td><?php echo e($customer->service_orders_count ?? $customer->serviceOrders()->count()); ?></td>
                        <td><?php echo e($customer->created_at?->format('d/m/Y')); ?></td>
                        <td class="text-center">
                            <a href="<?php echo e(route('customers.show', $customer)); ?>" class="btn btn-sm btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                            <a href="<?php echo e(route('customers.edit', $customer)); ?>" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            <?php echo $__env->make('partials.delete-form', ['action' => route('customers.destroy', $customer), 'name' => $customer->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/customers/index.blade.php ENDPATH**/ ?>