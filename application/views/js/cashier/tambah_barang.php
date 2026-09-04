<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
      $("#nama_barang").focus();
      $("#foto_barang").click(function() {
            $("#upload_foto_barang").trigger("click");
      });

      $("#upload_foto_barang").change(function(e) {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("upload_foto_barang").files[0]);

            oFReader.onload = function (oFREvent) {
                  document.getElementById("foto_barang").src = oFREvent.target.result;
            };
      });
</script>