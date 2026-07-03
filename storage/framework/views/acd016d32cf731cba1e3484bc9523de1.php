<?php if(session('swal_success')): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: <?php echo json_encode(session('swal_success'), 15, 512) ?>,
        timer: 3000,
        showConfirmButton: false
    });
</script>
<?php endif; ?>

<?php if(session('swal_error')): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: <?php echo json_encode(session('swal_error'), 15, 512) ?>
    });
</script>
<?php endif; ?>

<?php if(session('success') && !session('swal_success')): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: <?php echo json_encode(session('success'), 15, 512) ?>,
        timer: 2500,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
</script>
<?php endif; ?>

<?php if(session('error') && !session('swal_error')): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: <?php echo json_encode(session('error'), 15, 512) ?>,
        toast: true,
        position: 'top-end'
    });
</script>
<?php endif; ?>
<?php /**PATH C:\laragon\www\service-hp\resources\views/partials/alerts.blade.php ENDPATH**/ ?>