<div class="row">
    <div class="col-12">
        <div class="content pb-3" align="right">
            <a href="<?= base_url("warung_sipirman_ki") ?>" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-small" id="datatable">
                <thead class="thead-light bg-light-info">
                    <tr>
                        <th>No</th>
                        <th>Foto Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
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
                                    <td class="align-middle"><div style="width:20rem;">'.$data->nama_barang.'</div></td>
                                    <td class="align-middle">Rp. '.number_format($data->harga,0,"",".").'</td>
                                </tr>';
                            }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>