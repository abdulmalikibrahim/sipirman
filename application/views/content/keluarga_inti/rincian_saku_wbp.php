<?php
$kode_tahanan = $this->p2;
$sisa_uang = $this->admin_model->get_data_select("penyimpanan_uang","SUM(jumlah_uang) as uang_masuk, (SELECT SUM(total_penggunaan) FROM penggunaan_uang WHERE kode_tahanan = '$kode_tahanan' AND status = 'Received') as uang_keluar","kode_tahanan = '$kode_tahanan'","row");
$sisa = $sisa_uang->uang_masuk - $sisa_uang->uang_keluar;
if($sisa < 0){
    $sisa_display = '<span class="text-danger">-RP. '.number_format(abs($sisa),0,"",".").' (HUTANG)</span>';
}else{
    $sisa_display = "RP. ".number_format($sisa,0,"",".");
}
?>
<div class="row">
    <div class="col-12">
        <div class="w-100 text-right"><a href="<?= base_url("saku_wbp_monitor"); ?>" class="btn btn-sm btn-danger mb-2">Kembali</a></div>
        <div class="w-100 text-right"><h5>Uang saat ini : <?= $sisa_display; ?></h5></div>
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
                    $uang_masuk = $this->admin_model->get_data_select("penyimpanan_uang","id,tanggal,nama_pengirim,jumlah_uang","kode_tahanan = '$kode_tahanan' ORDER BY tanggal ASC","result");
                    
                    $load = '';
                    if(!empty($uang_masuk)){
                        foreach ($uang_masuk as $um) {
                            $rowspan = $this->admin_model->get_data_select("penggunaan_uang","COUNT(id) as rowspan","kode_tahanan = '$kode_tahanan' AND id_uang_masuk LIKE '%".$um->id."%' AND status = 'Received'","row");
                            $rowspan = $rowspan->rowspan+1;
                            if(!empty($rowspan)){
                                $rowspan = "rowspan = '".$rowspan."'";
                            }else{
                                $rowspan = "";
                            }
                            $pengeluaran = $this->admin_model->get_data_select("penggunaan_uang","tanggal,penggunaan,total_penggunaan,saldo_akhir,bukti","kode_tahanan = '$kode_tahanan' AND id_uang_masuk LIKE '%".$um->id."%' AND status = 'Received' ORDER BY tanggal ASC","result");
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
                                            $penggunaan .= '<ul class="m-0">';
                                            foreach ($value as $k => $v) {
                                                $penggunaan .= '<li>'.$k.' (Rp. '.number_format($v,0,"",".").')</li>';
                                            }
                                            $penggunaan .= '</ul>';
                                        }
                                    }
                                    $load_pengeluaran .= '
                                    <tr>
                                        <td style="border:1px solid;" class="text-center align-middle p-1">'.formatindo($pengeluaran->tanggal).'</td>
                                        <td style="border:1px solid;" class="align-middle p-1 pl-2"><div style="width:280px;">'.$penggunaan.'</div></td>
                                        <td style="border:1px solid;" class="text-center align-middle p-1">Rp. '.number_format($pengeluaran->total_penggunaan,0,"",".").'</td>
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
                                <td style="border:1px solid;" '.$rowspan.' class="text-center align-middle p-1">
                                    <a href="'.base_url("rincian_saku_wbp_tunai/".$kode_tahanan."?uangmasuk=".$um->id).'" class="btn btn-sm btn-info" style="font-size:6pt;">Rincian<br>Pengguaan<br>Tunai</a>
                                </td>
                                <td style="border:1px solid;" '.$rowspan.' class="text-center align-middle p-1">'.formatindo($um->tanggal).'</td>
                                <td style="border:1px solid;" '.$rowspan.' class="text-center align-middle p-1">'.$um->nama_pengirim.'</td>
                                <td style="border:1px solid;" '.$rowspan.' class="text-center align-middle p-1">Rp. '.number_format($um->jumlah_uang,0,"",".").'</td>
                                '.$load_pengeluaran.'
                            </tr>';
                        }
                    }
                    echo $load;
                    ?>
                </tbody>
            </table>
            <div class="w-100 text-right"><h5>Uang saat ini : <?= $sisa_display; ?></h5></div>
            <div class="w-100 text-right"><a href="<?= base_url("saku_wbp_monitor"); ?>" class="btn btn-sm btn-danger">Kembali</a></div>
        </div>
    </div>
</div>