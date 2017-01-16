<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_users Controller Class for Cser Manipulation
*/
class C_users extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_users');
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
		$data['active_section'] = 'system';
		$data['active_page'] 	= 'users';

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

		
		// Get Job Titles List
		//$data['job_title_list'] = $this->m_positions->get_positions()['items'];

		// Loading User Create Form
		$data['user_create_form'] = $this->load->view('pages/users/create_form', $data, true);

		// Loading User Main Section (Table Body)
		$this->load->view('pages/users/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/users/scripts', $data, true);

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
|Getting User List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function users_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'system';
		$data['active_page'] 	= 'users';

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

		// Getting User List from DB
		// 2nd Parameter Config for pagination
		// 3rd Parameter FALSE For Full Columns
		$data['user_list'] = $this->m_users->get_users( $id, false, $getConfig );
		$this->response($data['user_list']);

	}//#users_get


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
	|Insert User
	|---------------------------------------------------------------------------------
	*/
	public function users_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'system';
		$check['active_page'] 	 = 'users';

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
			$this->response($status['create_status']);
			return;
		}
		$user = [];
		// Passing Data to Validate
		$this->form_validation->set_data( $this->post() );
//$this->response(  );
		// Validate Posted Values for User Create
		if ( $this->form_validation->run('user_create')) 
		{
			$user['name'] 		 = trim($this->post('name')); //*
			$user['middle'] 	 = trim($this->post('middle'));
			$user['sname'] 		 = trim($this->post('sname'));
			$user['login'] 		 = trim($this->post('login'));//*
			$user['email'] 		 = trim($this->post('email'));//*
			$user['phone'] 		 = trim($this->post('phone'));
			$user['address'] 	 = trim($this->post('address'));
			$user['sex'] 		 = trim($this->post('sex'));//*
			//$user['position_id'] = trim($this->post('positionId'));//*
			$user['password'] 	 = $this->m_validation->hash_password( $this->post('password') );//*
			/*$user['status']		 = trim($this->post('inAppStatus'));*/
			$user['is_active']   = trim($this->post('isActive'));
			

			/*if ( $this->post('avatar') !== FALSE AND $this->post('avatar') !== "" ) 
			{
				$user['avatar'] = $this->post('avatar');
			}
			else
			{
				$user['avatar'] = $user['sex'] . "s2d6s6s6s5d9w9w6s.png";
			}*/

			//Starting Transaction
			//$this->db->trans_begin();

			//Insert New User
			$res = $this->m_users->insert($user);

			if ( $res ) 
				{
					//Newly Created User id
					$user['id'] = $res;


					// Insert Permissions
					$res_permissions = $this->m_users->insert_permissions( $user['id'] );

					if ( $res_permissions ) 
					{
						$res_permissions_status = "";
					}
					else
					{
						$res_permissions_status = " Error With Permissions"	;
					}

					$status['create_status']["status"] 	= 'success';
					$status['create_status']["message"] = "User Created." . $res_permissions_status;
					$status['create_status']["id"] 		= $user['id'];
					
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

		
	}//#users_post

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
	|Update User Info
	|---------------------------------------------------------------------------------
	*/
	public function users_put($id='0')
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'system';
		$check['active_page'] 	 = 'users';

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
			$this->response($status['update_status']);
			return;
		}

		$user = array();
		
		
		//Passing Data to Validate
		$this->form_validation->set_data( $this->put() );

		$dbUserInfo = $this->m_users->get_users($id)['items'][0];

		if ( $dbUserInfo ) 
		{
			// User Name Change Check
			if ( $dbUserInfo['name'] !== trim($this->put('name')) && strcasecmp($dbUserInfo['name'], trim($this->put('name')))==0 ) 
			{
				$user['name'] = trim($this->put('name')); //*				
			}
			elseif ( $dbUserInfo['name'] !== trim($this->put('name')) ) 
			{
				$user['name'] = trim($this->put('name')); //*
				$this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[50]');
			}
			
			// User sName Change Check
			if ( $dbUserInfo['sname'] !== trim($this->put('sname')) && strcasecmp($dbUserInfo['sname'], trim($this->put('sname')))==0 ) 
			{
				$user['sname'] = trim($this->put('sname')); //*				
			}
			elseif ( $dbUserInfo['sname'] !== trim($this->put('sname')) ) 
			{
				$user['sname'] = trim($this->put('sname')); //*
				$this->form_validation->set_rules('sname', 'Surname', 'trim|required|max_length[50]');
			}
			
			// User Login Change Check
			if ( $dbUserInfo['login'] !== trim($this->put('login')) && strcasecmp($dbUserInfo['login'], trim($this->put('login')))==0 ) 
			{
				$user['login'] = trim($this->put('login')); //*				
			}
			elseif ( $dbUserInfo['login'] !== trim($this->put('login')) ) 
			{
				$user['login'] = trim($this->put('login')); //*
				$this->form_validation->set_rules('login', 'Login', 'trim|alpha_dash|required|max_length[50]|is_unique[app_users.login]');
			}

			// User E-Mail Change Check
			if ( $dbUserInfo['email'] !== trim($this->put('email')) && strcasecmp($dbUserInfo['email'], trim($this->put('email')))==0 ) 
			{
				$user['email'] = trim($this->put('email')); //*				
			}
			elseif ( $dbUserInfo['email'] !== trim($this->put('email')) ) 
			{
				$user['email'] = trim($this->put('email')); //*
				$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required|is_unique[app_users.email]');
			}
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Invalid User. Refresh Page and Try Again';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
		}

		$user['middle'] 	 = trim($this->put('middle'));
		$this->form_validation->set_rules('middle', 'Initials', 'trim|max_length[50]');
		$user['sname'] 		 = trim($this->put('sname'));
		$this->form_validation->set_rules('sname', 'Surname', 'trim|max_length[50]');
		$user['phone'] 		 = trim($this->put('phone'));
		$this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[100]');
		$user['address'] 	 = trim($this->put('address'));
		$this->form_validation->set_rules('address', 'Address', 'trim|max_length[255]');
		$user['sex'] 		 = trim($this->put('sex'));//*
		$this->form_validation->set_rules('sex', 'Sex', 'trim|required|in_list[m,f]');
				
		//$user['position_id'] = trim($this->put('positionId'));//*
		//$this->form_validation->set_rules('positionId', 'Job Title', 'trim|required');	
		
		if ( $this->put('password') !== "" ) 
		{
			$user['password'] = $this->m_validation->hash_password( trim($this->put('password')) );//*
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');			
			$this->form_validation->set_rules('passwordConfirm', 'Confirm Password', 'trim|required|matches[password]');	
		}

		/*if ( $this->put('avatar') !== "" ) 
		{
			$user['avatar'] = trim($this->put('avatar'));
		}*/

		/*$user['status']		 = trim($this->put('inAppStatus'));
		$this->form_validation->set_rules('inAppStatus', 'Password', 'trim|numeric');*/
		
		$user['is_active']   = trim($this->put('isActive'));
		$this->form_validation->set_rules('isActive', 'Login Allowed', 'trim|numeric');

		
		// Validating PUT Values
		if ( $this->form_validation->run() !== FALSE ) 
		{
			$user['id'] = $id;
				
			// Updating User 
			$res = $this->m_users->update( $user );
				
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
	|Destroying User
	|---------------------------------------------------------------------------------
	*/
	public function users_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'system';
		$check['active_page']	 = 'users';

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
			$this->response($status['delete_status']);
			return;
		}

		// Check Boss/Department Head/...
		// Check Timesheets
		// Check Projects
		// Check All That Uses That User
		// Then Suggest To Disable User
		// After That Delete User

		$res = $this->m_users->delete($id);
		if ( $res['status'] ) 
		{
			$status['delete_status']["status"] = 'success';
			$status['delete_status']["message"] = 'Delete Success.';
			$this->response( $status['delete_status'], REST_Controller::HTTP_OK );
		}
		else
		{
			$status['delete_status']["status"] = 'failure';
			$status['delete_status']["message"] = 'Cannot delete. User Exists in some tables '.$res['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}
			

	}//#users_delete


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

public function profile_get($id,$test=0)
{
	//+ get logged in user
	//+ check for self editting
	//+ check for other users profile view permission
	//+ get requested user from db
	// get section for requesed user

	/*
	|---------------------------------------------------------------------------------
	| //Permission check Section
	|---------------------------------------------------------------------------------
	*/
	$data['active_section'] = 'system';
	$data['active_page'] 	= 'users';

	// Checking User Session Activity
	$data['userinfo'] = $this->m_validation->check_user_loggedin();

	// Checking for Requerted Page Permissions
	$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );
	
	// Value For Loggedin Users Own Profile Edit
	$data['self_edit'] = false;
	
	// Self Edit Check
	if ( $data['userinfo']['id'] == $id ) 
	{
		$data['self_edit'] = true;
		
	}
	else
	{
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

	// Get User Profile
	$data['user_profile'] = $this->m_users->get_users($id)['items'][0];
	
	// Get User Sections
	$data['user_sections'] = $this->m_config->get_sections($id);

	//Get User Settings
	$data['user_configs'] = $this->m_users->get_user_settings($id);

	// Get Job Titles List
	//$data['job_title_list'] = $this->m_positions->get_positions()['items'];

	// Load Profile View And Pass Data
	$this->load->view('pages/users/profile', $data);


	// Loading Scripts ( Modals/Buttons...)
	$data['scripts'] = $this->load->view('pages/users/scripts', '', true);

	// Loading Footer File
	$this->load->view('templates/footer', $data);
	
	return;
}//#profile


/*
|---------------------------------------------------------------------------------
| Edit Permissions
|---------------------------------------------------------------------------------
*/
	public function edit_permissions_put($id=0)
	{
		/*|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'system';
		$data['active_page'] 	= 'users';

		// Checking User Session Activity
		$userinfo = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );
		
		// Value For Loggedin Users Own Profile View
		$self_edit = false;
		
		// Self Edit Check
		if ( $userinfo['id'] == $id ) 
		{
			$data['self_edit'] = true;
			
		}
		else
		{
			############################################
			##****** UPDATE PERMISSION CHECK *********##
			############################################	
			if ( $allow['update'] === FALSE )
			{
				$data['update_status']["status"] = '400';
				$data['update_status']["message"] = 'You Don`t Have Permissions to Do This Operation!!!';
				$this->response($data['update_status'], REST_Controller::HTTP_BAD_REQUEST );
				return;
			}
		}

		//$this->response($this->put());
		
		if ( $this->put() !== FALSE AND !empty($this->put()) ) 
		{
			$permissions = $this->put();
			
			$upd_perm_vals = [];

			$perms_validation = true;
			
			foreach ( $permissions as $section) 
			{
				$perm = array(
								'c' => $section['c'],
								'd' => $section['d'],
								'r' => $section['r'],
								'u' => $section['u'],
								'user_id' => $section['user_id'],
								'section_id' => $section['section_id'],
								//$upd_perm_vals['section_seq'] = $section['section_seq'];
								'subsection_id' => $section['subsection_id']
								//$upd_perm_vals['subsection_seq'] = $section['subsection_seq'];
							);

				$this->form_validation->set_data($perm);

				if ( $this->form_validation->run('perms_update') !== false ) 
				{
					$upd_perm_vals[] = $perm;
				}
				else
				{
					$perms_validation = false;
					$status['update_status']["status"] = 'failure';
					$status['update_status']["message"] = validation_errors();
					// Postback
					$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
					break;
				}
				$this->form_validation->reset_validation();
			}

			// Update Permissions
			// For Specified User Sections
			$res_upd_perms = $this->m_users->update_permissions($upd_perm_vals);

			if ($res_upd_perms) 
			{
				$data['update_status']["status"] = 'success';
				$data['update_status']["message"] = 'Permissions Update Success';
				$this->response($data['update_status'], REST_Controller::HTTP_OK );
			}
			else
			{
				$data['update_status']["status"] = 'failure';
				$data['update_status']["message"] = 'Permissions Update Failed. Refresh Page And Try Again';
				$this->response($data['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Bad Data';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
		}



			return;
		}
	

	/*
	|---------------------------------------------------------------------------------
	| Update Settings
	|---------------------------------------------------------------------------------
	*/
	public function update_settings_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'system';
		$data['active_page'] 	= 'users';

		// Checking User Session Activity
		$userinfo = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );
		
		############################################
		##****** UPDATE PERMISSION CHECK *********##
		############################################	
		if ( $allow['update'] === FALSE )
		{
			$data['update_status']["status"] = '400';
			$data['update_status']["message"] = 'You Don`t Have Permissions to Do This Operation!!!';
			$this->response($data['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}
		


		if ( $this->post('user_id') !== null AND $this->post('key') !== null AND $this->post('value') !== null ) 
		{

			$user_id = trim($this->post('user_id'));
			$key 	 = trim($this->post('key'));
			$value 	 = trim($this->post('value'));

			// For Specified User Sections
			$res_upd_settings = $this->m_users->update_user_settings($user_id, array('key'=>$key,'value'=>$value));
			
			//$this->response($res_upd_settings);
			//return;
			
			if ($res_upd_settings) 
			{
				$data['update_status']["status"]  = 'success';
				$data['update_status']["message"] = 'Config Update Success';
				$this->response($data['update_status'], REST_Controller::HTTP_OK );
			}
			else
			{
				$data['update_status']["status"] = 'failure';
				$data['update_status']["message"] = 'Config Update Failed. Refresh Page And Try Again';
				$this->response($data['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Bad Data';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
		}



			return;
	}




	
	/*
	|---------------------------------------------------------------------------------
	|Validationg User Login Info
	|---------------------------------------------------------------------------------
	*/
	
	function validate_login_post()
    {
     //This method will have the credentials validation
     $this->load->library('form_validation');
   
     if($this->form_validation->run('user_login') == FALSE)
     {
       //On Validation Error Redirect to Login Page
       $this->load->view('pages/login');
     }
     else
     {
        $userinfo = $this->session->userdata('logged_in');
      
        if( $userinfo['is_active'] === '0' )
        {
			$data['not_active'] = "* " . $userinfo['login'] . " * user is not active. Please Contact your administrator. ";
			$this->session->unset_userdata('logged_in');
			$this->load->view('pages/login', $data );
        }
        else
        {
			
			// Generating Access Control List(ACL) For Logged in User
			$acl = $this->m_validation->generate_acl( $userinfo['id'] );

			//Setting ACL Into Session (Session['acl'])
			$this->session->set_userdata('acl', $acl);
			
			// Generate Sidebar
			$sidebar = $this->m_validation->generate_sidebar( $userinfo['id'] );
			
			//Setting Sidebar Into Session (Session['sidebar'])
			$this->session->set_userdata('sidebar', $sidebar);

			//Go To User Private Area
			redirect(site_url('backend/dashboard'));
        }
     }
   }//#validate_login_post
 /*
 |---------------------------------------------------------------------------------
 |Checking User Pass in Database And Going Forward
 |---------------------------------------------------------------------------------
 */
   public function check_and_go( $password )
   {
     // Getting Login From POST
     $login = $this->input->post('login');
     
     // Loading Date Helper
     $this->load->helper('date');

     // Loading Logger Class 
     $this->load->model('m_logger');

     // Checking Typed Login Password
     $check_result = $this->m_users->check_login($login, $password);

     if( $check_result )
     {
        date_default_timezone_set("Europe/Moscow");

        $check_result['last_login_time'] = date('Y-m-d H:i:s');

        //Setting Logged in User Info Into Session
        $this->session->unset_userdata('logged_in');
        $this->session->set_userdata('logged_in', $check_result);

        //Log section
        $data_txt = 'Login Success for User ==>'. $login;
        $this->m_logger->loggish($data_txt, 'info');
     }
     else
     {
        //log section
        $data_txt = 'Invalid Login or Password.';
        $this->m_logger->loggish($data_txt, 'info');
        
        //Setting Validation Message
        $this->form_validation->set_message('check_and_go', $data_txt);
        return false;
     }
   }


   public function change_password_post()
   {
   		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$status['page']['active_section'] = 'system';
		$status['page']['active_page'] 	= 'users';

		// Checking User Session Activity
		$userinfo = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $status['page']['active_section'], $status['page']['active_page'] );
		
		############################################
		##****** UPDATE PERMISSION CHECK *********##
		############################################	
		if ( $allow['update'] === FALSE )
		{
			$status['update_status']["status"] = '400';
			$status['update_status']["message"] = 'You Don`t Have Permissions to Do This Operation!!!';
			$this->response($status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}
		$this->form_validation->set_data($this->post());

		if ($this->form_validation->run('user_change_password') !==false) {
			$res = $this->m_users->change_password( $userinfo['id'], $this->post('passEdit') );
			if ($res) {
				$status['update']["status"] = 'success';
				$status['update']["message"] = 'Password Change Success.';
				$this->response($status['update'], REST_Controller::HTTP_OK );
			} else {
				$status['update']["status"] = 'failure';
				$status['update']["message"] = 'Nothing Has Change.';
				$this->response($status['update'], REST_Controller::HTTP_BAD_REQUEST );
			}
		} else {
			$status['update']["status"]  = 'failure';
			$status['update']["message"] = validation_errors();
			// Postback
			$this->response( $status['update'], REST_Controller::HTTP_BAD_REQUEST );
		}
   }

/*
	|---------------------------------------------------------------------------------
	| Check For Value Change When Editing
	| @param - is a {key => login, value=>loloz} pair. e.g.
	| 
	|---------------------------------------------------------------------------------
	*/
	public function is_same( $id, $param )
	{
		//Select and Get Statement
		$res = $this->db->select('')
				->from('users')
				->where( array( 'id' => $id, 
					   $param['key'] => $param['value']))
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

	

//End of c_users Class
}
 ?>