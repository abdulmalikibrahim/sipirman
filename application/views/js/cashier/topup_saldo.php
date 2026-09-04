<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
      function cari_wbp(e) {
            var nama_input = e.value.trim();
            $("#kode_tahanan").val("");
            if(nama_input){
                  var found = null;
                  $("#nama-tahanan option").each(function(){
                        if($(this).val().toLowerCase() === nama_input.toLowerCase()){
                              found = $(this);
                              return false;
                        }
                  });
                  if(found){
                        var saldo = parseInt(found.data("saldo")) || 0;
                        $("#kode_tahanan").val(found.data("code"));
                        $("#lbl-nama-wbp").html("NAMA : "+found.val());
                        if(saldo < 0){
                              $("#lbl-saldo-wbp").html("<span class='text-danger'>SALDO DIGITAL : -Rp. "+formatharga(Math.abs(saldo))+" (HUTANG)</span>");
                        }else{
                              $("#lbl-saldo-wbp").html("SALDO DIGITAL : Rp. "+formatharga(saldo));
                        }
                  }else{
                        $("#lbl-nama-wbp").html('<span class="text-danger">NAMA : TIDAK DITEMUKAN</span>');
                        $("#lbl-saldo-wbp").html("");
                  }
            }else{
                  $("#lbl-nama-wbp").html("");
                  $("#lbl-saldo-wbp").html("");
            }
      }

      function format_nominal(e) {
            e.value = formatharga(e.value);
      }

      function proses_topup() {
            kode_tahanan = $("#kode_tahanan").val();
            nominal = parseInt(($("#nominal").val()+"").replace(/\./g,"")) || 0;
            if(!kode_tahanan){
                  swal.fire("Warning","Mohon pilih WBP terlebih dahulu","warning");
                  return;
            }
            if(nominal <= 0){
                  swal.fire("Warning","Mohon masukkan nominal top up","warning");
                  return;
            }
            $("#input-pin").val("");
            $(".pin-dot").removeClass("filled error");
            $("#pin-error-msg").hide();
            $("#modalPin").modal("show");
            setTimeout(function(){ $("#input-pin").focus(); }, 400);
      }

      function tutup_modal_pin() {
            $("#input-pin").val("");
            $(".pin-dot").removeClass("filled error");
            $("#pin-error-msg").hide();
            $("#modalPin").modal("hide");
      }

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
            if(len >= 6){ konfirmasi_topup(); }
      }

      function pin_key(digit) {
            var current = $("#input-pin").val();
            if(current.length < 6){
                  $("#input-pin").val(current + digit);
                  update_pin_dots(document.getElementById("input-pin"));
            }
      }

      function pin_hapus() {
            var current = $("#input-pin").val();
            $("#input-pin").val(current.slice(0, -1));
            update_pin_dots(document.getElementById("input-pin"));
      }

      function konfirmasi_topup() {
            var pin = $("#input-pin").val();
            if(pin.length < 6){
                  swal.fire("Info","Masukkan 6 digit PIN terlebih dahulu","info");
                  return;
            }
            kode_tahanan = $("#kode_tahanan").val();
            nominal = parseInt(($("#nominal").val()+"").replace(/\./g,"")) || 0;
            $.ajax({
                  type:"post",
                  url:"<?= base_url("simpan_topup") ?>",
                  data:{ code_napi: kode_tahanan, nominal: nominal, pin: pin },
                  dataType:"JSON",
                  beforeSend:function(){ $(".pin-key").prop("disabled", true); },
                  success:function(r) {
                        $(".pin-key").prop("disabled", false);
                        if(r.status == 200){
                              $("#modalPin").modal("hide");
                              swal.fire({ title:r.title, html:r.res, icon:r.icon })
                              .then((result) => { if(result.isConfirmed){ location.reload(); } });
                        }else{
                              $("#pin-error-msg").show();
                              $("#pin-error-msg").html("<i class='fas fa-exclamation-circle mr-1'></i>"+r.res);
                              $(".pin-dot").addClass("error");
                              setTimeout(function(){
                                    $("#input-pin").val("");
                                    $(".pin-dot").removeClass("filled error");
                              }, 800);
                        }
                  },
                  error:function(a,b,c){
                        $(".pin-key").prop("disabled", false);
                        swal.fire("Error", "Gagal memproses top up: " + a.responseText, "error");
                  }
            });
      }
</script>
