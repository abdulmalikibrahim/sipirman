<?php $is_saku = ($this->session->userdata('level') === 'Saku'); ?>

<form action="<?= base_url($is_saku ? "saku_set_tanggal" : "settgl") ?>" method="post" id="form_filter" class="mb-3">
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

            <?php if(!$is_saku): ?>
            <!-- Card: Jumlah Barang Titipan Masuk (Super Admin only) -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-4 bg-info text-center pt-3">
                                <i class="fas fa-dolly text-light icon"></i>
                            </div>
                            <div class="col-lg-9 col-8 pt-3 pl-3 pr-3 pb-1">
                                <div class="row">
                                    <div class="col-lg-12"><h1 class="poppins"><?= number_format(count($total_titipan),0,"","."); ?> Brg</h1></div>
                                    <div class="col-lg-12">
                                        <label class="tag-label">Jumlah Barang Titipan Masuk</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Card: Jumlah Uang Masuk (Super Admin & Saku) -->
            <div class="<?= $is_saku ? 'col-lg-6' : 'col-lg-4' ?>">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-4 bg-info text-center pt-3">
                                <i class="fas fa-sack-dollar text-light icon"></i>
                            </div>
                            <div class="col-lg-9 col-8 pt-3 pl-3 pr-3 pb-1">
                                <div class="row">
                                    <?php
                                    $nominal_masuk = !empty($total_uang_masuk->total) ? $total_uang_masuk->total : 0;
                                    ?>
                                    <div class="col-lg-12"><h1 class="poppins">Rp. <?= number_format($nominal_masuk,0,"","."); ?></h1></div>
                                    <div class="col-lg-12">
                                        <label class="tag-label">Jumlah Uang Masuk</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Jumlah Uang Keluar (Super Admin & Saku) -->
            <div class="<?= $is_saku ? 'col-lg-6' : 'col-lg-4' ?>">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-4 bg-info text-center pt-3">
                                <i class="fas fa-hand-holding-dollar text-light icon"></i>
                            </div>
                            <div class="col-lg-9 col-8 pt-3 pl-3 pr-3 pb-1">
                                <div class="row">
                                    <?php
                                    $nominal_keluar = !empty($total_uang_keluar->total) ? $total_uang_keluar->total : 0;
                                    ?>
                                    <div class="col-lg-12"><h1 class="poppins">Rp. <?= number_format($nominal_keluar,0,"","."); ?></h1></div>
                                    <div class="col-lg-12">
                                        <label class="tag-label">Jumlah Uang Keluar</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(!$is_saku): ?>
            <!-- Card: Jumlah Titipan Antiseptik (Super Admin only) -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-4 bg-info text-center pt-3">
                                <i class="fas fa-kit-medical text-light icon"></i>
                            </div>
                            <div class="col-lg-9 col-8 pt-3 pl-3 pr-3 pb-1">
                                <div class="row">
                                    <div class="col-lg-12"><h1 class="poppins"><?= number_format($total_antiseptik->count,0,"","."); ?></h1></div>
                                    <div class="col-lg-12">
                                        <label class="tag-label">Jumlah Titipan Antiseptik</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Jumlah Titipan Obat (Super Admin only) -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-4 bg-info text-center pt-3">
                                <i class="fas fa-capsules text-light icon"></i>
                            </div>
                            <div class="col-lg-9 col-8 pt-3 pl-3 pr-3 pb-1">
                                <div class="row">
                                    <div class="col-lg-12"><h1 class="poppins"><?= number_format($total_obat->count,0,"","."); ?></h1></div>
                                    <div class="col-lg-12">
                                        <label class="tag-label">Jumlah Titipan Obat</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php if($is_saku): ?>
<!-- ============================================================ -->
<!-- FORM INPUT UANG MASUK — hanya tampil untuk level Saku        -->
<!-- ============================================================ -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-wallet pr-2"></i>Input Uang Masuk WBP</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('saku_input_uang') ?>" method="post" id="form-input-saku">
                    <div class="row">

                        <!-- Kode WBP -->
                        <div class="col-lg-4 col-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Kode WBP <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="kode_tahanan"
                                       id="kode_tahanan"
                                       class="form-control"
                                       placeholder="Masukkan kode WBP"
                                       required
                                       autocomplete="off">
                                <small id="nama-wbp-preview" class="font-weight-bold mt-1 d-block"></small>
                            </div>
                        </div>

                        <!-- Nama Pengirim -->
                        <div class="col-lg-4 col-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Pengirim <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="nama_pengirim"
                                       class="form-control"
                                       placeholder="Nama lengkap pengirim"
                                       required>
                            </div>
                        </div>

                        <!-- Jumlah Uang -->
                        <div class="col-lg-4 col-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Jumlah Uang <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text"
                                           name="jumlah_uang"
                                           id="jumlah_uang"
                                           class="form-control"
                                           placeholder="0"
                                           required
                                           autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <!-- NIK Pengirim -->
                        <div class="col-lg-4 col-12">
                            <div class="form-group">
                                <label class="font-weight-bold">NIK Pengirim</label>
                                <input type="text"
                                       name="nik"
                                       class="form-control"
                                       placeholder="NIK pengirim (opsional)"
                                       maxlength="16"
                                       oninput="this.value=this.value.replace(/\D/g,'')">
                            </div>
                        </div>

                        <!-- Hubungan -->
                        <div class="col-lg-4 col-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Hubungan dengan WBP</label>
                                <select name="hubungan" class="form-control">
                                    <option value="">-- Pilih Hubungan --</option>
                                    <option value="Orang Tua">Orang Tua</option>
                                    <option value="Suami/Istri">Suami/Istri</option>
                                    <option value="Anak">Anak</option>
                                    <option value="Saudara">Saudara</option>
                                    <option value="Kerabat">Kerabat</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pesan -->
                        <div class="col-lg-4 col-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Pesan untuk WBP</label>
                                <textarea name="pesan_penitip"
                                          class="form-control"
                                          rows="1"
                                          placeholder="Pesan dari pengirim (opsional)"></textarea>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="col-lg-12" align="right">
                            <button type="reset" class="btn btn-secondary mr-2">
                                <i class="fas fa-undo pr-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-success" name="btn-simpan" id="btn-simpan-saku">
                                <i class="fas fa-save pr-1"></i> Simpan
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Format angka rupiah saat ketik
document.getElementById('jumlah_uang').addEventListener('input', function(){
    let val = this.value.replace(/\D/g, '');
    this.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
});

// Preview nama WBP saat field kode kehilangan fokus (blur)
document.getElementById('kode_tahanan').addEventListener('blur', function(){
    const kode  = this.value.trim();
    const preview = document.getElementById('nama-wbp-preview');
    if (!kode) { preview.innerText = ''; return; }

    $.ajax({
        url  : '<?= base_url("get_narapidana_new") ?>',
        type : 'POST',
        data : { code_napi: kode },
        success: function(res){
            try {
                const d = (typeof res === 'string') ? JSON.parse(res) : res;
                if (d.nama_tahanan && d.nama_tahanan !== '-') {
                    preview.innerText   = '✓ ' + d.nama_tahanan;
                    preview.className   = 'text-success font-weight-bold mt-1 d-block';
                } else {
                    preview.innerText   = '✗ Kode WBP tidak ditemukan';
                    preview.className   = 'text-danger font-weight-bold mt-1 d-block';
                }
            } catch(e) { preview.innerText = ''; }
        },
        error: function(){ preview.innerText = ''; }
    });
});

// Juga trigger saat tekan Enter di field kode
document.getElementById('kode_tahanan').addEventListener('keypress', function(e){
    if (e.which === 13) { e.preventDefault(); this.blur(); }
});

// Cegah double-submit
document.getElementById('form-input-saku').addEventListener('submit', function(e){
    const preview = document.getElementById('nama-wbp-preview');
    if (preview.classList.contains('text-danger')) {
        e.preventDefault();
        alert('Kode WBP tidak valid. Periksa kembali kode yang dimasukkan.');
        return;
    }
    const btn = document.getElementById('btn-simpan-saku');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin pr-1"></i> Menyimpan...';
});

// Auto-submit filter tanggal saat tanggal berubah
document.getElementById('tanggal').addEventListener('change', function(){
    document.getElementById('form_filter').submit();
});
document.getElementById('tanggal_1').addEventListener('change', function(){
    document.getElementById('form_filter').submit();
});
</script>
<?php endif; ?>