<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
      $("#datatable-tahanan").DataTable({
            paging:false,
      });

      $(document).ready(function() {
            $(window).keydown(function(event){
                  if(event.keyCode == 13) {
                        event.preventDefault();
                        return false;
                  }
            });
      });
      $("#wbp").change(function() {
            sisa_uang = $(this).find(":selected").attr("data-sisa-uang");
            $("#sisa-uang").html("Sisa Uang : Rp. "+sisa_uang);
      });

      function get_data(e) {
            id = e.dataset.id;
            type = e.dataset.type;
            value = e.value;
            if(value){
                  $.ajax({
                        type:"post",
                        url:"<?= base_url("check_barang") ?>",
                        data:{
                              nama_barang:value,
                        },
                        dataType:"JSON",
                        beforeSend:function() {
                              $("#kode_barang_"+id).val("Memuat...");
                              $("#harga_"+id).val("Memuat...");
                        },
                        success:function(r) {
                              d = JSON.parse(JSON.stringify(r));
                              if(d.status == 200){
                                    $("#nama_barang_"+id).attr("data-type","edit");
                                    $("#kode_barang_"+id).val(d.kode_barang);
                                    $("#qty_"+id).val("1");
                                    $("#harga_"+id).val(d.harga);
                                    $("#total_"+id).val(d.harga);
                                    grand_total();
                                    if(type == "tambah"){
                                          new_id = Date.now();
                                          add_row = '<div class="row" id="row_'+new_id+'"><div class="col-12"><hr class="d-lg-none d-block mt-2 mb-1" style="border-color:#000;"><hr class="d-lg-none d-block mb-3 mt-0" style="border-color:#000;"></div><div class="col-lg-5 mb-2"><input type="text" name="nama_barang[]" id="nama_barang_'+new_id+'" data-type="tambah" data-id="'+new_id+'" list="data_barang" class="form-control nama_barang" onchange="get_data(this)" placeholder="Masukkan Nama Barang" autocomplete="false"></div><div class="col-lg-3 col-5 mb-2 pl-lg-0"><input type="number" name="qty[]" onkeyup="ganti_qty(this)" onchange="ganti_qty(this)" data-id="'+new_id+'" id="qty_'+new_id+'" class="form-control qty" placeholder="Qty Barang" value=""></div><div class="col-lg-3 col-5 mb-2 pl-0 pr-0"><input type="text" name="total[]" id="total_'+new_id+'" placeholder="Sub Total" class="form-control harga harga-total" readonly><input type="text" name="harga[]" id="harga_'+new_id+'" class="form-control harga harga-satuan" hidden></div><div class="col-lg-1 col-1 mb-2"><a href="javascript:void(0)" class="btn btn-danger" title="Delete" onclick="delete_row(this)" data-id="'+new_id+'"><i class="fas fa-trash-alt m-0"></i></a></div></div>';
      
                                          $("#list-barang").append(add_row);
                                    }
                              }else{
                                    $("#kode_barang_"+id).val("-");
                                    $("#harga_"+id).val("0");
                              }
                        },
                        error:function(a,b,c) {
                              console.log(a.responseText);
                        }
                  });
            }
      }

      function ganti_qty(data) {
            id = data.dataset.id;
            qty = $("#qty_"+id).val();
            harga = $("#harga_"+id).val();
            harga = harga.replace(".","");
            total_harga = parseInt(harga)*parseInt(qty);
            $("#total_"+id).val(formatharga(total_harga));
            grand_total();
      }

      function grand_total() {
            data_beli = $(".harga-total");
                  console.log(data_beli);
            var grand_total = 0;
            for (let i = 0; i < data_beli.length; i++) {
                  total_belanja = data_beli[i].value;
                  total_belanja = total_belanja.replace(".","");
                  if(total_belanja > 0){
                        grand_total += parseInt(total_belanja);
                  }
            }
            $("#grand-total").html(formatharga(grand_total));
      }

      function delete_row(data) {
            id = data.dataset.id;
            $("#row_"+id).remove();
            grand_total();
      }

      function pembelian_barang_ki() {
            code_napi = data.dataset.codeNapi;
            var nama_barang = $("input[name='nama_barang[]']").map(function() {
                  return this.value;
            }).get();
            var qty = $("input[name='qty[]']").map(function() {
                  return this.value;
            }).get();
            var total = $("input[name='total[]']").map(function() {
                  return this.value;
            }).get();
            var formData = new FormData();
            formData.append('nama_barang', nama_barang);
            formData.append('qty', qty);
            formData.append('total', total);
            formData.append('code_napi', code_napi);
            // Attach file
            formData.append('bukti', $("#bukti")[0].files[0]);
            $.ajax({
                  enctype: 'multipart/form-data',
                  type:"post",
                  url:"<?= base_url("pembelian_barang_ki") ?>",
                  data:formData,
                  dataType:"JSON",
                  processData: false,
                  contentType: false,
                  beforeSend:function() {
                        // loading_page('Meyimpan...','Check penyimpanan dan pemotongan uang secara otomatis');
                  },
                  success:function(r) {
                        d = JSON.parse(JSON.stringify(r));
                        if(d.status == 200){
                              swal.fire({
                                    title:d.title,
                                    html:d.res,
                                    icon:d.icon,
                              }).then((result) => {
                                    if(result.isConfirmed){
                                          location.reload();
                                    }
                              });
                        }else{
                              swal.fire(d.title,d.res,d.icon);
                        }
                  },
                  error:function(a,b,c) {
                        swal.fire("Error",a.responseText,"error");
                  }
            })
      }
</script>