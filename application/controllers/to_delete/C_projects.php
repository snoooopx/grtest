<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_projects Controller Class for Project Manipulation
*/
class C_projects extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_projects');
		$this->load->model('m_assignments');
		$this->load->model('m_operations');
		$this->load->model('m_users');
		$this->load->model('m_clients');
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
		$data['active_section'] = 'actions';
		$data['active_page'] 	= 'projects';

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

		
		
		// Get User List
		$data['user_list'] = $this->m_users->get_users()['items'];

		// Get Assignments List
		$data['assignment_list'] = $this->m_assignments->get_assignments(0,true);

		// Get Client List
		$data['client_list'] = $this->m_clients->get_clients(0,true);

		// Loading Project Create Form
		$data['project_create_form'] = $this->load->view('pages/projects/create_form', $data, true);

		// Loading Project Main Section 
		$this->load->view('pages/projects/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/projects/scripts', $data, true);

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
|Getting Project List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function projects_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'actions';
		$data['active_page'] 	= 'projects';

		// Checking Project Session Activity
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
		|  Getting Project List from DB
		|  3rd Parameter Config for pagination
		|  2nd Parameter brief(FALSE) For Full Columns
		*/
		$data['project_list'] = $this->m_projects->get_projects($id, false, $getConfig);
		$this->response($data['project_list']);

	}//#projects_get


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
	|Insert Project
	|---------------------------------------------------------------------------------
	*/
	public function projects_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'actions';
		$check['active_page'] 	 = 'projects';

		// Checking Project Session Activity
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
		$project = [];
		// Passing Data to Validate
		$this->form_validation->set_data( $this->post() );
		//$this->response( $this->post() );
		// Validate Posted Values for Project Create
		if ( $this->form_validation->run('project_create')) 
		{
			date_default_timezone_set("Asia/Yerevan");
 			$project['creation_date']	  = date('Y/m/d H:i:s');
			$project['name']			  = trim($this->post('name')); //*
			$project['code']			  = trim($this->post('code')); //*
			$project['ass_id'] 	 	 	  = trim($this->post('ass_id')); //*
			$project['client_id']		  = trim($this->post('client_id')); //*
			$project['start_date']		  = trim($this->post('agrSD')); //*
			$project['end_date']		  = trim($this->post('agrED'));//*
			$project['actual_start_date'] = trim($this->post('actSD'));
			$project['actual_end_date']	  = trim($this->post('actED'));
			$project['manager_id']		  = trim($this->post('manager_id'));//*
			$project['note']		  	  = trim($this->post('note'));//*
			$project['is_visible']		  = trim($this->post('is_visible'));
			$project['status_id'] 		  = trim($this->post('project_status_id'));
			$project['apt_status_id'] 	  = 1;
 
			$teamIds		  	 		  = $this->post('teamIds');//*
			


			//Insert New Project
			$res = $this->m_projects->insert($project);

			if ( $res ) 
				{
					
					$res_proj_team_status = "";
					
					//Newly Created Project id
					$project['id'] = $res;
					
					// Check And Insert Project Team
					if ( !empty($teamIds) ) 
					{
						$team_array = [];
						foreach ( $teamIds as $key=>$user )
						{
							$team_array[$key]['project_id']  = $project['id'];
							$team_array[$key]['user_id'] 	 = $user;
						}
						// Insert Projects Team Members
						$res_proj_team = $this->m_projects->insert_project_team( $team_array );
						
						if ( !$res_proj_team ) 
						{
							$res_proj_team_status = "<p>Error With Team members</p>"	;
						}
					}

					$status['create_status']["status"] 	= 'success';
					$status['create_status']["message"] = "Project Created." . $res_proj_team_status;
					$status['create_status']["id"] 		= $project['id'];
					
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

		
	}//#projects_post

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
	|Update Project Info
	|---------------------------------------------------------------------------------
	*/
	public function projects_put($id='0')
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'actions';
		$check['active_page'] 	 = 'projects';

		// Checking Project Session Activity
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

		$project = array();
		
		
		//Passing Data to Validate
		$this->form_validation->set_data( $this->put() );

		/*
		|---------------------------------------------------------------------------------
		| Get Project From DB and Compare With Updatable One
		|---------------------------------------------------------------------------------
		*/
		$dbProjectInfo = $this->m_projects->get_projects($id)['items'][0];

		if ( $dbProjectInfo ) 
		{
			// Check For Same Name Change (upper/lower)
			if ( $dbProjectInfo['name'] != trim($this->put('name')) && strcasecmp($dbProjectInfo['name'], trim($this->put('name')))==0 ) 
			{
				$project['name'] = $this->put('name'); //*
			}
			elseif ( $dbProjectInfo['name'] !== trim($this->put('name')) ) 
			{
				$project['name'] = $this->put('name'); //*
				$this->form_validation->set_rules('name', 'Name', 'trim|required|alpha_numeric_spaces|is_unique[projects.name]');
			}

			// Check For Same Code Change (upper/lower)
			if ( $dbProjectInfo['code'] != trim($this->put('code')) && strcasecmp($dbProjectInfo['code'], trim($this->put('code')))==0 ) 
			{
				$project['code'] = $this->put('code'); //*
			}
			elseif ( $dbProjectInfo['code'] !== trim($this->put('code')) ) 
			{
				$project['code'] = $this->put('code'); //*
				$this->form_validation->set_rules('code', 'Code', 'trim|required|alpha_dash|max_length[15]|is_unique[projects.code]');
			}
			

			$is_team_changed = false;

			// Make Team Ids String an Array
			$db_team_arr = explode(',' , $dbProjectInfo['team_ids_str']);
			
			// Project Team Change Check
			if ( $db_team_arr != $this->put('teamIds') ) 
			{
				$team_ids = $this->put('teamIds'); //*
				$this->form_validation->set_rules('teamIds[]', 'Team', 'trim|numeric');
			}
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Invalid Project. Refresh Page and Try Again';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}

		$project['start_date']			 =  trim($this->put('agrSD')); 			
		$project['end_date']			 =  trim($this->put('agrED')); 			
		$project['actual_start_date']	 =  trim($this->put('actSD')); 			
		$project['actual_end_date']		 =  trim($this->put('actED')); 			
		$project['note']				 =  trim($this->put('note')); 			
		$project['is_visible']			 =  trim($this->put('is_visible')); 	
		$project['ass_id']				 =  trim($this->put('ass_id')); 	 		
		$project['client_id']			 =  trim($this->put('client_id')); 		
		$project['manager_id']			 =  trim($this->put('manager_id')); 		
		$project['status_id'] 	 		 =  trim($this->put('project_status_id'));
		$project['apt_status_id']	 	 =  trim($this->put('apt_status_id')); 	

		// Setting Validation Rules For Update Operation
		$this->form_validation->set_rules('agrSD', 'Agreement Start Date', 'trim|required|callback_check_date',
											array('check_date'=> 'The %s is Wrong. It Must have "YYYY-MM-DD" format'));
		$this->form_validation->set_rules('agrED', 'Agreement End Date', 'trim|required|callback_check_date',
											array('check_date'=> 'The %s is Wrong. It Must have "YYYY-MM-DD" format'));
		$this->form_validation->set_rules('actSD', 'Actual Start Date', 'trim|required|callback_check_date',
											array('check_date'=> 'The %s is Wrong. It Must have "YYYY-MM-DD" format'));
		$this->form_validation->set_rules('actED', 'Actual End Date', 'trim|required|callback_check_date',
											array('check_date'=> 'The %s is Wrong. It Must have "YYYY-MM-DD" format'));
					
		$this->form_validation->set_rules('ass_id', 'Assignment', 'trim|numeric|required');
		$this->form_validation->set_rules('client_id', 'Client', 'trim|numeric|required');
		$this->form_validation->set_rules('manager_id', 'Project Manager', 'trim|numeric|required');
		$this->form_validation->set_rules('project_status_id', 'Project Status', 'trim|numeric|required');
		$this->form_validation->set_rules('apt_status_id', 'APT Status', 'trim|numeric|required');
		$this->form_validation->set_rules('note', 'Note', 'trim|max_length[255]');
		$this->form_validation->set_rules('is_visible', 'Visibility', 'trim|numeric|in_list[0,1]');	
		
		// Validating PUT Values
		if ( $this->form_validation->run() !== FALSE ) 
		{
			$project['id'] = $id;
			
			/*
			|---------------------------------------------------------------------------------
			| Check Project Team Select Options Change
			|---------------------------------------------------------------------------------
			*/
			if ( isset( $team_ids ) ) 
			{
				// Generating Project Departments Insert Array
				$project_team_arr = [];
				
				foreach ( $team_ids as $key=>$value ) 
				{
					$project_team_arr[$key]['project_id'] = $project['id'];
					$project_team_arr[$key]['user_id']    = $value;
				}
				
				
				// Delete Project Team
				// First value $dep_id=false, Second val $project_id=false
				$res_del_team = $this->m_projects->delete_project_team( $project['id'] );
				/*var_dump($res);
				die;*/
				if ( $res_del_team || $res_del_team == 0 ) 
				{
					$is_team_changed = true;
					
					if ( !empty($project_team_arr) ) 
					{
						/*print_r($project_team_arr);
						die;*/
						$res_ins_team = $this->m_projects->insert_project_team( $project_team_arr );
					
						if ( $res_ins_team ) 
						{
							$is_team_changed = true;
						}
						else
						{
							$status['update_status']["status"] = 'failure';
							$status['update_status']["message"] = "Error Changing Project Departments (B)";
							//Postback
							$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
						}
					}
				}
				else
				{
					$status['update_status']["status"] = 'failure';
					$status['update_status']["message"] = "Error Changing Project Departments (A)";
					//Postback
					$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
				}
			}


			/*
			|---------------------------------------------------------------------------------
			| Update Project
			|---------------------------------------------------------------------------------
			*/ 
			$res = $this->m_projects->update( $project );
			

			/*
			// Check Results
			*/
			if ( $res || $is_team_changed )
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
	}//#project_put

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
	|Destroying Project
	|---------------------------------------------------------------------------------
	*/
	public function projects_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'actions';
		$check['active_page']	 = 'projects';

		// Checking Project Session Activity
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
		$check_result = $this->m_projects->check_before_delete($id);

		// Check Delete and Response
		if ( $check_result['status'] ) 
		{
			// Delete Project from project_team
			$res_del_team = $this->m_projects->delete_project_team( $id );

			//Delete Project
			$res = $this->m_projects->delete($id);

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
			$status['delete_status']["message"] = 'Cannot delete. This Project is used in '.$check_result['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}

	}//#projects_delete


/*──────────────────────────────────────────────────────────────────────────────────────────────────────────
─██████████████─████████████████───██████████████─██████████████─██████████─██████─────────██████████████─
─██░░░░░░░░░░██─██░░░░░░░░░░░░██───██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░██─██░░██─────────██░░░░░░░░░░██─
─██░░██████░░██─██░░████████░░██───██░░██████░░██─██░░██████████─████░░████─██░░██─────────██░░██████████─
─██░░██──██░░██─██░░██────██░░██───██░░██──██░░██─██░░██───────────██░░██───██░░██─────────██░░██─────────
─██░░██████░░██─██░░████████░░██───██░░██──██░░██─██░░██████████───██░░██───██░░██─────────██░░██████████─
─██░░░░░░░░░░██─██░░░░░░░░░░░░██───██░░██──██░░██─██░░░░░░░░░░██───██░░██───██░░██─────────██░░░░░░░░░░██─
─██░░██████████─██░░██████░░████───██░░██──██░░██─██░░██████████───██░░██───██░░██─────────██░░██████████─
─██░░██─────────██░░██──██░░██─────██░░██──██░░██─██░░██───────────██░░██───██░░██─────────██░░██─────────
─██░░██─────────██░░██──██░░██████─██░░██████░░██─██░░██─────────████░░████─██░░██████████─██░░██████████─
─██░░██─────────██░░██──██░░░░░░██─██░░░░░░░░░░██─██░░██─────────██░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
─██████─────────██████──██████████─██████████████─██████─────────██████████─██████████████─██████████████─
──────────────────────────────────────────────────────────────────────────────────────────────────────────
*/

public function details_get($id,$test=0)
{
	/*
	|---------------------------------------------------------------------------------
	| //Permission check Section
	|---------------------------------------------------------------------------------
	*/
	$data['active_section'] = 'actions';
	$data['active_page'] 	= 'projects';

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

	// Get Project ass and operations
	$data['project_operations'] = $this->m_projects->get_project_operations($id);

	// get project team
	$data['project_team'] = $this->m_projects->get_project_team($id);
	
	// Get Project Details
	$proj_temp = $this->m_projects->get_projects($id)['items'];

	if ($proj_temp) 
	{
		$data['project_details'] = $proj_temp[0];
	}
	else
	{
		// Loading Header File

		//Loading Error Content
		$this->load->view('errors/error_550', $data);
		// Loading Footer File
		$this->load->view('templates/footer', $data);
		return;
		//exit;
	}

	// Load Profile View And Pass Data
	$this->load->view('pages/projects/profile', $data);

	// Loading Footer File
	$this->load->view('templates/footer', $data);
	
	return;
}//# project profile


// Planning Actions
public function planning_actions_post()
{
	/*
	|---------------------------------------------------------------------------------
	| //Permission check Section
	|---------------------------------------------------------------------------------
	*/
	$data['active_section'] = 'actions';
	$data['active_page'] 	= 'projects';

	// Checking User Session Activity
	$data['userinfo'] = $this->m_validation->check_user_loggedin();

	// Checking for Requerted Page Permissions
	$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );
	
	############################################
	###****** UPDATE PERMISSION CHECK *********###
	############################################	
	if ( $allow['update'] === FALSE )
	{
		$data['update_status']["status"] = '550';
		$data['update_status']["message"] = 'You Don`t Have Permissions to View This Page!!!';
		$this->response($data['update_status'],550);
		return;
	}


	// 

	#
	#
	#
	#
	#
	#
	//
}


public function check_date($date)
{
	$year = (int)substr($date, 0, 4);
	$month = (int)substr($date, 5, 2);
	$day = (int)substr($date, 8, 2);
	
	return checkdate( $month, $day, $year );
}







//End of c_projects Class
}
?>