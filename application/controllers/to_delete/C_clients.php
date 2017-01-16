<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_clients Controller Class for Client Manipulation
*/
class C_clients extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_clients');
		$this->load->model('m_departments');
		$this->load->model('m_sectors');
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
		$data['active_section'] = 'clients_all';
		$data['active_page'] 	= 'clients';

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

		
		// Get Business Type List/Organizational Type
		//$data['btype_list'] = $this->m_config->get_business_types();

		// Get Department List
		$this->load->model('m_departments');
		$data['department_list'] = $this->m_departments->get_departments();

		// Get Sector List
		$this->load->model('m_sectors');
		$data['sector_list'] = $this->m_sectors->get_sectors();

		// Loading Client Create Form
		$data['client_create_form'] = $this->load->view('pages/clients/create_form', $data, true);

		// Loading Client Main Section 
		$this->load->view('pages/clients/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/clients/scripts', $data, true);

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
|Getting Client List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function clients_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'clients_all';
		$data['active_page'] 	= 'clients';

		// Checking Client Session Activity
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
		|  Getting Client List from DB
		|  3rd Parameter Config for pagination
		|  2nd Parameter brief(FALSE) For Full Columns
		*/
		$data['client_list'] = $this->m_clients->get_clients($id, false, $getConfig);
		$this->response($data['client_list']);

	}//#clients_get


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
	|Insert Client
	|---------------------------------------------------------------------------------
	*/
	public function clients_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'clients_all';
		$check['active_page'] 	 = 'clients';

		// Checking Client Session Activity
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
		$client = [];
		// Passing Data to Validate
		$this->form_validation->set_data( $this->post() );
//$this->response( $this->post() );
		// Validate Posted Values for Client Create
		if ( $this->form_validation->run('client_create')) 
		{
			$client['name']			 	 = trim($this->post('name')); //*
			$client['abbr']			  	 = trim($this->post('abbr'));
			$client['contact_person'] 	 = trim($this->post('contact_person'));
			$client['email']			 = trim($this->post('email'));//*
			$client['phone']			 = trim($this->post('phone'));//*
			$client['address']		 	 = trim($this->post('address'));
			$client['bank_acc']		  	 = trim($this->post('bank_acc'));
			//$client['btype_id']		 	 = $this->post('btypeId');//*
			$client['reg_num']		  	 = trim($this->post('reg_num'));//*
			$client['tin']			  	 = trim($this->post('tin'));//*
			$client['is_visible']		 = trim($this->post('is_visible'));
			$departmentsIds 			 = $this->post('departmentsIds');
			$sectorsIds			 	 	 = $this->post('sectorsIds');			


			//Insert New Client
			$res = $this->m_clients->insert($client);

			if ( $res ) 
				{
					//Newly Created Client id
					$client['id'] = $res;
					
					// Check And Insert Department Clients
					if ( !empty($departmentsIds) ) 
					{
						$deps_array = [];
						foreach ( $departmentsIds as $key=>$value )
						{
							$deps_array[$key]['client_id']  = $client['id'];
							$deps_array[$key]['dep_id'] 	= $value;
						}
						// Insert Dep Clients
						$res_dep_clients = $this->m_departments->insert_dep_clients( $deps_array );
						
						if ( $res_dep_clients ) 
						{
							$res_dep_clients_status = "";
						}
						else
						{
							$res_dep_clients_status = "<p>Error With Departments</p>"	;
						}
					}

					// Check And Insert Client Sections
					if ( !empty($sectorsIds) ) 
					{
						$secs_array = [];
						foreach ( $sectorsIds as $key=>$value )
						{
							$secs_array[$key]['client_id']  = $client['id'];
							$secs_array[$key]['sector_id']	= $value;
						}
						// Insert Sec Clients
						$res_sec_clients = $this->m_sectors->insert_client_sectors( $secs_array );
						
						if ( $res_sec_clients ) 
						{
							$res_sec_clients_status = "";
						}
						else
						{
							$res_sec_clients_status = "<p>Error With Sectors</p>"	;
						}
					}


					$status['create_status']["status"] 	= 'success';
					$status['create_status']["message"] = "Client Created." . $res_dep_clients_status . " " . $res_sec_clients_status;
					$status['create_status']["id"] 		= $client['id'];
					
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

		
	}//#clients_post

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
	|Update Client Info
	|---------------------------------------------------------------------------------
	*/
	public function clients_put($id='0')
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'clients_all';
		$check['active_page'] 	 = 'clients';

		// Checking Client Session Activity
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

		$client = array();
		
		
		//Passing Data to Validate
		$this->form_validation->set_data( $this->put() );

		/*
		|---------------------------------------------------------------------------------
		| Get Client From DB and Check With Updateable One
		|---------------------------------------------------------------------------------
		*/
		$dbClientInfo = $this->m_clients->get_clients($id)['items'][0];

		if ( $dbClientInfo ) 
		{

			// Client Name Change Check
			if ( $dbClientInfo['name'] != trim($this->put('name')) && strcasecmp( $dbClientInfo['name'], trim($this->put('name')))==0 ) 
			{
				$client['name'] = trim($this->put('name')); //*				
			}
			elseif( $dbClientInfo['name'] !== trim($this->put('name')) )
			{
				$client['name'] = trim($this->put('name')); //*
				$this->form_validation->set_rules('name', 'Name', 'trim|required|is_unique[clients.name]');
			}

			// Client Abbr Change Check
			if ( $dbClientInfo['abbr'] != trim($this->put('abbr')) && strcasecmp( $dbClientInfo['abbr'], trim($this->put('abbr')))==0 ) 
			{
				$client['abbr'] = trim($this->put('abbr')); //*				
			}
			elseif( $dbClientInfo['abbr'] !== trim($this->put('abbr')) )
			{
				$client['abbr'] = trim($this->put('abbr')); //*
				$this->form_validation->set_rules('abbr', 'Abbreviation', 'trim|alpha_numeric|required|is_unique[clients.abbr]');
			}

			$are_deps_changed = false;
			// Make Dep Ids String an Array
			$db_deps_arr = explode(',' , $dbClientInfo['dep_ids_str']);
			
			// Client Dep Change Check
			if ( $db_deps_arr != $this->put('departmentsIds') ) 
			{
				$client['dep_ids'] = $this->put('departmentsIds'); //*
				$this->form_validation->set_rules('departmentsIds[]', 'Departments', 'trim|numeric|required');
			}

			$are_secs_changed = false;
			// Make Sec Ids String an Array
			$db_secs_arr = explode(',' , $dbClientInfo['sec_ids_str']);

			// Client Sec Change Check
			if ( $db_secs_arr != $this->put('sectorsIds') ) 
			{
				$client['sec_ids'] = $this->put('sectorsIds'); //*
				$this->form_validation->set_rules('sectorsIds[]', 'Sectors', 'trim|numeric|required');
			}
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Invalid Client. Refresh Page and Try Again';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}

			

		$client['contact_person'] 	= trim($this->put('contact_person'));
		$client['email'] 		 	= trim($this->put('email'));
		$client['phone'] 		 	= trim($this->put('phone'));
		$client['address'] 	 		= trim($this->put('address'));
		$client['bank_acc'] 	 	= trim($this->put('bank_acc'));
		//$client['btype_id'] 		= trim($this->put('btypeId'));//*
		$client['reg_num'] 	 		= trim($this->put('reg_num'));
		$client['tin'] 	 			= trim($this->put('tin'));
		$client['is_visible'] 		= trim($this->put('is_visible'));//*


		// Setting Validation Rules For Update Operation
		$this->form_validation->set_rules('contact_person', 'Contact Person', 'trim|alpha_numeric_spaces');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email');
		$this->form_validation->set_rules('phone', 'Phone', 'trim');
		$this->form_validation->set_rules('address', 'Address',	'trim');
		$this->form_validation->set_rules('bank_acc', 'Bank Account', 'alpha_dash');
		//$this->form_validation->set_rules('btype', 	'Organizational Type',  'trim');
		$this->form_validation->set_rules('reg_num', 'Reg Num',	'trim');
		$this->form_validation->set_rules('tin', 'Tax Code', 'trim|alpha_numeric|exact_length[8]');
		$this->form_validation->set_rules('is_visible', 'Visibility', 'trim|numeric|in_list[0,1]');	
		
		// Validating PUT Values
		if ( $this->form_validation->run() !== FALSE ) 
		{
			$client['id'] = $id;
			
			/*
			|---------------------------------------------------------------------------------
			| Check Client Deps Select Options Change
			|---------------------------------------------------------------------------------
			*/
			if ( isset($client['dep_ids']) ) 
			{
				// Generating Client Departments Insert Array
				$client_deps_arr = [];
				
				foreach ( $client['dep_ids'] as $key=>$value ) 
				{
					$client_deps_arr[$key]['client_id'] = $client['id'];
					$client_deps_arr[$key]['dep_id']    = $value;
				}
				unset($client['dep_ids']);
				// Delete Client Departments
				// First value $dep_id=false, Second val $client_id=false
				$res_del_dep = $this->m_departments->delete_dep_clients(false, $client['id']);
				/*var_dump($res);
				die;*/
				if ( $res_del_dep || $res_del_dep == 0 ) 
				{
					/*print_r($client_deps_arr);
					die;*/
					$res_ins_deps = $this->m_departments->insert_dep_clients($client_deps_arr);
					if ( $res_ins_deps ) 
					{
						$are_deps_changed = true;
					}
					else
					{
						$status['update_status']["status"] = 'failure';
						$status['update_status']["message"] = "Error Changing Client Departments (B)";
						//Postback
						$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
					}
				}
				else
				{
					$status['update_status']["status"] = 'failure';
					$status['update_status']["message"] = "Error Changing Client Departments (A)";
					//Postback
					$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
				}
			}


			/*
			|---------------------------------------------------------------------------------
			| Check Client Sectors select Options Change
			|---------------------------------------------------------------------------------
			*/
			if ( isset($client['sec_ids']) ) 
			{
				// Generating Client Sectors Insert Array
				$client_secs_arr = [];
				
				foreach ( $client['sec_ids'] as $key=>$value ) 
				{
					$client_secs_arr[$key]['client_id'] = $client['id'];
					$client_secs_arr[$key]['sector_id'] = $value;
				}
				unset($client['sec_ids']);
				// Delete Client Sectors
				// First value $dep_id=false, Second val $client_id=false
				$res_del_sec = $this->m_sectors->delete_client_sectors(false, $client['id']);

				if ( $res_del_sec || $res_del_sec==0 ) 
				{
					/*print_r($client_secs_arr);
					die;*/
					$res_ins_secs = $this->m_sectors->insert_client_sectors($client_secs_arr);
					if ( $res_ins_secs ) 
					{
						$are_secs_changed = true;
					}
					else
					{
						$status['update_status']["status"] = 'failure';
						$status['update_status']["message"] = "Error Chnaging Client Sectors (B)";
						//Postback
						$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
					}
				}
				else
				{
					$status['update_status']["status"] = 'failure';
					$status['update_status']["message"] = "Error Chnaging Client Sectors (A)";
					//Postback
					$this->response( $status['update_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
				}
			}


			/*
			|---------------------------------------------------------------------------------
			| Update Client
			|---------------------------------------------------------------------------------
			*/ 
			$res = $this->m_clients->update( $client );
			

			/*
			// Check Results
			*/

			if ( $res || $are_deps_changed || $are_secs_changed)
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
	}//#client_put

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
	|Destroying Client
	|---------------------------------------------------------------------------------
	*/
	public function clients_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'clients_all';
		$check['active_page']	 = 'clients';

		// Checking Client Session Activity
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
		$check_result = $this->m_clients->check_before_delete($id);

		// Check Delete and Response
		if ( $check_result['status'] ) 
		{
			// Delete Client from dep_clients
			$res_del_dep = $this->m_departments->delete_dep_clients( false, $id );

			// Delete Client from client_sectors
			$res_del_sec = $this->m_sectors->delete_client_sectors( false, $id );

			//Delete Client
			$res = $this->m_clients->delete($id);

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
			$status['delete_status']["message"] = 'Cannot delete. Client Exists in some tables '.$check_result['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}

	}//#clients_delete


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
	/*
	|---------------------------------------------------------------------------------
	| //Permission check Section
	|---------------------------------------------------------------------------------
	*/
	$data['active_section'] = 'clients_all';
	$data['active_page'] 	= 'clients';

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

	// Get Cliet Profile
	$data['client_profile'] = $this->m_clients->get_clients($id)['items'][0];

	// Load Profile View And Pass Data
	$this->load->view('pages/clients/profile', $data);

	// Loading Footer File
	$this->load->view('templates/footer', $data);
	
	return;
}//# client profile

//End of c_clients Class
}
?>