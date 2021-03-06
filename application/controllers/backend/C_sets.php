<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* C_sets Controller Class for sets Manipulation
*/
class C_sets extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_sets');
		$this->load->model('m_config');
		$this->load->model('m_validation');
		$this->load->library('form_validation');
	}

	
	private $set_featured_default_image	= 'bdc52e9f0197ed9d052b444891085cbf.png';

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
		$data['active_page'] 	= 'sets';

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

		// Loading set Create Form
		//$data['set_create_form'] = $this->load->view('pages/sets/create_form', $data, true);

		// Loading set Main Section 
		$this->load->view('pages/sets/main', $data);

		$data['gallery_directory'] = $this->m_config->get_gallery_dir();
		
		$data['upload_directory'] = $this->m_config->get_upload_dir();
		
		$data['featured_default_image'] = $this->set_featured_default_image;

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/sets/scripts', $data, true);

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
|Getting set List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function sets_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'catalog';
		$data['active_page'] 	= 'sets';

		// Checking set Session Activity
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
		|  Getting set List from DB
		|  3rd Parameter Config for pagination
		|  2nd Parameter brief(FALSE) For Full Columns
		*/
		$data['set_list'] = $this->m_sets->get_sets($id, $getConfig);
		$this->response($data['set_list']);

	}//#sets_get


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
	Create Set Page
	*/
	public function set_actions_get($action=false,$param_id=false)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'catalog';
		$data['active_page'] 	= 'sets';

		// Checking set Session Activity
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
		//Get Desert Type List
		$this->load->model('m_deserts');
		$data['desert_type_list'] = $this->m_deserts->get_deserts()['items'];

		$data['gallery_directory'] = $this->m_config->get_gallery_dir();
		
		$data['upload_directory'] = $this->m_config->get_upload_dir();
		
		$data['featured_default_image'] = $this->set_featured_default_image;


		$this->load->view('pages/sets/create',$data);
		// Loading Scripts ( Modals/Buttons...)
		$data['upload_template'] = $this->load->view('templates/upload_template',$data,true);
		$data['scripts'] = $this->load->view('pages/sets/scripts', $data, true);
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
	/*
	|---------------------------------------------------------------------------------
	|Insert set
	|---------------------------------------------------------------------------------
	*/
	public function sets_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'catalog';
		$data['active_page'] 	 = 'sets';

		// Checking set Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$data['allow'] = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** CREATE PERMISSION CHECK *********###
		############################################	
		if ( $data['allow']['create'] === FALSE )
		{
			$status['create_status']["status"] = '403';
			$status['create_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
			$this->response($status['create_status']);
			return;
		}
		#########################################################################
		###****** End of Permission Check * #####################################
		#########################################################################

		// CHECK POST VALUES
		if ( $this->post('info') !== false && $this->post('items') !== false && $this->post('attrs') !== false ) 
		{
			$info  = $this->post('info');
			$items = $this->post('items');
			$attrs = $this->post('attrs');

			if (isset($info['set_id'])) 
			{
				//action is update
				//get set from db and check
				$res = $this->m_sets->get_sets($info['set_id']);
				
				if (isset($res['items'][0])) 
				{
					// Check and set validation rules for updatable set
					// Return updatables array
					$updatables = $this->check_and_init( $res['items'][0], $info);

					$this->form_validation->set_data($updatables);
					//Validate
					if ( $this->form_validation->run() !== false ) 
					{
						// Delete Items
						$this->m_sets->delete_set_items($info['set_id']);
						// Delete Attributes
						$this->m_sets->delete_set_attributes($info['set_id']);
						// Collect items for insert
						$items_insert = [];
						foreach ($items as $item) {
							$temp=[];
							$temp['set_id'] 	= $info['set_id'];
							$temp['item_id'] 	= $item['id'];
							$temp['qty'] 		= $item['qty'];
							$items_insert[]		= $temp;
						}

						// Insert Set Items
						$res_items = $this->m_sets->insert_set_items($items_insert);
						if ( $res_items==false ) {
							$insert_status["status"]  = 'failure';
							$insert_status["message"] = 'Ошибка добавления десертов.';
							$this->response($insert_status,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
						}
						
						//Collect Attributes for insert
						$attrs_insert =[];
						foreach ($attrs as $attr) {
							$temp = [];
							$temp['set_id']	 	= $info['set_id'];
							$temp['attr_id'] 	= $attr['id'];
							$temp['unit_price'] = $attr['price'];
							$attrs_insert[] 	= $temp;
						}
						// Insert Set Attributes
						$res_attrs = $this->m_sets->insert_set_attrs($attrs_insert);

						if ( $res_attrs == false ) {
							$insert_status["status"]  = 'failure';
							$insert_status["message"] = 'Ошибка добавления Аттрибутов.';
							$this->response($insert_status,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);	
						}

						$updatables['id'] = $info['set_id'];
						// update set
						$update_res = $this->m_sets->update($updatables);
						
						/* Check Results */
						if ( $update_res ) {
							$status['update_status']["status"] = 'success';
							$status['update_status']["message"] = 'Update Success';
							//Postback
							$this->response( $status['update_status'], REST_Controller::HTTP_OK );
						} else {
							$status['update_status']["status"] = 'failure';
							$status['update_status']["message"] = "Update Failed";
							//Postback
							$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
							return;
						}
					} // Return validation errors
					else 
					{
						$insert_status["status"] 	= 'failure';
						$insert_status["message"] = validation_errors();
						$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
						return;
					}
				}
			}
			else
			{
				// Set data to validate
				$this->form_validation->set_data($info);
				// Validate
				if ( $this->form_validation->run('set_create_info') !== false ) 
				{
					// Validate Items Table
					if (!empty($items)) 
					{
						$item_qty = 0;
						foreach ($items as $item) {
							$item_qty+=$item['qty'];
						}

						if ($info['type'] == 'static') 
						{
							if ( $item_qty != $info['count'] ) {
								$insert_status["status"]  = 'failure';
								$insert_status["message"] = 'Сумарное количество десертов ('.$item_qty.') не соответствует обявненной (' . $info['count'].')';
								$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
								return;
							}
						}
					}
					else
					{
						$insert_status["status"]  = 'failure';
						$insert_status["message"] = 'Таблица Десертов пуста.';
						$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
						return;
					}


					// Validate Attributes Table
					if (empty($attrs)) {
						$insert_status["status"]  = 'failure';
						$insert_status["message"] = 'Таблица Аттрибутов пуста.';
						$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
						return;
					}

					// Collect And Fill Info Array for Insert
					$featured_img_loc	 = trim($info['featured_image_hash']);
					$featured_img_name 	 = trim($info['featured_image_name']);
					$featured_img 		 = $this->m_config->arrange_uploads_in_gallery( $featured_img_loc, $featured_img_name );

					if ( $featured_img === false ) {
						$featured_img = $this->set_featured_default_image;
					}
					date_default_timezone_set('Asia/Yerevan');
					$info_insert = [];
					$info_insert['created'] 			= date("Y-m-d H:i");
					$info_insert['created_by']			= $data['userinfo']['id'];
					$info_insert['name'] 				= trim($info['name']);
					$info_insert['sku'] 				= trim($info['sku']);
					$info_insert['defined_count'] 		= trim($info['count']);
					$info_insert['price'] 				= trim($info['price']);
					$info_insert['mmt_id'] 				= trim($info['mmt_id']);
					$info_insert['description'] 		= trim($info['description']);
					$info_insert['type'] 				= trim($info['type']);
					$info_insert['in_desert_page']		= trim($info['in_desert_page']);
					$info_insert['featured_image'] 		= $featured_img;
					$info_insert['is_enabled'] 			= trim($info['is_enabled']);
					$info_insert['is_new'] 				= trim($info['is_new']);
				
					// Insert Set Info
					$set_id = $this->m_sets->insert_set_info($info_insert);
					//Check Info Insert
					if ( $set_id !== false) 
					{
						// Collect items for insert
						$items_insert = [];
						foreach ($items as $item) {
							$temp=[];
							$temp['set_id'] 	= $set_id;
							$temp['item_id'] 	= $item['id'];
							$temp['qty'] 		= $item['qty'];
							$items_insert[]		= $temp;
						}


						// Insert Set Items
						$res_items = $this->m_sets->insert_set_items($items_insert);
						if ( $res_items==false ) {
							$insert_status["status"]  = 'failure';
							$insert_status["message"] = 'Ошибка добавления десертов.';
							$this->response($insert_status,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
						}
						
						//Collect Attributes for insert
						$attrs_insert =[];
						foreach ($attrs as $attr) {
							$temp = [];
							$temp['set_id']	 	=  $set_id;
							$temp['attr_id'] 	= $attr['id'];
							$temp['unit_price'] = $attr['price'];
							$attrs_insert[] 	= $temp;
						}
						// Insert Set Attributes
						$res_attrs = $this->m_sets->insert_set_attrs($attrs_insert);

						if ( $res_attrs == false ) {
							$insert_status["status"]  = 'failure';
							$insert_status["message"] = 'Ошибка добавления Аттрибутов.';
							$this->response($insert_status,REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
							return;
						}

						//Generate Success Message
						$insert_status['status']  = 'success';
						$insert_status['message'] = 'Набор '.$info['name'].' Created Successfully';
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
		}
		else
		{
			$insert_status["status"] 	= 'failure';
			$insert_status["message"] = 'POST Error.';
			$this->response( $insert_status, REST_Controller::HTTP_BAD_REQUEST );
		}

		
	}//#sets_post

	

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
	|Destroying set
	|---------------------------------------------------------------------------------
	*/
	public function sets_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'catalog';
		$data['active_page']	 = 'sets';

		// Checking set Session Activity
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
			$this->response($status['delete_status']);
			return;
		}

		// Check Client existence in another tables
		$check_result = $this->m_sets->check_before_delete($id);

		// Check Delete and Response
		if ( $check_result['status'] ) 
		{
			//Delete set items
			$this->m_sets->delete_set_items($id);
			//Delete set attrs
			$this->m_sets->delete_set_attributes($id);
			//Delete set
			$res = $this->m_sets->delete($id);

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
			$status['delete_status']["message"] = 'Cannot delete. set Exists in some tables '.$check_result['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}
	}//#sets_delete


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
| Get full set
|
*/
public function get_full_get($id=false)
{
	if ( $this->get('action')!==null && $this->get('id') !== null ) 
	{
		if ($this->get('action') == 'e') 
		{
			if (isset($this->m_sets->get_sets($this->get('id'))['items'][0])) 
			{
				// get set
				$set_info = $this->m_sets->get_sets($this->get('id'))['items'][0];
			}
			else
			{
				$set_info = 'undefined';
			}

			// get set items
			$set_items = $this->m_sets->get_set_items($this->get('id'));
			// get set attrs	
			$set_attrs = $this->m_sets->get_set_attrs($this->get('id'));
			$resp = array(
							'status'=>'success',
							'attrs'=>$set_attrs,
							'info'=>$set_info,
							'items'=>$set_items
							);

			$this->response($resp, REST_Controller::HTTP_OK);
		}
		
	}
}



/*
CHECK getted values with db values and SET rules for validating
INIT array for insert 
return __> full insertable array
*/

protected function check_and_init($db_values,$checkable_values)
	{
		// Check For Same Name Change (upper/lower)
		$db_name = mb_convert_case( $db_values['name'], MB_CASE_LOWER, "UTF-8" ); 
		$post_name = mb_convert_case( trim($checkable_values['name']), MB_CASE_LOWER, "UTF-8" );

		// Set Name Change Check
		// if it`s The Same Name But With Changed Case
		// No Need For Rules
		if ( $db_values['name'] !== trim($checkable_values['name']) && $db_name == $post_name )
		{
			$set['name'] = trim($checkable_values['name']); //*				
		}
		elseif ( $db_values['name'] !== trim($checkable_values['name']) ) 
		{
			$set['name'] = trim($checkable_values['name']); //*
			$this->form_validation->set_rules('name', 'Название', 'trim|required|is_unique[mb_sets.name]');
		}

		// Check SKU Change
		$db_sku = mb_convert_case( $db_values['sku'], MB_CASE_LOWER, "UTF-8" ); 
		$post_sku = mb_convert_case( trim($checkable_values['sku']), MB_CASE_LOWER, "UTF-8" );

		if ( $db_values['sku'] !== trim($checkable_values['sku']) && $db_sku == $post_sku && !empty(trim($checkable_values['sku'])) )
		{
			$set['sku'] = trim($checkable_values['sku']); //*				
		}
		elseif ( $db_values['sku'] !== trim($checkable_values['sku']) ) 
		{
			$set['sku'] = trim($checkable_values['sku']); //*
			$this->form_validation->set_rules('sku', 'SKU', 'trim|required|max_length[14]|is_unique[mb_sets.sku]');
		}
		
		// Check Featured Image Change
		$db_featured_image = mb_convert_case( $db_values['featured_image'], MB_CASE_LOWER, "UTF-8" ); 
		$post_featured_image = mb_convert_case( trim($checkable_values['featured_image']), MB_CASE_LOWER, "UTF-8" );

		if ( $db_values['featured_image'] !== trim($checkable_values['featured_image']) && $db_featured_image == $post_featured_image )
		{
			$featured_img_loc	 = trim($checkable_values['featured_image_hash']);
			$featured_img_name 	 = trim($checkable_values['featured_image_name']);
			$featured_img = $this->m_config->arrange_uploads_in_gallery($featured_img_loc,$featured_img_name);

			if ( $featured_img === false ) {
				$featured_img = $this->set_featured_default_image;
			}

			$set['featured_image'] = $featured_img;
		}
		elseif ( $db_values['featured_image'] !== trim($checkable_values['featured_image']) ) 
		{
			$this->form_validation->set_rules('featured_image', 'Главнoe изображение', 'trim|is_unique[mb_sets.featured_image]');
			
			$featured_img_loc	 = trim($checkable_values['featured_image_hash']);
			$featured_img_name 	 = trim($checkable_values['featured_image_name']);
			$featured_img = $this->m_config->arrange_uploads_in_gallery($featured_img_loc,$featured_img_name);

			if ( $featured_img === false ) {
				$featured_img = $this->set_featured_default_image;
			}

			$set['featured_image'] = $featured_img;
		}

		$set['description']		  = trim($checkable_values['description']);
		$set['modified'] 	  	  = date('Y/m/d H:i:s');
		$set['modified_by']	  	  = $this->session->userdata('logged_in')['id'];
		$set['defined_count']	  = trim($checkable_values['count']);
		$set['price']		 	  = trim($checkable_values['price']);
		$set['mmt_id']		 	  = trim($checkable_values['mmt_id']);
		$set['type']		 	  = trim($checkable_values['type']);
		$set['in_desert_page']	  = trim($checkable_values['in_desert_page']);
		$set['is_enabled']		  = trim($checkable_values['is_enabled']);
		$set['is_new']		  	  = trim($checkable_values['is_new']);
		
		// Setting Validation Rules For Update Operation
						
		$this->form_validation->set_rules('type', 'Тип', 'trim|required|in_list[static,custom]' );
		$this->form_validation->set_rules('in_desert_page', 'Показать в.', 'trim|required|numeric' );
		$this->form_validation->set_rules('mmt_id', 'Валюта', 'trim|required|numeric' );
		$this->form_validation->set_rules('defined_count', 'Кол. Дес.(шт.)', 'trim|required|numeric' );
		$this->form_validation->set_rules('price', 'Цена', 'trim|required|numeric|greater_than[0]' );
		$this->form_validation->set_rules('is_enabled', 'Активно', 'trim|required|in_list[0,1]' );
		$this->form_validation->set_rules('is_new', 'Новинка', 'trim|required|in_list[0,1]' );

		return $set;
	}




//End of C_sets Class
}
?>
