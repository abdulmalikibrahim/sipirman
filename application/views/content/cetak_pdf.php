<table width="100%">
    <tr>
        <td valign="top">
            <img src="<?php echo base_url('uploads/logo-orgs/kop-'.$data_org->logo_org); ?>" width="95px">
        </td>
        <td style="text-align:center; padding-left:-120px;">
            <h3><b><?php echo $data_org->link_instansi_pusat."<br>".$data_org->name_kantor; if(!empty($data_org->name_kantor)){ echo "<br>"; } ?><?php echo strtoupper($data_org->name); ?></b></h3>
            <p><p style="font-size:8pt;"><?php echo $data_org->alamat_org; ?>, Telp : <?php echo $data_org->telp_org; ?>, Fax : <?php echo $data_org->fax_org; ?>, Kode POS : <?php echo $data_org->kode_pos_org; ?>, <br>Website : <?php echo $data_org->url_site; ?>, Email : <?php echo $data_org->email_org; ?></p>
        </td>
    </tr>
</table>
<hr style="border-top: 3px solid black; margin-top:2px;">
<h3 style="text-align:center"><u>SURAT IJIN MENGUNJUNGI TAHANAN <?php echo strtoupper($data_org_target->name); ?></u></h3>
                  <!-------------------------------------- Convert To Romawi -------------------------------------->
                  <?php
                    function getRomawi($bln){
                        switch ($bln){
                            case 1: 
                                return "I";
                                break;
                            case 2:
                                return "II";
                                break;
                            case 3:
                                return "III";
                                break;
                            case 4:
                                return "IV";
                                break;
                            case 5:
                                return "V";
                                break;
                            case 6:
                                return "VI";
                                break;
                            case 7:
                                return "VII";
                                break;
                            case 8:
                                return "VIII";
                                break;
                            case 9:
                                return "IX";
                                break;
                            case 10:
                                return "X";
                                break;
                            case 11:
                                return "XI";
                                break;
                            case 12:
                                return "XII";
                                break;
                        }
                    }
                    $month_romawi = getRomawi(date('m',strtotime($data_surat->created_at)));
                  ?>
                  <h3 style="text-align:center"><u><b>No.: SIMITA.RTNRBG/<?php echo str_pad($data_surat->no_surat,2,"0",STR_PAD_LEFT)."/".$month_romawi."/".date('Y',strtotime($data_surat->created_at))."/".$data_org->kode_surat; ?></b></u></h3>
<p class="fs-xl"><p style="text-indent:45px">Yang bertanda tangan di bawah ini <?php echo $tanda_tangan->jabatan_pimpinan; ?> memberikan ijin kepada Pengunjung untuk mengunjungi Tahanan dengan data sebagai berikut :</p>
<ul>
    <table width="100%" style="font-size:12pt;">
        <tr>
            <td width="400px">
                <table>
                    <tr>
                        <td colspan="3"><b>DATA PENGUNJUNG</b></td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td width="200px">Nama Pengujung</td>
                        <td>:</td>
                        <td width="300px"><?php echo $data_surat->pemohon_nama; ?></td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>NIK</td>
                        <td>:</td>
                        <td><?php echo $data_surat->pemohon_nik; ?></td>
                    </tr>
                    <tr>
                        <td valign="top">3.</td>
                        <td valign="top">Alamat</td>
                        <td valign="top">:</td>
                        <td><?php echo $data_surat->pemohon_alamat; ?></td>
                    </tr>
                    <tr>
                        <td valign="top">4.</td>
                        <td>Hubungan dengan Tahanan</td>
                        <td valign="top">:</td>
                        <td valign="top"><?php echo $data_surat->hubungan_tahanan; ?></td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td><b>Foto Pengunjung</b></td>
                    </tr>
                    <tr>
                        <td><img src="<?php echo base_url('uploads/foto-pemohon/'.$data_surat->pemohon_foto); ?>" width="110px" height="110px"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" style="font-size:12pt;">
        <tr>
            <td width="400px">
                <table>
                    <tr>
                        <td colspan="3">
                            <b>DATA TAHANAN</b>
                        </td>
                    </tr>
                    <tr>
                        <td>1.</td>
                        <td width="200px">Nama Tahanan</td>
                        <td>:</td>
                        <td width="300px"><?php echo $data_tahanan->nama_tahanan; ?></td>
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td width="200px">Umur</td>
                        <td>:</td>
                        <td><?php echo $data_tahanan->umur." Tahun"; ?></td>
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td width="200px">Pekerjaan</td>
                        <td>:</td>
                        <td><?php echo $data_tahanan->pekerjaan; ?></td>
                    </tr>
                    <tr>
                        <td valign="top">4.</td>
                        <td width="200px" valign="top">Alamat</td>
                        <td valign="top">:</td>
                        <td><?php echo $data_tahanan->alamat; ?></td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Perkara (Pasal)</td>
                        <td>:</td>
                        <td><?php echo $data_tahanan->pasal_tuduhan; ?></td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td><b>Foto KTP</b></td>
                    </tr>
                    <tr>
                        <td><img src="<?php echo base_url('uploads/ktp/'.$data_surat->pemohon_ktp); ?>" width="170px" height="110px"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <li></li>
    <table width="100%" style="font-size:12pt;">
        <tr>
            <td width="400px" valign="top">
                <table>
                    <tr>
                        <td colspan="3">
                            <b>MASA BERLAKU SURAT</b>
                        </td>
                    </tr>
                    <tr>
                        <td width="520px"><?php echo date_indo($data_surat->mulai_masa_berlaku); ?> s.d. <?php echo date_indo($data_surat->sampai_masa_berlaku); ?> </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table>
                    <tr>
                        <td><b>Foto Tahanan</b></td>
                    </tr>
                    <tr>
                        <td><img src="<?php echo base_url('uploads/tahanan/'.$data_tahanan->foto); ?>" width="110px" height="110px"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</ul>
<p style="text-indent:45px">Yang bersangkutan ditahan di Rutan Rembang karena diduga melakukan tidak pidana sebagaimana tersebut di atas.</b>
<p style="text-indent:45px">Demikian surat keterangan ini dibuat dengan sebenarnya dan dapat dipergunakan seperlunya. Atas perhatian dan kerjasamanya kami ucapkan terimakasih.</p>
<table width="100%" border="0">
    <tr>
        <td width="320px" valign="bottom">
            <img src="<?php echo base_url('uploads/qrcode/'.$data_surat->qrcode_foto); ?>" alt="" width="50px">
        </td>
        <td>
        </td>
        <td>
            <img src="<?php echo base_url('uploads/qrcode_ttd/'.$tanda_tangan->id.'.png'); ?>" alt="" width="130px">
        </td>
        <td>
            <?php echo $web->kabupaten_web; ?>, <?php echo date_indo(date('Y-m-d')); ?><br>
            <?php echo $tanda_tangan->jabatan; ?>
            <br>
            <?php
                if($tanda_tangan->sebagai == '-'){
                    echo "";
                }else{
                    echo $tanda_tangan->sebagai;
                }
            ?>
            <br>
            <br>
            Ttd.
            <br>
            <br>
            <u><?php echo $tanda_tangan->nama; ?></u><br>
            <?php echo $tanda_tangan->nip_baru; ?>
        </td>
    </tr>
</table>