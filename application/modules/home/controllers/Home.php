<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct(){
		parent::__construct();
		modules::load('auth/auth')->is_logged_in();
		$this->load->model('m_home');

	}

	public function index()
	{
		// email yang dikirim dari login di tangkap disini
		$username = $this->session->userdata('name');
		$data = ['username' => $username];
		$karyawan = $this->m_home->dakar($data);
		$id_karyawan = '';
		$nama_karyawan = '';

		foreach ($karyawan as $kr) {
			$id_karyawan = $kr->id_karyawan; // Perbaikan di sini
			$nama_karyawan = $kr->nama_karyawan; // Perbaikan di sini
		}

		// Set nilai-nilai dalam sesi
		$this->session->set_userdata('idKaryawan', $id_karyawan);
		$this->session->set_userdata('namaKaryawan', $nama_karyawan);

		// Untuk mengakses nilai dari sesi
		$idKaryawan = $this->session->userdata('idKaryawan');
		$namaKaryawan = $this->session->userdata('namaKaryawan');

		

        // die(var_dump($karyawan, 'masa null mulu'));
		$this->load->view('templates/header', $data);
		$this->load->view('home', $data);
		$this->load->view('templates/footer');
	}

}
