<div class="row mb-3">
    <div class="col-lg-6 mb-3">
        <form action="<?= base_url("settgldata") ?>" method="post" id="form_filter">
            <label>Tanggal :</label>
            <div class="row ml-0">
                <div class="col-5 p-0">
                    <input type="date" name="tanggal_1" id="tanggal_1" value="<?= $this->tanggal_1 ?>" class="form-control">
                </div>
                <div class="col-lg-1 col-2 p-0 pt-2" align="center">s/d</div>
                <div class="col-5 pl-0">
                    <input type="date" name="tanggal" id="tanggal" value="<?= $this->tanggal ?>" class="form-control">
                </div>
            </div>
            <!--<div class="row">-->
            <!--    <div class="col-lg-5 pr-0">-->
            <!--        <input type="date" name="tanggal_1" id="tanggal_1" value="<?= $this->tanggal_1 ?>" class="form-control">-->
            <!--    </div>-->
            <!--    <div class="col-lg-1 p-0 pt-2" align="center">s/d</div>-->
            <!--    <div class="col-lg-5 pl-0">-->
            <!--        <input type="date" name="tanggal" id="tanggal" value="<?= $this->tanggal ?>" class="form-control">-->
            <!--    </div>-->
            <!--</div>-->
        </form>
    </div>
    <div class="col-lg-3 mb-2">
        <form action="<?= base_url("setstatus") ?>" method="post" id="ff_status">
            <label>Status :</label>
            <select name="fstatus" id="fstatus" class="form-control">
                <?php 
                $array = [
                    "Semua" => "Semua",
                    "Menunggu" => "Diterima Pendaftaran",
                    "Diterima P2U" => "Masuk P2U",
                    "Diterima Komandan Jaga" => "Diterima Komandan Jaga",
                    "Selesai Diantar" => "Diterima WBP"
                ];
                foreach ($array as $key => $value) {
                    if($key == $this->status){
                        $selected = "selected";
                    }else{
                        $selected = "";
                    }
                    ?>
                    <option <?=$selected?> value="<?=$key?>"><?=$value?></option>
                    <?php
                }
                ?>
            </select>
        </form>
    </div>
    <div class="col-lg-3">
        <div class="content pb-3" align="right">
            <a href="<?= base_url("input") ?>" class="btn btn-success"><i class="fas fa-plus"></i></a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-small" id="datatable">
                <thead class="thead-light bg-light-info">
                    <tr>
                        <th>No</th>
                        <th>No. Resi</th>
                        <th>Nama Penitip</th>
                        <th>Hubungan</th>
                        <!--<th>Keluarga Inti</th>-->
                        <th>Nama WBP</th>
                        <th>Barang Titipan</th>
                        <th>Status</th>
                        <?php 
                        if($this->level == "Admin"){
                            echo "<th>Opsi</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
foreach ($data_titipan as $data) {
    $get_phone = $this->admin_model->get_data_select("penitip","hp","nik = '".$data->nik."'","row");
    $status = $data->status;
    if(empty($data->diterima_p2u)){
        $status = "<button class='btn btn-sm btn-danger'>Diterima Pendaftaran<br>".date("d-m-Y H:i:s",strtotime($data->tanggal))."</button>";
    }else if(empty($data->diterima_komandan)){
        $status = "<button class='btn btn-sm btn-warning'>Masuk P2U<br>".date("d-m-Y H:i:s",strtotime($data->diterima_p2u))."</button>";
    }else if(empty($data->diterima_wbp)){
        $status = "<button class='btn btn-sm btn-info'>Diterima Komandan Jaga<br>".date("d-m-Y H:i:s",strtotime($data->diterima_komandan))."</button>";
    }else if(!empty($data->diterima_wbp)){
        $status = "<button class='btn btn-sm btn-success'>Diterima WBP<br>".date("d-m-Y H:i:s",strtotime($data->diterima_wbp))."</button>";
    }
    ?>
    
    <tr align="center" id="row_<?= $data->id; ?>">
        <td><?= $no++; ?></td>
        <td><?= $data->resi; ?></td>
        <td><?= $data->nama_pengirim; ?></td>
        <td><?= $data->hubungan; ?></td>
        <td><?= $data->nama_tahanan; ?></td>
        <td align="left" width="200">
            <?php 
            // MODIFIKASI: fallback ke array kosong - titipan uang/antiseptik/obat
            // (dari Penyimpanan::save()) tidak selalu punya data_barang, dan
            // count(null) fatal error di PHP 8+ (count() butuh Countable|array).
            $barang_titipan = json_decode($data->data_barang, true) ?: [];
            $count_bt = count($barang_titipan);
            if($count_bt > 1){
                ?>
                <a href='javascript:void(0)' data-id='<?=$data->id?>' data-status='full' class="text-dark item-min" id='detail-item-<?=$data->id?>'>
                    <div class="cut-text" id="cut-text-<?=$data->id;?>">
                        <?php
                        foreach ($barang_titipan as $key => $value) {
                            echo $key." (".$value.")<font class='dot-".$data->id."'> .......</font><br>";
                        }
                        ?>
                    </div>
                </a>
                <?php
            }else{
                foreach ($barang_titipan as $key => $value) {
                    echo $key." (".$value.")";
                }
            }
            ?>
        </td>
        <td>
            <div id="status_<?= $data->id; ?>"><?= $status; ?></div>
        </td>
        <?php 
        if($this->level == "Admin"){
            ?>
            <td width="100">
    <a href="<?= base_url("data/".$data->id); ?>" data-toggle="tooltip" title="Edit"><i class="fas fa-edit pb-2 icon-aksi pr-1"></i></a>
    
    <a href="javascript:void(0)" onclick="del_data(<?= $data->id; ?>)" data-toggle="tooltip" title="Hapus"><i class="fas fa-trash-alt icon-aksi pb-2 pr-1 text-danger"></i></a>
    
    <a href="javascript:void(0)" onclick="detail(<?= $data->id ?>)" data-toggle="tooltip" title="Detail"><i class="fas fa-clipboard icon-aksi text-success pb-2 pr-1"></i></a>

    <?php if($data->status == "Selesai Diantar"): ?>
        <?php
        $list_brg = json_decode($data->data_barang, true);
        $text_brg = "";
        if(!empty($list_brg)) {
            foreach ($list_brg as $key => $val) { 
                $text_brg .= "- " . $key . " (" . $val . ")%0A"; 
            }
        }

        $pesan_wa = "*SIPIRMAN RUTAN REMBANG*%0A%0A"
            . "Yth. Bpk/Ibu/Sdr. *" . $data->nama_pengirim . "*%0A"
            . "Kami informasikan bahwa barang titipan anda untuk WBP atas nama *" . $data->nama_tahanan . "* telah tersampaikan kepada yang bersangkutan.%0A%0A"
            . "Berikut Histori Tracking Barang Titipan anda:%0A%0A"
            . "*Diterima Pendaftaran (Petugas: " . $data->input_by . ")*%0A" . date("d-m-Y H:i:s", strtotime($data->tanggal)) . "%0A"
            . "Daftar Barang:%0A" . $text_brg . "%0A"
            . "*Diterima P2U (Petugas: " . (!empty($data->pic_p2u) ? $data->pic_p2u : '-') . ")*%0A" . (!empty($data->diterima_p2u) ? date("d-m-Y H:i:s", strtotime($data->diterima_p2u)) : '-') . "%0A%0A"
            . "*Diterima Komandan (Petugas: " . (!empty($data->pic_komandan) ? $data->pic_komandan : '-') . ")*%0A" . (!empty($data->diterima_komandan) ? date("d-m-Y H:i:s", strtotime($data->diterima_komandan)) : '-') . "%0A%0A"
            . "*Diterima WBP (" . $data->nama_tahanan . ")*%0A" . (!empty($data->diterima_wbp) ? date("d-m-Y H:i:s", strtotime($data->diterima_wbp)) : '-') . "%0A%0A"
            . "*Keterangan*%0A" . (!empty($data->keterangan) ? $data->keterangan : '-') . "%0A%0A"
            . "Pesan Anda:%0A" . (!empty($data->pesan_penitip) ? $data->pesan_penitip : '-') . "%0A"
            . "Pesan WBP: " . (!empty($data->pesan_tahanan) ? $data->pesan_tahanan : '-') . "%0A%0A"
            . "_ini adalah pesan otomatis dari Aplikasi SIPIRMAN RUTAN REMBANG_";
        ?>
        <a href="https://wa.me/<?= substr_replace($get_phone->hp, '+62', 0, 1); ?>?text=<?= $pesan_wa ?>" 
           data-toggle="tooltip" target="_blank" title="Kirim Info Tracking">
           <i class="fab fa-whatsapp icon-aksi text-primary pb-2 pr-1"></i>
        </a>
    <?php endif; ?>

    <?php if($data->status == "Menunggu"): ?>
        <a href="javascript:void(0)" data-toggle="modal" onclick="btn_ok(<?=$data->id?>)" data-target="#Modalp2u" title="Masuk P2U"><i class="fas fa-shipping-fast text-warning pb-2 pr-1 icon-aksi" id="btn-opsi-<?= $data->id; ?>"></i></a>
    <?php endif; ?>
</td>
            <?php
        }
        ?>
    </tr>
    <?php
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal untuk Memasukkan nama pelanggan -->
<div class="modal fade" id="Modalp2u" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Petugas P2U</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
            <div class="modal-body" align="right">
                <select id="pp2u" class="form-control">
                    <option value="">Pilih Petugas</option>
                    <?php 
                    $p2u = $this->admin_model->get_data_select("account","*","id != '' AND level = 'Tracer'","result");
                    foreach ($p2u as $p2u) {
                        ?>
                        <option value="<?=$p2u->name?>"><?=$p2u->name?></option>
                        <?php
                    }
                    ?>
                </select>
                <button onclick="deli_data()" data-id="" id="btn-ok" class="btn btn-info mt-2">OK</button>
            </div>
        </div>
    </div>
</div>