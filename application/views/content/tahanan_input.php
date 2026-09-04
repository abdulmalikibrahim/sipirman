<?php 
if(empty($edit)){
    $code_napi = strtoupper(hash("crc32",date("YmdHis")));
    $nama_ayah = "";
    $nama = "";
	$jenis_kelamin = "";
    $action_form = base_url("save_tahanan/input");
}else{
    $code_napi = $edit->code_napi;
    $nama_ayah = $edit->nama_ayah;
    $nama = $edit->nama;
	$jenis_kelamin = $edit->jenis_kelamin;
    $action_form = base_url("save_tahanan/".$this->uri->segment(2));
}
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-4">
                <form action="<?= $action_form ?>" method="post" enctype="multipart/form-data">
                    <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">Code NAPI</div>
                        <div class="col-lg-9">
                            <input type="text" id="code_napi" name="code_napi" class="form-control mb-2" value="<?= $code_napi ?>" readonly required="required">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">Nama</div>
                        <div class="col-lg-9">
                            <input type="text" id="nama" name="nama" class="form-control mb-2" value="<?= $nama ?>" required="required" placeholder="Masukkan nama WBP">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">Nama Ayah</div>
                        <div class="col-lg-9">
                            <input type="text" id="nama_ayah" name="nama_ayah" class="form-control mb-2" value="<?= $nama_ayah ?>" placeholder="Masukkan nama ayah">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">Jenis Kelamin</div>
                        <div class="col-lg-9">
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control">
                                <option value="Pria" <?php if($jenis_kelamin == "Pria"){ echo "selected"; } ?>>Pria</option>
                                <option value="Wanita" <?php if($jenis_kelamin == "Wanita"){ echo "selected"; } ?>>Wanita</option>
                            </select>
                        </div>
                        <div class="row mb-2">
                        <div class="col-lg-3 d-none d-lg-block m-auto">PIN Uang Digital</div>
                        <div class="col-lg-9">
                            <input type="password" id="pin" name="pin" class="form-control mb-1" maxlength="6" pattern="\d{6}" placeholder="<?= empty($edit) ? 'Masukkan 6 digit angka (Kosongkan = default 123456)' : 'Kosongkan jika tidak ingin merubah PIN saat ini' ?>" title="Harus 6 digit angka">
                            <small class="text-danger">*Hanya berisi 6 digit angka rahasia.</small>
                        </div>
                    </div>
                    </div>

                    <div class="mt-2" align="right">
                        <a href="<?= base_url("tahanan") ?>" class="btn btn-danger" data-dismiss="modal">Kembali</a>
                        <button type="submit" name="btn-simpan" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>