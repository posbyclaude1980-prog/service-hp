<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function () {
        $('.datatable').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
            },
            dom: "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row mb-3'<'col-sm-12'B>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                { extend: 'excel', className: 'btn btn-success btn-sm', text: '<i class="bi bi-file-earmark-excel"></i> Excel' },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', text: '<i class="bi bi-file-earmark-pdf"></i> PDF', orientation: 'landscape' },
                { extend: 'print', className: 'btn btn-secondary btn-sm', text: '<i class="bi bi-printer"></i> Cetak' }
            ],
            <?php if(isset($datatableOrder)): ?>
            order: <?php echo json_encode($datatableOrder, 15, 512) ?>,
            <?php else: ?>
            order: [[0, 'desc']],
            <?php endif; ?>
            <?php if(isset($datatableColumnDefs)): ?>
            columnDefs: <?php echo json_encode($datatableColumnDefs, 15, 512) ?>,
            <?php endif; ?>
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']]
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\service-hp\resources\views/partials/datatable-init.blade.php ENDPATH**/ ?>