<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller{
	
	private $_url;
	private $_title = 'User';
	private $_mainModel = 'm_user';
	private $_mainView  = 'v_user';

	public function __construct(){
		parent::__construct();
		// session check 
		modules::load('auth/auth')->is_logged_in();

		// setting url untuk form 
		$base_url = base_url('user');
		$this->_url = ['url' => $base_url, 'form' => $base_url.'/form', 'crud' => $base_url.'/crud'];
		$this->load->model($this->_mainModel, 'mainModel');
	}

	// main function master barang 
	public function index(){

		$data = [
			'title' => 'Users',
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
		$placeholderId = $this->mainModel->_placeholderId ?  str_replace('0', 'X', $this->m_parameter->getParamValue($this->mainModel->_placeholderId)->value) : false;

		$this->load->model('karyawan/m_karyawan');
		$dataKaryawan = $this->m_karyawan->getUserKaryawan();

		$data = [
			'title' => 'Form'.$this->_title,
			'column' => $this->mainModel->_column,
			'placeholderId' => $placeholderId,
			'selectData' => ['dataKaryawan' =>$dataKaryawan],
			'url' => $this->_url,
			'isEdit' => false
		];

		$isEdit = $this->input->get('isEdit');
		if($isEdit){
			$id = $this->input->get('id');
			$data['data'] = $this->mainModel->get($id);
			$data['isEdit'] = true;
		}

		$this->load->view($this->_mainView.'_form', $data);
		$this->load->view('templates/script', $this->_url);
	}

	// crud function 
	public function crud(){
		$isDelete = $this->input->get('isDelete');
		if($isDelete){
			$id = $this->input->get('id');
			$result = $this->mainModel->delete($id);
		}else{

			$data = [];
			foreach($this->mainModel->_column as $key => $val){
				if($val['isCrud']){
					$data[$key] = $key == 'password' ? password_hash($this->input->post($key), PASSWORD_DEFAULT) : $this->input->post($key);	
				}
			}
			
			$result = $this->mainModel->addOrEdit($data);
		}


		if($result){
			echo 'OK';
		}else{
			echo $result;
		}
	}
}

?>