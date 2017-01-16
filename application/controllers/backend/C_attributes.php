<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_attributes Controller Class for Attribute Manipulation
*/
class C_attributes extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_attributes');
		$this->load->model('m_attrgroups');
		$this->load->model('m_config');
		$this->load->model('m_validation');
		$this->load->library('form_validation');
		//$this->load->library('security');

	}


	private $gallery_dir = "application/assets/img/gallery";
	private $upload_dir = "application/assets/uploads";
	private $attribute_featured_default_image = 'bdc52e9f0197ed9d052b444891085cbf.png';
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
		$data['active_page'] 	= 'attributes';

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


		// Get Desert List
		$data['attrgroup_list'] = $this->m_attrgroups->get_attrgroups();
		// Get MMT List
		$data['mmt_list'] = $this->m_config->get_mmts();
		// Get Gallery Directory
		$data['gallery_directory'] = $this->gallery_dir;
		
		$data['upload_directory'] = $this->upload_dir;

		$data['featured_default_image'] = $this->attribute_featured_default_image;

		// Loading Attribute Create Form
		$data['attribute_create_form'] = $this->load->view('pages/attributes/create_form', $data, true);

		// Loading Attribute Main Section 
		$this->load->view('pages/attributes/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/attributes/scripts', $data, true);

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
|Getting Attribute List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function attributes_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'catalog';
		$data['active_page'] 	= 'attributes';

		// Checking Attribute Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$data['allow'] = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** READ PERMISSION CHECK *********###
		############################################	
		if ( $data['allow']['read'] === FALSE )
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
		|  Getting Attribute List from DB
		|  3rd Parameter Config for pagination
		|  2nd Parameter brief(FALSE) For Full Columns
		*/
		$data['attribute_list'] = $this->m_attributes->get_attributes($id, false, $getConfig);
		$this->response($data['attribute_list']);

	}//#attributes_get


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
	|Insert Attribute
	|---------------------------------------------------------------------------------
	*/
	public function attributes_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'catalog';
		$check['active_page'] 	 = 'attributes';

		// Checking Attribute Session Activity
		$check['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$data['allow'] = $this->m_validation->check_requested_page_perms( $check['active_section'], $check['active_page'] );

		############################################
		##****** CREATE PERMISSION CHECK *********##
		############################################	
		if ( $data['allow']['create'] === FALSE )
		{
			$status['create_status']["status"] = '403';
			$status['create_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
			$this->response($status['create_status']);
			return;
		}
		
		$attribute = [];
		/*
		// get this Vars from system config values( later )
		*/
		
		//$featured_img = $this->attribute_avatar_default_image;

		// Passing Data to Validate
		$this->form_validation->set_data( $this->post() );

		// Validate Posted Values for Attribute Create
		if ( $this->form_validation->run('attribute_create')) 
		{
			date_default_timezone_set("Asia/Yerevan");

			$featured_img_loc	 = trim($this->post('featured_image_hash'));
			$featured_img_name 	 = trim($this->post('featured_image_name'));
			$featured_img = $this->m_config->arrange_uploads_in_gallery($featured_img_loc,$featured_img_name);

			if ( $featured_img === false ) {
				$featured_img = $this->attribute_featured_default_image;
			}

			$attribute['name']		 	 	= trim($this->post('name'));
			$attribute['description']	 	= trim($this->post('description'));
			$attribute['created']		 	= date('Y/m/d H:i:s');
			$attribute['attrgroup_id']	 	= trim($this->post('attrgroup_id'));
			$attribute['mmt_id']		 	= trim($this->post('mmt_id'));
			$attribute['unit_price']	 	= trim($this->post('price'));
			$attribute['featured_image']	= $featured_img;
			$attribute['allow_user_text']   = trim($this->post('allow_user_text'));
			$attribute['is_active']		    = trim($this->post('is_active'));

			//Insert New Attribute
			$res = $this->m_attributes->insert($attribute);

			if ( $res ) {
				// Newly Created Attribute id
				$attribute['id'] = $res;
				// Postback Status
				$status['create_status']["status"] 	= 'success';
				$status['create_status']["message"] = "Attribute =|".$attribute['name']."|= Created.";
				$status['create_status']["id"] 		= $attribute['id'];
				//Postback
				$this->response( $status['create_status'], REST_Controller::HTTP_CREATED );
			}
			else{
				$status['create_status']["status"] 	= 'failure';
				$status['create_status']["message"] = 'Internal Error: ' . $this->db->error();
				// Postback
				$this->response( $status['create_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
			}
		}
		else{
			$status['create_status']["status"] 	= 'failure';
			$status['create_status']["message"] = validation_errors();

			// Postback
			$this->response( $status['create_status'], REST_Controller::HTTP_BAD_REQUEST );
		}

		
	}//#attributes_post

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
	|Update Attribute Info
	|---------------------------------------------------------------------------------
	*/
	public function attributes_put($id='0')
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'catalog';
		$check['active_page'] 	 = 'attributes';

		// Checking Attribute Session Activity
		$check['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$data['allow'] = $this->m_validation->check_requested_page_perms( $check['active_section'], $check['active_page'] );

		############################################
		##****** UPDATE PERMISSION CHECK *********##
		############################################	
		if ( $data['allow']['update'] === FALSE )
		{
			$status['update_status']["status"] = '403';
			$status['update_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
			$this->response($status['update_status']);
			return;
		}

		$attribute = array();
		
		
		//Passing Data to Validate
		$this->form_validation->set_data( $this->put() );

		/*|---------------------------------------------------------------------------------
		| Get Attribute From DB and Compare With Updatable One
		|---------------------------------------------------------------------------------*/
		$dbAttributeInfo = $this->m_attributes->get_attributes($id)['items'][0];

		if ( $dbAttributeInfo ) 
		{
			$attribute = $this->check_set_init($dbAttributeInfo);
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Invalid Attribute. Refresh Page and Try Again';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}

		date_default_timezone_set("Asia/Yerevan");
		// Validating PUT Values
		if ( $this->form_validation->run() !== FALSE ) 
		{
			$attribute['id'] = $id;
			
			/*|---------------------------------------------------------------------------------
			| Update Attribute
			|---------------------------------------------------------------------------------*/ 
			$res = $this->m_attributes->update( $attribute );
			

			/* Check Results */
			if ( $res )
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
	}//#attribute_put

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
	|Destroying Attribute
	|---------------------------------------------------------------------------------
	*/
	public function attributes_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'catalog';
		$check['active_page']	 = 'attributes';

		// Checking Attribute Session Activity
		$check['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requested Page Permissions
		$data['allow'] = $this->m_validation->check_requested_page_perms( $check['active_section'], $check['active_page'] );

		############################################
		##****** DELETE PERMISSION CHECK *********##
		############################################	
		if ( $data['allow']['delete'] === FALSE )
		{
			$status['delete_status']["status"] = '403';
			$status['delete_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
			$this->response($status['delete_status']);
			return;
		}

		// Check Client existence in another tables
		$check_result = $this->m_attributes->check_before_delete($id);

		// Check Delete and Response
		if ( $check_result['status'] ) 
		{
			// Delete Attribute from attribute_team
			//$res_del_team = $this->m_attributes->delete_attribute_team( $id );

			//Delete Attribute
			$res = $this->m_attributes->delete($id);

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
			$status['delete_status']["message"] = 'Cannot delete. This Attribute is used in '.$check_result['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}

	}//#attributes_delete


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
	$data['active_section'] = 'catalog';
	$data['active_page'] 	= 'attributes';

	// Checking User Session Activity
	$data['userinfo'] = $this->m_validation->check_user_loggedin();

	// Checking for Requerted Page Permissions
	$data['allow'] = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );
	
	############################################
	###****** READ PERMISSION CHECK *********###
	############################################	
	if ( $data['allow']['read'] === FALSE )
	{
		$data['read_status']["status"] = '550';
		$data['read_status']["message"] = 'You Don`t Have Permissions to View This Page!!!';
		$this->response($data['read_status']);
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

	// Get Attribute ass and operations
	$data['attribute_operations'] = $this->m_attributes->get_attribute_operations($id);

	// get attribute team
	$data['attribute_team'] = $this->m_attributes->get_attribute_team($id);
	
	// Get Attribute Details
	$proj_temp = $this->m_attributes->get_attributes($id)['items'];

	if ($proj_temp) 
	{
		$data['attribute_details'] = $proj_temp[0];
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
	$this->load->view('pages/attributes/profile', $data);

	// Loading Footer File
	$this->load->view('templates/footer', $data);
	
	return;
}//# attribute profile




public function check_date($date)
{
	$year = (int)substr($date, 0, 4);
	$month = (int)substr($date, 5, 2);
	$day = (int)substr($date, 8, 2);
	
	return checkdate( $month, $day, $year );
}



public function upload_image_post()
{
	$this->upload_universal_handler();
}

public function upload_image_delete()
{
	$this->upload_universal_handler();
}


/*
   |---------------------------------------------------------------------------------
   | Upload And save Attribute Images
   |---------------------------------------------------------------------------------
*/
public function upload_universal_handler()
{
	// Include the upload handler class
	//require_once "handler.php";
	$this->load->library('UpHandler');
	//$uploader = new UploadHandler();
	// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
	$this->uphandler->allowedExtensions = array("jpeg","jpg","png"); // all files types allowed by default
	// Specify max file size in bytes.
	$this->uphandler->sizeLimit = null;
	// Specify the input name set in the javascript.
	$this->uphandler->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
	// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
	$this->uphandler->chunksFolder = "chunks";
	// Temp Directory For Uploaded Files
	$upload_directory = $this->upload_dir;

	$method = $_SERVER["REQUEST_METHOD"];
	if ($method == "POST") {
	    header("Content-Type: text/plain");
	    // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
	    // For example: /myserver/handlers/endpoint.php?done
	    if (isset($_GET["done"])) {
	        $result = $this->uphandler->combineChunks("files");
	    }
	    // Handles upload requests
	    else {
	    	/*$this->response(getcwd());
	    	exit;*/
	        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
	        $result = $this->uphandler->handleUpload($upload_directory);
	        // To return a name used for uploaded file you can use the following line.
	        $result["uploadName"] = $this->uphandler->getUploadName();
	    }
	    echo json_encode($result);
	}
	// for delete file requests
	else if ($method == "DELETE") {
	    $result = $this->uphandler->handleDelete($upload_directory);
	    echo json_encode($result);
	}
	else {
	    header("HTTP/1.0 405 Method Not Allowed");
	}
}



   /*
   |---------------------------------------------------------------------------------
   | Upload And save Attribute Images
   |---------------------------------------------------------------------------------
   */
   public function upload_image_1_post()
    {
    	 $data['insert_status']=array();

		//Getting Config For Uploaded Image Thumb Creation
		$upload_config = $this->get_upload_config();

		//Loading Upload Library For File Upload 
        $this->load->library('upload', $upload_config);
        //print_r($this->post());
        //exit;
        if ( !$this->upload->do_upload('file'))
        {
            $data['insert_status']['file_upload_errors'] = $this->upload->display_errors('<p style="color:RED;">','</p>');
            $this->response(array( 
            					'upload_statusx'=>array(
            											'status'=>'failure',
            											'message'=>$data['insert_status']['file_upload_errors'])),REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        else
        {
        	$file_name = $upload_config['file_name'] . $this->upload->data('file_ext');
            //Collecting uploaded File Full Path
        	$source_image = $upload_config['upload_path'] . $file_name;

        	//Getting Config For Resizing Uploaded File
			$image_config = $this->get_resize_config($source_image);

			//Loading Image Library
			$this->load->library('image_lib', $image_config);

			//Resizing Image
			if ( !$this->image_lib->resize() ) 
			{
				$data['insert_status']['file_resize_errors'] = $this->image_lib->display_errors();

				$this->response(
									array( 
	            					'upload_statusx'=>array(
	        											'status'=>'failure',
	        											'message'=>$data['insert_status']['file_resize_errors'])
	            											),
									REST_Controller::HTTP_INTERNAL_SERVER_ERROR
							);
			}
			else
			{
				/*$company_name = $this->input->post('companyName');
				$company_head = $this->input->post('companyHead');
				$company_logo = $file_name;*/
				
				/*$res = $this->m_company->insert(array(
													'name' => $this->input->post('companyName'),
													'head_id' => $this->input->post('companyHead'),
													'logo'=> $file_name
											));*/
				//$data['insert_status']['insert_result'] =$res;
				$this->response(	
									array( 
            						'upload_statusx'=>array(
														'status'=>'success',
														'message'=>'uploaded to server',
														'newFileName'=>$upload_config['file_name'] . '_thumb' .$this->upload->data('file_ext'))
            											),
									REST_Controller::HTTP_OK
								);
			}
		}
    } //#upload_avtar

/*
	|---------------------------------------------------------------------------------
	|Function Returning Config For Uploaded File
	|---------------------------------------------------------------------------------
	*/
	public function get_upload_config()
	{
		//Loading Date Helper For DateTime
		$this->load->helper('date');

		//Location For Logo
		$img_location = 'application/assets/img/avatars/';

		//Generating Logo Name Viea MD5 Hash on Time
		$img_name = md5(now('Asia/Yerevan'));

		//File Upload Config
		$config['file_name']	   = $img_name;
		$config['upload_path']     = $img_location;
        $config['allowed_types']   = 'gif|jpg|jpeg|png';
        $config['max_size']        = 1000;
        $config['max_width']       = 10240;
        $config['max_height']      = 7680;

        return $config;
	}

	/*
	|---------------------------------------------------------------------------------
	|Function Returning Config For Image Resizing
	|---------------------------------------------------------------------------------
	*/
	public function get_resize_config( $full_path )
	{
		//Image Resize Config
		$config['image_library'] 	= 'gd2';
		$config['source_image'] 	= $full_path;
		$config['create_thumb'] 	= TRUE;
		$config['maintain_ratio'] 	= true;
		$config['width']         	= 215;
		$config['height']       	= 215;

		return $config;
	}


	
/*
CHECK getted values with db values and SET rules for validating
INIT array for insert 
return __> full insertable array
*/

protected function check_set_init($db_values)
	{
		// Check For Same Name Change (upper/lower)
		$db_name = mb_convert_case( $db_values['name'], MB_CASE_LOWER, "UTF-8" ); 
		$put_name = mb_convert_case( trim($this->put('name')), MB_CASE_LOWER, "UTF-8" );

		// Attribute Name Change Check
		// if it`s The Same Name But With Changed Case
		// No Need For Rules
		if ( $db_values['name'] !== trim($this->put('name')) && $db_name == $put_name )
		{
			$attribute['name'] = trim($this->put('name')); //*				
		}
		elseif ( $db_values['name'] !== trim($this->put('name')) ) 
		{
			$attribute['name'] = trim($this->put('name')); //*
			$this->form_validation->set_rules('name', 'Name', 'trim|required|is_unique[mb_attributes.name]');
		}

		// Check Featured Image Change
		$db_featured_image = mb_convert_case( $db_values['featured_image'], MB_CASE_LOWER, "UTF-8" ); 
		$put_featured_image = mb_convert_case( trim($this->put('featured_image')), MB_CASE_LOWER, "UTF-8" );

		if ( $db_values['featured_image'] !== trim($this->put('featured_image')) && $db_featured_image == $put_featured_image )
		{
			$featured_img_loc	 = trim($this->put('featured_image_hash'));
			$featured_img_name 	 = trim($this->put('featured_image_name'));
			$featured_img = $this->m_config->arrange_uploads_in_gallery($featured_img_loc,$featured_img_name);

			if ( $featured_img === false ) {
				$featured_img = $this->attribute_featured_default_image;
			}

			$attribute['featured_image'] = $featured_img;
		}
		elseif ( $db_values['featured_image'] !== trim($this->put('featured_image')) ) 
		{
			$this->form_validation->set_rules('featured_image', 'Name', 'trim|is_unique[mb_attributes.featured_image]');
			
			$featured_img_loc	 = trim($this->put('featured_image_hash'));
			$featured_img_name 	 = trim($this->put('featured_image_name'));
			$featured_img = $this->m_config->arrange_uploads_in_gallery($featured_img_loc,$featured_img_name);

			if ( $featured_img === false ) {
				$featured_img = $this->attribute_featured_default_image;
			}

			$attribute['featured_image'] = $featured_img;
		}

		$attribute['description']		  = trim($this->put('description'));
		$attribute['last_modified'] 	  = date('Y/m/d H:i:s');
		$attribute['attrgroup_id']		  = trim($this->put('attrgroup_id'));
		$attribute['mmt_id']		 	  = trim($this->put('mmt_id'));
		$attribute['unit_price']		  = trim($this->put('price'));
		$attribute['allow_user_text']	  = trim($this->put('allow_user_text'));
		$attribute['is_active']		  	  = trim($this->put('is_active'));
		// Setting Validation Rules For Update Operation
		
		$this->form_validation->set_rules('description', 'Description', 'trim|max_length[255]' );				
		$this->form_validation->set_rules('attrgroup_id', 'Desert Type', 'trim|required|numeric' );
		$this->form_validation->set_rules('mmt_id', 'Price', 'trim|numeric|required' );
		$this->form_validation->set_rules('price', 'Price', 'trim|numeric' );
		$this->form_validation->set_rules('allow_user_text', 'Allow User Text', 'trim|required|in_list[0,1]' );
		$this->form_validation->set_rules('is_active', 'Active', 'trim|required|in_list[0,1]' );

		return $attribute;
	}


//End of c_attributes Class
}
?>