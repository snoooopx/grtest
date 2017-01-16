<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class C_site extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->site_get();
	}
/*
|---------------------------------------------------------------------------------
|site Function for Showing Some Significant or Not so Much Significant info
|---------------------------------------------------------------------------------
*/
	function site_get()
	{
		// Loading Header File
		$this->load->view('frontend/pages/unser_construction.php');
	}
}
 ?>