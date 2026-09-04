<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // Kolom hutang punya atribut data-order (angka mentah) di setiap <td>,
        // otomatis dipakai DataTables untuk sorting numerik yang benar.
        $('#datatable').DataTable({
            order: [[5, 'desc']]
        });
    });

    function lihat_detail_hutang(code_napi, nama) {
          $.ajax({
                type:"post",
                url:"<?= base_url("get_detail_hutang") ?>",
                data:{ code_napi: code_napi },
                dataType:"JSON",
                success:function(r) {
                      var body = "";
                      if(r.length > 0){
                            for (var i = 0; i < r.length; i++) {
                                  var badge = r[i].jenis == "Uang Tunai"
                                        ? '<span class="badge badge-success">'+r[i].jenis+'</span>'
                                        : '<span class="badge badge-primary">'+r[i].jenis+'</span>';
                                  var saldo_akhir = r[i].saldo_akhir;
                                  var saldo_akhir_text = (saldo_akhir < 0 ? "-Rp. " : "Rp. ") + formatharga(Math.abs(saldo_akhir));
                                  body += '<tr>'+
                                        '<td class="text-center">'+r[i].tanggal+'</td>'+
                                        '<td class="text-center">'+badge+'</td>'+
                                        '<td>'+r[i].belanja+'</td>'+
                                        '<td class="text-center">Rp. '+formatharga(r[i].saldo_awal)+'</td>'+
                                        '<td class="text-center text-danger">Rp. '+formatharga(r[i].jumlah_hutang)+'</td>'+
                                        '<td class="text-center '+(saldo_akhir < 0 ? "text-danger font-weight-bold" : "")+'">'+saldo_akhir_text+'</td>'+
                                        '</tr>';
                            }
                      }else{
                            body = '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
                      }
                      $("#body-detail-hutang").html(body);
                      $("#modalDetailHutangLabel").html("Rincian Hutang - "+nama);
                      $("#modalDetailHutang").modal("show");
                },
                error:function(a,b,c) { swal.fire("Gagal", a.responseText, "error"); }
          });
    }
</script>
