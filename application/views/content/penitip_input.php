<?php 
if(empty($edit)){
    $nama = "";
    $nik = "";
    $hp = "";
    $nama_wbp = "";
	$jadwal_kunjungan = "";
	$keluarga_inti = "0";
	$pengikut = "";
    $foto = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png";
    $foto_ktp = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png";
    $foto_kk = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png";
    $foto_super = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png";
    $action_form = base_url("t_penitip?inp=y");
    $required_foto = "required";
}else{
    $nama = $edit->nama;
    $nik = $edit->nik;
    $hp = $edit->hp;
    $nama_wbp = $edit->nama_wbp;
	$jadwal_kunjungan = $edit->jadwal_kunjungan;
	$keluarga_inti = $edit->keluarga_inti;
	$pengikut = $edit->pengikut;
	if(!empty($edit->foto)){
        $foto = base_url("upload/foto_diri/".$edit->foto."?t=".time());
	}else{
        $foto = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png"; 
	}
	if(!empty($edit->foto_ktp)){
        $foto_ktp = base_url("upload/ktp/".$edit->foto_ktp."?t=".time());
	}else{
        $foto_ktp = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png";
	}
	if(!empty($edit->foto_kk)){
        $foto_kk = base_url("upload/foto_kk/".$edit->foto_kk."?t=".time());
	}else{
        $foto_kk = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png";
	}
	if(!empty($edit->foto_pernyataan)){
        $foto_super = base_url("upload/super/".$edit->foto_pernyataan."?t=".time());
	}else{
        $foto_super = "https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png";
	}
    $action_form = base_url("simpan_edit_penitip/".$this->uri->segment(2));
    $required_foto = "";
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-4">
                <form action="<?= $action_form ?>" method="post" enctype="multipart/form-data">
                    <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">Nama Penitip</div>
                        <div class="col-lg-9">
                            <input type="text" id="nama" name="nama" class="form-control mb-2" value="<?= $nama ?>" required="required" placeholder="Ketik dengan huruf balok">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">NIK</div>
                        <div class="col-lg-9">
                            <input type="number" id="nik" name="nik" class="form-control mb-2" value="<?= $nik ?>" placeholder="NIK 16 digit angka">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">No. HP</div>
                        <div class="col-lg-9">
                            <input type="text" id="hp" name="hp" class="form-control mb-2" value="<?= $hp ?>" required="required" placeholder="Jika tidak ada WA akhiri dengan (Non-WA). Jika tidak ada nomor tulis 0 (nol)">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">Nama WBP</div>
                        <div class="col-lg-9">
                            <datalist id="nama-tahanan">
                                <?php 
                                $all_tahanan = $this->admin_model->get_data_select("tahanan","*","code_napi !=","result");
                                foreach ($all_tahanan as $tahanan) {
                                    ?>
                                    <option value="<?= $tahanan->nama ?>"><?= $tahanan->code_napi; ?></option>
                                    <?php
                                }
                                ?>
                            </datalist>
                            <?php
                            if(!empty($nama_wbp)){
                                $nama_wbp = json_decode($nama_wbp,true);
                                if(is_array($nama_wbp)){
                                    $no = 1;
                                    foreach ($nama_wbp as $key => $value) {
                                        if($key <= 0){
                                            $button_action = '<a href="javascript:void(0)" class="btn bg-light-extra ml-2" id="add-wbp" data-toggle="tooltip" title="Tambah WBP"><i class="fas fa-plus"></i></a>';
                                        }else{
                                            $button_action = '<a href="javascript:void(0)" onclick="remove_wbp('.$no.')" class="btn bg-light-extra ml-2"><i class="fas fa-minus text-danger"></i></a>';
                                        }
                                        ?>
                                        <div class="input-group mb-2" id="add-wbp-<?= $no; ?>">
                                            <input type="text" list="nama-tahanan" id="nama_wbp_<?= $no; ?>" name="nama_wbp[]" class="form-control nama_wbp" value="<?= $value ?>"  placeholder="Ketik dengan huruf balok (optional)">
                                            <?= $button_action; ?>
                                        </div>
                                        <?php
                                        $no++;
                                    }
                                }else{
                                    ?>
                                    <div class="input-group mb-2">
                                        <input type="text" list="nama-tahanan" id="nama_wbp_1" name="nama_wbp[]" class="form-control nama_wbp" value="<?= $nama_wbp ?>"  placeholder="Ketik dengan huruf balok (optional)">
                                        <a href="javascript:void(0)" class="btn bg-light-extra ml-2" id="add-wbp" data-toggle="tooltip" title="Tambah WBP"><i class="fas fa-plus"></i></a>
                                    </div>
                                    <?php
                                }
                            }else{
                                ?>
                                <div class="input-group">
                                    <input type="text" list="nama-tahanan" id="nama_wbp_1" name="nama_wbp[]" class="form-control nama_wbp" placeholder="Ketik dengan huruf balok (optional)">
                                    <a href="javascript:void(0)" class="btn bg-light-extra ml-2" id="add-wbp" data-toggle="tooltip" title="Tambah WBP"><i class="fas fa-plus"></i></a>
                                </div>
                                <?php
                            }
                            ?>
                            <div id="content-tambah-wbp"></div>
                        </div>
                    </div>
					<!-- <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">Jadwal Kunjungan</div>
                        <div class="col-lg-9">
                            <input type="date" id="jadwal_kunjungan" name="jadwal_kunjungan" class="form-control mb-2" value="<?= $jadwal_kunjungan ?>"  placeholder="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">Pengikut</div>
                        <div class="col-lg-9">
                            <input type="text" id="pengikut" name="pengikut" class="form-control mb-2" value="<?= $pengikut ?>"  placeholder="Jika tidak ada pengikut, ketik (Boleh mengajak 1 orang keluarga lainnya)">
                        </div>
                    </div> -->
					<div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">Keluarga Inti</div>
                        <div class="col-lg-9">
                            <select class="form-control" name="keluarga_inti" required>
                                <option value="0" <?php if($keluarga_inti == "0"){ echo "selected"; } ?>>Tidak</option>
                                <option value="1" <?php if($keluarga_inti == "1"){ echo "selected"; } ?>>Ya</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 mb-2" style="min-height:200px; height: 400px;">
                            <div align="center"><h5>Foto Penitip</h5></div>
                            <div class="custom-file custom-my-file" id="foto">
                                <div onclick="warningKagatau()" 
                                style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;cursor:pointer;">
                                </div>
                                <img src="<?= $foto ?>" width="100%" id="img-super" style="position: absolute; top: 0px; border-radius: 10px; z-index: 0;">
                            </div>
                        </div>
                        <div class="col-lg-3 mb-2" style="min-height:200px; height: 400px;">
                            <div align="center"><h5>Foto KTP</h5></div>
                            <div class="custom-file custom-my-file" id="foto_ktp">
                                <div onclick="warningKagatau()" 
                                style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;cursor:pointer;">
                                </div>
                                <img src="<?= $foto_ktp ?>" width="100%" id="img-super" style="position: absolute; top: 0px; border-radius: 10px; z-index: 0;">
                            </div>
                        </div>
                        <div class="col-lg-3 mb-2" style="min-height:200px; height: 400px;">
                            <div align="center"><h5>Foto KK (keluarga inti)</h5></div>
                            <div class="custom-file custom-my-file" id="foto_kk">
                                <div onclick="warningKagatau()" 
                                style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;cursor:pointer;">
                                </div>
                                <img src="<?= $foto_kk ?>" width="100%" id="img-super" style="position: absolute; top: 0px; border-radius: 10px; z-index: 0;">
                            </div>
                        </div>
                        <div class="col-lg-3 mb-2" style="min-height:200px; height: 400px;">
                            <div align="center"><h5>Lain-Lain</h5></div>
                            <div class="custom-file custom-my-file" id="foto_pernyataan">
                                <div onclick="warningKagatau()" 
                                style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;cursor:pointer;">
                                </div>
                                <img src="<?= $foto_super ?>" width="100%" id="img-super" style="position: absolute; top: 0px; border-radius: 10px; z-index: 0;">
                            </div>
                        </div>
                    </div>
                    <div class="mt-2" align="right">
                        <a href="<?= base_url("penitip") ?>" class="btn btn-danger" data-dismiss="modal">Kembali</a>
                        <button type="submit" name="btn-simpan" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function warningKagatau()
{
    Swal.fire({
        title: 'Upload Dokumen Dinonaktifkan',
        html: `
            <div style="font-size:14px;">
                Upload dokumen pada SIPIRMAN sudah tidak digunakan.<br><br>
                Seluruh dokumen penitip dikelola melalui
                <b>APLIKASI KAGATAU</b>.
            </div>
        `,
        icon: 'warning',
        confirmButtonText: 'Tutup',
        width: '450px'
    });
}
</script>