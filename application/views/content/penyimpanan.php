<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-small" id="datatable">
                <thead class="thead-light bg-light-info">
                    <tr>
                        <th>No</th>
                        <th>Nama WBP</th>
                        <th>Uang</th>
                        <th>Antiseptik</th>
                        <th>Obat-Obatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $load = '';
                    // NOTE: GROUP BY needs to sit on top of the UNION as a derived table (with an
                    // aggregate on nama_tahanan) instead of selecting the raw column directly,
                    // otherwise this fails under the ONLY_FULL_GROUP_BY sql_mode that most
                    // MySQL/MariaDB installs enable by default (verified: throws ERROR 1055).
                    $data_penyimpanan = $this->admin_model->query("SELECT kode_tahanan, MAX(nama_tahanan) as nama_tahanan FROM (SELECT kode_tahanan,nama_tahanan FROM `penyimpanan_uang` UNION SELECT kode_tahanan,nama_tahanan FROM `penyimpanan_obat` UNION SELECT kode_tahanan,nama_tahanan FROM `penyimpanan_antiseptik`) t GROUP BY kode_tahanan ORDER BY kode_tahanan ASC");
                    if(!empty($data_penyimpanan)){
                        foreach ($data_penyimpanan as $dp) {
                            $uang = $this->admin_model->get_data_select("penyimpanan_uang","SUM(jumlah_uang) as jumlah_uang","kode_tahanan = '".$dp["kode_tahanan"]."'","row");
                            $penggunaan_uang = $this->admin_model->get_data_select("penggunaan_uang","SUM(total_penggunaan) as total_penggunaan","kode_tahanan = '".$dp["kode_tahanan"]."' AND status = 'Received'","row");
                            $jumlah_uang = $uang->jumlah_uang - $penggunaan_uang->total_penggunaan;
                            
                            $antiseptik = $this->admin_model->get_data_select("penyimpanan_antiseptik","data_antiseptik","kode_tahanan = '".$dp["kode_tahanan"]."'","row");
                            $load_antiseptik = "";
                            if(!empty($antiseptik)){
                                $data_antiseptik = json_decode($antiseptik->data_antiseptik,true);
                                $load_antiseptik .= '<table class="w-100" style="border: 0 !important;">';
                                foreach ($data_antiseptik as $key => $value) {
                                    $load_antiseptik .= '
                                    <tr>
                                        <td class="p-0" style="border-color:#fff !important">'.$value["nama"].'</td>
                                        <td class="p-0" style="border-color:#fff !important"> : </td>
                                        <td class="p-0" style="border-color:#fff !important">'.number_format($value["jumlah"],0,"",".").$value["satuan"].'</td>
                                    </tr>';
                                }
                                $load_antiseptik .= '</table>';
                            }
                            
                            $obat = $this->admin_model->get_data_select("penyimpanan_obat","data_obat","kode_tahanan = '".$dp["kode_tahanan"]."'","row");
                            $load_obat = "";
                            if(!empty($obat)){
                                $data_obat = json_decode($obat->data_obat,true);
                                $load_obat .= '<table class="w-100" style="border: 0 !important;">';
                                foreach ($data_obat as $key => $value) {
                                    $load_obat .= '
                                    <tr>
                                        <td class="p-0" style="border-color:#fff !important">'.$value["nama"].'</td>
                                        <td class="p-0" style="border-color:#fff !important"> : </td>
                                        <td class="p-0" style="border-color:#fff !important">'.number_format($value["jumlah"],0,"",".").$value["satuan"].'</td>
                                    </tr>';
                                }
                                $load_obat .= '</table>';
                            }
                            $load .= '
                            <tr>
                                <td class="text-center">'.$no.'</td>
                                <td class="text-center">'.$dp["nama_tahanan"].'</td>
                                <td class="text-center">Rp. '.number_format($jumlah_uang,0,"",".").'<br><a href="'.base_url("rincian_uang/".$dp["kode_tahanan"]).'" class="btn btn-sm btn-success mt-2">Lihat Rincian</a></td>
                                <td>'.$load_antiseptik.'</td>
                                <td>'.$load_obat.'</td>
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