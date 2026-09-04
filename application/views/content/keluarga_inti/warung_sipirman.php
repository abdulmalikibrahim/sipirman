
<div class="row">
      <div class="col-lg-12 text-right">
            <a href="<?= base_url("status_belanja_ki") ?>" class="btn btn-info mb-2"><i class="fas fa-eye mr-2"></i>Lihat Status Belanja</a>
            <a href="<?= base_url("data_barang_ki") ?>" class="btn btn-success mb-2"><i class="fab fa-shopify mr-2"></i>Daftar Barang</a>
      </div>
</div>
<form action="<?= base_url("simpan_belanja_ki"); ?>" method="post">
      <div class="card">
            <div class="card-body p-3">
                  <div class="row">
                        <div class="col-lg-3 mb-2">
                              <select name="wbp" id="wbp" class="form-control" required>
                                    <option value="" disabled selected>Pilih WBP</option>
                                    <?php
                                    if($this->keluarga_inti > 0){
                                          $kode_tahanan = $this->kode_tahanan;
                                          if(is_array(json_decode($kode_tahanan,true))){
                                                $kode_tahanan = json_decode($kode_tahanan,true);
                                                foreach ($kode_tahanan as $key => $value) {
                                                      $kode_tahanan_array[] = $value;
                                                }
                                                $get_kode_tahanan_history = $this->admin_model->get_data_select("penyimpanan_uang","kode_tahanan","nama_pengirim = '".$this->nama."' GROUP BY kode_tahanan","result");
                                                if(!empty($get_kode_tahanan_history)){
                                                      foreach ($get_kode_tahanan_history as $kt) {
                                                            $kode_tahanan_array[] = $kt->kode_tahanan;
                                                      }
                                                }
                                                $kode_tahanan =  "'".implode("','",$kode_tahanan_array)."'";
                                          }else{
                                                $kode_tahanan = "'".$kode_tahanan."'";
                                          }
                                    }else{
                                          $get_kode_tahanan_history = $this->admin_model->get_data_select("penyimpanan_uang","kode_tahanan","nama_pengirim = '".$this->nama."' GROUP BY kode_tahanan","result");
                                          if(!empty($get_kode_tahanan_history)){
                                                foreach ($get_kode_tahanan_history as $kt) {
                                                      $kode_tahanan_array[] = $kt->kode_tahanan;
                                                }
                                                $kode_tahanan = "'".implode("','",$kode_tahanan_array)."'";
                                          }else{
                                                $kode_tahanan = "";
                                          }
                                    }
                                    if(!empty($kode_tahanan)){
                                          $all_tahanan = $this->admin_model->get_data_select("tahanan","*","code_napi IN (".$kode_tahanan.") AND code_napi != ''","result");
                                          foreach ($all_tahanan as $tahanan) {
                                                if($this->keluarga_inti > 0){
                                                      $total_sisa_uang = $this->admin_model->get_data_select("penyimpanan_uang","SUM(jumlah_uang) as total_uang, SUM((SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id)) as total_penggunaan","kode_tahanan = '".$tahanan->code_napi."' AND id != '' ORDER BY tanggal DESC LIMIT 0,20","row");
                              
                                                      $total_sisa_uang_digital = $total_sisa_uang->total_uang - $total_sisa_uang->total_penggunaan;
                                                }else{
                                                      $total_sisa_uang = $this->admin_model->get_data_select("penyimpanan_uang","SUM(jumlah_uang) as total_uang, SUM((SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id)) as total_penggunaan","kode_tahanan = '".$tahanan->code_napi."' AND nama_pengirim = '".$this->nama."' AND id != '' ORDER BY tanggal DESC LIMIT 0,20","row");
      
                                                      $total_sisa_uang_digital = $total_sisa_uang->total_uang - $total_sisa_uang->total_penggunaan;
                                                }
                                                echo '<option value="'.$tahanan->code_napi.'" data-uang-terima="'.$total_sisa_uang->total_uang.'" data-uang-terpakai="'.$total_sisa_uang->total_penggunaan.'" data-sisa-uang="'.number_format($total_sisa_uang_digital,0,"",".").'">'.$tahanan->nama.'</option>';
                                          }
                                    }
                                    ?>
                              </select>
                        </div>
                        <div class="col-lg-4 mt-lg-2 mt-1">
                              <span id="sisa-uang" class="font-weight-bold "></span>
                        </div>
                        <div class="col-lg-12">
                              <p class="text-sm">Belanja WBP Dari Rumah</p>
                              <datalist id="data_barang">
                                    <?php
                                    $data_barang = $this->admin_model->get_data_select("data_barang_koperasi","nama_barang,harga","id !=","result");
                                    if(!empty($data_barang)){
                                          foreach ($data_barang as $data_barang) {
                                                echo '<option data-harga="'.$data_barang->harga.'" value="'.$data_barang->nama_barang.'">'.$data_barang->nama_barang.'</option>';
                                          }
                                    }
                                    ?>
                              </datalist>
                              <div>
                                    <div class="row" id="row_1">
                                          <div class="col-lg-5 mb-2">
                                                <input type="text" name="nama_barang[]" id="nama_barang_1" data-type="tambah" data-id="1" list="data_barang" class="form-control nama_barang" onchange="get_data(this)" placeholder="Masukkan Nama Barang" autocomplete="false">
                                          </div>
                                          <div class="col-lg-3 col-5 mb-2 pl-lg-0">
                                                <input type="number" name="qty[]" onkeyup="ganti_qty(this)" onchange="ganti_qty(this)" data-id="1" id="qty_1" class="form-control qty" placeholder="Qty Barang" value="">
                                          </div>
                                          <div class="col-lg-3 col-5 mb-2 pl-0 pr-0">
                                                <input type="text" name="total[]" id="total_1" placeholder="Sub Total" class="form-control harga harga-total" readonly>
                                                <input type="text" name="harga[]" id="harga_1" class="form-control harga harga-satuan" hidden>
                                          </div>
                                          <div class="col-lg-1 col-1 mb-2">
                                                <a href="javascript:void(0)" class="btn btn-danger" title="Delete" onclick="delete_row(this)" data-id="1"><i class="fas fa-trash-alt m-0"></i></a>
                                          </div>
                                    </div>
                                    <div id="list-barang"></div>
                                    <div class="row">
                                          <div class="col-lg-8 col-5 text-lg-right" style="font-size:20pt;">TOTAL</div>
                                          <div class="col-lg-4 col-6 pl-0"><span id="grand-total" style="font-size:20pt;">0</span></div>
                                    </div>
                                    <input type="text" name="uang_awal" id="uang_awal" class="form-control" hidden>
                                    <input type="text" name="id_penyerahan" id="id_penyerahan" class="form-control" hidden>
                                    <input type="text" name="kode_tahanan" id="kode_tahanan" class="form-control" hidden>
                              </div>
                              <div class="w-100 text-right">
                                    <button type="submit" class="btn btn-info"><i class="fas fa-cart-shopping mr-2"></i>Simpan</a><br>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</form>