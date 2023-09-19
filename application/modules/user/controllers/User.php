<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

	public function __construct(){
		parent::__construct();
		modules::load('auth')->is_logged_in();
	}

	public function index()
	{
		$this->load->model('user/M_user');
		$data = [
					'username' => $this->session->userdata['name'], 
					'user_data' => $this->M_user->get_all()
				];

		$this->load->view('user', $data);
	}

	public function user_table(){
		$this->load->view('user_table');
	}

	public function add_user(){
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$created_by = $this->session->userdata['name'];

		$this->load->model('M_user');
		$result = $this->M_user->add_user($name, $email, $password, $created_by);
		if($result){
			echo 'OK';
		}else{
			echo $result;
		}

	}
	
}
