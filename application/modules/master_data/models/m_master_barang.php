<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_master_barang extends CI_Model{

	private $_table = 'barang';

	public function __construct(){
		parent::__construct();
	}

	public function getAll(){
		return 'OK';
	}

}

 ?>