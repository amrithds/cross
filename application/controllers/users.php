<?php
class Users extends Api_Controller{
	
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'users_model' ,'user');
	}
	
	public function create(){
		$this->load->library('my_form_validation');
		$this->my_form_validation->set_rules('fname', 'First Name', 'required');
		$this->my_form_validation->set_rules('lname', 'Last Name', 'required');
		$this->my_form_validation->set_rules('password', 'Password', 'required|matches[confirmpass]|min_length[5]|max_length[12]|alpha_numeric');
		$this->my_form_validation->set_rules('confirmpass', 'Password Confirmation', 'required');
		$this->my_form_validation->set_rules('phone_number', 'Last Name', 'numeric|min_length[10]|max_length[10]');
		$this->my_form_validation->set_rules ( 'email', 'email', 'required|valid_email' );
		$this->my_form_validation->set_rules ( 'company_name', 'Company Name', 'required' );
		
		// check if token is valid
		if ($this->my_form_validation->run () === FALSE) {
			
			$data ['error'] = 'invalid parameters';
			$this->response ( $data, 400 );
		} else {
			$encryptedPass = md5 ( $this->input->post ( 'password' ) );
			
			$userData = array (
					'password' => $encryptedPass,
					'status' => users::USER_STATUS_ACTIVE,
					'fname' => $this->input->post ( 'fname' ),
					'lname' => $this->input->post ( 'lname' ) 
			);
			$result = $this->users->create ( $token, $userData );
			$this->response ( $data );
		}	
		
	}
	
	public function login() {
		$this->load->library ( 'form_validation' );
		$this->form_validation->set_rules ( 'email', 'email', 'required|valid_email' );
		$this->form_validation->set_rules ( 'password', 'Password', 'required' );
		
		if ($this->form_validation->run () === FALSE) {
			$data ['error'][] = 'Invalid input';
			$this->response ( $data, 401 );
		} else {
			$email = $this->input->post ( 'email' );
			$password = $this->input->post ( 'password' );
			$result = $this->user->login ( $email, $password );
			if ($result !== false) {
				$session_data = array (
						'id' => $result->id,
						'email' => $email,
						'fname' => $result->fname,
						'lname' => $result->lname 
				);
				$this->session->set_userdata ( 'logged_in', $session_data );
				$data ['message'] = 'Success';
				$this->response ( $data );
			} else {
				$data ['error'][] = 'Invalid credentials';
				$this->response ( $data, 401 );
			}
		}
	}
}