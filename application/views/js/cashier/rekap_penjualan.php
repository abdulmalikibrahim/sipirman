<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
      // ============================================================
      // DROPDOWN GANTI TABEL
      // ============================================================
      function ganti_tabel(val) {
            if(val === 'cashless'){
                  $("#section-cashless").show();
                  $("#section-tunai").hide();
            }else{
                  $("#section-cashless").hide();
                  $("#section-tunai").show();
            }
            // Reset filter saat ganti tabel
            reset_filter_rows();
      }

      // ============================================================
      // FILTER
      // ============================================================
      function terapkan_filter() {
            var tglMulai   = $("#filter-tgl-mulai").val();
            var tglSelesai = $("#filter-tgl-selesai").val();
            var namaWbp    = $("#filter-nama-wbp").val().toLowerCase().trim();
            var namaBarang = $("#filter-nama-barang").val().toLowerCase().trim();
            var jenis      = $("#filter-jenis-tabel").val();

            var tbodyId = (jenis === 'cashless') ? 'tbody-cashless' : 'tbody-tunai';
            filter_tbody(tbodyId, tglMulai, tglSelesai, namaWbp, namaBarang);
      }

      function filter_tbody(tbodyId, tglMulai, tglSelesai, namaWbp, namaBarang) {
            $("#"+tbodyId+" tr").each(function(){
                  var tglBaris    = $(this).data("tgl")    || "";
                  var wbpBaris    = ($(this).data("wbp")   || "").toString();
                  var barangBaris = ($(this).data("barang") || "").toString();

                  var lolos = true;
                  if(tglMulai   && tglBaris < tglMulai)                   lolos = false;
                  if(tglSelesai && tglBaris > tglSelesai)                  lolos = false;
                  if(namaWbp    && wbpBaris.indexOf(namaWbp) === -1)       lolos = false;
                  if(namaBarang && barangBaris.indexOf(namaBarang) === -1)  lolos = false;

                  $(this).toggle(lolos);
            });
      }

      function reset_filter() {
            $("#filter-tgl-mulai").val("");
            $("#filter-tgl-selesai").val("");
            $("#filter-nama-wbp").val("");
            $("#filter-nama-barang").val("");
            reset_filter_rows();
      }

      function reset_filter_rows() {
            $("#tbody-cashless tr, #tbody-tunai tr").show();
      }
</script>