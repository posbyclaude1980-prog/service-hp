<?php $__env->startSection('title', 'Pembayaran'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Data Pembayaran',
    'breadcrumbs' => ['Pembayaran' => route('payments.index')],
    'actions' => ''
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="No. tiket..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Metode</label>
                <select name="method" class="form-select">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = \App\Enums\PaymentMethod::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($method->value); ?>" <?php if(request('method') === $method->value): echo 'selected'; endif; ?>><?php echo e($method->label()); ?></option>
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
                <a href="<?php echo e(route('payments.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover datatable w-100">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Tiket</th>
                    <th>Pelanggan</th>
                    <th>Metode</th>
                    <th>Kasir</th>
                    <th class="text-end">Jumlah</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($payment->payment_date?->format('d/m/Y')); ?></td>
                    <td><a href="<?php echo e(route('service-orders.show', $payment->service_order_id)); ?>"><?php echo e($payment->serviceOrder->ticket_number ?? '-'); ?></a></td>
                    <td><?php echo e($payment->serviceOrder->customer->name ?? '-'); ?></td>
                    <td><?php echo e($payment->method?->label()); ?></td>
                    <td><?php echo e($payment->user->name ?? '-'); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></td>
                    <td class="text-center">
                        <a href="<?php echo e(route('payments.show', $payment)); ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="<?php echo e(route('payments.edit', $payment)); ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <?php echo $__env->make('partials.delete-form', ['action' => route('payments.destroy', $payment), 'name' => 'pembayaran ini'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/payments/index.blade.php ENDPATH**/ ?>