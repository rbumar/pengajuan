<?php
class m_pettycash extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    // pemanggilan data dengan relasi table pengajuan_pettycash, pertanggung_jawaban
    public function data(){
        $this->db->select('pengajuan_pettycash.*, pengajuan_pettycash.deskripsi AS pettycash_deskripsi,pengajuan_pettycash.nominal as pettycash_nominal, karyawan.*,approval.*, approval.deskripsi as approval_deskripsi, pertanggung_jawaban.*,pertanggung_jawaban.nominal as nominal_pj');
        $this->db->from('pengajuan_pettycash');
        $this->db->join('approval', 'approval.id_pengajuan = pengajuan_pettycash.id_pengajuan');
        $this->db->join('karyawan','karyawan.id_karyawan = pengajuan_pettycash.id_karyawan');
        $this->db->join('pertanggung_jawaban','pertanggung_jawaban.id_pengajuan = pengajuan_pettycash.id_pengajuan');
        $query = $this->db->get();
        return $query->result();
   }
//    memanggil function yang telah di buat di sql dengan nama generate_pettycash_sequence()
   public function get_next_pettycash_sequence() {
    $query = $this->db->query("SELECT generate_pettycash_sequence() AS generated_id");
    $result = $query->row();
    $next_sequence = $result->generated_id;
    return $next_sequence;
}

public function get_next_pertanggung_jawaban_sequence(){
    $query = $this->db->query("SELECT generate_pertanggung_jawaban_sequence() AS generated_id");
    $result = $query->row();
    $nextPertanggungJawab = $result->generated_id;
    return $nextPertanggungJawab;
}

public function get_next_approval_sequence(){
    $query = $this->db->query("SELECT generate_approval_sequence() AS generated_id");
    $result = $query->row();
    $nextApproval = $result->generated_id;
    return $nextApproval;
}
public function dataWithWhere($where) {
    $this->db->where($where);
    $query = $this->db->get('approval'); // Ganti 'nama_tabel' dengan nama tabel yang sesuai

    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return array(); // Atau sesuaikan dengan respons yang sesuai jika tidak ada data
    }
}

   public function simpan($id,$namaPengajuan, $nominal, $deskripsi, $idKaryawan,$idPertanggungJawaban,$idApproval){
    // menyimpan di pengajuan pettycash
    $pengajuanPettycash = array(
        'id_pengajuan' => $id,
        'nama_pengajuan' => $namaPengajuan,
        'nominal' => $nominal,
        'nominal_approval'=>'0',
        'id_karyawan'=> $idKaryawan,
        'deskripsi' => $deskripsi
    );
    //menyimpan di pertanggung jawaban
    $pertanggungJawaban = array(
      'id_pertanggung_jawab' => $idPertanggungJawaban,
      'id_pengajuan' => $id,
      'nominal'=>0,
      'deskripsi'=>'none'
    );

    // menyimpan di approval
    $approval = array(
      'id_approval' => $idApproval,
      'id_pengajuan' => $id,
      'id_karyawan'=>'',
      'status'=>'Drafting',
      'deskripsi'=>''
    );
    $this->db->insert('pengajuan_pettycash', $pengajuanPettycash);
    $this->db->insert('pertanggung_jawaban',$pertanggungJawaban);
    $this->db->insert('approval',$approval);
    return true;
    
   }

//    update nominal yang diberikan
public function nominalDiberikan($idPengajuan, $nominalFix){
    $this->db->set('nominal_approval', $nominalFix);
    $this->db->where('id_pengajuan', $idPengajuan);
    $this->db->update('pengajuan_pettycash');
    return true;
}
public function ubahStatus($id,$status, $idKaryawan){
    $this->db->set('status',$status);
    $this->db->set('id_karyawan',$idKaryawan);
    $this->db->where('id_pengajuan',$id);
    $this->db->update('approval');
    return true;
}

public function cariStatus($status){
    $this->db->select('pengajuan_pettycash.*, pengajuan_pettycash.deskripsi AS pettycash_deskripsi,pengajuan_pettycash.nominal as pettycash_nominal, karyawan.*,approval.*, approval.deskripsi as approval_deskripsi, pertanggung_jawaban.*,pertanggung_jawaban.nominal as nominal_pj');
        $this->db->from('pengajuan_pettycash');
        $this->db->join('approval', 'approval.id_pengajuan = pengajuan_pettycash.id_pengajuan');
        $this->db->join('karyawan','karyawan.id_karyawan = pengajuan_pettycash.id_karyawan');
        $this->db->join('pertanggung_jawaban','pertanggung_jawaban.id_pengajuan = pengajuan_pettycash.id_pengajuan');
        $this->db->where('approval.status',$status);
        $query = $this->db->get();
        return $query->result();

}
public function detailPengajuan($id){
    $this->db->select('pengajuan_pettycash.*,pengajuan_pettycash.id_karyawan as pt_idKaryawan, pengajuan_pettycash.deskripsi AS pettycash_deskripsi,pengajuan_pettycash.nominal as pettycash_nominal,approval.*, approval.deskripsi as approval_deskripsi,approval.id_karyawan as ap_idKaryawan, pertanggung_jawaban.*,pertanggung_jawaban.nominal as nominal_pj, pertanggung_jawaban.deskripsi as pj_deskripsi');
        $this->db->from('pengajuan_pettycash');
        $this->db->join('approval', 'approval.id_pengajuan = pengajuan_pettycash.id_pengajuan');
        $this->db->join('pertanggung_jawaban','pertanggung_jawaban.id_pengajuan = pengajuan_pettycash.id_pengajuan');
        $this->db->where('pengajuan_pettycash.id_pengajuan',$id);
        $query = $this->db->get();
        return $query->result();
}

public function dakar(){
    $this->db->select("*");
    $this->db->from('karyawan');
    $query = $this->db->get();
    return $query->result();
}
    
   
}

?>