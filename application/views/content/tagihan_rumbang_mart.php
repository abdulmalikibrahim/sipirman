<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-4">
                <label>Filter: Status</label>
                <select id="filter-status" class="form-control form-control-sm">
                    <option value="Semua">Semua Status</option>
                    <option value="Tertagih">Tertagih</option>
                    <option value="Terbayar">Terbayar</option>
                </select>
            </div>
            <div class="col-md-4 offset-md-4 text-right">
                <label>Search: Nama WBP</label>
                <input type="text" id="search-wbp" class="form-control form-control-sm" placeholder="Ketik nama WBP...">
            </div>
        </div>
    </div>
    
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" id="table-tagihan">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center align-middle">No</th>
                        <th class="text-center align-middle">Nama WBP</th>
                        <th class="text-center align-middle">Saldo Fisik Uang WBP</th>
                        <th class="text-center align-middle">Tgl Belanja</th>
                        <th class="text-center align-middle">Nama Barang</th>
                        <th class="text-center align-middle">Jumlah Barang</th>
                        <th class="text-center align-middle">Harga Satuan</th>
                        <th class="text-center align-middle">Total Harga</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-tagihan">
                    <?php 
                    $no = 1;
                    foreach($data_tagihan as $wbp): 
                        $rowspan = count($wbp['items']);
                        if($rowspan == 0) continue;
                        
                        $kode_tahanan = $wbp['kode_tahanan'];
                        $first = true;
                        foreach($wbp['items'] as $item):
                            $badge_class = ($item['status'] == 'Tertagih') ? 'badge-warning' : 'badge-success';
                    ?>
                    <tr class="wbp-row" 
                        data-group="<?= $kode_tahanan; ?>" 
                        data-nama="<?= strtolower($wbp['nama_wbp']); ?>" 
                        data-status="<?= $item['status']; ?>" 
                        data-total="<?= $item['total_harga']; ?>"
                        data-first="<?= $first ? '1' : '0'; ?>">
                        
                        <?php if($first): ?>
                        <td class="text-center align-middle rs-cell" rowspan="<?= $rowspan; ?>"><?= $no; ?></td>
                        <td class="align-middle font-weight-bold rs-cell" rowspan="<?= $rowspan; ?>"><?= $wbp['nama_wbp']; ?></td>
                        <td class="text-right align-middle font-weight-bold rs-cell" rowspan="<?= $rowspan; ?>">Rp <?= number_format($wbp['saldo_fisik'], 0, ',', '.'); ?>,-</td>
                        <?php endif; ?>
                        
                        <td class="text-center align-middle"><?= date('d/m/Y', strtotime($item['tanggal_terakhir'])); ?></td>
                        <td class="align-middle"><?= $item['nama_barang']; ?></td>
                        <td class="text-center align-middle"><?= $item['qty']; ?></td>
                        <td class="text-right align-middle">Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?>,-</td>
                        <td class="text-right align-middle">Rp <?= number_format($item['total_harga'], 0, ',', '.'); ?>,-</td>
                        <td class="text-center align-middle">
                            <span class="badge <?= $badge_class; ?>"><?= $item['status']; ?></span>
                        </td>
                        
                        <?php if($first): ?>
                        <td class="text-center align-middle rs-cell" rowspan="<?= $rowspan; ?>">
                            <?php if($wbp['has_tertagih']): ?>
                                <button class="btn btn-sm btn-info" onclick="proses_bayar('<?= $wbp['kode_tahanan']; ?>', '<?= $wbp['nama_wbp']; ?>')">
                                    <i class="fas fa-check-circle mr-1"></i>(bayar)
                                </button>
                            <?php else: ?>
                                <span class="text-success"><i class="fas fa-check"></i> Selesai</span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php 
                        $first = false;
                        endforeach; 
                        $no++;
                    endforeach; 
                    ?>
                </tbody>
                <thead class="thead-light">
                    <tr>
                        <th colspan="7" class="text-right align-middle" style="font-size:13pt;">Jumlah Tertagih:</th>
                        <th colspan="3" class="text-left text-danger align-middle" style="font-size:13pt;" id="footer-tertagih">Rp <?= number_format($total_tertagih, 0, ',', '.'); ?>,-</th>
                    </tr>
                    <tr>
                        <th colspan="7" class="text-right align-middle" style="font-size:13pt;">Jumlah Terbayar:</th>
                        <th colspan="3" class="text-left text-success align-middle" style="font-size:13pt;" id="footer-terbayar">Rp <?= number_format($total_terbayar, 0, ',', '.'); ?>,-</th>
                    </tr>
                </thead>
            </table>
            <div class="p-2 text-muted" style="font-size: 10pt;">
                <em>* Keterangan: Saldo Fisik Uang WBP otomatis berkurang setelah klik "bayar".</em>
            </div>
        </div>
    </div>
</div>