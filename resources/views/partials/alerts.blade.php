@if(session('swal_success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('swal_success')),
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('swal_error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: @json(session('swal_error'))
    });
</script>
@endif

@if(session('success') && !session('swal_success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        timer: 2500,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
</script>
@endif

@if(session('error') && !session('swal_error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: @json(session('error')),
        toast: true,
        position: 'top-end'
    });
</script>
@endif
