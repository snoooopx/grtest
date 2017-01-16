<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';

/**
*  Settings class for site Settings manipulation
*/
class C_settings extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
		$this->load->model('m_settings');
		$this->load->library('form_validation');
	}

	public function index_get()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'system';
		$data['active_page'] 	= 'settings';

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
		// Loading Header File
		$this->load->view('templates/header', $data);
		
		// Getting Sidebar From Session
		$data['sidebar'] = $this->session->userdata('sidebar');

		// Loading Sidebar File
		$this->load->view('templates/sidebar', $data);

		######################################################################################################
		#################################### * End of Permission Check * #####################################
		######################################################################################################

		// get all settings
		$data['settings'] = $this->m_settings->get_all();

		$this->load->model('m_cart');
		// Get Shipping Zones
		$data['shipping']['zones'] = $this->m_cart->get_shipping_zones();
		// Get Shipping Types
		$data['shipping']['types'] = $this->m_cart->get_shipping_types();

		// get Shipping Periods
		$data['shipping']['periods'] = $this->m_cart->get_shipping_periods();


		$this->load->view('pages/settings/main',$data);

		$this->load->view('templates/footer');
	}

	public function save_settings_get()
	{
		redirect('backend/settings');
	}


	/*
	# Save Settings
	*/
	public function save_settings_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'system';
		$data['active_page'] 	= 'settings';

		// Checking User Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$data['allow'] = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** READ PERMISSION CHECK *********###
		############################################	
		if ( $data['allow']['update'] === FALSE )
		{
			$this->response(array('status'=>'failure', 'У вас нет доступа для этой операции!!!'), REST_Controller::HTTP_BAD_REQUEST);
			return;
		}
		
		$updateable = [];
		$wanna_update = false;
		// Check For socail Info Update
		if ( $this->post('submitSocial') !== NULL) 
		{
			($this->post('fb')!==null)? $updateable[0]['name']  = 'fb_account': '';
			($this->post('fb')!==null)? $updateable[0]['value'] = $this->post('fb'): '';
			($this->post('inst')!==null)? $updateable[1]['name'] = 'inst_account': '';
			($this->post('inst')!==null)? $updateable[1]['value'] = $this->post('inst'): '';
			($this->post('twt')!==null)? $updateable[2]['name'] = 'twt_account': '';
			($this->post('twt')!==null)? $updateable[2]['value'] = $this->post('twt'): '';

			$wanna_update = true;
		}

		// Check For Opening Info Update
		if ( $this->post('submitOpeningHours') !== NULL) 
		{
			($this->post('openingHours')!==null)? $updateable[0]['name'] = 'opening_hours': '';
			($this->post('openingHours')!==null)? $updateable[0]['value'] = $this->post('openingHours'): '';
			
			$wanna_update = true;
		}

		// Check For Company Info Update
		if ( $this->post('submitInfo') !== NULL) 
		{
			($this->post('address')!==null)? $updateable[0]['name'] = 'company_address': '';
			($this->post('address')!==null)? $updateable[0]['value'] = $this->post('address'): '';
			($this->post('phone')!==null)? $updateable[1]['name'] = 'company_phone': '';
			($this->post('phone')!==null)? $updateable[1]['value'] = $this->post('phone'): '';
			($this->post('email')!==null)? $updateable[2]['name'] = 'company_email': '';
			($this->post('email')!==null)? $updateable[2]['value'] = $this->post('email'): '';
			($this->post('name')!==null)? $updateable[3]['name'] = 'company_name': '';
			($this->post('name')!==null)? $updateable[3]['value'] = $this->post('name'): '';

			$wanna_update = true;
		}

			// Check For Company Info Update
		if ( $this->post('submitAboutUs') !== NULL) 
		{
			($this->post('aboutUsShort')!==null)? $updateable[0]['name'] = 'company_aboutus_short': '';
			($this->post('aboutUsShort')!==null)? $updateable[0]['value'] = $this->post('aboutUsShort'): '';
			($this->post('aboutUsLong')!==null)? $updateable[1]['name'] = 'company_aboutus_long': '';
			($this->post('aboutUsLong')!==null)? $updateable[1]['value'] = $this->post('aboutUsLong'): '';
			
			$wanna_update = true;
		}

	if ($wanna_update) 
	{
		$res = $this->m_settings->update($updateable);
		if ($res) {
			$this->response(array('status'=>'success','message'=>'update success.'), REST_Controller::HTTP_OK);
		} else {
			$this->response(array('status'=>'failure', 'message'=>'nothing has changed'), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	}



}
?>