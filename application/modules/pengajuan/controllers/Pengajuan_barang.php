<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan_barang extends MY_Controller{
	
	private $_url;
	private $_title = 'Pengajuan Barang';
	private $_mainModel = 'm_pengajuan_barang';
	private $_mainView  = 'v_pengajuan_barang';

	public function __construct(){
		parent::__construct();
		// setting url untuk form 
		$base_url = base_url('pengajuan/pengajuan_barang');
		$this->_url = ['url' => $base_url, 'form' => $base_url.'/form', 'crud' => $base_url.'/crud'];
		$this->load->model($this->_mainModel, 'mainModel');
	}

	// main function master barang 
	public function index(){
		
		$activeStatus = $this->input->get('status') ? $this->input->get('status') : 'ALL';
		
		$data = [
			'title' => $this->_title,
			'data' => $this->mainModel->getFiltered($activeStatus),
			'statusCount' => $this->mainModel->getStatusCount(),
			'statusColorClass' => $this->mainModel->_colorClass,
			'activeStatus' => $activeStatus,
			'column' => $this->mainModel->mainModel->_column,
			'url' => $this->_url,
			'idCol' => $this->mainModel->_id
		];

		$this->load->view($this->_mainView, $data);
		$this->load->view('templates/script', $this->_url);
	}

	// show form function 
	public function form(){
		// get stock and product data 
		$this->load->model('master_data/m_master_stock');
		$stockData = $this->m_master_stock->getAll();

		// get placeholder / pattern for id 
		$this->load->model('parameter/m_parameter');
		$placeholderId = str_replace('0', 'X', $this->m_parameter->getParamValue($this->mainModel->_placeholderId)->value);
		$data = [
			'title' => 'Form '.$this->_title,
			'placeholderId' => $placeholderId,
			'stockData' => $stockData,
			'url' => $this->_url
		];

		// check is edit mode 
		if($this->input->get('isEdit')){
			$data['data'] = $this->mainModel->get(@$this->input->get('id'));
		}

		$this->load->view($this->_mainView.'_form', $data);
		$this->load->view('templates/script', $this->_url);
	}

	// crud function 
	public function crud(){

		try {
			if($this->input->get('isDelete')){
			
				$result = $this->mainModel->delete(@$this->input->get('id'));

			}else if ($this->input->get('isSubmit')){
				$result = $this->mainModel->submit(@$this->input->get('id'));				
			}else{

				$data = [];
				foreach($this->mainModel->_column as $key => $val){
					$data[$key] = $this->input->post($key);
				}
				$data['id_barang'] = $this->input->post('id_barang');
				
				$result = $this->mainModel->addOrEdit($data);
			}


			if($result){
				echo 'OK';
			}else{
				echo $result;
			}

		} catch (Exception $e) {
			echo $e->getMessage();
		}

		
	}

}

?>