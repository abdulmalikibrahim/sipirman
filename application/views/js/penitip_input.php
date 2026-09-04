<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
function readURLFoto(input) {
    if (input.files && input.files[0]) {
        var reader_foto = new FileReader();
        reader_foto.onload = function (e) {
            $('#img-foto').attr('src', e.target.result);
        }
        reader_foto.readAsDataURL(input.files[0]);
    }
}

function readURLKTP(input) {
    if (input.files && input.files[0]) {
        var reader_ktp = new FileReader();
        reader_ktp.onload = function (e) {
            $('#img-ktp').attr('src', e.target.result);
        }
        reader_ktp.readAsDataURL(input.files[0]);
    }
}

function readURLKK(input) {
    if (input.files && input.files[0]) {
        var reader_kk = new FileReader();
        reader_kk.onload = function (e) {
            $('#img-kk').attr('src', e.target.result);
        }
        reader_kk.readAsDataURL(input.files[0]);
    }
}

function readURLSuper(input) {
    if (input.files && input.files[0]) {
        var reader_super = new FileReader();
        reader_super.onload = function (e) {
            $('#img-super').attr('src', e.target.result);
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


var counter = ($(".nama_wbp").length)+1;
$("#add-wbp").click(function() {
    if(counter>5){
        swal.fire("","Maaf, data tambah wbp sudah melebihi batas","info");
    }else{
        $("#content-tambah-wbp").append('<div class="row mt-2" id="add-wbp-'+counter+'"><div class="col-lg-12"><div class="input-group"><input type="text" list="nama-tahanan" id="nama_wbp_'+counter+'" name="nama_wbp[]" class="form-control nama_wbp" value=""  placeholder="Ketik dengan huruf balok"><a href="javascript:void(0)" onclick="remove_wbp('+counter+')" class="btn bg-light-extra ml-2"><i class="fas fa-minus text-danger"></i></a></div></div></div>');
        $("#nama_wbp_"+counter).focus();
        counter++;
    }
});
function remove_wbp(params) {
    $("#add-wbp-"+params).remove();
    counter--;
}
</script>