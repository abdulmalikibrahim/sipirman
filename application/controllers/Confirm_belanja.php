<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Confirm_belanja extends MY_Controller {
      public function home()
      {
		$data["content"] = "confirm_belanja";
		$data["javascript"] = "confirm_belanja";
		$data["title"] = "Konfirmasi Belanja";
		$this->load->view('layout/index',$data);
      }
      public function proses_kasir($p)
      {
            if($p == "deny"){
                  $this->form_validation->set_rules("alasan_discard","Alasan Tolak","required|trim|xss_clean");
            }
            $this->form_validation->set_rules("id_belanja","ID Belanja","required|trim|xss_clean|integer");
            if($this->form_validation->run() === TRUE){
                  $id_belanja = $this->input->post("id_belanja");
                  if($p == "deny"){
                        // Kembalikan stok barang yang sudah dipotong saat order dibuat, sebelum
                        // statusnya diubah jadi Discard. Satu order (id_belanja_keluarga) bisa
                        // punya beberapa baris penggunaan_uang (FIFO per sumber dana) tapi semua
                        // baris menyimpan JSON "penggunaan" yang sama, jadi cukup diproses sekali.
                        $rows_dibatalkan = $this->admin_model->get_data_select("penggunaan_uang","penggunaan","id_belanja_keluarga = '$id_belanja' AND status != 'Discard' LIMIT 1","result");
                        if(!empty($rows_dibatalkan)){
                              $decoded_penggunaan = json_decode($rows_dibatalkan[0]->penggunaan, true);
                              if(!empty($decoded_penggunaan)){
                                    foreach ($decoded_penggunaan as $daftar_item) {
                                          if(is_array($daftar_item)){
                                                foreach ($daftar_item as $item_qty => $harga) {
                                                      if(preg_match('/^(.*) \((\d+)\)$/', $item_qty, $m)){
                                                            $nama_barang_restore = trim($m[1]);
                                                            $jumlah_restore = (int) $m[2];
                                                      }else{
                                                            $nama_barang_restore = trim($item_qty);
                                                            $jumlah_restore = 1;
                                                      }
                                                      $this->admin_model->ubah_stok($nama_barang_restore, $jumlah_restore);
                                                }
                                          }
                                    }
                              }
                        }

                        $alasan_discard = $this->input->post("alasan_discard");
                        $history_date["confirmation"] = date("Y-m-d H:i:s");
                        $data_update = [
                              "status" => "Discard",
                              "tanggal_diserahkan" => json_encode($history_date),
                              "alasan_discard" => htmlentities($alasan_discard),
                        ];
                        $update = $this->admin_model->update_data("penggunaan_uang","id_belanja_keluarga = '$id_belanja'",$data_update);
                        if(!$update){
                              $fb = ["status" => 200, "icon" => "success", "res" => "Berhasil membatalkan pesanan, kami akan mengirim notifikasi ke keluarga.", "title" => "Sukses"];
                        }else{
                              $fb = ["status" => 500, "icon" => "error", "res" => "Gagal membatalkan pesanan, silahkan coba kembali", "title" => "Gagal"];
                        }
                  }else{
                        $history_date["confirmation"] = date("Y-m-d H:i:s");
                        $data_update = [
                              "status" => "Process",
                              "tanggal_diserahkan" => json_encode($history_date),
                        ];
                        $update = $this->admin_model->update_data("penggunaan_uang","id_belanja_keluarga = '$id_belanja'",$data_update);
                        if(!$update){
                              $fb = ["status" => 200, "icon" => "success", "res" => "Berhasil mengirim data ke petugas kasir", "title" => "Sukses"];
                        }else{
                              $fb = ["status" => 500, "icon" => "error", "res" => "Gagal mengirim data ke petugas kasir", "title" => "Gagal"];
                        }
                  }
            }else{
                  $fb = ["status" => 500, "icon" => "warning", "res" => str_replace("\n","<br>",validation_errors()), "title" => "Warning"];
            }
            echo json_encode($fb);
            die();
      }

      public function konfirmasi_belanja_selesai()
      {
            if(!empty($_FILES["bukti"]["name"]) && !empty($this->input->post("id_belanja"))){
                  $filename_bukti = hash("ripemd160",time()).".jpeg";
                  $config_bukti = array(
                        "upload_path" => "./upload/bukti_belanja_digital/",
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
                        
                        $id_belanja = $this->input->post("id_belanja");
                        $data_update = [
                              "status" => "Received",
                              "bukti" => base_url("upload/bukti_belanja_digital/".$filename_bukti),
                              "tanggal_diserahkan" => date("Y-m-d H:i:s"),
                        ];
                        $update = $this->admin_model->update_data("penggunaan_uang","id_belanja_keluarga = '$id_belanja'",$data_update);
                        if(!$update){
                              $fb = ["status" => 200, "title" => "Sukses", "res" => "Data berhasil diupdate","icon" => "success"];
                        }else{
                              $fb = ["status" => 500, "title" => "Gagal", "res" => "Data gagal diupdate","icon" => "error"];
                        }
                  }else{
                        $fb = ["status" => 500, "title" => "Gagal", "res" => "Data gagal diupdate karena gagal upload foto bukti","icon" => "error"];
                  }
            }else{
                  $fb = ["status" => 500, "title" => "Gagal", "res" => "Foto bukti tidak boleh kosong", "icon" => "error"];
            }
            echo json_encode($fb);
            die();
      }
}
?>