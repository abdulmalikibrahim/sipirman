<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logincashier extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
        $this->load->helper('url');
    }

    public function index() {
        // Jika sudah login sebagai Cashier, langsung ke halaman cashier
        if ($this->session->userdata('level') == 'Cashier') {
            redirect('cashier'); 
        }
        
        $this->load->view('login_rumbang_mart');
    }

    public function proses() {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        // Berdasarkan My_control.php, nama tabelnya adalah 'account'
        $this->db->where('username', $username);
        $user = $this->db->get('account')->row(); 

        if ($user) {
            // Verifikasi password Hash (BCRYPT)
            if (password_verify($password, $user->password)) {
                
                // Pastikan levelnya adalah Cashier
                if ($user->level == 'Cashier') {
                    
                    // SESSION DISAMAKAN 100% DENGAN STANDAR SIPIRMAN (My_control.php)
                    $session_data = array(
                        "user_id"      => $user->id,
                        "username"     => $user->username,
                        "nama"         => $user->name,
                        "level"        => $user->level,
                        "tanggal"      => date("Y-m-d"),
                        "tanggal_1"    => date("Y-m-d"),
                        "tanggal_data" => date("Y-m-d"),
                        "status"       => "Semua"
                    );
                    $this->session->set_userdata($session_data);
                    
                    // BERHASIL: Dialihkan ke halaman cashier
                    redirect('cashier'); 

                } else {
                    $this->session->set_flashdata('error', 'Akses ditolak! Anda bukan petugas RUMBANG MART.');
                    redirect('logincashier');
                }

            } else {
                $this->session->set_flashdata('error', 'Password salah!');
                redirect('logincashier');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan!');
            redirect('logincashier');
        }
    }
}