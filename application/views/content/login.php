<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPIRMAN</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png'); ?>">
	<link href="<?= base_url("assets/plugins/bootstrap/css/bootstrap.min.css"); ?>" rel="stylesheet">
	<link href="<?= base_url("assets/style.css"); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" integrity="sha512-XJ3ntWHl40opEiE+6dGhfK9NAKOCELrpjiBRQKtu6uJf9Pli8XY+Hikp7rlFzY4ElLSFtzjx9GGgHql7PLSeog==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body style="overflow-x: hidden;">
    <div class="page-wrapper">
        <div class="page-inner">
            <div></div>
        </div>
        <div class="row">
            <div class="col-lg-6 bg-img d-none d-lg-block">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="height:100%;">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" style="height:100%">
                        <div class="carousel-item active" style="height:100%">
                            <img class="d-block w-100" src="<?= base_url("assets/img/banner-sipirman.jpg?v1"); ?>" alt="First slide">
                        </div>
                        <div class="carousel-item" style="height:100%">
                            <img class="d-block w-100" src="<?= base_url("assets/img/banner-saku.jpg?v1"); ?>" alt="Second slide">
                        </div>
                        <div class="carousel-item" style="height:100%">
                            <img class="d-block w-100" src="<?= base_url("assets/img/banner-rumart.jpg?v1"); ?>" alt="Third slide">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 bg-img" style="min-height: 100vh;">
                <div class="row">
                    <div class="col-10 col-lg-6 m-auto">
                        <div class="row">
                            <div class="col-lg-12">
                                <img src="<?= base_url("assets/img/logo-sipirman.jpg"); ?>" width="100%;" align="center">
                                <div class="row">
                                    <div class="col-lg-3 col-6 pr-lg-1 pr-1">
                                        <a href="https://forms.gle/2AMsQyUbgWz5LNqeA" target="_blank" class="mb-1 p-1 pt-2 pb-2 text-center btn btn-info text-white w-100 btn-pengaduan" style="font-size:6pt;"><b>PENGADUAN</b></a>
                                    </div>
                                    <div class="col-lg-3 col-6 pr-lg-1 pl-lg-0 pl-1">
                                        <a href="https://wa.me/6282324735454?text=*INFO%20SIPIRMAN*%0A%0ASilahkan%20sampaikan%20pertanyaan%20anda..." target="_blank" class="mb-1 p-1 pt-2 pb-2 text-center btn btn-info text-white w-100 btn-informasi" style="font-size:6pt;"><b>INFORMASI</b></a>
                                    </div>
                                    <div class="col-lg-3 col-6 pr-lg-1 pl-lg-0 pr-1">
                                        <a href="https://survei-bsk.kemenkumham.go.id/ly/xjlnWERb" target="_blank" class="mb-1 p-1 pt-2 pb-2 text-center btn btn-info text-white w-100 btn-survei-kepuasan" style="font-size:6pt;"><b>SURVEI KEPUASAN</b></a>
                                    </div>
                                    <div class="col-lg-3 col-6 pl-lg-0 pl-1">
                                        <a href="https://bit.ly/manual-book-sipirman" target="_blank" class="mb-1 p-1 pt-2 pb-2 text-center btn btn-info text-white w-100 btn-survei-kepuasan" style="font-size:6pt;"><b>PANDUAN</b></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <form action="" id="login-form" method="post" style="display: none;" align="center">
                                    <div class="col-lg-12 mt-2" align="left">
                                        <label><b>Username</b></label>
                                        <input type="text" name="username" id="username" placeholder="Username" class="form-control">
                                    </div>
                                    <div class="col-lg-12 mt-2" align="left">
                                        <label><b>Password</b></label>
                                        <input type="password" name="password" id="password" placeholder="Password" class="form-control">
                                    </div>
                                    <div class="col-lg-12 mt-2">
                                        <button class="btn btn-warning text-white w-100 poppins" name="btn-login"><b>Login</b></button>
                                    </div>
                                    <div class="col-lg-12 mt-2">
                                    <a href="javascript:void(0)" class="mb-1 btn btn-danger text-white w-100 poppins btn-lacak"><b>Kembali</b></a>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12">
                                <form action="<?= base_url("login_dashboard"); ?>" id="login-form-dashboard" method="post" style="display: none;" align="center">
                                    <label class="mt-2 mb-0 font-weight-bold">LOGIN DASHBOARD</label>
                                    <div class="col-lg-12 mt-2" align="left">
                                        <label><b>Username</b></label>
                                        <input type="text" name="username" id="username" placeholder="Username" class="form-control">
                                    </div>
                                    <div class="col-lg-12 mt-2" align="left">
                                        <label><b>Password</b></label>
                                        <input type="password" name="password" id="password" placeholder="Password" class="form-control">
                                    </div>
                                    <div class="col-lg-12 mt-2">
                                        <button class="btn btn-warning text-white w-100 poppins" name="btn-login"><b>Login</b></button>
                                    </div>
                                    <div class="col-lg-12 mt-2">
                                    <a href="javascript:void(0)" class="mb-1 btn btn-danger text-white w-100 poppins btn-lacak"><b>Kembali</b></a>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12 mt-2" id="menu-form">
                                <button class="mb-1 btn btn-warning text-white w-100 poppins"><b>MENU LAINNYA</b></button>
                                <a href="javascript:void(0)" class="mb-1 btn btn-info text-white w-100 poppins btn-pilihan-menu" data-card="card-saku-wbp"><b>SAKU WBP</b></a>
                                <div class="card card-fitur" id="card-saku-wbp">
                                    <div class="card-body text-center pt-2 pb-2" style="font-size:9pt;">
                                        Gunakan fitur ini untuk melihat daftar penyimpanan uang dan rincian keluar masuk uang WBP<br>
                                        <?php
                                        if(empty($this->id_keluarga)){
                                            ?>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-success mt-2" onclick="open_login(this)" data-redirect="saku_wbp">PILIH</a>
                                            <?php
                                        }else{
                                            ?>
                                            <a href="<?= base_url("salu_wbp"); ?>" class="btn btn-sm btn-success mt-2"><i class="fas fa-eye pr-1"></i>LIHAT</a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <a href="javascript:void(0)" class="mb-1 btn btn-info text-white w-100 poppins btn-pilihan-menu" data-card="card-warung-sipirman"><b>WARUNG SIPIRMAN</b></a>
                                <div class="card card-fitur" id="card-warung-sipirman">
                                    <div class="card-body text-center pt-2 pb-2" style="font-size:9pt;">
                                        Lihat persediaan barang pada warung SIPIRMAN, belanjakan WBP dari rumah<br>
                                        <?php
                                        if(empty($this->id_keluarga)){
                                            ?>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-success mt-2" onclick="open_login(this)" data-redirect="warung_sipirman_ki">PILIH</a>
                                            <?php
                                        }else{
                                            ?>
                                            <a href="<?= base_url("warung_sipirman_ki"); ?>" class="btn btn-sm btn-success mt-2"><i class="fas fa-eye pr-1"></i>LIHAT</a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <a href="javascript:void(0)" class="mb-1 btn btn-info text-white w-100 poppins btn-pilihan-menu" data-card="card-loker-antiseptik"><b>LOKER ANTISEPTIK</b></a>
                                <div class="card card-fitur" id="card-loker-antiseptik">
                                    <div class="card-body text-center pt-2 pb-2" style="font-size:9pt;">
                                        Antiseptik titipan anda dikelola oleh Petugas Perawatan, Pantau rincian pendistribusiannya di sini<br>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-success mt-2">PILIH</a>
                                    </div>
                                </div>
                                <a href="javascript:void(0)" class="mb-1 btn btn-info text-white w-100 poppins btn-pilihan-menu" data-card="card-kotak-obat"><b>KOTAK OBAT</b></a>
                                <div class="card card-fitur" id="card-kotak-obat">
                                    <div class="card-body text-center pt-2 pb-2" style="font-size:9pt;">
                                        Obat-obatan titipan anda dikelola oleh Petugas Kesehatan, Pantau rincian penyerahannya disini<br>
                                        <a href="javascript:void(0)" class="btn btn-sm btn-success mt-2">PILIH</a>
                                    </div>
                                </div>
                                <a href="javascript:void(0)" class="mb-1 btn btn-info text-white w-100 poppins btn-dashboard"><b>DASHBOARD</b></a>
                                <a href="javascript:void(0)" class="mb-1 btn btn-info text-white w-100 poppins btn-login"><b>LOGIN</b></a>
                                <a href="javascript:void(0)" class="mb-1 btn btn-danger text-white w-100 poppins btn-lacak"><b>TUTUP MENU</b></a>
                            </div>
                            <div class="col-lg-12" id="tracking-form">
                                <div class="col-lg-12 mt-2" align="center">
                                    <label><b>Nomor Resi</b></label>
                                    <input type="text" name="kode-tracking" id="kode-tracking" placeholder="Masukkan nomor resi anda di sini..." class="form-control">
                                    
                                </div>
                                <div class="col-lg-12 mt-2">
                                    <a href="javascript:void(0)" class="btn btn-info text-white w-100 poppins" id="btn-proses-lacak"><b>Lacak</b></a>
                                </div>
                                <div class="col-lg-12 mt-1" align="center">
                                    <label class="mt-1"><b>atau</b></label>
                                </div>
                                <div class="col-lg-12 mt-1">
                                    <a href="javascript:void(0)" class="btn btn-warning text-white w-100 poppins btn-menu-lainnya"><b>MENU LAINNYA</b></a>
                                </div>
                            </div>
                            <div class="col-lg-12" align="center">
                                <h6 style="font-size: 11pt;" class="mt-3 mb-2">PENGUNJUNG SIPIRMAN</h5>
                            </div>
                            <div class="col-lg-12 mb-3 d-flex justify-content-center">
                                <table>
                                    <tr>
                                        <td><i class="fa fa-male pr-2 text-info"></i> Hari ini</td>
                                        <td width="70px" align="center"><?= $today->count; ?></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-male pr-2 text-primary"></i> Kemarin</td>
                                        <td width="70px" align="center"><?= $yesterday->count; ?></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-male pr-2 text-danger"></i> Minggu ini</td>
                                        <td width="70px" align="center"><?= $weekly->count; ?></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-male pr-2 text-success"></i> Bulan ini</td>
                                        <td width="70px" align="center"><?= $yearly->count; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 bg-img d-block d-lg-none">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="height:100%;">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" style="height:100%">
                        <div class="carousel-item active" style="height:100%">
                            <img class="d-block w-100" src="<?= base_url("assets/img/background.jpeg?v1"); ?>" style="height:100%;" alt="First slide">
                        </div>
                        <div class="carousel-item" style="height:100%">
                            <img class="d-block w-100" src="<?= base_url("assets/img/banner-saku.jpeg?v1"); ?>" alt="Second slide">
                        </div>
                        <div class="carousel-item" style="height:100%">
                            <img class="d-block w-100" src="<?= base_url("assets/img/banner-warung.jpg?v1"); ?>" alt="Third slide">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-sop" tabindex="-1" role="dialog" aria-labelledby="modal-sopLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" align="center">
                    <img src=<?= base_url("assets/img/logo-sipirman.jpg"); ?> width="100%" class="img-foto mb-1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-login-keluarga" tabindex="1" style="z-index:2000;" role="dialog" aria-labelledby="modal-login-keluargaLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="<?= base_url("login_keluarga") ?>" method="post">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <img src="<?= base_url("assets/img/logo-sipirman.jpeg"); ?>" width="90%">
                            </div>
                            <div class="col-lg-12">
                                <p class="m-0 mb-1">Username</p>
                                <input type="text" name="username_keluarga" id="username_keluarga" class="form-control" placeholder="Masukkan Username / NIK">
                            </div>
                            <div class="col-lg-12">
                                <p class="m-0 mb-1">Password</p>
                                <input type="password" name="password_keluarga" id="password_keluarga" class="form-control" placeholder="Masukkan Password / Telepon">
                                <input type="hidden" name="redirect" id="redirect" class="form-control">
                            </div>
                            <div class="col-lg-12 text-right">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0)" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Close</a>
                        <button class="btn btn-info">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="<?= base_url("assets/plugins/jquery/jquery.min.js") ?>"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="<?= base_url("assets/plugins/bootstrap/js/tether.min.js") ?>"></script>
<script src="<?= base_url("assets/plugins/bootstrap/js/bootstrap.min.js") ?>"></script>
<script>
    $("#modal-sop").modal("show");
</script>
<script>
    $("#menu-form").hide();
    $(".btn-menu-lainnya").on("click",function() {
        $("#login-form-dashboard").hide(400);
        $("#login-form").hide(200);
        $("#tracking-form").hide(200);
        $("#menu-form").show(400);
    });

    $(".btn-login").on("click",function() {
        $("#login-form-dashboard").hide(400);
        $("#login-form").show(400);
        $("#tracking-form").hide(200);
        $("#menu-form").hide(200);
    });

    $(".btn-dashboard").on("click",function() {
        $("#login-form-dashboard").show(400);
        $("#login-form").hide(400);
        $("#tracking-form").hide(200);
        $("#menu-form").hide(200);
    });

    $(".btn-lacak").on("click",function() {
        $("#login-form-dashboard").hide(400);
        $("#login-form").hide(200);
        $("#tracking-form").show(400);
        $("#menu-form").hide(200);
    });

    $(".card-fitur").hide();
    $(".btn-pilihan-menu").click(function() {
        card = $(this).attr("data-card");
        $(".card-fitur").hide();
        $("#"+card).show(200);
    })

    $("#btn-proses-lacak").on("click",function() {
        kode_tracking = $("#kode-tracking").val();
        $.ajax({
            type: "get",
            url: "<?= base_url("get_tracking"); ?>",
            data: {
                resi: kode_tracking,
            },
            dataType: 'JSON',
            success:function(result) {
                var data = JSON.parse(JSON.stringify(result));
                if(result.status == "sukses"){
                    swal.fire({
                        html: result.list_view,
                        customClass: {
                            popup: 'p-0',
                            content: 'p-0 grayBox',
                            actions: 'mt-0 grayBox pb-3',
                        }
                    });
                }else{
                    swal.fire("UPSSS...","Nomor resi yang anda masukan tidak terdaftar.","error");
                }
            }
        })
    });

    function open_login(data) {
        redirect = data.dataset.redirect;
        $("#modal-login-keluarga").modal("show");
        $("#redirect").val(redirect);
    }

    <?php
        if(!empty($this->id_keluarga)){
            ?>
            console.log("OK");
            $("#login-form").hide(200);
            $("#tracking-form").hide(200);
            $("#menu-form").show(400);
            <?php
        }
    ?>
</script>
<?php 
if(!empty($this->session->flashdata("swal"))){
    echo $this->session->flashdata("swal");
}
if(!empty($this->session->flashdata("login-form"))){
    echo $this->session->flashdata("login-form");
}
if(!empty($this->session->flashdata("login-form-dashboard"))){
    echo $this->session->flashdata("login-form-dashboard");
}
?>
