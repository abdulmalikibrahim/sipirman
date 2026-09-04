<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Keluarga_inti extends MY_Controller {
      public function login_keluarga()
      {
            $this->form_validation->set_rules("username_keluarga","Username","required|trim|xss_clean");
            $this->form_validation->set_rules("password_keluarga","Password","required|trim|xss_clean");
            $this->form_validation->set_rules("redirect","Redirect","trim|xss_clean");
            if($this->form_validation->run() === TRUE){
                  $username = $this->input->post("username_keluarga");
                  $password = $this->input->post("password_keluarga");
                  $redirect = $this->input->post("redirect");
                  if(!empty($redirect)){
                        $redirect = $redirect;
                  }else{
                        $redirect = "saku_wbp";
                  }
                  $check_validasi = $this->admin_model->get_data_select("penitip","*","nik = '$username' AND hp = '$password' OR username = '$username'","row");
                  if(!empty($check_validasi)){
                        if(!empty($check_validasi->password)){
                              echo $check_validasi->password;
					if(password_verify($password, $check_validasi->password)){
                                    $data_sess = [
                                          "user_id" => $check_validasi->id,
                                          "nama" => $check_validasi->nama,
                                          "keluarga_inti" => $check_validasi->keluarga_inti,
                                          "kode_tahanan" => $check_validasi->kode_tahanan,
                                          "level" => 'keluarga',
                                    ];
                                    $this->session->set_userdata($data_sess);
                                    redirect($redirect);
                              }else{
                                    $this->swal("Gagal Login","Username / Password anda salah","error");
                              }
                        }else{
                              $data_sess = [
                                    "user_id" => $check_validasi->id,
                                    "nama" => $check_validasi->nama,
                                    "keluarga_inti" => $check_validasi->keluarga_inti,
                                    "kode_tahanan" => $check_validasi->kode_tahanan,
                                    "level" => 'keluarga',
                              ];
                              $this->session->set_userdata($data_sess);
                              $this->session->set_flashdata("swal",'
                              <script>
                                  swal.fire({
                                      title: "Data Kurang Lengkap",
                                      html: "Anda belum setting username dan password login anda, mohon segera setting username dan password anda sekarang.",
                                      icon: "warning",
                                      confirmButtonText:"Lengkapi Sekarang",
                                  }).then((result) => {
                                    if(result.isConfirmed){
                                          window.location.href = "'.base_url("profile_ki").'";
                                    }
                                  });
                              </script>');
                              redirect($redirect);
                        }
                  }else{
                        $this->swal("Data Tidak Ditemukan","Data yang anda masukkan tidak terdaftar","error");
                  }
            }else{
                  $this->swal("Warning",str_replace("\n","<br>",validation_errors()),"warning");
            }
            redirect("login");
      }

      public function saku_wbp()
      {
            $data["content"] = "keluarga_inti/saku_wbp";
		$data["javascript"] = "keluarga_inti/saku_wbp";
		$data["title"] = "SAKU WBP";
		$this->load->view('layout/index',$data);
      }

      public function saku_wbp_monitor()
      {
            $data["content"] = "keluarga_inti/saku_wbp_monitor";
		$data["javascript"] = "keluarga_inti/saku_wbp_monitor";
		$data["title"] = "MONITOR SAKU WBP KELUARGA INTI";
		$this->load->view('layout/index',$data);
      }
      public function rincian_saku_wbp($kode_tahanan)
      {
            $tahanan = $this->admin_model->get_data_select("tahanan","nama","code_napi = '$kode_tahanan'","row");
            if(!empty($tahanan)){
                  $nama_tahanan = $tahanan->nama;
            }else{
                  $nama_tahanan = "";
            }
            $data["content"] = "keluarga_inti/rincian_saku_wbp";
            // $data["javascript"] = "keluarga_inti/rincian_saku_wbp";
            $data["title"] = "Rincian Penyimpanan Uang ".$nama_tahanan;
            $this->load->view('layout/index',$data);
      }
      public function rincian_saku_wbp_tunai($kode_tahanan)
      {
            $tahanan = $this->admin_model->get_data_select("tahanan","nama","code_napi = '$kode_tahanan'","row");
            if(!empty($tahanan)){
                  $nama_tahanan = $tahanan->nama;
            }else{
                  $nama_tahanan = "";
            }
            $data["content"] = "keluarga_inti/rincian_saku_wbp_tunai";
            $data["javascript"] = "keluarga_inti/rincian_saku_wbp_tunai";
            $data["title"] = "Serahkan Tuna Kepada WBP (".$nama_tahanan.")";
            $this->load->view('layout/index',$data);
      }

      public function profile()
      {
            $data["content"] = "keluarga_inti/profile";
		$data["javascript"] = "keluarga_inti/profile";
		$data["title"] = "Profile";
		$this->load->view('layout/index',$data);
      }

      public function simpan_profile()
      {
            $this->form_validation->set_rules("username","Username","required|trim|xss_clean");
            $this->form_validation->set_rules("password","Password","required|trim|xss_clean");
            if($this->form_validation->run() === TRUE){
                  $username = $this->input->post("username");
                  $password = $this->input->post("password");
                  $check_kelengkapan_data = $this->admin_model->get_data_select("penitip","username,password","id = '".$this->user_id."'","row");
                  if(empty($check_kelengkapan_data->username) && empty($check_kelengkapan_data->password)){
                        //CHECK USERNAME
                        $check_username = $this->admin_model->get_data_select("penitip","id","username = '$username'","row");
                        if(empty($check_username->id)){
                              $data_update = [
                                    "username" => $username,
                                    "password" => password_hash($password,PASSWORD_DEFAULT),
                              ];
                              $update = $this->admin_model->update_data("penitip","id = '".$this->user_id."'",$data_update);
                              if(!$update){
                                    $this->swal("Sukses","Profile berhasil di lengkapi","success");
                              }else{
                                    $this->swal("Gagal","Profile gagal di lengkapi","error");
                              }
                        }else{
                              $this->swal("Gagal","Username sudah terpakai<br>Mohon masukkan username yang lain yang belum terpakai","error");
                        }
                  }else{
                        if($check_kelengkapan_data->username == $username){
                              $this->swal("Gagal","Username sudah terpakai<br>Mohon masukkan username yang lain yang belum terpakai","error");
                        }else{
                              $this->swal("Information","Data anda sudah lengkap","success");
                        }
                  }
            }else{
                  $this->swal("Warning",str_replace("\n","<br>",validation_errors()),"warning");
            }
            redirect("profile_ki");
      }

      public function simpan_username()
      {
            $this->form_validation->set_rules("username","Username","required|trim|xss_clean");
            if($this->form_validation->run() === TRUE){
                  $username = $this->input->post("username");
                  $check_username = $this->admin_model->get_data_select("penitip","id","username = '".$username."'","row");
                  if(!empty($check_username->id)){
                        if($check_username->id == $this->user_id){
                              //CHECK USERNAME
                              $data_update = [
                                    "username" => $username,
                              ];
                              $update = $this->admin_model->update_data("penitip","id = '".$this->user_id."'",$data_update);
                              if(!$update){
                                    $this->swal("Sukses","Username berhasil di rubah","success");
                              }else{
                                    $this->swal("Gagal","Username gagal di rubah","error");
                              }
                        }else{
                              $this->swal("Gagal","Username sudah terpakai<br>Mohon masukkan username yang lain yang belum terpakai","error");
                        }
                  }else{
                        $data_update = [
                              "username" => $username,
                        ];
                        $update = $this->admin_model->update_data("penitip","id = '".$this->user_id."'",$data_update);
                        if(!$update){
                              $this->swal("Sukses","Username berhasil di rubah","success");
                        }else{
                              $this->swal("Gagal","Username gagal di rubah","error");
                        }
                  }
            }else{
                  $this->swal("Warning",str_replace("\n","<br>",validation_errors()),"warning");
            }
            redirect("profile_ki");
      }

      public function simpan_password()
      {
            $this->form_validation->set_rules("password","Password","required|trim|xss_clean");
            if($this->form_validation->run() === TRUE){
                  $password = $this->input->post("password");
                  $data_update = [
                        "password" => password_hash($password,PASSWORD_DEFAULT),
                  ];
                  $update = $this->admin_model->update_data("penitip","id = '".$this->user_id."'",$data_update);
                  if(!$update){
                        $this->swal("Sukses","Password berhasil di rubah","success");
                  }else{
                        $this->swal("Gagal","Password gagal di rubah","error");
                  }
            }else{
                  $this->swal("Warning",str_replace("\n","<br>",validation_errors()),"warning");
            }
            redirect("profile_ki");
      }

      public function warung_sipirman()
      {
            $data["content"] = "keluarga_inti/warung_sipirman";
		$data["javascript"] = "keluarga_inti/warung_sipirman";
		$data["title"] = "Warung Sipirman";
		$this->load->view('layout/index',$data);
      }
      
      public function simpan_belanja()
      {
            header("Content-Type:text/event-stream");
            if(!empty($this->user_id)){
                  $nama_barang = array_filter($this->input->post("nama_barang[]"));
                  $qty = array_filter($this->input->post("qty[]"));
                  $total = array_filter($this->input->post("total[]"));
                  $code_napi = $this->input->post("wbp");
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

                        if($this->keluarga_inti > 0){
                              $belanja_koperasi["Belanja Warung SIPIRMAN (Oleh Keluarga ".$this->nama.")"] = $daftar_belanja;
                        }else{
                              $belanja_koperasi["Belanja Warung SIPIRMAN (Oleh Penitip ".$this->nama.")"] = $daftar_belanja;
                        }

                        $validasi_stok = $this->admin_model->validasi_stok($nama_barang, $qty);
                        if($validasi_stok !== TRUE){
                              $this->swal("Gagal",$validasi_stok,"error");
                        }else{
                              $id_belanja_keluarga = date("YmdHis").$this->user_id;
                              if($this->keluarga_inti > 0){
                                    // Keluarga inti menghabiskan seluruh dompet (saldo digital) WBP,
                                    // dan BOLEH sampai minus/berhutang.
                                    $riwayat_penyimpanan = $this->admin_model->get_data_select("penyimpanan_uang","id,jumlah_uang,(SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id) as total_penggunaan,(jumlah_uang-IF((SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id) > 0,(SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id),0)) as sisa_uang","kode_tahanan = '$code_napi' HAVING sisa_uang >= 1 AND id != '' ORDER BY tanggal DESC LIMIT 0,20","result");

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

                                    $data_input = [];
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
                                                      "status" => "Need Confirm",
                                                      "id_belanja_keluarga" => $id_belanja_keluarga,
                                                      "is_hutang" => "Tidak",
                                                ];
                                          }
                                    }

                                    if($sisa_penggunaan > 0){
                                          // Saldo digital WBP kurang/habis, sisanya dicatat sebagai hutang
                                          $data_input[] = [
                                                "id_uang_masuk" => NULL,
                                                "tanggal" => date("Y-m-d H:i:s"),
                                                "kode_tahanan" => $code_napi,
                                                "penggunaan" => json_encode($belanja_koperasi),
                                                "saldo_awal" => 0,
                                                "total_penggunaan" => $sisa_penggunaan,
                                                "saldo_akhir" => -$sisa_penggunaan,
                                                "status" => "Need Confirm",
                                                "id_belanja_keluarga" => $id_belanja_keluarga,
                                                "is_hutang" => "Ya",
                                          ];
                                    }

                                    if(!empty($data_input)){
                                          $action = $this->admin_model->insertimport("penggunaan_uang",$data_input);
                                          if($action){
                                                $this->admin_model->kurangi_stok($nama_barang, $qty);
                                                $this->swal("Sukses","Daftar belanja berhasil di order, silahkan tunggu konfirmasi dari admin kami.","success");
                                          }else{
                                                $this->swal("Gagal","Daftar belanja gagal di order, silahkan coba kembali","error");
                                          }
                                    }else{
                                          $this->swal("Gagal","Daftar Belanja tidak kosong","error");
                                    }
                              }else{
                                    // Penitip biasa (bukan keluarga inti): hanya boleh pakai dana yang
                                    // dititipkan sendiri, tetap diblokir kalau tidak cukup (tidak ada hutang).
                                    $riwayat_penyimpanan = $this->admin_model->get_data_select("penyimpanan_uang","id,jumlah_uang,(SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id) as total_penggunaan,(jumlah_uang-IF((SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id) > 0,(SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id),0)) as sisa_uang","kode_tahanan = '$code_napi' AND nama_pengirim = '".$this->nama."' HAVING sisa_uang >= 1 AND id != '' ORDER BY tanggal DESC LIMIT 0,20","result");

                                    $total_sisa_uang = $this->admin_model->get_data_select("penyimpanan_uang","SUM(jumlah_uang) as total_uang, SUM((SELECT SUM(total_penggunaan) as total_penggunaan FROM penggunaan_uang WHERE id_uang_masuk=penyimpanan_uang.id)) as total_penggunaan","kode_tahanan = '$code_napi' AND nama_pengirim = '".$this->nama."' AND id != '' ORDER BY tanggal DESC LIMIT 0,20","row");

                                    $total_sisa_uang_digital = $total_sisa_uang->total_uang - $total_sisa_uang->total_penggunaan;
                                    if($total_sisa_uang_digital < $total_belanja){
                                          $this->swal("Gagal","Gagal menyimpan, WBP hanya memiliki simpanan uang sebesar ".number_format($total_sisa_uang_digital,0,"","."),"error");
                                    }else{
                                          $data_penggunaan = [];
                                          if(!empty($riwayat_penyimpanan)){
                                                $sisa_penggunaan = $total_belanja;
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

                                          $data_input = [];
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
                                                            "status" => "Need Confirm",
                                                            "id_belanja_keluarga" => $id_belanja_keluarga,
                                                            "is_hutang" => "Tidak",
                                                      ];
                                                }
                                          }

                                          if(!empty($data_input)){
                                                $action = $this->admin_model->insertimport("penggunaan_uang",$data_input);
                                                if($action){
                                                      $this->admin_model->kurangi_stok($nama_barang, $qty);
                                                      $this->swal("Sukses","Daftar belanja berhasil di order, silahkan tunggu konfirmasi dari admin kami.","success");
                                                }else{
                                                      $this->swal("Gagal","Daftar belanja gagal di order, silahkan coba kembali","error");
                                                }
                                          }else{
                                                $this->swal("Gagal","Daftar Belanja tidak kosong","error");
                                          }
                                    }
                              }
                        }
                  }else{
                        $this->swal("Gagal","Tidak ada barang yang dibeli","error");
                  }
                  redirect("warung_sipirman_ki");
            }else{
                  redirect("logout");
            }
      }

      public function data_barang()
      {
		$data["content"] = "keluarga_inti/data_barang";
		$data["javascript"] = "keluarga_inti/data_barang";
		$data["title"] = "Data Barang Koperasi";
		$this->load->view('layout/index',$data);
      }

      public function status_belanja()
      {
		$data["content"] = "keluarga_inti/status_belanja";
		$data["javascript"] = "keluarga_inti/status_belanja";
		$data["title"] = "Histori dan Status Belanja";
		$this->load->view('layout/index',$data);
      }
}