<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct(){
		parent::__construct();
		modules::load('auth/auth')->isLoggedIn();
		$this->load->model('m_home');
	}

	public function index()
	{
		// email yang dikirim dari login di tangkap disini
		$username = $this->session->userdata('email');
		// Untuk mengakses nilai dari sesi
		$idKaryawan = $this->session->userdata('idKaryawan');
		$namaKaryawan = $this->session->userdata('namaKaryawan');

		$data = [
			'idKaryawan' => $idKaryawan,
			'namaKaryawan' => $username,
			'username' => $username
		];

		$this->load->view('templates/header', $data);
		$this->load->view('home', $data);
		$this->load->view('templates/footer');
	}

}
