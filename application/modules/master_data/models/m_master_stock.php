<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_master_stock extends CI_Model{

	private $_table = 'stok';
	public $_id1 = 'id_stok';
	public $_id2 = 'id_barang';
	private $_view = 'v_stock';

	public function __construct(){
		parent::__construct();
	}

	// get all data 
	public function getAll(){
		$query = $this->db->get($this->_view);
		return $query->result();
	}

	public function get($id){
		$this->db->where($this->_id2, $id);
		$query = $this->db->get($this->_view);
		return $query->row();
	}

	// add function 
	public function addOrEdit($data){
		if($data[$this->_id1] !== ''){
			
			$this->db->where($this->_id1, $data[$this->_id1]);
			return $this->db->update($this->_table, $data);

		}else{
			unset($data['id_stok']);
			return  $this->db->insert($this->_table, $data);

		}
		
	}

}

?>