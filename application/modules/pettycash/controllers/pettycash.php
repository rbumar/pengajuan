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
		$data['df'] = $this->m_pettycash->dataWithWhere(array('status' => 'Drafting'));
		$data['ap'] = $this->m_pettycash->dataWithWhere(array('status' => 'Approved'));
		$data['rj'] = $this->m_pettycash->dataWithWhere(array('status' => 'Rejected'));
		$data['pd'] = $this->m_pettycash->dataWithWhere(array('status' => 'Pending'));

		$data['all'] = count($data['pengajuan']);
		$data['draft'] = count($data['df']);
		$data['pending'] = count($data['pd']);
		$data['approved'] = count($data['ap']);
		$data['rejected'] = count($data['rj']);

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
			// if ($result) {
			// 	$updateData = $this->load->view('v_pettycash', [], TRUE);
			// 	echo json_encode(['status' => 'success', 'updateData' => $updateData]);
			// } else {
			// 	echo json_encode(['status' => 'error']);
			// }
			
		}
	}

	// mengubah status pengajuan
	public function ubahStatus(){
		$id = $this->input->post('id');
        $status = $this->input->post('status');
        $idKaryawan = $this->input->post('idKaryawan');
		$this->m_pettycash->ubahStatus($id, $status,$idKaryawan);
	}

	// merubah table yang di tampilkan berdasarkan menu yang di klik
	public function ambilData(){
		$status = $this->input->post('status');
		if ($status === 'all') { // Perhatikan penggunaan === untuk perbandingan tipe data
			$data = $this->m_pettycash->data();
			echo json_encode($data);
		} else {
			$data = $this->m_pettycash->cariStatus($status); // Panggil sebagai fungsi
			echo json_encode($data);
		}
	}

	// mencari detail pengajuan
	public function detailPengajuan(){
		$id = $this->input->post('id');
		$data['all'] = $this->m_pettycash->detailPengajuan($id);
		$data['dakar'] = $this->m_pettycash->dakar();
		$data['crd'] = '';
		$data['acc'] = '';
		foreach($data['all'] as $dt){
			foreach($data['dakar'] as $dkr){
				if($dt->pt_idKaryawan == $dkr->id_karyawan){
					$data['crd'] = $dkr->nama_karyawan;
				}
				if($dt->ap_idKaryawan == $dkr->id_karyawan){
					$data['acc'] = $dkr->nama_karyawan;
				}
			}
		}

		echo json_encode($data);
	}

}
