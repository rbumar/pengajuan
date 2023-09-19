<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_stock extends MY_Controller{
	
	private $_url;
	private $_column = [
		'id_barang' => 'Kode Barang',
		'nama_barang' => 'Nama Barang',
		'stok' => 'Stock',
		'deskripsi' => 'Deskripsi'
	];

	public function __construct(){
		parent::__construct();
		// setting url untuk form 
		$base_url = base_url('master_data/master_stock');
		$this->_url = ['url' => $base_url, 'form' => $base_url.'/form', 'crud' => $base_url.'/crud'];
		$this->load->model('m_master_stock');
	}

	// main function master barang 
	public function index(){
		$data = [
			'title' => 'Master Data Stock',
			'data' => $this->m_master_stock->getAll(),
			'column' => $this->_column,
			'url' => $this->_url,
			'idCol' => $this->m_master_stock->_id2
		];

		$this->load->view('v_master_stock', $data);
		$this->load->view('templates/script', $this->_url);
	}

	// show form function 
	public function form(){

		$data = [
			'title' => 'Form Master Data Stock',
			'url' => $this->_url
		];

		$isEdit = $this->input->get('isEdit');
		if($isEdit){
			$idBarang = $this->input->get('id');
			$data['data'] = $this->m_master_stock->get($idBarang);
		}

		$this->load->view('v_master_stock_form', $data);
		$this->load->view('templates/script', $this->_url);
	}

	// crud function 
	public function crud(){
		$data = [
			'id_stok' => @$this->input->post('id_stok'),
			'id_barang' => @$this->input->post('id_barang'),
			'stok' => @$this->input->post('stok')
		];
		
		$result = $this->m_master_stock->addOrEdit($data);
		

		if($result){
			echo 'OK';
		}else{
			echo $result;
		}
	}
}

?>