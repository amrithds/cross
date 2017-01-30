<?php
class Events_model extends CI_Model {
	function __construct() {
		parent::__construct ();
	}
	public function getEvents($criteria = array()) {
		$this->db->select ( 'e.*,a.location_latitude as latitude,a.location_longitude as longitude,a.address' );
		$this->db->from ( 'events e' );
		$this->db->join ( 'auditoriums a', 'e.auditorium_id =a.id', 'left' );
		
		if (! empty ( $criteria )) {
			$query = $this->db->where ( 'e.id', $criteria ['id'] );
		}
		$this->db->order_by ( "e.created_at", "desc" );
		$query = $this->db->get ( 'events' );
		
		return $query->result_array ();
	}
	
	
	public function getEventStandDetails($eventId){
		$this->db->select ( 's.id as stand_id,s.name,s.image as stand_image,s.details,c.logo,c.address,(CASE WHEN sb.id IS NULL THEN "0" ELSE "1" END) AS is_booked ' );
		$this->db->from ( 'events e' );
		$this->db->join ( 'auditoriums a', 'e.auditorium_id =a.id', 'left' );
		$this->db->join ( 'stands s', 'a.id =s.auditorium_id', 'left' );
		$this->db->join ( 'stand_bookings sb', 's.id = sb.stand_id', 'left' );
		$this->db->join ( 'users u', 'sb.user_id = u.id', 'left' );
		$this->db->join ( 'companies c', 'u.company_id = c.id', 'left' );
		$query = $this->db->where ( "e.id = $eventId AND ( sb.is_deleted = 0 OR sb.is_deleted IS NULL ) ");
		$query = $this->db->get ( 'events' );
		
		return $query->result_array ();
	}
	
	
	public function createEvent($data) {
		$this->db->trans_start ();
		$this->db->insert ( 'events', $data );
		
		if ($this->db->trans_status () === FALSE) {
		}
		return;
	}
}