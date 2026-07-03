<?php $__env->startSection('title', 'Pengguna'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.page-header', [
    'title' => 'Manajemen Pengguna',
    'breadcrumbs' => ['Pengguna' => route('users.index')],
    'actions' => '<a href="'.route('users.create').'" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Pengguna</a>'
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Nama atau email..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Role</label>
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="owner" <?php if(request('role') === 'owner'): echo 'selected'; endif; ?>>Owner</option>
                    <option value="admin" <?php if(request('role') === 'admin'): echo 'selected'; endif; ?>>Admin</option>
                    <option value="kasir" <?php if(request('role') === 'kasir'): echo 'selected'; endif; ?>>Kasir</option>
                    <option value="teknisi" <?php if(request('role') === 'teknisi'): echo 'selected'; endif; ?>>Teknisi</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>Aktif</option>
                    <option value="inactive" <?php if(request('status') === 'inactive'): echo 'selected'; endif; ?>>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
                <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover datatable w-100">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th class="text-center" style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($user->name); ?></td>
                    <td><?php echo e($user->email); ?></td>
                    <td><?php echo e($user->phone ?: '-'); ?></td>
                    <td><span class="badge bg-<?php echo e(match($user->role) { 'owner' => 'dark', 'admin' => 'danger', 'teknisi' => 'info', default => 'secondary' }); ?>"><?php echo e($user->roleLabel()); ?></span></td>
                    <td>
                        <?php if($user->is_active): ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($user->created_at?->format('d/m/Y')); ?></td>
                    <td class="text-center">
                        <a href="<?php echo e(route('users.show', $user)); ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        <?php if($user->id !== auth()->id()): ?>
                            <?php echo $__env->make('partials.delete-form', ['action' => route('users.destroy', $user), 'name' => $user->name], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('partials.datatable-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\service-hp\resources\views/users/index.blade.php ENDPATH**/ ?>