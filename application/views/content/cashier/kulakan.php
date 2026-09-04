<div class="row">
    <div class="col-12">
        <div class="content pb-3" align="right">
            <a href="<?= base_url("kulakan_input") ?>" class="btn btn-success"><i class="fas fa-plus"></i> Input Kulakan</a>
        </div>
        <div class="table-responsive">
        <table class="table table-bordered table-small" id="datatable">
            <thead class="thead-light bg-light-info">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Total Biaya</th>
                    <th>Dokumentasi</th>
                    <th>Input Oleh</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $data_kulakan = $this->admin_model->get_data_select("kulakan","*","id !=","result");
                if(!empty($data_kulakan)){
                    foreach ($data_kulakan as $data) {
                        if(!empty($data->dokumentasi)){
                            $dokumentasi = '<a href="'.$data->dokumentasi.'" target="_blank"><img src="'.$data->dokumentasi.'" width="60"></a>';
                        }else{
                            $dokumentasi = '-';
                        }
                        echo '
                        <tr align="center" id="row_'.$data->id.'">
                            <td class="align-middle">'.$no++.'</td>
                            <td class="align-middle">'.date("d-m-Y H:i",strtotime($data->tanggal)).'</td>
                            <td class="align-middle">Rp. '.number_format($data->total_biaya,0,"",".").'</td>
                            <td class="align-middle">'.$dokumentasi.'</td>
                            <td class="align-middle">'.$data->input_by.'</td>
                            <td class="align-middle">
                                <a href="javascript:void(0)" onclick="lihat_detail('.$data->id.')" data-toggle="tooltip" title="Lihat Detail"><i class="fas fa-eye icon-aksi pr-2"></i></a>
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
</div>

<!-- Modal Detail Kulakan -->
<div class="modal fade" id="modalDetailKulakan" tabindex="-1" role="dialog" aria-labelledby="modalDetailKulakanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailKulakanLabel">Detail Barang Kulakan</h5>
                <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Harga Tengkulak</th>
                            <th class="text-center">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="body-detail-kulakan"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
