<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
      $("#foto_dokumentasi").click(function() { $("#dokumentasi").trigger("click"); });

      $("#dokumentasi").change(function(e) {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("dokumentasi").files[0]);
            oFReader.onload = function(oFREvent) {
                  document.getElementById("foto_dokumentasi").src = oFREvent.target.result;
            };
      });

      function get_data(e) {
            id    = e.dataset.id;
            type  = e.dataset.type;
            value = e.value;
            if(value){
                  $.ajax({
                        type:"post",
                        url:"<?= base_url("check_barang") ?>",
                        data:{ nama_barang:value },
                        dataType:"JSON",
                        success:function(r) {
                              d = JSON.parse(JSON.stringify(r));
                              if(d.status == 200){
                                    $("#nama_barang_"+id).attr("data-type","edit");
                                    $("#stok_"+id).val(d.stok);
                                    if(type == "tambah"){
                                          new_id = Date.now();
                                          add_row = '<tr id="row_'+new_id+'">'+
                                                '<td><input type="text" name="nama_barang[]" id="nama_barang_'+new_id+'" data-type="tambah" data-id="'+new_id+'" list="data_barang" class="form-control" onchange="get_data(this)"></td>'+
                                                '<td><input type="text" id="stok_'+new_id+'" class="form-control" readonly></td>'+
                                                '<td><input type="number" min="1" name="jumlah_barang[]" onkeyup="hitung_subtotal(this)" onchange="hitung_subtotal(this)" data-id="'+new_id+'" id="jumlah_'+new_id+'" class="form-control"></td>'+
                                                '<td><input type="text" name="harga_tengkulak[]" onkeyup="format_harga_tengkulak(this)" onchange="hitung_subtotal(this)" data-id="'+new_id+'" id="harga_'+new_id+'" class="form-control"></td>'+
                                                '<td><input type="text" id="subtotal_'+new_id+'" class="form-control" readonly></td>'+
                                                '<td class="align-middle"><a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" onclick="delete_row(this)" data-id="'+new_id+'"><i class="fas fa-trash-alt m-0"></i></a></td>'+
                                                '</tr>';
                                          $("#list-barang").append(add_row);
                                    }
                              }else{
                                    $("#stok_"+id).val("-");
                              }
                        },
                        error:function(a,b,c) { console.log(a.responseText); }
                  });
            }
      }

      function format_harga_tengkulak(data) {
            id = data.dataset.id;
            $("#harga_"+id).val(formatharga($("#harga_"+id).val()));
            hitung_subtotal(data);
      }

      function hitung_subtotal(data) {
            id     = data.dataset.id;
            jumlah = parseInt($("#jumlah_"+id).val()) || 0;
            harga  = ($("#harga_"+id).val()+"").replace(/\./g,"");
            harga  = parseInt(harga) || 0;
            subtotal = jumlah * harga;
            $("#subtotal_"+id).val(formatharga(subtotal));
            grand_total();
      }

      function delete_row(data) {
            id = data.dataset.id;
            $("#row_"+id).remove();
            grand_total();
      }

      function grand_total() {
            data_subtotal = $("[id^='subtotal_']");
            var grandtotal = 0;
            for (let i = 0; i < data_subtotal.length; i++) {
                  nilai = (data_subtotal[i].value+"").replace(/\./g,"");
                  if(nilai > 0){
                        grandtotal += parseInt(nilai);
                  }
            }
            $("#grand-total").html(formatharga(grandtotal));
      }

      $("form").submit(function(e) {
            grand_total_value = parseInt($("#grand-total").html().replace(/\./g,""));
            if(!grand_total_value || grand_total_value <= 0){
                  e.preventDefault();
                  swal.fire("Warning","Mohon lengkapi daftar barang kulakan (jumlah & harga tengkulak)","warning");
            }
      });
</script>
