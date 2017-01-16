<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class C_dashboard extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("m_validation");
	}

	function index()
	{
		$this->dashboard_get();
	}
/*
|---------------------------------------------------------------------------------
|Dashboard Function for Showing Some Significant or Not so Much Significant info
|---------------------------------------------------------------------------------
*/
	function dashboard_get()
	{
		######################################################################################################
		######################################################################################################
		$data['active_section'] = 'dashboard';
		$data['active_page'] = 'dashboard';

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
		/*$this->load->helper('ts');
		$data['notify'] = get_notifications_helper($data['userinfo']['id']);*/

		// Loading Header File
		$this->load->view('templates/header', $data);

		// Getting Sidebar From Session
		$data['sidebar'] = $this->session->userdata('sidebar');

		// Loading Sidebar File
		$this->load->view('templates/sidebar', $data);

		######################################################################################################
		#######################################################################################################

		//$this->load->model('m_timesheets');

		// Get Recent Activity
		//$data['recent_activity'] = $this->m_timesheets->get_ts_history(false,$data['userinfo']['id']);

		// Load Main Section
		$this->load->view('pages/dashboard/main', $data);


		// Loading Footer File
		$this->load->view('templates/footer', $data);
	}
}
 ?>