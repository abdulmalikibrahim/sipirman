<div class="row">
    <div class="col-12">
        <table class="table table-bordered table-small" id="datatable">
            <thead class="thead-light bg-light-info">
                <tr>
                    <th>No</th>
                    <th>Tanggal Belanja</th>
                    <th>WBP Tujuan</th>
                    <th>Daftar Belanja</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                // NOTE: "*" together with GROUP BY fails under the ONLY_FULL_GROUP_BY sql_mode
                // that most MySQL/MariaDB installs enable by default (verified: ERROR 1055),
                // since id, tanggal, kode_tahanan, penggunaan, status etc. aren't aggregated.
                // These columns are identical across every row sharing one id_belanja_keluarga
                // (they're just split across multiple deposits of the same checkout), so it's
                // safe to pick any one value per group via MAX().
                $select_belanja = "id_belanja_keluarga, MAX(kode_tahanan) as kode_tahanan, MAX(tanggal) as tanggal, MAX(status) as status, MAX(penggunaan) as penggunaan, SUM(total_penggunaan) as grand_total_penggunaan";
                if($this->level == "Admin"){
                    $status_belanja = $this->admin_model->get_data_select("penggunaan_uang",$select_belanja,"penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh Keluarga%' AND id_belanja_keluarga != '' AND status = 'Need Confirm' OR penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh Penitip%' AND id_belanja_keluarga != '' AND status = 'Need Confirm' GROUP BY id_belanja_keluarga ORDER BY tanggal DESC","result");
                }else if($this->level == "Cashier"){
                    $status_belanja = $this->admin_model->get_data_select("penggunaan_uang",$select_belanja,"penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh Keluarga%' AND id_belanja_keluarga != '' AND status = 'Process' OR penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh Penitip%' AND id_belanja_keluarga != '' AND status = 'Process' GROUP BY id_belanja_keluarga ORDER BY tanggal DESC","result");
                }
                if(!empty($status_belanja)){
                        foreach ($status_belanja as $data) {
                            $wbp = $this->admin_model->get_data_select("tahanan","nama","code_napi = '".$data->kode_tahanan."'","row");
                            if(!empty($wbp)){
                                $wbp = $wbp->nama;
                            }else{
                                $wbp = "Not found in database";
                            }
                            $penggunaan_decode = json_decode($data->penggunaan,true);
                            $penggunaan = '';
                            foreach ($penggunaan_decode as $key => $value) {
                                if(is_numeric($key)){
                                    $penggunaan .= '- '.$value.'<br>';
                                }else{
                                    $penggunaan .= '- '.$key.'<br>';
                                    $penggunaan .= '<ul>';
                                    foreach ($value as $k => $v) {
                                        $penggunaan .= '<li>'.$k.' (Rp. '.number_format($v,0,"",".").')</li>';
                                    }
                                    $penggunaan .= '<ul>';
                                }
                            }

                            if($data->status == "Need Confirm"){
                                $status = '<span class="badge badge-warning">Menunggu Konfirmasi</span>';
                            }else if($data->status == "Process"){
                                $status = '<span class="badge badge-info">Permintaan Proses</span>';
                            }

                            if($this->level == "Admin"){
                                $column_action = '
                                <td class="align-middle" align="center">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-info" onclick="confirm(this)" data-id="'.$data->id_belanja_keluarga.'">Konfirmasi</a>
                                </td>';
                            }else{
                                $column_action = '
                                <td class="align-middle" align="center">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-info" onclick="uploadfoto(this)" data-id="'.$data->id_belanja_keluarga.'">Konfirmasi</a>
                                </td>';
                            }
                            echo '
                            <tr id="row-'.$data->id_belanja_keluarga.'">
                                <td class="align-middle" align="center">'.$no++.'</td>
                                <td class="align-middle" align="center">'.date("d-M-Y H:i:s",strtotime($data->tanggal)).'</td>
                                <td class="align-middle" align="center">'.$wbp.'</td>
                                <td class="align-middle">'.$penggunaan.'</td>
                                <td class="align-middle" align="center">Rp. '.number_format($data->grand_total_penggunaan,0,"",".").'</td>
                                <td class="align-middle" align="center">'.$status.'</td>
                                '.$column_action.'
                            </tr>';
                        }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="uploadfoto" tabindex="-1" role="dialog" aria-labelledby="uploadfotoLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
            <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="uploadfotoLabel">Upload bukti penyerahan</h5>
                  <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                  </a>
                  </div>
                  <div class="modal-body">
                        <label for="" class="mt-0">Bukti Penyerahan</label>
                        <input type="file" name="bukti" id="bukti" accept=".png, .jpg" hidden required>
                        <img src="data:image/svg+xml,<svg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20200%20150%22><rect%20width=%22200%22%20height=%22150%22%20fill=%22%23f3f4f8%22/><g%20fill=%22none%22%20stroke=%22%23c7cbd4%22%20stroke-width=%224%22><rect%20x=%2255%22%20y=%2240%22%20width=%2290%22%20height=%2265%22%20rx=%226%22/><circle%20cx=%2280%22%20cy=%2263%22%20r=%228%22/><path%20d=%22M55%2095l25-25%2020%2018%2015-15%2030%2030%22/></g><text%20x=%22100%22%20y=%22128%22%20font-family=%22sans-serif%22%20font-size=%2213%22%20fill=%22%239aa4b8%22%20text-anchor=%22middle%22>No%20Image</text></svg>" alt="Bukti Penyerahan" id="foto_bukti" width="100%">
                        <div class="row mt-3">
                              <div class="col-12 text-right"><a href="javascript:void(0)" id="btn-selesai" data-id="" class="btn btn-info w-100" onclick="proses_selesai(this)">Selesai</a></div>
                        </div>
                  </div>
            </div>
      </div>
</div>