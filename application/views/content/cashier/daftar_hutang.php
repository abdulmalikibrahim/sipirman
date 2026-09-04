<div class="row">
    <div class="col-12">
        <div class="table-responsive">
        <table class="table table-bordered table-small" id="datatable">
            <thead class="thead-light bg-light-info">
                <tr>
                    <th>No</th>
                    <th>Kode WBP</th>
                    <th>Nama WBP</th>
                    <th>Hutang Saldo Digital</th>
                    <th>Hutang Uang Tunai</th>
                    <th>Total Hutang</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $semua_tahanan = $this->admin_model->get_data_select("tahanan","code_napi,nama","code_napi !=","result");
                if(!empty($semua_tahanan)){
                    foreach ($semua_tahanan as $tahanan) {
                        $saldo_digital = $this->admin_model->get_saldo_digital($tahanan->code_napi);
                        $saldo_tunai   = $this->admin_model->get_saldo_tunai($tahanan->code_napi);
                        if($saldo_digital < 0 || $saldo_tunai < 0){
                            $hutang_digital = $saldo_digital < 0 ? abs($saldo_digital) : 0;
                            $hutang_tunai   = $saldo_tunai < 0 ? abs($saldo_tunai) : 0;
                            $total_hutang   = $hutang_digital + $hutang_tunai;
                            echo '
                            <tr align="center">
                                <td class="align-middle">'.$no++.'</td>
                                <td class="align-middle">'.$tahanan->code_napi.'</td>
                                <td class="align-middle">'.$tahanan->nama.'</td>
                                <td class="align-middle text-danger" data-order="'.$hutang_digital.'">'.($hutang_digital > 0 ? 'Rp. '.number_format($hutang_digital,0,"",".") : '-').'</td>
                                <td class="align-middle text-danger" data-order="'.$hutang_tunai.'">'.($hutang_tunai > 0 ? 'Rp. '.number_format($hutang_tunai,0,"",".") : '-').'</td>
                                <td class="align-middle text-danger font-weight-bold" data-order="'.$total_hutang.'">Rp. '.number_format($total_hutang,0,"",".").'</td>
                                <td class="align-middle">
                                    <a href="javascript:void(0)" onclick="lihat_detail_hutang(\''.$tahanan->code_napi.'\',\''.$tahanan->nama.'\')" class="btn btn-sm btn-info"><i class="fas fa-eye mr-1"></i>Detail</a>
                                </td>
                            </tr>';
                        }
                    }
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<!-- Modal Detail Hutang -->
<div class="modal fade" id="modalDetailHutang" tabindex="-1" role="dialog" aria-labelledby="modalDetailHutangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailHutangLabel">Rincian Hutang</h5>
                <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                <table class="table table-sm table-bordered" style="font-size:9pt;">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Belanja</th>
                            <th class="text-center">Saldo Awal</th>
                            <th class="text-center">Jumlah Hutang</th>
                            <th class="text-center">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody id="body-detail-hutang"></tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
