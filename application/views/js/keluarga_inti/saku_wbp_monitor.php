<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/fh-3.2.4/r-2.3.0/datatables.min.js"></script>
<script>
      $(document).ready(function() {
            $('#datatable').DataTable({
                  responsive: true,
            });
      });

      function under_develop() {
            swal.fire("Information","Fitur ini masih dalam tahap perkembangan","info");
      }

      function bukan_ki() {
            swal.fire("Information","Maaf, fitur ini hanya untuk keluarga inti, anda tidak tercatat sebagai keluarga inti WBP manapun.","info");
      }
</script>