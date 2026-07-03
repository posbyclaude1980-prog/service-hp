<form action="<?php echo e($action); ?>" method="POST" class="d-inline form-delete">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
    <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-name="<?php echo e($name ?? 'data ini'); ?>">
        <i class="bi bi-trash"></i>
    </button>
</form>

<?php if (! $__env->hasRenderedOnce('297d58cd-c1db-4461-a62c-34becd1a3c48')): $__env->markAsRenderedOnce('297d58cd-c1db-4461-a62c-34becd1a3c48'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
    $(document).on('click', '.btn-delete', function () {
        const form = $(this).closest('form');
        const name = $(this).data('name') || 'data ini';
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Anda yakin ingin menghapus ' + name + '? Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\service-hp\resources\views/partials/delete-form.blade.php ENDPATH**/ ?>