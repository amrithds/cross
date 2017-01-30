<?php
class Users_model extends CI_Model {
	
	/**
	 * array to map table data
	 * 
	 * @param array $data
	 *        	return bool
	 */
	function create($data) {
		return $this->db->insert ( 'users', $data );
	}
	public function login($email, $password) {
		$this->db->select ( 'id, email, fname, lname' );
		$criteria = array (
				'email' => $email,
				'status' => self::USER_STATUS_ACTIVE,
				'password' => md5 ( $password ) 
		);
		$this->db->from ( 'users' );
		$this->db->where ( $criteria );
		$this->db->limit ( 1 );
		
		$query = $this->db->get ();
		
		if ($query->num_rows () == 1) {
			$res = $query->result ();
			return $res [0];
		} else {
			return false;
		}
	}
}