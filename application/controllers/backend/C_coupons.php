<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* C_coupons Controller Class for coupons Manipulation
*/
class C_coupons extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_coupons');
		$this->load->model('m_config');
		$this->load->model('m_validation');
		$this->load->library('form_validation');
	}

	
	private $coupon_featured_default_image	= 'bdc52e9f0197ed9d052b444891085cbf.png';

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
		$data['active_section'] = 'orders_all';
		$data['active_page'] 	= 'coupons';

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

		// Loading coupon Create Form
		//$data['coupon_create_form'] = "";//$this->load->view('pages/coupons/create', $data, true);

		// Loading coupon Main Section 
		$this->load->view('pages/coupons/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/coupons/scripts', $data, true);

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
|Getting coupon List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function coupons_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'orders_all';
		$data['active_page'] 	= 'coupons';

		// Checking coupon Session Activity
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
		|  Getting coupon List from DB
		|  3rd Parameter Config for pagination
		|  2nd Parameter brief(FALSE) For Full Columns
		*/
		$data['coupon_list'] = $this->m_coupons->get_coupons($id, $getConfig);
		$this->response($data['coupon_list']);

	}//#coupons_get


/*
──────────────────────────────────────────────────────────────────────────────────────────────────
─██████████████─████████████████───██████████████─██████████████─██████████████─██████████████────
─██░░░░░░░░░░██─██░░░░░░░░░░░░██───██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██────
─██░░██████████─██░░████████░░██───██░░██████████─██░░██████░░██─██████░░██████─██░░██████████────
─██░░██─────────██░░██────██░░██───██░░██─────────██░░██──██░░██─────██░░██─────██░░██────────────
─██░░██─────────██░░████████░░██───██░░██████████─██░░██████░░██─────██░░██─────██░░██████████────
─██░░██─────────██░░░░░░░░░░░░██───██░░░░░░░░░░██─██░░░░░░░░░░██─────██░░██─────██░░░░░░░░░░██────
─██░░██─────────██░░██████░░████───██░░██████████─██░░██████░░██─────██░░██─────██░░██████████────
─██░░██─────────██░░██──██░░██─────██░░██─────────██░░██──██░░██─────██░░██─────██░░██────────────
─██░░██████████─██░░██──██░░██████─██░░██████████─██░░██──██░░██─────██░░██─────██░░██████████────
─██░░░░░░░░░░██─██░░██──██░░░░░░██─██░░░░░░░░░░██─██░░██──██░░██─────██░░██─────██░░░░░░░░░░██────
─██████████████─██████──██████████─██████████████─██████──██████─────██████─────██████████████────
──────────────────────────────────────────────────────────────────────────────────────────────────
─────────────────────────────────────────────────────────────
─██████████████─██████████████─██████████████─██████████████─
─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
─██░░██████░░██─██░░██████░░██─██░░██████████─██░░██████████─
─██░░██──██░░██─██░░██──██░░██─██░░██─────────██░░██─────────
─██░░██████░░██─██░░██████░░██─██░░██─────────██░░██████████─
─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░██──██████─██░░░░░░░░░░██─
─██░░██████████─██░░██████░░██─██░░██──██░░██─██░░██████████─
─██░░██─────────██░░██──██░░██─██░░██──██░░██─██░░██─────────
─██░░██─────────██░░██──██░░██─██░░██████░░██─██░░██████████─
─██░░██─────────██░░██──██░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
─██████─────────██████──██████─██████████████─██████████████─
─────────────────────────────────────────────────────────────

	*/
	/*
	Create Coupon Page
	*/
	public function coupon_actions_get($action=false,$param_id=false)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'orders_all';
		$data['active_page'] 	= 'coupons';

		// Checking coupon Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$data['allow'] = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** CREATE PERMISSION CHECK *********###
		############################################	
		if ( $data['allow']['create'] === FALSE )
		{
			$data['create_status']["status"] = '550';
			$data['create_status']["message"] = 'You Don`t Have Permissions to View This Page!!!';
			$this->response($data['read_status']);
			return;
		}

		// Loading Header File
		$this->load->view('templates/header', $data);
		
		// Getting Sidebar From Session
		$data['sidebar'] = $this->session->userdata('sidebar');

		// Loading Sidebar File
		$this->load->view('templates/sidebar', $data);

		######################################################################################################
		#################################### * End of Permission Check * #####################################
		######################################################################################################

		//Get MMT List
		$data['mmt_list'] = $this->m_config->get_mmts();

		$this->load->view('pages/coupons/create',$data);
		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/coupons/scripts', $data, true);
		//loading Footer
		$this->load->view('templates/footer',$data);

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
	|Insert Coupon
	|---------------------------------------------------------------------------------
	*/
	public function coupons_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'orders_all';
		$check['active_page'] 	 = 'coupons';

		// Checking Coupon Session Activity
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

		
		$coupon = [];

		// Passing Data to Validate
		$this->form_validation->set_data( $this->post() );

		// Validate Posted Values for Coupon Create
		if ( $this->form_validation->run('coupon_create')) 
		{
			date_default_timezone_set("Asia/Yerevan");

			$coupon['code']		 	= trim($this->post('code'));
			$coupon['description']	= trim($this->post('description'));
			$coupon['type']			= trim($this->post('type'));
			$coupon['discount']	 	= trim($this->post('discount'));
			$coupon['start_date']	= trim($this->post('start_date'));
			$coupon['end_date']	 	= trim($this->post('end_date'));
			$coupon['is_enabled']	= trim($this->post('is_enabled'));

			//Insert New Coupon
			$res = $this->m_coupons->insert($coupon);

			if ( $res ) {
				// Newly Created Coupon id
				$coupon['id'] = $res;
				// Postback Status
				$status['create_status']["status"] 	= 'success';
				$status['create_status']["message"] = "Coupon =|".$coupon['code']."|= Created.";
				$status['create_status']["id"] 		= $coupon['id'];
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

		
	}//#coupons_post

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
	|Update Coupon Info
	|---------------------------------------------------------------------------------
	*/
	public function coupons_put($id='0')
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'orders_all';
		$check['active_page'] 	 = 'coupons';

		// Checking Coupon Session Activity
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

		$coupon = array();
		
		
		//Passing Data to Validate
		$this->form_validation->set_data( $this->put() );

		/*|---------------------------------------------------------------------------------
		| Get Coupon From DB and Compare With Updatable One
		|---------------------------------------------------------------------------------*/
		$dbCouponInfo = $this->m_coupons->get_coupons($id)['items'][0];

		if ( $dbCouponInfo ) 
		{
			$coupon = $this->check_and_init($dbCouponInfo);
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Invalid Coupon. Refresh Page and Try Again';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}

		date_default_timezone_set("Asia/Yerevan");
		// Validating PUT Values
		if ( $this->form_validation->run() !== FALSE ) 
		{
			$coupon['id'] = $id;
			
			/*|---------------------------------------------------------------------------------
			| Update Coupon
			|---------------------------------------------------------------------------------*/ 
			$res = $this->m_coupons->update( $coupon );
			

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
	}//#coupon_put

/*─────────────────────────────────────────────────────────────────────────────────────────
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
	|Destroying coupon
	|---------------------------------------------------------------------------------
	*/
	public function coupons_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'orders_all';
		$data['active_page']	 = 'coupons';

		// Checking coupon Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requested Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

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
		$check_result = $this->m_coupons->check_before_delete($id);

		// Check Delete and Response
		if ( $check_result['status'] ) 
		{
			//Delete coupon
			$res = $this->m_coupons->delete($id);

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
			$status['delete_status']["message"] = 'Cannot delete. coupon Exists in some tables '.$check_result['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}
	}//#coupons_delete


/*
|
| Get Desert list 
|
*/
public function get_items_get()
{
	$q = htmlspecialchars($this->get('q'),ENT_QUOTES);
	$this->load->model('m_products');
	$res = $this->m_products->get_products(0,true,false,$q);
	$this->response($res);
}

/*
|
| Get Attribute list 
|
*/
public function get_attributes_get()
{
	$q = htmlspecialchars($this->get('q'),ENT_QUOTES);
	$this->load->model('m_attributes');
	$res = $this->m_attributes->get_attributes(0,true,false,$q);
	$this->response($res);
}


/*
|
| Get full coupon
|
*/
public function get_full_get($id=false)
{
	if ( $this->get('action')!==null && $this->get('id') !== null ) 
	{
		if ($this->get('action') == 'e') 
		{
			if (isset($this->m_coupons->get_coupons($this->get('id'))['items'][0])) 
			{
				// get coupon
				$coupon_info = $this->m_coupons->get_coupons($this->get('id'))['items'][0];
			}
			else
			{
				$coupon_info = 'undefined';
			}

			// get coupon items
			$coupon_items = $this->m_coupons->get_coupon_items($this->get('id'));
			// get coupon attrs	
			$coupon_attrs = $this->m_coupons->get_coupon_attrs($this->get('id'));
			$resp = array(
							'status'=>'success',
							'attrs'=>$coupon_attrs,
							'info'=>$coupon_info,
							'items'=>$coupon_items
							);

			$this->response($resp, REST_Controller::HTTP_OK);
		}
		
	}
}



public function check_date($date)
{
	/*//$datetmp = DateTime::createFromFormat( 'Y-m-d',$date);
	$datetmp = strtotime($date);
	if (!$datetmp) {
		return false;
	}
	$year  = $datetmp->format('Y');//(int)substr($date, 0, 4);
	$month = $datetmp->format('m');//(int)substr($date, 5, 2);
	$day   = $datetmp->format('d');//(int)substr($date, 8, 2);*/

	$format = 'Y-m-d';
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
/*		
		return checkdate( $month, $day, $year );*/
}

public function check_period($date)
{
	if ($_SERVER['REQUEST_METHOD']==='POST') 
	{
		$start_date = $this->post('start_date');
		$end_date 	= $this->post('end_date');
	} 
	elseif ($_SERVER['REQUEST_METHOD']==='PUT') 
	{
		$start_date = $this->put('start_date');
		$end_date 	= $this->put('end_date');
	}
	else
	{
		return false;
	}
		
	
		if ($this->check_date($start_date)) 
		{
			$start_date = DateTime::createFromFormat( 'Y-m-d',$start_date);
		}
		else
		{
			return false;
		}

		if ($this->check_date($end_date)) 
		{
			$end_date   = DateTime::createFromFormat( 'Y-m-d',$end_date);
		}
		else
		{
			return false;
		}
		
	
	//$interval = date_diff( $start_date->format('Y-m-d'), $end_date->format('Y-m-d') );

	//check interval in days "%a"
	if ( $start_date->format('Y-m-d') > $end_date->format('Y-m-d') ) 
	{
		return false;
	}
	else
	{
		return true;
	}
}

/*
CHECK getted values with db values and SET rules for validating
INIT array for insert 
return __> full insertable array
*/

protected function check_and_init($db_values)
	{
		// Check For Same Name Change (upper/lower)
		$db_code = mb_convert_case( $db_values['code'], MB_CASE_LOWER, "UTF-8" ); 
		$put_code = mb_convert_case( trim($this->put('code')), MB_CASE_LOWER, "UTF-8" );

		// Coupon Name Change Check
		// if it`s The Same Name But With Changed Case
		// No Need For Rules
		if ( $db_values['code'] !== trim($this->put('code')) && $db_code == $put_code )
		{
			$coupon['code'] = trim($this->put('code')); //*				
		}
		elseif ( $db_values['code'] !== trim($this->put('code')) ) 
		{
			$coupon['code'] = trim($this->put('code')); //*
			$this->form_validation->set_rules('code', 'Code', 'trim|required|is_unique[mb_coupons.code]');
		}

		$coupon['description']	= trim($this->put('description'));
		$coupon['type']			= trim($this->put('type'));
		$coupon['discount']	 	= trim($this->put('discount'));
		$coupon['start_date']	= trim($this->put('start_date'));
		$coupon['end_date']	 	= trim($this->put('end_date'));
		$coupon['is_enabled']	= trim($this->put('is_enabled'));

		// Setting Validation Rules For Update Operation
		
		$this->form_validation->set_rules('description', 'Description', 'trim|max_length[255]' );				
		$this->form_validation->set_rules('type', 'Type', 'trim|required|in_list[fix,percent]' );
		$this->form_validation->set_rules('discount', 'Discount', 'trim|required|numeric' );
		
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|callback_check_date',
											array('check_date'=> 'The %s is Wrong. It Must have "YYYY-MM-DD" format',
												'check_period'=> 'Invalid %s Period'));

		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required|callback_check_date|callback_check_period',
											array('check_date'=> 'The %s is Wrong. It Must have "YYYY-MM-DD" format',
												'check_period'=> 'Invalid %s Period'));
	
		$this->form_validation->set_rules('is_enabled', 'Enabled', 'trim|required|in_list[0,1]' );

		return $coupon;
	}

//End of C_coupons Class
}
?>