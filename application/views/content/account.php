<div class="row">
    <div class="col-12">
        <div class="content pb-3" align="right">
            <a href="<?= base_url("ainput") ?>" class="btn btn-success"><i class="fas fa-plus"></i></a>
        </div>
        <table class="table table-bordered table-small" id="datatable">
            <thead class="thead-light bg-light-info">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Level</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($data_account as $data) {
                    ?>
                    <tr align="center" id="row_<?= $data->id; ?>">
                        <td><?= $no++; ?></td>
                        <td><?= $data->username; ?></td>
                        <td><?= $data->name ?></td>
                        <td><?= $data->level ?></td>
                        <td>
                            <a href="<?= base_url("account/".$data->id) ?>" data-toggle="tooltip" title="Edit"><i class="fas fa-edit icon-aksi pr-2"></i></a>

                            <a href="javascript:voi(0)" onclick="del_data(<?= $data->id; ?>)" data-toggle="tooltip" title="Hapus"><i class="fas fa-trash-alt text-danger icon-aksi pr-2"></i></a>

                            <a href="javascript:void(0)" data-toggle="modal" title="Rubah Password" data-target="#exampleModal" onclick="np(<?= $data->id ?>)"><i class="fas fa-key text-success icon-aksi pr-2"></i></a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rubah Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url("new_pw") ?>" method="post">
                    <input type="hidden" name="id_account" id="id_account" class="form-control" placeholder="Masukkan Password Baru">
                    <input type="password" name="new_pw" id="new_pw" class="form-control" placeholder="Masukkan Password Baru">
                    <div class="mt-2" align="right">
                        <a href="javascript:void(0)" class="btn btn-secondary" data-dismiss="modal">Tutup</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>