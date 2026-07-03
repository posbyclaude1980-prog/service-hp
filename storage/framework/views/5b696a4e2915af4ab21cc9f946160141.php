

<?php $__env->startSection('title', 'Pekerjaan Teknisi'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Daftar Pekerjaan',
    'breadcrumbs' => ['Pekerjaan Teknisi' => '#']
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="bi bi-tools text-primary fs-4"></i></div>
                <div>
                    <div class="text-muted small">Pekerjaan Aktif</div>
                    <div class="fs-4 fw-bold"><?php echo e($stats['active'] ?? 0); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3"><i class="bi bi-clipboard-check text-info fs-4"></i></div>
                <div>
                    <div class="text-muted small">Menunggu QC</div>
                    <div class="fs-4 fw-bold"><?php echo e($stats['qc'] ?? 0); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="No. tiket, pelanggan, perangkat...">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <?php $__currentLoopData = \App\Enums\ServiceOrderStatus::workflow(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status->value); ?>" <?php if(($filters['status'] ?? '') === $status->value): echo 'selected'; endif; ?>><?php echo e($status->label()); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Tab</label>
                <select name="tab" class="form-select">
                    <option value="active" <?php if(($filters['tab'] ?? 'active') === 'active'): echo 'selected'; endif; ?>>Aktif</option>
                    <option value="completed" <?php if(($filters['tab'] ?? '') === 'completed'): echo 'selected'; endif; ?>>Selesai</option>
                    <option value="all" <?php if(($filters['tab'] ?? '') === 'all'): echo 'selected'; endif; ?>>Semua</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Service</th>
                        <th>Pelanggan</th>
                        <th>Perangkat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <?php if($user->role !== 'teknisi'): ?>
                            <th>Teknisi</th>
                        <?php endif; ?>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($job->ticket_number); ?></td>
                        <td><?php echo e($job->customer->name ?? '-'); ?></td>
                        <td><?php echo e($job->device_brand); ?> <?php echo e($job->device_model); ?></td>
                        <td><?php echo $__env->make('partials.service-order-status-badge', ['serviceOrder' => $job], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></td>
                        <td><?php echo e($job->entry_date?->format('d/m/Y')); ?></td>
                        <?php if($user->role !== 'teknisi'): ?>
                            <td><?php echo e($job->technician?->user?->name ?? '-'); ?></td>
                        <?php endif; ?>
                        <td class="text-center">
                            <a href="<?php echo e(route('technician.jobs.show', $job)); ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-wrench"></i> Kerjakan
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="<?php echo e($user->role !== 'teknisi' ? 7 : 6); ?>" class="text-center text-muted py-4">
                            Tidak ada pekerjaan ditemukan.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($jobs->hasPages()): ?>
    <div class="card-footer"><?php echo e($jobs->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/technician/jobs/index.blade.php ENDPATH**/ ?>