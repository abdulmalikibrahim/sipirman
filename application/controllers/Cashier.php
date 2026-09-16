<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Cashier extends MY_Controller {
      public function home()
      {
		$data["content"] = "cashier/cashier";
		$data["javascript"] = "cashier/cashier";
		$data["title"] = "Cashier";
		$this->load->view('layout/index',$data);
      }
      public function barang_koperasi()
      {
		$data["content"] = "cashier/barang_koperasi";
		$data["javascript"] = "cashier/barang_koperasi";
		$data["title"] = "Barang Koperasi";
		$this->load->view('layout/index',$data);
      }
      public function tambah_barang($p)
      {
            if($p <= 0){
                  $data["title"] = "Tambah Barang";
            }else{
                  $data["title"] = "Edit Barang";
            }
		$data["content"] = "cashier/tambah_barang";
		$data["javascript"] = "cashier/tambah_barang";
		$this->load->view('layout/index',$data);
      }
      public function simpan_barang($p)
      {
            $this->form_validation->set_rules("kode_barang","Kode Barang","trim|xss_clean");
            $this->form_validation->set_rules("nama_barang","Nama Barang","required|trim|xss_clean");
            $this->form_validation->set_rules("harga","Harga","required|trim|xss_clean");
            if($this->form_validation->run() === TRUE){
                  $kode_barang = $this->input->post("kode_barang");
                  $nama_barang = $this->input->post("nama_barang");
                  $harga = $this->input->post("harga");
                  $check_nama_barang = $this->admin_model->get_data_select("data_barang_koperasi","*","nama_barang = '$nama_barang'","row");
                  if(!empty($check_nama_barang->id) && $p <= 0){
                        $this->swal("Warning","nama barang sudah ada","warning");
                        $redirect = "tambah_barang_koperasi/".$p;
                  }else{
                        $data_submit = [
                              "kode_barang" => $kode_barang,
                              "nama_barang" => $nama_barang,
                              "harga" => str_replace(".","",$harga),
                        ];
                        if(!empty($_FILES["upload_foto_barang"]["name"])){
                              if($p > 0){
                                    if(!empty($check_nama_barang)){
                                          $foto_barang = $check_nama_barang->foto_barang;
                                    }else{
                                          $foto_barang = "";
                                    }
                                    if(!empty($foto_barang)){
                                          $exp_barang = explode("/",$foto_barang);
                                          if(file_exists(FCPATH."/upload/foto_barang_koperasi/".end($exp_barang))){
                                                unlink(FCPATH."/upload/foto_barang_koperasi/".end($exp_barang));
                                          }
                                    }
                              }
                              $filename_barang = hash("ripemd160",time()).".jpeg";
                              $config_barang = array(
                                    "upload_path" => "./upload/foto_barang_koperasi/",
                                    "allowed_types" => "jpg|jpeg|png|",
                                    "file_name" => $filename_barang,
                              );
                              $this->load->library('upload', $config_barang);
                              $upload_barang = $this->upload->initialize($config_barang);
                              if ($this->upload->do_upload('upload_foto_barang')) {
                                    if (!$upload_barang) {
                                          $image_data = $this->upload->data();
                                          $config_barang['image_library'] = 'gd2';
                                          $config_barang['source_image'] = $image_data['full_path']; //get original imag
                                          $config_barang['maintain_ratio'] = TRUE;
                                          $config_barang['width'] = 100;
                                          $config_barang['height'] = 100;
                                          $this->load->library('image_lib', $config_barang);
                                          $this->image_lib->resize();
                                    }
                                    $file_foto_barang = base_url("upload/foto_barang_koperasi/".$filename_barang);
                                    $data_submit["foto_barang"] = $file_foto_barang;
                              }
                        }
                        if($p <= 0){
                              $action = $this->admin_model->insert_data("data_barang_koperasi",$data_submit);
                              $redirect = "tambah_barang_koperasi/".$p;
                        }else{
                              $action = $this->admin_model->update_data("data_barang_koperasi","id = '$p'",$data_submit);
                              $redirect = "barang_koperasi";
                        }
                        if(!$action){
                              $this->swal("Sukses","","success");
                        }else{
                              $sendback = $data_submit;
                              $this->swal("Gagal","","success");
                        }
                  }
            }else{
                  $this->swal("Warning",validation_errors(),"warning");
                  $redirect = "barang_koperasi";
            }
            redirect($redirect);
      }
      public function delete_barang()
      {
            $this->form_validation->set_rules("id","Barang","required|trim|xss_clean");
            if($this->form_validation->run() === TRUE){
                  $id = $this->input->post("id");
                  $action = $this->admin_model->delete_data("data_barang_koperasi","id='$id'");
                  if(!$action){
                        echo "Sukses";
                  }else{
                        echo "Barang gagal dihapus";
                  }
            }else{
                  echo validation_errors();
            }
            die();
      }
      public function check_barang()
      {
            $this->form_validation->set_rules("nama_barang","Barang","required|trim|xss_clean");
            if($this->form_validation->run() === TRUE){
                  $nama_barang = $this->input->post("nama_barang");
                  $data = $this->admin_model->get_data_select("data_barang_koperasi","kode_barang,harga,stok","nama_barang='$nama_barang'","row");
                  if(!empty($data)){
                        $fb = ["status" => 200, "kode_barang" => $data->kode_barang, "harga" => number_format($data->harga,0,"",".") , "stok" => (int) $data->stok];
                  }else{
                        $fb = ["status" => 200, "kode_barang" => "-", "harga" => "0", "stok" => 0];
                  }
            }else{
                  $fb = ["status" => 200, "kode_barang" => "-", "harga" => "0", "stok" => 0];
            }
            echo json_encode($fb);
            die();
      }

      public function diserahkan_uang()
      {
            $code_napi = $this->input->get("code_napi");
            $total_uang_dipegang = $this->admin_model->get_saldo_tunai($code_napi);
            echo $total_uang_dipegang;
            die();
      }

      public function use_money_manual()
      {
            header("Content-Type:text/event-stream");
            $nama_barang_post = explode(",",$this->input->post("nama_barang"));
            foreach ($nama_barang_post as $key => $value) {
                  if(!empty($value)){
                        $nama_barang[] = $value;
                  }
            }
            $qty_post = explode(",",$this->input->post("qty"));
            foreach ($qty_post as $key => $value) {
                  if(!empty($value)){
                        $qty[] = $value;
                  }
            }
            $total_post = explode(",",str_replace(".","",$this->input->post("total")));
            foreach ($total_post as $key => $value) {
                  if(!empty($value)){
                        $total[] = $value;
                  }
            }
            $kode_tahanan = $this->input->post("code_napi");
            if(!empty($_FILES["bukti"]["name"])){
                  if(!empty($nama_barang)){
                        $filename_bukti = hash("ripemd160",time()).".jpeg";
                        $config_bukti = array(
                              "upload_path" => "./upload/bukti_belanja_manual/",
                              "allowed_types" => "jpg|jpeg|png|",
                              "file_name" => $filename_bukti,
                        );
                        $this->load->library('upload', $config_bukti);
                        $upload_bukti = $this->upload->initialize($config_bukti);
                        if ($this->upload->do_upload('bukti')) {
                              if (!$upload_bukti) {
                                    $image_data = $this->upload->data();
                                    $config_bukti['image_library'] = 'gd2';
                                    $config_bukti['source_image'] = $image_data['full_path']; //get original imag
                                    $config_bukti['maintain_ratio'] = TRUE;
                                    $config_bukti['width'] = 100;
                                    $config_bukti['height'] = 100;
                                    $this->load->library('image_lib', $config_bukti);
                                    $this->image_lib->resize();
                              }
                              $image_status = "";
                        }else{
                              $image_status = "Namun bukti foto gagal di upload";
                        }
                        $grand_total = 0;
                        foreach ($nama_barang as $key => $value) {
                              if(!empty($qty[$key])){
                                    $qty_barang = " (".$qty[$key].")";
                              }else{
                                    $qty_barang = "";
                              }

                              if(!empty($total[$key])){
                                    $total_barang = str_replace(".","",$total[$key])*1;
                              }else{
                                    $total_barang = 0;
                              }
                              $grand_total += $total_barang;
                              $daftar_belanja[$value.$qty_barang] = $total_barang;
                        }

                        $validasi_stok = $this->admin_model->validasi_stok($nama_barang, $qty);
                        if($validasi_stok !== TRUE){
                              $fb = ["status" => 500, "title" => "Gagal", "res" => $validasi_stok, "icon" => "error"];
                        }else{
                              $get_data_diserahkan = $this->admin_model->get_data_select("penggunaan_uang","id,tanggal,total_penggunaan,(SELECT COALESCE(SUM(penggunaan),0) FROM belanja_uang_tunai WHERE belanja_uang_tunai.id_penyerahan=penggunaan_uang.id) as total_belanja_tunai,(total_penggunaan-(SELECT COALESCE(SUM(penggunaan),0) FROM belanja_uang_tunai WHERE belanja_uang_tunai.id_penyerahan=penggunaan_uang.id)) as sisa_uang_tunai","penggunaan LIKE '%Diserahkan Tunai Ke WBP%' AND kode_tahanan = '$kode_tahanan' ORDER BY tanggal DESC LIMIT 0,20","result");
                              $get_data_diserahkan = array_reverse($get_data_diserahkan);
                              // Alokasikan belanja ke penyerahan tunai yang tersedia (FIFO). Jika saldo
                              // tunai tidak cukup untuk menutup seluruh belanja, transaksi ditolak
                              // (tidak boleh berhutang).
                              $sisa_total_belanja = $grand_total;
                              $data_penggunaan = [];
                              foreach ($get_data_diserahkan as $gdd1) {
                                    if(!empty($gdd1->sisa_uang_tunai)){
                                          if($sisa_total_belanja > 0){
                                                if($gdd1->sisa_uang_tunai > $sisa_total_belanja){
                                                      $data_penggunaan[$gdd1->id]["saldo_awal"] = $gdd1->sisa_uang_tunai;
                                                      $data_penggunaan[$gdd1->id]["penggunaan"] = $sisa_total_belanja;
                                                      $data_penggunaan[$gdd1->id]["total_sisa"] = $gdd1->sisa_uang_tunai-$sisa_total_belanja;

                                                      $sisa_total_belanja -= $sisa_total_belanja;
                                                }else{
                                                      $data_penggunaan[$gdd1->id]["saldo_awal"] = $gdd1->sisa_uang_tunai;
                                                      $data_penggunaan[$gdd1->id]["penggunaan"] = $gdd1->sisa_uang_tunai;
                                                      $data_penggunaan[$gdd1->id]["total_sisa"] = 0;
                                                      $sisa_total_belanja -= $gdd1->sisa_uang_tunai;
                                                }
                                          }
                                    }
                              }
                              if($sisa_total_belanja > 0){
                                    // Saldo tunai WBP tidak mencukupi, pembelian ditolak.
                                    $fb = ["status" => 500, "title" => "Gagal", "res" => "Saldo tunai WBP tidak mencukupi (kurang Rp. ".number_format($sisa_total_belanja,0,"",".").")", "icon" => "error"];
                              }else{
                                    $data_input = [];
                                    foreach ($data_penggunaan as $id_penyerahan => $value_dp) {
                                          $data_input[] = [
                                                "id_penyerahan" => $id_penyerahan,
                                                "tanggal" => date("Y-m-d H:i:s"),
                                                "kode_tahanan" => $kode_tahanan,
                                                "data_belanja" => json_encode($daftar_belanja),
                                                "saldo_awal" => $value_dp["saldo_awal"],
                                                "penggunaan" => $value_dp["penggunaan"],
                                                "total_sisa" => $value_dp["total_sisa"],
                                                "bukti" => base_url("upload/bukti_belanja_manual/".$filename_bukti),
                                                "is_hutang" => "Tidak",
                                          ];
                                    }
                                    if(!empty($data_input)){
                                          $action = $this->admin_model->insertimport("belanja_uang_tunai",$data_input);
                                          if($action){
                                                $this->admin_model->kurangi_stok($nama_barang, $qty);
                                                $fb = ["status" => 200, "title" => "Sukses", "res" => "Data berhasil disimpan<br>".$image_status, "icon" => "success"];
                                          }else{
                                                $fb = ["status" => 500, "title" => "Gagal", "res" => "Data gagal disimpan<br>".$image_status, "icon" => "error"];
                                          }
                                    }else{
                                          $fb = ["status" => 500, "title" => "Gagal", "res" => "Data input kosong", "icon" => "error"];
                                    }
                              }
                        }
                  }else{
                        $fb = ["status" => 500, "title" => "Gagal", "res" => "Data barang kosong", "icon" => "error"];
                  }
            }
            echo json_encode($fb);
            die();
      }

      // ==========================================================
      // PEMBELI NON-WBP (pembeli umum/luar, bukan warga binaan)
      // Pembayaran selalu tunai, tidak menyentuh saldo/penyimpanan uang
      // WBP manapun - hanya mengurangi stok & tercatat di rekap penjualan.
      // ==========================================================
      public function use_money_non_wbp()
      {
            header("Content-Type:text/event-stream");
            $nama_barang_post = explode(",",$this->input->post("nama_barang"));
            foreach ($nama_barang_post as $key => $value) {
                  if(!empty($value)){
                        $nama_barang[] = $value;
                  }
            }
            $qty_post = explode(",",$this->input->post("qty"));
            foreach ($qty_post as $key => $value) {
                  if(!empty($value)){
                        $qty[] = $value;
                  }
            }
            $total_post = explode(",",str_replace(".","",$this->input->post("total")));
            foreach ($total_post as $key => $value) {
                  if(!empty($value)){
                        $total[] = $value;
                  }
            }

            if(empty($_FILES["bukti"]["name"]) || empty($nama_barang)){
                  echo json_encode(["status" => 500, "title" => "Gagal", "res" => "Data barang / bukti foto tidak boleh kosong", "icon" => "error"]);
                  die();
            }

            $validasi_stok = $this->admin_model->validasi_stok($nama_barang, $qty);
            if($validasi_stok !== TRUE){
                  echo json_encode(["status" => 500, "title" => "Gagal", "res" => $validasi_stok, "icon" => "error"]);
                  die();
            }

            $filename_bukti = hash("ripemd160",time()).".jpeg";
            $config_bukti = array(
                  "upload_path" => "./upload/bukti_belanja_manual/",
                  "allowed_types" => "jpg|jpeg|png|",
                  "file_name" => $filename_bukti,
            );
            $this->load->library('upload', $config_bukti);
            $upload_bukti = $this->upload->initialize($config_bukti);
            if(!$this->upload->do_upload('bukti')){
                  echo json_encode(["status" => 500, "title" => "Gagal", "res" => "Bukti foto gagal diupload: ".strip_tags($this->upload->display_errors()), "icon" => "error"]);
                  die();
            }
            if(!$upload_bukti){
                  $image_data = $this->upload->data();
                  $config_bukti['image_library'] = 'gd2';
                  $config_bukti['source_image'] = $image_data['full_path'];
                  $config_bukti['maintain_ratio'] = TRUE;
                  $config_bukti['width'] = 100;
                  $config_bukti['height'] = 100;
                  $this->load->library('image_lib', $config_bukti);
                  $this->image_lib->resize();
            }

            $grand_total = 0;
            $daftar_belanja = [];
            foreach ($nama_barang as $key => $value) {
                  $qty_barang = !empty($qty[$key]) ? " (".$qty[$key].")" : "";
                  $total_barang = !empty($total[$key]) ? str_replace(".","",$total[$key])*1 : 0;
                  $grand_total += $total_barang;
                  $daftar_belanja[$value.$qty_barang] = $total_barang;
            }

            // Dicatat di belanja_uang_tunai TANPA id_penyerahan/kode_tahanan (tidak
            // terkait deposit WBP manapun) supaya tetap muncul di rekap penjualan
            // tunai, tapi tidak pernah memotong/mengurangi saldo WBP manapun.
            $data_input = [
                  "id_penyerahan" => NULL,
                  "tanggal" => date("Y-m-d H:i:s"),
                  "kode_tahanan" => "NON-WBP",
                  "data_belanja" => json_encode($daftar_belanja),
                  "saldo_awal" => 0,
                  "penggunaan" => $grand_total,
                  "total_sisa" => 0,
                  "bukti" => base_url("upload/bukti_belanja_manual/".$filename_bukti),
                  "is_hutang" => "Tidak",
            ];
            $this->admin_model->insert_data("belanja_uang_tunai",$data_input);
            if($this->db->insert_id()){
                  $this->admin_model->kurangi_stok($nama_barang, $qty);
                  echo json_encode(["status" => 200, "title" => "Sukses", "res" => "Penjualan ke pembeli Non-WBP berhasil disimpan", "icon" => "success"]);
            }else{
                  echo json_encode(["status" => 500, "title" => "Gagal", "res" => "Data gagal disimpan", "icon" => "error"]);
            }
            die();
      }

      public function verify_pin()
      {
            $pin       = $this->input->post("pin");
            $code_napi = $this->input->post("code_napi");

            if(empty($pin) || empty($code_napi)){
                  echo json_encode(["status" => 400, "msg" => "Data tidak lengkap"]);
                  die();
            }

            // Ambil data tahanan berdasarkan code_napi, cek kolom "pin"
            $tahanan = $this->admin_model->get_data_select(
                  "tahanan",
                  "code_napi, pin",
                  "code_napi = '$code_napi'",
                  "row"
            );

            if(empty($tahanan)){
                  echo json_encode(["status" => 404, "msg" => "WBP tidak ditemukan"]);
                  die();
            }

            // PIN disimpan sebagai MD5 di database
            if(md5($pin) === $tahanan->pin){
                  echo json_encode(["status" => 200, "msg" => "PIN benar"]);
            }else{
                  echo json_encode(["status" => 401, "msg" => "PIN salah"]);
            }
            die();
      }

      public function use_money_digital()
      {
            $nama_barang_post = explode(",",$this->input->post("nama_barang"));
            foreach ($nama_barang_post as $key => $value) {
                  if(!empty($value)){
                        $nama_barang[] = $value;
                  }
            }
            $qty_post = explode(",",$this->input->post("qty"));
            foreach ($qty_post as $key => $value) {
                  if(!empty($value)){
                        $qty[] = $value;
                  }
            }
            $total_post = explode(",",str_replace(".","",$this->input->post("total")));
            foreach ($total_post as $key => $value) {
                  if(!empty($value)){
                        $total[] = $value;
                  }
            }
            $code_napi = $this->input->post("code_napi");
            // Bukti foto opsional untuk digital, PIN sudah jadi verifikasi
            $filename_bukti = "-";
            $image_status   = "";
            if(!empty($_FILES["bukti"]["name"])){
                  $fn = hash("ripemd160",time()).".jpeg";
                  $cfg = array(
                        "upload_path" => "./upload/bukti_belanja_digital/",
                        "allowed_types" => "jpg|jpeg|png|",
                        "file_name" => $fn,
                  );
                  $this->load->library('upload', $cfg);
                  $this->upload->initialize($cfg);
                  if($this->upload->do_upload('bukti')){
                        $filename_bukti = base_url("upload/bukti_belanja_digital/".$fn);
                  }else{
                        $image_status = "Namun bukti foto gagal di upload";
                  }
            }
            if(!empty($nama_barang)){
                        $total_belanja = 0;
                        foreach ($nama_barang as $key => $value) {
                              if(!empty($qty[$key])){
                                    $qty_barang = " (".$qty[$key].")";
                              }else{
                                    $qty_barang = "";
                              }

                              if(!empty($total[$key])){
                                    $total_barang = str_replace(".","",$total[$key])*1;
                              }else{
                                    $total_barang = 0;
                              }
                              $total_belanja += $total_barang;
                              $daftar_belanja[$value.$qty_barang] = $total_barang;
                        }

                        $belanja_koperasi["Belanja Warung SIPIRMAN (Oleh WBP)"] = $daftar_belanja;

                        $validasi_stok = $this->admin_model->validasi_stok($nama_barang, $qty);
                        if($validasi_stok !== TRUE){
                              $fb = ["status" => 500, "title" => "Gagal", "res" => $validasi_stok, "icon" => "error"];
                        }else{
                              //CHECK PENYIMPANAN UANG
                              $riwayat_penyimpanan = $this->admin_model->get_data_select("penyimpanan_uang","id,jumlah_uang,(SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id) as total_penggunaan,(jumlah_uang-IF((SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id) > 0,(SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id),0)) as sisa_uang","kode_tahanan = '$code_napi' HAVING sisa_uang >= 1 AND id != '' ORDER BY tanggal DESC LIMIT 0,20","result");

                              // Alokasikan belanja ke setoran (penyimpanan_uang) yang masih tersisa (FIFO,
                              // untuk keperluan histori/audit per setoran). Jika saldo digital tidak
                              // cukup untuk menutup seluruh belanja, transaksi ditolak (tidak boleh
                              // berhutang).
                              $data_penggunaan = [];
                              $sisa_penggunaan = $total_belanja;
                              if(!empty($riwayat_penyimpanan)){
                                    foreach (array_reverse($riwayat_penyimpanan) as $rp) {
                                          if(!empty($rp->sisa_uang)){
                                                if($sisa_penggunaan > 0){
                                                      if($rp->sisa_uang > $sisa_penggunaan){
                                                            $data_penggunaan[$rp->id]["saldo_awal"] = $rp->sisa_uang;
                                                            $data_penggunaan[$rp->id]["total_penggunaan"] = $sisa_penggunaan;
                                                            $data_penggunaan[$rp->id]["saldo_akhir"] = $rp->sisa_uang-$sisa_penggunaan;
                                                            $sisa_penggunaan -= $sisa_penggunaan;
                                                      }else{
                                                            $data_penggunaan[$rp->id]["saldo_awal"] = $rp->sisa_uang;
                                                            $data_penggunaan[$rp->id]["total_penggunaan"] = $rp->sisa_uang;
                                                            $data_penggunaan[$rp->id]["saldo_akhir"] = 0;
                                                            $sisa_penggunaan -= $rp->sisa_uang;
                                                      }
                                                }
                                          }
                                    }
                              }

                              if($sisa_penggunaan > 0){
                                    // Saldo digital WBP tidak mencukupi, pembelian ditolak.
                                    $fb = ["status" => 500, "title" => "Gagal", "res" => "Saldo digital WBP tidak mencukupi (kurang Rp. ".number_format($sisa_penggunaan,0,"",".").")", "icon" => "error"];
                              }else{
                                    $data_input = [];
                                    foreach ($data_penggunaan as $id_uang_masuk => $value_dp) {
                                          $data_input[] = [
                                                "id_uang_masuk" => $id_uang_masuk,
                                                "tanggal" => date("Y-m-d H:i:s"),
                                                "kode_tahanan" => $code_napi,
                                                "penggunaan" => json_encode($belanja_koperasi),
                                                "saldo_awal" => $value_dp["saldo_awal"],
                                                "total_penggunaan" => $value_dp["total_penggunaan"],
                                                "saldo_akhir" => $value_dp["saldo_akhir"],
                                                "bukti" => $filename_bukti,
                                                "status" => "Need Confirm",
                                                "is_hutang" => "Tidak",
                                          ];
                                    }

                                    if(!empty($data_input)){
                                          $this->admin_model->insertimport("penggunaan_uang",$data_input);
                                          $affected = $this->db->affected_rows();
                                          if($affected >= 0){
                                                $this->admin_model->kurangi_stok($nama_barang, $qty);
                                                $fb = ["status" => 200, "title" => "Sukses", "res" => "Data berhasil disimpan<br>".$image_status, "icon" => "success"];
                                          }else{
                                                $fb = ["status" => 500, "title" => "Gagal", "res" => "Data gagal disimpan", "icon" => "error"];
                                          }
                                    }else{
                                          $fb = ["status" => 500, "title" => "Gagal", "res" => "Data input kosong", "icon" => "error"];
                                    }
                              }
                        }
            }else{
                  $fb = ["status" => 500, "title" => "Gagal", "res" => "Tidak ada barang yang dibeli", "icon" => "error"];
            }
            echo json_encode($fb);
            die();
      }

      public function rekap_penjualan()
      {
            $data["content"]    = "cashier/rekap_penjualan";
            $data["javascript"] = "cashier/rekap_penjualan";
            $data["title"]      = "Rekap Penjualan";
            $this->load->view('layout/index', $data);
      }

      public function terima_pembayaran_digital()
      {
            $id = $this->input->post("id");
            if(empty($id)){
                  echo json_encode(["status" => 500, "msg" => "ID tidak valid"]);
                  die();
            }
            $this->admin_model->update_data("penggunaan_uang", "id = '$id'", ["status" => "Received"]);
            $affected = $this->db->affected_rows();
            if($affected >= 0){
                  echo json_encode(["status" => 200, "msg" => "Pembayaran berhasil dikonfirmasi"]);
            }else{
                  echo json_encode(["status" => 500, "msg" => "Gagal mengkonfirmasi pembayaran"]);
            }
            die();
      }

      // ==========================================================
      // KULAKAN (Pembelian Stok Barang Koperasi dari Tengkulak)
      // ==========================================================
      public function kulakan()
      {
            $data["content"]    = "cashier/kulakan";
            $data["javascript"] = "cashier/kulakan";
            $data["title"]      = "Kulakan";
            $this->load->view('layout/index', $data);
      }

      public function kulakan_input()
      {
            $data["content"]    = "cashier/kulakan_input";
            $data["javascript"] = "cashier/kulakan_input";
            $data["title"]      = "Input Kulakan";
            $this->load->view('layout/index', $data);
      }

      public function simpan_kulakan()
      {
            // array_filter TANPA array_values supaya key tetap sejajar dengan
            // $jumlah_barang / $harga_subtotal (baris kosong bisa saja ada di tengah)
            $nama_barang    = array_filter((array) $this->input->post("nama_barang"));
            $jumlah_barang  = (array) $this->input->post("jumlah_barang");
            // MODIFIKASI: yang diinput sekarang harga sub total (harga borongan dari
            // tengkulak untuk seluruh jumlah kulakan), harga satuan dihitung otomatis
            // (sub total dibagi jumlah kulakan) - bukan lagi diinput manual per satuan.
            $harga_subtotal = (array) $this->input->post("harga_subtotal");

            if(empty($nama_barang)){
                  $this->swal("Gagal","Daftar barang kulakan tidak boleh kosong","error");
                  redirect("kulakan_input");
            }
            if(empty($_FILES["dokumentasi"]["name"])){
                  $this->swal("Gagal","Dokumentasi/nota kulakan wajib diupload","error");
                  redirect("kulakan_input");
            }

            $filename_dokumentasi = hash("ripemd160",time()).".jpeg";
            $config_dokumentasi = array(
                  "upload_path" => "./upload/dokumentasi_kulakan/",
                  "allowed_types" => "jpg|jpeg|png|",
                  "file_name" => $filename_dokumentasi,
            );
            $this->load->library('upload', $config_dokumentasi);
            $upload_dokumentasi = $this->upload->initialize($config_dokumentasi);
            if(!$this->upload->do_upload('dokumentasi')){
                  $this->swal("Gagal","Dokumentasi gagal diupload: ".strip_tags($this->upload->display_errors()),"error");
                  redirect("kulakan_input");
            }
            if(!$upload_dokumentasi){
                  $image_data = $this->upload->data();
                  $config_dokumentasi['image_library'] = 'gd2';
                  $config_dokumentasi['source_image'] = $image_data['full_path'];
                  $config_dokumentasi['maintain_ratio'] = TRUE;
                  $config_dokumentasi['width'] = 400;
                  $this->load->library('image_lib', $config_dokumentasi);
                  $this->image_lib->resize();
            }
            $dokumentasi = base_url("upload/dokumentasi_kulakan/".$filename_dokumentasi);

            $total_biaya = 0;
            $detail = [];
            foreach ($nama_barang as $key => $nb) {
                  $barang = $this->admin_model->get_data_select("data_barang_koperasi","id,kode_barang,nama_barang","nama_barang = '".$this->db->escape_str($nb)."'","row");
                  if(empty($barang)){
                        $this->swal("Gagal","Barang \"".$nb."\" tidak ditemukan di data barang koperasi, silahkan tambahkan dulu di menu Data Barang","error");
                        redirect("kulakan_input");
                  }
                  $jumlah   = !empty($jumlah_barang[$key]) ? (int) str_replace(".","",$jumlah_barang[$key]) : 0;
                  $subtotal = !empty($harga_subtotal[$key]) ? (int) str_replace(".","",$harga_subtotal[$key]) : 0;
                  $harga    = $jumlah > 0 ? (int) round($subtotal / $jumlah) : 0;
                  $total_biaya += $subtotal;
                  $detail[] = [
                        "kode_barang" => $barang->kode_barang,
                        "nama_barang" => $barang->nama_barang,
                        "jumlah_barang" => $jumlah,
                        "harga_tengkulak" => $harga,
                        "subtotal" => $subtotal,
                  ];
            }

            $data_header = [
                  "tanggal" => date("Y-m-d H:i:s"),
                  "dokumentasi" => $dokumentasi,
                  "total_biaya" => $total_biaya,
                  "input_by" => $this->nama,
            ];
            $this->admin_model->insert_data("kulakan", $data_header);
            $id_kulakan = $this->db->insert_id();

            if(!empty($id_kulakan)){
                  foreach ($detail as $d) {
                        $d["id_kulakan"] = $id_kulakan;
                        $this->admin_model->insert_data("kulakan_detail", $d);
                        $this->admin_model->ubah_stok($d["nama_barang"], $d["jumlah_barang"]);
                  }
                  $this->swal("Sukses","Kulakan berhasil disimpan, stok barang sudah bertambah","success");
                  redirect("kulakan");
            }else{
                  $this->swal("Gagal","Kulakan gagal disimpan","error");
                  redirect("kulakan_input");
            }
      }

      public function get_detail_kulakan()
      {
            $id_kulakan = (int) $this->input->post("id_kulakan");
            $detail = $this->admin_model->get_data_select("kulakan_detail","*","id_kulakan = '$id_kulakan'","result");
            echo json_encode($detail);
            die();
      }

      public function delete_kulakan()
      {
            $id = (int) $this->input->post("id");
            if(empty($id)){
                  echo "ID tidak valid";
                  die();
            }
            $kulakan = $this->admin_model->get_data_select("kulakan","*","id = '$id'","row");
            $detail  = $this->admin_model->get_data_select("kulakan_detail","*","id_kulakan = '$id'","result");
            if(!empty($detail)){
                  foreach ($detail as $d) {
                        // Rollback stok yang sudah ditambahkan oleh transaksi kulakan ini
                        $this->admin_model->ubah_stok($d->nama_barang, -$d->jumlah_barang);
                  }
            }
            if(!empty($kulakan->dokumentasi)){
                  $exp_dok = explode("/",$kulakan->dokumentasi);
                  if(file_exists(FCPATH."/upload/dokumentasi_kulakan/".end($exp_dok))){
                        unlink(FCPATH."/upload/dokumentasi_kulakan/".end($exp_dok));
                  }
            }
            $this->admin_model->delete_data("kulakan_detail","id_kulakan = '$id'");
            $this->admin_model->delete_data("kulakan","id = '$id'");
            echo "Sukses";
            die();
      }

      // ==========================================================
      // TOP UP SALDO WBP MANDIRI
      // ==========================================================
      public function topup_saldo()
      {
            $data["content"]    = "cashier/topup_saldo";
            $data["javascript"] = "cashier/topup_saldo";
            $data["title"]      = "Top Up Saldo WBP";
            $this->load->view('layout/index', $data);
      }

      public function simpan_topup()
      {
            $code_napi = $this->input->post("code_napi");
            $nominal   = (int) str_replace(".","",$this->input->post("nominal"));
            $pin       = $this->input->post("pin");

            if(empty($code_napi) || empty($nominal) || empty($pin)){
                  echo json_encode(["status" => 500, "title" => "Gagal", "res" => "Data tidak lengkap", "icon" => "error"]);
                  die();
            }

            $tahanan = $this->admin_model->get_data_select("tahanan","nama,pin","code_napi = '".$this->db->escape_str($code_napi)."'","row");
            if(empty($tahanan)){
                  echo json_encode(["status" => 500, "title" => "Gagal", "res" => "WBP tidak ditemukan", "icon" => "error"]);
                  die();
            }
            if(empty($tahanan->pin) || md5($pin) !== $tahanan->pin){
                  echo json_encode(["status" => 401, "title" => "Gagal", "res" => "PIN salah", "icon" => "error"]);
                  die();
            }

            $data_input = [
                  "resi"          => hash("crc32b",date("dmyhis")),
                  "input_by"      => $this->nama,
                  "tanggal"       => date("Y-m-d H:i:s"),
                  "nik"           => "-",
                  "nama_pengirim" => $tahanan->nama,
                  "hubungan"      => "Mandiri",
                  "keluarga_inti" => "Tidak",
                  "kode_tahanan"  => $code_napi,
                  "nama_tahanan"  => $tahanan->nama,
                  "jumlah_uang"   => $nominal,
                  "pesan_penitip" => "Top Up WBP Mandiri",
                  "sumber_dana"   => "Mandiri",
            ];
            $this->admin_model->insert_data("penyimpanan_uang", $data_input);
            if($this->db->insert_id()){
                  echo json_encode(["status" => 200, "title" => "Sukses", "res" => "Top up saldo berhasil, saldo WBP bertambah Rp. ".number_format($nominal,0,"",".") , "icon" => "success"]);
            }else{
                  echo json_encode(["status" => 500, "title" => "Gagal", "res" => "Top up saldo gagal disimpan", "icon" => "error"]);
            }
            die();
      }

      // ==========================================================
      // DAFTAR HUTANG WBP
      // ==========================================================
      public function daftar_hutang()
      {
            $data["content"]    = "cashier/daftar_hutang";
            $data["javascript"] = "cashier/daftar_hutang";
            $data["title"]      = "Daftar Hutang WBP";
            $this->load->view('layout/index', $data);
      }

      // Ubah JSON belanja saldo digital ({"Belanja ... (Oleh ...)": {"ITEM (qty)": harga}})
      // jadi daftar nama barang yang dibeli (dipakai untuk detail hutang).
      private function format_belanja_digital($json)
      {
            $decoded = json_decode($json, true);
            $out = "";
            if(!empty($decoded)){
                  foreach ($decoded as $items) {
                        if(is_array($items)){
                              foreach ($items as $item => $harga) {
                                    $out .= "- ".$item."<br>";
                              }
                        }
                  }
            }
            return !empty($out) ? $out : "-";
      }

      // Ubah JSON belanja uang tunai ({"ITEM (qty)": harga}) jadi daftar nama barang.
      private function format_belanja_tunai($json)
      {
            $decoded = json_decode($json, true);
            $out = "";
            if(!empty($decoded)){
                  foreach ($decoded as $item => $harga) {
                        $out .= "- ".$item."<br>";
                  }
            }
            return !empty($out) ? $out : "-";
      }

      // Rincian transaksi yang membuat WBP berhutang (dipakai oleh modal detail
      // di halaman Daftar Hutang WBP): belanja apa saja, saldo awal, dan jumlah hutangnya.
      public function get_detail_hutang()
      {
            $code_napi = $this->db->escape_str($this->input->post("code_napi"));
            $rows = [];

            $digital = $this->admin_model->get_data_select("penggunaan_uang","tanggal,penggunaan,saldo_awal,total_penggunaan,saldo_akhir,status","kode_tahanan = '$code_napi' AND is_hutang = 'Ya' ORDER BY tanggal ASC","result");
            if(!empty($digital)){
                  foreach ($digital as $d) {
                        $rows[] = [
                              "tanggal"       => date("d-m-Y H:i", strtotime($d->tanggal)),
                              "jenis"         => "Saldo Digital",
                              "belanja"       => $this->format_belanja_digital($d->penggunaan),
                              "saldo_awal"    => (int) $d->saldo_awal,
                              "jumlah_hutang" => (int) $d->total_penggunaan,
                              "saldo_akhir"   => (int) $d->saldo_akhir,
                              "status"        => $d->status,
                        ];
                  }
            }

            $tunai = $this->admin_model->get_data_select("belanja_uang_tunai","tanggal,data_belanja,saldo_awal,penggunaan,total_sisa","kode_tahanan = '$code_napi' AND is_hutang = 'Ya' ORDER BY tanggal ASC","result");
            if(!empty($tunai)){
                  foreach ($tunai as $t) {
                        $rows[] = [
                              "tanggal"       => date("d-m-Y H:i", strtotime($t->tanggal)),
                              "jenis"         => "Uang Tunai",
                              "belanja"       => $this->format_belanja_tunai($t->data_belanja),
                              "saldo_awal"    => (int) $t->saldo_awal,
                              "jumlah_hutang" => (int) $t->penggunaan,
                              "saldo_akhir"   => (int) $t->total_sisa,
                              "status"        => "-",
                        ];
                  }
            }

            echo json_encode($rows);
            die();
      }

}
?>