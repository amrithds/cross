<?php
class Company extends Api_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'companies_model', 'company' );
	}
	
	/**
	 * get all company list
	 * 
	 * @param string $id
	 *        	(Company id)
	 */
	public function index($id = NULL) {
		$this->load->library ( 'form_validation' );
		$this->form_validation->set_rules ( 'id', 'Id', 'numeric' );
		
		// array to add where condition in sql
		$criteria = array ();
		if (! empty ( $id )) {
			$criteria ['id'] = $id;
		}
		
		$data ['data'] = $this->company->getCompanies ( $criteria );
		
		$this->response ( $data );
	}
}