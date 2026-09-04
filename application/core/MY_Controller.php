<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class MY_Controller extends CI_Controller {
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
		$this->p0 = $this->uri->segment(0);
		$this->p1 = $this->uri->segment(1);
		$this->p2 = $this->uri->segment(2);
		$this->p3 = $this->uri->segment(3);
		$this->keluarga_inti = $this->session->userdata("keluarga_inti");
		$this->kode_tahanan = $this->session->userdata("kode_tahanan");
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
}