<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_barang extends MY_Controller{
	public function __construct(){
		parent::__construct();
	}

	public function index(){

		$this->load->model('m_master_barang');
		$this->load->model('user/M_user');
		$data = [ 
			'title' => 'Master Data Barang',
			'data' => $this->M_user->get_all() 
		];
		$this->load->view('v_master_barang', $data);
		$this->load->view('templates/script');
	}
}

?>