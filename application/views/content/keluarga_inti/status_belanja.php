<div class="row">
    <div class="col-12">
        <div class="content pb-3" align="right">
            <a href="<?= base_url("warung_sipirman_ki") ?>" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <table class="table table-bordered table-small" id="datatable">
            <thead class="thead-light bg-light-info">
                <tr>
                    <th>No</th>
                    <th>Tanggal Belanja</th>
                    <th>WBP</th>
                    <th>Daftar Belanja</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $status_belanja = $this->admin_model->get_data_select("penggunaan_uang","*,SUM(total_penggunaan) as grand_total_penggunaan","penggunaan LIKE '%".$this->nama."%' AND id_belanja_keluarga != '' GROUP BY id_belanja_keluarga ORDER BY tanggal DESC","result");
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
                                $status = '<span class="badge badge-info">Sedang Diproses</span>';
                            }else if($data->status == "Received"){
                                if(!empty($data->tanggal_diserahkan)){
                                    $tanggal_penyerahan = '<span style="font-size:8pt;">'.date("d-M-Y",strtotime($data->tanggal_diserahkan))."<br>".date("H:i:s",strtotime($data->tanggal_diserahkan)).'</span>';
                                }else{
                                    $tanggal_penyerahan = "-";
                                }

                                if(!empty($data->bukti)){
                                    $bukti = '<a href="'.$data->bukti.'" class="btn btn-sm btn-info" target="_blank">Lihat Bukti</a>';
                                }else{
                                    $bukti = "Tidak ada bukti";
                                }
                                $status = '<span class="badge badge-success">Selesai</span><br>'.$tanggal_penyerahan."<br>".$bukti;
                            }else{
                                if(!empty($data->tanggal_diserahkan)){
                                    $tanggal_penyerahan = '<span style="font-size:8pt;">'.date("d-M-Y",strtotime($data->tanggal_diserahkan))."<br>".date("H:i:s",strtotime($data->tanggal_diserahkan)).'</span>';
                                }else{
                                    $tanggal_penyerahan = "-";
                                }

                                $alasan_ditolak = '<a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="show_msg(this)" data-alasan="'.$data->alasan_discard.'"><i class="fas fa-eye pr-1"></i>Lihat alasan</a>';
                                $status = '<span class="badge badge-danger">Ditolak</span><br>'.$tanggal_penyerahan."<br>".$alasan_ditolak;
                            }
                            echo '
                            <tr>
                                <td class="align-middle" align="center">'.$no++.'</td>
                                <td class="align-middle" align="center">'.date("d-M-Y H:i:s",strtotime($data->tanggal)).'</td>
                                <td class="align-middle" align="center">'.$wbp.'</td>
                                <td class="align-middle">'.$penggunaan.'</td>
                                <td class="align-middle" align="center">Rp. '.number_format($data->grand_total_penggunaan,0,"",".").'</td>
                                <td class="align-middle" align="center">'.$status.'</td>
                            </tr>';
                        }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>