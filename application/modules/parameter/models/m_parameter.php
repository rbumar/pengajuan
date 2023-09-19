<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_parameter extends CI_Model{

	private $_table = 'global_param';

	public function __construct(){
		parent::__construct();
	}

	public function getAll(){
		$query = $this->db->get($this->_table);
		return $query->result();
	}

	// function untuk mengambil nilai parameter 
	public function getParamValue($code){
		$this->db->where('name', $code);
		$query = $this->db->get($this->_table);
		return $query->row();
	}

}

 ?>