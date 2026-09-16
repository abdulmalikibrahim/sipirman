<?php
// ================================================================
// rekap_penjualan.php — View: Rekap Penjualan Kasir
// ================================================================

// MODIFIKASI: sebelumnya cuma menangkap pembelian "Oleh WBP" (dari kasir
// langsung) - pembelian lewat akun Keluarga Inti/Penitip (Warung SIPIRMAN
// online, disimpan dengan pola "Oleh Keluarga <nama>"/"Oleh Penitip <nama>")
// tidak pernah ikut muncul di rekap walau stok barangnya sudah berkurang.
$rekap_cashless = $this->admin_model->get_data_select(
      "penggunaan_uang",
      "id, tanggal, kode_tahanan, penggunaan, total_penggunaan, status",
      "(penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh WBP)%' OR penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh Keluarga%' OR penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh Penitip%') AND id != '' ORDER BY tanggal DESC",
      "result"
);

$rekap_tunai = $this->admin_model->get_data_select(
      "belanja_uang_tunai",
      "id, tanggal, kode_tahanan, data_belanja, penggunaan as total_penggunaan",
      "id != '' ORDER BY tanggal DESC",
      "result"
);

function parse_item_key($key) {
      if(preg_match('/^(.+?)\s*\((\d+)\)\s*$/', trim($key), $m)){
            return ["nama" => trim($m[1]), "qty" => (int)$m[2]];
      }
      return ["nama" => trim($key), "qty" => 1];
}

function harga_satuan_item($total, $qty) {
      return ($qty > 0) ? (int)round($total / $qty) : $total;
}

function parse_belanja_json($json_str) {
      $decode = json_decode($json_str, true);
      $items  = [];
      if(empty($decode)) return $items;
      foreach($decode as $grup => $val){
            if(is_array($val)){
                  foreach($val as $k => $v){
                        $p = parse_item_key($k);
                        $items[] = ["nama" => $p["nama"], "qty" => $p["qty"], "satuan" => harga_satuan_item($v,$p["qty"]), "total" => $v];
                  }
            }else{
                  $p = parse_item_key($grup);
                  $items[] = ["nama" => $p["nama"], "qty" => $p["qty"], "satuan" => harga_satuan_item($val,$p["qty"]), "total" => $val];
            }
      }
      return $items;
}
?>

<div style="padding: 8px 4px;">

      <!-- ===== FILTER ===== -->
      <div class="card mb-4" style="border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
            <div class="card-body" style="padding:20px 24px;">
                  <div class="row" style="margin-bottom:12px;">
                        <div class="col-12">
                              <strong style="font-size:13px;"><i class="fas fa-filter mr-2 text-info"></i>Filter & Tampilan</strong>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-6" style="padding-right:10px; padding-left:10px; margin-bottom:14px;">
                              <label style="font-size:11px; font-weight:600; color:#555; margin-bottom:4px; display:block;">TAMPILKAN TABEL</label>
                              <select id="filter-jenis-tabel" class="form-control form-control-sm" onchange="ganti_tabel(this.value)">
                                    <option value="cashless">Cashless (Uang Digital)</option>
                                    <option value="tunai">Tunai</option>
                              </select>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6" style="padding-right:10px; padding-left:10px; margin-bottom:14px;">
                              <label style="font-size:11px; font-weight:600; color:#555; margin-bottom:4px; display:block;">TANGGAL MULAI</label>
                              <input type="date" id="filter-tgl-mulai" class="form-control form-control-sm">
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6" style="padding-right:10px; padding-left:10px; margin-bottom:14px;">
                              <label style="font-size:11px; font-weight:600; color:#555; margin-bottom:4px; display:block;">TANGGAL SELESAI</label>
                              <input type="date" id="filter-tgl-selesai" class="form-control form-control-sm">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6" style="padding-right:10px; padding-left:10px; margin-bottom:14px;">
                              <label style="font-size:11px; font-weight:600; color:#555; margin-bottom:4px; display:block;">NAMA WBP</label>
                              <input type="text" id="filter-nama-wbp" class="form-control form-control-sm" placeholder="Cari nama WBP...">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6" style="padding-right:10px; padding-left:10px; margin-bottom:14px;">
                              <label style="font-size:11px; font-weight:600; color:#555; margin-bottom:4px; display:block;">NAMA BARANG</label>
                              <input type="text" id="filter-nama-barang" class="form-control form-control-sm" placeholder="Cari nama barang...">
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-12" style="padding-right:10px; padding-left:10px;">
                              <button class="btn btn-info btn-sm mr-2" onclick="terapkan_filter()" style="min-width:120px;">
                                    <i class="fas fa-search mr-1"></i> Terapkan Filter
                              </button>
                              <button class="btn btn-outline-secondary btn-sm" onclick="reset_filter()" style="min-width:90px;">
                                    <i class="fas fa-times mr-1"></i> Reset
                              </button>
                        </div>
                  </div>
            </div>
      </div>

      <!-- ===== TABEL CASHLESS ===== -->
      <div id="section-cashless">
            <div class="card" style="border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
                  <div class="card-header" style="padding:14px 20px; border-radius:8px 8px 0 0;">
                        <strong style="font-size:13px;"><i class="fas fa-credit-card mr-2"></i>Rekap Penjualan Cashless (Uang Digital)</strong>
                  </div>
                  <div class="card-body" style="padding:16px 12px;">
                        <div class="table-responsive">
                              <table class="table table-bordered table-sm table-hover" id="datatable-cashless" style="font-size:12px;">
                                    <thead class="thead-light">
                                          <tr class="text-center">
                                                <th style="width:40px;">No</th>
                                                <th style="width:90px;">Tgl Belanja</th>
                                                <th>Nama WBP</th>
                                                <th>Nama Barang</th>
                                                <th style="width:60px;">Jumlah</th>
                                                <th style="width:115px;">Harga Satuan</th>
                                                <th style="width:115px;">Total Harga</th>
                                                <th style="width:85px;">Status</th>
                                          </tr>
                                    </thead>
                                    <tbody id="tbody-cashless">
                                    <?php
                                    if(!empty($rekap_cashless)){
                                          $no = 1;
                                          foreach($rekap_cashless as $row){
                                                $wbp      = $this->admin_model->get_data_select("tahanan","nama","code_napi = '".$row->kode_tahanan."'","row");
                                                $nama_wbp = !empty($wbp) ? strtoupper($wbp->nama) : 'Tidak ditemukan';

                                                $badge = ($row->status == 'Need Confirm')
                                                      ? '<span class="badge badge-warning" style="font-size:10px;">Tertagih</span>'
                                                      : '<span class="badge badge-success" style="font-size:10px;">Terbayar</span>';

                                                $items      = parse_belanja_json($row->penggunaan);
                                                $item_count = count($items);
                                                $all_barang = strtolower(implode(',', array_column($items, 'nama')));
                                                $tgl_data   = date("Y-m-d", strtotime($row->tanggal));
                                                $wbp_lower  = strtolower($nama_wbp);

                                                if($item_count == 0){
                                                      echo '<tr id="cl-row-'.$row->id.'" data-tgl="'.$tgl_data.'" data-wbp="'.$wbp_lower.'" data-barang="">';
                                                      echo '<td class="text-center align-middle">'.$no.'</td>';
                                                      echo '<td class="text-center align-middle">'.date("d/m/Y",strtotime($row->tanggal)).'</td>';
                                                      echo '<td class="align-middle">'.htmlspecialchars($nama_wbp).'</td>';
                                                      echo '<td class="align-middle text-muted">-</td>';
                                                      echo '<td class="text-center align-middle text-muted">-</td>';
                                                      echo '<td class="text-right align-middle text-muted">-</td>';
                                                      echo '<td class="text-right align-middle">Rp '.number_format($row->total_penggunaan,0,"",".").',-</td>';
                                                      echo '<td class="text-center align-middle">'.$badge.'</td>';
                                                      echo '</tr>';
                                                      $no++;
                                                }else{
                                                      foreach($items as $idx => $item){
                                                            $is_first = ($idx === 0);
                                                            $rs = ($item_count > 1) ? ' rowspan="'.$item_count.'"' : '';
                                                            echo '<tr'.($is_first ? ' id="cl-row-'.$row->id.'"' : '').' data-tgl="'.$tgl_data.'" data-wbp="'.$wbp_lower.'" data-barang="'.$all_barang.'">';
                                                            if($is_first){
                                                                  echo '<td class="text-center align-middle"'.$rs.'>'.$no.'</td>';
                                                                  echo '<td class="text-center align-middle"'.$rs.'>'.date("d/m/Y",strtotime($row->tanggal)).'</td>';
                                                                  echo '<td class="align-middle"'.$rs.'>'.htmlspecialchars($nama_wbp).'</td>';
                                                            }
                                                            echo '<td class="align-middle">'.htmlspecialchars($item["nama"]).'</td>';
                                                            echo '<td class="text-center align-middle">'.$item["qty"].'</td>';
                                                            echo '<td class="text-right align-middle">Rp '.number_format($item["satuan"],0,"",".").',-</td>';
                                                            echo '<td class="text-right align-middle">Rp '.number_format($item["total"],0,"",".").',-</td>';
                                                            if($is_first){
                                                                  echo '<td class="text-center align-middle"'.$rs.'>'.$badge.'</td>';
                                                            }
                                                            echo '</tr>';
                                                      }
                                                      $no++;
                                                }
                                          }
                                    }else{
                                          echo '<tr><td colspan="8" class="text-center text-muted py-3">Belum ada data penjualan cashless</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                              </table>
                        </div>
                        <small class="text-muted mt-1 d-block" style="padding:0 4px;">
                              <i class="fas fa-info-circle mr-1"></i>
                              Status <strong>Tertagih</strong> = menunggu konfirmasi Admin &nbsp;|&nbsp; <strong>Terbayar</strong> = sudah dikonfirmasi Admin.
                        </small>
                  </div>
            </div>
      </div>

      <!-- ===== TABEL TUNAI (tersembunyi default) ===== -->
      <div id="section-tunai" style="display:none;">
            <div class="card" style="border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
                  <div class="card-header" style="padding:14px 20px; border-radius:8px 8px 0 0;">
                        <strong style="font-size:13px;"><i class="fas fa-money-bill-wave mr-2"></i>Rekap Penjualan Tunai</strong>
                  </div>
                  <div class="card-body" style="padding:16px 12px;">
                        <div class="table-responsive">
                              <table class="table table-bordered table-sm table-hover" id="datatable-tunai" style="font-size:12px;">
                                    <thead class="thead-light">
                                          <tr class="text-center">
                                                <th style="width:40px;">No</th>
                                                <th style="width:90px;">Tgl Belanja</th>
                                                <th>Nama WBP</th>
                                                <th>Nama Barang</th>
                                                <th style="width:60px;">Jumlah</th>
                                                <th style="width:115px;">Harga Satuan</th>
                                                <th style="width:115px;">Total Harga</th>
                                          </tr>
                                    </thead>
                                    <tbody id="tbody-tunai">
                                    <?php
                                    if(!empty($rekap_tunai)){
                                          $no = 1;
                                          foreach($rekap_tunai as $row){
                                                $wbp      = $this->admin_model->get_data_select("tahanan","nama","code_napi = '".$row->kode_tahanan."'","row");
                                                $nama_wbp = !empty($wbp) ? strtoupper($wbp->nama) : 'Tidak ditemukan';

                                                $items      = parse_belanja_json($row->data_belanja);
                                                $item_count = count($items);
                                                $all_barang = strtolower(implode(',', array_column($items, 'nama')));
                                                $tgl_data   = date("Y-m-d", strtotime($row->tanggal));
                                                $wbp_lower  = strtolower($nama_wbp);

                                                if($item_count == 0){
                                                      echo '<tr data-tgl="'.$tgl_data.'" data-wbp="'.$wbp_lower.'" data-barang="">';
                                                      echo '<td class="text-center align-middle">'.$no.'</td>';
                                                      echo '<td class="text-center align-middle">'.date("d/m/Y",strtotime($row->tanggal)).'</td>';
                                                      echo '<td class="align-middle">'.htmlspecialchars($nama_wbp).'</td>';
                                                      echo '<td class="align-middle text-muted">-</td>';
                                                      echo '<td class="text-center align-middle text-muted">-</td>';
                                                      echo '<td class="text-right align-middle text-muted">-</td>';
                                                      echo '<td class="text-right align-middle">Rp '.number_format($row->total_penggunaan,0,"",".").',-</td>';
                                                      echo '</tr>';
                                                      $no++;
                                                }else{
                                                      foreach($items as $idx => $item){
                                                            $is_first = ($idx === 0);
                                                            $rs = ($item_count > 1) ? ' rowspan="'.$item_count.'"' : '';
                                                            echo '<tr data-tgl="'.$tgl_data.'" data-wbp="'.$wbp_lower.'" data-barang="'.$all_barang.'">';
                                                            if($is_first){
                                                                  echo '<td class="text-center align-middle"'.$rs.'>'.$no.'</td>';
                                                                  echo '<td class="text-center align-middle"'.$rs.'>'.date("d/m/Y",strtotime($row->tanggal)).'</td>';
                                                                  echo '<td class="align-middle"'.$rs.'>'.htmlspecialchars($nama_wbp).'</td>';
                                                            }
                                                            echo '<td class="align-middle">'.htmlspecialchars($item["nama"]).'</td>';
                                                            echo '<td class="text-center align-middle">'.$item["qty"].'</td>';
                                                            echo '<td class="text-right align-middle">Rp '.number_format($item["satuan"],0,"",".").',-</td>';
                                                            echo '<td class="text-right align-middle">Rp '.number_format($item["total"],0,"",".").',-</td>';
                                                            echo '</tr>';
                                                      }
                                                      $no++;
                                                }
                                          }
                                    }else{
                                          echo '<tr><td colspan="7" class="text-center text-muted py-3">Belum ada data penjualan tunai</td></tr>';
                                    }
                                    ?>
                                    </tbody>
                              </table>
                        </div>
                  </div>
            </div>
      </div>

</div><!-- end padding wrapper -->