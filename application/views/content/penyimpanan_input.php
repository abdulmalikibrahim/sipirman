<?php 
if(empty($edit)){
    $nama_pengirim = "";
    $nama_tahanan = "";
    $hubungan = "";
}else{
    $nama_pengirim = $edit->nik;
    $nama_tahanan = $edit->nama_tahanan;
    $hubungan = $edit->hubungan;
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-4">
                <form action="<?= base_url("penyimpanan_save") ?>" method="post">
                    <div class="row mb-3">
                        <div class="col-lg-4 d-none d-lg-block m-auto">Nama Penitip</div>
                        <div class="col-lg-8">
                            <div class="input-group">
                                <datalist id="nama-penitip">
                                    <?php 
                                    $all_penitip = $this->admin_model->get_data_select("penitip","nik,nama","id != '' ORDER BY nama ASC","result");
                                    foreach ($all_penitip as $penitip) {
                                        ?>
                                        <option value="<?= $penitip->nik ?>"><?= $penitip->nama; ?></option>
                                        <?php
                                    }
                                    ?>
                                </datalist>
                                <input list="nama-penitip" type="text" class="form-control" name="nama_pengirim" id="nama_pengirim" value="<?= $nama_pengirim; ?>" placeholder="Cari Nama Penitip / NIK" required>
                                <a href="javascript:void(0)" class="btn btn-info ml-2" data-toggle="modal" data-target="#exampleModal" title="Tambah Penitip"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header pt-2 pb-2" align="center"><h4>Data Penitip</h4></div>
                                <div class="card-body p-2">
                                    <div class="row" id="d-data-diri">
                                        <div class="col-lg-4">
                                            <div class="row">
                                                <input type="hidden" name="txt_nama_pengirim" id="txt_nama_pengirim">
                                                <input type="hidden" name="txt-email" id="txt-email">
                                                <div class="col-lg-12 font-bold">NIK</div>
                                                <div class="col-lg-12" id="d-nik">-</div>
                                                <div class="col-lg-12 font-bold">Nama</div>
                                                <div class="col-lg-12" id="d-nama">-</div>
                                                <div class="col-lg-12 font-bold">Email</div>
                                                <div class="col-lg-12" id="d-email">-</div>
                                                <div class="col-lg-12 font-bold">HP</div>
                                                <div class="col-lg-12" id="d-hp">-</div>
                                                <div class="col-lg-12 font-bold">Hubungan</div>
                                                <div class="col-lg-12" id="d-hub">
                                                    <select id="hubungan" name="hubungan" class="form-control mb-2" required="required">
                                                        <option value="">Hubungan</option>
                                                        <?php 
                                                        $thubungan = $this->admin_model->get_data_select("hubungan","hubungan","id !=","result");
                                                        foreach ($thubungan as $thubungan) {
                                                            if($thubungan->hubungan == $hubungan){
                                                                $selected = "selected";
                                                            }else{
                                                                $selected = "";
                                                            }
                                                            ?>
                                                            <option <?=$selected?>><?= $thubungan->hubungan; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-12 font-bold">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="Ya" id="keluarga_inti" name="keluarga_inti">
                                                        <label class="form-check-label" for="keluarga_inti">
                                                            Keluarga Inti
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4" align="center">
                                            <h4 class="mt-2">Foto Diri</h4>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png" id="img-foto" class="img-foto" width="100%">
                                        </div>
                                        <div class="col-lg-4" align="center">
                                            <h4 class="mt-2">Foto KTP</h4>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png" id="img-ktp" class="img-ktp" width="100%">
                                        </div>
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-4" align="center">
                                            <h4 class="mt-2">*Foto KK</h4>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png" id="img-kk" class="img-kk" width="100%">
                                        </div>
                                        <div class="col-lg-4" align="center">
                                            <h4 class="mt-2">*Surat Pernyataan</h4>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png" id="img-super" class="img-super" width="100%">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12" id="loader-data-diri" align="center" style="display: none;">
                                            <img src="<?= base_url("assets/img/Wedges.svg") ?>" alt=""><br>Mengambil Data....
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4 d-none d-lg-block m-auto">WBP</div>
                        <div class="col-lg-8">
                            <datalist id="nama-tahanan">
                                <?php 
                                $all_tahanan = $this->admin_model->get_data_select("tahanan","*","code_napi !=","result");
                                foreach ($all_tahanan as $tahanan) {
                                    ?>
                                    <option value="<?= $tahanan->code_napi ?>"><?= $tahanan->nama; ?></option>
                                    <?php
                                }
                                ?>
                            </datalist>
                            <input list="nama-tahanan" type="text" class="form-control" name="code_tahanan" id="code_tahanan" placeholder="Ketik dengan Huruf Balok" value="<?= $nama_tahanan; ?>" required>
                            <input type="text" name="nama_tahanan" id="nama_tahanan" class="form-control" required hidden>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header pt-2 pb-2" align="center"><h4>Detail WBP Penerima</h4></div>
                                <div class="card-body p-2">
                                    <div class="row" id="d-tahanan">
                                        <div class="col-lg-4" align="center">
                                            <h4 class="mt-2">Foto WBP</h4>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg" id="img-fopi" width="100%">
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <div class="col-lg-12 font-bold">Nama Tahanan</div>
                                                <div class="col-lg-12" id="d-nama-tahanan">-</div>
                                                <div class="col-lg-12 font-bold">Nama Ayaha</div>
                                                <div class="col-lg-12" id="d-nama-ayah">-</div>
                                                <div class="col-lg-12 font-bold">Jenis Kelamin</div>
                                                <div class="col-lg-12" id="d-jk">-</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12" id="loader-data-diri" align="center" style="display: none;">
                                            <img src="<?= base_url("assets/img/Wedges.svg") ?>" alt=""><br>Mengambil Data....
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    if(!empty($edit)){
                        $no = 1;
                        $barang_titipan = json_decode($edit->data_barang);
                        foreach ($barang_titipan as $key => $value) {
                            if($no == "1"){
                                $button = '<a href="javascript:void(0)" class="btn bg-light-extra ml-2" id="add-barang" data-toggle="tooltip" title="Tambah Barang"><i class="fas fa-plus"></i></a>';
                            }else{
                                $button = '<a href="javascript:void(0)" onclick="remove_barang('.$no.')" class="btn bg-light-extra ml-2"><i class="fas fa-minus text-danger"></i></a>';
                            }
                            ?>
                            <div class="row mb-3" id="add-barang-<?= $no; ?>">
                                <div class="col-lg-4 d-none d-lg-block m-auto"></div>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control w-50" value="<?= $key; ?>" name="nama_barang[]" placeholder="Nama Barang" required>
                                        <input type="text" class="form-control" value="<?= $value; ?>"  name="satuan_barang[]" placeholder="Satuan" required>
                                        <?= $button; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $no++;
                        }
                    }else{
                        ?>
                        <div class="row mb-3">
                            <div class="col-lg-4 d-none d-lg-block m-auto">Titipan Uang</div>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <input type="text" class="form-control w-50 harga" name="uang" placeholder="Jumlah titipan uang">
                                    <input type="text" class="form-control" placeholder="Rupiah" readonly>
                                    <a href="javascript:void(0)" class="btn ml-2" style="background-color:white;"><i class="fas fa-plus" style="color:white;"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4 d-none d-lg-block m-auto">Titipan Antispetik</div>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" style="width:30%;" name="antiseptik[]" placeholder="Nama Antiseptik">
                                    <input type="text" class="form-control harga" style="width:5%;" name="jumlah_antiseptik[]" placeholder="Jumlah">
                                    <input type="text" class="form-control" style="width:6%;" name="satuan_antiseptik[]" placeholder="Satuan">
                                    <a href="javascript:void(0)" class="btn bg-light-extra ml-2" id="add-antiseptik" data-toggle="tooltip" title="Tambah Antiseptik"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div id="content-tambah-antiseptik"></div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-4 d-none d-lg-block m-auto">Titipan Obat</div>
                            <div class="col-lg-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" style="width:30%;" name="obat[]" placeholder="Nama Obat">
                                    <input type="text" class="form-control harga" style="width:5%;" name="jumlah_obat[]" placeholder="Jumlah">
                                    <input type="text" class="form-control" style="width:6%;" name="satuan_obat[]" placeholder="Satuan">
                                    <a href="javascript:void(0)" class="btn bg-light-extra ml-2" id="add-obat" data-toggle="tooltip" title="Tambah Obat"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div id="content-tambah-obat"></div>
                            </div>
                            <div class="col-lg-12"><span class="text-small text-danger">* Kosongkan jika tidak ada titipan</span></div>
                        </div>
                        <?php
                    }
                    ?>
                    <div align="right">
                        <a href="<?= base_url("penyimpanan"); ?>" class="btn btn-danger" name="btn-simpan">Kembali</a>
                        <button class="btn btn-info" name="btn-simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penitip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url("t_penitip") ?>" method="post" enctype="multipart/form-data">
                    <input type="text" id="nama" name="nama" class="form-control mb-2" placeholder="Nama Penitip">
                    <input type="number" id="nik" name="nik" class="form-control mb-2" required="required" placeholder="NIK">
                    <input type="number" id="hp" name="hp" class="form-control mb-2" required="required" placeholder="No HP">
                    <div class="row">
                        <div class="col-lg-6 mb-2">
                            <div align="center"><h5>Foto Penitip</h5></div>
                            <div class="custom-file custom-my-file" id="foto">
                                <input type="file" class="custom-file-input custom-my-file" name="foto" style="position: absolute; top: 0px; border-radius: 10px; z-index: 1;" id="customFileFoto" required="required">

                                <img src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg" width="100%" id="img-foto" class="img-foto" style="position: absolute; top: 0px; border-radius: 10px; z-index: 0;">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div align="center"><h5>Foto KTP</h5></div>
                            <div class="custom-file custom-my-file" id="foto_ktp">
                                <input type="file" class="custom-file-input custom-my-file" name="foto_ktp" required="required" style="position: absolute; top: 0px; border-radius: 10px; z-index: 1;" id="customFileKTP">

                                <img src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg" width="100%" id="img-ktp" class="img-ktp" style="position: absolute; top: 0px; border-radius: 10px; z-index: 0;">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div align="center"><h5>Foto KK (Optional)</h5></div>
                            <div class="custom-file custom-my-file" id="foto_kk">
                                <input type="file" class="custom-file-input custom-my-file" name="foto_kk" style="position: absolute; top: 0px; border-radius: 10px; z-index: 1;" id="customFileKK">

                                <img src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg" width="100%" id="img-kk" class="img-kk" style="position: absolute; top: 0px; border-radius: 10px; z-index: 0;">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-2">
                            <div align="center"><h5>Lain-Lain</h5></div>
                            <div class="custom-file custom-my-file" id="super">
                                <input type="file" class="custom-file-input custom-my-file" name="super" style="position: absolute; top: 0px; border-radius: 10px; z-index: 1;" id="customFileSuper">

                                <img src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg" width="100%" id="img-super" class="img-super" style="position: absolute; top: 0px; border-radius: 10px; z-index: 0;">
                            </div>
                        </div>
                    </div>
                    <div class="mt-2" align="right">
                        <a href="javascript:void(0)" class="btn btn-secondary" data-dismiss="modal">Tutup</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>