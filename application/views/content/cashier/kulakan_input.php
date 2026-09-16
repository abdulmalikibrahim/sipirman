<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body p-4">
                <form action="<?= base_url("simpan_kulakan") ?>" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-lg-4 d-none d-lg-block m-auto">Tanggal</div>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" value="<?= date("d-m-Y H:i") ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-4 d-none d-lg-block">Dokumentasi / Nota <span class="text-danger">*</span></div>
                        <div class="col-lg-8">
                            <input type="file" name="dokumentasi" id="dokumentasi" accept=".png, .jpg" hidden required>
                            <img src="data:image/svg+xml,<svg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20200%20150%22><rect%20width=%22200%22%20height=%22150%22%20fill=%22%23f3f4f8%22/><g%20fill=%22none%22%20stroke=%22%23c7cbd4%22%20stroke-width=%224%22><rect%20x=%2255%22%20y=%2240%22%20width=%2290%22%20height=%2265%22%20rx=%226%22/><circle%20cx=%2280%22%20cy=%2263%22%20r=%228%22/><path%20d=%22M55%2095l25-25%2020%2018%2015-15%2030%2030%22/></g><text%20x=%22100%22%20y=%22128%22%20font-family=%22sans-serif%22%20font-size=%2213%22%20fill=%22%239aa4b8%22%20text-anchor=%22middle%22>No%20Image</text></svg>" alt="Dokumentasi Kulakan" id="foto_dokumentasi" width="25%">
                        </div>
                    </div>

                    <hr>
                    <p class="text-sm">Daftar Barang Kulakan</p>
                    <datalist id="data_barang">
                        <?php
                        $data_barang = $this->admin_model->get_data_select("data_barang_koperasi","nama_barang","id !=","result");
                        if(!empty($data_barang)){
                            foreach ($data_barang as $db) {
                                echo '<option value="'.$db->nama_barang.'">'.$db->nama_barang.'</option>';
                            }
                        }
                        ?>
                    </datalist>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center"><div style="width:25rem;">Nama Barang</div></th>
                                    <th class="text-center"><div style="width:8rem;">Stok Saat Ini</div></th>
                                    <th class="text-center"><div style="width:8rem;">Jumlah Kulakan</div></th>
                                    <th class="text-center"><div style="width:10rem;">Harga Sub Total</div></th>
                                    <th class="text-center">Harga Satuan</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="list-barang">
                                <tr id="row_1">
                                    <td><input type="text" name="nama_barang[]" id="nama_barang_1" data-type="tambah" data-id="1" list="data_barang" class="form-control" onchange="get_data(this)"></td>
                                    <td><input type="text" id="stok_1" class="form-control" readonly></td>
                                    <td><input type="number" min="1" name="jumlah_barang[]" onkeyup="hitung_satuan(this)" onchange="hitung_satuan(this)" data-id="1" id="jumlah_1" class="form-control"></td>
                                    <td><input type="text" name="harga_subtotal[]" onkeyup="format_harga_subtotal(this)" onchange="hitung_satuan(this)" data-id="1" id="subtotal_1" class="form-control"></td>
                                    <td><input type="text" id="harga_1" class="form-control" readonly></td>
                                    <td class="align-middle"><a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" onclick="delete_row(this)" data-id="1"><i class="fas fa-trash-alt m-0"></i></a></td>
                                </tr>
                            </tbody>
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-right" colspan="4" style="font-size:16pt;">TOTAL BIAYA KULAKAN</th>
                                    <th class="text-center" colspan="2"><span id="grand-total" style="font-size:16pt;">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div align="right">
                        <a href="<?= base_url("kulakan"); ?>" class="btn btn-danger">Kembali</a>
                        <button class="btn btn-info" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
