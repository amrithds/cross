<?php
class Api_Controller extends CI_Controller {
	public $data = array ();
	
	public function __construct() {
		parent::__construct ();
		$this->data ['status'] = 200;
		$this->data ['message'] = '';
		$this->data ['error'] = '';
		$this->data ['data'] = '';
		// ready json input
		
		if ($this->input->server ( 'REQUEST_METHOD' ) == "GET") {
			$_GET = self::getJsonInputs ();
		} else {
			$_POST = self::getJsonInputs ();
		}
		
	}

	/**
	 * function to get the input parameters
	 * 
	 * @return null
	 */
	private static function getJsonInputs() {
		$input = file_get_contents ( "php://input" );
		if (empty ( $input )) {
			return array();
		}
		
		return json_decode ( $input, true );
	}
	
	/**
	 * 
	 * @param array $data
	 * @param int $statusCode
	 * @param string $contentType
	 * return response object
	 */
	public function response($contentType = 'application/json') {
		return $this->output->set_content_type ( $contentType )->set_status_header ( $this->data['status'] )->set_output ( json_encode ( $this->data ) );
	}
}