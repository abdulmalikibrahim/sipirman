<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Penyimpanan extends MY_Controller {
      public function index()
      {
		$data["content"] = "penyimpanan";
		$data["javascript"] = "penyimpanan";
		$data["title"] = "Data Penyimpanan";
		$this->load->view('layout/index',$data);
      }
      public function input()
      {
		$data["content"] = "penyimpanan_input";
		$data["javascript"] = "penyimpanan_input";
		$data["title"] = "Input Penyimpanan";
		$this->load->view('layout/index',$data);
      }
      public function save()
      {
            $this->form_validation->set_rules("txt_nama_pengirim","Nama Pengirim","required|trim|xss_clean");
            $this->form_validation->set_rules("nama_pengirim","Penitip","required|trim|xss_clean");
            $this->form_validation->set_rules("code_tahanan","Tahanan","required|trim|xss_clean");
            $this->form_validation->set_rules("nama_tahanan","Tahanan","required|trim|xss_clean");
            $this->form_validation->set_rules("uang","Jumlah Uang","trim|xss_clean");
            if($this->form_validation->run() === TRUE){
                  $nama_pengirim = htmlentities($this->input->post("txt_nama_pengirim"));
                  $nik = htmlentities($this->input->post("nama_pengirim"));
                  $nama_tahanan = htmlentities($this->input->post("nama_tahanan"));
                  $kode_tahanan = htmlentities($this->input->post("code_tahanan"));
      
                  $antiseptik = $this->input->post("antiseptik");
                  $jumlah_antiseptik = $this->input->post("jumlah_antiseptik");
                  $satuan_antiseptik = $this->input->post("satuan_antiseptik");
                  $data_antiseptik = [];
                  if(!empty($antiseptik)){
                        foreach ($antiseptik as $key => $value) {
                              if(!empty($value)){
                                    $data_antiseptik[] = array("nama" => htmlentities($value), "jumlah" => htmlentities(str_replace(".","",$jumlah_antiseptik[$key])), "satuan" => htmlentities($satuan_antiseptik[$key]));
                              }
                        }
                  }
      
                  $obat = $this->input->post("obat");
                  $jumlah_obat = $this->input->post("jumlah_obat");
                  $satuan_obat = $this->input->post("satuan_obat");
                  $data_obat = [];
                  if(!empty($antiseptik)){
                        foreach ($obat as $key => $value) {
                              if(!empty($value)){
                                    $data_obat[] = array("nama" => htmlentities($value), "jumlah" => htmlentities(str_replace(".","",$jumlah_obat[$key])), "satuan" => htmlentities($satuan_obat[$key]));
                              }
                        }
                  }
      
                  $uang = htmlentities(str_replace(".","",$this->input->post("uang")));
      
                  $hubungan = $this->input->post("hubungan");
                  $email_penitip = $this->input->post("txt-email");
                  $keluarga_inti = $this->input->post("keluarga_inti");

                  if(!empty($keluarga_inti)){
                        $dki = "Ya";
                  }else{
                        $dki = "Tidak";
                  }

                  //buat nomor resi
                  $resi = hash("crc32b",date("dmyhis"));
                  if(!empty($data_antiseptik)){
                        $data_input = [
                              "id" => NULL,
                              "resi" => $resi,
                              "input_by" => $this->nama,
                              "tanggal" => date("Y-m-d H:i:s"),
                              "nik" => $nik,
                              "nama_pengirim" => $nama_pengirim,
                              "hubungan" => $hubungan,
                              "keluarga_inti" => $dki,
                              "kode_tahanan" => $kode_tahanan,
                              "nama_tahanan" => $nama_tahanan,
                              "data_antiseptik" => json_encode($data_antiseptik),
                        ];
                        $this->admin_model->insert_data("penyimpanan_antiseptik",$data_input);
                        if($this->db->insert_id()){
                              $status["antiseptik"] = 200;
                        }else{
                              $status["antiseptik"] = "Penyimpanan Antiseptik gagal input<br>";
                        }
                  }else{
                        $status["antiseptik"] = 200;
                  }
                  if(!empty($data_obat)){
                        $data_input = [
                              "id" => NULL,
                              "resi" => $resi,
                              "input_by" => $this->nama,
                              "tanggal" => date("Y-m-d H:i:s"),
                              "nik" => $nik,
                              "nama_pengirim" => $nama_pengirim,
                              "hubungan" => $hubungan,
                              "keluarga_inti" => $dki,
                              "kode_tahanan" => $kode_tahanan,
                              "nama_tahanan" => $nama_tahanan,
                              "data_obat" => json_encode($data_obat),
                        ];
                        $this->admin_model->insert_data("penyimpanan_obat",$data_input);
                        if($this->db->insert_id()){
                              $status["obat"] = 200;
                        }else{
                              $status["obat"] = "Penyimpanan obat gagal input<br>";
                        }
                  }else{
                        $status["obat"] = 200;
                  }
                  if(!empty($uang)){
                        $data_input = [
                              "id" => NULL,
                              "resi" => $resi,
                              "input_by" => $this->nama,
                              "tanggal" => date("Y-m-d H:i:s"),
                              "nik" => $nik,
                              "nama_pengirim" => $nama_pengirim,
                              "hubungan" => $hubungan,
                              "keluarga_inti" => $dki,
                              "kode_tahanan" => $kode_tahanan,
                              "nama_tahanan" => $nama_tahanan,
                              "jumlah_uang" => $uang,
                        ];
                        $this->admin_model->insert_data("penyimpanan_uang",$data_input);
                        if($this->db->insert_id()){
                              $status["uang"] = 200;
                        }else{
                              $status["uang"] = "Penyimpanan uang gagal input<br>";
                        }
                  }else{
                        $status["uang"] = 200;
                  }

                  $status_json = json_encode($status);
                  if(substr_count($status_json,200) >= 2){
                        foreach ($status as $key => $value) {
                              if($key == "antiseptik"){
                                    if(!empty($data_antiseptik)){
                                          $msg_email[] = ucwords($key);
                                    }
                              }
                              
                              if($key == "obat"){
                                    if(!empty($data_obat)){
                                          $msg_email[] = ucwords($key);
                                    }
                              }
                              
                              if($key == "uang"){
                                    if(!empty($uang)){
                                          $msg_email[] = ucwords($key);
                                    }
                              }
                        }
                        $msg_email = implode(", ",$msg_email);
                        // ini_set( 'display_errors', 1 );   
                        // error_reporting( E_ALL );    
                        // $from = "sipirman@rutanrembang.id";    
                        // $to = $email_penitip;
                        // $subject = $resi." No Resi SIPIRMAN";    
                        // $message = "Penyimpanan ".$msg_email." berhasil di registrasi, anda bisa melacak sendiri status barang titipan anda di https://sipirman.rutanrembang.id";
                                    
                        // $headers = "From:" . $from;    
                        // mail($to,$subject,$message, $headers);
      
                        $this->swal("Sukses","Penyimpanan ".$msg_email." Berhasil","success");
                  }else{
                        $this->swal("Gagal","","error");
                  }
            }
            redirect("penyimpanan_input","refresh");
      }

      public function rincian_uang($kode_tahanan)
      {
            $tahanan = $this->admin_model->get_data_select("tahanan","nama","code_napi = '$kode_tahanan'","row");
            if(!empty($tahanan)){
                  $nama_tahanan = $tahanan->nama;
            }else{
                  $nama_tahanan = "";
            }
            $data["content"] = "rincian_uang";
            $data["javascript"] = "rincian_uang";
            $data["title"] = "Rincian Penyimpanan Uang ".$nama_tahanan;
            $this->load->view('layout/index',$data);
      }

      public function penyerahan_uang($kode_tahanan)
      {
            $tahanan = $this->admin_model->get_data_select("tahanan","nama","code_napi = '$kode_tahanan'","row");
            if(!empty($tahanan)){
                  $nama_tahanan = $tahanan->nama;
            }else{
                  $nama_tahanan = "";
            }
            $data["content"] = "penyerahan_uang";
            $data["javascript"] = "penyerahan_uang";
            $data["title"] = "Serahkan Tuna Kepada WBP (".$nama_tahanan.")";
            $this->load->view('layout/index',$data);
      }
      public function penyerahan_uang_simpan()
      {
            $id_uang_masuk = $this->input->get("uangmasuk");
            $saldo_awal = $this->input->get("i");
            $diserahkan = str_replace(".","",$this->input->post("diserahkan"));
            $kode_tahanan = $this->input->post("kode_tahanan");
            $saldo_akhir = $saldo_awal - $diserahkan;
            if(!empty($_FILES["bukti"]["name"])){
                  $filename_bukti = hash("ripemd160",time()).".jpeg";
                  $config_bukti = array(
                        "upload_path" => "./upload/bukti_penyerahan_uang/",
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
                        $data_input = [
                              "id_uang_masuk" => $id_uang_masuk,
                              "tanggal" => date("Y-m-d H:i:s"),
                              "kode_tahanan" => $kode_tahanan,
                              "penggunaan" => json_encode(array("Diserahkan Tunai Ke WBP")),
                              "saldo_awal" => $saldo_awal,
                              "total_penggunaan" => $diserahkan,
                              "saldo_akhir" => $saldo_akhir,
                              "bukti" => base_url("upload/bukti_penyerahan_uang/".$filename_bukti),
                        ];
                        $update_bukti = $this->admin_model->insert_data("penggunaan_uang",$data_input);
                        $this->swal("Sukses","Penyerahan uang tunai berhasil di registrasi","success");
                  }else{
                        $this->swal("Gagal","Penyerahan uang tunai gagal, Foto Bukti gagal di upload","error");
                  }
            }else{
                  $this->swal("Gagal","Tidak ada file foto bukti yang diupload","error");
            }
            redirect("rincian_uang/".$kode_tahanan);
      }

      public function rincian_uang_tunai($kode_tahanan)
      {
            $tahanan = $this->admin_model->get_data_select("tahanan","nama","code_napi = '$kode_tahanan'","row");
            if(!empty($tahanan)){
                  $nama_tahanan = $tahanan->nama;
            }else{
                  $nama_tahanan = "";
            }
            $data["content"] = "rincian_uang_tunai";
            $data["javascript"] = "rincian_uang_tunai";
            $data["title"] = "Serahkan Tuna Kepada WBP (".$nama_tahanan.")";
            $this->load->view('layout/index',$data);
      }
}