<div class="row">
    <div class="col-12">
        <div class="content pb-3" align="right">
            <a href="<?= base_url("pinput") ?>" class="btn btn-success"><i class="fas fa-plus"></i></a>
        </div>
        <div class="table-responsive">
            <span class="text-danger">Note : Tekan CTRL + F5 jika gambar tidak berubah</span>
            <table class="table table-bordered table-small" id="datatable">
                <thead class="thead-light bg-light-info">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>HP</th>
                        <th>Nama WBP</th>
						<th>Keluarga Inti</th>
                        <th>Foto Diri</th>
                        <th>Foto KTP</th>
                        <th>Foto KK</th>
                        <th>Lain-Lain</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($data_titipan as $data) {
                        if(!empty($data->foto)){
                            $foto = '<a href="'.base_url("upload/foto_diri/".$data->foto."?t=".time()).'" target="_blank">Lihat Foto</a>';
                        }else{
                            $foto = '<span class="text-danger font-weight-bold">No Foto</span>';
                        }
                        if(!empty($data->foto_ktp)){
                            $foto_ktp = '<a href="'.base_url("upload/ktp/".$data->foto_ktp."?t=".time()).'" target="_blank">Lihat Foto</a>';
                        }else{
                            $foto_ktp = '<span class="text-danger font-weight-bold">No Foto</span>';
                        }
                        if(!empty($data->foto_kk)){
                            $foto_kk = '<a href="'.base_url("upload/kk/".$data->foto_kk."?t=".time()).'" target="_blank">Lihat Foto</a>';
                        }else{
                            $foto_kk = '<span class="text-danger font-weight-bold">No Foto</span>';
                        }
                        if(!empty($data->foto_pernyataan)){
                            $foto_pernyataan = '<a href="'.base_url("upload/super/".$data->foto_pernyataan."?t=".time()).'" target="_blank">Lihat Foto</a>';
                        }else{
                            $foto_pernyataan = '<span class="text-danger font-weight-bold">No Foto</span>';
                        }
                        ?>
                        <tr align="center" id="row_<?= $data->id; ?>">
                            <?php
                            $nama_wbp = "";
                            $encode_wbp = json_decode($data->nama_wbp,TRUE);
                            if(is_array($encode_wbp)){
                                $nama_wbp = implode(", ",$encode_wbp);
                            }else{
                                $nama_wbp = $data->nama_wbp;
                            }

                            if(!empty($data->keluarga_inti)){
                                $keluarga_inti = '<span class="badge badge-success">Ya</span>';
                            }else{
                                $keluarga_inti = '<span class="badge badge-danger">Tidak</span>';
                            }
                            ?>
                            <td class="align-middle"><?= $no++; ?></td>
                            <td class="align-middle"><?= $data->nama; ?></td>
                            <td class="align-middle"><?= $data->nik; ?></td>
                            <td class="align-middle"><?= $data->hp; ?></td>
                            <td class="align-middle"><?= $nama_wbp; ?></td>
							<td class="align-middle"><?= $keluarga_inti; ?></td>
                            <td class="align-middle"><?= $foto ?></td>
                            <td class="align-middle"><?= $foto_ktp ?></td>
                            <td class="align-middle"><?= $foto_kk ?></td>
                            <td class="align-middle"><?= $foto_pernyataan ?></td>
                            <td class="align-middle">
                                <a href="<?= base_url("penitip/".$data->id); ?>" data-toggle="tooltip" title="Edit"><i class="fas fa-edit icon-aksi pr-2"></i></a>
                                <a href="javascript:void(0)" onclick="del_data(<?= $data->id; ?>)" data-toggle="tooltip" title="Hapus"><i class="fas fa-trash-alt icon-aksi pr-2 text-danger"></i></a>
                            </td>
							
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>