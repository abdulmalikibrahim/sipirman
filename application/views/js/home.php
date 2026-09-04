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
    $("#customFileFoto").on("change", function() {
        readURLFoto(this);
    });

</script>
<script>
    $("#no-resi").on("keyup",function () {
        if($("#no-resi").val() !== ""){
            $.ajax({
                type: "get",
                url: "<?= base_url("getresi") ?>",
                data: {
                    resi: $(this).val(),
                    akses: $("#akses-mode").val(),
                },
                dataType: "JSON",
                beforeSend: function () {
                    $("#loader-checklist-titipan").show();
                    $("#checklist-data-titipan").hide();
                },
                success: function(result) {
                    data = JSON.parse(JSON.stringify(result));
                    $("#loader-checklist-titipan").hide();
                    $("#checklist-data-titipan").show();
                    if(result.status === "sukses"){
                        if(data.diterima_komandan == null){
                            $("#col-foto-pesan").hide();
                            $("#col-button").addClass("d-flex justify-content-start");
                            $("#form-update-status").attr("onsubmit","return validateMyForm('p2u');");
                        }else{
                            $("#col-foto-pesan").show();
                            $("#col-button").removeClass("d-flex justify-content-start");
                            $("#form-update-status").attr("onsubmit","return validateMyForm('selesai_antar');");
                            $("#btn-update").html('<i class="fas fa-send pr-2"></i>Selesai Antar');
                        }
                        $("#checklist-data-titipan").html(data.checklist);
                        $("#pesan_penitip").html(data.pesan_penitip);

                        // MODIFIKASI: Kosongkan text area keterangan komandan agar tidak terbawa dari pencarian resi sebelumnya
                        if($("#keterangan").length) {
                            $("#keterangan").val("");
                        }

                        console.log(result);
                    }else if(result.status === "sudah diterima komandan"){
                        swal.fire("Warning","Data Titipan sudah diterima komandan jaga","warning");
                    }else if(result.status === "selesai antar"){
                        swal.fire("Warning","Data Titipan sudah diterima WBP","warning");
                    }else{
                        swal.fire("Error","Data Titipan tidak ditemukan","error");
                        $("#loader-checklist-titipan").hide();
                    }
                    console.log(result.status)
                }
            })
        }else{
            $("#loader-checklist-titipan").hide();
        }
    });

    $("#tanggal, #tanggal_1").change(function() {
        $("#form_filter").submit()
    });

    function tolak(id) {
        $("#"+id).removeClass("d-none");
        $("#cb"+id).attr("onclick","setuju('"+id+"')");
        $("#"+id).focus();
        $("#"+id).attr("required",true);
    }

    function setuju(id) {
        $("#"+id).addClass("d-none");
        $("#cb"+id).attr("onclick","tolak('"+id+"')");
        $("#"+id).attr("required",false);
    }
</script>