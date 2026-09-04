<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
      function transaksiterbaru() {
            var scrollPos =  $("#transaksi-terbaru").offset().top;
            $(window).scrollTop(scrollPos);
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
            var scrollPos =  $("#headertop").offset().top - 100;
            $(window).scrollTop(scrollPos);
            console.log("headertop");
      }

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
</script>