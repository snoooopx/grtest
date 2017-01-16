<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|---------------------------------------------------------------------------------
|c_login Controller Class
|---------------------------------------------------------------------------------
*/
class C_login extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_config');
	}

	public function index()
	{
		redirect( site_url('backend/login/check') );
		
		//$this->check();
	}

	/*
	|---------------------------------------------------------------------------------
	|Function for Showing Check Login Page
	|---------------------------------------------------------------------------------
	*/
	public function check()
	{
		$this->load->helper(array('form'));
		$this->load->view('pages/login');
	}

	/*
	|---------------------------------------------------------------------------------
	|Function for Destroying User Session and Logout
	|---------------------------------------------------------------------------------
	*/
	public function logout()
	{
		//Get Logged in User Info From Session
		$userinfo = $this->session->userdata('logged_in');
		$data_txt =  $userinfo['login'] . '- Successfully Logged Out.';

		$this->load->model('m_logger');
		$this->load->model('m_validation');
		$this->m_logger->loggish($data_txt, 'info');
		

		$this->m_validation->destroy_session();

		//Redirect to Login Page
		redirect(site_url('backend/login/check'), 'refresh');
	}
 
}
?>