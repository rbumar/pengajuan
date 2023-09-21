<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_karyawan extends CI_Model{

	private $_table = 'karyawan';
	private $_view = 'v_karyawan';
	public $_id = 'id_karyawan';
	public $_placeholderId = 'pattern_id_karyawan';
	public $_sequence = 'karyawan_seq';
	public $_column = [
		'id_karyawan' => ['label' => 'ID', 'show' => true, 'isCrud' => true],
		'nama_karyawan' => ['label' => 'Email', 'show' => true, 'isCrud' => true],
		'alamat' => ['label' => 'Alamat', 'show' => true, 'isCrud' => true],
		'email' => ['label' => 'Email', 'show' => true, 'isCrud' => true],
		'id_role' => ['label' => 'ID Jabatan', 'show' => false, 'isCrud' => true],
		'nama_role' => ['label' => 'Jabatan', 'show' => true, 'isCrud' => false]
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

	public function getUserKaryawan(){
		$sql = "SELECT * 
					FROM v_karyawan k
					WHERE NOT EXISTS (SELECT 1 
										FROM users u 
										WHERE u.email = k.email)
					ORDER BY 1 ASC";
		$query = $this->db->query($sql);
		return $query->result();
	}

	// add function 
	public function addOrEdit($data){
		
		if($data[$this->_id] !== ''){
			
			// check if email exists 
			if($this->checkEmail($data[$this->_id], $data['email']) > 0){
				throw new Exception('Cannot update email, email '.$data['email'].' already exists.');
			}
			$this->db->where($this->_id,$data[$this->_id]);
			return $this->db->update($this->_table, $data);

		}else{

			if($this->checkEmail($data[$this->_id], $data['email']) > 0){
				throw new Exception('Cannot insert new data, email '.$data['email'].' already exists.');
			}

			$sql = "SELECT f_gen_id(?, ?) as id";
			$query = $this->db->query($sql, array($this->_placeholderId, $this->_sequence));
			$row = $query->row();

			if($row->id){
				$data[$this->_id] = $row->id;
				return  $this->db->insert($this->_table, $data);

			}else{
				throw new Exception('Terjadi kesalahan pada saat membuat ID '.$row);
			}

		}
		
	}

	public function checkEmail($id, $email){
		$this->db->where('id_karyawan != ', $id);
		$this->db->where('email', $email);
		return $this->db->get($this->_table)->num_rows();
	}

	public function delete($id){
		$this->db->where($this->_id, $id);
		return $this->db->delete($this->_table);
	}

}

?>