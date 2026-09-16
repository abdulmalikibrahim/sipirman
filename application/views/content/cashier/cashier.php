<div class="card">
      <div class="card-header">
            <label for="">Check Keuangan WBP</label>
            <div class="row">
                  <div class="col-lg-3">
                        <datalist id="nama-tahanan">
                              <?php 
                              $all_tahanan = $this->admin_model->get_data_select("tahanan","*","code_napi !=","result");
                              foreach ($all_tahanan as $tahanan) {
                              ?>
                              <option value="<?= $tahanan->nama; ?>" data-code="<?= $tahanan->code_napi; ?>">
                              <?php
                              }
                              ?>
                        </datalist>
                        <!-- FIX #1: Hapus autocomplete="off" agar datalist bisa tampil, ganti ke oninput -->
                        <input list="nama-tahanan" oninput="check_keuangan_wbp(this)" type="text" class="form-control" name="code_tahanan_search" id="code_tahanan_search" placeholder="Ketik untuk cari WBP" autocomplete="on">
                  </div>
                  <div class="col-lg-3 pt-2">
                        <label id="lbl-nama-wbp" class="m-0"></label>
                  </div>
                  <div class="col-lg-3 pt-lg-2">
                        <label id="lbl-uang-digital-wbp" class="m-0"></label>
                  </div>
                  <div class="col-lg-3 pt-lg-2">
                        <label id="lbl-uang-manual-wbp" class="m-0"></label>
                  </div>
            </div>
      </div>
      <div class="card-body p-3">
            <div class="row">
                  <div class="col-lg-12">
                        <p class="text-sm">Masukkan Barang</p>
                        <datalist id="data_barang">
                              <?php
                              $data_barang = $this->admin_model->get_data_select("data_barang_koperasi","nama_barang,harga","id !=","result");
                              if(!empty($data_barang)){
                                    foreach ($data_barang as $db) {
                                          echo '<option data-harga="'.$db->harga.'" value="'.$db->nama_barang.'">'.$db->nama_barang.'</option>';
                                    }
                              }
                              ?>
                        </datalist>
                        <div class="table-responsive">
                              <table class="table table-bordered table-sm">
                                    <thead class="thead-light">
                                          <tr>
                                                <th class="text-center"><div style="width:10rem;">Kode Barang</div></th>
                                                <th><div style="width:25rem;" class="text-center">Nama Barang</div></th>
                                                <th><div style="width:5rem;" class="text-center">Stok</div></th>
                                                <th><div style="width:5rem;" class="text-center">Qty</div></th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center"></th>
                                          </tr>
                                    </thead>
                                    <tbody id="list-barang">
                                          <tr id="row_1">
                                                <td><input type="text" name="kode_barang[]" id="kode_barang_1" class="form-control kode_barang"></td>
                                                <!-- FIX #1: Hapus autocomplete="false" (nilai tidak valid), pakai autocomplete="off" hanya di sini supaya browser tidak override datalist -->
                                                <td><input type="text" name="nama_barang[]" id="nama_barang_1" data-type="tambah" data-id="1" list="data_barang" class="form-control nama_barang" onchange="get_data(this)"></td>
                                                <!-- MODIFIKASI: tampilkan stok barang saat ini supaya kasir tahu batas jual sebelum checkout -->
                                                <td><input type="text" id="stok_1" class="form-control" readonly></td>
                                                <td><input type="number" name="qty[]" onkeyup="ganti_qty(this)" onchange="ganti_qty(this)" data-id="1" id="qty_1" class="form-control qty" value=""></td>
                                                <td>
                                                      <input type="text" name="total[]" id="total_1" class="form-control harga harga-total" readonly>
                                                      <input type="text" name="harga[]" id="harga_1" class="form-control harga harga-satuan" hidden>
                                                </td>
                                                <td class="align-middle"><a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" onclick="delete_row(this)" data-id="1"><i class="fas fa-trash-alt m-0"></i></a></td>
                                          </tr>
                                    </tbody>
                                    <thead class="thead-light">
                                          <tr>
                                                <th class="text-right" colspan="3" style="font-size:20pt;">TOTAL</th>
                                                <th class="text-center"><span id="grand-total" style="font-size:20pt;">0</span></th>
                                          </tr>
                                    </thead>
                              </table>
                              <input type="text" name="uang_awal" id="uang_awal" class="form-control" hidden>
                              <input type="text" name="id_penyerahan" id="id_penyerahan" class="form-control" hidden>
                              <input type="text" name="kode_tahanan" id="kode_tahanan" class="form-control" hidden>
                        </div>
                        <div class="w-100 text-right">
                              <a href="javascript:void(0)" onclick="pilih_tahanan()" class="btn btn-info" id="btn-next-process"><i class="fas fa-arrow-right mr-2"></i>Lanjut</a>
                        </div>
                  </div>
            </div>
      </div>
</div>

<!-- Modal Pilih Tahanan -->
<div class="modal fade" id="pilihtahanan" tabindex="-1" role="dialog" aria-labelledby="pilihtahananLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="pilihtahananLabel">Pilih Tahanan</h5>
                  <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                  </a>
                  </div>
                  <div class="modal-body">
                        <!-- MODIFIKASI: opsi pembeli Non-WBP (pengunjung/umum, bukan warga binaan) -
                             pembayaran selalu tunai, tidak menyentuh saldo WBP manapun. -->
                        <div class="w-100 text-right mb-2">
                              <a href="javascript:void(0)" class="btn btn-warning" onclick="pilih_non_wbp()"><i class="fas fa-user mr-2"></i>Pembeli Non-WBP (Bayar Tunai)</a>
                        </div>
                        <table class="table table-sm table-bordered table-hover" id="datatable-tahanan">
                              <thead class="thead-light">
                                    <tr style="font-size:10pt;">
                                          <th class="text-center">No</th>
                                          <th class="text-center">Nama Tahanan</th>
                                          <th class="text-center">Sisa Uang Digital</th>
                                          <th class="text-center">Sisa Uang Dipegang</th>
                                    </tr>
                              </thead>
                              <tbody>
                                    <?php
                                    $tahanan = $this->admin_model->get_data_select("tahanan","code_napi,nama","id !=","result");
                                    if(!empty($tahanan)){
                                          $no = 1;
                                          foreach ($tahanan as $tahanan) {
                                                $saldo_digital = $this->admin_model->get_saldo_digital($tahanan->code_napi);
                                                if($saldo_digital < 0){
                                                      $total_sisa_uang_digital = '<span class="text-danger">-'.number_format(abs($saldo_digital),0,"",".").' (HUTANG)</span>';
                                                }else{
                                                      $total_sisa_uang_digital = number_format($saldo_digital,0,"",".");
                                                }

                                                $saldo_tunai = $this->admin_model->get_saldo_tunai($tahanan->code_napi);
                                                if($saldo_tunai < 0){
                                                      $total_sisa_uang_manual = '<span class="text-danger">-'.number_format(abs($saldo_tunai),0,"",".").' (HUTANG)</span>';
                                                }else{
                                                      $total_sisa_uang_manual = number_format($saldo_tunai,0,"",".");
                                                }
                                                echo '
                                                <tr style="cursor:pointer; font-size:10pt;" title="Klik baris untuk memilih" data-code-napi="'.$tahanan->code_napi.'" data-nama="'.$tahanan->nama.'" onclick="pick_tahanan(this)">
                                                      <td class="text-center">'.$no.'</td>
                                                      <td class="text-center" id="nama-wbp-'.$tahanan->code_napi.'">'.$tahanan->nama.'</td>
                                                      <td class="text-center" id="sisa-uang-digital-'.$tahanan->code_napi.'">'.$total_sisa_uang_digital.'</td>
                                                      <td class="text-center" id="sisa-uang-manual-'.$tahanan->code_napi.'">'.$total_sisa_uang_manual.'</td>
                                                </tr>';
                                                $no++;
                                          }
                                    }
                                    ?>
                              </tbody>
                        </table>
                  </div>
            </div>
      </div>
</div>

<!-- Modal Pilih Penggunaan Uang -->
<div class="modal fade" id="pilihpenggunaanuang" tabindex="-1" role="dialog" aria-labelledby="pilihpenggunaanuangLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
            <div class="modal-content">
                  <div class="modal-header">
                  <h5 class="modal-title" id="pilihpenggunaanuangLabel">Pilih Penggunaan Uang</h5>
                  <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                  </a>
                  </div>
                  <div class="modal-body">
                        <label for="" class="mt-0">Bukti Penyerahan</label>
                        <input type="file" name="bukti" id="bukti" accept=".png, .jpg" hidden required>
                        <img src="data:image/svg+xml,<svg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20200%20150%22><rect%20width=%22200%22%20height=%22150%22%20fill=%22%23f3f4f8%22/><g%20fill=%22none%22%20stroke=%22%23c7cbd4%22%20stroke-width=%224%22><rect%20x=%2255%22%20y=%2240%22%20width=%2290%22%20height=%2265%22%20rx=%226%22/><circle%20cx=%2280%22%20cy=%2263%22%20r=%228%22/><path%20d=%22M55%2095l25-25%2020%2018%2015-15%2030%2030%22/></g><text%20x=%22100%22%20y=%22128%22%20font-family=%22sans-serif%22%20font-size=%2213%22%20fill=%22%239aa4b8%22%20text-anchor=%22middle%22>No%20Image</text></svg>" alt="Bukti Penyerahan" id="foto_bukti" width="100%">
                        <div class="row mt-3">
                              <div class="col-6" id="col-uang-manual"><a href="javascript:void(0)" id="uang-manual" data-code-napi="" class="btn btn-success w-100" onclick="proses_pembayaran(this)"><i class="fas fa-money-bill-wave d-block mb-1" style="font-size:16pt;"></i>UANG TUNAI</a></div>
                              <!-- MODIFIKASI: disembunyikan di mode pembeli Non-WBP (pembayaran selalu tunai) -->
                              <div class="col-6" id="col-uang-digital"><a href="javascript:void(0)" id="uang-digital" data-code-napi="" class="btn btn-primary w-100" onclick="tampil_modal_pin(this)"><i class="fas fa-wallet d-block mb-1" style="font-size:16pt;"></i>UANG DIGITAL</a></div>
                        </div>
                  </div>
            </div>
      </div>
</div>

<!-- FIX #2: Modal PIN untuk pembayaran digital -->
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
                        <p class="text-muted mb-3" style="font-size:13px;">Masukkan PIN 6 digit untuk konfirmasi pembayaran digital</p>

                        <!-- Tampilan titik PIN -->
                        <div class="d-flex justify-content-center mb-3" id="pin-dots">
                              <span class="pin-dot mx-1" id="dot-1"></span>
                              <span class="pin-dot mx-1" id="dot-2"></span>
                              <span class="pin-dot mx-1" id="dot-3"></span>
                              <span class="pin-dot mx-1" id="dot-4"></span>
                              <span class="pin-dot mx-1" id="dot-5"></span>
                              <span class="pin-dot mx-1" id="dot-6"></span>
                        </div>

                        <!-- Input PIN tersembunyi -->
                        <input type="password" id="input-pin" maxlength="6" class="form-control text-center mb-3"
                               style="letter-spacing:8px; font-size:18px; width:160px; margin:auto;"
                               placeholder="••••••" oninput="update_pin_dots(this)" autocomplete="off">

                        <p id="pin-error-msg" class="text-danger" style="font-size:12px; display:none;"><i class="fas fa-exclamation-circle mr-1"></i>PIN salah, coba lagi.</p>

                        <!-- Numpad -->
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
                                    <div class="col-4 p-1"><button class="btn btn-success btn-block pin-key" onclick="konfirmasi_pin()"><i class="fas fa-check"></i></button></div>
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