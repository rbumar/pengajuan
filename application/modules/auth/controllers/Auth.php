<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('auth');
	}

	public function signIn(){

		$this->load->model('auth/M_auth');
		$this->load->library('form_validation');

		$rules = $this->M_auth->rules();
		$result_validation = $this->form_validation->set_rules($rules);
		$result_validation = $this->form_validation->set_rules($rules);

		if(!$this->form_validation->run()){
			return $this->load->view('auth', [ 'message' => validation_errors() ]);
		}

		$user_email = $this->input->post('email');
		$user_password = $this->input->post('password');

		$login_result = $this->M_auth->login($user_email, $user_password);
		if( $login_result !== 'OK'){
			return $this->load->view('auth', ['message' => $login_result]);
		}else{
			$this->session->set_userdata('name', $this->input->post('email'));
			redirect('home');
		}
		
	}

	public function isLoggedIn(){
		if(!$this->session->userdata['isLoggedIn']){
			redirect('auth');
		}
		return true;
	}

	public function is_logged_in(){
		if(!$this->session->userdata['isLoggedIn']){
			redirect('auth');
		}
		return true;
	}
	
	public function signOut(){
		$this->session->sess_destroy();
		redirect('auth');
	}
}
