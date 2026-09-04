<?php
$kode_tahanan = $this->p2;
$sisa_uang = $this->admin_model->get_data_select("penyimpanan_uang","SUM(jumlah_uang) as uang_masuk, (SELECT SUM(total_penggunaan) FROM penggunaan_uang WHERE kode_tahanan = '$kode_tahanan' AND status = 'Received') as uang_keluar","kode_tahanan = '$kode_tahanan'","row");
$sisa = $sisa_uang->uang_masuk - $sisa_uang->uang_keluar;
?>
<div class="row">
    <div class="col-12">
        <div class="w-100 text-right"><a href="<?= base_url("penyimpanan"); ?>" class="btn btn-sm btn-danger mb-2">Kembali</a></div>
        <div class="w-100 text-right"><h5>Uang saat ini : <?= "RP. ".number_format($sisa,0,"","."); ?></h5></div>
        <div class="table-responsive">
            <table class="table table-bordered table-small mb-1" style="font-size:7pt;">
                <thead class="thead-light bg-light-info">
                    <tr>
                        <th class="align-middle" style="border:1px solid;" rowspan="2">Aksi</th>
                        <th class="align-middle" style="border:1px solid;" colspan="3">Uang Masuk</th>
                        <th class="align-middle" style="border:1px solid;" colspan="4">uang Keluar</th>
                        <th class="align-middle" style="border:1px solid;" rowspan="2">Sisa</th>
                    </tr>
                    <tr>
                        <th class="align-middle" style="border:1px solid;">Hari, Tanggal</th>
                        <th class="align-middle" style="border:1px solid;">Penitip</th>
                        <th class="align-middle" style="border:1px solid;">Jumlah</th>
                        <th class="align-middle" style="border:1px solid;">Hari, Tanggal</th>
                        <th class="align-middle" style="border:1px solid;">Penggunaan</th>
                        <th class="align-middle" style="border:1px solid;">Jumlah</th>
                        <th class="align-middle" style="border:1px solid;">Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $uang_masuk = $this->admin_model->get_data_select("penyimpanan_uang","id,tanggal,nama_pengirim,jumlah_uang,kode_tahanan","kode_tahanan = '$kode_tahanan' ORDER BY tanggal ASC","result");
                    
                    $load = '';
                    if(!empty($uang_masuk)){
                        foreach ($uang_masuk as $um) {
                            $rowspan = $this->admin_model->get_data_select("penggunaan_uang","COUNT(id) as rowspan","id_uang_masuk LIKE '%".$um->id."%'  AND kode_tahanan = '".$um->kode_tahanan."' AND status = 'Received'","row");
                            $rowspan = $rowspan->rowspan+1;
                            if(!empty($rowspan)){
                                $rowspan = "rowspan = '".$rowspan."'";
                            }else{
                                $rowspan = "";
                            }
                            $pengeluaran = $this->admin_model->get_data_select("penggunaan_uang","tanggal,penggunaan,total_penggunaan,saldo_akhir,bukti","id_uang_masuk LIKE '%".$um->id."%' AND kode_tahanan = '".$um->kode_tahanan."' AND status = 'Received' ORDER BY tanggal ASC","result");
                            $load_pengeluaran = "";
                            if(!empty($pengeluaran)){
                                foreach ($pengeluaran as $pengeluaran) {
                                    $penggunaan_decode = json_decode($pengeluaran->penggunaan,true);
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
                                    $load_pengeluaran .= '
                                    <tr>
                                        <td style="border:1px solid;" class="text-center">'.formatindo($pengeluaran->tanggal).'</td>
                                        <td style="border:1px solid;"><div style="width:280px;">'.$penggunaan.$um->kode_tahanan.'</div></td>
                                        <td style="border:1px solid;" class="text-center">Rp. '.number_format($pengeluaran->total_penggunaan,0,"",".").'</td>
                                        <td style="border:1px solid;" class="text-center"><a href="'.$pengeluaran->bukti.'" target="_blank">Lihat Bukti</a></td>
                                        <td style="border:1px solid;" class="text-center">Rp. '.number_format($pengeluaran->saldo_akhir,0,"",".").'</td>
                                    </tr>';
                                }
                                $saldo_akhir = $pengeluaran->saldo_akhir;
                            }else{
                                $load_pengeluaran .= '
                                <td style="border:1px solid;" class="text-center">-</td>
                                <td style="border:1px solid;" class="text-center">-</td>
                                <td style="border:1px solid;" class="text-center">-</td>
                                <td style="border:1px solid;" class="text-center">-</td>
                                <td style="border:1px solid;" class="text-center">Rp. '.number_format($um->jumlah_uang,0,"",".").'</td>';
                                $saldo_akhir = $um->jumlah_uang;
                            }
                            if($saldo_akhir > 0){
                                $btn_serahkan = '<a href="'.base_url("penyerahan_uang/".$kode_tahanan."?uangmasuk=".$um->id."&i=".$saldo_akhir).'" class="btn btn-sm btn-success mb-2" style="font-size:6pt;">Serahkan<br>Tunai</a><br>';
                            }else{
                                $alert = "alert('Warning','Tidak bisa menyerahkan tunai, karena sisa uang sudah habis','warning')";
                                $btn_serahkan = '<a href="javascript:void(0)" onclick="'.$alert.'" class="btn btn-sm btn-success mb-2" style="font-size:6pt;">Serahkan<br>Tunai</a><br>';
                            }
                            $load .= '
                            <tr>
                                <td style="border:1px solid;" '.$rowspan.' class="text-center">
                                    '.$btn_serahkan.'
                                    <a href="'.base_url("rincian_uang_tunai/".$kode_tahanan."?uangmasuk=".$um->id).'" class="btn btn-sm btn-info" style="font-size:6pt;">Rincian<br>Pengguaan<br>Tunai</a>
                                </td>
                                <td style="border:1px solid;" '.$rowspan.' class="text-center">'.formatindo($um->tanggal).'</td>
                                <td style="border:1px solid;" '.$rowspan.' class="text-center">'.$um->nama_pengirim.'</td>
                                <td style="border:1px solid;" '.$rowspan.' class="text-center">Rp. '.number_format($um->jumlah_uang,0,"",".").'</td>
                                '.$load_pengeluaran.'
                            </tr>
                            <tr><td colspan="9" style="border-bottom:1px solid;"></td></tr>';
                        }
                    }
                    echo $load;
                    ?>
                </tbody>
            </table>
            <div class="w-100 text-right"><h5>Uang saat ini : <?= "RP. ".number_format($sisa,0,"","."); ?></h5></div>
            <div class="w-100 text-right"><a href="<?= base_url("penyimpanan"); ?>" class="btn btn-sm btn-danger">Kembali</a></div>
        </div>
    </div>
</div>