<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_positions Controller Class For Job Titles Manipulatio
*/
class C_positions extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_positions');
		$this->load->model('m_validation');
//		$this->load->model('m_users');
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
		$data['active_page'] = 'job_titles';

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

		// Getting Position List from DB
		//$data['position_list'] = $this->m_positions->get_positions();

		// Get Department List
		$this->load->model('m_departments');
		$data['department_list'] = $this->m_departments->get_departments();

		// Loading Postion Create Form
		$data['pos_create_form'] = $this->load->view('pages/positions/create_form', $data, true);

		// Loading Postions Main Section (Table Body)
		$this->load->view('pages/positions/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/positions/scripts', $data, true);

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
	|Getting Position List for Ajax Request
	|---------------------------------------------------------------------------------
	*/
	public function positions_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'organization';
		$data['active_page'] = 'job_titles';

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
		// Getting Position List from DB
		$data['position_list'] = $this->m_positions->get_positions($id, $getConfig);
		$this->response($data['position_list']);

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
	|Creating Job Title
	|---------------------------------------------------------------------------------
	*/
	public function positions_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'organization';
		$check['active_page'] = 'job_titles';

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
		if ( $this->form_validation->run('pos_create') ) 
		{
			if ( trim($this->post('name')) !== FALSE && trim($this->post('note')) !== FALSE ) 
			{	
				if ( $this->post('depId') !== FALSE && $this->post('depId') !== "" ) 
				{
					$job_title['dep_id'] = $this->post('depId');
				}

				$job_title['name'] = trim($this->post('name'));
				$job_title['note'] = trim($this->post('note'));
				
				// Inseting New Position
				$res = $this->m_positions->insert( $job_title );
				
				if ( $res ) 
				{
					$job_title['id'] = $res;

					$status['create_status']["status"] = 'success';
					$status['create_status']["message"] = $job_title;
					$status['create_status']["id"] = $job_title['id'];

					
					//Postback
					$this->response( $status['create_status'], REST_Controller::HTTP_CREATED );
				}
				else
				{
					$status['create_status']["status"] = 'failure';
					$status['create_status']["message"] = 'Insert Error';
					// Postback
					$this->response( $status['create_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
				}
			}
			else
			{
				$status['create_status']["status"] = 'failure';
				$status['create_status']["message"] = 'POST Error';

				// Postback
				$this->response( $status['create_status'], REST_Controller::HTTP_UNPROCESSABLE_ENTITY );
			}
		}
		else
		{
				$status['create_status']["status"] = 'failure';
				$status['create_status']["message"] = validation_errors();

				// Postback
				$this->response( $status['create_status'], REST_Controller::HTTP_BAD_REQUEST );
		}

	}//#positions_post
	

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
	public function positions_put($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'organization';
		$check['active_page'] = 'job_titles';

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

		$job_title = array();
		
		
		//Passing Data to Validate
		$this->form_validation->set_data( $this->put() );
		/*
		|---------------------------------------------------------------------------------
		| Get Position From DB and Check With Updateable One
		|---------------------------------------------------------------------------------
		*/
		$dbPositionInfo = $this->m_positions->get_positions($id)['items'][0];

		if ( $dbPositionInfo ) 
		{
			//$this->response($dbPositionInfo);
			// Position Name Change Check
			if ( $dbPositionInfo['name'] != trim($this->put('name')) && strcasecmp($dbPositionInfo['name'], trim($this->put('name')))==0 ) 
			{
				$job_title['name'] = trim($this->put('name'));				
			}
			elseif ( $dbPositionInfo['name'] !== trim($this->put('name')) ) 
			{
				$job_title['name'] = trim($this->put('name'));	
					$this->form_validation->set_rules('name','Job Title', 'trim|required|is_unique[positions.name]');	
			}
			
			
			// Checking For Job Title Note Update
			if ( trim($this->put('note')) !== false ) 
			{
				$job_title['note'] = trim($this->put('note'));
				$this->form_validation->set_rules('note','Note', 'trim');
			}
			
			// Checking For Department Update
			if ( $this->put('depId') !== false ) {
				$job_title['dep_id'] = $this->put('depId');
			}

			// Validating PUT Values
			if ( $this->form_validation->run() !== false ) 
			{
				$job_title['id'] = $id;
					
				// Updatig Job Title 
				$res = $this->m_positions->update( $job_title );
					
				if ( $res > 0 )
				{
					$status['update_status']["status"] 	= 'success';
					$status['update_status']["message"] = 'Update Success';
					//Postback
					$this->response( $status['update_status'], REST_Controller::HTTP_OK );
				}
				else
				{
					$status['update_status']["status"] 	= 'failure';
					$status['update_status']["message"] = "Nothing Has Updated";
					//Postback
					$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );	
				}
			}
			else
			{
				$status['update_status']["status"] 	= 'failure';
				$status['update_status']["message"] = validation_errors();
				// Postback
				$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			}
		}
		else
		{
			$status['update_status']["status"] 	= 'failure';
			$status['update_status']["message"] = 'Invalid Operation. Refresh Page and Try Again';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}
	}//#positions_put


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
	public function positions_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'organization';
		$check['active_page'] = 'job_titles';

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

		$res = $this->m_positions->delete($id);
		if ( $res['status'] ) 
		{
			$status['delete_status']["status"] = 'success';
			$status['delete_status']["message"] = 'Delete Success.';
			$this->response( $status['delete_status'], REST_Controller::HTTP_OK );
		}
		else
		{
			$status['delete_status']["status"] = 'failure';
			$status['delete_status']["message"] = 'Cannot delete. Job Title Exists in some tables '.$res['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR  );
		}

	}//#positions_delete




	/*
	|---------------------------------------------------------------------------------
	| Check For Same Job Title Value When Editing
	|---------------------------------------------------------------------------------
	*/
	public function is_same( $id, $name )
	{
		//Select and Get Statement
		$res = $this->db->select('')
				->from('positions')
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



//-->END of "c_positions" Controller class
}
 ?>