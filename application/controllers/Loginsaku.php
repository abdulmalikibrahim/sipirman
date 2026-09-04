<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Loginsaku extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		// Meload helper dan library yang dibutuhkan jika belum terload otomatis
		$this->load->library('session');
		$this->load->library('form_validation');
	}

	public function index()
	{
		// Pastikan nama file view login rahasia Anda sesuai, contoh: login_saku.php
		$this->load->view('login_saku');
	}

	public function proses()
	{
		$username = $this->input->post('username', TRUE);
		$password = $this->input->post('password', TRUE);
		$level    = $this->input->post('level', TRUE); // Nilainya 'saku' dari hidden input

		// 1. Validasi Input form
		if (empty($username) || empty($password)) {
			$this->session->set_flashdata('error', 'Username dan Password wajib diisi!');
			redirect('loginsaku');
		}

		// 2. Cari user di tabel account yang memiliki username COCOK dan level WAJIB 'Saku'
		// Ini memastikan akun admin/level lain TIDAK BISA masuk lewat pintu rahasia ini
		$this->db->where('username', $username);
		$this->db->where('level', 'Saku');
		$query = $this->db->get('account');

		if ($query->num_rows() == 1) {
			$user = $query->row();

			// 3. Verifikasi Password menggunakan password_verify (Sesuai dengan sistem My_control)
			if (password_verify($password, $user->password)) {
				
				// 4. Siapkan Data Session yang dibutuhkan aplikasi
				$session_data = [
					'user_id'  => $user->id,
					'username' => $user->username,
					'nama'     => $user->name,
					'level'    => $user->level, // Bernilai 'Saku'
					'status'   => 'login',
					
					// Menyamakan setelan tanggal default seperti pada My_control
					'tanggal'  => date('Y-m-d'),
					'tanggal_1'=> date('Y-m-d'),
					'tanggal_data' => date('Y-m-d')
				];

				$this->session->set_userdata($session_data);

				// 5. Alihkan ke halaman utama/dashboard khusus Saku WBP
				// Sesuaikan dengan route dashboard saku yang akan Anda gunakan nanti
				redirect('home'); 

			} else {
				// Password salah
				$this->session->set_flashdata('error', 'Kata sandi yang Anda masukkan salah!');
				redirect('loginsaku');
			}
		} else {
			// Username tidak ditemukan atau username tersebut bukan level 'Saku'
			$this->session->set_flashdata('error', 'Akses ditolak! Akun tidak ditemukan atau bukan kewenangan Level Saku.');
			redirect('loginsaku');
		}
	}
}