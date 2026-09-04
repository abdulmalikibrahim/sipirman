<?php

class Admin_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		// $this->napi = $this->load->database('napi', TRUE);
	}
	public function get_data_db_select($db,$table,$select,$where,$status)
	{
		$this->$db->select($select);
		$this->$db->where($where);
		$this->$db->from($table);
		if($status == 'result'){
			return $this->$db->get()->result();
		}else{
			return $this->$db->get()->row();
		}
	}
	public function get_data_select($table,$select,$where,$status)
	{
		$this->db->select($select);
		$this->db->where($where);
		$this->db->from($table);
		if($status == 'result'){
			return $this->db->get()->result();
		}else{
			return $this->db->get()->row();
		}
	}
	public function join_data($table, $table_join, $on_join, $where, $status)
	{
		$this->db->where($where);
		$this->db->from($table);
		$this->db->join($table_join, $on_join);
		if ($status == 'result') {
			return $this->db->get()->result();
		} else {
			return $this->db->get()->row();
		}
	}
	public function delete_data($table, $where)
	{
		$this->db->where($where);
		$this->db->delete($table);
	}
	function update_data($table, $where, $data)
	{
		$this->db->where($where);
		$this->db->update($table, $data);
	}
	public function insert_data($table, $data)
	{
		$this->db->insert($table, $data);
	}
	public function get_data_count($table,$select,$where,$return)
	{
		$this->db->select($select);
		$this->db->from($table);
		$this->db->where($where);
		if($return == "result"){
			return $this->db->get()->result();
		}else{
			return $this->db->get()->row();
		}
	}
	public function insertimport($table,$data)
    	{
        $this->db->insert_batch($table, $data);
        return $this->db->insert_id();
	}
	public function truncate($table)
	{
		$this->db->truncate($table);
	}
	public function query($sql)
	{
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	// ==========================================================
	// HELPER SALDO WBP (Digital & Tunai) - Perhitungan Net (Masuk - Keluar)
	// Menggantikan pola FIFO per-baris supaya bisa minus (hutang) dan
	// otomatis lunas begitu ada uang masuk baru.
	// ==========================================================
	public function get_saldo_digital($kode_tahanan)
	{
		$masuk = $this->db->query("SELECT COALESCE(SUM(jumlah_uang),0) as total FROM penyimpanan_uang WHERE kode_tahanan = ?", array($kode_tahanan))->row();
		$keluar = $this->db->query("SELECT COALESCE(SUM(total_penggunaan),0) as total FROM penggunaan_uang WHERE kode_tahanan = ? AND status != 'Discard'", array($kode_tahanan))->row();
		return $masuk->total - $keluar->total;
	}

	public function get_saldo_tunai($kode_tahanan)
	{
		$diserahkan = $this->db->query("SELECT COALESCE(SUM(total_penggunaan),0) as total FROM penggunaan_uang WHERE kode_tahanan = ? AND penggunaan LIKE '%Diserahkan Tunai Ke WBP%' AND status = 'Received'", array($kode_tahanan))->row();
		$terpakai = $this->db->query("SELECT COALESCE(SUM(penggunaan),0) as total FROM belanja_uang_tunai WHERE kode_tahanan = ?", array($kode_tahanan))->row();
		return $diserahkan->total - $terpakai->total;
	}

	// ==========================================================
	// HELPER STOK BARANG KOPERASI
	// ==========================================================
	public function get_stok($nama_barang)
	{
		return $this->db->select("id,kode_barang,nama_barang,harga,stok")
			->where("nama_barang", $nama_barang)
			->get("data_barang_koperasi")->row();
	}

	public function ubah_stok($nama_barang, $delta)
	{
		$delta = (int) $delta;
		$this->db->set("stok", "stok+(".$delta.")", FALSE);
		$this->db->where("nama_barang", $nama_barang);
		$this->db->update("data_barang_koperasi");
	}

	// Validasi stok untuk sekumpulan barang yang akan dibeli, dipakai bersama
	// oleh Cashier (use_money_digital, use_money_manual) dan Keluarga_inti (simpan_belanja).
	// Return: TRUE kalau semua stok cukup, atau string pesan error.
	public function validasi_stok($nama_barang, $qty)
	{
		foreach ($nama_barang as $key => $nb) {
			$jumlah = !empty($qty[$key]) ? (int) $qty[$key] : 1;
			$barang = $this->get_stok($nb);
			if(empty($barang)){
				return "Barang \"".$nb."\" tidak ditemukan di data barang koperasi";
			}
			if((int)$barang->stok < $jumlah){
				return "Stok \"".$nb."\" tidak mencukupi, sisa stok ".(int)$barang->stok;
			}
		}
		return TRUE;
	}

	// Kurangi stok untuk sekumpulan barang yang berhasil dibeli
	public function kurangi_stok($nama_barang, $qty)
	{
		foreach ($nama_barang as $key => $nb) {
			$jumlah = !empty($qty[$key]) ? (int) $qty[$key] : 1;
			$this->ubah_stok($nb, -$jumlah);
		}
	}
}