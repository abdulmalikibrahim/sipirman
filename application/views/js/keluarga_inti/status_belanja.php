<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    } );

    function show_msg(data) {
        pesan = data.dataset.alasan;
        swal.fire("Information",pesan,"info");
    }
</script>