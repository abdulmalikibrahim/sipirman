<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    } );
    
    $("#foto_bukti").click(function() {
        $("#bukti").trigger("click");
    });

    $("#bukti").change(function(e) {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("bukti").files[0]);

        oFReader.onload = function (oFREvent) {
                document.getElementById("foto_bukti").src = oFREvent.target.result;
        };
    });

    function confirm(data) {
        id_belanja = data.dataset.id;
        Swal.fire({
            title: 'Konfirmasi',
            input: 'textarea',
            inputPlaceholder: "Masukkan alasan tidak setuju, Kosongkan saja jika menyetujui pembelian",
            showConfirmButton:true,
            showDenyButton:true,
            confirmButtonText:'Setuju, Lanjut ke Kasir',
            denyButtonText:'Tidak Setuju',
            showCancelButton:true,
        }).then(function(result) {
            if(result.isConfirmed){
                $.ajax({
                    type:"post",
                    url:"<?= base_url("proses_kasir/accept"); ?>",
                    data:{
                        id_belanja:id_belanja,
                    },
                    dataType:"JSON",
                    beforeSend:function() {
                        loading_page("Sedang Menyimpan...","Mohon tunggu sebentar, sistem sedang menyimpan data");
                    },
                    success:function(r) {
                        d = JSON.parse(JSON.stringify(r));
                        if(d.status == 200){
                            $("#row-"+id_belanja).remove();
                        }
                        swal.fire(d.title,d.res,d.icon);
                    },
                    error:function(a,b,c) {
                        swal.fire("Error",a.responseText,"error");
                    }
                });
            }else if(result.isDenied){
                alasan_tolak = $(".swal2-textarea").val();
                console.log(result);
                $.ajax({
                    type:"post",
                    url:"<?= base_url("proses_kasir/deny"); ?>",
                    data:{
                        id_belanja:id_belanja,
                        alasan_discard:alasan_tolak,
                    },
                    dataType:"JSON",
                    beforeSend:function() {
                        loading_page("Sedang Menyimpan...","Mohon tunggu sebentar, sistem sedang menyimpan data");
                    },
                    success:function(r) {
                        d = JSON.parse(JSON.stringify(r));
                        if(d.status == 200){
                            $("#row-"+id_belanja).remove();
                        }
                        swal.fire(d.title,d.res,d.icon);
                    },
                    error:function(a,b,c) {
                        swal.fire("Error",a.responseText,"error");
                    }
                });
            }
        });
    }

    function uploadfoto(data) {
        id_belanja = data.dataset.id;
        $("#uploadfoto").modal("show");
        $("#btn-selesai").attr("data-id",id_belanja);
    }

    function proses_selesai(data) {
        var formData = new FormData();
        formData.append('id_belanja', data.dataset.id);
        formData.append('bukti', $("#bukti")[0].files[0]);
        $.ajax({
                enctype: 'multipart/form-data',
                type:"post",
                url:"<?= base_url("konfirmasi_belanja_selesai") ?>",
                data:formData,
                dataType:"JSON",
                processData: false,
                contentType: false,
                beforeSend:function() {
                    loading_page('Meyimpan...','Mohon tunggu, sedang update data');
                },
                success:function(r) {
                    d = JSON.parse(JSON.stringify(r));
                    if(d.status == 200){
                            swal.fire({
                                title:d.title,
                                html:d.res,
                                icon:d.icon,
                            }).then((result) => {
                                if(result.isConfirmed){
                                    location.reload();
                                }
                            });
                    }else{
                            swal.fire(d.title,d.res,d.icon);
                    }
                },
                error:function(a,b,c) {
                    swal.fire("Error",a.responseText,"error");
                }
        })
    }
</script>