<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_products Controller Class for Product Manipulation
*/
class C_products extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_products');
		$this->load->model('m_deserts');
		$this->load->model('m_flavors');
		$this->load->model('m_colors');
		$this->load->model('m_config');
		$this->load->model('m_validation');
		$this->load->library('form_validation');
		//$this->load->library('security');

	}

	private $product_avatar_default_image 	= 'bdc52e9f0197ed9d052b444891085cbf.png';
	private $product_featured_default_image = 'bdc52e9f0197ed9d052b444891085cbf.png';
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
		$data['active_page'] 	= 'products';

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
		$data['desert_list'] = $this->m_deserts->get_deserts();
		// Get Flavor List
		$data['flavor_list'] = $this->m_flavors->get_flavors();
		// Get Color List
		$data['color_list'] = $this->m_colors->get_colors();
		// Get MMT List
		$data['mmt_list'] = $this->m_config->get_mmts();
		// Get Gallery Directory
		$data['gallery_directory'] = $this->m_config->get_gallery_dir();
		
		$data['upload_directory'] = $this->m_config->get_upload_dir();

		
		$data['avatar_default_image'] = $this->product_avatar_default_image;
		$data['featured_default_image'] = $this->product_featured_default_image;

		// Loading Product Create Form
		$data['product_create_form'] = $this->load->view('pages/products/create_form', $data, true);

		// Loading Product Main Section 
		$this->load->view('pages/products/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/products/scripts', $data, true);

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
|Getting Product List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function products_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'catalog';
		$data['active_page'] 	= 'products';

		// Checking Product Session Activity
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
		|  Getting Product List from DB
		|  3rd Parameter Config for pagination
		|  2nd Parameter brief(FALSE) For Full Columns
		*/
		$data['product_list'] = $this->m_products->get_products($id, false, $getConfig);
		$this->response($data['product_list']);

	}//#products_get


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
	|Insert Product
	|---------------------------------------------------------------------------------
	*/
	public function products_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'catalog';
		$check['active_page'] 	 = 'products';

		// Checking Product Session Activity
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
		
		$product = [];
		/*
		// get this Vars from system config values( later )
		*/
		
		//$featured_img = $this->product_avatar_default_image;

		// Passing Data to Validate
		$this->form_validation->set_data( $this->post() );

		// Validate Posted Values for Product Create
		if ( $this->form_validation->run('product_create')) 
		{
			date_default_timezone_set("Asia/Yerevan");

			$avatar_loc	 = trim($this->post('avatar_hash'));
			$avatar_name = trim($this->post('avatar_name'));
			$avatar = $this->m_config->arrange_uploads_in_gallery($avatar_loc,$avatar_name);

			if ( $avatar === false ) {
				$avatar = $this->product_avatar_default_image;
			}

			$featured_img_loc	 = trim($this->post('featured_image_hash'));
			$featured_img_name 	 = trim($this->post('featured_image_name'));
			$featured_img = $this->m_config->arrange_uploads_in_gallery($featured_img_loc,$featured_img_name);

			if ( $featured_img === false ) {
				$featured_img = $this->product_featured_default_image;
			}

			$product['name']		 	  = trim($this->post('name'));
			$product['sku']		 		  = trim($this->post('sku'));
			$product['description']		  = trim($this->post('description'));
			$product['created']		 	  = date('Y/m/d H:i:s');
			$product['desert_id']		  = trim($this->post('desert_id'));
			$product['flavor_id']		  = trim($this->post('flavor_id'));
			$product['color_id']		  = trim($this->post('color_id'));
			$product['mmt_id']		 	  = trim($this->post('mmt_id'));
			$product['weight']		 	  = trim($this->post('weight'));
			$product['unit_price']		  = trim($this->post('price'));
			$product['custom_box_avatar'] = $avatar;
			$product['featured_image']	  = $featured_img;
			$product['use_in_set']		  = trim($this->post('use_in_set'));
			$product['show_in_gallery']	  = trim($this->post('show_in_gallery'));
			$product['is_active']		  = trim($this->post('is_active'));

			//Insert New Product
			$res = $this->m_products->insert($product);

			if ( $res ) {
				// Newly Created Product id
				$product['id'] = $res;
				// Postback Status
				$status['create_status']["status"] 	= 'success';
				$status['create_status']["message"] = "Product =|".$product['name']."|= Created.";
				$status['create_status']["id"] 		= $product['id'];
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

		
	}//#products_post

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
	|Update Product Info
	|---------------------------------------------------------------------------------
	*/
	public function products_put($id='0')
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'catalog';
		$check['active_page'] 	 = 'products';

		// Checking Product Session Activity
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

		$product = array();
		
		
		//Passing Data to Validate
		$this->form_validation->set_data( $this->put() );

		/*|---------------------------------------------------------------------------------
		| Get Product From DB and Compare With Updatable One
		|---------------------------------------------------------------------------------*/
		$dbProductInfo = $this->m_products->get_products($id)['items'][0];

		if ( $dbProductInfo ) 
		{
			$product = $this->check_set_init($dbProductInfo);
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Invalid Product. Refresh Page and Try Again';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}

		date_default_timezone_set("Asia/Yerevan");
		// Validating PUT Values
		if ( $this->form_validation->run() !== FALSE ) 
		{
			$product['id'] = $id;
			
			/*|---------------------------------------------------------------------------------
			| Update Product
			|---------------------------------------------------------------------------------*/ 
			$res = $this->m_products->update( $product );
			

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
	}//#product_put

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
	|Destroying Product
	|---------------------------------------------------------------------------------
	*/
	public function products_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'catalog';
		$check['active_page']	 = 'products';

		// Checking Product Session Activity
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
		$check_result = $this->m_products->check_before_delete($id);

		// Check Delete and Response
		if ( $check_result['status'] ) 
		{
			// Delete Product from product_team
			//$res_del_team = $this->m_products->delete_product_team( $id );

			//Delete Product
			$res = $this->m_products->delete($id);

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
			$status['delete_status']["message"] = 'Cannot delete. This Product is used in '.$check_result['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}

	}//#products_delete


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
	$data['active_page'] 	= 'products';

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

	// Get Product ass and operations
	$data['product_operations'] = $this->m_products->get_product_operations($id);

	// get product team
	$data['product_team'] = $this->m_products->get_product_team($id);
	
	// Get Product Details
	$proj_temp = $this->m_products->get_products($id)['items'];

	if ($proj_temp) 
	{
		$data['product_details'] = $proj_temp[0];
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
	$this->load->view('pages/products/profile', $data);

	// Loading Footer File
	$this->load->view('templates/footer', $data);
	
	return;
}//# product profile




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
   | Upload And save Product Images
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
	$upload_directory = $this->m_config->get_upload_dir();;

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
   | Upload And save Product Images
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

		// Product Name Change Check
		// if it`s The Same Name But With Changed Case
		// No Need For Rules
		if ( $db_values['name'] !== trim($this->put('name')) && $db_name == $put_name )
		{
			$product['name'] = trim($this->put('name')); //*				
		}
		elseif ( $db_values['name'] !== trim($this->put('name')) ) 
		{
			$product['name'] = trim($this->put('name')); //*
			$this->form_validation->set_rules('name', 'Name', 'trim|required|is_unique[mb_products.name]');
		}

		// Check SKU Change
		$db_sku = mb_convert_case( $db_values['sku'], MB_CASE_LOWER, "UTF-8" ); 
		$put_sku = mb_convert_case( trim($this->put('sku')), MB_CASE_LOWER, "UTF-8" );

		if ( $db_values['sku'] !== trim($this->put('sku')) && $db_sku == $put_sku && !empty(trim($this->put('sku'))) )
		{
			$product['sku'] = trim($this->put('sku')); //*				
		}
		elseif ( $db_values['sku'] !== trim($this->put('sku')) ) 
		{
			$product['sku'] = trim($this->put('sku')); //*
			$this->form_validation->set_rules('sku', 'SKU', 'trim|required|max_length[10]|is_unique[mb_products.sku]');
		}
		
		// Check Avatar Change
		$db_avatar = mb_convert_case( $db_values['avatar'], MB_CASE_LOWER, "UTF-8" ); 
		$put_avatar = mb_convert_case( trim($this->put('avatar')), MB_CASE_LOWER, "UTF-8" );

		if ( $db_values['avatar'] !== trim($this->put('avatar')) && $db_avatar == $put_avatar )
		{
			$avatar_loc	 = trim($this->put('avatar_hash'));
			$avatar_name = trim($this->put('avatar_name'));
			$avatar = $this->m_config->arrange_uploads_in_gallery($avatar_loc,$avatar_name);

			if ( $avatar === false ) {
				$avatar = $this->product_avatar_default_image;
			}

			$product['custom_box_avatar'] = $avatar;
		}
		elseif ( $db_values['avatar'] !== trim($this->put('avatar')) ) 
		{
			$this->form_validation->set_rules('avatar_hash', 'Avatar', 'trim|is_unique[mb_products.custom_box_avatar]');
			
			$avatar_loc	 = trim($this->put('avatar_hash'));
			$avatar_name = trim($this->put('avatar_name'));
			$avatar = $this->m_config->arrange_uploads_in_gallery($avatar_loc,$avatar_name);

			if ( $avatar === false ) {
				$avatar = $this->product_avatar_default_image;
			}
			$product['custom_box_avatar'] = $avatar;
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
				$featured_img = $this->product_featured_default_image;
			}

			$product['featured_image'] = $featured_img;
		}
		elseif ( $db_values['featured_image'] !== trim($this->put('featured_image')) ) 
		{
			$this->form_validation->set_rules('featured_image', 'Featured Image', 'trim|is_unique[mb_products.featured_image]');
			
			$featured_img_loc	 = trim($this->put('featured_image_hash'));
			$featured_img_name 	 = trim($this->put('featured_image_name'));
			$featured_img = $this->m_config->arrange_uploads_in_gallery($featured_img_loc,$featured_img_name);

			if ( $featured_img === false ) {
				$featured_img = $this->product_featured_default_image;
			}

			$product['featured_image'] = $featured_img;
		}

		$product['description']		  = trim($this->put('description'));
		$product['last_modified'] 	  = date('Y/m/d H:i:s');
		$product['desert_id']		  = trim($this->put('desert_id'));
		$product['flavor_id']		  = trim($this->put('flavor_id'));
		$product['color_id']		  = trim($this->put('color_id'));
		$product['mmt_id']		 	  = trim($this->put('mmt_id'));
		$product['weight']		 	  = trim($this->put('weight'));
		$product['unit_price']		  = trim($this->put('price'));
		$product['use_in_set']		  = trim($this->put('use_in_set'));
		$product['show_in_gallery']	  = trim($this->put('show_in_gallery'));
		$product['is_active']		  = trim($this->put('is_active'));

		// Setting Validation Rules For Update Operation
						
		$this->form_validation->set_rules('desert_id', 'Desert Type', 'trim|required|numeric' );
		$this->form_validation->set_rules('flavor_id', 'Flavor', 'trim|required|numeric' );
		$this->form_validation->set_rules('color_id', 'Color', 'trim|required|numeric' );
		$this->form_validation->set_rules('mmt_id', 'Currency', 'trim|numeric|required' );
		$this->form_validation->set_rules('weight', 'Weight', 'trim|numeric' );
		$this->form_validation->set_rules('price', 'Price', 'trim|numeric' );
		$this->form_validation->set_rules('use_in_set', 'Use in Set', 'trim|required|in_list[0,1]');
		$this->form_validation->set_rules('show_in_gallery', 'Show In Gallery', 'trim|required|in_list[0,1]' );
		$this->form_validation->set_rules('is_active', 'Active', 'trim|required|in_list[0,1]' );

		return $product;
	}


//End of c_products Class
}
?>