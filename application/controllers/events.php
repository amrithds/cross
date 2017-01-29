<?php
class Events extends Api_Controller{
	
	//status codes
	const STATUS_CANCELLED =0;
	const STATUS_ACTIVE =1;
	const STATUS_COMPLETED = 2;
	
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'events_model' ,'event');
	}
	
	/**
	 * 
	 * @param string $id (Event id)
	 */
	public function index($id = NULL){
		//array to add where condition in sql
		$criteria = array();
		
		if(! empty($id)){
			$criteria['id'] = $id;
		}
		
		$data ['data'] = $this->event->getEvents ($criteria);
		
		
		$this->response($data);
	}
	
	
}