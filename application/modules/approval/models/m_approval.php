<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_approval extends CI_Model{

	private $_table = 'approval';
	public $_id = 'id_approval';
	public $_sequence = 'approval_seq';
	public $_placeholderId = 'pattern_id_approval';
	public $_column = [
		'id_approval' => ['label' => 'Kode Pengajuan', 'show' => true],
		'nama_pengajuan' => 'Nama Pengajuan',
		'nama_barang' => 'Nama Barang',
		'kuantitas' => 'Qty',
		'status' => 'Status',
		'deskripsi' => 'Deskripsi'
	];
	private $_view = 'v_approval';

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
		
		$stock = $this->getAvailableStock($data['id_barang']);
		if($stock < $data['kuantitas']){
			throw new Exception('Stock barang tidak mencukupi.');
		}

		unset($data['nama_barang']);
		unset($data['status']);

		if($data[$this->_id] !== ''){
			$this->db->where($this->_id, $data[$this->_id]);
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

	public function add($id, $status){
		// insert into approval
		$sql = "SELECT f_gen_id(?, ?) as id";
		$query = $this->db->query($sql, array($this->_placeholderId, $this->_sequence));
		$row = $query->row();

		$data = [
			'id_approval' => $row->id,
			'id_pengajuan' => $id,
			'status' => $status
		];

		return $this->db->insert($this->_table, $data);
	}

	public function delete($id){
		$this->db->where($this->_id, $id);
		return $this->db->delete($this->_table);
	}

	private function getAvailableStock($id_barang){
		$this->load->model('master_data/m_master_stock');
		$stock = $this->m_master_stock->get($id_barang);
		return $stock->stok;
	}

}

?>