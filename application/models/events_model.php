<?php
class Events_model extends CI_Model{
	function __construct() {
		parent::__construct ();
	}
	
	public function getEvents($criteria = array()) {
		$this->db->select('e.*,a.location_latitude as latitude,a.location_longitude as longitude,a.address');
		$this->db->from('events e');
		$this->db->join('auditoriums a', 'e.auditorium_id =a.id', 'left');
		
		if (!empty($criteria)) {
			$query = $this->db->where ( 'e.id', $criteria['id'] );
		}
		$this->db->order_by ( "e.created_at", "desc" );
		$query = $this->db->get ( 'events' );
		
		return $query->result_array ();
	}
	
	public function createEvent($data) {
		$this->db->trans_start();
		$this->db->insert ( 'events', $data );
		
		
		if ($this->db->trans_status() === FALSE) {
			
		}
		return ;
	}
}