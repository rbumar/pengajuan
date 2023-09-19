<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_barang extends MY_Controller{
	
	private $_url;
	private $_column = [
		'id_barang' => 'Kode Barang',
		'nama_barang' => 'Nama Barang',
		'deskripsi' => 'Deskripsi'
	];

	public function __construct(){
		parent::__construct();
		// setting url untuk form 
		$base_url = base_url('master_data/master_barang');
		$this->_url = ['url' => $base_url, 'addUrl' => $base_url.'/form', 'crud' => $base_url.'/crud'];
		$this->load->model('m_master_barang');
	}

	// main function master barang 
	public function index(){

		$data = [
			'title' => 'Master Data Barang',
			'data' => $this->m_master_barang->getAll(),
			'column' => $this->_column,
			'url' => $this->_url
		];

		$this->load->view('v_master_barang', $data);
		$this->load->view('templates/script', $this->_url);
	}

	// show form function 
	public function form(){

		$this->load->model('parameter/m_parameter');
		$placeholderId = str_replace('0', 'X', $this->m_parameter->getParamValue($this->m_master_barang->_placeholderId)->value);
		$data = [
			'title' => 'Form Master Data Barang',
			'placeholderId' => $placeholderId,
			'url' => $this->_url
		];

		$isEdit = $this->input->get('isEdit');
		if($isEdit){
			$id = $this->input->get('id');
			$data['data'] = $this->m_master_barang->get($id);
		}

		$this->load->view('v_master_barang_form', $data);
		$this->load->view('templates/script', $this->_url);
	}

	// crud function 
	public function crud(){
		$isDelete = $this->input->get('isDelete');
		if($isDelete){
			$id = $this->input->get('id');
			$result = $this->m_master_barang->delete($id);
		}else{

			$data = [];
			foreach($this->_column as $key => $val){
				$data[$key] = $this->input->post($key);
			}
			
			$result = $this->m_master_barang->addOrEdit($data);
		}


		if($result){
			echo 'OK';
		}else{
			echo $result;
		}
	}
}

?>