<form action="<?= base_url("settgl") ?>" method="post" id="form_filter" class="mb-3">
    <div class="form-group">
        <label>Filter Date :</label>
        <div class="row">
            <div class="col-lg-5 col-12">
                <div class="row">
                    <div class="col-lg-5 col-5 p-0">
                        <input type="date" name="tanggal_1" id="tanggal_1" value="<?= $this->tanggal_1 ?>" class="form-control">
                    </div>
                    <div class="col-lg-1 col-2 p-0 pt-2" align="center">s/d</div>
                    <div class="col-lg-5 col-5 pl-0">
                        <input type="date" name="tanggal" id="tanggal" value="<?= $this->tanggal ?>" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3">
                <a href="<?=base_url("cstatus?s=Menunggu")?>">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-4 bg-info pt-lg-3 pt-4 pb-3 pl-3">
                                    <i class="fas fa-clock-o text-light icon"></i>
                                </div>
                                <div class="col-lg-9 col-8 pt-3 pl-3 pb-1">
                                    <div class="row">
                                        <div class="col-lg-12"><h1 class="poppins"><?= count($menunggu); ?> Brg</h1></div>
                                        <div class="col-lg-12">
                                            <label class="tag-label">Masih Di Pos Pendaftaran</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="<?=base_url("cstatus?s=Diterima P2U")?>">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-4 bg-info pt-lg-3 pt-4 pb-3 pl-2">
                                    <i class="fas fa-dolly text-light icon"></i>
                                </div>
                                <div class="col-lg-9 col-8 pt-3 pl-3 pr-3 pb-1">
                                    <div class="row">
                                        <div class="col-lg-12"><h1 class="poppins"><?= count($diterima_p2u); ?> Brg</h1></div>
                                        <div class="col-lg-12">
                                            <label class="tag-label">Diterima Pemeriksaan P2U</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="<?=base_url("cstatus?s=Diterima Komandan Jaga")?>">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-lg-3 col-4 bg-info pt-lg-3 pt-4 pb-3 pl-3">
                                    <i class="fas fa-user text-light icon"></i>
                                </div>
                                <div class="col-lg-9 col-8 pt-3 pl-3 pr-3 pb-1">
                                    <div class="row">
                                        <div class="col-lg-12"><h1 class="poppins"><?= count($diterima_komandan); ?> Brg</h1></div>
                                        <div class="col-lg-12">
                                            <label class="tag-label">Diterima Komandan Jaga</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="<?=base_url("cstatus?s=Selesai Diantar")?>">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-4 bg-info pt-lg-3 pt-4 pb-3 pl-3">
                                    <i class="fas fa-clipboard-check text-light icon"></i>
                                </div>
                                <div class="col-lg-9 col-8 pt-3 pl-3 pb-1">
                                    <div class="row">
                                        <div class="col-lg-12"><h1 class="poppins"><?= count($diterima_wbp); ?> Brg</h1></div>
                                        <div class="col-lg-12">
                                            <label class="tag-label">Tersampaikan WBP</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php 
// MODIFIKASI: Admin bisa melihat form aksi jika aksesnya 'p2u' atau 'komandan'
if($this->level != "Admin" || ($this->level == "Admin" && ($akses == "p2u" || $akses == "komandan"))){
    ?>
    <form action="" method="post" enctype="multipart/form-data" id="form-update-status">
        <!-- MODIFIKASI: Bawa mode akses Admin (p2u/komandan) ke JS agar ajax getresi tahu harus minta checklist yang mana -->
        <input type="hidden" id="akses-mode" value="<?= $akses ?>">
        <div class="row">
            <div class="col-lg-12" align="center"><h2>Update Status Penitipan</h2></div>
            <div class="col-lg-5" align="center">
                <datalist id="resi-number">
                    <?php
                    // MODIFIKASI: Penyesuaian hak akses untuk mengambil list no resi
                    if($this->level == "Tracer" || ($this->level == "Admin" && $akses == "p2u")){
                        $resi_number = $this->admin_model->get_data_select("data_titipan","resi, nama_tahanan","id != '' AND deleted_date IS NULL AND diterima_p2u IS NOT NULL AND diterima_komandan IS NULL","result");
                    }else{
                        $resi_number = $this->admin_model->get_data_select("data_titipan","resi, nama_tahanan","id != '' AND deleted_date IS NULL  AND diterima_komandan IS NOT NULL AND diterima_wbp IS NULL","result");
                    }
                    foreach ($resi_number as $resi_number) {
                        ?>
                        <option value="<?= $resi_number->resi ?>"><?= $resi_number->nama_tahanan; ?></option>
                        <?php
                    }
                    ?>
                </datalist>
                <input list="resi-number" type="text" name="no-resi" id="no-resi" class="form-control" style="font-size: 1.5em; text-align: center;" placeholder="Masukkan No Resi" autocomplete="off">
                <div class="row mt-2">
                    <?php 
                    // MODIFIKASI: Tampilkan pilihan komandan jika sedang dalam mode P2U
                    if($this->level == "Tracer" || ($this->level == "Admin" && $akses == "p2u")){
                        ?>
                        <div class="col-lg-12">
                            <select name="pkomandan" class="form-control" required>
                                <option value="">Pilih Komandan Penerima</option>
                                <?php 
                                $komandan = $this->admin_model->get_data_select("account","*","id != '' AND level = 'Komandan'","result");
                                foreach ($komandan as $komandan) {
                                    ?>
                                    <option value="<?=$komandan->name?>"><?=$komandan->name?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="col-lg-12" id="checklist-data-titipan">
                        </div>
                    <div class="col-lg-12" id="loader-checklist-titipan" align="center" style="display: none;">
                        <img src="<?= base_url("assets/img/Wedges.svg") ?>" alt="" width="40%"><br>Mengambil Data....
                    </div>
                </div>
            </div>
            <?php 
            // MODIFIKASI: Penentuan label tombol dan visibilitas elemen berdasarkan mode akses
            if($this->level == "Tracer" || ($this->level == "Admin" && $akses == "p2u")){
                $hidden = "hidden";
                $align = "left";
                $text_submit = "Antar Komandan";
            }else{
                $hidden = "";
                $align = "right";
                $text_submit = "Serahkan WBP";
            }
            ?>
            <div class="col-lg-12" id="col-foto-pesan">
                <div class="row">
                    <div class="col-lg-3 mb-2" align="center" <?= $hidden; ?>>
                        <div>
                            <h5 class="mt-2 mb-2">Foto Bukti Penerimaan Barang</h5>
                        </div>
                        <div class="custom-file custom-my-file" id="foto">
                            <input type="file" class="custom-file-input custom-my-file" name="foto" style="position: absolute; top: 0px; border-radius: 10px; z-index: 1;" id="customFileFoto">

                            <img src="https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg" id="img-foto" style="border-radius: 10px; z-index: 0; left: 0; width: 100%; height: 100%">
                            </br>
                            </br>
                            <label class="container-check" style="font-size: 0.7em">Ceklist jika WBP tidak bersedia fotonya dikirimkan<br>
                                <input type="checkbox" class="check-box" name="status_dokumentasi" value="Tidak Bersedia">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-5" <?= $hidden; ?>>
                        <div align="center"><h5 class="mt-2 mb-2">Pesan dari penitip</h5></div>
                        <p id="pesan_penitip"></p>
                    </div>
                    <div class="col-lg-4" <?= $hidden; ?>>
                        <div align="center"><h5 class="mt-2 mb-2">Pesan untuk penitip</h5></div>
                        <textarea name="pesan-tahanan" id="pesan-tahanan" class="form-control" cols="30" rows="10" placeholder="Balas pesan Penitip"></textarea>
                    </div>
                    
                    <?php 
                    // MODIFIKASI: Menampilkan textarea keterangan jika user adalah Komandan atau Admin dalam mode komandan
                    if($this->level == "Komandan" || ($this->level == "Admin" && $akses == "komandan")): ?>
                    <div class="col-lg-12 mt-3" <?= $hidden; ?>> 
                        <div align="center"><h5 class="mt-2 mb-2">Input Keterangan (isi jika ada barang yang ditolak)</h5></div>
                        <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3">Barang titipan tersampaikan lengkap</textarea>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
            <div class="col-lg-12 mt-2" align="<?= $align; ?>" id="col-button">
                <button class="btn btn-success" name="btn-update" id="btn-update"><i class="fas fa-send pr-2"></i><?= $text_submit; ?></button>
            </div>
        </div>
    </form>
    <?php
}
?>