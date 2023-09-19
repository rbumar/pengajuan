<?php 

class M_user extends CI_Model{

	private $_table = 'v_user';

	public function __construct(){
		parent::__construct();
	}

	public function get_all(){
		$query = $this->db->get($this->_table);
		return $query->result();
	}

	public function add_user($name, $email, $password, $created_by){

		$user_data = array(
			'name' => $name,
			'email' => $email,
			'status' => 1,
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'created_by' => $created_by,
			'updated_by' => $created_by
		);

		return $this->db->insert('users', $user_data);
	}
}
?>