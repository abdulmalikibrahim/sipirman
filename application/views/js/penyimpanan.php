<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    } );
    
    $("#tanggal").change(function() {
        $("#form_filter").submit()
    })
    
    $("#fstatus").change(function() {
        $("#ff_status").submit()
    })

    $(".item-min").click(function() {
        id = $(this).attr("data-id")
        status = $(this).attr("data-status")
        if(status == "full"){
            full_text(id)
            $(this).attr("data-status","min")
        }else{
            min_text(id)
            $(this).attr("data-status","full")
        }
    })

    $("#pp2u").change(function() {
        data_id = $("#btn-ok").attr("data-id")
        pic = "'"+$("#pp2u").val()+"'";
        $("#btn-ok").attr("onclick","deli_data("+data_id+","+pic+")")
    })
</script>
<script>
    function full_text(id) {
        $(".dot-"+id).hide()
        $("#cut-text-"+id).removeClass("cut-text");
    }
    function min_text(id) {
        $("#cut-text-"+id).addClass("cut-text");
        $(".dot-"+id).show()
    }
    function detail(id) {
        $.ajax({
            type: "get",
            url: "<?= base_url("detail"); ?>",
            data: {
                id: id,
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
                    console.log(result)
                }else{
                    swal.fire("Error","Kode Tracking Tidak Ditemukan.","error");
                }
            }
        })
    }
    function del_data(params) {
        swal.fire({
            title: "Hapus data ini?",
            icon: "question",
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type:"post",
                    url:"<?= base_url("delete"); ?>",
                    data:{
                        id: params
                    },
                    success:function(r) {
                        if(r = "success"){
                            swal.fire("Sukses Hapus","","success");
                            $("#row_"+params).remove();
                        }else if(r = "fail"){
                            swal.fire("Gagal Hapus","","error");
                        }
                        console.log(r);
                    }
                })
            }
        })
    }


    function btn_ok(id) {
        $("#btn-ok").attr("data-id",id)
    }

    function deli_data(params,pic) {
        pic = $("#pp2u").val()
        if(pic != ""){
            $.ajax({
                type:"post",
                url:"<?= base_url("deliv"); ?>",
                data:{
                    pic:pic,
                    id: params
                },
                dataType: 'JSON',
                success:function(result) {
                    var data = JSON.parse(JSON.stringify(result));
                    if(data.status == "Sukses"){
                        swal.fire("Sukses Rubah Status","","success");
                        $("#status_"+params).html("<button class='btn btn-sm btn-warning'>Masuk P2U<br>"+ data.diterima_p2u +"</button>");
                        $("#btn-opsi-"+params).hide();
                        $("#Modalp2u").modal("hide")
                    }else{
                        swal.fire("Gagal Rubah Status","","error");
                    }
                    console.log(result);
                }
            })
        }else{
            swal.fire("Informasi","Anda belum memilih petugas","warning")
        }
    }
</script>