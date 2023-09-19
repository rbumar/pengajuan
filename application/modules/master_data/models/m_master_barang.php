<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_master_barang extends CI_Model{

	private $_table = 'barang';
	private $_id = 'id_barang';
	public $_placeholderId = 'PLACEHOLDER_ID_BARANG';
	public $_sequence = 'barang_seq';

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
			
			$this->db->where($this->_id,$data[$this->_id]);
			return $this->db->update($this->_table, $data);

		}else{

			$sql = "SELECT f_gen_id(?, ?) as id";
			$query = $this->db->query($sql, array($this->_placeholderId, $this->_sequence));
			$row = $query->row();

			if($row->id){
				$data[$this->_id] = $row->id;
				return  $this->db->insert($this->_table, $data);

			}else{
				return $row->id;
			}

		}
		
	}

	public function delete($id){
		$this->db->where($this->_id, $id);
		return $this->db->delete($this->_table);
	}

}

?>