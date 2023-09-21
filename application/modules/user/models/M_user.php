<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model{

	private $_table = 'users';
	private $_view = 'v_users';
	public $_placeholderId = false;
	public $_sequence = false;
	public $_id = 'id';
	public $_column = [
		'id' => ['label' => 'ID', 'hide' => true, 'show' => false, 'isCrud' => true, 'elem' => 'text', 'readonly' => 'readonly' ],
		'id_karyawan' => ['label' => 'Nik', 'hide' => false, 'show' => true, 'isCrud' => false, 'elem' => 'select', 'data' => 'dataKaryawan', 'readonly' => 'readonly', 'key' => 'id_karyawan', 'value' => 'id_karyawan-nama_karyawan'],
		'nama_karyawan' => ['label' => 'Nama', 'hide' => false,'show' => true, 'isCrud' => false, 'elem' => 'text', 'readonly' => 'readonly' ],
		'email' => ['label' => 'Email', 'hide' => false, 'show' => true, 'isCrud' => true, 'elem' => 'text', 'readonly' => 'readonly' ],
		'password' => ['label' => 'Password', 'hide' => true, 'show' => true, 'isCrud' => true, 'elem' => 'text', 'type' => "password" , 'readonly' => ''],
		'nama_role' => ['label' => 'Jabatan', 'hide' => false, 'show' => true, 'isCrud' => false, 'elem' => 'text', 'readonly' => 'readonly' ]
	];

	public function __construct(){
		parent::__construct();
	}

	// get all data 
	public function getAll(){
		$query = $this->db->get($this->_view);
		return $query->result();
	}

	public function get($id){
		$this->db->where($this->_id, $id);
		$query = $this->db->get($this->_view);
		return $query->row();
	}

	// add function 
	public function addOrEdit($data){
		
		if($data[$this->_id] !== ''){
			
			$this->db->where($this->_id,$data[$this->_id]);
			return $this->db->update($this->_table, $data);

		}else{
			unset($data[$this->_id]);
			// remove id from $data if exists, since the id is auto increment 
			return  $this->db->insert($this->_table, $data);

		}
		
	}

	public function delete($id){
		$this->db->where($this->_id, $id);
		return $this->db->delete($this->_table);
	}

}

?>