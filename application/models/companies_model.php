<?php
class Companies_model extends CI_Model {
	/**
	 * 
	 * @param string $criteria
	 * return mysql resultset
	 */
	public function getCompanies($criteria = NULL) {
		$this->db->order_by ( "created_at", "desc" );
		if ($criteria !== NULL) {
			$query = $this->db->get_where ( 'companies', $criteria );
		} else {
			$query = $this->db->get ( 'companies' );
		}
		return $query->result_array ();
	}
	
	public function create($data){
		$this->db->insert ( 'companies', $data );
		return $this->db->insert_id();
	}
}