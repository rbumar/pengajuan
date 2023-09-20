<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pettycash extends MY_Controller {

	public function __construct(){
		parent::__construct();
		modules::load('auth/auth')->is_logged_in();
		$this->load->model('m_pettycash');
	}

	public function index()
	{
		$username = $this->session->userdata['name'];
		$data = ['username' => $username];
		$data['pengajuan'] = $this->m_pettycash->data();
		$this->load->view('templates/script');
		$this->load->view('v_pettycash', $data);
	}

	public function form_tambah(){
		$this->load->view('v_tambah_pettycash');
	}
	// simpan data pengajuan pettycash
	public function simpanPettycash(){
		//simpan pettycash ini menyimpan sekaligus di 3 table, diantaranya
		//table pengajuan_pettycash, pertanggung_jawaban, approval

		// id yang didapat dari pembuatan function di sql
		$next_sequence = $this->m_pettycash->get_next_pettycash_sequence();
		$nextPertanggungJawab = $this->m_pettycash->get_next_pertanggung_jawaban_sequence();
		$nextApproval = $this->m_pettycash->get_next_approval_sequence();
		
		
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
		$namaPengajuan = $this->input->post('namaPengajuan');
		$nominal = $this->input->post('nominal');
		$deskripsi = $this->input->post('deskripsi');
		$idKaryawan = $this->input->post('idKaryawan');
		$id = $next_sequence;
		$idPertanggungJawaban = $nextPertanggungJawab;
		$idApproval = $nextApproval;


		$simpanData = $this->m_pettycash->simpan($id,$namaPengajuan, $nominal, $deskripsi, $idKaryawan,$idPertanggungJawaban,$idApproval);
		$data['pengajuan'] = $this->m_pettycash->data();
		$this->load->view('templates/header', $data);
		$this->load->view('v_pettycash', $data);
		$this->load->view('templates/footer');
		}

	}
	public function nominalDiberikan(){
		if($_SERVER['REQUEST_METHOD']=== 'POST'){
			$nominalFix = $this->input->post('nominalDiberikan');
			$idPengajuan = $this->input->post('id_pengajuan');
			$result = $this->m_pettycash->nominalDiberikan($idPengajuan, $nominalFix);
			if ($result) {
				$updateData = $this->load->view('v_pettycash', [], TRUE);
				echo json_encode(['status' => 'success', 'updateData' => $updateData]);
			} else {
				echo json_encode(['status' => 'error']);
			}
			
		}
	}

	// mengubah status pengajuan
	public function ubahStatus(){
		$id = $this->input->post('id');
        $status = $this->input->post('status');
        $idKaryawan = $this->input->post('idKaryawan');
		$this->m_pettycash->ubahStatus($id, $status,$idKaryawan);
	}

}
