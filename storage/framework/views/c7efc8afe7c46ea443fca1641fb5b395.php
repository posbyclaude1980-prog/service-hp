<?php $__env->startSection('title', 'Service Order'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Data Service Order',
    'breadcrumbs' => ['Service Order' => route('service-orders.index')],
    'actions' => '<a href="'.route('service-orders.create').'" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Penerimaan Baru</a>'
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="No. tiket, nama, telepon..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <?php $__currentLoopData = \App\Enums\ServiceOrderStatus::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status->value); ?>" <?php if(request('status') === $status->value): echo 'selected'; endif; ?>><?php echo e($status->label()); ?></option>
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
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
                <a href="<?php echo e(route('service-orders.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover datatable w-100">
            <thead>
                <tr>
                    <th>No. Tiket</th>
                    <th>Pelanggan</th>
                    <th>Perangkat</th>
                    <th>Status</th>
                    <th>Tgl Masuk</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Sisa</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $serviceOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><a href="<?php echo e(route('service-orders.show', $order)); ?>"><?php echo e($order->ticket_number); ?></a></td>
                    <td><?php echo e($order->customer->name ?? '-'); ?></td>
                    <td><?php echo e($order->device_brand); ?> <?php echo e($order->device_model); ?></td>
                    <td><?php echo $__env->make('partials.service-order-status-badge', ['serviceOrder' => $order], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                    <td><?php echo e($order->entry_date?->format('d/m/Y')); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($order->total_cost, 0, ',', '.')); ?></td>
                    <td class="text-end <?php echo e($order->remaining_payment > 0 ? 'text-danger fw-semibold' : 'text-success'); ?>">
                        Rp <?php echo e(number_format($order->remaining_payment, 0, ',', '.')); ?>

                    </td>
                    <td class="text-center">
                        <a href="<?php echo e(route('service-orders.show', $order)); ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="<?php echo e(route('service-orders.edit', $order)); ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <?php echo $__env->make('partials.delete-form', ['action' => route('service-orders.destroy', $order), 'name' => $order->ticket_number], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/service-orders/index.blade.php ENDPATH**/ ?>