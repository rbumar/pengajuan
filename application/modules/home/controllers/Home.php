<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct(){
		parent::__construct();
		modules::load('auth/auth')->is_logged_in();
	}

	public function index()
	{

		$username = $this->session->userdata['name'];
		$data = ['username' => $username];

		$this->load->view('templates/header', $data);
		$this->load->view('home', $data);
		$this->load->view('templates/footer');
	}

}
