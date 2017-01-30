<?php
class Users extends Api_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'users_model', 'user' );
	}
	
	/**
	 * create user api
	 */
	public function create() {
		$this->load->model('companies_model','company');
		$this->load->library ( 'my_form_validation' );
		
		$this->my_form_validation->set_rules ( 'fname', 'First Name', 'required' );
		$this->my_form_validation->set_rules ( 'lname', 'Last Name', 'required' );
		$this->my_form_validation->set_rules ( 'address', 'Address', 'required' );
		//$this->my_form_validation->set_rules ( 'password', 'Password', 'required|matches[confirmpass]|min_length[5]|max_length[12]|alpha_numeric' );
		//$this->my_form_validation->set_rules ( 'confirmpass', 'Password Confirmation', 'required' );
		$this->my_form_validation->set_rules ( 'phone_number', 'Last Name', 'numeric|min_length[10]|max_length[10]' );
		$this->my_form_validation->set_rules ( 'email', 'email', 'required|valid_email|is_unique[users.email]' );
		$this->my_form_validation->set_rules ( 'company_name', 'Company Name', 'required' );
		$this->my_form_validation->set_rules ( 'admin_email', 'Admin Email', 'required|valid_email|is_unique[companies.admin_email]' );
		$this->my_form_validation->set_rules ( 'company_address', 'Company Address', 'required' );
		
		// check if token is valid
		if ($this->my_form_validation->run () === FALSE) {
			$this->data ['error'] = 'invalid parameters';
			$this->data ['status'] = 400;
		} else {
			$companyDocument = '';
			
			if(! empty($_FILES['document']['name'])){
				$companyDocument = $this->uploadFile('document','documents/','doc|pdf|gif|jpg|png');
			}
			
			if ( $companyDocument === false) {
				$this->data ['error'] = 'Unable to handle request';
				$this->data ['status'] = 500;
			} else {
				$companyLogo = '';
				if( ! empty($_FILES['logo']['name'])){
					$companyLogo = $this->uploadFile ( 'logo', 'images/', 'gif|jpg|png' );
				}
				
				if ($companyLogo === false) {
					$this->data ['error'] = 'Unable to handle request';
					$this->data ['status'] = 500;
				}
				
				$companyData = array (
						'name' => $_POST ['company_name'],
						'admin_email' => $_POST ['admin_email'],
						'address' => $_POST ['company_address'],
						'logo' => $companyLogo,
						'document' => $companyDocument 
				);
				$companyId = $this->company->create ( $companyData );
				
				if($companyId){
					$userData = array (
							'fname' => $_POST ['fname'],
							'lname' => $_POST ['lname'],
							'company_id' => $companyId,
							'phone_number' => $_POST ['phone_number'],
							'email' => $_POST ['email'],
							'address' => $_POST ['address']
					);
					$result = $this->user->create ( $userData );
					$this->data['message'] = "Success";
				}else{
					$this->data ['error'] = 'Unable to handle request';
					$this->data ['status'] = 500;
				}
			}
		}
		$this->response ();
	}
	
	private function uploadFile($fieldName,$path,$allowed_types){
		$config ['upload_path'] = './uploads/'.$path;
		$config ['allowed_types'] = 'doc|pdf|gif|jpg|png';
		
		//get session data
		$path = $_FILES[$fieldName]['name'];
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		
		$fileName = 'company_doc_'.time().'.'.$ext;
		$config['file_name'] = $fileName;
		$this->load->library ( 'upload', $config );
		
		if ($this->upload->do_upload ($fieldName)) {
			return $fileName;
		} else {
			return false;
		}
	}
	
	public function login() {
		$this->load->library ( 'form_validation' );
		$this->form_validation->set_rules ( 'email', 'email', 'required|valid_email' );
		$this->form_validation->set_rules ( 'password', 'Password', 'required' );
		
		if ($this->form_validation->run () === FALSE) {
			$data ['error'] [] = 'Invalid input';
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
				$data ['error'] [] = 'Invalid credentials';
				$this->response ( $data, 401 );
			}
		}
	}
}