<div class="row">
    <div class="col-12">
        <div class="content pb-3" align="right">
            <a href="<?= base_url("tambah_barang_koperasi/0") ?>" class="btn btn-success"><i class="fas fa-plus"></i></a>
        </div>
        <table class="table table-bordered table-small" id="datatable">
            <thead class="thead-light bg-light-info">
                <tr>
                    <th>No</th>
                    <th>Foto Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $data_barang = $this->admin_model->get_data_select("data_barang_koperasi","*","id !=","result");
                if(!empty($data_barang)){
                        foreach ($data_barang as $data) {
                            if(!empty($data->foto_barang)){
                                $exp_barang = explode("/",$data->foto_barang);
                                if(file_exists(FCPATH."/upload/foto_barang_koperasi/".end($exp_barang))){
                                    $foto_barang = '<img src="'.$data->foto_barang.'" width="25%">';
                                }else{
                                    $foto_barang = '<img src="https://getstamped.co.uk/wp-content/uploads/WebsiteAssets/Placeholder.jpg" alt="barang koperasi" id="foto_barang" width="25%">';
                                }
                            }else{
                                $foto_barang = '<img src="https://getstamped.co.uk/wp-content/uploads/WebsiteAssets/Placeholder.jpg" alt="barang koperasi" id="foto_barang" width="25%">';
                            }
                              echo '
                              <tr align="center" id="row_'.$data->id.'">
                                  <td class="align-middle">'.$no++.'</td>
                                  <td class="align-middle">'.$foto_barang.'</td>
                                  <td class="align-middle"><div style="width:35rem;">'.$data->nama_barang.'</div></td>
                                  <td class="align-middle">Rp. '.number_format($data->harga,0,"",".").'</td>
                                  <td class="align-middle">
                                      <a href="'.base_url("tambah_barang_koperasi/".$data->id).'" data-toggle="tooltip" title="Edit"><i class="fas fa-edit icon-aksi pr-2"></i></a>
          
                                      <a href="javascript:void(0)" onclick="del_data('.$data->id.')" data-toggle="tooltip" title="Hapus"><i class="fas fa-trash-alt text-danger icon-aksi pr-2"></i></a>
                                  </td>
                              </tr>';
                        }
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