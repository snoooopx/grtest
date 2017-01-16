<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_assignments Controller Class for Assignments Manipulation
*/
class C_assignments extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_assignments');
		$this->load->model('m_departments');
		$this->load->model('m_config');
		$this->load->model('m_validation');
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
	/*
	|---------------------------------------------------------------------------------
	|Main Page Load
	|---------------------------------------------------------------------------------
	*/
	public function index_get()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'repository';
		$data['active_page'] 	= 'assignments';

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

		// Get Department List
		$this->load->model('m_departments');
		$data['department_list'] = $this->m_departments->get_departments();

		// Get Operation List
		$this->load->model('m_operations');
		$data['operation_list'] = $this->m_operations->get_operations()['items'];

		// Loading Assignment Create Form
		$data['assignment_create_form'] = $this->load->view('pages/assignments/create_form', $data, true);

		// Loading Assignment Main Section 
		$this->load->view('pages/assignments/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/assignments/scripts', $data, true);

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
|Getting Assignment List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function assignments_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'repository';
		$data['active_page'] 	= 'assignments';

		// Checking Assignment Session Activity
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

		/*
		|  Getting Assignment List from DB
		|  3rd Parameter Config for pagination
		|  2nd Parameter brief(FALSE) For Full Columns
		*/
		$data['assignment_list'] = $this->m_assignments->get_assignments($id, false, $getConfig);
		$this->response($data['assignment_list']);

	}//#assignments_get


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
	|Insert Assignment
	|---------------------------------------------------------------------------------
	*/
	public function assignments_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'repository';
		$check['active_page'] 	 = 'assignments';

		// Checking Assignment Session Activity
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
		$assignment = [];
		// Passing Data to Validate
		$this->form_validation->set_data( $this->post() );
//$this->response( $this->post() );
		// Validate Posted Values for Assignment Create
		if ( $this->form_validation->run('assignment_create')) 
		{
			$assignment['name']			= trim($this->post('name')); //*
			$assignment['description'] 	= trim($this->post('description'));
			$assignment['is_visible']	= trim($this->post('is_visible'));
			$departmentsIds 			= $this->post('departmentsIds');
			$operationsIds 				= $this->post('operationsIds');


			//Insert New Assignment
			$res = $this->m_assignments->insert($assignment);

			if ( $res ) 
			{
				//Newly Created Assignment id
				$assignment['id'] = $res;
				
				// Check And Insert Department Assignments
				if ( !empty($departmentsIds) ) 
				{
					$deps_array = [];
					foreach ( $departmentsIds as $key=>$value )
					{
						$deps_array[$key]['ass_id']  = $assignment['id'];
						$deps_array[$key]['dep_id']  = $value;
					}
					// Insert Dep Assignments
					$res_dep_assignments = $this->m_departments->insert_dep_assignments( $deps_array );
					
					if ( $res_dep_assignments ) 
					{
						$res_dep_assignments_status = "";
					}
					else
					{
						$res_dep_assignments_status = "<p> / Error With Departments</p>"	;
					}
				}

				// Check And Insert Assignment Operations
				if ( !empty($operationsIds) ) 
				{
					$opers_array = [];
					foreach ( $operationsIds as $key=>$value )
					{
						$opers_array[$key]['ass_id']  = $assignment['id'];
						$opers_array[$key]['oper_id']  = $value;
					}
					// Insert Ass Operations
					$res_ass_operations = $this->m_assignments->insert_ass_operations( $opers_array );
					
					if ( $res_ass_operations ) 
					{
						$res_ass_operations_status = "";
					}
					else
					{
						$res_ass_operations_status = "<p> / Error With Assignments</p>"	;
					}
				}


				$status['create_status']["status"] 	= 'success';
				$status['create_status']["message"] = "Assignment Created." . $res_dep_assignments_status . $res_ass_operations_status;
				$status['create_status']["id"] 		= $assignment['id'];
				
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
			$status['create_status']["message"] = validation_errors();

			// Postback
			$this->response( $status['create_status'], REST_Controller::HTTP_BAD_REQUEST );
		}

		
	}//#assignments_post

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
	|Update Assignment Info
	|---------------------------------------------------------------------------------
	*/
	public function assignments_put($id='0')
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'repository';
		$check['active_page'] 	 = 'assignments';

		// Checking Assignment Session Activity
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

		$assignment = array();
		
		
		//Passing Data to Validate
		$this->form_validation->set_data( $this->put() );

		/*
		|---------------------------------------------------------------------------------
		| Get Assignment From DB and Check With Updateable One
		|---------------------------------------------------------------------------------
		*/
		$dbAssignmentInfo = $this->m_assignments->get_assignments($id)['items'][0];

		if ( $dbAssignmentInfo ) 
		{
			// Assignment Name Change Check
			if ( $dbAssignmentInfo['name'] !== trim($this->put('name')) && strcasecmp($dbAssignmentInfo['name'], trim($this->put('name')))==0 ) 
			{
				$assignment['name'] = trim($this->put('name')); //*				
			}
			elseif ( $dbAssignmentInfo['name'] !== trim($this->put('name')) ) 
			{
				$assignment['name'] = trim($this->put('name')); //*
				$this->form_validation->set_rules('name', 'Name', 'trim|alpha_numeric_spaces|required|is_unique[assignments.name]');
			}

			$are_deps_changed = false;
			// Make Dep Ids String an Array
			$db_deps_arr = explode(',' , $dbAssignmentInfo['dep_ids_str']);
			
			// Assignment Dep Change Check
			if ( $db_deps_arr != $this->put('departmentsIds') ) 
			{
				$assignment['dep_ids'] = $this->put('departmentsIds'); //*
				$this->form_validation->set_rules('departmentsIds[]', 'Departments', 'trim|numeric|required');
			}

			$are_opers_changed = false;
			// Make Oper Ids String an Array
			$db_opers_arr = explode(',' , $dbAssignmentInfo['oper_ids_str']);
			
			// Assignment Oper Change Check
			if ( $db_opers_arr != $this->put('operationsIds') ) 
			{
				$assignment['oper_ids'] = $this->put('operationsIds'); //*
				$this->form_validation->set_rules('operationsIds[]', 'Operations', 'trim|numeric|required');
			}
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Invalid Assignment. Refresh Page and Try Again';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}

		$assignment['description'] 	= trim($this->put('description'));
		$assignment['is_visible']	= trim($this->put('is_visible'));


		// Setting Validation Rules For Update Operation
		$this->form_validation->set_rules('description', 'Description', 'trim|max_length[255]');
		$this->form_validation->set_rules('is_visible',  'Visibility',  'trim|numeric|in_list[0,1]');	
		
		// Validating PUT Values
		if ( $this->form_validation->run() !== FALSE ) 
		{
			$assignment['id'] = $id;
			
			/*
			|---------------------------------------------------------------------------------
			| Check Assignment Deps Select Options Change
			|---------------------------------------------------------------------------------
			*/
			if ( isset($assignment['dep_ids']) ) 
			{
				// Generating Assignment Departments Insert Array
				$assignment_deps_arr = [];
				
				foreach ( $assignment['dep_ids'] as $key=>$value ) 
				{
					$assignment_deps_arr[$key]['ass_id'] = $assignment['id'];
					$assignment_deps_arr[$key]['dep_id']    = $value;
				}
				unset($assignment['dep_ids']);
				// Delete Assignment Departments
				// First value $dep_id=false, Second val $assignment_id=false
				$res_del_dep = $this->m_departments->delete_dep_assignments(false, $assignment['id']);

				// Check for dep_assignments delete
				if ( $res_del_dep || $res_del_dep == 0 ) 
				{
					// Insert New Deps
					$res_ins_deps = $this->m_departments->insert_dep_assignments($assignment_deps_arr);
					if ( $res_ins_deps ) 
					{
						$are_deps_changed = true;
					}
					else
					{
						$status['update_status']["status"] = 'failure';
						$status['update_status']["message"] = "Error Changing Assignment Departments (B)";
						//Postback
						$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
					}
				}
				else
				{
					$status['update_status']["status"] = 'failure';
					$status['update_status']["message"] = "Error Changing Assignment Departments (A)";
					//Postback
					$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
				}
			}

			/*
			|---------------------------------------------------------------------------------
			| Check Assignment Opers Select Options Change
			|---------------------------------------------------------------------------------
			*/
			if ( isset($assignment['oper_ids']) ) 
			{
				// Generating Assignment Departments Insert Array
				$assignment_opers_arr = [];
				
				foreach ( $assignment['oper_ids'] as $key=>$value ) 
				{
					$assignment_opers_arr[$key]['ass_id']  = $assignment['id'];
					$assignment_opers_arr[$key]['oper_id'] = $value;
				}

				unset($assignment['oper_ids']);

				// Delete Assignment Operations
				// First value $ass_is=false, Second val $operation_id=false
				$res_del_assops = $this->m_assignments->delete_ass_operations($assignment['id'],false);
				// Check for ass_operations delete
				if ( $res_del_assops || $res_del_assops == 0 ) 
				{
					// Insert New Opers
					$res_ins_assigs = $this->m_assignments->insert_ass_operations($assignment_opers_arr);
					if ( $res_ins_assigs ) 
					{
						$are_opers_changed = true;
					}
					else
					{
						$status['update_status']["status"] = 'failure';
						$status['update_status']["message"] = "Error Changing Assignment Operations (B)";
						//Postback
						$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
					}
				}
				else
				{
					$status['update_status']["status"] = 'failure';
					$status['update_status']["message"] = "Error Changing Assignment Operations (A)";
					//Postback
					$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
				}
			}

			
			/*
			|---------------------------------------------------------------------------------
			| Update Assignment
			|---------------------------------------------------------------------------------
			*/ 
			$res = $this->m_assignments->update( $assignment );
			

			/*
			// Check Results
			*/

			if ( $res || $are_deps_changed || $are_opers_changed )
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
	}//#assignment_put

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
	|Destroying Assignment
	|---------------------------------------------------------------------------------
	*/
	public function assignments_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'repository';
		$check['active_page']	 = 'assignments';

		// Checking Assignment Session Activity
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

		// Check Client existence in another tables
		$check_result = $this->m_assignments->check_before_delete($id);

		// Check Delete and Response
		if ( $check_result['status'] ) 
		{
			// Delete Assignment from dep_assignments
			$res_del_dep = $this->m_departments->delete_dep_assignments( false, $id );

			// Delete operations from ass_operations
			$res_del_opers = $this->m_assignments->delete_ass_operations( $id, false );

			//Delete Assignment
			$res = $this->m_assignments->delete($id);

			if ( $res['status'] ) 
			{
				$status['delete_status']["status"] = 'success';
				$status['delete_status']["message"] = 'Delete Success.';
				$this->response( $status['delete_status'], REST_Controller::HTTP_OK );
			}
			else
			{
				$status['delete_status']["status"] = 'failure';
				$status['delete_status']["message"] = 'Cannot delete. Internal Error';
				$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );		
			}
		}
		else
		{
			$status['delete_status']["status"] = 'failure';
			$status['delete_status']["message"] = 'Cannot delete. This assignment is used in '.$check_result['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}

	}//#assignments_delete



//End of c_assignments Class
}
?>