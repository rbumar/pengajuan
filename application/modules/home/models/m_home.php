<?php 

class m_home extends CI_Model{

	public function __construct(){
		parent::__construct();
	}

   public function dakar($data){
    $this->db->select('*');
    $this->db->from('karyawan');
    $this->db->join('users', 'users.email = karyawan.email');
    $this->db->where('karyawan.email', $data['username']); // Menggunakan $data['username'] sebagai nilai WHERE
    $query = $this->db->get();
    return $query->result(); 
}



	

}

 ?>