<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            order: [[1, 'desc']]
        });
    });

    function lihat_detail(id_kulakan) {
          $.ajax({
                type:"post",
                url:"<?= base_url("get_detail_kulakan") ?>",
                data:{ id_kulakan: id_kulakan },
                dataType:"JSON",
                success:function(r) {
                      var body = "";
                      if(r.length > 0){
                            for (var i = 0; i < r.length; i++) {
                                  body += '<tr>'+
                                        '<td class="text-center">'+r[i].nama_barang+'</td>'+
                                        '<td class="text-center">'+r[i].jumlah_barang+'</td>'+
                                        '<td class="text-center">Rp. '+formatharga(r[i].harga_tengkulak)+'</td>'+
                                        '<td class="text-center">Rp. '+formatharga(r[i].subtotal)+'</td>'+
                                        '</tr>';
                            }
                      }else{
                            body = '<tr><td colspan="4" class="text-center">Tidak ada data</td></tr>';
                      }
                      $("#body-detail-kulakan").html(body);
                      $("#modalDetailKulakan").modal("show");
                },
                error:function(a,b,c) { swal.fire("Gagal", a.responseText, "error"); }
          });
    }

    function del_data(params) {
        swal.fire({
            title: "Hapus data kulakan ini?",
            html: "Stok barang terkait akan dikurangi kembali sesuai jumlah kulakan ini.",
            icon: "question",
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                  $.ajax({
                        type:"post",
                        url:"<?= base_url("delete_kulakan"); ?>",
                        data:{ id: params },
                        success:function(result) {
                              if(result === "Sukses"){
                                    swal.fire("Sukses Hapus","","success").then(() => { location.reload(); });
                              }else{
                                    swal.fire("Gagal",result,"error");
                              }
                        },
                        error:function(a,b,c) {
                              swal.fire("Gagal",a.responseText,"error");
                        }
                  })
            }
        })
    }
</script>
