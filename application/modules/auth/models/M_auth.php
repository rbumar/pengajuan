<?php 

class M_auth extends CI_Model{

	private $_table = 'users';
	const SESSION_KEY = 'id';

	public function __construct(){
		parent::__construct();
	}

	public function rules()
	{
		return [
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'required'
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required|max_length[255]|min_length[4]'
			]
		];
	}

	public function login($email, $password ){

		$this->db->where('email', $email);
		$query = $this->db->get($this->_table);
		$user = $query->row();
		// die(var_dump($user));

		if(!$user){
			return 'User not found';
		}

		if (password_verify($password, $user->password)){
			$this->session->set_userdata(
				[
					self::SESSION_KEY => $user->password, 
					'id' => $user->id, 
					'email' => $user->email, 
					'name' => $user->name,
					'is_logged_in' => true
				]
			);
			$this->_update_last_login($user->id);
			return 'OK';
		}else{
			return 'User or password mismatch';
		}
	}

	public function is_logged_in(){
		if(!$this->session->userdata['is_logged_in']){
			redirect('auth');
		}
		return true;
	}

	private function _update_last_login($id){
		$data = ['last_login' => date('Y-m-d H:i:s')];
		return $this->db->update($this->_table, $data, ['id' => $id]);
	}

}

 ?>