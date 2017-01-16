<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_departments Controller Class
*/
class C_departments extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_departments');
		$this->load->model('m_validation');
		$this->load->model('m_users');
		$this->load->model('m_company');
		$this->load->library('form_validation');
	}

/*
─────────────────────────────────────────────────────────────────────────
─██████──────────██████─██████████████─██████████─██████──────────██████─
─██░░██████████████░░██─██░░░░░░░░░░██─██░░░░░░██─██░░██████████──██░░██─
─██░░░░░░░░░░░░░░░░░░██─██░░██████░░██─████░░████─██░░░░░░░░░░██──██░░██─
─██░░██████░░██████░░██─██░░██──██░░██───██░░██───██░░██████░░██──██░░██─
─██░░██──██░░██──██░░██─██░░██████░░██───██░░██───██░░██──██░░██──██░░██─
─██░░██──██░░██──██░░██─██░░░░░░░░░░██───██░░██───██░░██──██░░██──██░░██─
─██░░██──██████──██░░██─██░░██████░░██───██░░██───██░░██──██░░██──██░░██─
─██░░██──────────██░░██─██░░██──██░░██───██░░██───██░░██──██░░██████░░██─
─██░░██──────────██░░██─██░░██──██░░██─████░░████─██░░██──██░░░░░░░░░░██─
─██░░██──────────██░░██─██░░██──██░░██─██░░░░░░██─██░░██──██████████░░██─
─██████──────────██████─██████──██████─██████████─██████──────────██████─
─────────────────────────────────────────────────────────────────────────
*/

	public function index_get()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'organization';
		$data['active_page'] = 'departments';

		// Checking User Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** READ PERMISSION CHECK *********###
		############################################	
		if ( $allow['read'] === FALSE )
		{
			// Loading Header File
			$this->load->view('templates/header', $data);
			// Getting Sidebar From Session
			$data['sidebar'] = $this->session->userdata('sidebar');
			// Loading Sidebar File
			$this->load->view('templates/sidebar', $data);
			//Loading Error Content
			$this->load->view('errors/error_550', $data);
			// Loading Footer File
			$this->load->view('templates/footer', $data);
			//exit;
			return;
		}

		// Load TS Helper For Getting missed Timesheets
		$this->load->helper('ts');
		$data['notify'] = get_notifications_helper($data['userinfo']['id']);
		
		// Passing Requested Page Permissions For Later Use in View
		$data['allow'] = $allow;

		// Loading Header File
		$this->load->view('templates/header', $data);
		
		// Getting Sidebar From Session
		$data['sidebar'] = $this->session->userdata('sidebar');

		// Loading Sidebar File
		$this->load->view('templates/sidebar', $data);

		######################################################################################################
		#################################### * End of Permission Check * #####################################
		######################################################################################################

		// Getting Department List from DB
		//$data['dep_list'] = $this->m_departments->get_departments();

		// Get Company Info
		$data['company_list'] = $this->m_company->get_company();

		// Get User List/ Brief(true)
		$data['user_list'] = $this->m_users->get_users(0,true)['items'];

		// Loading Department Create Form
		$data['dep_create_form'] = $this->load->view('pages/departments/create_form', $data, true);

		// Loading Department Main Section (Table Body)
		$this->load->view('pages/departments/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/departments/scripts', $data, true);

		// Loading Footer File
		$this->load->view('templates/footer', $data);

	}//#index_get*


/*──────────────────────────────────────────────
─██████████████─██████████████─██████████████─
─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
─██░░██████████─██░░██████████─██████░░██████─
─██░░██─────────██░░██─────────────██░░██─────
─██░░██─────────██░░██████████─────██░░██─────
─██░░██──██████─██░░░░░░░░░░██─────██░░██─────
─██░░██──██░░██─██░░██████████─────██░░██─────
─██░░██──██░░██─██░░██─────────────██░░██─────
─██░░██████░░██─██░░██████████─────██░░██─────
─██░░░░░░░░░░██─██░░░░░░░░░░██─────██░░██─────
─██████████████─██████████████─────██████─────
──────────────────────────────────────────────*/
	/*
	|---------------------------------------------------------------------------------
	|Getting Department List for Ajax Request
	|---------------------------------------------------------------------------------
	*/
	public function departments_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'organization';
		$data['active_page'] = 'departments';

		// Checking User Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** READ PERMISSION CHECK *********###
		############################################	
		if ( $allow['read'] === FALSE )
		{
			$data['read_status']["status"] = '550';
			$data['read_status']["message"] = 'You Don`t Have Permissions to View This Page!!!';
			$this->response($data['read_status']);
			return;
		}

		$perPage = 10;
		$page = 1;
		$sortKey = '';

		$getConfig = array();

		if ( $this->get('q') !== false) {
			$getConfig['q'] = $this->get('q');
		}

		if ( $this->get('per_page') !== false) {
			$getConfig['per_page'] = $this->get('per_page');
		}

		if ( $this->get('page') !== false) {
			$getConfig['page'] = $this->get('page');
		}

		if ( $this->get('sort') !== false) {
			$getConfig['sort'] = $this->get('sort');
		}

		if ( $this->get('order') !== false) {
			$getConfig['order'] = $this->get('order');
		}
		/*print_r($this->get()) ;
		die;*/
		// Getting Department List from DB
		$data['dep_list'] = $this->m_departments->get_departments_paged($id, $getConfig);
		$this->response($data['dep_list']);

	}


/*───────────────────────────────────────────────────────────────────────────────────────────────────
─██████████─██████──────────██████─██████████████─██████████████─████████████████───██████████████─
─██░░░░░░██─██░░██████████──██░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░░░██───██░░░░░░░░░░██─
─████░░████─██░░░░░░░░░░██──██░░██─██░░██████████─██░░██████████─██░░████████░░██───██████░░██████─
───██░░██───██░░██████░░██──██░░██─██░░██─────────██░░██─────────██░░██────██░░██───────██░░██─────
───██░░██───██░░██──██░░██──██░░██─██░░██████████─██░░██████████─██░░████████░░██───────██░░██─────
───██░░██───██░░██──██░░██──██░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░░░██───────██░░██─────
───██░░██───██░░██──██░░██──██░░██─██████████░░██─██░░██████████─██░░██████░░████───────██░░██─────
───██░░██───██░░██──██░░██████░░██─────────██░░██─██░░██─────────██░░██──██░░██─────────██░░██─────
─████░░████─██░░██──██░░░░░░░░░░██─██████████░░██─██░░██████████─██░░██──██░░██████─────██░░██─────
─██░░░░░░██─██░░██──██████████░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░██──██░░░░░░██─────██░░██─────
─██████████─██████──────────██████─██████████████─██████████████─██████──██████████─────██████─────
───────────────────────────────────────────────────────────────────────────────────────────────────
*/
	/*
	|---------------------------------------------------------------------------------
	|Creating Department
	|---------------------------------------------------------------------------------
	*/
	public function departments_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'organization';
		$check['active_page'] = 'departments';

		// Checking User Session Activity
		$check['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $check['active_section'], $check['active_page'] );

		############################################
		##****** CREATE PERMISSION CHECK *********##
		############################################	
		if ( $allow['create'] === FALSE )
		{
			$status['create_status']["status"] = '403';
			$status['create_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
			$this->response($data['create_status']);
			return;
		}

		//Passing Data to Validate
		$this->form_validation->set_data($this->post());

		//Validating Input Fields
		if ( $this->form_validation->run('dep_create') ) 
		{
			if ( trim($this->post('depName')) !== FALSE && $this->post('depHeadId') !== FALSE && $this->post('depCompanyId') !== FALSE ) 
			{	
				$department = array( 'name'	 	=>	trim($this->post('depName')),
									 'head_id'	=>	$this->post('depHeadId'),
									 'org_id' 	=>	$this->post('depCompanyId'));
				
				// Inseting New Department
				$res = $this->m_departments->insert( $department );
				
				if ( $res ) 
				{
					$department['id'] = $res;

					$status['create_status']["status"] 	= 'success';
					$status['create_status']["message"] = $department;
					$status['create_status']["id"] 		= $department['id'];

					
					//Postback
					$this->response( $status['create_status'], REST_Controller::HTTP_CREATED );
				}
				else
				{
					$status['create_status']["status"] 	= 'failure';
					$status['create_status']["message"] = 'Internal Error: ' . $this->db->error();
					// Postback
					$this->response( $status['create_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
				}
			}
			else
			{
				$status['create_status']["status"] 	= 'failure';
				$status['create_status']["message"] = 'POST Error';

				// Postback
				$this->response( $status['create_status'], REST_Controller::HTTP_UNPROCESSABLE_ENTITY );
			}
		}
		else
		{
				$status['create_status']["status"] 	= 'failure';
				$status['create_status']["message"] = validation_errors();

				// Postback
				$this->response( $status['create_status'], REST_Controller::HTTP_BAD_REQUEST );
		}

	}//#departments_post
	

/*───────────────────────────────────────────────────────────────────────────────────────────
─██████──██████─██████████████─████████████───██████████████─██████████████─██████████████─
─██░░██──██░░██─██░░░░░░░░░░██─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
─██░░██──██░░██─██░░██████░░██─██░░████░░░░██─██░░██████░░██─██████░░██████─██░░██████████─
─██░░██──██░░██─██░░██──██░░██─██░░██──██░░██─██░░██──██░░██─────██░░██─────██░░██─────────
─██░░██──██░░██─██░░██████░░██─██░░██──██░░██─██░░██████░░██─────██░░██─────██░░██████████─
─██░░██──██░░██─██░░░░░░░░░░██─██░░██──██░░██─██░░░░░░░░░░██─────██░░██─────██░░░░░░░░░░██─
─██░░██──██░░██─██░░██████████─██░░██──██░░██─██░░██████░░██─────██░░██─────██░░██████████─
─██░░██──██░░██─██░░██─────────██░░██──██░░██─██░░██──██░░██─────██░░██─────██░░██─────────
─██░░██████░░██─██░░██─────────██░░████░░░░██─██░░██──██░░██─────██░░██─────██░░██████████─
─██░░░░░░░░░░██─██░░██─────────██░░░░░░░░████─██░░██──██░░██─────██░░██─────██░░░░░░░░░░██─
─██████████████─██████─────────████████████───██████──██████─────██████─────██████████████─*/
	/*
	|---------------------------------------------------------------------------------
	|Updating Job Title
	|---------------------------------------------------------------------------------
	*/
	public function departments_put($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'organization';
		$check['active_page'] = 'departments';

		// Checking User Session Activity
		$check['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $check['active_section'], $check['active_page'] );

		############################################
		##****** UPDATE PERMISSION CHECK *********##
		############################################	
		if ( $allow['update'] === FALSE )
		{
			$status['update_status']["status"] = '403';
			$status['update_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
			$this->response($data['update_status']);
			return;
		}

		$department = array();
		/*
		|---------------------------------------------------------------------------------
		| Get department From DB and Check With Updateable One
		|---------------------------------------------------------------------------------
		*/
		$dbDepartmentInfo = $this->m_departments->get_departments($id)[0];
		
		if ( $dbDepartmentInfo ) 
		{
			//Passing Data to Validate
			$this->form_validation->set_data( $this->put() );
		
			if ( $this->put('id') !== FALSE 
				&& $this->put('depName') !== FALSE 
				&& $this->put('depHeadId') !== FALSE 
				&& $this->put('depCompanyId') !== FALSE ) 
			{

				// Department Name Change Check
				if ( $dbDepartmentInfo['depName'] != trim($this->put('depName')) && strcasecmp( $dbDepartmentInfo['depName'], trim($this->put('depName')))==0 ) 
				{
					$department['name'] = trim($this->put('depName')); //*				
				}
				elseif( $dbDepartmentInfo['depName'] !== trim($this->put('depName')) )
				{
					$department['name'] = trim($this->put('depName'));	
					$this->form_validation->set_rules('depName','Department', 'trim|required|is_unique[departments.name]');	
				}
			}
			else
			{
				$status['update_status']["status"] = 'failure';
				$status['update_status']["message"] = 'Invalid Parameters.';
				// Postback
				$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
				return false;
			}

			
			//Department head value later will be optional i think
			$this->form_validation->set_rules('depHeadId','Department Head', 'trim|required');
			$this->form_validation->set_rules('depCompanyId','Company', 'trim|required');
			
			$department['head_id'] = $this->put('depHeadId');
			$department['org_id']  = $this->put('depCompanyId');
			
			// Validating PUT Values
			if ( $this->form_validation->run() !== FALSE ) 
			{
				$department['id'] = $id;
					
				// Updatig Department 
				$res = $this->m_departments->update( $department );
					
				if ( $res > 0 )
				{
					$status['update_status']["status"] = 'success';
					$status['update_status']["message"] = 'Update Success';
					//Postback
					$this->response( $status['update_status'], REST_Controller::HTTP_OK );
				}
				else
				{
					$status['update_status']["status"] = 'failure';
					$status['update_status']["message"] = "Nothing Has Updated";
					//Postback
					$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );	
				}
			}
			else
			{
				$status['update_status']["status"] = 'failure';
				$status['update_status']["message"] = validation_errors();
				// Postback
				$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			}
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Invalid Client. Refresh Page and Try Again';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}
	}//#departments_put


/*───────────────────────────────────────────────────────────────────────────────────────────
─████████████───██████████████─██████─────────██████████████─██████████████─██████████████─
─██░░░░░░░░████─██░░░░░░░░░░██─██░░██─────────██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
─██░░████░░░░██─██░░██████████─██░░██─────────██░░██████████─██████░░██████─██░░██████████─
─██░░██──██░░██─██░░██─────────██░░██─────────██░░██─────────────██░░██─────██░░██─────────
─██░░██──██░░██─██░░██████████─██░░██─────────██░░██████████─────██░░██─────██░░██████████─
─██░░██──██░░██─██░░░░░░░░░░██─██░░██─────────██░░░░░░░░░░██─────██░░██─────██░░░░░░░░░░██─
─██░░██──██░░██─██░░██████████─██░░██─────────██░░██████████─────██░░██─────██░░██████████─
─██░░██──██░░██─██░░██─────────██░░██─────────██░░██─────────────██░░██─────██░░██─────────
─██░░████░░░░██─██░░██████████─██░░██████████─██░░██████████─────██░░██─────██░░██████████─
─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─────██░░██─────██░░░░░░░░░░██─
─████████████───██████████████─██████████████─██████████████─────██████─────██████████████─
───────────────────────────────────────────────────────────────────────────────────────────
*/
	/*
	|---------------------------------------------------------------------------------
	|Destroying Job Title
	|---------------------------------------------------------------------------------
	*/
	public function departments_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'organization';
		$check['active_page'] = 'departments';

		// Checking User Session Activity
		$check['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requested Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $check['active_section'], $check['active_page'] );

		############################################
		##****** DELETE PERMISSION CHECK *********##
		############################################	
		if ( $allow['delete'] === FALSE )
		{
			$status['delete_status']["status"] = '403';
			$status['delete_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
			$this->response($data['delete_status']);
			return;
		}

		$res = $this->m_departments->delete($id);
		if ( $res['status'] ) 
		{
			$status['delete_status']["status"] = 'success';
			$status['delete_status']["message"] = 'Delete Success.';
			$this->response( $status['delete_status'], REST_Controller::HTTP_OK );
		}
		else
		{
			$status['delete_status']["status"] = 'failure';
			$status['delete_status']["message"] = 'Cannot delete. Department Exists in some tables '.$res['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}

	}//#departments_delete




	/*
	|---------------------------------------------------------------------------------
	| Check For Same Job Title Value When Editing
	|---------------------------------------------------------------------------------
	*/
	public function is_same( $id, $name )
	{
		//Select and Get Statement
		$res = $this->db->select('')
				->from('departments')
				->where(array('id'=>$id,'name'=>$name))
				->get();

		if ( $res->num_rows() > 0 ) 
		{
			//Same Name
			return true;
		}
		else
		{
			//Another name
			return false;
		}
	}



//-->END of "c_departments" Controller class
}
 ?>