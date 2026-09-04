<?php
$kode_tahanan = $this->p2;
$iduangmasuk = $this->input->get("uangmasuk");
$detailpenyimpananuang = $this->admin_model->get_data_select("penyimpanan_uang","nama_pengirim,tanggal","id = '$iduangmasuk'","row");
$pengirim = $detailpenyimpananuang->nama_pengirim;
$tanggal_simpan = formatindo($detailpenyimpananuang->tanggal);
$jumlah_uang = "Rp. ".number_format($this->input->get("i"),0,"",".");
?>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body p-2">
                <form action="<?= base_url("penyerahan_uang_simpan?uangmasuk=".$iduangmasuk."&i=".$this->input->get("i")) ?>" method="post" enctype="multipart/form-data" id="form-submit">
                    <input type="text" name="kode_tahanan" value="<?= $kode_tahanan; ?>" hidden>
                    <div class="row">
                        <div class="col-lg-5">Pengirim</div>
                        <div class="col-lg-7"><?= $pengirim; ?></div>
                        <div class="col-lg-5">Tanggal</div>
                        <div class="col-lg-7"><?= str_replace("<br>",", ",$tanggal_simpan); ?></div>
                        <div class="col-5 bg-info text-light pt-2 pb-2">Sisa Uang</div>
                        <div class="col-7 bg-info text-light pt-2 pb-2"><?= $jumlah_uang; ?></div>
                        <div class="col-lg-12"><h4 class="mt-2 mb-2">Penyerahan</h4></div>
                        <div class="col-lg-12">
                            <div class="input-group">
                                <input type="text" class="form-control harga w-50" id="diserahkan" name="diserahkan" placeholder="Jumlah yang ingin diserahkan" required>
                                <input type="text" class="form-control" style="width:10px !important;" value="Rupiah" readonly>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label for="" class="mt-2">Bukti Penyerahan</label>
                            <input type="file" name="bukti" id="bukti" accept=".png, .jpg" hidden required>
                            <img src="https://getstamped.co.uk/wp-content/uploads/WebsiteAssets/Placeholder.jpg" alt="Bukti Penyerahan" id="foto_bukti" width="100%">
                        </div>
                        <div class="col-lg-12 mt-2 text-right">
                            <a href="<?= base_url("rincian_uang/".$this->p2); ?>" class="btn btn-sm btn-danger">Kembali</a>
                            <a href="javascript:void(0)" id="btnsimpan" class="btn btn-sm btn-info">Simpan</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-2"><h4>Rincian Penggunaan Uang Tunai</h4></div>
            <div class="card-body p-2">
                <div class="row mb-2">
                    <div class="col-lg-6" id="uangdiwbp">Uang di WBP : </div>
                    <div class="col-lg-6 text-right"><a href="javascript:void(0)" onclick="transaksiterbaru()" class="btn btn-sm btn-warning">Penyerahan Terbaru</a></div>
                </div>
            </div>
            <div class="card-body p-2" style="height:483px; max-height:483px; overflow:auto;" id="body-rincian-uang-tunai">
                <table class="table table-small table-bordered mb-0" style="font-size:6pt;" id="table-rincian">
                    <thead class="thead-light">
                        <tr id="start-rincian">
                            <th colspan="3" style="border:1px solid #000;" class="bg-info text-light p-1">Penyerahan Tunai</th>
                            <th colspan="4" style="border:1px solid #000;" class="bg-info text-light p-1">Penggunaan Tunai Di Warung SIPIRMAN</th>
                            <th rowspan="2" style="border:1px solid #000;" class="align-middle bg-info text-light p-1">Bukti</th>
                        </tr>
                        <tr>
                            <th class="text-light p-1" style="border:1px solid #000; background-color: #5bc0de!important;">Hari, Tanggal</th>
                            <th class="text-light p-1" style="border:1px solid #000; background-color: #5bc0de!important;">Penitip</th>
                            <th class="text-light p-1" style="border:1px solid #000; background-color: #5bc0de!important;">Jumlah</th>
                            <th class="text-light p-1" style="border:1px solid #000; background-color: #5bc0de!important;">Hari, Tanggal</th>
                            <th class="text-light p-1" style="border:1px solid #000; background-color: #5bc0de!important;">Daftar Belanja</th>
                            <th class="text-light p-1" style="border:1px solid #000; background-color: #5bc0de!important;">Total</th>
                            <th class="text-light p-1" style="border:1px solid #000; background-color: #5bc0de!important;">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $load = '';
                        $total_belanja = 0;
                        $total_penyerahan = 0;
                        $pum = $this->admin_model->get_data_select("penggunaan_uang","id,tanggal,total_penggunaan","id_uang_masuk = '".$iduangmasuk."' AND penggunaan LIKE '%Diserahkan Tunai Ke WBP%' AND kode_tahanan = '$kode_tahanan' AND status = 'Received'","result");
                        if(!empty($pum)){
                            $no_pum = 1;
                            $countpum = count($pum);
                            foreach ($pum as $pum) {
                                $belanja = $this->admin_model->get_data_select("belanja_uang_tunai","*","id_penyerahan = '".$pum->id."' AND kode_tahanan = '$kode_tahanan'","result");
                                $total_count_belanja = 0;
                                if(!empty($belanja)){
                                    $load_belanja = '';
                                    $total_belanja = 0;
                                    $total_penyerahan = 0;
                                    foreach ($belanja as $belanja) {
                                        $detail_belanja = '';
                                        $totalbelanjaperpenyerahan = 0;
                                        $data_belanja = json_decode($belanja->data_belanja,true);
                                        // echo $belanja->id."<br>".print_r($data_belanja);
                                        $count_data_belanja = count($data_belanja);
                                        $total_count_belanja += $count_data_belanja;
                                        if($count_data_belanja > 1){
                                            $rowspan = "rowspan = '".($count_data_belanja)."'";
                                            $no = 1;
                                            foreach ($data_belanja as $key => $value) {
                                                $total_belanja += $value["harga"];
                                                $totalbelanjaperpenyerahan += $value["harga"];
                                                if($no <= 1){
                                                    $bukti = '<td style="border:1px solid #000;" class="p-1 align-middle text-center" rowspan="'.$count_data_belanja.'"><a href="'.$belanja->bukti.'" style="text-decoration:none;" target="_blank">Lihat Bukti</a></td>';
                                                    $detail_belanja .= '
                                                    <td style="border:1px solid #000;" class="p-1 align-middle text-center">'.$key.'</td>
                                                    <td style="border:1px solid #000;" class="p-1 align-middle text-center" rowspan="'.$count_data_belanja.'">Rp. '.number_format($belanja->penggunaan,0,"",".").'</td>
                                                    <td style="border:1px solid #000;" class="p-1 align-middle text-center" rowspan="'.$count_data_belanja.'">Rp. '.number_format($belanja->total_sisa,0,"",".").'</td>
                                                    '.$bukti;
                                                }else{
                                                    $bukti = '';
                                                    $detail_belanja .= '
                                                    <tr>
                                                        <td style="border:1px solid #000;" class="p-1 align-middle text-center">'.$key.'</td>
                                                        '.$bukti.'
                                                    </tr>';
                                                }
                                                $no++;
                                            }
                                        }else{
                                            $rowspan = "";
                                            foreach ($data_belanja as $key => $value) {
                                                $detail_belanja .= '
                                                <td style="border:1px solid #000;" class="p-1 align-middle text-center">'.$key.'</td>
                                                <td style="border:1px solid #000;" class="p-1 align-middle text-center">Rp. '.number_format($belanja->penggunaan,0,"",".").'</td>
                                                <td style="border:1px solid #000;" class="p-1 align-middle text-center">Rp. '.number_format($belanja->total_sisa,0,"",".").'</td>
                                                <td style="border:1px solid #000;" class="p-1 align-middle text-center"><a href="'.$belanja->bukti.'" style="text-decoration:none;" target="_blank">Lihat Bukti</a></td>';
                                            }
                                        }
                                        $load_belanja .= '
                                        <tr>
                                            <td style="border:1px solid #000;" class="p-1 align-middle text-center" '.$rowspan.'>'.formatindo($belanja->tanggal).'</td>
                                            '.$detail_belanja.'
                                        </tr>';
                                    }
                                }else{
                                    $load_belanja = '<td style="border:1px solid #000;" class="p-1 align-middle text-center" colspan="5" class="text-center align-middle">Belum ada riwayat belanja</td>';
                                }

                                if($total_count_belanja > 1){
                                    $rowspan = "rowspan = '".($total_count_belanja+1)."'";
                                }else{
                                    $rowspan = "";
                                }

                                if($no_pum >= $countpum){
                                    $transaksiterbaru = "transaksi-terbaru";
                                }else{
                                    $transaksiterbaru = "";
                                }
                                $load .= '
                                <tr id="'.$transaksiterbaru.'">
                                    <td style="border:1px solid #000;" class="p-1 align-middle text-center" '.$rowspan.'>'.formatindo($pum->tanggal).'</td>
                                    <td style="border:1px solid #000;" class="p-1 align-middle text-center" '.$rowspan.'>'.$pengirim.'</td>
                                    <td style="border:1px solid #000;" class="p-1 align-middle text-center" '.$rowspan.'>Rp. '.number_format($pum->total_penggunaan,0,"",".").'</td>'.$load_belanja.'
                                </tr>';
                                $total_penyerahan += $pum->total_penggunaan;
                                $no_pum++;
                            }
                            $uangdipegang = $total_penyerahan - $total_belanja;
                        }else{
                            $load .= '<tr id="transaksi-terbaru"><td colspan="8" class="text-center">Belum ada riwayat penggunaan</td></tr>';
                        }
                        echo $load;
                        ?>
                    </tbody>
                </table>
                <label id="uang-dipegang" hidden><?= $uangdipegang; ?></label>
                <div class="w-100 text-right mt-2">
                    <a href="javascript:void(0)" class="btn btn-sm btn-warning" id="arrow-up" onclick="totop()"><i class="fas fa-arrow-up"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>