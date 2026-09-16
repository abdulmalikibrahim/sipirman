<div class="card">
    <div class="card-header">
        <label for="">Top Up Saldo WBP Mandiri</label>
    </div>
    <div class="card-body p-3">
        <div class="row">
            <div class="col-lg-4">
                <label>Pilih WBP</label>
                <datalist id="nama-tahanan">
                    <?php
                    $all_tahanan = $this->admin_model->get_data_select("tahanan","*","code_napi !=","result");
                    foreach ($all_tahanan as $tahanan) {
                        $saldo_digital = $this->admin_model->get_saldo_digital($tahanan->code_napi);
                    ?>
                    <option value="<?= $tahanan->nama; ?>" data-code="<?= $tahanan->code_napi; ?>" data-saldo="<?= $saldo_digital; ?>">
                    <?php
                    }
                    ?>
                </datalist>
                <input list="nama-tahanan" oninput="cari_wbp(this)" type="text" class="form-control" id="code_tahanan_search" placeholder="Ketik untuk cari WBP" autocomplete="on">
                <input type="hidden" id="kode_tahanan" value="">
            </div>
            <div class="col-lg-4 pt-4">
                <label id="lbl-nama-wbp" class="m-0"></label>
            </div>
            <div class="col-lg-4 pt-4">
                <label id="lbl-saldo-wbp" class="m-0"></label>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-4">
                <label>Nominal Top Up</label>
                <input type="text" class="form-control" id="nominal" oninput="format_nominal(this)" placeholder="Nominal Top Up">
            </div>
        </div>
        <div class="w-100 text-right mt-3">
            <a href="javascript:void(0)" onclick="proses_topup()" class="btn btn-info"><i class="fas fa-wallet mr-2"></i>Simpan</a>
        </div>
    </div>
</div>

<!-- MODIFIKASI: Histori Top Up Saldo (Tgl, Nama WBP, Nominal) -->
<div class="card mt-3">
    <div class="card-header">
        <label for="" class="m-0">Histori Top Up Saldo</label>
    </div>
    <div class="card-body p-2">
        <div class="table-responsive">
            <table class="table table-bordered table-small" id="datatable">
                <thead class="thead-light bg-light-info">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama WBP</th>
                        <th>Nominal</th>
                        <th>Diinput Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $riwayat_topup = $this->admin_model->get_data_select("penyimpanan_uang","*","sumber_dana = 'Mandiri' ORDER BY tanggal DESC","result");
                    if(!empty($riwayat_topup)){
                        foreach ($riwayat_topup as $rt) {
                            echo '
                            <tr>
                                <td class="align-middle" align="center">'.$no++.'</td>
                                <td class="align-middle" align="center">'.date("d-M-Y H:i:s",strtotime($rt->tanggal)).'</td>
                                <td class="align-middle">'.$rt->nama_tahanan.'</td>
                                <td class="align-middle" align="center">Rp. '.number_format($rt->jumlah_uang,0,"",".").'</td>
                                <td class="align-middle">'.$rt->input_by.'</td>
                            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- FIX #2: Modal PIN untuk konfirmasi top up (sama seperti modal PIN di halaman Cashier) -->
<div class="modal fade" id="modalPin" tabindex="-1" role="dialog" aria-labelledby="modalPinLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="modalPinLabel"><i class="fas fa-lock mr-2"></i>Masukkan PIN</h5>
                        <a href="javascript:void(0)" class="close" onclick="tutup_modal_pin()" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                        </a>
                  </div>
                  <div class="modal-body text-center">
                        <p class="text-muted mb-3" style="font-size:13px;">Masukkan PIN 6 digit milik WBP untuk konfirmasi top up saldo mandiri</p>

                        <div class="d-flex justify-content-center mb-3" id="pin-dots">
                              <span class="pin-dot mx-1" id="dot-1"></span>
                              <span class="pin-dot mx-1" id="dot-2"></span>
                              <span class="pin-dot mx-1" id="dot-3"></span>
                              <span class="pin-dot mx-1" id="dot-4"></span>
                              <span class="pin-dot mx-1" id="dot-5"></span>
                              <span class="pin-dot mx-1" id="dot-6"></span>
                        </div>

                        <input type="password" id="input-pin" maxlength="6" class="form-control text-center mb-3"
                               style="letter-spacing:8px; font-size:18px; width:160px; margin:auto;"
                               placeholder="••••••" oninput="update_pin_dots(this)" autocomplete="off">

                        <p id="pin-error-msg" class="text-danger" style="font-size:12px; display:none;"><i class="fas fa-exclamation-circle mr-1"></i>PIN salah, coba lagi.</p>

                        <div id="pin-numpad" class="mt-2">
                              <div class="row no-gutters justify-content-center mb-1">
                                    <div class="col-4 p-1"><button class="btn btn-outline-secondary btn-block pin-key" onclick="pin_key('1')">1</button></div>
                                    <div class="col-4 p-1"><button class="btn btn-outline-secondary btn-block pin-key" onclick="pin_key('2')">2</button></div>
                                    <div class="col-4 p-1"><button class="btn btn-outline-secondary btn-block pin-key" onclick="pin_key('3')">3</button></div>
                              </div>
                              <div class="row no-gutters justify-content-center mb-1">
                                    <div class="col-4 p-1"><button class="btn btn-outline-secondary btn-block pin-key" onclick="pin_key('4')">4</button></div>
                                    <div class="col-4 p-1"><button class="btn btn-outline-secondary btn-block pin-key" onclick="pin_key('5')">5</button></div>
                                    <div class="col-4 p-1"><button class="btn btn-outline-secondary btn-block pin-key" onclick="pin_key('6')">6</button></div>
                              </div>
                              <div class="row no-gutters justify-content-center mb-1">
                                    <div class="col-4 p-1"><button class="btn btn-outline-secondary btn-block pin-key" onclick="pin_key('7')">7</button></div>
                                    <div class="col-4 p-1"><button class="btn btn-outline-secondary btn-block pin-key" onclick="pin_key('8')">8</button></div>
                                    <div class="col-4 p-1"><button class="btn btn-outline-secondary btn-block pin-key" onclick="pin_key('9')">9</button></div>
                              </div>
                              <div class="row no-gutters justify-content-center mb-1">
                                    <div class="col-4 p-1"><button class="btn btn-outline-danger btn-block pin-key" onclick="pin_hapus()"><i class="fas fa-backspace"></i></button></div>
                                    <div class="col-4 p-1"><button class="btn btn-outline-secondary btn-block pin-key" onclick="pin_key('0')">0</button></div>
                                    <div class="col-4 p-1"><button class="btn btn-success btn-block pin-key" onclick="konfirmasi_topup()"><i class="fas fa-check"></i></button></div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<style>
.pin-dot {
      width: 16px;
      height: 16px;
      border-radius: 50%;
      border: 2px solid #aaa;
      display: inline-block;
      background: transparent;
      transition: background 0.15s;
}
.pin-dot.filled {
      background: #17a2b8;
      border-color: #17a2b8;
}
.pin-dot.error {
      background: #dc3545;
      border-color: #dc3545;
}
.pin-key {
      font-size: 16px;
      height: 44px;
}
</style>
