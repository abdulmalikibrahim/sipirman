<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    } );
</script>
<script>
    function np(params) {
        $("#id_account").val(params);
    }
    
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
                        url:"<?= base_url("delete_barang_koperasi"); ?>",
                        data:{
                              id: params
                        },
                        success:function(result) {
                              if(result === "Sukses"){
                                    swal.fire("Sukses Hapus","","success");
                                    $("#row_"+params).remove();
                              }else{
                                    swal.fire("Gagal",result,"error");
                              }
                              console.log(result);
                        },
                        error:function(a,b,c) {
                              swal.fire("Gagal",a.responseText,"error");
                        }
                  })
            }
        })
    }
</script>