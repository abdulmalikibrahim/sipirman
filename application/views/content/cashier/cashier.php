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
                                                $total_sisa_uang = $this->admin_model->get_data_select("penyimpanan_uang","SUM(jumlah_uang) as total_uang, SUM((SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id)) as total_penggunaan","kode_tahanan = '".$tahanan->code_napi."' AND id != '' ORDER BY tanggal DESC LIMIT 0,20","row");
                                                $total_sisa_uang_digital = $total_sisa_uang->total_uang - $total_sisa_uang->total_penggunaan;
                                                if(!empty($total_sisa_uang_digital)){
                                                      $total_sisa_uang_digital = number_format($total_sisa_uang_digital,0,"",".");
                                                }else{
                                                      $total_sisa_uang_digital = 0;
                                                }

                                                $get_data_diserahkan = $this->admin_model->get_data_select("penggunaan_uang","id,tanggal,total_penggunaan,(SELECT COALESCE(SUM(penggunaan),0) FROM belanja_uang_tunai WHERE belanja_uang_tunai.id_penyerahan=penggunaan_uang.id) as total_belanja_tunai,(total_penggunaan-(SELECT COALESCE(SUM(penggunaan),0) FROM belanja_uang_tunai WHERE belanja_uang_tunai.id_penyerahan=penggunaan_uang.id)) as sisa_uang_tunai","penggunaan LIKE '%Diserahkan Tunai Ke WBP%' AND kode_tahanan = '".$tahanan->code_napi."' ORDER BY tanggal DESC LIMIT 0,20","result");
                                                $get_data_diserahkan = array_reverse($get_data_diserahkan);
                                                $total_uang_dipegang = 0;
                                                foreach ($get_data_diserahkan as $gdd) {
                                                      $total_uang_dipegang += $gdd->sisa_uang_tunai;
                                                }

                                                if(!empty($total_uang_dipegang)){
                                                      $total_sisa_uang_manual = number_format($total_uang_dipegang,0,"",".");
                                                }else{
                                                      $total_sisa_uang_manual = 0;
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
                        <img src="https://getstamped.co.uk/wp-content/uploads/WebsiteAssets/Placeholder.jpg" alt="Bukti Penyerahan" id="foto_bukti" width="100%">
                        <div class="row mt-3">
                              <div class="col-6"><a href="javascript:void(0)" id="uang-manual" data-code-napi="" class="btn btn-info w-100" onclick="proses_pembayaran(this)">UANG TUNAI</a></div>
                              <div class="col-6"><a href="javascript:void(0)" id="uang-digital" data-code-napi="" class="btn btn-info w-100" onclick="tampil_modal_pin(this)">UANG DIGITAL</a></div>
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