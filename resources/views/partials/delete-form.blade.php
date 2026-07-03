<form action="{{ $action }}" method="POST" class="d-inline form-delete">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-name="{{ $name ?? 'data ini' }}">
        <i class="bi bi-trash"></i>
    </button>
</form>

@once
@push('scripts')
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
@endpush
@endonce
