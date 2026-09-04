<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
      $("#foto_bukti").click(function() {
            $("#bukti").trigger("click");
      });

      $("#diserahkan").keyup(function() {
            var tersedia = <?= $this->input->get("i"); ?>;
            var value = $(this).val();
            if(parseInt(value.replace(".","")) > tersedia){
                  swal.fire("Warning","Jumlah yang anda masukkan melebihi sisa uang yang tersedia.","warning");
                  $(this).val("");
            }
      });

      $("#bukti").change(function(e) {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("bukti").files[0]);

            oFReader.onload = function (oFREvent) {
                  document.getElementById("foto_bukti").src = oFREvent.target.result;
            };
      });

      function formatrupiah(angka, prefix){
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split   		= number_string.split(','),
            sisa     		= split[0].length % 3,
            harga     		= split[0].substr(0, sisa),
            ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if(ribuan){
                  separator = sisa ? '.' : '';
                  harga += separator + ribuan.join('.');
            }

            harga = split[1] != undefined ? harga + ',' + split[1] : harga;
            return prefix == undefined ? harga : (harga ? harga : '');
      }

      var uangdipegang = parseInt($("#uang-dipegang").html());
      $("#uangdiwbp").html("Uang di WBP : Rp. "+formatrupiah(uangdipegang));

      $("#btnsimpan").click(function() {
            var uangdipegang = parseInt($("#uang-dipegang").html());
            if(uangdipegang > 0){
                  swal.fire({
                        title:"Warning",
                        html:"WBP masih memiliki pegangan uang<br>sebesar Rp. "+formatharga(uangdipegang)+"<br>Apakah anda yakin akan melanjutkan proses penyerahan?",
                        icon:"warning",
                        showConfirmButton:true,
                        showDenyButton:true,
                        denyButtonText:"Tidak",
                        confirmButtonText:"Ya, Lanjutkan",
                  }).then((result) => {
                        if(result.isConfirmed){
                              if($("#diserahkan").val()){
                                    if($("#bukti").val()){
                                          $("#form-submit").submit();
                                    }else{
                                          alert("Warning","Mohon masukkan foto bukti penyerahan","warning");
                                    }
                              }else{
                                    alert("Warning","Mohon isi nominal penyerahan terlebih dahulu","warning");
                              }
                        }else{
                              swal.close();
                        }
                  });
            }else{
                  if($("#diserahkan").val()){
                        if($("#bukti").val()){
                              $("#form-submit").submit();
                        }else{
                              alert("Warning","Mohon masukkan foto bukti penyerahan","warning");
                        }
                  }else{
                        alert("Warning","Mohon isi nominal penyerahan terlebih dahulu","warning");
                  }
            }
      });
      
      function transaksiterbaru() {
            var scrollPos =  $("#transaksi-terbaru").offset().top;
            $("#body-rincian-uang-tunai").scrollTop(scrollPos);
            $("#transaksi-terbaru").addClass("bg-warning");
            var no = 1;
            blinking = setInterval(function() {
                  $('#transaksi-terbaru').fadeOut(100);
                  $('#transaksi-terbaru').fadeIn(100);
                  no++;
                  if(no >= 5){
                        clearInterval(blinking);
                        $("#transaksi-terbaru").removeClass("bg-warning");
                  }
            }, 200);

      }

      function totop() {
            var scrollPos =  $("#start-rincian").offset().top - 200;
            $("#body-rincian-uang-tunai").scrollTop(scrollPos);
            console.log("start-rincian");
      }

      var height_body = $("#body-rincian-uang-tunai")[0].clientHeight;
      var height_table = $("#table-rincian")[0].clientHeight;
      function show_arrow_up() {
            if(height_table >= height_body){
                  $("#arrow-up").show();
            }else{
                  $("#arrow-up").hide();
            }
      }
      show_arrow_up();
</script>