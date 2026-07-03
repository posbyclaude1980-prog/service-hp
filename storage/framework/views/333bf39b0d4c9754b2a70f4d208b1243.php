<?php $__env->startSection('title', 'Laporan Service'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Laporan Service',
    'breadcrumbs' => ['Laporan' => '#', 'Service' => route('reports.service')],
    'actions' => '
        <a href="'.route('print.reports.daily', ['date' => request('date_from', date('Y-m-d')), 'preview' => 1]).'" class="btn btn-outline-dark" target="_blank"><i class="bi bi-printer"></i> Cetak LX-300</a>
        <a href="'.route('reports.service.export.pdf', request()->query()).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> Export PDF</a>
    '
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = \App\Enums\ServiceOrderStatus::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status->value); ?>" <?php if(request('status') === $status->value): echo 'selected'; endif; ?>><?php echo e($status->label()); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Teknisi</label>
                <select name="technician_id" class="form-select">
                    <option value="">Semua Teknisi</option>
                    <?php $__currentLoopData = $technicians ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tech->id); ?>" <?php if(request('technician_id') == $tech->id): echo 'selected'; endif; ?>><?php echo e($tech->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from', $dateFrom ?? '')); ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to', $dateTo ?? '')); ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Tampilkan</button>
                <a href="<?php echo e(route('reports.service')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Total Service</div>
            <div class="fs-4 fw-bold"><?php echo e($summary['total'] ?? 0); ?></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Selesai</div>
            <div class="fs-4 fw-bold text-success"><?php echo e($summary['completed'] ?? 0); ?></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Dalam Proses</div>
            <div class="fs-4 fw-bold text-primary"><?php echo e($summary['in_progress'] ?? 0); ?></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Total Nilai</div>
            <div class="fs-5 fw-bold">Rp <?php echo e(number_format($summary['total_value'] ?? 0, 0, ',', '.')); ?></div>
        </div></div>
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
                    <th>Teknisi</th>
                    <th>Status</th>
                    <th>Tgl Masuk</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $orders ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($order->ticket_number); ?></td>
                    <td><?php echo e($order->customer->name ?? '-'); ?></td>
                    <td><?php echo e($order->device_brand); ?> <?php echo e($order->device_model); ?></td>
                    <td><?php echo e($order->technician->name ?? '-'); ?></td>
                    <td><?php echo e($order->status_label); ?></td>
                    <td><?php echo e($order->entry_date?->format('d/m/Y')); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($order->total_cost, 0, ',', '.')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/reports/service.blade.php ENDPATH**/ ?>