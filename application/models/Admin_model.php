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
}