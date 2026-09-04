<script src="<?= base_url("assets/plugins/jquery/jquery.min.js") ?>"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="<?= base_url("assets/plugins/bootstrap/js/tether.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/bootstrap/js/bootstrap.min.js") ?>"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?= base_url("assets/js/jquery.slimscroll.js"); ?>"></script>
<!--Wave Effects -->
<script src="<?= base_url("assets/js/waves.js"); ?>"></script>
<!--Menu sidebar -->
<script src="<?= base_url("assets/js/sidebarmenu.js"); ?>"></script>
<!--stickey kit -->
<script src="<?= base_url("assets/plugins/sticky-kit-master/dist/sticky-kit.min.js") ?>"></script>
<!--Custom JavaScript -->
<script src="<?= base_url("assets/js/custom.min.js"); ?>"></script>
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!-- chartist chart -->
<!-- <script src="<?= base_url("assets/plugins/chartist-js/dist/chartist.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js") ?>"></script>
<script src="<?= base_url("assets/js/dashboard1.js"); ?>"></script> -->
<!--c3 JavaScript -->
<script src="<?= base_url("assets/plugins/d3/d3.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/c3-master/c3.min.js") ?>"></script>
<!-- Chart JS -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XLY2L9X9Q3"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-XLY2L9X9Q3');
  
  $(".harga").keyup(function() {
    $(this).val(formatharga($(this).val(),''))
  });
  function formatharga(angka, prefix){
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

  function alert(title,msg,icon) {
    swal.fire({title:title,html:msg,icon:icon});
  }
  function sleep(milliseconds) {
    const date = Date.now();
    let currentDate = null;
    do {
      currentDate = Date.now();
    } while (currentDate - date < milliseconds);
  }
	function loading_page(title,pesan) {
		if(title == ''){
			title = "Please Wait...";
		}
		swal.fire({
			title: "<font style='color:white'>"+title+"</font>",
			html:"<font style='color:white'>"+pesan+"</font>",
			background:'rgba(0,0,0,0)',
			showConfirmButton: false,
			allowOutsideClick: false
		});
	}
</script>
<?php
if(!empty($this->session->flashdata("swal"))){
    echo "<!-- ADA SWAL -->";
}
?>
