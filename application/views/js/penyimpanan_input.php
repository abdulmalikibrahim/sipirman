<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    <?php
    if(!empty($edit)){
        $counter = 1;
        $data_barang = json_decode($edit->data_barang);
        foreach ($data_barang as $key => $value) {
            $counter++;
        }
        ?>
        $(document).ready(function(){
            get_penitip();
            get_data_napi();
        })
        <?php
    }else{
        $counter = 2;
    }
    ?>
    var counter = <?= $counter; ?>;
    $("#add-antiseptik").click(function() {
        if(counter>20){
            swal.fire("","Maaf, data tambah antiseptik sudah melebihi batas","info");
        }else{
            $("#content-tambah-antiseptik").append('<div class="row" id="add-antiseptik-'+counter+'"><div class="col-lg-4 d-none d-lg-block m-auto"></div><div class="col-lg-8 mt-2"><div class="input-group"><input type="text" class="form-control" style="width:30%;" name="antiseptik[]" id="nama_antiseptik_'+counter+'" placeholder="Nama Antiseptik" required><input type="text" class="form-control harga" style="width:5%;" name="jumlah_antiseptik[]" placeholder="Jumlah" required><input type="text" class="form-control" style="width:6%;" name="satuan_antiseptik[]" placeholder="Satuan" required><a href="javascript:void(0)" onclick="remove_antiseptik('+counter+')" class="btn bg-light-extra ml-2"><i class="fas fa-minus text-danger"></i></a></div></div></div>');
            $("#nama_antiseptik_"+counter).focus();
            counter++;
        }
    });
    function remove_antiseptik(params) {
        $("#add-antiseptik-"+params).remove();
        counter--;
    }

    $("#add-obat").click(function() {
        if(counter>20){
            swal.fire("","Maaf, data tambah obat sudah melebihi batas","info");
        }else{
            $("#content-tambah-obat").append('<div class="row" id="add-obat-'+counter+'"><div class="col-lg-4 d-none d-lg-block m-auto"></div><div class="col-lg-8 mt-2"><div class="input-group"><input type="text" class="form-control" style="width:30%;" name="obat[]" id="nama_obat_'+counter+'" placeholder="Nama obat" required><input type="text" class="form-control harga" style="width:5%;" name="jumlah_obat[]" placeholder="Jumlah" required><input type="text" class="form-control" style="width:6%;" name="satuan_obat[]" placeholder="Satuan" required><a href="javascript:void(0)" onclick="remove_obat('+counter+')" class="btn bg-light-extra ml-2"><i class="fas fa-minus text-danger"></i></a></div></div></div>');
            $("#nama_obat_"+counter).focus();
            counter++;
        }
    });
    function remove_obat(params) {
        $("#add-obat-"+params).remove();
        counter--;
    }

    $("#nama_pengirim").on("keyup",function() {
        get_penitip();
    })

    $("#code_tahanan").on("keyup",function() {
        get_data_napi();
    })

    $("#nama_pengirim").click(function() {
        $("#nama_pengirim").val("");
    })

    $("#code_tahanan").click(function() {
        $("#code_tahanan").val("");
    })
</script>
<script>
    function get_penitip() {
        $.ajax({
            type: "get",
            url: "<?= base_url("getpen"); ?>",
            data: {
                nik: $("#nama_pengirim").val(),
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#d-data-diri").hide();
                $("#loader-data-diri").show();
            },
            success:function(result) {
                $("#d-data-diri").show();
                $("#loader-data-diri").hide();
                var data = JSON.parse(JSON.stringify(result));
                $("#d-nik").html(result.nik);
                $("#d-nama").html(result.nama);
                $("#txt_nama_pengirim").val(result.nama);
                $("#txt-email").val(result.email);
                $("#d-email").html(result.email);
                $("#d-hp").html(result.hp);
                $("#d-hub").html(result.hubungan);
                $(".img-foto").attr("src",result.fodir);
                $(".img-ktp").attr("src",result.ktp);
                $(".img-kk").attr("src",result.kk);
                $(".img-super").attr("src",result.super);
            }
        })
    }

    function get_data_napi() {
        $.ajax({
            type: "post",
            url: "<?= base_url("getnapi_new"); ?>",
            data: {
                code_napi: $("#code_tahanan").val(),
            },
            dataType: 'JSON',
            beforeSend: function() {
                $("#loader-tahanan").show();
            },
            success:function(result) {
                var data = JSON.parse(JSON.stringify(result));
                $("#nama_tahanan").val(result.nama_tahanan);
                $("#d-nama-tahanan").html(result.nama_tahanan);
                $("#d-nama-ayah").html(result.nama_ayah);
                $("#d-jk").html(result.jenis_kelamin);
                $("#img-fopi").attr("src",result.fopi);
            }
        })
    }
</script>
<script>
    function readURLFoto(input) {
        if (input.files && input.files[0]) {
            var reader_foto = new FileReader();
            reader_foto.onload = function (e) {
                $('.img-foto').attr('src', e.target.result);
            }
            reader_foto.readAsDataURL(input.files[0]);
        }
    }

    function readURLKTP(input) {
        if (input.files && input.files[0]) {
            var reader_ktp = new FileReader();
            reader_ktp.onload = function (e) {
                $('.img-ktp').attr('src', e.target.result);
            }
            reader_ktp.readAsDataURL(input.files[0]);
        }
    }

    function readURLKK(input) {
        if (input.files && input.files[0]) {
            var reader_kk = new FileReader();
            reader_kk.onload = function (e) {
                $('.img-kk').attr('src', e.target.result);
            }
            reader_kk.readAsDataURL(input.files[0]);
        }
    }

    function readURLSuper(input) {
        if (input.files && input.files[0]) {
            var reader_super = new FileReader();
            reader_super.onload = function (e) {
                $('.img-super').attr('src', e.target.result);
            }
            reader_super.readAsDataURL(input.files[0]);
        }
    }
// Add the following code if you want the name of the file appear on select
$("#customFileFoto").on("change", function() {
    readURLFoto(this);
});
$("#customFileKTP").on("change", function() {
    readURLKTP(this);
});
$("#customFileKK").on("change", function() {
    readURLKK(this);
});
$("#customFileSuper").on("change", function() {
    readURLSuper(this);
});
</script>