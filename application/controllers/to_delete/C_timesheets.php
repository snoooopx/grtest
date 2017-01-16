<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';

/**
* c_timesheets Class For User Timesheet Manipulations
*/
class C_timesheets extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_timesheets');
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
		$data['active_page'] 	= 'timesheets';

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

		/*$time_start = microtime(true);
		// Check Users missed timesheets
		$data['missed_tss'] = $this->check_not_created_ts($data['userinfo']['id']);
		$data['pending_tss'] = $this->check_pending_user_ts($data['userinfo']['id']);
		$time_end= microtime(true);
		$data['exec_time'] = $time_end - $time_start;*/

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


		//load Users Model
		$this->load->model('m_users');

		// Load user list
		if ( $data['userinfo']['ceo']==1 || $data['userinfo']['is_admin'] == 1) 
		{
			// select all
			$criterion['type'] = 3;
			// Get User List
			$data['user_list'] = $this->m_users->get_user_list($criterion);
		} 
		else if ( $data['userinfo']['head_of_dep'] == 1 )
		{
			// Select Department all
			$criterion['type'] = 2;
			$criterion['dep_id']  = $data['userinfo']['dep_id'];
			//print_r($criterion);
			// Get User List
			$data['user_list'] = $this->m_users->get_user_list($criterion);
		}
		else
		{
			// Select logged in user
			$data['user_list'][0]['id'] = $data['userinfo']['id'] ;
			$data['user_list'][0]['name'] = $data['userinfo']['name'] ;
			$data['user_list'][0]['middle'] = $data['userinfo']['middle'] ;
			$data['user_list'][0]['sname'] = $data['userinfo']['sname'] ;
		}
	/*	echo "<pre>";
		print_r($data['user_list']);
		echo "</pre>";

		echo "<pre>";
		print_r($data['userinfo']);
		echo "</pre>";
		die;*/

		// Loading Timesheet Create Form
		//$data['ts_create_form'] = $this->load->view('pages/timesheets/create_ts', $data, true);

		// Loading Timesheet Main Section 
		$this->load->view('pages/timesheets/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/timesheets/scripts', $data, true);

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
|Getting Timesheet List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function timesheets_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'actions';
		$data['active_page'] 	= 'timesheets';

		// Checking Timesheet Session Activity
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

		if ( $this->get('year') !== false) {
			$getConfig['year'] = $this->get('year');
		}
		if ( $this->get('user') !== false) {
			$getConfig['user'] = $this->get('user');
		}
		if ( $this->get('week') !== false) {
			$getConfig['week'] = $this->get('week');
		}
		if ( $this->get('status_id') !== false) {
			$getConfig['status_id'] = $this->get('status_id');
		}

		/*
		|  Getting Timesheet List from DB
		|  @param criterion = getConfig for full criterion list
		*/
		$data['ts_list'] = $this->m_timesheets->get_ts($getConfig);
		$this->response($data['ts_list']);

	}//#timesheets_get


	/*───────────────────────────────────────────     ────────────────────────────────────
	─██████████████─██████████████─██████████████     ─██████████████─████████──████████─ 
	─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██     ─██░░░░░░░░░░██─██░░░░██──██░░░░██─ 
	─██░░██████████─██░░██████████─██████░░██████     ─██░░██████░░██─████░░██──██░░████─ 
	─██░░██─────────██░░██─────────────██░░██────     ─██░░██──██░░██───██░░░░██░░░░██─── 
	─██░░██─────────██░░██████████─────██░░██────     ─██░░██████░░██───████░░░░░░████─── 
	─██░░██──██████─██░░░░░░░░░░██─────██░░██────     ─██░░░░░░░░░░██─────██░░░░░░██───── 
	─██░░██──██░░██─██░░██████████─────██░░██────     ─██░░██████░░██───████░░░░░░████─── 
	─██░░██──██░░██─██░░██─────────────██░░██────     ─██░░██──██░░██───██░░░░██░░░░██─── 
	─██░░██████░░██─██░░██████████─────██░░██────     ─██░░██──██░░██─████░░██──██░░████─ 
	─██░░░░░░░░░░██─██░░░░░░░░░░██─────██░░██────     ─██░░██──██░░██─██░░░░██──██░░░░██─ 
	─██████████████─██████████████─────██████────     ─██████──██████─████████──████████─ 
	─────────────────────────────────────────────     ─────────────────────────────────── 
	─────────────────────────────────────────────────────────────
	─██████████████─██████──██████─██████─────────██████─────────
	─██░░░░░░░░░░██─██░░██──██░░██─██░░██─────────██░░██─────────
	─██░░██████████─██░░██──██░░██─██░░██─────────██░░██─────────
	─██░░██─────────██░░██──██░░██─██░░██─────────██░░██─────────
	─██░░██████████─██░░██──██░░██─██░░██─────────██░░██─────────
	─██░░░░░░░░░░██─██░░██──██░░██─██░░██─────────██░░██─────────
	─██░░██████████─██░░██──██░░██─██░░██─────────██░░██─────────
	─██░░██─────────██░░██──██░░██─██░░██─────────██░░██─────────
	─██░░██─────────██░░██████░░██─██░░██████████─██░░██████████─
	─██░░██─────────██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
	─██████─────────██████████████─██████████████─██████████████─
	─────────────────────────────────────────────────────────────*/

/*
|---------------------------------------------------------------------------------
|Getting Timesheet List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function timesheet_full_get()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'actions';
		$data['active_page'] 	= 'timesheets';

		// Checking Timesheet Session Activity
		$userinfo = $this->m_validation->check_user_loggedin();

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
		$criterions = [];
		
		$criterions['user_id'] = $userinfo['id'];

		if ( $this->get('action') !== false ) 
		{
			if (trim($this->get('action')) == 'cc' or trim($this->get('action')) == 'c') 
			{
				$criterions['for_edit'] = false;
			}
			elseif (trim($this->get('action')) == 'e') 
			{
				$criterions['for_edit'] = true;
			}
			else
			{
				$this->response( array('status' =>'failure' ,'message'=>'Invalid Action1' ));
				return;		
			}
		}
		else
		{
			$this->response( array('status' =>'failure' ,'message'=>'Invalid Action2' ));
			return;
		}




		if ( $this->get('id') !== false ) 
		{
			$criterions['ts_id'] = trim($this->get('id'));
		}
		else
		{
			$this->response( array('status' =>'failure' ,'message'=>'invalid ts id' ));
			return;
		}

		/*
		|  Getting Timesheet from DB VIA ID
		|  @param  1 for full ts select
		|  @param  criterions for condition
		*/
		$full_ts = $this->m_timesheets->get_full_ts(1,$criterions);
		$this->response($full_ts);


	}//#timesheets_get

/*
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
	──────────────────────────────────────────────
──────────────────────────────────────────────────────────────────────────────────────────────────────────────
─██████████████─██████████████─██████████████─██████████─██████████████─██████──────────██████─██████████████─
─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░██─██░░░░░░░░░░██─██░░██████████──██░░██─██░░░░░░░░░░██─
─██░░██████░░██─██░░██████████─██████░░██████─████░░████─██░░██████░░██─██░░░░░░░░░░██──██░░██─██░░██████████─
─██░░██──██░░██─██░░██─────────────██░░██───────██░░██───██░░██──██░░██─██░░██████░░██──██░░██─██░░██─────────
─██░░██████░░██─██░░██─────────────██░░██───────██░░██───██░░██──██░░██─██░░██──██░░██──██░░██─██░░██████████─
─██░░░░░░░░░░██─██░░██─────────────██░░██───────██░░██───██░░██──██░░██─██░░██──██░░██──██░░██─██░░░░░░░░░░██─
─██░░██████░░██─██░░██─────────────██░░██───────██░░██───██░░██──██░░██─██░░██──██░░██──██░░██─██████████░░██─
─██░░██──██░░██─██░░██─────────────██░░██───────██░░██───██░░██──██░░██─██░░██──██░░██████░░██─────────██░░██─
─██░░██──██░░██─██░░██████████─────██░░██─────████░░████─██░░██████░░██─██░░██──██░░░░░░░░░░██─██████████░░██─
─██░░██──██░░██─██░░░░░░░░░░██─────██░░██─────██░░░░░░██─██░░░░░░░░░░██─██░░██──██████████░░██─██░░░░░░░░░░██─
─██████──██████─██████████████─────██████─────██████████─██████████████─██████──────────██████─██████████████─
	────────────────────────────────────────────────────────────────────────────────────
	─██████████─██████──────────██████─████████████───██████████████─████████──████████─
	─██░░░░░░██─██░░██████████──██░░██─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░██──██░░░░██─
	─████░░████─██░░░░░░░░░░██──██░░██─██░░████░░░░██─██░░██████████─████░░██──██░░████─
	───██░░██───██░░██████░░██──██░░██─██░░██──██░░██─██░░██───────────██░░░░██░░░░██───
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░██████████───████░░░░░░████───
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░░░░░░░░░██─────██░░░░░░██─────
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░██████████───████░░░░░░████───
	───██░░██───██░░██──██░░██████░░██─██░░██──██░░██─██░░██───────────██░░░░██░░░░██───
	─████░░████─██░░██──██░░░░░░░░░░██─██░░████░░░░██─██░░██████████─████░░██──██░░████─
	─██░░░░░░██─██░░██──██████████░░██─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░██──██░░░░██─
	─██████████─██████──────────██████─████████████───██████████████─████████──████████─
*/
// create/ copy create/ edid and create
public function timesheet_actions_index_get()
{
	/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'actions';
		$data['active_page'] 	= 'timesheets';

		// Checking User Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requested Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** Create PERMISSION CHECK *********##
		############################################	
		if ( $allow['create'] === FALSE )
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

			/*$status['create_status']["status"] = '403';
			$status['create_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
			$this->response($status['create_status'], 403);
			return;*/
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

		// Get TS Activity Types.
		$data['activity_types'] = $this->m_timesheets->get_activity_types();
		
		// Get TS Absence Types.
		$data['absence_types'] = $this->m_timesheets->get_absence_types();

		// Get Projects ( with operations if possible, else get separately ( point 3.))
		//  + 0. All for company if User is Boss or Supervisor
		// 	1. All for department if User is Head Of Department
		//	2. In Other cases get projects in what user is involved
		//	3. Get operations for project-assignments
		
		$this->load->model('m_projects');
		$data['project_list'] = $this->m_projects->get_projects(0,true)['items'];

		// Loading Timesheet Create Main Page
		$this->load->view('pages/timesheets/main_create_ts', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/timesheets/scripts', $data, true);

		// Loading Footer File
		$this->load->view('templates/footer', $data);

}


	/*─██████████████─██████████████─██████████████─
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
	──────────────────────────────────────────────

	──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
	─██████████████─██████████████─██████──────────██████─████████████───██████████─██████──────────██████─██████████████─
	─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░██████████──██░░██─██░░░░░░░░████─██░░░░░░██─██░░██████████──██░░██─██░░░░░░░░░░██─
	─██░░██████░░██─██░░██████████─██░░░░░░░░░░██──██░░██─██░░████░░░░██─████░░████─██░░░░░░░░░░██──██░░██─██░░██████████─
	─██░░██──██░░██─██░░██─────────██░░██████░░██──██░░██─██░░██──██░░██───██░░██───██░░██████░░██──██░░██─██░░██─────────
	─██░░██████░░██─██░░██████████─██░░██──██░░██──██░░██─██░░██──██░░██───██░░██───██░░██──██░░██──██░░██─██░░██─────────
	─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░██──██░░██──██░░██─██░░██──██░░██───██░░██───██░░██──██░░██──██░░██─██░░██──██████─
	─██░░██████████─██░░██████████─██░░██──██░░██──██░░██─██░░██──██░░██───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─
	─██░░██─────────██░░██─────────██░░██──██░░██████░░██─██░░██──██░░██───██░░██───██░░██──██░░██████░░██─██░░██──██░░██─
	─██░░██─────────██░░██████████─██░░██──██░░░░░░░░░░██─██░░████░░░░██─████░░████─██░░██──██░░░░░░░░░░██─██░░██████░░██─
	─██░░██─────────██░░░░░░░░░░██─██░░██──██████████░░██─██░░░░░░░░████─██░░░░░░██─██░░██──██████████░░██─██░░░░░░░░░░██─
	─██████─────────██████████████─██████──────────██████─████████████───██████████─██████──────────██████─██████████████─
	──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
		────────────────────────────────────────────────────────────────────────────────────
	─██████████─██████──────────██████─████████████───██████████████─████████──████████─
	─██░░░░░░██─██░░██████████──██░░██─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░██──██░░░░██─
	─████░░████─██░░░░░░░░░░██──██░░██─██░░████░░░░██─██░░██████████─████░░██──██░░████─
	───██░░██───██░░██████░░██──██░░██─██░░██──██░░██─██░░██───────────██░░░░██░░░░██───
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░██████████───████░░░░░░████───
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░░░░░░░░░██─────██░░░░░░██─────
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░██████████───████░░░░░░████───
	───██░░██───██░░██──██░░██████░░██─██░░██──██░░██─██░░██───────────██░░░░██░░░░██───
	─████░░████─██░░██──██░░░░░░░░░░██─██░░████░░░░██─██░░██████████─████░░██──██░░████─
	─██░░░░░░██─██░░██──██████████░░██─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░██──██░░░░██─
	─██████████─██████──────────██████─████████████───██████████████─████████──████████─*/
//Pendings
/*
|---------------------------------------------------------------------------------
| Get Pending Timesheets That Must be Accepted/Rejected
| @param $user_id - for Getting specified Users TS
|
|---------------------------------------------------------------------------------
*/
public function pending_timesheets_index_get()
{
	/*
	|---------------------------------------------------------------------------------
	| //Permission check Section
	|---------------------------------------------------------------------------------
	*/
	$data['active_section'] = 'actions';
	$data['active_page'] 	= 'timesheets';

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

	//$time_start = microtime(true);

	// Load TS Helper For Getting missed Timesheets
	$this->load->helper('ts');
	$data['notify'] = get_notifications_helper($data['userinfo']['id']);
	
	//$time_end= microtime(true);
	//$data['exec_time'] = $time_end - $time_start;

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

	// Get pending Timesheets based on logged in user perms
	// if CEO OR Admin 	-> get all
	// if Head Of Dep 	-> get Dep all and Those Where He is Approver
	// if Empl 			-> get Own and Those Where He is Approver

	
	$this->load->view('pages/timesheets/main_pendings.php',$data);

	// Loading Scripts ( Modals/Buttons...)
	$data['scripts'] = $this->load->view('pages/timesheets/scripts', $data, true);

	// Loading Footer File
	$this->load->view('templates/footer', $data);


	return;
	
}

/*──────────────────────────────────────────────────────────────────────────────────────────────────────
─████████████───██████████████─██████████████─██████████████─██████████─██████─────────██████████████─
─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░██─██░░██─────────██░░░░░░░░░░██─
─██░░████░░░░██─██░░██████████─██████░░██████─██░░██████░░██─████░░████─██░░██─────────██░░██████████─
─██░░██──██░░██─██░░██─────────────██░░██─────██░░██──██░░██───██░░██───██░░██─────────██░░██─────────
─██░░██──██░░██─██░░██████████─────██░░██─────██░░██████░░██───██░░██───██░░██─────────██░░██████████─
─██░░██──██░░██─██░░░░░░░░░░██─────██░░██─────██░░░░░░░░░░██───██░░██───██░░██─────────██░░░░░░░░░░██─
─██░░██──██░░██─██░░██████████─────██░░██─────██░░██████░░██───██░░██───██░░██─────────██████████░░██─
─██░░██──██░░██─██░░██─────────────██░░██─────██░░██──██░░██───██░░██───██░░██─────────────────██░░██─
─██░░████░░░░██─██░░██████████─────██░░██─────██░░██──██░░██─████░░████─██░░██████████─██████████░░██─
─██░░░░░░░░████─██░░░░░░░░░░██─────██░░██─────██░░██──██░░██─██░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
─████████████───██████████████─────██████─────██████──██████─██████████─██████████████─██████████████─
		────────────────────────────────────────────────────────────────────────────────────
	─██████████─██████──────────██████─████████████───██████████████─████████──████████─
	─██░░░░░░██─██░░██████████──██░░██─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░██──██░░░░██─
	─████░░████─██░░░░░░░░░░██──██░░██─██░░████░░░░██─██░░██████████─████░░██──██░░████─
	───██░░██───██░░██████░░██──██░░██─██░░██──██░░██─██░░██───────────██░░░░██░░░░██───
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░██████████───████░░░░░░████───
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░░░░░░░░░██─────██░░░░░░██─────
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░██████████───████░░░░░░████───
	───██░░██───██░░██──██░░██████░░██─██░░██──██░░██─██░░██───────────██░░░░██░░░░██───
	─████░░████─██░░██──██░░░░░░░░░░██─██░░████░░░░██─██░░██████████─████░░██──██░░████─
	─██░░░░░░██─██░░██──██████████░░██─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░██──██░░░░██─
	─██████████─██████──────────██████─████████████───██████████████─████████──████████─*/

public function timesheet_details_index_get($user_id=0,$ts_id=0,$from=0)
{
	/*
	|---------------------------------------------------------------------------------
	| //Permission check Section
	|---------------------------------------------------------------------------------
	*/
	$data['active_section'] = 'actions';
	$data['active_page'] 	= 'timesheets';

	// Checking User Session Activity
	$data['userinfo'] = $this->m_validation->check_user_loggedin();

	// Checking for Requerted Page Permissions
	$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

	if ( $from === "p" ) 
	{
		$data['active_page'] = 'pending_timesheets';	
	}

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

	if ( $user_id==0 and $ts_id==0 )
	{
		//Loading Error Content
		$this->load->view('errors/error_550', $data);
		// Loading Footer File
		$this->load->view('templates/footer', $data);
		//exit;
		return;
	}

	$criterions = [];
	$criterions['ts_id'] = trim($ts_id);

	// Get Timesheet
	
	if ( $data['userinfo']['ceo'] == 1 || $data['userinfo']['head_of_dep'] == 1 || $data['userinfo']['is_admin'] == 1 ) 
	{
		$criterions['user_id'] = $user_id;
	}
	else
	{
		// NO Permission to view Not OWN TS
		// Init empty array;
		$criterions['user_id'] = $data['userinfo']['id'];
	}
	
	// Get Timesheet Owner User info
	$data['ts_owner'] = $this->m_users->get_users($user_id)['items'];

	// first @param - 1 for TS Details
	$data['ts_details'] = $this->m_timesheets->get_full_ts(1, $criterions);

	$data['ts_history'] = $this->m_timesheets->get_ts_history( $criterions['ts_id'] );

	$this->load->view('pages/timesheets/details', $data);

	/*echo '<pre>';
	print_r($res_full_ts);
	echo '</pre>';*/



	// Loading Scripts ( Modals/Buttons...)
	$data['scripts'] = $this->load->view('pages/timesheets/scripts', $data, true);

	// Loading Footer File
	$this->load->view('templates/footer', $data);
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
	─██████████████─██████████████─██████████████─██████████████─
	─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
	─██░░██████░░██─██░░██████░░██─██░░██████████─██████░░██████─
	─██░░██──██░░██─██░░██──██░░██─██░░██─────────────██░░██─────
	─██░░██████░░██─██░░██──██░░██─██░░██████████─────██░░██─────
	─██░░░░░░░░░░██─██░░██──██░░██─██░░░░░░░░░░██─────██░░██─────
	─██░░██████████─██░░██──██░░██─██████████░░██─────██░░██─────
	─██░░██─────────██░░██──██░░██─────────██░░██─────██░░██─────
	─██░░██─────────██░░██████░░██─██████████░░██─────██░░██─────
	─██░░██─────────██░░░░░░░░░░██─██░░░░░░░░░░██─────██░░██─────
	─██████─────────██████████████─██████████████─────██████─────
	─────────────────────────────────────────────────────────────
*/
public function timesheet_create_post()
{
	/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'actions';
		$data['active_page'] 	= 'timesheets';

		// Checking User Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requested Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** Create PERMISSION CHECK *********##
		############################################	
		if ( $allow['create'] === FALSE )
		{
			$status['create_status']["status"] = '403';
			$status['create_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
			$this->response($status['create_status'], 403);
			return;
		}

		// CHECK POST VALUES
		if ( $this->post('info') !== false && $this->post('main') !== false && $this->post('absence') !== false ) 
		{
			$mainPost 	 = $this->post('main');
			$absencePost = $this->post('absence');

			$this->form_validation->set_data($this->post('info'));

			if ( $this->form_validation->run('ts_create_info') !== false ) 
			{
				// Check For  Two Tables Emptyness
				if ( empty($mainPost[0]) && empty($absencePost[0]) ) 
				{
					$insert_status["status"]  = 'failure';
					$insert_status["message"] = 'Timesheet is Not Filled.';
					$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
					return;
				}
				date_default_timezone_set('Asia/Yerevan');
				$info['created'] = date("Y-m-d H:i");
				// Fill Info Array for Insert
				$info['user_id'] 	= $data['userinfo']['id'];
				$info['status_id'] 	= trim($this->post('info')['saveType']);
				$info['ts_year'] 	= trim($this->post('info')['year']);
				$info['w_no'] 		= trim($this->post('info')['week']);
				$info['w_start'] 	= trim($this->post('info')['weekStart']);
				$info['w_end'] 		= trim($this->post('info')['weekEnd']);

				// Insert INFO
				$ts_id = $this->m_timesheets->insert_ts_info($info);
				
				// Check INFO And Insert main
				if ($ts_id) 
				{
					// Check and Generate Main Array for Insert
					if ( !empty($mainPost) ) 
					{
						foreach ($mainPost as $key => $item) 
						{
							$temp = [];

							// Check for billable not billable
							// --//-- 	training, self dev, admin
							if ( trim($item['tsActivityTypeCode']) == 'at1' || trim($item['tsActivityTypeCode']) == 'at2' )
							{
								$temp['project_id']		= trim($item['tsProject']);
								$temp['operation_id']	= trim($item['tsOperation']);
							}
							else
							{
								$temp['project_id']		= null;
								$temp['operation_id']	= null;	
							}

							// tstype
							// 1-Main, 2-Absence
							$temp['ts_type'] 		= 1;
							$temp['ts_id'] 			= $ts_id;
							$temp['absence_id']		= null;
							$temp['activity_id']	= trim($item['tsActivityType']);
							$temp['is_accepted']	= trim($item['tsRowNeedToAccept']);
							$temp['wd1'] 			= trim($item['tsWD1']);
							$temp['wd2'] 			= trim($item['tsWD2']);
							$temp['wd3'] 			= trim($item['tsWD3']);
							$temp['wd4'] 			= trim($item['tsWD4']);
							$temp['wd5'] 			= trim($item['tsWD5']);
							$temp['wd6'] 			= trim($item['tsWD6']);
							$temp['wd7'] 			= trim($item['tsWD7']);
							$temp['note']			= trim($item['tsComment']);

							$main[] = $temp;
						}

					}

					
					// Check and Generate Absence Array for Insert
					if ( !empty($absencePost) ) 
					{
						foreach ($absencePost as $key => $item) 
						{
							$temp=[];
							// tstype
							// 1-Main, 2-Absence
							$temp['ts_type'] 		= 2;
							$temp['ts_id'] 			= $ts_id;
							$temp['absence_id']		= $item['tsAbsenceType'];
							$temp['activity_id'] 	= null;
							$temp['project_id']	 	= null;
							$temp['operation_id'] 	= null;
							$temp['is_accepted']	= trim($item['tsRowNeedToAccept']);
							$temp['wd1'] 			= trim($item['tsWD1']);
							$temp['wd2'] 			= trim($item['tsWD2']);
							$temp['wd3'] 			= trim($item['tsWD3']);
							$temp['wd4'] 			= trim($item['tsWD4']);
							$temp['wd5'] 			= trim($item['tsWD5']);
							$temp['wd6'] 			= trim($item['tsWD6']);
							$temp['wd7'] 			= trim($item['tsWD7']);
							$temp['note']			= trim($item['tsComment']);
							$main[] = $temp;
						}

						//Insert ABSENCE
						//$res_absence = $this->m_timesheets->insert_ts_absence($absence);
					}
					//
					/*print_r($main);
					return;*/
					
					// Insert MAIN
					$res_main = $this->m_timesheets->insert_ts_main($main);

					$main_warning="";
					if (isset($res_main) && $res_main < 1) 
					{
						$main_warning = ' with Warning. Please Check Timesheet details.';
					}

					// Collecting Event info
					$event=[];
					$event['ts_id'] = $ts_id;
					$event['status_id'] = trim($this->post('info')['saveType']);
					$event['user_id'] = $this->session->userdata('logged_in')['id'];
					$event['touched_object'] = 'Timesheet';
					date_default_timezone_set('Asia/Yerevan');
					$event['action_date'] = date('Y-m-d H:i:s');

					// Ah here it is. Insering History event
					$res_hostory = $this->m_timesheets->insert_history_event($event);

					/*$absence_warning="";
					if (isset($res_absence) && $res_absence < 1) 
					{
						$absence_warning = 'Warning in Absence Section. Please Check.';
					}*/

					$insert_status['status']  = 'success';
					$insert_status['action_type']  = trim($this->post('info')['saveType']);
					$insert_status['message'] = 'Timesheet '.$info['ts_year'].'#'.$info['w_no'].' Created Successfully'.$main_warning;
					$this->response($insert_status,REST_Controller::HTTP_CREATED);
				}
				else
				{
					$insert_status["status"]  = 'failure';
					$insert_status["message"] = 'Timesheet Create Internal error.';
					$this->response($insert_status,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
				}
			}
			else
			{
				$insert_status["status"] 	= 'failure';
				$insert_status["message"] = validation_errors();
				$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
			}
		}
		else
		{
				$insert_status["status"] 	= 'failure';
				$insert_status["message"] = 'POST Error.';
				$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
		}
}

/*─────────────────────────────────────────────────────────
	─██████████████─████████████───██████████─██████████████─
	─██░░░░░░░░░░██─██░░░░░░░░████─██░░░░░░██─██░░░░░░░░░░██─
	─██░░██████████─██░░████░░░░██─████░░████─██████░░██████─
	─██░░██─────────██░░██──██░░██───██░░██───────██░░██─────
	─██░░██████████─██░░██──██░░██───██░░██───────██░░██─────
	─██░░░░░░░░░░██─██░░██──██░░██───██░░██───────██░░██─────
	─██░░██████████─██░░██──██░░██───██░░██───────██░░██─────
	─██░░██─────────██░░██──██░░██───██░░██───────██░░██─────
	─██░░██████████─██░░████░░░░██─████░░████─────██░░██─────
	─██░░░░░░░░░░██─██░░░░░░░░████─██░░░░░░██─────██░░██─────
	─██████████████─████████████───██████████─────██████─────
	─────────────────────────────────────────────────────────
	─██████████████─██████████████─██████████████─██████████████─
	─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
	─██░░██████░░██─██░░██████░░██─██░░██████████─██████░░██████─
	─██░░██──██░░██─██░░██──██░░██─██░░██─────────────██░░██─────
	─██░░██████░░██─██░░██──██░░██─██░░██████████─────██░░██─────
	─██░░░░░░░░░░██─██░░██──██░░██─██░░░░░░░░░░██─────██░░██─────
	─██░░██████████─██░░██──██░░██─██████████░░██─────██░░██─────
	─██░░██─────────██░░██──██░░██─────────██░░██─────██░░██─────
	─██░░██─────────██░░██████░░██─██████████░░██─────██░░██─────
	─██░░██─────────██░░░░░░░░░░██─██░░░░░░░░░░██─────██░░██─────
	─██████─────────██████████████─██████████████─────██████─────
	─────────────────────────────────────────────────────────────
*/
public function timesheet_edit_post()
{
	/*
	|---------------------------------------------------------------------------------
	| //Permission check Section
	|---------------------------------------------------------------------------------
	*/
	$data['active_section'] = 'actions';
	$data['active_page'] 	= 'timesheets';

	// Checking User Session Activity
	$data['userinfo'] = $this->m_validation->check_user_loggedin();

	// Checking for Requested Page Permissions
	$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

	############################################
	###****** Edit PERMISSION CHECK *********##
	############################################	
	if ( $allow['update'] === FALSE )
	{
		$status['edit_status']["status"] = '403';
		$status['edit_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
		$this->response($status['edit_status'], 403);
		return;
	}

	// CHECK POST VALUES
	if ( $this->post('info') !== false && $this->post('main') !== false && $this->post('absence') !== false ) 
	{
		$mainPost 	 = $this->post('main');
		$absencePost = $this->post('absence');

		//$this->form_validation->set_data($this->post('info'));

		// Validate TS Info
		if (isset( $this->post('info')['ts_id']) ) 
		{
			// Check For  Two Tables Emptyness
			if ( empty($mainPost[0]) && empty($absencePost[0]) ) 
			{
				$insert_status["status"]  = 'failure';
				$insert_status["message"] = 'Timesheet is Not Filled.';
				$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
				return;
			}
			date_default_timezone_set('Asia/Yerevan');
			$info['ts_id'] 			= trim($this->post('info')['ts_id']);
			$info['status_id'] 		= trim($this->post('info')['saveType']);
			$info['last_modified'] 	= date("Y-m-d H:i");

			// Check TS Status
			$check_status = $this->m_timesheets->check_ts_status($data['userinfo']['id'],false,false,$info['ts_id']);
			
			$this->db->trans_begin();

			// Checking if Requested TS exists For Logged In User
			if ( $check_status ) 
			{
				// Update INFO
				$res_update_info = $this->m_timesheets->update_ts_info($info);
			}
			else
			{
				$insert_status["status"]  = 'failure';
				$insert_status["message"] = 'Invalid Timesheet Request.';
				$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
				return;
			}
			//$this->response($res_update_info);
			// Check INFO Update And Start Updating Main
			if ($res_update_info > 0) 
			{
				// Delete Specified TS Rows From ts_Main (Main and Absence Rows)
				$this->m_timesheets->delete_ts_main($info['ts_id']);

				// Check and Generate Main Array for Insert
				if ( !empty($mainPost) ) 
				{
					// Generating Main Rows For Insert
					foreach ($mainPost as $key => $item) 
					{
						$temp = [];

						// Check for billable not billable
						// --//-- 	training, self dev, admin
						if ( trim($item['tsActivityTypeCode']) == 'at1' || trim($item['tsActivityTypeCode']) == 'at2' )
						{
							$temp['project_id']		= trim($item['tsProject']);
							$temp['operation_id']	= trim($item['tsOperation']);
						}
						else
						{
							$temp['project_id']		= null;
							$temp['operation_id']	= null;	
						}
						// tstype
						// 1-Main, 2-Absence
						$temp['ts_type'] 		= 1;
						$temp['ts_id'] 			= trim($info['ts_id']);
						$temp['absence_id']		= null;
						$temp['activity_id']	= trim($item['tsActivityType']);
						$temp['is_accepted']	= $item['tsRowNeedToAccept'];
						$temp['wd1'] 			= trim($item['tsWD1']);
						$temp['wd2'] 			= trim($item['tsWD2']);
						$temp['wd3'] 			= trim($item['tsWD3']);
						$temp['wd4'] 			= trim($item['tsWD4']);
						$temp['wd5'] 			= trim($item['tsWD5']);
						$temp['wd6'] 			= trim($item['tsWD6']);
						$temp['wd7'] 			= trim($item['tsWD7']);
						$temp['note']			= trim($item['tsComment']);

						$main[] = $temp;
					}
				}

				
				// Check and Generate Absence Array for Insert
				if ( !empty($absencePost) ) 
				{
					// Generating Absence Rows For Insert
					foreach ($absencePost as $key => $item) 
					{
						$temp=[];
						// tstype
						// 1-Main, 2-Absence
						$temp['ts_type'] 		= 2;
						$temp['ts_id'] 			= trim($info['ts_id']);
						$temp['absence_id']		= trim($item['tsAbsenceType']);
						$temp['activity_id'] 	= null;
						$temp['project_id']	 	= null;
						$temp['operation_id'] 	= null;
						$temp['is_accepted']	= $item['tsRowNeedToAccept'];
						$temp['wd1'] 			= trim($item['tsWD1']);
						$temp['wd2'] 			= trim($item['tsWD2']);
						$temp['wd3'] 			= trim($item['tsWD3']);
						$temp['wd4'] 			= trim($item['tsWD4']);
						$temp['wd5'] 			= trim($item['tsWD5']);
						$temp['wd6'] 			= trim($item['tsWD6']);
						$temp['wd7'] 			= trim($item['tsWD7']);
						$temp['note']			= trim($item['tsComment']);
						$main[] = $temp;
					}
				}
				
				// Insert MAIN (Main and Absence Rows)
				$res_main = $this->m_timesheets->insert_ts_main($main);

				$main_warning="";
				
				if (isset($res_main) && $res_main < 1) 
				{
					$main_warning = ' with Warning. Please Check Timesheet details.';
				}

				// Collecting Event info
				$event=[];
				$event['ts_id'] = $info['ts_id'];
				$event['status_id'] = trim($this->post('info')['saveType']);;
				$event['user_id'] = $this->session->userdata('logged_in')['id'];
				$event['touched_object'] = 'Timesheet';
				date_default_timezone_set('Asia/Yerevan');
				$event['action_date'] = date('Y-m-d H:i:s');

				// Ah here it is. Insering History event
				$res_hostory = $this->m_timesheets->insert_history_event($event);

				if ( $this->db->trans_status() !== false )
				{
					$this->db->trans_commit();
					//return true;
				}
				else
				{
					$this->db->trans_rollback();
					$insert_status["status"]  = 'failure';
					$insert_status["message"] = 'Timesheet Edit Internal error. Rolled Back';
					$this->response($insert_status,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
					return false;
				}
				

				$insert_status['status']  = 'success';
				$insert_status['action_type']  = trim($this->post('info')['saveType']);
				$insert_status['message'] = 'Timesheet '.$this->post('info')['year'].'#'.$this->post('info')['week'].' Edited Successfully'.$main_warning;
				$this->response($insert_status,REST_Controller::HTTP_CREATED);
			}
			else
			{
				$insert_status["status"]  = 'failure';
				$insert_status["message"] = 'Timesheet Edit Internal error.';
				$this->response($insert_status,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
		}
		else
		{
			$insert_status["status"]  = 'failure';
			$insert_status["message"] = 'Bad Request';
			$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
		}
	}
	else
	{
			$insert_status["status"] 	= 'failure';
			$insert_status["message"] = 'POST Error.';
			$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
	}
}
/*─────────────────────────────────────────────────────────
	─██████████████─████████████───██████████─██████████████─
	─██░░░░░░░░░░██─██░░░░░░░░████─██░░░░░░██─██░░░░░░░░░░██─
	─██░░██████████─██░░████░░░░██─████░░████─██████░░██████─
	─██░░██─────────██░░██──██░░██───██░░██───────██░░██─────
	─██░░██████████─██░░██──██░░██───██░░██───────██░░██─────
	─██░░░░░░░░░░██─██░░██──██░░██───██░░██───────██░░██─────
	─██░░██████████─██░░██──██░░██───██░░██───────██░░██─────
	─██░░██─────────██░░██──██░░██───██░░██───────██░░██─────
	─██░░██████████─██░░████░░░░██─████░░████─────██░░██─────
	─██░░░░░░░░░░██─██░░░░░░░░████─██░░░░░░██─────██░░██─────
	─██████████████─████████████───██████████─────██████─────
	─────────────────────────────────────────────────────────

	────────────────────────────────────────────────────────────────────────────────────
	─██████████─██████──────────██████─████████████───██████████████─████████──████████─
	─██░░░░░░██─██░░██████████──██░░██─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░██──██░░░░██─
	─████░░████─██░░░░░░░░░░██──██░░██─██░░████░░░░██─██░░██████████─████░░██──██░░████─
	───██░░██───██░░██████░░██──██░░██─██░░██──██░░██─██░░██───────────██░░░░██░░░░██───
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░██████████───████░░░░░░████───
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░░░░░░░░░██─────██░░░░░░██─────
	───██░░██───██░░██──██░░██──██░░██─██░░██──██░░██─██░░██████████───████░░░░░░████───
	───██░░██───██░░██──██░░██████░░██─██░░██──██░░██─██░░██───────────██░░░░██░░░░██───
	─████░░████─██░░██──██░░░░░░░░░░██─██░░████░░░░██─██░░██████████─████░░██──██░░████─
	─██░░░░░░██─██░░██──██████████░░██─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░██──██░░░░██─
	─██████████─██████──────────██████─████████████───██████████████─████████──████████─*/

public function timesheet_edit_index_get($user_id=0,$ts_id=0)
{
	/*
	|---------------------------------------------------------------------------------
	| //Permission check Section
	|---------------------------------------------------------------------------------
	*/
	$data['active_section'] = 'actions';
	$data['active_page'] 	= 'timesheets';

	// Checking User Session Activity
	$data['userinfo'] = $this->m_validation->check_user_loggedin();

	// Checking for Requerted Page Permissions
	$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

	############################################
	###****** UPDATE PERMISSION CHECK *********###
	############################################	
	if ( $allow['update'] === FALSE )
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

	if ( $user_id==0 and $ts_id==0 )
	{
		//Loading Error Content
		$this->load->view('errors/error_550', $data);
		// Loading Footer File
		$this->load->view('templates/footer', $data);
		//exit;
		return;
	}

	$criterions = [];
	$criterions['ts_id'] = trim($ts_id);

	// Get Timesheet
	
	if ( $data['userinfo']['ceo'] == 1 || $data['userinfo']['head_of_dep'] == 1 || $data['userinfo']['is_admin'] == 1 ) 
	{
		$criterions['user_id'] = $user_id;
	}
	else
	{
		// NO Permission to view Not OWN TS
		// Init empty array;
		$criterions['user_id'] = $data['userinfo']['id'];
	}
	
	
	// first @param - 1 for TS Details
	$data['ts_details'] = $this->m_timesheets->get_full_ts(1, $criterions);

	//$data['ts_history'] = $this->m_timesheets->get_ts_history( $criterions['ts_id'] );

	$this->load->view('pages/timesheets/main_edit_ts', $data);

	/*echo '<pre>';
	print_r($res_full_ts);
	echo '</pre>';*/

	// Loading Scripts ( Modals/Buttons...)
	$data['scripts'] = $this->load->view('pages/timesheets/scripts', $data, true);

	// Loading Footer File
	$this->load->view('templates/footer', $data);
}


public function get_pr_operations_get()
{
	

	if ( $this->get('project_id') ) 
	{
		$this->load->model('m_projects');
		
		$project_id = $this->get('project_id');
		
		$res = $this->m_projects->get_project_operations( $project_id );
		
		if ( $res ) 
		{
			$this->response($res);
		}
	}
	else
	{
		$this->response("nothing");
	}
}

public function ax_check_ts_date_get()
{
	// Checking User Session Activity
	$userinfo = $this->m_validation->check_user_loggedin();
	if ( $this->get('year')!==false && $this->get('month') !== false && $this->get('day') !== false ) 
	{
		date_default_timezone_set('Asia/Yerevan');
		$year = $this->get('year');
		$month = $this->get('month');
		$day = $this->get('day');
		$date_str = $day.'-'.$month.'-'.$year;
		$date_str = strtotime($date_str);
		if (!$date_str) 
		{
			$status['status']='failure';
			$status['message']='Invalid Date.';
			$this->response($status);
			return;
		}

		// Generate Week of Year
		$week = date('W', $date_str);

		if( $week == 1 && $month == 12 )
		{
			$year = $year + 1;
		}
		elseif ( $week > 50 && $month==1 ) 
		{
			$year = $year - 1;
		}

		// Check TS Existence and Status
		$res = $this->m_timesheets->check_ts_status($userinfo['id'], $year, $week);

		// Generating Status Array for Response
		$status = array('week_no' => $week,
							'year' => $year);

		if ( !$res ) 
		{
			$status['status']  = 'success';
			$status['message'] = '#'.$year.' W#'.$week.' Not created yet. But you can do it now.';
			$this->response($status);
		}
		else
		{
			$status['status']  = 'failure';
			$status['message'] = '#'.$year.'W#'.$week.' Timesheet exists with status ('.$res.')';
			$this->response($status);	
		}
	}
	else
	{
			$status['status']  = 'failure';
			$status['message'] = 'Post Error';
			$this->response($status);	
	}
}

/*
|---------------------------------------------------------------------------------
| Check Timesheet For requested Year Week existence
|---------------------------------------------------------------------------------
*/
public function check_ts( $week )
{
	$year = $this->post('info')['year'];
	$logged_in_user = $this->session->userdata( 'logged_in' );
	
	$res = $this->m_timesheets->check_ts_status( $logged_in_user['id'], $year, $week );
	if ( !$res ) {
		return true;
	}else{
		$this->form_validation->set_message('check_ts','#'.$year.' %s #'.$week.' Timesheet exists in DB with status "'.$res.'"');
		return false;
	}
}


/*
|---------------------------------------------------------------------------------
| Check for OWn Not Created TSs
|---------------------------------------------------------------------------------
*/
/*public function check_not_created_ts($user_id)
{
	date_default_timezone_set('Asia/Yerevan');
	//$current_date = date('Y-m-d H:i');
	$current_year = date('Y');
	$current_week = date('W');

	$res_weeks = $this->m_timesheets->get_user_weeks( $user_id, $current_year );
	$needed_weeks = [];
	// 
	if( $res_weeks )
	{
		for ($i=1; $i < $current_week; $i++) 
		{ 
			if ( !in_array(array('w_no'=>$i), $res_weeks) ) 
			{
				array_push($needed_weeks, $i);
			}
		}
	}
	else
	{
		for ($i=1; $i < $current_week; $i++) 
		{ 
				array_push($needed_weeks, $i);
		}
	}

	//print_r($res_weeks);
	//print_r($needed_weeks);

	return $needed_weeks;
}*/

/*
|---------------------------------------------------------------------------------
| Function For User Ts Full Accept Reject 
|---------------------------------------------------------------------------------
*/
public function ax_ts_actions_get()
{
	// Check GET
	if  ($this->get('id') !== false && $this->get('user_id') !== false && $this->get('action') !== false ) 
	{
		// Collecting Event info
		$info['ts_id'] = trim( $this->get('id') );
		$comment = '';
		$userinfo = $this->session->userdata('logged_in');

		if ( trim($this->get('action')) == 1 ) 
		{
			$info['status_id'] = 3;
			$act_message = 'Accept';
		} 
		elseif ( trim($this->get('action')) == 2 ) 
		{
			if ( trim($this->get('comment')) != false ) 
			{
				$info['status_id'] = 4;
				$act_message = 'Reject';
				$comment = trim($this->get('comment'));
			}
			else
			{
				$insert_status["status"]  = 'failure';
				$insert_status["message"] = 'Invalid Action. Comment is Required';
				$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );	
			}
		}
		else
		{
			$insert_status["status"]  = 'failure';
			$insert_status["message"] = 'Invalid Action.';
			$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );		
		}
		$specified_user_id = trim($this->get('user_id'));
		
		// Check User TS Existence In DB
		$res_check = $this->m_timesheets->check_ts_status( $specified_user_id, false, false, $info['ts_id']);

		if ( $res_check !== false ) 
		{

			if ( $info['status_id'] == 4) 
			{
				$this->m_timesheets->update_ts_proj_status( $info, $reset=true );

			}// Check for Timesheets not accepted projects
			else if ( !$this->m_timesheets->is_ts_acceptable( $info['ts_id'] ) ) 
			{
				$insert_status["status"]  = 'failure';
				$insert_status["message"] = 'This Timesheet is Not Acceptable. May Be There are unaccepted projects.';
				$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
				return;
			}
			
			// Accept/\Reject Specified Users Ts info
			####################################################
			$res_update = $this->m_timesheets->update_ts_info($info);
			
			if ( $res_update > 0 ) 
			{
				// Check for Rejection
				// And Set acceptable projects "is_accepted" values to 1
				// Where values are (2 or 3)


				// Still Collecting Event info
				$info['user_id'] = $userinfo['id'];
				date_default_timezone_set('Asia/Yerevan');
				$info['action_date'] = date('Y-m-d H:i:s');
				$info['touched_object'] = 'Timesheet';
				$info['comment'] = $comment;
				// Ah here it is. Insering History Event
				################################################
				$this->m_timesheets->insert_history_event($info);

				$insert_status['status']  = 'success';
				$insert_status['message'] = 'Timesheet '.$act_message .'ed successfully';
				$info['status'] = $act_message.'ed';
				$info['user'] = $userinfo['name'];
				$insert_status['ts_status_info'] = $info;
				$this->response($insert_status,REST_Controller::HTTP_CREATED);		
			}
			else
			{
				$insert_status["status"]  = 'failure';
				$insert_status['message'] = 'Timesheet '.$act_message .' failure';
				$this->response( $insert_status, REST_Controller::HTTP_INTERNAL_SERVER_ERROR );				
			}
		}
		else
		{
			$insert_status["status"]  = 'failure';
			$insert_status["message"] = 'Invalid User Timesheet.';
			$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );		
		}
	}
	else
	{
		$insert_status["status"]  = 'failure';
		$insert_status["message"] = 'POST Error.';
		$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
	}


	

	
	return;
}


/*
|---------------------------------------------------------------------------------
| Function For User Ts Row(Project) Accept Reject 
|---------------------------------------------------------------------------------
*/
public function ax_ts_project_actions_get()
{
	// Check GET
	if  ($this->get('id') !== false && $this->get('user_id') !== false && $this->get('action') !== false && $this->get('project_id') !== false && $this->get('operation_id') !== false )
	{
		// Collecting Event info
		$info['ts_id'] 			= trim( $this->get('id') );
		$info['project_id'] 	= trim($this->get('project_id'));
		$info['operation_id'] 	= trim($this->get('operation_id'));
		$comment='';
		if ( trim($this->get('action')) == 2 ) 
		{
			$info['is_accepted'] = 2;
			$status_id = 3;
			$act_message = 'Accept';
		} 
		elseif ( trim($this->get('action')) == 3 ) 
		{
			if ( trim($this->get('comment')) !== false ) 
			{
				$info['is_accepted'] = 3;
				$status_id = 4;
				$act_message = 'Reject';
				$comment = trim($this->get('comment'));
			}
			else
			{
				$insert_status["status"]  = 'failure';
				$insert_status["message"] = 'Invalid Action. Comment is Required';
				$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );	
			}
		}
		else
		{
			$insert_status["status"]  = 'failure';
			$insert_status["message"] = 'Invalid Action.';
			$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );		
		}
		

		// Accept/\Reject Specified Users Ts info
		###########################################################
		$res_update = $this->m_timesheets->update_ts_proj_status($info);

		if ( $res_update > 0 ) 
		{
			
			// Accept/\Reject Specified Users Ts info
			###########################################################
			//$res_update = $this->m_timesheets->update_ts_info($info);
			
			// + accept project in ts_main.
			// when rejecting reject projectct too
			// + insert into history.
			// + return responce.
			
			// Still Collecting Event info
			$info['user_id'] = $this->session->userdata('logged_in')['id'];
			date_default_timezone_set('Asia/Yerevan');
			$info['action_date'] 	= date('Y-m-d H:i:s');
			$info['touched_object'] = 'TS Project';
			$info['status_id'] 		= $status_id;
			$info['comment'] 		= $comment;
			unset($info['is_accepted']);

			// Ah here it is. Insering History Event
			################################################
			$this->m_timesheets->insert_history_event($info);

			$insert_status['status']  = 'success';
			$insert_status['message'] = 'TS Project '.$act_message .'ed successfully.';
			$this->response($insert_status,REST_Controller::HTTP_CREATED);		
		}
		else
		{
			$insert_status["status"]  = 'TS Project '.$act_message .' failure';
			$insert_status["message"] = 'No action has performed. Please Refresh page and try again.';
			$this->response( $insert_status, REST_Controller::HTTP_INTERNAL_SERVER_ERROR );		
		}
	}
	else
	{
		$insert_status["status"]  = 'failure';
		$insert_status["message"] = 'POST Error.';
		$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
	}


	

	
	return;
}

//#->End of c_timesheets Class
}

?>