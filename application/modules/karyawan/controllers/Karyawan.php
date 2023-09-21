<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends MY_Controller{
	
	private $_url;
	private $_title = 'Karyawan';
	private $_main = 'karyawan';
	private $_mainModel;
	private $_mainView;

	public function __construct(){
		parent::__construct();
		// session check 
		modules::load('auth/auth')->is_logged_in();

		// setting url untuk form 
		$this->_mainModel = 'm_'.$this->_main;
		$this->_mainView  = 'v_'.$this->_main;
		$base_url = base_url($this->_main);
		$this->_url = ['url' => $base_url, 'form' => $base_url.'/form', 'crud' => $base_url.'/crud'];
		$this->load->model($this->_mainModel, 'mainModel');
	}

	// main function master barang 
	public function index(){
		$data = [
			'title' => $this->_title,
			'data' => $this->mainModel->getAll(),
			'column' => $this->mainModel->_column,
			'idCol' => $this->mainModel->_id,
			'url' => $this->_url
		];

		$this->load->view($this->_mainView, $data);
		$this->load->view('templates/script', $this->_url);
	}

	// show form function 
	public function form(){

		$this->load->model('parameter/m_parameter');
		$placeholderId = $this->mainModel->_placeholderId ? str_replace('0', 'X', $this->m_parameter->getParamValue($this->mainModel->_placeholderId)->value) : '';

		$this->load->model('role/m_role');

		$data = [
			'title' => 'Form '.$this->_title,
			'placeholderId' => $placeholderId,
			'roleData' => $this->m_role->getAll(),
			'url' => $this->_url
		];

		$isEdit = $this->input->get('isEdit');
		if($isEdit){
			$id = $this->input->get('id');
			$data['data'] = $this->mainModel->get($id);
		}

		$this->load->view($this->_mainView.'_form', $data);
		$this->load->view('templates/script', $this->_url);
	}

	// crud function 
	public function crud(){

		try {
			
			$isDelete = $this->input->get('isDelete');
			if($isDelete){
				$id = $this->input->get('id');
				$result = $this->mainModel->delete($id);
			}else{

				$data = [];
				foreach($this->mainModel->_column as $key => $val){
					if($val['isCrud']){
						$data[$key] = $this->input->post($key);
					}
				}
				
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