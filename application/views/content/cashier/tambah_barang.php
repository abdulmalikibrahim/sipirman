<?php
$p = $this->p2;
if($p <= 0){
    $kode_barang = strtoupper(hash("crc32",date("YmdHis")));
    $nama_barang = "";
    $harga = "";
    $foto_barang = "";
}else{
    $data_edit = $this->admin_model->get_data_select("data_barang_koperasi","*","id = '$p'","row");
    if(!empty($data_edit)){
        $kode_barang = $data_edit->kode_barang;
        if(!empty($kode_barang)){
            $kode_barang = $kode_barang;
        }else{
            $kode_barang = strtoupper(hash("crc32",date("YmdHis")));
        }
        $nama_barang = $data_edit->nama_barang;
        $harga = $data_edit->harga;
        $foto_barang = $data_edit->foto_barang;
    }else{
        $kode_barang = strtoupper(hash("crc32",date("YmdHis")));
        $nama_barang = "";
        $harga = "";
        $foto_barang = "";
    }
}
?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form action="<?= base_url("simpan_barang_koperasi/".$p) ?>" method="post" enctype="multipart/form-data">
                    <div class="row mb-3" hidden>
                        <div class="col-lg-4 d-none d-lg-block m-auto">Kode Barang <span class="text-small">(Optional)</span></div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="kode_barang" value="<?= $kode_barang; ?>" placeholder="Kode Barang">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4 d-none d-lg-block m-auto">Nama Barang <span class="text-danger">*</span></div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Nama Barang" value="<?= $nama_barang; ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4 d-none d-lg-block m-auto">Harga <span class="text-danger">*</span></div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control harga" name="harga" placeholder="Harga" value="<?= $harga; ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4 d-none d-lg-block">Foto Barang <span class="text-dark">(Optional)</span></div>
                        <div class="col-lg-8">
                            <input type="file" name="upload_foto_barang" id="upload_foto_barang" accept=".png, .jpg" hidden>
                            <?php
                            if(!empty($foto_barang)){
                                $exp_barang = explode("/",$foto_barang);
                                if(file_exists(FCPATH."/upload/foto_barang_koperasi/".end($exp_barang))){
                                    $foto_barang = '<img src="'.$foto_barang.'" width="45%" id="foto_barang">';
                                }else{
                                    $foto_barang = '<img src="https://getstamped.co.uk/wp-content/uploads/WebsiteAssets/Placeholder.jpg" alt="barang koperasi" id="foto_barang" width="45%">';
                                }
                            }else{
                                $foto_barang = '<img src="https://getstamped.co.uk/wp-content/uploads/WebsiteAssets/Placeholder.jpg" alt="barang Penyerahan" id="foto_barang" width="45%">';
                            }
                            echo $foto_barang;
                            ?>
                        </div>
                    </div>
                    <div align="right">
                        <a href="<?= base_url("barang_koperasi"); ?>" class="btn btn-danger" name="btn-simpan">Kembali</a>
                        <button class="btn btn-info" name="btn-simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>