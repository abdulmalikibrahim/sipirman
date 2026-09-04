<div class="row">
    <div class="col-12">
        <div class="content pb-3" align="right">
            <a href="<?= base_url("tahanan_input/input") ?>" class="btn btn-success"><i class="fas fa-plus"></i></a>
        </div>
        <div class="table-responsive">
            <span class="text-danger">Note : Tekan CTRL + F5 jika gambar tidak berubah</span>
            <table class="table table-bordered table-small" id="datatable">
                <thead class="thead-light bg-light-info">
                    <tr>
                        <th>No</th>
                        <th>Code NAPI</th>
                        <th>Nama</th>
                        <th>Nama Ayah</th>
                        <th>Jenis Kelamin</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($data_tahanan as $data) {
                        ?>
                        <tr align="center" id="row_<?= $data->id; ?>">
                            <td><?= $no++; ?></td>
                            <td><?= $data->code_napi; ?></td>
                            <td><?= $data->nama; ?></td>
                            <td><?= $data->nama_ayah; ?></td>
                            <td><?= $data->jenis_kelamin; ?></td>
                            <td>
                                <a href="<?= base_url("tahanan_input/".$data->id); ?>" data-toggle="tooltip" title="Edit"><i class="fas fa-edit icon-aksi pr-2"></i></a>
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