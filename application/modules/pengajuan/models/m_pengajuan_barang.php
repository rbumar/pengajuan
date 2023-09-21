<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengajuan_barang extends CI_Model{

	private $_table = 'pengajuan_barang';
	public $_id = 'id_pengajuan';
	public $_sequence = 'pengajuan_barang_seq';
	public $_placeholderId = 'pattern_id_pengajuan_barang';
	public $_column = [
		'id_pengajuan' => 'Kode Pengajuan',
		'nama_pengajuan' => 'Nama Pengajuan',
		'nama_barang' => 'Nama Barang',
		'kuantitas' => 'Qty',
		'status' => 'Status',
		'deskripsi' => 'Deskripsi'
	];
	private $_view = 'v_pengajuan_barang';
	private $_status = ['ALL', 'OPEN', 'SUBMITTED', 'APPROVED', 'REJECTED'];
	public $_colorClass = ['ALL' => 'info', 'OPEN' => 'warning', 'SUBMITTED' =>'primary', 'APPROVED' => 'success', 'REJECTED' =>'danger'];

	public function __construct(){
		parent::__construct();
	}

	// get all data 
	public function getAll(){
		$query = $this->db->get($this->_view);
		return $query->result();
	}

	// get filtered data
	public function getFiltered($status){
		if($status != 'ALL'){
			$this->db->where('status', $status);
		}
		$query = $this->db->get($this->_view);
		return $query->result();
	}

	public function get($id){
		$this->db->where($this->_id, $id);
		$query = $this->db->get($this->_view);
		return $query->row();
	}

	public function getStatusCount(){
		$data = [];
		foreach ($this->_status as $value) {
			if($value != 'ALL'){
				$this->db->where('status', $value);
			}
			$count = $this->db->get($this->_view)->num_rows();
			$data[$value] = $count;
		}
		return $data;
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

	public function submit($id){
		// insert into approval 
		$this->db->where($this->_id, $id);
		$pengajuan = $this->db->get($this->_view)->row();
		
		if($pengajuan->status !== 'OPEN'){
			throw new Exception('Tidak dapat melanjutkan proses untuk data dengan status '.$pengajuan->status);
		}

		$stock = $this->getAvailableStock($pengajuan->id_barang);
		if($stock < $pengajuan->kuantitas){
			throw new Exception('Stock barang tidak mencukupi.');
		}

		$this->load->model('approval/m_approval');

		return $this->m_approval->add($id, 'SUBMITTED');
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