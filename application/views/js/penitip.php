<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    } );
</script>
<script>
    function del_data(params) {
        swal.fire({
            title: "Hapus data ini?",
            icon: "question",
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type:"post",
                    url:"<?= base_url("d_pen"); ?>",
                    data:{
                        id: params
                    },
                    success:function(r) {
                        if(r = "Sukses"){
                            swal.fire("Sukses Hapus","","success");
                            $("#row_"+params).remove();
                        }else if(r = "Gagal"){
                            swal.fire("Gagal Hapus","","error");
                        }
                        console.log(r);
                    }
                })
            }
        })
    }
</script>