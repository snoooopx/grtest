<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_company Controller Class for Company Info Manipulation
*/
class C_company extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_company');
		$this->load->model('m_validation');
		$this->load->model('m_users');
		$this->load->library('form_validation');

	}

	/*
	─────────────────────────────────────────────────────────────────
	─██████████─██████──────────██████─██████████████─██████████████─
	─██░░░░░░██─██░░██████████──██░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
	─████░░████─██░░░░░░░░░░██──██░░██─██░░██████████─██░░██████░░██─
	───██░░██───██░░██████░░██──██░░██─██░░██─────────██░░██──██░░██─
	───██░░██───██░░██──██░░██──██░░██─██░░██████████─██░░██──██░░██─
	───██░░██───██░░██──██░░██──██░░██─██░░░░░░░░░░██─██░░██──██░░██─
	───██░░██───██░░██──██░░██──██░░██─██░░██████████─██░░██──██░░██─
	───██░░██───██░░██──██░░██████░░██─██░░██─────────██░░██──██░░██─
	─████░░████─██░░██──██░░░░░░░░░░██─██░░██─────────██░░██████░░██─
	─██░░░░░░██─██░░██──██████████░░██─██░░██─────────██░░░░░░░░░░██─
	─██████████─██████──────────██████─██████─────────██████████████─
	*/
	public function index()
	{

		######################################################################################################
		######################################################################################################
		$data['active_section'] = 'organization';
		$data['active_page'] = 'company';

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

		//Passing Requested Page Permissions For Later Use in View
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


		$data['insert_status']=array();

		//Getting User List { 0- get all users, true- get brief }
		$data['user_list'] = $this->m_users->get_users('0',true)['items'];

		//Getting Company Create Form HTML
		$data['company_create_form_html'] = $this->load->view('pages/company/create_form', $data ,TRUE);

		//Getting Company Info
		$data['company_info'] = $this->m_company->get_company();

		//Getting Company Info HTML
		$data['company_info_html'] = $this->load->view('pages/company/info', $data ,TRUE);

		//$this->load->view('templates/main');
		$this->load->view( 'pages/company/company', $data );

		// Loading Footer File
		$this->load->view('templates/footer', $data);







/*		$this->load->view('templated/header');
		$this->load->view('templated/header');*/
		/*
		* Loading Company View and Passing info
		* If no Company is defined $res is FALSE
		*/
		
	}

	/*
	|---------------------------------------------------------------------------------
	|Get Company Info
	|---------------------------------------------------------------------------------
	*/
	public function show_get()
	{

	}


	/*
	───────────────────────────────────────────────────────────────────────────────────────────────────
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
	*/
	public function insert()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'organization';
		$data['active_page'] = 'company';

		// Checking User Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** CREATE PERMISSION CHECK *********###
		############################################	
		if ( $allow['create'] === FALSE )
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

		//Passing Requested Page Permissions For Later Use in View
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

        $data['insert_status']=array();

        if ( $this->input->post('sbmt_company_create') !== FALSE ) 
        {
			if ( $this->form_validation->run('company') !== FALSE ) 
			{
				//Getting Config For Uploaded Image Thumb Creation
				$upload_config = $this->get_upload_config();

				//Loading Upload Library For File Upload 
		        $this->load->library('upload', $upload_config);

		        if ( !$this->upload->do_upload('companyLogo'))
		        {
		            $data['insert_status']['file_upload_errors'] = $this->upload->display_errors('<p style="color:RED;">','</p>');
		        }
		        else
		        {
		        	$logo_name = $upload_config['file_name'] . $this->upload->data('file_ext');
		            //Collecting uploaded File Full Path
		        	$source_image = $upload_config['upload_path'] . $logo_name;

		        	//Getting Config For Resizing Uploaded File
					$image_config = $this->get_resize_config($source_image);

					//Loading Image Library
					$this->load->library('image_lib', $image_config);

					//Resizing Image
					if ( !$this->image_lib->resize() ) 
					{
						$data['insert_status']['file_resize_errors'] = $this->image_lib->display_errors();
					}
					else
					{
						/*$company_name = $this->input->post('companyName');
						$company_head = $this->input->post('companyHead');
						$company_logo = $logo_name;*/
						
						$res = $this->m_company->insert(array(
															'name' => $this->input->post('companyName'),
															'head_id' => $this->input->post('companyHead'),
															'logo'=> $logo_name
													));
						$data['insert_status']['insert_result'] =$res;
					}
		        }
			}
		}

    	//Getting User List { 0- get all users, true- get brief info about user }
		$data['user_list'] = $this->m_users->get_users('0',true)['items'];
		
		//Getting Company Info
		$data['company_info'] = $this->m_company->get_company();
		
		//Loading Error Content
		$data['permission_550'] = $this->load->view('errors/error_550', '', TRUE);

		//Getting Company Create Form HTML
		$data['company_create_form_html'] = $this->load->view('pages/company/create_form', $data ,TRUE);

		//Getting Company Info HTML
		$data['company_info_html'] = $this->load->view('pages/company/info', $data ,TRUE);

		//$this->load->view('templates/main');
		$this->load->view( 'pages/company/company', $data );

		// Loading Footer File
		$this->load->view('templates/footer', $data);
	}


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
	|Update Company
	|---------------------------------------------------------------------------------
	*/
	public function update_put()
	{
		
	}




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
	|Destroy Company
	|---------------------------------------------------------------------------------
	*/
	public function destroy_delete($id='0')
	{
		
	}

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
		$img_location = 'application/assets/img/logo/';

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
		$config['create_thumb'] 	= FALSE;
		$config['maintain_ratio'] 	= TRUE;
		//$config['width']         	= 215;
		//$config['height']       	= 215;

		return $config;
	}


//-->End of c_company Class
}
 ?>