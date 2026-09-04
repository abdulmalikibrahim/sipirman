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
                  $data = $this->admin_model->get_data_select("data_barang_koperasi","kode_barang,harga","nama_barang='$nama_barang'","row");
                  if(!empty($data)){
                        $fb = ["status" => 200, "kode_barang" => $data->kode_barang, "harga" => number_format($data->harga,0,"",".")];
                  }else{
                        $fb = ["status" => 200, "kode_barang" => "-", "harga" => "0"];
                  }
            }else{
                  $fb = ["status" => 200, "kode_barang" => "-", "harga" => "0"];
            }
            echo json_encode($fb);
            die();
      }
      public function diserahkan_uang()
      {
            $load = '';
            $code_napi = $this->input->get("code_napi");
            $get_data_diserahkan = $this->admin_model->get_data_select("penggunaan_uang","id,tanggal,total_penggunaan,(SELECT COALESCE(SUM(penggunaan),0) FROM belanja_uang_tunai WHERE belanja_uang_tunai.id_penyerahan=penggunaan_uang.id) as total_belanja_tunai,(total_penggunaan-(SELECT COALESCE(SUM(penggunaan),0) FROM belanja_uang_tunai WHERE belanja_uang_tunai.id_penyerahan=penggunaan_uang.id)) as sisa_uang_tunai","penggunaan LIKE '%Diserahkan Tunai Ke WBP%' AND kode_tahanan = '$code_napi' ORDER BY tanggal DESC LIMIT 0,20","result");
            $get_data_diserahkan = array_reverse($get_data_diserahkan);

            $db_to_array = json_decode(json_encode($get_data_diserahkan),TRUE);
            $total_uang_dipegang = array_sum(array_column($db_to_array,"sisa_uang_tunai"));
            echo $total_uang_dipegang;
            die();
            if(!empty($get_data_diserahkan)){
                  foreach ($get_data_diserahkan as $gds) {
                        $sisa_uang = $this->admin_model->get_data_select("belanja_uang_tunai","SUM(pengguanaan)","id_penyerahan = '".$gds->id."' AND kode_tahanan = '$code_napi' ORDER BY total_sisa ASC","row");
                        if(!empty($sisa_uang->total_sisa)){
                              $load .= '
                              <tr style="cursor:pointer; font-size:10pt;" title="Klik baris untuk memilih" data-id-penyerahan="'.$gds->id.'" data-sisa-uang="'.$sisa_uang->total_sisa.'" onclick="proses_pembayaran(this)">
                                    <td class="text-center">'.date("d-M-Y",strtotime($gds->tanggal)).'</td>
                                    <td class="text-center">'.number_format($sisa_uang->total_sisa,0,"",".").'</td>
                              </tr>';
                        }
                  }
            }else{
                  $load .= '
                  <tr>
                        <td colspan="2" class="text-center">Tidak ada penyerahan uang kepada Tahanan</td>
                  </tr>';
            }
            echo $load;
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
      
                        $get_data_diserahkan = $this->admin_model->get_data_select("penggunaan_uang","id,tanggal,total_penggunaan,(SELECT COALESCE(SUM(penggunaan),0) FROM belanja_uang_tunai WHERE belanja_uang_tunai.id_penyerahan=penggunaan_uang.id) as total_belanja_tunai,(total_penggunaan-(SELECT COALESCE(SUM(penggunaan),0) FROM belanja_uang_tunai WHERE belanja_uang_tunai.id_penyerahan=penggunaan_uang.id)) as sisa_uang_tunai","penggunaan LIKE '%Diserahkan Tunai Ke WBP%' AND kode_tahanan = '$kode_tahanan' ORDER BY tanggal DESC LIMIT 0,20","result");
                        $get_data_diserahkan = array_reverse($get_data_diserahkan);
                        $total_uang_dipegang = 0;
                        foreach ($get_data_diserahkan as $gdd) {
                              $total_uang_dipegang += $gdd->sisa_uang_tunai;
                        }
                        if($total_uang_dipegang >= $grand_total){
                              $sisa_total_belanja = $grand_total;
                              foreach ($get_data_diserahkan as $gdd1) {
                                    // echo $rp->id."\n";
                                    if(!empty($gdd1->sisa_uang_tunai)){
                                          // echo $sisa_penggunaan."\n";
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
                              if(!empty($data_penggunaan)){
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
                                          ];
                                    }
                              }
                              if(!empty($data_input)){
                                    $action = $this->admin_model->insertimport("belanja_uang_tunai",$data_input);
                                    if($action){
                                          $fb = ["status" => 200, "title" => "Sukses", "res" => "Data berhasil disimpan<br>".$image_status, "icon" => "success"];
                                    }else{
                                          $fb = ["status" => 500, "title" => "Gagal", "res" => "Data gagal disimpan<br>".$image_status, "icon" => "error"];
                                    }
                              }else{
                                    $fb = ["status" => 500, "title" => "Gagal", "res" => "Data input kosong", "icon" => "error"];
                              }
                        }else{
                              $fb = ["status" => 500, "title" => "Gagal", "res" => "Uang WBP tidak cukup, WBP hanya memiliki pegangan uang sebesar ".number_format($total_uang_dipegang,0,"","."), "icon" => "error"];
                        }
                  }else{
                        $fb = ["status" => 500, "title" => "Gagal", "res" => "Data barang kosong", "icon" => "error"];
                  }
            }
            echo json_encode($fb);
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

                        //CHECK PENYIMPANAN UANG
                        $riwayat_penyimpanan = $this->admin_model->get_data_select("penyimpanan_uang","id,jumlah_uang,(SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id) as total_penggunaan,(jumlah_uang-IF((SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id) > 0,(SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id),0)) as sisa_uang","kode_tahanan = '$code_napi' HAVING sisa_uang >= 1 AND id != '' ORDER BY tanggal DESC LIMIT 0,20","result");

                        $total_sisa_uang = $this->admin_model->get_data_select("penyimpanan_uang","SUM(jumlah_uang) as total_uang, (SELECT COALESCE(SUM(total_penggunaan),0) FROM penggunaan_uang WHERE id_uang_masuk IN (SELECT id FROM penyimpanan_uang WHERE kode_tahanan = '$code_napi')) as total_penggunaan","kode_tahanan = '$code_napi' AND id != ''","row");

                        $total_sisa_uang_digital = $total_sisa_uang->total_uang - $total_sisa_uang->total_penggunaan;
                        if($total_sisa_uang_digital < $total_belanja){
                              $fb = ["status" => 500, "title" => "Gagal", "res" => "Gagal menyimpan, WBP hanya memiliki simpanan uang sebesar ".number_format($total_sisa_uang_digital,0,"","."), "icon" => "error"];
                        }else{
                              if(!empty($riwayat_penyimpanan)){
                                    $sisa_penggunaan = $total_belanja;
                                    $saldo_akhir_1 = 0;
                                    foreach (array_reverse($riwayat_penyimpanan) as $rp) {
                                          // echo $rp->id."\n";
                                          if(!empty($rp->sisa_uang)){
                                                // echo $sisa_penggunaan."\n";
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
                                    // echo $sisa_penggunaan;
                                    // print_r($data_penggunaan);
                                    if(!empty($data_penggunaan)){
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
                                                ];
                                          }
                                    }

                                    if(!empty($data_input)){
                                          $this->admin_model->insertimport("penggunaan_uang",$data_input);
                                          $affected = $this->db->affected_rows();
                                          if($affected >= 0){
                                                $fb = ["status" => 200, "title" => "Sukses", "res" => "Data berhasil disimpan<br>".$image_status, "icon" => "success"];
                                          }else{
                                                $fb = ["status" => 500, "title" => "Gagal", "res" => "Data gagal disimpan", "icon" => "error"];
                                          }
                                    }else{
                                          $fb = ["status" => 500, "title" => "Gagal", "res" => "Data input kosong", "icon" => "error"];
                                    }
                              }else{
                                    $fb = ["status" => 500, "title" => "Gagal", "res" => "Riwayat penyimpanan tidak ditemukan", "icon" => "error"];
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

}
?>