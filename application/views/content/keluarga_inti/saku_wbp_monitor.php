<?php
if($this->keluarga_inti <= 0){
    $this->session->set_flashdata("swal",'
    <script>
        swal.fire({
            title: "Error",
            html: "Anda tidak memiliki hak akses di halaman tersebut",
            icon: "error"
        });
    </script>');
    redirect("saku_wbp");
}
if(is_array(json_decode($this->kode_tahanan,true))){
    $kode_tahanan = "'".implode("','",json_decode($this->kode_tahanan,true))."'";
}else{
    $kode_tahanan = "'".$this->kode_tahanan."'";
}
?>
<div class="row">
    <div class="col-lg-12 text-right mb-2">
        <a href="<?= base_url("saku_wbp") ?>" class="btn btn-sm btn-danger">Kembali</a>
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-small" id="datatable">
                <thead class="thead-light bg-light-info">
                    <tr>
                        <th>No</th>
                        <th>Nama WBP</th>
                        <th>Sisa Uang</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $load = '';
                    $data_penyimpanan = $this->admin_model->query("SELECT kode_tahanan,nama_tahanan FROM `penyimpanan_uang` WHERE kode_tahanan IN(".$kode_tahanan.") GROUP BY kode_tahanan ORDER BY kode_tahanan ASC");
                    if(!empty($data_penyimpanan)){
                        foreach ($data_penyimpanan as $dp) {
                            $uang = $this->admin_model->get_data_select("penyimpanan_uang","SUM(jumlah_uang) as jumlah_uang","kode_tahanan = '".$dp["kode_tahanan"]."'","row");
                            $penggunaan_uang = $this->admin_model->get_data_select("penggunaan_uang","SUM(total_penggunaan) as total_penggunaan","kode_tahanan = '".$dp["kode_tahanan"]."' AND status = 'Received'","row");
                            $jumlah_uang = $uang->jumlah_uang - $penggunaan_uang->total_penggunaan;
                            if($jumlah_uang < 0){
                                $tampil_uang = '<span class="text-danger">-Rp. '.number_format(abs($jumlah_uang),0,"",".").' (HUTANG)</span>';
                            }else{
                                $tampil_uang = 'Rp. '.number_format($jumlah_uang,0,"",".");
                            }

                            $load .= '
                            <tr>
                                <td class="text-center align-middle">'.$no.'</td>
                                <td class="text-center align-middle">'.$dp["nama_tahanan"].'</td>
                                <td class="text-center align-middle">'.$tampil_uang.'<br><a href="'.base_url("rincian_saku_wbp/".$dp["kode_tahanan"]).'" class="btn btn-sm btn-success mt-2">Lihat Rincian</a></td>
                            </tr>';
                            $no++;
                        }
                    }
                    echo $load;
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