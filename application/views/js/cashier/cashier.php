<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
      $("#datatable-tahanan").DataTable({
            paging:false,
      });

      // ============================================================
      // FIX #1: DROPDOWN / DATALIST
      // - Ganti event onkeyup → oninput (sudah di HTML)
      // - Cocokkan berdasarkan value input yang dipilih dari datalist
      //   (value berisi code_napi, label berisi nama)
      // ============================================================
      function check_keuangan_wbp(e) {
            var nama_input = e.value.trim();
            if(nama_input){
                  // Cari baris di tabel datatable-tahanan yang namanya cocok
                  var code_napi = "";
                  $("#datatable-tahanan tbody tr").each(function(){
                        var nama_di_tabel = $(this).find("td:nth-child(2)").text().trim();
                        if(nama_di_tabel.toLowerCase() === nama_input.toLowerCase()){
                              code_napi = $(this).data("code-napi");
                              return false; // break
                        }
                  });

                  if(code_napi){
                        var nama_wbp    = $("#nama-wbp-"+code_napi).html();
                        var uang_digital = $("#sisa-uang-digital-"+code_napi).html();
                        var uang_manual  = $("#sisa-uang-manual-"+code_napi).html();
                        $("#lbl-nama-wbp").html("NAMA : "+nama_wbp);
                        $("#lbl-uang-digital-wbp").html("UANG DIGITAL : "+uang_digital);
                        $("#lbl-uang-manual-wbp").html("UANG DIPEGANG : "+uang_manual);
                  }else{
                        $("#lbl-nama-wbp").html('<span class="text-danger">NAMA : TIDAK DITEMUKAN</span>');
                        $("#lbl-uang-digital-wbp").html("");
                        $("#lbl-uang-manual-wbp").html("");
                  }
            }else{
                  $("#lbl-nama-wbp").html("");
                  $("#lbl-uang-digital-wbp").html("");
                  $("#lbl-uang-manual-wbp").html("");
            }
      }

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
                        beforeSend:function() {
                              $("#kode_barang_"+id).val("Memuat...");
                              $("#harga_"+id).val("Memuat...");
                              $("#btn-next-process").attr("disabled",true);
                              $("#btn-next-process").removeClass("btn-info").addClass("btn-secondary");
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
                                          add_row = '<tr id="row_'+new_id+'"><td><input type="text" name="kode_barang[]" id="kode_barang_'+new_id+'" class="form-control"></td><td><input type="text" name="nama_barang[]" id="nama_barang_'+new_id+'" data-type="tambah" data-id="'+new_id+'" list="data_barang" class="form-control" onchange="get_data(this)"></td><td><input type="number" name="qty[]" onkeyup="ganti_qty(this)" onchange="ganti_qty(this)" data-id="'+new_id+'" id="qty_'+new_id+'" class="form-control" value=""></td><td><input type="text" name="total[]" id="total_'+new_id+'" class="form-control harga harga-total text-dark" readonly><input type="text" name="harga[]" id="harga_'+new_id+'" class="form-control harga" hidden></td><td class="align-middle"><a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" onclick="delete_row(this)" data-id="'+new_id+'"><i class="fas fa-trash-alt m-0"></i></a></td></tr>';
                                          $("#list-barang").append(add_row);
                                    }
                              }else{
                                    $("#kode_barang_"+id).val("-");
                                    $("#harga_"+id).val("0");
                              }
                              $("#btn-next-process").attr("disabled",false);
                              $("#btn-next-process").removeClass("btn-secondary").addClass("btn-info");
                        },
                        error:function(a,b,c) {
                              console.log(a.responseText);
                        }
                  });
            }
      }

      function ganti_qty(data) {
            id    = data.dataset.id;
            qty   = $("#qty_"+id).val();
            harga = $("#harga_"+id).val();
            harga = harga.replace(/\./g,"");
            total_harga = parseInt(harga) * parseInt(qty);
            $("#total_"+id).val(formatharga(total_harga));
            grand_total();
      }

      function delete_row(data) {
            id = data.dataset.id;
            $("#row_"+id).remove();
            grand_total();
      }

      function grand_total() {
            data_beli  = $(".harga-total");
            var grandtotal = 0;
            for (let i = 0; i < data_beli.length; i++) {
                  total_belanja = data_beli[i].value.replace(/\./g,"");
                  if(total_belanja > 0){
                        grandtotal += parseInt(total_belanja);
                  }
            }
            $("#grand-total").html(formatharga(grandtotal));
      }

      function pick_tahanan(data) {
            total_belanja = parseInt($("#grand-total").html().replace(/\./g,""));
            if(total_belanja > 0){
                  code_napi = data.dataset.codeNapi;
                  $.ajax({
                        type:"get",
                        url:"<?= base_url("diserahkan_uang") ?>",
                        data:{ code_napi:code_napi },
                        beforeSend:function() { console.log("Loading..."); },
                        success:function(r) {
                              $("#kode_tahanan").val(code_napi);
                              $("#uang-digital").attr("data-code-napi", code_napi);
                              $("#uang-manual").attr("data-code-napi", code_napi);
                              $("#pilihtahanan").modal("hide");
                              $("#pilihpenggunaanuang").modal("show");
                        },
                        error:function(a,b,c) { console.log(a.responseText); }
                  });
            }else{
                  swal.fire("Warning","Mohon masukkan barang belanja","warning");
            }
      }

      function pilih_tahanan() {
            total_belanja = parseInt($("#grand-total").html().replace(/\./g,""));
            if(total_belanja > 0){
                  $("#pilihtahanan").modal("show");
            }else{
                  swal.fire("Warning","Mohon masukkan barang belanja","warning");
            }
      }

      $("#foto_bukti").click(function() { $("#bukti").trigger("click"); });

      $("#bukti").change(function(e) {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("bukti").files[0]);
            oFReader.onload = function(oFREvent) {
                  document.getElementById("foto_bukti").src = oFREvent.target.result;
            };
      });

      function proses_pembayaran(data) {
            bukti = $("#bukti").val();
            if(bukti){
                  code_napi = data.dataset.codeNapi;
                  var nama_barang = $("input[name='nama_barang[]']").map(function(){ return this.value; }).get();
                  var qty         = $("input[name='qty[]']").map(function(){ return this.value; }).get();
                  var total       = $("input[name='total[]']").map(function(){ return this.value; }).get();
                  var formData    = new FormData();
                  formData.append('nama_barang', nama_barang);
                  formData.append('qty', qty);
                  formData.append('total', total);
                  formData.append('code_napi', code_napi);
                  formData.append('bukti', $("#bukti")[0].files[0]);
                  $.ajax({
                        enctype:'multipart/form-data',
                        type:"post",
                        url:"<?= base_url("use_money_manual") ?>",
                        data:formData,
                        dataType:"JSON",
                        processData:false,
                        contentType:false,
                        beforeSend:function(){ loading_page('Menyimpan...','Sedang menyimpan data...'); },
                        success:function(r) {
                              d = JSON.parse(JSON.stringify(r));
                              if(d.status == 200){
                                    swal.fire({ title:d.title, html:d.res, icon:d.icon })
                                    .then((result) => { if(result.isConfirmed){ location.reload(); } });
                              }else{
                                    swal.fire(d.title, d.res, d.icon);
                              }
                        },
                        error:function(a,b,c){ swal.fire("Error", a.responseText, "error"); }
                  });
            }else{
                  swal.fire("Error","Foto Bukti tidak boleh kosong","error");
            }
      }

      // ============================================================
      // FIX #2: SISTEM PIN UNTUK PEMBAYARAN DIGITAL
      // - Tombol "UANG DIGITAL" kini memanggil tampil_modal_pin()
      // - PIN divalidasi via AJAX ke endpoint verify_pin
      // - Setelah PIN benar, baru jalankan use_money_digital()
      // ============================================================
      var _pin_code_napi = ""; // simpan sementara code_napi saat PIN diminta

      function tampil_modal_pin(data) {
            _pin_code_napi = data.dataset.codeNapi;
            // Reset state PIN
            $("#input-pin").val("");
            $(".pin-dot").removeClass("filled error");
            $("#pin-error-msg").hide();
            // Tampilkan modal PIN, sembunyikan modal sebelumnya
            $("#pilihpenggunaanuang").modal("hide");
            setTimeout(function(){
                  $("#modalPin").modal("show");
                  // Fokus ke input tersembunyi supaya keyboard mobile bisa muncul
                  setTimeout(function(){ $("#input-pin").focus(); }, 400);
            }, 400);
      }

      function tutup_modal_pin() {
            $("#input-pin").val("");
            $(".pin-dot").removeClass("filled error");
            $("#pin-error-msg").hide();
            $("#modalPin").modal("hide");
            // Kembalikan ke modal pilih penggunaan
            setTimeout(function(){
                  $("#pilihpenggunaanuang").modal("show");
            }, 400);
      }

      // Update tampilan titik sesuai panjang PIN
      function update_pin_dots(input) {
            var len = input.value.length;
            for(var i = 1; i <= 6; i++){
                  if(i <= len){
                        $("#dot-"+i).addClass("filled").removeClass("error");
                  }else{
                        $("#dot-"+i).removeClass("filled error");
                  }
            }
            $("#pin-error-msg").hide();
            // Auto submit jika sudah 6 digit
            if(len >= 6){ konfirmasi_pin(); }
      }

      // Numpad: tambah digit
      function pin_key(digit) {
            var current = $("#input-pin").val();
            if(current.length < 6){
                  $("#input-pin").val(current + digit);
                  update_pin_dots(document.getElementById("input-pin"));
            }
      }

      // Numpad: hapus digit terakhir
      function pin_hapus() {
            var current = $("#input-pin").val();
            $("#input-pin").val(current.slice(0, -1));
            update_pin_dots(document.getElementById("input-pin"));
      }

      // Konfirmasi PIN ke server lalu proses pembayaran
      function konfirmasi_pin() {
            var pin = $("#input-pin").val();
            if(pin.length < 6){
                  swal.fire("Info","Masukkan 6 digit PIN terlebih dahulu","info");
                  return;
            }

            $.ajax({
                  type:"post",
                  url:"<?= base_url("verify_pin") ?>",
                  data:{ pin: pin, code_napi: _pin_code_napi },
                  dataType:"JSON",
                  beforeSend:function(){
                        $(".pin-key").prop("disabled", true);
                  },
                  success:function(r) {
                        $(".pin-key").prop("disabled", false);
                        if(r.status == 200){
                              // PIN benar — tutup modal PIN dan jalankan pembayaran digital
                              $("#modalPin").modal("hide");
                              setTimeout(function(){
                                    exec_use_money_digital(_pin_code_napi);
                              }, 400);
                        }else{
                              // PIN salah — tampilkan pesan error, shake dots
                              $("#pin-error-msg").show();
                              $(".pin-dot").addClass("error");
                              setTimeout(function(){
                                    $("#input-pin").val("");
                                    $(".pin-dot").removeClass("filled error");
                              }, 800);
                        }
                  },
                  error:function(a,b,c){
                        $(".pin-key").prop("disabled", false);
                        swal.fire("Error", "Gagal memverifikasi PIN: " + a.responseText, "error");
                  }
            });
      }

      // Eksekusi pembayaran digital (dipanggil setelah PIN terverifikasi)
      function exec_use_money_digital(code_napi) {
            var nama_barang = $("input[name='nama_barang[]']").map(function(){ return this.value; }).get();
            var qty         = $("input[name='qty[]']").map(function(){ return this.value; }).get();
            var total       = $("input[name='total[]']").map(function(){ return this.value; }).get();
            var formData    = new FormData();
            formData.append('nama_barang', nama_barang);
            formData.append('qty', qty);
            formData.append('total', total);
            formData.append('code_napi', code_napi);
            // bukti opsional untuk digital (bisa dikosongkan jika memang tidak wajib)
            if($("#bukti")[0].files[0]){
                  formData.append('bukti', $("#bukti")[0].files[0]);
            }
            $.ajax({
                  enctype:'multipart/form-data',
                  type:"post",
                  url:"<?= base_url("use_money_digital") ?>",
                  data:formData,
                  dataType:"JSON",
                  processData:false,
                  contentType:false,
                  beforeSend:function(){ loading_page('Menyimpan...','Check penyimpanan dan pemotongan uang secara otomatis'); },
                  success:function(r) {
                        d = JSON.parse(JSON.stringify(r));
                        if(d.status == 200){
                              swal.fire({ title:d.title, html:d.res, icon:d.icon })
                              .then((result) => { if(result.isConfirmed){ location.reload(); } });
                        }else{
                              swal.fire(d.title, d.res, d.icon);
                        }
                  },
                  error:function(a,b,c){ swal.fire("Error", a.responseText, "error"); }
            });
      }

      // Fungsi use_money_digital lama dipertahankan untuk backward compatibility
      function use_money_digital(data) {
            tampil_modal_pin(data);
      }

      $("#table-uang-tunai").hide();
      function show_uang_tunai() {
            $("#table-uang-tunai").show();
      }
</script>