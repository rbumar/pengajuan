<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_role extends CI_Model{

	private $_table = 'role';
	public $_id = 'id_role';
	public $_placeholderId = 'pattern_id_karyawan';
	public $_sequence = 'karyawan_seq';
	public $_column = [
		'id_role' => ['label' => 'ID', 'show' => false, 'isCrud' => false],
		'nama_role' => ['label' => 'Nama', 'show' => true, 'isCrud' => true],
		'role_level' => ['label' => 'Level', 'show' => true, 'isCrud' => true],
		'deskripsi' => ['label' => 'Deskripsi', 'show' => true, 'isCrud' => true]
	];

	public function __construct(){
		parent::__construct();
	}

	// get all data 
	public function getAll(){
		$query = $this->db->get($this->_table);
		return $query->result();
	}

	public function get($id){
		$this->db->where($this->_id, $id);
		$query = $this->db->get($this->_table);
		return $query->row();
	}
	// add function 
	public function addOrEdit($data){
		
		if($data[$this->_id] !== ''){
			
			// check if email exists 
			$this->db->where($this->_id,$data[$this->_id]);
			return $this->db->update($this->_table, $data);

		}else{

			unset($data[$this->_id]);
			return  $this->db->insert($this->_table, $data);

		}
		
	}

	public function delete($id){
		$this->db->where($this->_id, $id);
		return $this->db->delete($this->_table);
	}

}

?>