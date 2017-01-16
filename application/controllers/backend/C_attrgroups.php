<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* C_attrgroups Controller Class for attrgroups Manipulation
*/
class C_attrgroups extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_attrgroups');
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
		$data['active_section'] = 'catalog';
		$data['active_page'] 	= 'attrgroups';

		// Checking User Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$data['allow'] = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** READ PERMISSION CHECK *********###
		############################################	
		if ( $data['allow']['read'] === FALSE )
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
		
		// Loading Header File
		$this->load->view('templates/header', $data);
		
		// Getting Sidebar From Session
		$data['sidebar'] = $this->session->userdata('sidebar');

		// Loading Sidebar File
		$this->load->view('templates/sidebar', $data);

		######################################################################################################
		#################################### * End of Permission Check * #####################################
		######################################################################################################

		// Loading attrgroup Create Form
		$data['attrgroup_create_form'] = $this->load->view('pages/attrgroups/create_form', $data, true);

		// Loading attrgroup Main Section 
		$this->load->view('pages/attrgroups/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/attrgroups/scripts', $data, true);

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
|Getting attrgroup List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function attrgroups_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'catalog';
		$data['active_page'] 	= 'attrgroups';

		// Checking attrgroup Session Activity
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
		|  Getting attrgroup List from DB
		|  3rd Parameter Config for pagination
		|  2nd Parameter brief(FALSE) For Full Columns
		*/
		$data['attrgroup_list'] = $this->m_attrgroups->get_attrgroups($id, $getConfig);
		$this->response($data['attrgroup_list']);

	}//#attrgroups_get


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
	|Insert attrgroup
	|---------------------------------------------------------------------------------
	*/
	public function attrgroups_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'catalog';
		$check['active_page'] 	 = 'attrgroups';

		// Checking attrgroup Session Activity
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
		$attrgroup = [];
		// Passing Data to Validate
		$this->form_validation->set_data( $this->post() );
		
		// Validate Posted Values for attrgroup Create
		if ( $this->form_validation->run('attrgroup_create')) 
		{
			$attrgroup['name']				= trim($this->post('name')); //*
			$attrgroup['description'] 		= trim($this->post('description'));
			/*$attrgroup['show_in_footer']	= trim($this->post('show_in_footer')); 
			$attrgroup['show_in_menu']		= trim($this->post('show_in_menu')); */
			$attrgroup['is_enabled']		= trim($this->post('is_enabled')); //*

			//Insert New attrgroup
			$res = $this->m_attrgroups->insert($attrgroup);

			if ( $res ) 
			{
					//Newly Created attrgroup id
					$attrgroup['id'] = $res;

					$status['create_status']["status"] 	= 'success';
					$status['create_status']["message"] = "Attribute Group Created.";
					$status['create_status']["id"] 		= $attrgroup['id'];
					
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

		
	}//#attrgroups_post

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
	|Update attrgroup Info
	|---------------------------------------------------------------------------------
	*/
	public function attrgroups_put($id='0')
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'catalog';
		$check['active_page'] 	 = 'attrgroups';

		// Checking attrgroup Session Activity
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

		$attrgroup = array();
		
		
		//Passing Data to Validate
		$this->form_validation->set_data( $this->put() );

		//|---------------------------------------------------------------------------------
		//| Get attrgroup From DB and Check With Updateable One
		//|---------------------------------------------------------------------------------

		$dbattrgroupInfo = $this->m_attrgroups->get_attrgroups($id)['items'][0];

		if ( $dbattrgroupInfo ) 
		{
			$db_name = mb_convert_case( $dbattrgroupInfo['name'], MB_CASE_LOWER, "UTF-8" ); 
			$post_name = mb_convert_case( trim($this->put('name')), MB_CASE_LOWER, "UTF-8" );

			// attrgroup Name Change Check
			// if it`s The Same Name But With Changed Case
			// No Need For Rules
			if ( $dbattrgroupInfo['name'] !== trim($this->put('name')) && $db_name == $post_name )
			{
				$attrgroup['name'] = trim($this->put('name')); //*				
			}
			elseif ( $dbattrgroupInfo['name'] !== trim($this->put('name')) ) 
			{
				$attrgroup['name'] = trim($this->put('name')); //*
				$this->form_validation->set_rules('name', 'Name', 'trim|required|is_unique[mb_attribute_groups.name]');
			}
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Invalid attrgroup. Refresh Page and Try Again';
			
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}

		$attrgroup['description'] 		= trim($this->put('description'));
		$attrgroup['is_enabled'] 		= trim($this->put('is_enabled'));

		// Setting Validation Rules For Update attrgroup
		$this->form_validation->set_rules('description', 'Description', 'trim|max_length[255]');
		$this->form_validation->set_rules('is_enabled', 'Is Enabled', 'trim|required|numeric|in_list[0,1]');
		
		// Validating PUT Values
		if ( $this->form_validation->run() !== FALSE ) 
		{
			$attrgroup['id'] = $id;

			/*
			|---------------------------------------------------------------------------------
			| Update attrgroup
			|---------------------------------------------------------------------------------
			*/ 
			$res = $this->m_attrgroups->update( $attrgroup );
			

			/*
			// Check Results
			*/

			if ( $res ) //|| $are_assigs_changed 
			{
				$status['update_status']["status"] = 'success';
				$status['update_status']["message"] = 'Update Success';
				//Postback
				$this->response( $status['update_status'], REST_Controller::HTTP_OK );
			}
			else
			{
				$status['update_status']["status"] = 'failure';
				$status['update_status']["message"] = "Nothing Has Changed";
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
	}//#attrgroup_put

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
	|Destroying attrgroup
	|---------------------------------------------------------------------------------
	*/
	public function attrgroups_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'catalog';
		$check['active_page']	 = 'attrgroups';

		// Checking attrgroup Session Activity
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

		// Check Client existence in another tables
		$check_result = $this->m_attrgroups->check_before_delete($id);

		// Check Delete and Response
		if ( $check_result['status'] ) 
		{
			//Delete attrgroup
			$res = $this->m_attrgroups->delete($id);

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
			}
		}
		else
		{
			$status['delete_status']["status"] = 'failure';
			$status['delete_status']["message"] = 'Cannot delete. Attribute Group Exists in some tables '.$check_result['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}
	}//#attrgroups_delete


public function alpha_custom1($name)
{
	if (!preg_match('/^[a-zA-Z1-9 .,;\-]+$/i',$name)) 
	{
		$this->form_validation->set_message('alpha_custom1','The %s field must contain only Alpha-Numeric, Dot, Comma Space and Dash values.' );
		return false;

	}
	else
	{
		return true;
	}
}


//End of C_attrgroups Class
}
?>