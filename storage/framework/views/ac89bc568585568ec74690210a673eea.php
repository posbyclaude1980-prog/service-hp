<?php $__env->startSection('title', 'Laporan Pendapatan'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Laporan Pendapatan',
    'breadcrumbs' => ['Laporan' => '#', 'Pendapatan' => route('reports.revenue')],
    'actions' => '
        <a href="'.route('reports.revenue.export.pdf', request()->query()).'" class="btn btn-outline-danger"><i class="bi bi-file-pdf"></i> Export PDF</a>
    '
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from', $dateFrom ?? '')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to', $dateTo ?? '')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Metode Pembayaran</label>
                <select name="method" class="form-select">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = \App\Enums\PaymentMethod::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($method->value); ?>" <?php if(request('method') === $method->value): echo 'selected'; endif; ?>><?php echo e($method->label()); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Tampilkan</button>
                <a href="<?php echo e(route('reports.revenue')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Total Pendapatan</div>
            <div class="fs-4 fw-bold text-success">Rp <?php echo e(number_format($summary['total_revenue'] ?? 0, 0, ',', '.')); ?></div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Jumlah Transaksi</div>
            <div class="fs-4 fw-bold"><?php echo e($summary['total_transactions'] ?? 0); ?></div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card"><div class="card-body text-center">
            <div class="text-muted small">Rata-rata Transaksi</div>
            <div class="fs-5 fw-bold">Rp <?php echo e(number_format($summary['average'] ?? 0, 0, ',', '.')); ?></div>
        </div></div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Grafik Pendapatan</div>
            <div class="card-body"><canvas id="revenueReportChart" height="100"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Per Metode</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Metode</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $byMethod ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(\App\Enums\PaymentMethod::tryFrom($method)?->label() ?? $method); ?></td>
                            <td class="text-end">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
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
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $payments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($payment->payment_date?->format('d/m/Y')); ?></td>
                    <td><?php echo e($payment->serviceOrder->ticket_number ?? '-'); ?></td>
                    <td><?php echo e($payment->serviceOrder->customer->name ?? '-'); ?></td>
                    <td><?php echo e($payment->method_label); ?></td>
                    <td><?php echo e($payment->user->name ?? '-'); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    new Chart(document.getElementById('revenueReportChart'), {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($chartLabels ?? [], 15, 512) ?>,
            datasets: [{ label: 'Pendapatan (Rp)', data: <?php echo json_encode($chartData ?? [], 15, 512) ?>, backgroundColor: '#22c55e', borderRadius: 4 }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/reports/revenue.blade.php ENDPATH**/ ?>