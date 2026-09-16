<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class My_control extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->user_id = $this->session->userdata("user_id");
		$this->username = $this->session->userdata("username");
		$this->nama = $this->session->userdata("nama");
		$this->level = $this->session->userdata("level");
		$this->tanggal = $this->session->userdata("tanggal");
		$this->tanggal_1 = $this->session->userdata("tanggal_1");
		$this->status = $this->session->userdata("status");
		$this->tanggal_data = $this->session->userdata("tanggal_data");
		$this->periode = $this->periode();
	}
	public function periode()
	{
		$month = date("m");
		switch ($month) {
			case '01':
				$month = "Januari";
				break;
			case '02':
				$month = "Februari";
				break;
			case '03':
				$month = "Maret";
				break;
			case '04':
				$month = "April";
				break;
			case '05':
				$month = "Mei";
				break;
			case '06':
				$month = "Juni";
				break;
			case '07':
				$month = "Juli";
				break;
			case '08':
				$month = "Agustus";
				break;
			case '09':
				$month = "September";
				break;
			case '10':
				$month = "Oktober";
				break;
			case '11':
				$month = "November";
				break;
			case '12':
				$month = "Desember";
				break;
		}
		return $month;
	}
	public function swal($title, $text, $icon)
	{
		$this->session->set_flashdata("swal",'
		<script>
			swal.fire({
				title: "'.$title.'",
				html: "'.$text.'",
				icon: "'.$icon.'"
			});
		</script>');
	}
	// LOGIN SETUP //
	function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public function index()
	{
		if(isset($_POST["btn-login"])){
			$this->form_validation->set_rules("username","Username","trim|xss_clean|required");
			$this->form_validation->set_rules("password","Password","trim|xss_clean|required");
			if($this->form_validation->run() === TRUE){
				$username = $this->input->post("username");
				$valid_account = $this->admin_model->get_data_select("account","id, password, name, level, username","username = '$username'","row");
				if(!empty($valid_account)){
					$p_bcrypt = $valid_account->password;
					if(password_verify($this->input->post("password"), $p_bcrypt)){
						$data = [
							"user_id" => $valid_account->id,
							"username" => $valid_account->username,
							"nama" => $valid_account->name,
							"level" => $valid_account->level,
							"tanggal" => date("Y-m-d"),
							"tanggal_1" => date("Y-m-d"),
							"tanggal_data" => date("Y-m-d"),
							"status" => "Semua",
						];
						$this->session->set_userdata($data);
						if($valid_account->level == "Cashier"){
							redirect("cashier");
						}else if($valid_account->level == "Saku"){
							redirect("saku_dashboard");
						}else if($valid_account->level == "Super Admin"){
							redirect("home_dashboard");
						}else{
							redirect("home");
						}
					}else{
						$this->swal("","Username atau Password anda salah","error");
						$this->session->set_flashdata("login-form",'
						<script>
							$("#tracking-form").hide(200);
							$("#login-form").show(400);
						</script>');
						redirect("login");
					}
				}else{
					$this->swal("","Username atau Password anda salah","error");
					$this->session->set_flashdata("login-form",'
					<script>
						$("#tracking-form").hide(200);
						$("#login-form").show(400);
					</script>');
					redirect("login");
				}
			}else{
				$this->swal("","Username atau Password anda salah","error");
				$this->session->set_flashdata("login-form",'
				<script>
					$("#tracking-form").hide(200);
					$("#login-form").show(400);
				</script>');
				redirect("login");
			}
		}
		$check_ip = $this->admin_model->get_data_select("t_visitors","COUNT(id) as count","date = '".date("Y-m-d")."' AND ip_address = '".$this->get_client_ip()."'","row");
		$data_input = [
			"date" => date("Y-m-d"),
			"ip_address" => $this->get_client_ip(),
		];
		if($check_ip->count <= 0){
			$insert = $this->admin_model->insert_data("t_visitors",$data_input);
		}
		$cv["today"] = $this->admin_model->get_data_select("t_visitors","COUNT(*) as count","date = DATE(NOW())","row");
		$cv["yesterday"] = $this->admin_model->get_data_select("t_visitors","COUNT(*) as count","date = '".date("Y-m-d",strtotime("-1 days"))."'","row");
		$cv["weekly"] = $this->admin_model->get_data_select("t_visitors","COUNT(*) as count","YEARWEEK(date) = YEARWEEK(NOW()) GROUP BY YEARWEEK(date)","row");
		$cv["yearly"] = $this->admin_model->get_data_select("t_visitors","COUNT(*) as count","date LIKE '%".date("Y-m")."%'","row");
		$this->load->view('content/login',$cv);
		if(!empty($this->level)){
			if($this->level == "Super Admin"){
				redirect("home_dashboard");
			}else if($this->level == "Admin"){
				redirect("home");
			}else if($this->level == "Tracer"){
				redirect("home");
			}else if($this->level == "Komandan"){
				redirect("home");
			}else if($this->level == "Cashier"){
				redirect("cashier");
			}else if($this->level == "Saku"){
				redirect("saku_dashboard");
			}else if($this->level == "keluarga"){
				redirect("saku_wbp");
			}
		}
	}
	// END LOGIN SETUP //
	
	// LOGOUT SETUP //
	// LOGOUT SETUP //
	public function logout()
	{
		// 1. Tangkap dulu level user saat ini sebelum session dihapus
		$level = $this->session->userdata("level");

		// 2. Hancurkan semua session
		$this->session->sess_destroy();

		// 3. Cek levelnya, lalu arahkan ke pintu keluar yang sesuai
		if ($level == 'Cashier') {
			redirect("logincashier"); // Balik ke login RUMBANG MART
		} else if ($level == 'Saku') {
			redirect("loginsaku");        // Balik ke login utama SIPIRMAN
		} else {
			redirect("login");        // Balik ke login utama SIPIRMAN
		}
	}
	// END LOGOUT SETUP //
	// END LOGOUT SETUP //
	
	// DASHBOARD SETUP //
	public function set_tanggal_dash()
	{
		$data["tanggal"] = $this->input->post("tanggal");
		$data["tanggal_1"] = $this->input->post("tanggal_1");
		$this->session->set_userdata($data);
		if($this->level == "Super Admin"){
			redirect("home_dashboard");
		}else if($this->level == "Saku"){
			redirect("saku_dashboard");
		}else{
			redirect("home");
		}
	}

	// MODIFIKASI: Mendukung parameter akses untuk Dashboard God Mode (Pendaftaran, P2U, Komandan)
	public function home($akses = 'pendaftaran')
	{
		if(isset($_POST["btn-update"])){
			$resi = $this->input->post("no-resi");
			$status_dokumentasi = $this->input->post("status_dokumentasi");
			
			// MODIFIKASI: Izin akses untuk Komandan ASLI atau Admin di mode Komandan
			if($this->level == "Komandan" || ($this->level == "Admin" && $akses == "komandan")){
				if(!empty($_FILES["foto"]["name"])){
					$filename_foto = hash("ripemd160",$resi).".jpeg";
					$config_foto = array(
						"upload_path" => "./upload/foto_bukti/",
						"allowed_types" => "jpg|jpeg|png|",
						"file_name" => $filename_foto,
					);
					$this->load->library('upload', $config_foto);
					$upload_foto = $this->upload->initialize($config_foto);
					$detail_titipan = $this->admin_model->get_data_select("data_titipan","diterima_p2u","resi = '$resi'","row");
					foreach ($this->input->post("checkbox") as $key => $value) {
						$array_checklist[str_replace("|_|"," ",$key)] = $value;
					};
	
					foreach ($this->input->post("tolak") as $key => $value) {
						$array_tolak[str_replace("|_|"," ",$key)] = str_replace("'","",str_replace('"','',$value));
					};
	
					$penolakan = json_encode($array_tolak);
					$json_checklist = json_encode($array_checklist);

                    $keterangan = str_replace("'","&#39",str_replace('"','&#34',$this->input->post("keterangan")));
					
					$data_update = [
						"diterima_wbp" => date("Y-m-d H:i:s"),
						"status" => "Selesai Diantar",
						"foto_bukti" => $filename_foto,
						"status_dokumentasi" => $status_dokumentasi,
						"pesan_tahanan" => str_replace("'","&#39",str_replace('"','&#34',$this->input->post("pesan-tahanan"))),
						"alasan_tolak_wbp" => $penolakan,
						"list_barang_selesai_antar" => $json_checklist,
                        "keterangan" => $keterangan,
					];
					$update_foto = $this->admin_model->update_data("data_titipan","resi = '$resi'",$data_update);
					if(!$update_foto){
						if ($this->upload->do_upload('foto')) {
							if (!$upload_foto) {
								$image_data = $this->upload->data();
								$config_foto['image_library'] = 'gd2';
								$config_foto['source_image'] = $image_data['full_path'];
								$config_foto['maintain_ratio'] = TRUE;
								$config_foto['width'] = 100;
								$config_foto['height'] = 100;
								$this->load->library('image_lib', $config_foto);
								$this->image_lib->resize();
							}
							$this->swal("Sukses","Proses penitipan barang selesai","success");
						}else{
							$this->swal($this->upload->display_errors()." Code 'F'","","error");
						}
					}
					
					// MODIFIKASI: Redirect kembali ke dashboard spesifik
					redirect(($this->level == "Admin") ? "home/komandan" : "home", "refresh");
				}else{
					$this->swal("Gagal","Mohon masukkan foto serah terima","error");
				}

			// MODIFIKASI: Izin akses untuk Tracer (P2U) ASLI atau Admin di mode P2U
			}else if($this->level == "Tracer" || ($this->level == "Admin" && $akses == "p2u")){
				foreach ($this->input->post("checkbox") as $key => $value) {
					$array_checklist[str_replace("|_|"," ",$key)] = $value;
				};

				foreach ($this->input->post("tolak") as $key => $value) {
					$array_tolak[str_replace("|_|"," ",$key)] = str_replace("'","",str_replace('"','',$value));
				};
				
				$penolakan = json_encode($array_tolak);
				$json_checklist = json_encode($array_checklist);
				$data_update = [
					"pic_komandan" => $this->input->post("pkomandan"),
					"diterima_komandan" => date("Y-m-d H:i:s"),
					"list_barang_komandan" => $json_checklist,
					"alasan_tolak_komandan" => $penolakan,
					"status" => "Diterima Komandan Jaga",
				];
				$update_data = $this->admin_model->update_data("data_titipan","resi = '$resi'",$data_update);
				if(!$update_data){
					$this->swal("Sukses","Status Penitipan berhasil update","success");
				}else{
					$this->swal("Gagal","Status Penitipan gagal update","error");
				}

				// MODIFIKASI: Redirect kembali ke dashboard spesifik
				redirect(($this->level == "Admin") ? "home/p2u" : "home", "refresh");
			}
		}
		
		$data["total_titipan"] = $this->admin_model->get_data_select("data_titipan","id","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","result");
		$data["menunggu"] = $this->admin_model->get_data_select("data_titipan","id","diterima_p2u IS NULL AND tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","result");
		$data["diterima_p2u"] = $this->admin_model->get_data_select("data_titipan","id","diterima_p2u IS NOT NULL AND diterima_komandan IS NULL AND tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","result");
		$data["diterima_komandan"] = $this->admin_model->get_data_select("data_titipan","id","diterima_komandan IS NOT NULL AND diterima_wbp IS NULL AND tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","result");
		$data["diterima_wbp"] = $this->admin_model->get_data_select("data_titipan","id","diterima_wbp IS NOT NULL AND tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","result");
		
		$data["akses"] = $akses;
		$data["content"] = "home";
		$data["javascript"] = "home";
		
		$title_mode = ($this->level == "Admin") ? " - " . ucfirst($akses) : "";
		$data["title"] = "Dashboard" . $title_mode;
		
		$this->load->view('layout/index',$data);
	}
	// END DASHBOARD SETUP //
	
	// DATA TITIPAN SETUP //
	public function set_tanggal_data()
	{
		$data["tanggal"] = $this->input->post("tanggal");
		$data["tanggal_1"] = $this->input->post("tanggal_1");
		$this->session->set_userdata($data);
		redirect("data");
	}
	// DATA TITIPAN SETUP //
	public function cari_status()
	{
		$status = $this->input->get("s");
		if(empty($status)){
			$status == "Semua";
		}
		$data["status"] = $status;
		$this->session->set_userdata($data);
		redirect("data");
	}
	// DATA TITIPAN SETUP //
	public function set_status()
	{
		$data["status"] = $this->input->post("fstatus");
		$this->session->set_userdata($data);
		redirect("data");
	}
	public function data($params = null)
	{
		if(empty($params)){
			$data["content"] = "data";
			$data["javascript"] = "data";
			$data["title"] = "Data Titipan";
			if(!empty($this->status)){
				$status = $this->status;
				if($status == "Semua"){
					$data["data_titipan"] = $this->admin_model->get_data_select("data_titipan","*","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","result");
				}else if($status != "Semua"){
					switch ($status) {
						case 'Menunggu':
							$data["data_titipan"] = $this->admin_model->get_data_select("data_titipan","*","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND diterima_p2u IS NULL AND deleted_date IS NULL","result");
							break;
						case 'Diterima P2U':
							$data["data_titipan"] = $this->admin_model->get_data_select("data_titipan","*","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND diterima_p2u IS NOT NULL AND diterima_komandan IS NULL AND deleted_date IS NULL","result");
							break;
						case 'Diterima Komandan Jaga':
							$data["data_titipan"] = $this->admin_model->get_data_select("data_titipan","*","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND diterima_komandan IS NOT NULL AND diterima_wbp IS NULL AND deleted_date IS NULL","result");
							break;
						case 'Selesai Diantar':
							$data["data_titipan"] = $this->admin_model->get_data_select("data_titipan","*","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND diterima_wbp IS NOT NULL AND deleted_date IS NULL","result");
							break;
					}
				}
			}else{
				$data["data_titipan"] = $this->admin_model->get_data_select("data_titipan","*","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","result");
			}
		}else if($params == "input"){
			if(isset($_POST["btn-simpan"])){
				$nama_pengirim = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("txt_nama_pengirim")));
				$nik = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nama_pengirim")));
				$kode_tahanan = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("code_tahanan")));
				$nama_tahanan = $this->admin_model->get_data_select("tahanan","nama","code_napi = '$kode_tahanan'","row");
				if(!empty($nama_tahanan->nama)){
					$nama_tahanan = $nama_tahanan->nama;
					$pesan_penitip = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("pesan_penitip")));
					$hubungan = $this->input->post("hubungan");
					$email_penitip = $this->input->post("txt-email");
					$keluarga_inti = $this->input->post("keluarga_inti");
					if(!empty($keluarga_inti)){
						$dki = "Ya";
					}else{
						$dki = "Tidak";
					}
					//buat nomor resi
					$check_data = $this->admin_model->get_data_select("data_titipan","id","tanggal LIKE '%".date("Y-m-d")."%'","result");
					$no = count($check_data) + 1;
					$resi = hash("crc32b",date("dmy").sprintf("%03d",$no));
	
					$nama_barang = str_replace("'","",str_replace('"',"",$this->input->post("nama_barang")));
					$satuan_barang = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("satuan_barang")));
					$data_barang = [];
					if(!empty($nama_barang)){
						foreach ($nama_barang as $key => $value) {
							if(!empty($value)){
								$data_barang[$value] = $satuan_barang[$key];
							}
						}
					}
	
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
					if(!empty($obat)){
						foreach ($obat as $key => $value) {
							if(!empty($value)){
								$data_obat[] = array("nama" => htmlentities($value), "jumlah" => htmlentities(str_replace(".","",$jumlah_obat[$key])), "satuan" => htmlentities($satuan_obat[$key]));
							}
						}
					}
			
					$uang = htmlentities(str_replace(".","",$this->input->post("uang")));
					$tanggal_dibuat = date("Y-m-d H:i:s");
	
					//CHECK DATA TITIPAN BARANG
					// Selalu insert ke data_titipan sebagai record induk,
					// data_barang boleh kosong jika hanya titip uang/antiseptik/obat
					$data_input = [
						"id" => NULL,
						"resi" => $resi,
						"input_by" => $this->nama,
						"tanggal" => $tanggal_dibuat,
						"nik" => $nik,
						"nama_pengirim" => $nama_pengirim,
						"hubungan" => $hubungan,
						"keluarga_inti" => $dki,
						"pesan_penitip" => $pesan_penitip,
						"kode_tahanan" => $kode_tahanan,
						"nama_tahanan" => $nama_tahanan,
						"data_barang" => !empty($data_barang) ? json_encode($data_barang) : json_encode([]),
						"status" => "Menunggu",
					];
					$this->admin_model->insert_data("data_titipan",$data_input);
					if($this->db->insert_id()){
						$status["barang"] = 200;
					}else{
						$status["barang"] = "Penyimpanan data titipan gagal input<br>";
					}
	
					//CHECK DATA TITIPAN ANTISEPTIK
					if(!empty($data_antiseptik)){
						$data_input = [
							"id" => NULL,
							"resi" => $resi,
							"input_by" => $this->nama,
							"tanggal" => $tanggal_dibuat,
							"nik" => $nik,
							"nama_pengirim" => $nama_pengirim,
							"hubungan" => $hubungan,
							"keluarga_inti" => $dki,
							"kode_tahanan" => $kode_tahanan,
							"nama_tahanan" => $nama_tahanan,
							"data_antiseptik" => json_encode($data_antiseptik),
							"pesan_penitip" => $pesan_penitip,
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
	
					//CHECK DATA TITIPAN OBAT
					if(!empty($data_obat)){
						$data_input = [
							"id" => NULL,
							"resi" => $resi,
							"input_by" => $this->nama,
							"tanggal" => $tanggal_dibuat,
							"nik" => $nik,
							"nama_pengirim" => $nama_pengirim,
							"hubungan" => $hubungan,
							"keluarga_inti" => $dki,
							"kode_tahanan" => $kode_tahanan,
							"nama_tahanan" => $nama_tahanan,
							"data_obat" => json_encode($data_obat),
							"pesan_penitip" => $pesan_penitip,
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
	
					//CHECK DATA TITIPAN UANG
					if(!empty($uang)){
						$data_input = [
							"id" => NULL,
							"resi" => $resi,
							"input_by" => $this->nama,
							"tanggal" => $tanggal_dibuat,
							"nik" => $nik,
							"nama_pengirim" => $nama_pengirim,
							"hubungan" => $hubungan,
							"keluarga_inti" => $dki,
							"kode_tahanan" => $kode_tahanan,
							"nama_tahanan" => $nama_tahanan,
							"jumlah_uang" => $uang,
							"pesan_penitip" => $pesan_penitip,
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
					if(substr_count($status_json,'200') >= 2){
						$msg_email = [];
						foreach ($status as $key => $value) {
							if($key == "barang"){
								if(!empty($data_barang)){
									$msg_email[] = ucwords($key);
								}
							}
	
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
						ini_set( 'display_errors', 1 );   
						error_reporting( E_ALL );    
						$from = "sipirman@rutanrembang.id";    
						$to = $email_penitip;
						$subject = $resi." No Resi SIPIRMAN";    
						$message = "Berikut adalah no resi titipan anda ".$resi.", anda bisa melacak sendiri status barang titipan anda di https://sipirman.rutanrembang.id";
								
						$headers = "From:" . $from;    
						
						// =========================================================================
						// FIX DIMATIKAN SEMENTARA AGAR TIDAK TERJADI CHROME-ERROR SAAT LOCALHOST
						// =========================================================================
						// mail($to,$subject,$message, $headers);
						// =========================================================================
						
						$this->swal("Sukses","No Resi ".$resi."<br>Penyimpanan ".$msg_email." Berhasil","success");
					}else{
						$this->swal("Gagal","","error");
					}
				}else{
					$this->swal("Gagal","Tahanan tidak terdaftar di database, Mohon gunakan nama tahanan yang sesuai","error");
				}
				
				redirect("input","refresh");
			}else if(is_numeric($params)){
				$data["edit"] = $this->admin_model->get_data_select("data_titipan","*","id = '$params'","row");
			}

			$data["content"] = "data_input";
			$data["javascript"] = "data_input";
			$data["title"] = "Data Input Titipan";
		}
		$this->load->view('layout/index',$data);
	}
	public function delete_data()
	{
		$id = $this->input->post("id");
		$data_update = [
			"deleted_date" => date("Y-m-d"),
		];
		$delete = $this->admin_model->update_data("data_titipan","id = '$id'",$data_update);
		if(!$delete){
			echo "Sukses";
		}else{
			echo "Gagal";
		}
		die();
	}
	
	public function tambah_penitip()
	{
		$nik = $this->input->post("nik");
		$nama = $this->input->post("nama");
		$hp = $this->input->post("hp");
		$check_data_penitip = $this->admin_model->get_data_select("penitip","id","nik = '$nik' OR nama = '$nama' OR hp = '$hp'","row");
		if(empty($check_data_penitip->id)){
			$nama_wbp = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nama_wbp")));
			$keluarga_inti = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("keluarga_inti")));
			foreach ($nama_wbp as $key => $value) {
				$kode_tahanan = $this->admin_model->get_data_select("tahanan","code_napi","nama = '$value'","row");
				if(!empty($kode_tahanan->code_napi)){
					$kode_napi[] = $kode_tahanan->code_napi;
					$wbp[] = $value;
				}
			}
			if(!empty($kode_napi)){
				$kode_tahanan = json_encode($kode_napi);
				$wbp = json_encode($wbp);
			}else{
				$kode_tahanan = NULL;
				$wbp = [];
				foreach ($nama_wbp as $key => $value) {
					if(!empty($value)){
						$wbp[] = $value;
					}
				}
				if(!empty($wbp)){
					$wbp = json_encode($wbp);
				}else{
					$wbp = NULL;
				}
			}
			
			$jadwal_kunjungan = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("jadwal_kunjungan")));
			$pengikut = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("pengikut")));
	
			$data_input = [
				"nama" => $nama,
				"nik" => $nik,
				"hp" => $hp,
				"nama_wbp" => $wbp,
				"kode_tahanan" => $kode_tahanan,
				// "jadwal_kunjungan" => $jadwal_kunjungan,
				// "pengikut" => $pengikut,
				"keluarga_inti" => $keluarga_inti,
			];
			$inp = $this->input->get("inp");
			$this->admin_model->insert_data("penitip",$data_input);
			$id = $this->db->insert_id();
			if($this->db->insert_id()){
				if(!empty($_FILES["foto"]["name"])){
					$filename_foto = hash("ripemd160",$id.time()).".jpeg";
					$config_foto = array(
						"upload_path" => "./upload/foto_diri/",
						"allowed_types" => "jpg|jpeg|png|",
						"file_name" => $filename_foto,
					);
					$this->load->library('upload', $config_foto);
					$upload_foto = $this->upload->initialize($config_foto);
					if ($this->upload->do_upload('foto')) {
						if (!$upload_foto) {
							$image_data = $this->upload->data();
							$config_foto['image_library'] = 'gd2';
							$config_foto['source_image'] = $image_data['full_path']; //get original imag
							$config_foto['maintain_ratio'] = TRUE;
							$config_foto['width'] = 100;
							$config_foto['height'] = 100;
							$this->load->library('image_lib', $config_foto);
							$this->image_lib->resize();
						}
						$foto_update["foto"] = $filename_foto;
						$update_foto = $this->admin_model->update_data("penitip","id = '$id'",$foto_update);
					}else{
						$this->swal($this->upload->display_errors()." Code 'F'","","error");
						$this->admin_model->delete_data("penitip","id = '$id'");
					}
				}
	
				if(!empty($_FILES["foto_ktp"]["name"])){
					$filename_ktp = hash("ripemd160",$id.time()).".jpeg";
					$config_ktp = array(
						"upload_path" => "./upload/ktp/",
						"allowed_types" => "jpg|jpeg|png|",
						"file_name" => $filename_ktp,
					);
					$this->load->library('upload', $config_ktp);
					$upload_ktp = $this->upload->initialize($config_ktp);
					if ($this->upload->do_upload('foto_ktp')) {
						if (!$upload_ktp) {
							$image_data = $this->upload->data();
							$config_ktp['image_library'] = 'gd2';
							$config_ktp['source_image'] = $image_data['full_path']; //get original imag
							$config_ktp['maintain_ratio'] = TRUE;
							$config_ktp['width'] = 100;
							$config_ktp['height'] = 100;
							$this->load->library('image_lib', $config_ktp);
							$this->image_lib->resize();
						}
						$ktp_update["foto_ktp"] = $filename_ktp;
						$update_ktp = $this->admin_model->update_data("penitip","id = '$id'",$ktp_update);
					}else{
						$this->swal($this->upload->display_errors()." : Code 'K'","","error");
						$this->admin_model->delete_data("penitip","id = '$id'");
					}
				}
	
				if(!empty($_FILES["foto_kk"]["name"])){
					$filename_kk = hash("ripemd160",$id.time()).".jpeg";
					$config_kk = array(
						"upload_path" => "./upload/kk/",
						"allowed_types" => "jpg|jpeg|png|",
						"file_name" => $filename_kk,
					);
					$this->load->library('upload', $config_kk);
					$upload_kk = $this->upload->initialize($config_kk);
					if ($this->upload->do_upload('foto_kk')) {
						if (!$upload_kk) {
							$image_data = $this->upload->data();
							$config_kk['image_library'] = 'gd2';
							$config_kk['source_image'] = $image_data['full_path']; //get original imag
							$config_kk['maintain_ratio'] = TRUE;
							$config_kk['width'] = 100;
							$config_kk['height'] = 100;
							$this->load->library('image_lib', $config_kk);
							$this->image_lib->resize();
						}
						$kk_update["foto_kk"] = $filename_kk;
						$update_kk = $this->admin_model->update_data("penitip","id = '$id'",$kk_update);
					}else{
						$this->swal($this->upload->display_errors()." Code 'F'","","error");
						$this->admin_model->delete_data("penitip","id = '$id'");
					}
				}
	
				if(!empty($_FILES["super"]["name"])){
					$filename_super = hash("ripemd160",$id.time()).".jpeg";
					$config_super = array(
						"upload_path" => "./upload/super/",
						"allowed_types" => "jpg|jpeg|png|",
						"file_name" => $filename_super,
					);
					$this->load->library('upload', $config_super);
					$upload_super = $this->upload->initialize($config_super);
					if ($this->upload->do_upload('super')) {
						if (!$upload_super) {
							$image_data = $this->upload->data();
							$config_super['image_library'] = 'gd2';
							$config_super['source_image'] = $image_data['full_path']; //get original imag
							$config_super['maintain_ratio'] = TRUE;
							$config_super['width'] = 100;
							$config_super['height'] = 100;
							$this->load->library('image_lib', $config_super);
							$this->image_lib->resize();
						}
						$super_update["foto_pernyataan"] = $filename_super;
						$update_super = $this->admin_model->update_data("penitip","id = '$id'",$super_update);
					}else{
						$this->swal($this->upload->display_errors()." Code 'F'","","error");
						$this->admin_model->delete_data("penitip","id = '$id'");
					}
				}
	
				if(!$update_foto){
					if(!$update_ktp){
						$this->swal("Sukses","","success");
					}
				}
			}
			if(!empty($inp)){
				redirect("penitip");
			}else{
				redirect("input");
			}
		}else{
			$this->swal("Gagal","Tolong pastikan memasukkan NIK, Nama, & No HP yang belum terdaftar","error");
			redirect("input");
		}
	}
	public function get_penitip()
	{
		$nik = $this->input->get("nik");
		$get_data = $this->admin_model->get_data_select("penitip","*","nik = '$nik'","row");
		if(empty($get_data)){
			$data = array(
				"nik" => "-",
				"nama" => "-",
				"hp" => "-",
				"nama_wbp" => "-",
				"fodir" => "https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg",
				"ktp" => "https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg",
				"keluarga_inti" => 0,
			);
		}else{
			$data = array(
				"nik" => $get_data->nik,
				"nama" => $get_data->nama,
				"hp" => $get_data->hp,
				"nama_wbp" => $get_data->nama_wbp,
				"fodir" => base_url("upload/foto_diri/".$get_data->foto),
				"ktp" => base_url("upload/ktp/".$get_data->foto_ktp),
				"kk" => base_url("upload/kk/".$get_data->foto_kk),
				"super" => base_url("upload/super/".$get_data->foto_pernyataan),
				"keluarga_inti" => ($get_data->keluarga_inti*1),
			);
		}
		echo json_encode($data);
	}
	public function get_narapidana()
	{
		$nama = $this->input->post("nama");
		$get_data = $this->admin_model->get_data_db_select("napi","tahanan","*","nama_tahanan = '$nama'","row");
		if(empty($get_data)){
			$data = array(
				"ttl" => "-",
				"jenis_kelamin" => "-",
				"pekerjaan" => "-",
				"alamat" => "-",
				"pasal_tuduhan" => "-",
				"fopi" => "https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg",
			);
		}else{
			$data = array(
				"ttl" => $get_data->tempat_lahir.", ".date("d M Y",strtotime($get_data->tanggal_lahir)),
				"jenis_kelamin" => $get_data->jenis_kelamin,
				"pekerjaan" => $get_data->pekerjaan,
				"alamat" => $get_data->alamat,
				"pasal_tuduhan" => $get_data->pasal_tuduhan,
				"fopi" => "https://simita.rutanrembang.id/uploads/tahanan/".$get_data->foto,
			);
		}
		echo json_encode($data);
	}
	public function get_narapidana_new()
	{
		$code_napi = $this->input->post("code_napi");
		$get_data = $this->admin_model->get_data_select("tahanan","*","code_napi = '$code_napi'","row");
		if(empty($get_data)){
			$data = array(
				"nama_tahanan" => "-",
				"nama_ayah" => "-",
				"jenis_kelamin" => "-",
				"fopi" => "https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg",
			);
		}else{
			$data = array(
				"nama_tahanan" => $get_data->nama,
				"nama_ayah" => $get_data->nama_ayah,
				"jenis_kelamin" => $get_data->jenis_kelamin,
				"fopi" => "https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg",
			);
		}
		echo json_encode($data);
	}
	public function edit_titipan($params)
	{
		if(is_numeric($params)){
			$data["edit"] = $this->admin_model->get_data_select("data_titipan","*","id = '$params'","row");
			if(isset($_POST["btn-simpan"])){
				$nama_pengirim = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("txt_nama_pengirim")));
				$nik = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nama_pengirim")));
				$nama_tahanan = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nama_tahanan")));
				$nama_barang = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nama_barang")));
				$satuan_barang = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("satuan_barang")));
				$pesan_penitip = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("pesan_penitip")));
				$hubungan = $this->input->post("hubungan");
				$email_penitip = $this->input->post("txt-email");
				$keluarga_inti = $this->input->post("keluarga_inti");
				foreach ($nama_barang as $key => $value) {
					$data_barang[$value] = $satuan_barang[$key];
				}
				if(!empty($keluarga_inti)){
					$dki = "Ya";
				}else{
					$dki = "Tidak";
				}
				$data_edit = [
					"nik" => $nik,
					"nama_pengirim" => $nama_pengirim,
					"hubungan" => $hubungan,
					"keluarga_inti" => $dki,
					"pesan_penitip" => $pesan_penitip,
					"nama_tahanan" => $nama_tahanan,
					"data_barang" => json_encode($data_barang),
				];
				$update = $this->admin_model->update_data("data_titipan","id = '$params'",$data_edit);
				if(!$update){
					$this->swal("Sukses","","success");
				}else{
					$this->swal("Gagal","","error");
				}
				redirect("data");
			}
			$data["content"] = "data_input";
			$data["javascript"] = "data_input";
			$data["title"] = "Edit Titipan";
			$this->load->view('layout/index',$data);
		}else{
			redirect("data");
		}
	}
	public function detail_titipan($params)
	{
		if(is_numeric($params)){
			$data["edit"] = $this->admin_model->get_data_select("data_titipan","*","id = '$params'","row");
			$data["content"] = "detail_titipan";
			$data["javascript"] = "detail_titipan";
			$data["title"] = "Detail Titipan";
			$this->load->view('layout/index',$data);
		}else{
			redirect("data");
		}
	}
	public function delivery()
	{
		$id = $this->input->post("id");
		$pic = $this->input->post("pic");
		$diterima_p2u = date("Y-m-d H:i:s");
		$tgl_antar_js = date("d-m-Y H:i:s");
		$data_update = [
			"pic_p2u" => $pic,
			"diterima_p2u" => $diterima_p2u,
			"status" => "Diterima P2U",
		];
		$update = $this->admin_model->update_data("data_titipan","id = '$id'",$data_update);
		if(!$update){
			$data = [
				"diterima_p2u" => $tgl_antar_js,
				"status" => "Sukses",
			];
			echo json_encode($data);
		}else{
			$data = [
				"diterima_p2u" => $tgl_antar_js,
				"status" => "Gagal",
			];
			echo json_encode($data);
		}
	}
	// END DATA TITIPAN SETUP //

	// ACCOUNT SETUP //
	public function account($params = null)
	{
		if(empty($params)){
			$data["data_account"] = $this->admin_model->get_data_select("account","id,username,name,level","id != '".$this->user_id."'","result");
			$data["content"] = "account";
			$data["javascript"] = "account";
			$data["title"] = "Account";
		}else if($params == "input"){
			if(isset($_POST["btn-simpan"])){
				$data_input = [
					"username" => $this->input->post("username"),
					"password" => password_hash($this->input->post("password"), PASSWORD_DEFAULT),
					"name" => $this->input->post("name"),
					"level" => $this->input->post("level"),
				];
				$this->admin_model->insert_data("account",$data_input);
				if($this->db->insert_id()){
					$this->swal("Sukses","","success");
				}else{
					$this->swal("Gagal","","error");
				}
			}
			$data["content"] = "account_input";
			$data["content"] = "account_input";
			$data["title"] = "Input Account";
		}
		$this->load->view('layout/index',$data);
	}
	public function edit_account($params = null)
	{
		if(is_numeric($params)){
			if(isset($_POST["btn-simpan"])){
				$data_edit = [
					"username" => $this->input->post("username"),
					"name" => $this->input->post("name"),
					"level" => $this->input->post("level"),
				];
				$update = $this->admin_model->update_data("account","id='$params'",$data_edit);
				if(!$update){
					$this->swal("Sukses","","success");
				}else{
					$this->swal("Gagal","","error");
				}
				redirect("account");
			}
			$data["edit"] = $this->admin_model->get_data_select("account","id,username,name,level","id = '$params'","row");
			$data["content"] = "account_input";
			$data["content"] = "account_input";
			$data["title"] = "Input Account";
			$this->load->view('layout/index',$data);
		}else{
			redirect("account");
		}
	}
	public function new_password()
	{
		$id = $this->input->post("id_account");
		$new_pw = password_hash($this->input->post("new_pw"),PASSWORD_DEFAULT);
		$data_update = [
			"password" => $new_pw,
		];
		$update = $this->admin_model->update_data("account","id='$id'",$data_update);
		if(!$update){
			$this->swal("Sukses","Sukses update password","success");
		}else{
			$this->swal("Gagal","Gagal update password","error");
		}
		redirect("account");
	}
	public function delete_account()
	{
		$id = $this->input->post("id");
		$delete = $this->admin_model->delete_data("account", "id = '$id'");
		if(!$delete){
			echo "Sukses";
		}else{
			echo "Gagal";
		}
	}
	// END ACCOUNT SETUP //

	// PROFILE SETUP //
	public function profile()
	{
		if(isset($_POST["btn-simpan"])){
			$data_edit = [
				"username" => $this->input->post("username"),
				"name" => $this->input->post("name"),
			];
			$update = $this->admin_model->update_data("account","id='".$this->user_id."'",$data_edit);
			if(!$update){
				$data_sess = [
					"username" => $this->input->post("username"),
					"nama" => $this->input->post("name"),
				];
				$this->session->set_userdata($data_sess);
				$this->swal("Sukses","","success");
			}else{
				$this->swal("Gagal","","error");
			}
			redirect("profile");
		}
		$data["content"] = "profile";
		$data["javascript"] = "profile";
		$data["title"] = "Profile";
		$this->load->view('layout/index',$data);
	}
	public function new_password_profile()
	{
		$new_pw = password_hash($this->input->post("new_pw"),PASSWORD_DEFAULT);
		$data_update = [
			"password" => $new_pw,
		];
		$update = $this->admin_model->update_data("account","id='".$this->user_id."'",$data_update);
		if(!$update){
			$this->swal("Sukses","Sukses update password","success");
		}else{
			$this->swal("Gagal","Gagal update password","error");
		}
		redirect("profile");
	}
	// END PROFILE SETUP //

	// PENITIP SETUP //
	public function penitip($params = null)
	{
		if(empty($params)){
			$data["content"] = "penitip";
			$data["javascript"] = "penitip";
			$data["title"] = "Data Penitip";
			$data["data_titipan"] = $this->admin_model->get_data_select("penitip","*","id !=","result");
		}else if($params == "input"){
			if(isset($_POST["btn-simpan"])){
				$nama_pengirim = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nama_pengirim")));
				$nama_tahanan = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nama_tahanan")));
				$nama_barang = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nama_barang")));
				$satuan_barang = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("satuan_barang")));
				foreach ($nama_barang as $key => $value) {
					$data_barang[$value] = $satuan_barang[$key];
				}
				//buat nomor resi
				$check_data = $this->admin_model->get_data_select("data_titipan","id","tanggal LIKE '%".date("Y-m-d")."%'","result");
				$no = count($check_data) + 1;
				$resi = hash("crc32b",date("dmy").sprintf("%03d",$no));

				$data_input = [
					"id" => NULL,
					"resi" => $resi,
					"input_by" => $this->nama,
					"tanggal" => date("Y-m-d H:i:s"),
					"nama_pengirim" => $nama_pengirim,
					"nama_tahanan" => $nama_tahanan,
					"data_barang" => json_encode($data_barang),
					"status" => "Menunggu",
				];
				$this->admin_model->insert_data("data_titipan",$data_input);
				if($this->db->insert_id()){
					$this->swal("Sukses","No Resi ".$resi,"success");
				}else{
					$this->swal("Gagal","","error");
				}
			}else if(is_numeric($params)){
				$data["edit"] = $this->admin_model->get_data_select("penitip","*","id = '$params'","row");
			}

			$data["content"] = "penitip_input";
			$data["javascript"] = "penitip_input";
			$data["title"] = "Data Input Penitip";
		}
		$this->load->view('layout/index',$data);
	}
	public function edit_penitip($params)
	{
		if(is_numeric($params)){
			$data["edit"] = $this->admin_model->get_data_select("penitip","*","id = '$params'","row");
			$data["content"] = "penitip_input";
			$data["javascript"] = "penitip_input";
			$data["title"] = "Edit Penitip";
			$this->load->view('layout/index',$data);
		}else{
		    redirect("penitip");
		}
	}
	public function simpan_edit_penitip($params){
		$nama = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nama")));
		$nik = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nik")));
		$hp = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("hp")));
		$nama_wbp = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("nama_wbp")));
		$keluarga_inti = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("keluarga_inti")));
		foreach ($nama_wbp as $key => $value) {
			$kode_tahanan = $this->admin_model->get_data_select("tahanan","code_napi","nama = '$value'","row");
			if(!empty($kode_tahanan->code_napi)){
				$kode_napi[] = $kode_tahanan->code_napi;
				$wbp[] = $value;
			}
		}
		if(!empty($kode_napi)){
			$kode_tahanan = json_encode($kode_napi);
			$wbp = json_encode($wbp);
		}else{
			$kode_tahanan = NULL;
			$wbp = [];
			foreach ($nama_wbp as $key => $value) {
				if(!empty($value)){
					$wbp[] = $value;
				}
			}
			if(!empty($wbp)){
				$wbp = json_encode($wbp);
			}else{
				$wbp = NULL;
			}
		}
		
		$jadwal_kunjungan = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("jadwal_kunjungan")));
		$pengikut = str_replace("'","&#39",str_replace('"',"&#34",$this->input->post("pengikut")));

		$data_edit = [
			"nama" => $nama,
			"nik" => $nik,
			"hp" => $hp,
			"nama_wbp" => $wbp,
			"kode_tahanan" => $kode_tahanan,
			// "jadwal_kunjungan" => $jadwal_kunjungan,
			// "pengikut" => $pengikut,
			"keluarga_inti" => $keluarga_inti,
		];
		$update = $this->admin_model->update_data("penitip","id = '$params'",$data_edit);
		$data["edit"] = $this->admin_model->get_data_select("penitip","*","id = '$params'","row");
		if(!$update){
			$this->swal("Sukses","","success");
			if(!empty($_FILES["foto"]["name"])){
				$unlink_foto = unlink($_SERVER["DOCUMENT_ROOT"]."/upload/foto_diri/".$data["edit"]->foto);
				$filename_foto = $data["edit"]->foto;
				$config_foto = array(
					"upload_path" => "./upload/foto_diri/",
					"allowed_types" => "jpg|jpeg|png|",
					"file_name" => $filename_foto,
				);
				$this->load->library('upload', $config_foto);
				$upload_foto = $this->upload->initialize($config_foto);
				if ($this->upload->do_upload('foto')) {
					if (!$upload_foto) {
						$image_data = $this->upload->data();
						$config_foto['image_library'] = 'gd2';
						$config_foto['source_image'] = $image_data['full_path']; //get original imag
						$config_foto['maintain_ratio'] = TRUE;
						$config_foto['width'] = 100;
						$config_foto['height'] = 100;
						$this->load->library('image_lib', $config_foto);
						$this->image_lib->resize();
					}
				}else{
					$this->swal($this->upload->display_errors()." Code 'F'","","error");
				}
			}

			if(!empty($_FILES["foto_ktp"]["name"])){
				$unlink_foto = unlink($_SERVER["DOCUMENT_ROOT"]."/upload/ktp/".$data["edit"]->foto_ktp);
				$filename_ktp = $data["edit"]->foto_ktp;
				$config_ktp = array(
					"upload_path" => "./upload/ktp/",
					"allowed_types" => "jpg|jpeg|png|",
					"file_name" => $filename_ktp,
				);
				$this->load->library('upload', $config_ktp);
				$upload_ktp = $this->upload->initialize($config_ktp);
				if ($this->upload->do_upload('foto_ktp')) {
					if (!$upload_ktp) {
						$image_data = $this->upload->data();
						$config_ktp['image_library'] = 'gd2';
						$config_ktp['source_image'] = $image_data['full_path']; //get original imag
						$config_ktp['maintain_ratio'] = TRUE;
						$config_ktp['width'] = 100;
						$config_ktp['height'] = 100;
						$this->load->library('image_lib', $config_ktp);
						$this->image_lib->resize();
					}
				}else{
					$this->swal($this->upload->display_errors()." : Code 'K'","","error");
				}
			}

			if(!empty($_FILES["foto_kk"]["name"])){
				if(!empty($data["edit"]->foto_kk)){
					$unlink_foto = unlink($_SERVER["DOCUMENT_ROOT"]."/upload/kk/".$data["edit"]->foto_kk);
					$filename_kk = $data["edit"]->foto_kk;
					$config_kk = array(
						"upload_path" => "./upload/kk/",
						"allowed_types" => "jpg|jpeg|png|",
						"file_name" => $filename_kk,
					);
					$this->load->library('upload', $config_kk);
					$upload_kk = $this->upload->initialize($config_kk);
					if ($this->upload->do_upload('foto_kk')) {
						if (!$upload_kk) {
							$image_data = $this->upload->data();
							$config_kk['image_library'] = 'gd2';
							$config_kk['source_image'] = $image_data['full_path']; //get original imag
							$config_kk['maintain_ratio'] = TRUE;
							$config_kk['width'] = 100;
							$config_kk['height'] = 100;
							$this->load->library('image_lib', $config_kk);
							$this->image_lib->resize();
						}
					}else{
						$this->swal($this->upload->display_errors()." Code 'KK'","","error");
					}
				}else{
					if(!empty($_FILES["foto_kk"]["name"])){
						$filename_kk = hash("ripemd160",$params).".jpeg";
						$config_kk = array(
							"upload_path" => "./upload/kk/",
							"allowed_types" => "jpg|jpeg|png|",
							"file_name" => $filename_kk,
						);
						$this->load->library('upload', $config_kk);
						$upload_kk = $this->upload->initialize($config_kk);
						if ($this->upload->do_upload('foto_kk')) {
							if (!$upload_kk) {
								$image_data = $this->upload->data();
								$config_kk['image_library'] = 'gd2';
								$config_kk['source_image'] = $image_data['full_path']; //get original imag
								$config_kk['maintain_ratio'] = TRUE;
								$config_kk['width'] = 100;
								$config_kk['height'] = 100;
								$this->load->library('image_lib', $config_kk);
								$this->image_lib->resize();
							}
							$kk_update["foto_kk"] = $filename_kk;
							$update_kk = $this->admin_model->update_data("penitip","id = '$params'",$kk_update);
						}else{
							$this->swal($this->upload->display_errors()." Code 'F'","","error");
						}
					}
				}
			}
			
			if(!empty($data["edit"]->foto_pernyataan)){
				if(!empty($_FILES["foto_super"]["name"])){
					$unlink_foto = unlink($_SERVER["DOCUMENT_ROOT"]."/upload/super/".$data["edit"]->foto_pernyataan);
					$filename_super = $data["edit"]->foto_pernyataan;
					$config_super = array(
						"upload_path" => "./upload/super/",
						"allowed_types" => "jpg|jpeg|png|",
						"file_name" => $filename_super,
					);
					$this->load->library('upload', $config_super);
					$upload_super = $this->upload->initialize($config_super);
					if ($this->upload->do_upload('foto_super')) {
						if (!$upload_super) {
							$image_data = $this->upload->data();
							$config_super['image_library'] = 'gd2';
							$config_super['source_image'] = $image_data['full_path']; //get original imag
							$config_super['maintain_ratio'] = TRUE;
							$config_super['width'] = 100;
							$config_super['height'] = 100;
							$this->load->library('image_lib', $config_super);
							$this->image_lib->resize();
						}
					}else{
						$this->swal($this->upload->display_errors()." Code 'S'","","error");
					}
				}
			}else{
				if(!empty($_FILES["foto_super"]["name"])){
					$filename_super = hash("ripemd160",$params).".jpeg";
					$config_super = array(
						"upload_path" => "./upload/super/",
						"allowed_types" => "jpg|jpeg|png|",
						"file_name" => $filename_super,
					);
					$this->load->library('upload', $config_super);
					$upload_super = $this->upload->initialize($config_super);
					if ($this->upload->do_upload('foto_super')) {
						if (!$upload_super) {
							$image_data = $this->upload->data();
							$config_super['image_library'] = 'gd2';
							$config_super['source_image'] = $image_data['full_path']; //get original imag
							$config_super['maintain_ratio'] = TRUE;
							$config_super['width'] = 100;
							$config_super['height'] = 100;
							$this->load->library('image_lib', $config_super);
							$this->image_lib->resize();
						}
						$super_update["foto_pernyataan"] = $filename_super;
						$update_super = $this->admin_model->update_data("penitip","id = '$params'",$super_update);
					}else{
						$this->swal($this->upload->display_errors()." Code 'F'","","error");
					}
				}
			}
			redirect("penitip");
		}else{
			$this->swal("Gagal","","error");
			redirect("penitip/".$params);
		}
	}
	public function delete_penitip()
	{
		$id = $this->input->post("id");
		$data = $this->admin_model->get_data_select("penitip","foto,foto_ktp","id = $id","row");
		$unlink_foto = unlink($_SERVER["DOCUMENT_ROOT"]."/sipirman/upload/foto_diri/".$data->foto);
		$unlink_ktp = unlink($_SERVER["DOCUMENT_ROOT"]."/sipirman/upload/ktp/".$data->foto_ktp);
		$delete = $this->admin_model->delete_data("penitip","id = '$id'");
		if(!$delete){
			echo "Sukses";
		}else{
			echo "Gagal";
		}
	}
	// END PENITIP SETUP //


	public function get_tracking()
	{
	    $this->repairdate();
		$kode_tracking = $this->input->get("resi");
		$get_status = $this->admin_model->get_data_select("data_titipan","*","resi = '$kode_tracking'","row");
		if(empty($get_status)){
			$data = [
				"status" => "gagal",
			];
		}else{
			if($get_status->tanggal != "" && $get_status->diterima_p2u != "" && $get_status->diterima_komandan == "" && $get_status->diterima_wbp == ""){
				$status_antar = "Diterima P2U";
			}else if($get_status->tanggal != "" && $get_status->diterima_p2u != "" && $get_status->diterima_komandan != "" && $get_status->diterima_wbp == ""){
				$status_antar = "Diterima Komandan Jaga";
			}else if($get_status->tanggal != "" && $get_status->diterima_p2u != "" && $get_status->diterima_komandan != "" && $get_status->diterima_wbp != ""){
				$status_antar = "Selesai Diantar";
			}else{
				$status_antar = "Menunggu";
			}

			$data_penyimpanan = [];
			//CHECK PENYIMPANAN UANG
			$penyimpanan_uang = $this->admin_model->get_data_select("penyimpanan_uang","jumlah_uang","resi = '$kode_tracking'","row");
			if(!empty($penyimpanan_uang->jumlah_uang)){
				$data_penyimpanan[] = "<li><font class='text-warning mr-2'>&#9745;</font>Uang Rp.".number_format($penyimpanan_uang->jumlah_uang,0,"",".");
			}
			//CHECK PENYIMPANAN ANTISEPTIK
			$penyimpanan_antiseptik = $this->admin_model->get_data_select("penyimpanan_antiseptik","data_antiseptik","resi = '$kode_tracking'","row");
			if(!empty($penyimpanan_antiseptik->data_antiseptik)){
				$data_antiseptik = json_decode($penyimpanan_antiseptik->data_antiseptik,TRUE);
				if(!empty($data_antiseptik)){
					$data_penyimpanan[] = "<li><font class='text-warning mr-2'>&#9745;</font>Titipan Antiseptik";
					foreach ($data_antiseptik as $key => $value) {
						$data_penyimpanan[] = "<li><font class='text-success mr-1 ml-4'>&#10004;</font>".$value["nama"]." ".$value["jumlah"].$value["satuan"];
					}
				}
			}
			//CHECK PENYIMPANAN OBAT
			$penyimpanan_obat = $this->admin_model->get_data_select("penyimpanan_obat","data_obat","resi = '$kode_tracking'","row");
			if(!empty($penyimpanan_obat->data_obat)){
				$data_obat = json_decode($penyimpanan_obat->data_obat,TRUE);
				if(!empty($data_obat)){
					$data_penyimpanan[] = "<li><font class='text-warning mr-2'>&#9745;</font>Titipan Obat";
					foreach ($data_obat as $key => $value) {
						$data_penyimpanan[] = "<li><font class='text-success mr-1 ml-4'>&#10004;</font>".$value["nama"]." ".$value["jumlah"].$value["satuan"];
					}
				}
			}

			$list_penyimpanan = "";
			if(!empty($data_penyimpanan)){
				$list_penyimpanan = "<ul style='text-align:left; font-size: 11pt;'><li class='mb-1 mt-1'><b>Masuk Penyimpanan :</b></li>";
				foreach ($data_penyimpanan as $key => $value) {
					$list_penyimpanan .= $value;
				}
				$redirect_saku_wbp = "'saku_wbp'";
				$redirect_loker_antiseptik = "'loker_antiseptik'";
				$redirect_kotak_obat = "'kotak_obat'";
				$list_penyimpanan .= '<li><label class="text-danger" style="font-size:10pt;">* Cek status pendistribusian barang tersimpan melalui fitur <a href="javascript:void(0)" style="text-decoration:none;" class="text-info" onclick="open_login(this)" data-redirect="('.$redirect_saku_wbp.')">SAKU WBP</a> / <a href="javascript:void(0)" style="text-decoration:none;" class="text-info" data-redirect="('.$redirect_loker_antiseptik.')">Loker Antiseptik</a> / <a href="javascript:void(0)" style="text-decoration:none;" class="text-info" data-redirect="('.$redirect_kotak_obat.')">Kotak Obat</a></label></li>';
				$list_penyimpanan .= "</ul>";
			}
			if($status_antar == "Menunggu"){
				$list_all_wait = json_decode($get_status->data_barang,true);
				$list_barang_wait = "<ul style='text-align:left; font-size: 11pt;'><li class='mb-1 mt-1'><b>Diterima :</b></li>";
				foreach ($list_all_wait as $key => $value) {
					$list_barang_wait .= "<li><font class='text-success mr-2'>&#9745;</font>".$key." ".$value."</li>";
				}
				$list_barang_wait .= "</ul>";

				$list_view = '
				<div class="w-100 p-3 bg-info" align="center"><h5 class="text-white font-weight-bold m-0">Tracking Barang Titipan</h5></div>
				<div>
					<ul class="events">
						<li align="left">
							<time class="timeactive"></time> 
							<span><strong>Diterima Pendaftaran</strong> '.date("d-M-Y \n H:i:s",strtotime($get_status->tanggal)).' ('.$get_status->input_by.')
							'.$list_barang_wait.$list_penyimpanan.'<label class="mt-1">Status : Menunggu pengantaran</label></span>
						</li>  
						<li align="left">
							<time class="timenotactive"></time> 
							<span><strong>Diterima P2U</strong></span>
						</li>
						<li align="left">
							<time class="timenotactive"></time> 
							<span><strong>Diterima Komandan Jaga</strong></span>
						</li>
						<li align="left">
							<time class="timenotactive"></time> 
							<span><strong>Diterima Oleh WBP</strong></span>
						</li>
					</ul>
				</div>
				<div class="row mt-3 justify-content-center" align="center">
					<div class="col-lg-6">
						<h5>Pesan Anda</h5>
						<p>'.str_replace("\n","<br>",$get_status->pesan_penitip).'</p>
					</div>
				</div>';
			}else if($status_antar == "Diterima P2U"){
				$list_all_wait = json_decode($get_status->data_barang,true);
				$list_barang_wait = "<ul style='text-align:left; font-size: 11pt;'><li class='mb-1 mt-1'><b>Diterima :</b></li>";
				foreach ($list_all_wait as $key => $value) {
					$list_barang_wait .= "<li><font class='text-success mr-2'>&#9745;</font>".$key." ".$value."</li>";
				}
				$list_barang_wait .= "</ul>";
				$list_view = '
				<div class="w-100 p-3 bg-info" align="center"><h5 class="text-white font-weight-bold m-0">Tracking Barang Titipan</h5></div>
				<div>
					<ul class="events">
						<li align="left">
							<time class="timeactive"></time> 
							<span><strong>Diterima Pendaftaran</strong> '.date("d-M-Y \n H:i:s",strtotime($get_status->tanggal)).' ('.$get_status->input_by.')
							'.$list_barang_wait.$list_penyimpanan.'
							<label class="mt-1">Status : Diantar ke P2U</label></span>
						</li>  
						<li align="left">
							<time class="timenotactive"></time> 
							<span><strong>Diterima P2U</strong></span>
						</li>
						<li align="left">
							<time class="timenotactive"></time> 
							<span><strong>Diterima Komandan Jaga</strong></span>
						</li>
						<li align="left">
							<time class="timenotactive"></time> 
							<span><strong>Diterima Oleh WBP</strong></span>
						</li>
					</ul>
				</div>
				<div class="row mt-3 justify-content-center" align="center">
					<div class="col-lg-6">
						<h5>Pesan Anda</h5>
						<p>'.str_replace("\n","<br>",$get_status->pesan_penitip).'</p>
					</div>
				</div>';
			}else if($status_antar == "Diterima Komandan Jaga"){
				
				$list_all_wait = json_decode($get_status->data_barang,true);
				$list_barang_wait = "<ul style='text-align:left; font-size: 11pt;'><li class='mb-1 mt-1'><b>Diterima :</b></li>";
				foreach ($list_all_wait as $key => $value) {
					$list_barang_wait .= "<li><font class='text-success mr-2'>&#9745;</font>".$key." ".$value."</li>";
				}
				$list_barang_wait .= "</ul>";

				$list_all_komandan = json_decode($get_status->data_barang,true);
				$list_check_komandan = json_decode($get_status->list_barang_komandan,true);
				$list_td_komandan = array_diff_key($list_all_komandan,$list_check_komandan);
				$list_barang_komandan = "<ul style='text-align:left; font-size: 11pt;'><li class='mb-1 mt-1'><b>Diterima :</b></li>";
				foreach ($list_check_komandan as $key => $value) {
					$list_barang_komandan .= "<li><font class='text-success mr-2'>&#9745;</font>".$key." ".$value."</li>";
				}
				$list_barang_komandan .= "</ul>";
				if(!empty($list_td_komandan)){
					$list_tdd_komandan = "<ul style='text-align: left'><li class='mb-1 mt-1'><b>Ditolak :</b></li>";
					foreach ($list_td_komandan as $key => $value) {
						$arr_tolak = json_decode($get_status->alasan_tolak_komandan,true);
						$alasan_tolak = $arr_tolak[$key];
						$list_tdd_komandan .= "<li style='font-size: 11pt;'><font class='text-danger mr-2'>&#9746;</font>".$key." ".$value."<br>*".$alasan_tolak."</li>";
					}
					$list_tdd_komandan .= '</ul>';
				}else{
					$list_tdd_komandan = "<ul style='text-align: left'><li class='mb-1 mt-1'><b>Ditolak :</b></li><li'><font class='text-success mr-2'>&#9745;</font>Semua barang diterima</li></ul>";
				}
				
				$list_view = '
				<div class="w-100 p-3 bg-info" align="center"><h5 class="text-white font-weight-bold m-0">Tracking Barang Titipan</h5></div>
				<div>
					<ul class="events">
						<li align="left">
							<time class="timeactive"></time> 
							<span><strong>Diterima Pendaftaran</strong> '.date("d-M-Y \n H:i:s",strtotime($get_status->tanggal)).' ('.$get_status->input_by.')'.$list_barang_wait.$list_penyimpanan.'</span>
						</li>  
						<li align="left">
							<time class="timeactive"></time> 
							<span><strong>Diterima P2U</strong> '.date("d-M-Y \n H:i:s",strtotime($get_status->diterima_p2u)).' ('.$get_status->pic_p2u.')'.$list_barang_komandan.$list_tdd_komandan.'<label class="mt-1">Status : Sedang diantar Komandan</label></span>
							
						</li>
						<li align="left">
							<time class="timenotactive"></time> 
							<span><strong>Diterima Komandan Jaga</strong></span>
						</li>
						<li align="left">
							<time class="timenotactive"></time> 
							<span><strong>Diterima Oleh WBP</strong></span>
						</li>
					</ul>
				</div>
				<div class="row mt-3 justify-content-center" align="center">
					<div class="col-lg-6">
						<h5>Pesan Anda</h5>
						<p>'.str_replace("\n","<br>",$get_status->pesan_penitip).'</p>
					</div>
				</div>';
			}else if($status_antar == "Selesai Diantar"){
				$list_all_wait = json_decode($get_status->data_barang,true);
				$list_barang_wait = "<ul style='text-align:left; font-size: 11pt;'><li class='mb-1 mt-1'><b>Diterima :</b></li>";
				foreach ($list_all_wait as $key => $value) {
					$list_barang_wait .= "<li><font class='text-success mr-2'>&#9745;</font>".$key." ".$value."</li>";
				}
				$list_barang_wait .= "</ul>";

				$list_all_komandan = json_decode($get_status->data_barang,true);
				$list_check_komandan = json_decode($get_status->list_barang_komandan,true);
				$list_td_komandan = json_decode($get_status->alasan_tolak_komandan,true);
				$list_barang_komandan = "<ul style='text-align:left; font-size: 11pt;'><li class='mb-1 mt-1'><b>Diterima :</b></li>";
				foreach ($list_check_komandan as $key => $value) {
					$list_barang_komandan .= "<li><font class='text-success mr-2'>&#9745;</font>".$key." ".$value."</li>";
				}
				$list_barang_komandan .= "</ul>";
				if(!empty($list_td_komandan)){
					$list_tdd_komandan = "<ul style='text-align: left'><li class='mb-1 mt-1'><b>Ditolak :</b></li>";
					foreach ($list_td_komandan as $key => $value) {
						if(!empty($value)){
							$qty = $list_all_komandan[$key];
							$list_tdd_komandan .= "<li style='font-size: 11pt;'><font class='text-danger mr-2'>&#9746;</font>".$key." ".$qty."<br>*".$value."</li>";
						}else{
							$list_tdd_komandan .= "";
						}
					}
					$list_tdd_komandan .= '</ul>';
				}else{
					$list_tdd_komandan = "<ul style='text-align: left'><li class='mb-1 mt-1'><b>Ditolak :</b></li><li'><font class='text-success mr-2'>&#9745;</font>Semua barang diterima</li></ul>";
				}

				$list_all_wbp = json_decode($get_status->data_barang,true);
				$list_check_wbp = json_decode($get_status->list_barang_selesai_antar,true);
				$list_td_wbp = json_decode($get_status->alasan_tolak_wbp,true);
				$list_barang_wbp = "<ul style='text-align:left; font-size: 11pt;'><li class='mb-1'><b>Diterima :</b></li>";
				foreach ($list_check_wbp as $key => $value) {
					$list_barang_wbp .= "<li><font class='text-success mr-2'>&#9745;</font>".$key." ".$value."</li>";
				}
				$list_barang_wbp .= "</ul>";
				if(!empty($list_td_wbp)){
					$list_tdd_wbp = "<ul style='text-align: left'><li class='mb-1 mt-1'><b>Ditolak :</b></li>";
					foreach ($list_td_wbp as $key => $value) {
						if(!empty($value)){
							$qty = $list_all_wbp[$key];
							$list_tdd_wbp .= "<li style='font-size: 11pt;'><font class='text-danger mr-2'>&#9746;</font>".$key." ".$qty."<br>*".$value."</li>";
						}else{
							$list_tdd_wbp .= "";
						}
					}
					$list_tdd_wbp .= '</ul>';
				}else{
					$list_tdd_wbp = "<ul style='text-align: left'><li class='mb-1 mt-1'><b>Ditolak :</b></li><li'><font class='text-success mr-2'>&#9745;</font>Semua barang diterima WBP</li></ul>";
				}

				if($get_status->status_dokumentasi != "Tidak Bersedia" && $get_status->keluarga_inti == "Ya"){
					$foto_bukti = '<img src="'.base_url("upload/foto_bukti/".$get_status->foto_bukti).'" width="100%"><font style="font-size:10pt;">
					<span><strong class="text-danger">PERINGATAN</strong> : Demi keamanan dan privasi, dilarang menyalahgunakan atau membagikan foto di media sosial maupun media lainnya</span></font>';//
				}else{
					$foto_bukti = '<h5 style="font-size: 11pt;">Untuk menjaga privasi Warga Binaan, foto bukti penyerahan hanya dapat dilihat oleh Penitip keluarga inti
					dan telah mendapat persetujuan dari Warga Binaan</h5>';
				}

				$pesan_penitip = $get_status->pesan_penitip;
				if(!empty($pesan_penitip)){
					$pesan_penitip = str_replace("\n","<br>",$get_status->pesan_penitip);
				}else{
					$pesan_penitip = "<i style='font-size:10pt;'>Tidak ada pesan</i>";
				}

				$pesan_tahanan = $get_status->pesan_tahanan;
				if(!empty($pesan_tahanan)){
					$pesan_tahanan = str_replace("\n","<br>",$get_status->pesan_tahanan);
				}else{
					$pesan_tahanan = "<i style='font-size:10pt;'>Tidak ada pesan</i>";
				}

				$list_view = '
				<div class="w-100 p-3 bg-info" align="center"><h5 class="text-white font-weight-bold m-0">Tracking Barang Titipan</h5></div>
				<div>
					<ul class="events">
						<li align="left">
							<time class="timeactive"></time> 
							<span><strong>Diterima Pendaftaran</strong> '.date("d-M-Y \n H:i:s",strtotime($get_status->tanggal)).' ('.$get_status->input_by.')'.$list_barang_wait.$list_penyimpanan.'</span>
						</li> 
						<li align="left">
							<time class="timeactive"></time> 
							<span><strong>Diterima P2U</strong> '.date("d-M-Y \n H:i:s",strtotime($get_status->diterima_p2u)).' ('.$get_status->pic_p2u.')<div class="pt-2">
							'.$list_barang_komandan.$list_tdd_komandan.'<div></span>
						</li>
						<li align="left">
							<time class="timeactive"></time> 
							<span><strong>Diterima Komandan Jaga</strong> '.date("d-M-Y \n H:i:s",strtotime($get_status->diterima_komandan)).' ('.$get_status->pic_komandan.')<div class="pt-2">
							'.$list_barang_wbp.$list_tdd_wbp.'<div></span>
						</li>
						<li align="left">
							<time class="timeactive"></time> 
							<span><strong>Diterima Oleh WBP</strong> <p>'.date("d-M-Y \n H:i:s",strtotime($get_status->diterima_wbp)).' ('.$get_status->nama_tahanan.')</p>
						</li>
					</ul>
				</div>
				<div class="row p-3">
					<div class="col-lg-12 mt-3" align="center">
						<h5 class="p-2 bg-info text-white">Foto Penyerahan</h5>
						'.$foto_bukti.'
					</div>
				</div>
				<div class="row p-3 mt-3 justify-content-center" align="center">
					<div class="col-lg-6">
						<h5 class="p-2 bg-info text-white">Pesan Anda</h5>
						<p style="font-size: 11pt;">'.$pesan_penitip.'</p>
					</div>
					<div class="col-lg-6">
						<h5 class="p-2 bg-info text-white">Pesan Warga Binaan</h5>
						<p style="font-size: 11pt;">'.$pesan_tahanan.'</p>
					</div>
					<div class="col-lg-12 mt-3">
						<h5 class="p-2 bg-info text-white">Keterangan / Catatan Komandan</h5>
						<p style="font-size: 11pt;">'.(!empty($get_status->keterangan) ? $get_status->keterangan : "-").'</p>
					</div>
				</div>';
			}
			$list_view .= '
				<div class="row pt-3 pl-3 pr-3 pb-1 mt-3 justify-content-center" align="center">
					<div class="col-lg-12">
						<h5 class="p-2 bg-info text-white">Saran & Kritik</h5>
					</div>
				</div>
    			<div align="left" class="pb-3 pl-3 pr-3 pt-1" style="font-size: 11pt">
    			Silahkan laporkan apabila terdapat barang yang tidak sampai, atau tidak sesuai, pengantaran lama, atau pelayanan Petugas yang tidak ramah.
    			<br>
    			Identitas Pelapor kami rahasiakan. Terimakasih
    			</div>
                <div class="w-100 pr-3" align="right"><a href="https://wa.me/6282324735454?text=*LAPOR SIPIRMAN!*%0A%0ASilahkan sebutkan nomor resi dan sampaikan keluhan/saran/kritik anda.%0A%0ANomor resi:%0APermasalahan:%0A%0A%0AAnda juga bisa melaporkan permasalahan lainnya melalui layanan ini. *IDENTITAS KAMI RAHASIAKAN*%0A%0A^%0a^%0A(Silahkan lengkapi isian di atas, kemudian kirim. Terimakasih)" target="blank" class="btn btn-danger"><i class="fa fa-whatsapp pr-2"></i>Lapor</a><a href="https://bit.ly/kunjungansurvei" target="_blank" class="btn btn-warning ml-2">Survei Kepuasan</a></div>';
			$data = [
				"status" => "sukses",
				"list_view" => $list_view,
			];
		}
		echo json_encode($data);
	}

	public function detail()
	{
		$id = $this->input->get("id");
		$get_status = $this->admin_model->get_data_select("data_titipan","*","id = '$id'","row");
		if(empty($get_status)){
			$data = [
				"status" => "gagal",
			];
		}else{
			if(empty($get_status->foto_bukti)){
				$foto_bukti = '<span style="font-size: 11pt;">Belum Ada Foto</span>';
			}else{
				$foto_bukti = '<img src="'.base_url("upload/foto_bukti/".$get_status->foto_bukti).'" width="100%">';
			}

			$list_check = json_decode($get_status->data_barang, true) ?: [];
			$list_terima = json_decode($get_status->list_barang_selesai_antar, true) ?: [];
			$list_td = array_diff($list_check, $list_terima);

			$list_barang = "<ul style='text-align: left'>";
			foreach ($list_check as $key => $value) {
				$list_barang .= "<li style='font-size: 11pt;'>".$key." ".$value."</li>";
			}
			$list_barang .= '</ul>';

			if(!empty($list_td)){
				$list_tdd = "<ul style='text-align: left'>";
				foreach ($list_td as $key => $value) {
					$list_tdd .= "<li style='font-size: 11pt;'>".$key." ".$value."</li>";
				}
				$list_tdd .= '</ul>';
			}else{
				$list_tdd = "<i style='font-size:10pt;'>Semua barang tersampaikan ke tahanan</i>";
			}
			
			$pendaftaran = $get_status->tanggal;
			$diterima_p2u = $get_status->diterima_p2u;
			$diterima_komandan = $get_status->diterima_komandan;
			$diterima_wbp = $get_status->diterima_wbp;

			$pesan_penitip = $get_status->pesan_penitip;
			if(!empty($pesan_penitip)){
				$pesan_penitip = str_replace("\n","<br>",$get_status->pesan_penitip);
			}else{
				$pesan_penitip = "<i style='font-size:10pt;'>Tidak ada pesan</i>";
			}

			$pesan_tahanan = $get_status->pesan_tahanan;
			if(!empty($pesan_tahanan)){
				$pesan_tahanan = str_replace("\n","<br>",$get_status->pesan_tahanan);
			}else{
				$pesan_tahanan = "<i style='font-size:10pt;'>Tidak ada pesan</i>";
			}

			if(!empty($pendaftaran)){
				$pendaftaran = date("d-M-Y H:i:s",strtotime($pendaftaran));
				$ppendaftaran = "(".$get_status->input_by.")";
			}else{
				$pendaftaran = "-";
				$ppendaftaran = "";
			}

			if(!empty($diterima_p2u)){
				$diterima_p2u = date("d-M-Y H:i:s",strtotime($diterima_p2u));
				$pditerima_p2u = "(".$get_status->pic_p2u.")";
			}else{
				$diterima_p2u = "-";
				$pditerima_p2u = "";
			}
			
			if(!empty($diterima_komandan)){
				$diterima_komandan = date("d-M-Y H:i:s",strtotime($diterima_komandan));
				$pditerima_komandan = "(".$get_status->pic_komandan.")";
			}else{
				$diterima_komandan = "-";
				$pditerima_komandan = "";
			}
			
			if(!empty($diterima_wbp)){
				$diterima_wbp = date("d-M-Y H:i:s",strtotime($diterima_wbp));
				$pditerima_wbp = "(".$get_status->nama_tahanan.")";
			}else{
				$diterima_wbp = "-";
				$pditerima_wbp = "";
			}
			$list_view = '
			<div class="w-100 p-2 bg-light-info mb-2" align="center">
				<h3 class="m-0 font-weight-bold">Detail Data</h3>
			</div>
			<div class="row">
				<div class="col-lg-6 mb-2" align="center">
					<div style="font-size: 10pt;" class="font-weight-bold w-100">Penitip</div>
					<span style="font-size: 11pt;">'.$get_status->nama_pengirim.'<br>'.$get_status->nik.'</span>
				</div>
				<div class="col-lg-6 mb-2" align="center">
					<div style="font-size: 10pt;" class="font-weight-bold w-100">No Resi</div>
					<span style="font-size: 11pt;">'.$get_status->resi.'</span>
				</div>
				<div class="col-lg-6 mb-2" align="center">
					<div style="font-size: 10pt;" class="font-weight-bold w-100">Masih Di Pendaftaran</div>
					<span style="font-size: 11pt;">'.$pendaftaran.'<br>'.$ppendaftaran.'</span>
				</div>
				<div class="col-lg-6 mb-2" align="center">
					<div style="font-size: 10pt;" class="font-weight-bold w-100">Diterima P2U</div>
					<span style="font-size: 11pt;">'.$diterima_p2u.'<br>'.$pditerima_p2u.'</span>
				</div>
				<div class="col-lg-6 mb-2" align="center">
					<div style="font-size: 10pt;" class="font-weight-bold w-100">Diterima Komandan Jaga</div>
					<span style="font-size: 11pt;">'.$diterima_komandan.'<br>'.$pditerima_komandan.'</span>
				</div>
				<div class="col-lg-6 mb-2" align="center">
					<div style="font-size: 10pt;" class="font-weight-bold w-100">Selesai</div>
					<span style="font-size: 11pt;">'.$diterima_wbp.'<br>'.$pditerima_wbp.'</span>
				</div>
			</div>
			<div class="row p-3">
				<div class="col-lg-6 mt-3 pr-1" align="center">
					<h5 class="p-2 bg-info text-white">Foto Penyerahan</h5>
					'.$foto_bukti.'
				</div>
				<div class="col-lg-6 mt-3 pl-1">
					<h5 class="p-2 bg-info text-white">List Barang</h5>
					'.$list_barang.'
				</div>
			</div>
			<div class="row p-3 mt-3 justify-content-center" align="center">
				<div class="col-lg-6 pr-1">
					<h5 class="p-2 bg-info text-white">Pesan Anda</h5>
					<p style="font-size: 11pt;">'.$pesan_penitip.'</p>
				</div>
				<div class="col-lg-6 pl-1">
					<h5 class="p-2 bg-info text-white">Pesan Warga Binaan</h5>
					<p style="font-size: 11pt;">'.$pesan_tahanan.'</p>
				</div>
				<div class="col-lg-12 mt-3">
					<h5 class="p-2 bg-info text-white">Keterangan / Catatan Komandan</h5>
					<p style="font-size: 11pt;">'.(!empty($get_status->keterangan) ? $get_status->keterangan : "-").'</p>
				</div>
				<!--<div class="col-lg-12 mt-3">
					<h5 class="p-2 bg-info text-white">Barang Tidak Tersampaikan</h5>
					'.$list_tdd.'
				</div>-->
			</div>';
			$data = [
				"status" => "sukses",
				"list_view" => $list_view,
			];
		}
		echo json_encode($data);
	}

	public function get_data_resi()
	{
	    	$this->repairdate();
		$resi = $this->input->get("resi");
		$data_penyimpanan = [];
		//CHECK PENYIMPANAN UANG
		$penyimpanan_uang = $this->admin_model->get_data_select("penyimpanan_uang","jumlah_uang","resi = '$resi'","row");
		if(!empty($penyimpanan_uang->jumlah_uang)){
			$data_penyimpanan[] = "<li style='list-style:none;'><font class='text-warning mr-2'>&#9745;</font>Uang Rp.".number_format($penyimpanan_uang->jumlah_uang,0,"",".");
		}
		//CHECK PENYIMPANAN ANTISEPTIK
		$penyimpanan_antiseptik = $this->admin_model->get_data_select("penyimpanan_antiseptik","data_antiseptik","resi = '$resi'","row");
		if(!empty($penyimpanan_antiseptik->data_antiseptik)){
			$data_antiseptik = json_decode($penyimpanan_antiseptik->data_antiseptik,TRUE);
			if(!empty($data_antiseptik)){
				$data_penyimpanan[] = "<li style='list-style:none;'><font class='text-warning mr-2'>&#9745;</font>Titipan Antiseptik";
				foreach ($data_antiseptik as $key => $value) {
					$data_penyimpanan[] = "<li style='list-style:none;'><font class='text-success mr-1 ml-4'>&#10004;</font>".$value["nama"]." ".$value["jumlah"].$value["satuan"];
				}
			}
		}
		//CHECK PENYIMPANAN OBAT
		$penyimpanan_obat = $this->admin_model->get_data_select("penyimpanan_obat","data_obat","resi = '$resi'","row");
		if(!empty($penyimpanan_obat->data_obat)){
			$data_obat = json_decode($penyimpanan_obat->data_obat,TRUE);
			if(!empty($data_obat)){
				$data_penyimpanan[] = "<li style='list-style:none;'><font class='text-warning mr-2'>&#9745;</font>Titipan Obat";
				foreach ($data_obat as $key => $value) {
					$data_penyimpanan[] = "<li style='list-style:none;'><font class='text-success mr-1 ml-4'>&#10004;</font>".$value["nama"]." ".$value["jumlah"].$value["satuan"];
				}
			}
		}

		$list_penyimpanan = "";
		if(!empty($data_penyimpanan)){
			$list_penyimpanan = "<h4 class='mt-3'><b>Titipan Masuk Penyimpanan</b></h4><ul style='text-align:left; font-size: 13pt;' class='p-0'>";
			foreach ($data_penyimpanan as $key => $value) {
				$list_penyimpanan .= $value;
			}
			$list_penyimpanan .= "</ul>";
		}
		// MODIFIKASI: Sejak dashboard Tracer & Komandan digabung ke dashboard Admin (lihat home()),
		// akun Admin memakai mode "$akses" (p2u/komandan) untuk menentukan checklist mana yang
		// diambil, sama seperti yang sudah dipakai di home() dan content/home.php.
		$akses = $this->input->get("akses");
		$get_data = $this->admin_model->get_data_select("data_titipan","hubungan,nama_pengirim,nama_tahanan,status,data_barang,pesan_penitip,diterima_komandan,list_barang_komandan","resi = '$resi'","row");
		if(!empty($get_data)){
			if($this->level == "Tracer" || ($this->level == "Admin" && $akses == "p2u")){
				if(empty($get_data->diterima_komandan)){
					$array_barang = json_decode($get_data->data_barang,true);
					$checklist = "
					<h4 class='m-0'><b>Informasi</b></h4>
					<label class='container-check pl-0'>
						<p class='m-0'>Dari : ".$get_data->nama_pengirim." (".$get_data->hubungan.")</p>
						<p class='m-0'>Untuk : ".$get_data->nama_tahanan."</p>
					</label>
					<h4><b>Checklist Data Titipan</b></h4>
					";
					foreach ($array_barang as $key => $value) {
						$tolak = "'".str_replace(" ","",$key)."'";
						$checklist .= '
						<label class="container-check">'.$key." ".$value.'
							<input type="checkbox" class="check-box" checked="checked" onclick="tolak('.$tolak.')" name="checkbox['.str_replace(" ","|_|",$key).']" id="cb'.str_replace(" ","",$key).'" value="'.$value.'">
							<span class="checkmark"></span>
						</label>
						<input type="text" class="form-control mb-2 d-none" id="'.str_replace(" ","",$key).'" name="tolak['.str_replace(" ","|_|",$key).']" placeholder="Alasan penolakan">';
					}
					$data = [
						"status" => "sukses",
						"checklist" => $checklist.$list_penyimpanan,
						"pesan_penitip" => $get_data->pesan_penitip,
						"diterima_komandan" => $get_data->diterima_komandan,
					];
				}else{
					$data = [
						"status" => "sudah diterima komandan",
					];
				}
			}else if($this->level == "Komandan" || ($this->level == "Admin" && $akses == "komandan")){
				if($get_data->status == "Diterima Komandan Jaga"){
					$array_barang = json_decode($get_data->list_barang_komandan);
					$checklist = "
					<h4 class='m-0'><b>Informasi</b></h4>
					<label class='container-check pl-0'>
						<p class='m-0'>Dari : ".$get_data->nama_pengirim." (".$get_data->hubungan.")</p>
						<p class='m-0'>Untuk : ".$get_data->nama_tahanan."</p>
					</label>
					<h4><b>Checklist Data Titipan</b></h4>
					";
					foreach ($array_barang as $key => $value) {
						$tolak = "'".str_replace(" ","",$key)."'";
						$checklist .= '
						<label class="container-check">'.$key." ".$value.'
							<input type="checkbox" class="check-box" checked="checked" onclick="tolak('.$tolak.')" name="checkbox['.str_replace(" ","|_|",$key).']" id="cb'.str_replace(" ","",$key).'" value="'.$value.'">
							<span class="checkmark"></span>
						</label>
						<input type="text" class="form-control mb-2 d-none" id="'.str_replace(" ","",$key).'" name="tolak['.str_replace(" ","|_|",$key).']" placeholder="Alasan penolakan">';
					}
					$data = [
						"status" => "sukses",
						"checklist" => $checklist.$list_penyimpanan,
						"pesan_penitip" => $get_data->pesan_penitip,
						"diterima_komandan" => $get_data->diterima_komandan,
					];
				}else{
					$data = [
						"status" => "selesai antar",
					];
				}
			}else{
				// Guard: level tidak cocok Tracer/Komandan ataupun Admin dengan akses yang sesuai
				$data = [
					"status" => "Gagal",
				];
			}
			echo json_encode($data);
		}else{
			$data = [
				"status" => "Gagal",
			];
			echo json_encode($data);
		}
	}

	public function send_email($params = null)
	{
		$data = $this->admin_model->get_data_select("data_titipan","nik","resi = '".$params."'","row");
		$email = $this->admin_model->get_data_select("penitip","email","nik = '".$data->nik."'","row");
		ini_set( 'display_errors', 1 );   
		error_reporting( E_ALL );    
		$from = "sipirman@rutanrembang.id";    
		$to = $email->email;
		$subject = $resi." No Resi SIPIRMAN";    
		$message = "Berikut adalah no resi barang titipan anda ".$params.", anda bisa melacak sendiri status barang titipan anda di https://sipirman.rutanrembang.id";
		
		$headers = "From:" . $from;
        
        // =========================================================================
        // FIX DIMATIKAN SEMENTARA AGAR TIDAK TERJADI CHROME-ERROR SAAT LOCALHOST
        // =========================================================================
		// if(mail($to,$subject,$message, $headers)){
        if(true){ // Simulasi pengiriman berhasil
		// =========================================================================
        
			$this->swal("Sukses","Berhasil kirim resi ke email ".$email->email.".","success");
		}else{
			$this->swal("Gagal","Gagal kirim resi ke email ".$email->email.".","error");
		}
		redirect("data");
	}

	public function repairdate()
	{
		$get_data = $this->admin_model->get_data_select("data_titipan","id,diterima_komandan,diterima_wbp","diterima_komandan > diterima_wbp","result");
		if(!empty($get_data)){
			foreach ($get_data as $get_data) {
				$diterima_komandan = $get_data->diterima_komandan;
				$diterima_wbp = $get_data->diterima_wbp;
				$id = $get_data->id;
				$data_update = [
					"diterima_komandan" => $diterima_wbp,
					"diterima_wbp" => $diterima_komandan,
				];
				$update = $this->admin_model->update_data("data_titipan","id = '$id'",$data_update);
				if(!$update){
					$status = "Sukses";
				}else{
					$status = "Gagal";
				}
			}
		}else{
		    $status = "kosong";
		}
	}

	//TAHANAN//
	public function tahanan()
	{
		$data["content"] = "tahanan";
		$data["javascript"] = "tahanan";
		$data["title"] = "Data Tahanan";
		$data["data_tahanan"] = $this->admin_model->get_data_select("tahanan","*","id !=","result");
		$this->load->view('layout/index',$data);
	}
	public function tahanan_input($p)
	{
		$data["content"] = "tahanan_input";
		$data["javascript"] = "tahanan_input";
		if($p == "input"){
			$data["title"] = "Tambah Tahanan";
		}else{
			$data["edit"] = $this->admin_model->get_data_select("tahanan","*","id = '$p'","row");
			$data["title"] = "Edit Tahanan";
		}
		$this->load->view('layout/index',$data);
	}
	public function save_tahanan($p)
	{
		$this->form_validation->set_rules("code_napi","Code NAPI","required|trim|xss_clean");
		$this->form_validation->set_rules("nama","Nama","required|trim|xss_clean");
		$this->form_validation->set_rules("nama_ayah","Nama Ayah","required|trim|xss_clean");
		$this->form_validation->set_rules("jenis_kelamin","Jenis Kelamin","required|trim|xss_clean");
		$this->form_validation->set_rules("pin","PIN Uang Digital","trim|xss_clean|exact_length[6]|numeric");

		if($this->form_validation->run() === TRUE){

            $code_napi = $this->input->post("code_napi");
            $nama_ayah = $this->input->post("nama_ayah");
            $nama = $this->input->post("nama");
            $jenis_kelamin = $this->input->post("jenis_kelamin");
            
            $pin_input = $this->input->post("pin");

            $data_input = [
                "code_napi" => $code_napi,
                "nama_ayah" => $nama_ayah,
                "nama" => $nama,
                "jenis_kelamin" => $jenis_kelamin,
            ];

            if(!empty($pin_input)){
                $data_input["pin"] = password_hash($pin_input, PASSWORD_DEFAULT);
            } else if($p == "input") {
                $data_input["pin"] = password_hash("123456", PASSWORD_DEFAULT);
            }

            if(!empty($p)){
                if($p == "input"){
                    $proses = $this->admin_model->insert_data("tahanan",$data_input);
                    $msg = "tambah";
                    $redirect = "tahanan_input/input";
                }else{
                    $proses = $this->admin_model->update_data("tahanan","id = '$p'",$data_input);
                    $msg = "update";
                    $redirect = "tahanan";
                }

				if(!$proses){
					$this->swal("Sukses","Data berhasil di ".$msg,"success");
				}else{
					$this->swal("Gagal","Data gagal di ".$msg,"error");
				}
			}else{
				$this->swal("Gagal","Parameter not valid","error");
				$redirect = "tahanan";
			}
		}else{
			$this->swal("Warning",str_replace("\n","<br>",validation_errors()),"warning");
			$redirect = "tahanan_input/".$p;
		}
		redirect($redirect);
	}

	// ============================================================
	// SAKU WBP LEVEL SETUP
	// Level baru "Saku" dengan fitur khusus pengelolaan saku WBP
	// ============================================================

	/**
	 * Middleware cek akses level Saku.
	 * Dipanggil di awal setiap method yang hanya boleh diakses level Saku.
	 */
// ==========================================
    // TAMBAHAN: FUNGSI DASHBOARD SAKU WBP
    // ==========================================

    private function _cek_akses_saku()
    {
        // Pastikan user sudah login dan levelnya adalah Saku
        if ($this->level !== 'Saku') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('login');
        }
    }

    public function saku_dashboard()
    {
        $this->_cek_akses_saku();

        // Data yang dibutuhkan view home_dashboard untuk blok Saku
        // Pastikan Anda memiliki admin_model yang sesuai
        $data['total_uang_masuk']  = $this->admin_model->get_data_select(
            'penyimpanan_uang',
            'COALESCE(SUM(jumlah_uang), 0) as total',
            "tanggal BETWEEN '{$this->tanggal_1} 00:00:01' AND '{$this->tanggal} 23:59:59' AND deleted_date IS NULL",
            'row'
        );
        $data['total_uang_keluar'] = $this->admin_model->get_data_select(
            'penggunaan_uang',
            'COALESCE(SUM(total_penggunaan), 0) as total',
            "tanggal BETWEEN '{$this->tanggal_1} 00:00:01' AND '{$this->tanggal} 23:59:59'",
            'row'
        );

        // Menggunakan view home_dashboard yang sudah ada
        // Pastikan di dalam view home_dashboard.php Anda ada kondisi 
        // if($this->session->userdata('level') == 'Saku') untuk membatasi tampilan
        $data['content']    = 'home_dashboard';
        $data['javascript'] = 'home_dashboard';
        $data['title']      = 'Dashboard Saku WBP';
        
        $this->load->view('layout/index', $data);
    }

	// ============================================================
	// END SAKU WBP LEVEL SETUP
	// ============================================================

	public function login_dashboard()
	{
		$this->form_validation->set_rules("username","Username","trim|xss_clean|required");
		$this->form_validation->set_rules("password","Password","trim|xss_clean|required");
		if($this->form_validation->run() === TRUE){
			$username = $this->input->post("username");
			$valid_account = $this->admin_model->get_data_select("account","id, password, name, level, username","username = '$username' AND level = 'Super Admin'","row");
			if(!empty($valid_account)){
				$p_bcrypt = $valid_account->password;
				if(password_verify($this->input->post("password"), $p_bcrypt)){
					$data = [
						"user_id" => $valid_account->id,
						"username" => $valid_account->username,
						"nama" => $valid_account->name,
						"level" => $valid_account->level,
						"tanggal" => date("Y-m-d"),
						"tanggal_1" => date("Y-m-d"),
						"tanggal_data" => date("Y-m-d"),
						"status" => "Semua",
					];
					$this->session->set_userdata($data);
					redirect("home_dashboard");
				}else{
					$this->swal("","Username atau Password anda salah","error");
					$this->session->set_flashdata("login-form-dashboard",'
					<script>
						$("#tracking-form").hide(200);
						$("#login-form-dashboard").show(400);
					</script>');
					redirect("login");
				}
			}else{
				$this->swal("","Username atau Password anda salah","error");
				$this->session->set_flashdata("login-form-dashboard",'
				<script>
					$("#tracking-form").hide(200);
					$("#login-form-dashboard").show(400);
				</script>');
				redirect("login");
			}
		}else{
			$this->swal("","Username atau Password anda salah","error");
			$this->session->set_flashdata("login-form-dashboard",'
			<script>
				$("#tracking-form").hide(200);
				$("#login-form-dashboard").show(400);
			</script>');
			redirect("login");
		}
	}
	
	public function home_dashboard()
	{
		// Guard: hanya Super Admin yang boleh akses halaman ini
		if(empty($this->level)){
			redirect("login");
		}
		if($this->level !== "Super Admin"){
			if($this->level == "Saku")      redirect("saku_dashboard");
			else if($this->level == "Cashier") redirect("cashier");
			else redirect("home");
		}

		$data["total_titipan"] = $this->admin_model->get_data_select("data_titipan","id","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","result");
		$data["total_uang_masuk"] = $this->admin_model->get_data_select("penyimpanan_uang","SUM(jumlah_uang) as total","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","row");
		$data["total_uang_keluar"] = $this->admin_model->get_data_select("penggunaan_uang","SUM(total_penggunaan) as total","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59'","row");
		$data["total_antiseptik"] = $this->admin_model->get_data_select("penyimpanan_antiseptik","COUNT(id) as count","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","row");
		$data["total_obat"] = $this->admin_model->get_data_select("penyimpanan_obat","COUNT(id) as count","tanggal BETWEEN '".$this->tanggal_1." 00:00:01' AND '".$this->tanggal." 23:59:59' AND deleted_date IS NULL","row");
		$data["content"] = "home_dashboard";
		$data["javascript"] = "home_dashboard";
		$data["title"] = "Dashboard";
		$this->load->view('layout/index',$data);
	}

	// TAGIHAN RUMBANG MART SETUP //
	public function tagihan_rumbang_mart()
	{
		$data["title"]      = "Tagihan Rumbang Mart";
		$data["content"]    = "tagihan_rumbang_mart";
		$data["javascript"] = "tagihan_rumbang_mart";

		// MODIFIKASI: sebelumnya cuma menangkap transaksi "Oleh WBP" (dari kasir
		// langsung) - pembelian lewat akun Keluarga Inti/Penitip untuk WBP yang
		// sama tidak pernah ikut tertagih di sini walau stok sudah berkurang.
		$wbp_transaksi = $this->db->query("
			SELECT DISTINCT p.kode_tahanan, t.nama
			FROM penggunaan_uang p
			JOIN tahanan t ON p.kode_tahanan = t.code_napi
			WHERE p.penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh WBP)%'
			   OR p.penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh Keluarga%'
			   OR p.penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh Penitip%'
		")->result();

		$data_tagihan = [];
		$total_semua_tertagih = 0;
		$total_semua_terbayar = 0;

		foreach ($wbp_transaksi as $wbp) {
			$items_db = $this->db->query("
				SELECT id, tanggal, penggunaan, total_penggunaan, status
				FROM penggunaan_uang
				WHERE kode_tahanan = ?
				AND (
					penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh WBP)%'
					OR penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh Keluarga%'
					OR penggunaan LIKE '%Belanja Warung SIPIRMAN (Oleh Penitip%'
				)
				ORDER BY tanggal DESC
			", [$wbp->kode_tahanan])->result();

			$total_tertagih_wbp = 0;
			$grouped_items = []; // Array untuk agregasi penggabungan item sama

			foreach ($items_db as $row) {
				$status_item = (empty($row->status) || $row->status != 'Received') ? 'Tertagih' : 'Terbayar';
				
				// Decode format JSON dari database
				$json_str = $row->penggunaan;
				$decoded = json_decode($json_str, true);

				// MODIFIKASI: cari key grup "Belanja Warung SIPIRMAN (Oleh ...)" secara
				// generik - bisa "Oleh WBP" (dari kasir), atau "Oleh Keluarga <nama>" /
				// "Oleh Penitip <nama>" (dari Warung SIPIRMAN online), bukan cuma yang
				// literal "Oleh WBP" saja.
				$belanja_items = [];
				if (is_array($decoded)) {
					$grup_belanja = null;
					foreach ($decoded as $grup_key => $grup_val) {
						if (is_array($grup_val) && strpos($grup_key, 'Belanja Warung SIPIRMAN (Oleh') === 0) {
							$grup_belanja = $grup_key;
							break;
						}
					}
					if ($grup_belanja !== null) {
						$belanja_items = $decoded[$grup_belanja];
					} else {
						$belanja_items = $decoded;
					}
				} else {
					// Fallback jika ada data lama berformat string biasa
					$nama_str = trim(str_replace("Belanja Warung SIPIRMAN (Oleh WBP) -", "", $json_str));
					$belanja_items[$nama_str] = $row->total_penggunaan;
				}

				foreach ($belanja_items as $item_key => $total_harga_item) {
					// Pecah nama barang dan qty dari string e.g., "Sukun Putih 12 (1)"
					$qty = 1;
					$nama_barang = $item_key;
					if(preg_match('/^(.+?)\s*\((\d+)\)\s*$/', trim($item_key), $m)){
						$nama_barang = trim($m[1]);
						$qty = (int)$m[2];
					}
					
					// Rumus : Harga Total dibagi QTY = Harga Satuan
					$harga_satuan = ($qty > 0) ? (int)round($total_harga_item / $qty) : $total_harga_item;
					
					// Kunci penggabungan berdasarkan Status dan Nama Barang
					$group_key = $status_item . '|' . strtolower($nama_barang) . '|' . $harga_satuan;
					
					if (!isset($grouped_items[$group_key])) {
						$grouped_items[$group_key] = [
							'nama_barang' => $nama_barang,
							'qty' => 0,
							'harga_satuan' => $harga_satuan,
							'total_harga' => 0,
							'status' => $status_item,
							'tanggal_terakhir' => $row->tanggal
						];
					}
					
					// Jumlahkan Qty dan Total Harga jika barang sama
					$grouped_items[$group_key]['qty'] += $qty;
					$grouped_items[$group_key]['total_harga'] += $total_harga_item;
					
					if ($status_item == 'Tertagih') {
						$total_tertagih_wbp += $total_harga_item;
						$total_semua_tertagih += $total_harga_item;
					} else {
						$total_semua_terbayar += $total_harga_item;
					}
				}
			}

			// Ambil Real-time Saldo
			$uang_masuk = $this->db->query("SELECT COALESCE(SUM(jumlah_uang),0) as total FROM penyimpanan_uang WHERE kode_tahanan = ?", [$wbp->kode_tahanan])->row()->total;
			$uang_keluar = $this->db->query("SELECT COALESCE(SUM(total_penggunaan),0) as total FROM penggunaan_uang WHERE kode_tahanan = ?", [$wbp->kode_tahanan])->row()->total;
			$sisa_uang_digital = $uang_masuk - $uang_keluar;

			$saldo_fisik_tampil = $sisa_uang_digital + $total_tertagih_wbp;

			// Konversi group array dan urutkan Tertagih di atas
			$final_items = array_values($grouped_items);
			usort($final_items, function($a, $b) {
				if ($a['status'] == $b['status']) {
					return strtotime($b['tanggal_terakhir']) - strtotime($a['tanggal_terakhir']);
				}
				return ($a['status'] == 'Tertagih') ? -1 : 1;
			});

			if (count($final_items) > 0) {
				$data_tagihan[] = [
					'kode_tahanan' => $wbp->kode_tahanan,
					'nama_wbp'     => $wbp->nama,
					'saldo_fisik'  => $saldo_fisik_tampil,
					'items'        => $final_items,
					'has_tertagih' => ($total_tertagih_wbp > 0)
				];
			}
		}

		$data['data_tagihan'] = $data_tagihan;
		$data['total_tertagih'] = $total_semua_tertagih;
		$data['total_terbayar'] = $total_semua_terbayar;

		$this->load->view('layout/index', $data);
	}

	public function bayar_tagihan_rumbang()
	{
		$kode_tahanan = $this->input->post('kode_tahanan');
		if($kode_tahanan){
			$this->db->where('kode_tahanan', $kode_tahanan);
			$this->db->like('penggunaan', 'Belanja Warung SIPIRMAN (Oleh WBP)');
			$this->db->group_start();
				$this->db->where('status', NULL);
				$this->db->or_where('status !=', 'Received');
			$this->db->group_end();
			
			$update = $this->db->update('penggunaan_uang', ['status' => 'Received']);
			
			if($update){
				echo json_encode(['status' => 200, 'msg' => 'Tagihan terbayar. Saldo fisik WBP telah disesuaikan dengan saldo digital.']);
			} else {
				echo json_encode(['status' => 500, 'msg' => 'Terjadi kesalahan pemrosesan database.']);
			}
		}
	}
	// END TAGIHAN RUMBANG MART SETUP //
}
?>