<?php
$kode_tahanan = $this->kode_tahanan;
$keluarga_inti = $this->keluarga_inti;
if($keluarga_inti <= 0){
    $data_penyimpanan = $this->admin_model->get_data_select("penyimpanan_uang","*","nama_pengirim = '".$this->nama."' ORDER BY tanggal DESC","result");
    if(!empty($data_penyimpanan)){
        foreach ($data_penyimpanan as $dp) {
            $kode_tahanan_array[] = $dp->kode_tahanan;
        }
        $kode_tahanan = "'".implode("','",$kode_tahanan_array)."'";
    }else{
        $kode_tahanan = "";
    }
    $monitor_saku = '<a href="javascript:void(0)" onclick="bukan_ki()" class="btn btn-info mb-2">Kontrol Keluar Masuk Uang WBP</a>';
}else{
    if(is_array(json_decode($kode_tahanan,true))){
        $kode_tahanan =  "'".implode("','",json_decode($kode_tahanan,true))."'";
    }else{
        $kode_tahanan = "'".$kode_tahanan."'";
    }
    $data_penyimpanan = $this->admin_model->get_data_select("penyimpanan_uang","*","kode_tahanan IN(".$kode_tahanan.") OR nama_pengirim = '".$this->nama."' ORDER BY tanggal DESC","result");
    $monitor_saku = '<a href="'.base_url("saku_wbp_monitor").'" class="btn btn-info mb-2">Kontrol Keluar Masuk Uang WBP</a>';
}
?>
<button class="btn btn-success mb-2" onclick="under_develop()">Titip Uang Online</button>
<?= $monitor_saku; ?>
<div class="row">
    <div class="col-12">
        <center><h5 style="font-weight:bold;">History Penitipan Uang Anda</h5></center>
        <div class="table-responsive">
            <table class="table table-bordered table-small mb-1" style="font-size:7pt;">
                <thead class="thead-light bg-light-info">
                    <tr>
                        <th class="align-middle" style="border:1px solid;" colspan="3">Data Titipan Uang</th>
                        <th class="align-middle" style="border:1px solid;" colspan="4">Status Penyerahan</th>
                        <th class="align-middle" style="border:1px solid;" rowspan="2">Sisa</th>
                    </tr>
                    <tr>
                        <th class="align-middle" style="border:1px solid;">Hari, Tanggal</th>
                        <th class="align-middle" style="border:1px solid;">WBP Tujuan</th>
                        <th class="align-middle" style="border:1px solid;">Jumlah</th>
                        <th class="align-middle" style="border:1px solid;">Hari, Tanggal</th>
                        <th class="align-middle" style="border:1px solid;">Jenis Penyerahan</th>
                        <th class="align-middle" style="border:1px solid;">Jumlah</th>
                        <th class="align-middle" style="border:1px solid;">Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $load = '';
                    if(!empty($data_penyimpanan)){
                        if(!empty($kode_tahanan)){
                            $uang_masuk = $this->admin_model->get_data_select("penyimpanan_uang","id,tanggal,nama_tahanan,nama_pengirim,jumlah_uang","nama_pengirim = '".$this->nama."' ORDER BY tanggal DESC","result");
                        }else{
                            $uang_masuk = "";
                        }
                        if(!empty($uang_masuk)){
                            foreach ($uang_masuk as $um) {
                                $rowspan = $this->admin_model->get_data_select("penggunaan_uang","COUNT(id) as rowspan","id_uang_masuk LIKE '%".$um->id."%' AND status = 'Received'","row");
                                $rowspan = $rowspan->rowspan+1;
                                if(!empty($rowspan)){
                                    $rowspan = "rowspan = '".$rowspan."'";
                                }else{
                                    $rowspan = "";
                                }
                                $pengeluaran = $this->admin_model->get_data_select("penggunaan_uang","tanggal,penggunaan,total_penggunaan,saldo_akhir,bukti","id_uang_masuk LIKE '%".$um->id."%' AND status = 'Received' ORDER BY tanggal ASC","result");
                                $load_pengeluaran = "";
                                if(!empty($pengeluaran)){
                                    $hitung_total = 'bukan_belanja';
                                    foreach ($pengeluaran as $pengeluaran) {
                                        $penggunaan_decode = json_decode($pengeluaran->penggunaan,true);
                                        $penggunaan = '';
                                        foreach ($penggunaan_decode as $key => $value) {
                                            if(is_numeric($key)){
                                                $penggunaan .= '- '.$value.'<br>';
                                            }else{
                                                $penggunaan .= '- '.$key.'<br>';
                                                $penggunaan .= '<ul class="m-0">';
                                                $hitung_total = 0;
                                                foreach ($value as $k => $v) {
                                                    $hitung_total += $v;
                                                    $penggunaan .= '<li>'.$k.' (Rp. '.number_format($v,0,"",".").')</li>';
                                                }
                                                $penggunaan .= '</ul>';
                                            }
                                        }
                                        if(is_numeric($hitung_total)){
                                            $uang_penitip_lain = $hitung_total-$pengeluaran->total_penggunaan;
                                            if($uang_penitip_lain > 0){
                                                $keterangan_lebih = '<br><label class="m-0 text-danger">(+ Rp. '.number_format($uang_penitip_lain,0,"",".").'<br>dari penitip lain)</label>';
                                            }else{
                                                $keterangan_lebih = '';
                                            }
                                        }else{
                                            $keterangan_lebih = '';
                                        }
                                        $load_pengeluaran .= '
                                        <tr>
                                            <td style="border:1px solid;" class="text-center align-middle p-1">'.formatindo($pengeluaran->tanggal).'</td>
                                            <td style="border:1px solid;" class="align-middle p-1 pl-2"><div style="width:280px;">'.$penggunaan.'</div></td>
                                            <td style="border:1px solid;" class="text-center align-middle p-1">Rp. '.number_format($pengeluaran->total_penggunaan,0,"",".").$keterangan_lebih.'</td>
                                            <td style="border:1px solid;" class="text-center align-middle p-1"><a href="'.$pengeluaran->bukti.'" target="_blank">Lihat Bukti</a></td>
                                            <td style="border:1px solid;" class="text-center align-middle p-1">Rp. '.number_format($pengeluaran->saldo_akhir,0,"",".").'</td>
                                        </tr>';
                                    }
                                    $saldo_akhir = $pengeluaran->saldo_akhir;
                                }else{
                                    $load_pengeluaran .= '
                                    <td style="border:1px solid;" class="text-center align-middle p-1">-</td>
                                    <td style="border:1px solid;" class="text-center align-middle p-1">-</td>
                                    <td style="border:1px solid;" class="text-center align-middle p-1">-</td>
                                    <td style="border:1px solid;" class="text-center align-middle p-1">-</td>
                                    <td style="border:1px solid;" class="text-center align-middle p-1">Rp. '.number_format($um->jumlah_uang,0,"",".").'</td>';
                                    $saldo_akhir = $um->jumlah_uang;
                                }
                                $load .= '
                                <tr>
                                    <td style="border:1px solid;" '.$rowspan.' class="text-center align-middle p-1">'.formatindo($um->tanggal).'</td>
                                    <td style="border:1px solid;" '.$rowspan.' class="text-center align-middle p-1">'.$um->nama_tahanan.'</td>
                                    <td style="border:1px solid;" '.$rowspan.' class="text-center align-middle p-1">Rp. '.number_format($um->jumlah_uang,0,"",".").'</td>
                                    '.$load_pengeluaran.'
                                </tr>';
                            }
                        }
                        echo $load;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>