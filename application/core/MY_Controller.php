<?php
class Api_Controller extends CI_Controller {
	public $data = array ();
	public function __construct() {
		parent::__construct ();
		$this->data ['status'] = 200;
		$this->data ['message'] = '';
		$this->data ['error'] = '';
	}
	/**
	 * function to get the input parameters
	 * 
	 * @return array
	 */
	public function getJsonInputs() {
		$input = file_get_contents ( "php://input" );
		if (empty ( $input )) {
			return array ();
		}
		return json_decode ( $input, true );
	}
	public function response($data, $statusCode = 200, $contentType = 'application/json') {
		return $this->output->set_content_type ( $contentType )->set_status_header ( $statusCode )->set_output ( json_encode ( $data ) );
	}
}