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
                        <th>Stok</th>
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
                                            $foto_barang = '<img src="data:image/svg+xml,<svg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20200%20150%22><rect%20width=%22200%22%20height=%22150%22%20fill=%22%23f3f4f8%22/><g%20fill=%22none%22%20stroke=%22%23c7cbd4%22%20stroke-width=%224%22><rect%20x=%2255%22%20y=%2240%22%20width=%2290%22%20height=%2265%22%20rx=%226%22/><circle%20cx=%2280%22%20cy=%2263%22%20r=%228%22/><path%20d=%22M55%2095l25-25%2020%2018%2015-15%2030%2030%22/></g><text%20x=%22100%22%20y=%22128%22%20font-family=%22sans-serif%22%20font-size=%2213%22%20fill=%22%239aa4b8%22%20text-anchor=%22middle%22>No%20Image</text></svg>" alt="barang koperasi" id="foto_barang" width="25%">';
                                        }
                                    }else{
                                        $foto_barang = '<img src="data:image/svg+xml,<svg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20200%20150%22><rect%20width=%22200%22%20height=%22150%22%20fill=%22%23f3f4f8%22/><g%20fill=%22none%22%20stroke=%22%23c7cbd4%22%20stroke-width=%224%22><rect%20x=%2255%22%20y=%2240%22%20width=%2290%22%20height=%2265%22%20rx=%226%22/><circle%20cx=%2280%22%20cy=%2263%22%20r=%228%22/><path%20d=%22M55%2095l25-25%2020%2018%2015-15%2030%2030%22/></g><text%20x=%22100%22%20y=%22128%22%20font-family=%22sans-serif%22%20font-size=%2213%22%20fill=%22%239aa4b8%22%20text-anchor=%22middle%22>No%20Image</text></svg>" alt="barang koperasi" id="foto_barang" width="25%">';
                                    }
                                echo '
                                <tr align="center" id="row_'.$data->id.'">
                                    <td class="align-middle">'.$no++.'</td>
                                    <td class="align-middle">'.$foto_barang.'</td>
                                    <td class="align-middle"><div style="width:20rem;">'.$data->nama_barang.'</div></td>
                                    <td class="align-middle">Rp. '.number_format($data->harga,0,"",".").'</td>
                                    <td class="align-middle '.($data->stok <= 0 ? "text-danger font-weight-bold" : "").'">'.($data->stok <= 0 ? "Habis" : (int)$data->stok).'</td>
                                </tr>';
                            }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>