<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_sectors Controller Class For Client Areas Manipulatios
*/
class C_sectors extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_sectors');
		$this->load->model('m_validation');
//		$this->load->model('m_users');
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

	public function index_get()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Sectors check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'repository';
		$data['active_page']  	= 'sectors';

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

		// Loading Sector Create Form
		$data['sec_create_form'] = $this->load->view('pages/sectors/create_form', $data, true);

		// Loading Sectors Main Section (Table Body)
		$this->load->view('pages/sectors/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/sectors/scripts', $data, true);

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
	|Getting Sector List for Ajax Request
	|---------------------------------------------------------------------------------
	*/
	public function sectors_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'repository';
		$data['active_page'] 	= 'sectors';

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

		// Getting sector List from DB
		$data['sector_list'] = $this->m_sectors->get_sectors($id, $getConfig);
		$this->response($data['sector_list']);

	}//#sectors_get

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
	| Creating Sector
	|---------------------------------------------------------------------------------
	*/
	public function sectors_post()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'repository';
		$check['active_page'] 	 = 'sectors';

		// Checking User Session Activity
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

		//Passing Data to Validate
		$this->form_validation->set_data($this->post());

		//Validating Input Fields
		if ( $this->form_validation->run('sec_create') ) 
		{
			if ( $this->post('name') !== FALSE && $this->post('note') !== FALSE ) 
			{	
				$sector['name'] = trim($this->post('name'));
				$sector['note'] = trim($this->post('note'));
				
				// Inseting New Sector
				$res = $this->m_sectors->insert( $sector );
				
				if ( $res ) 
				{
					$sector['id'] = $res;

					$status['create_status']["status"] = 'success';
					$status['create_status']["message"] = $sector;
					$status['create_status']["id"] = $sector['id'];
					//Postback
					$this->response( $status['create_status'], REST_Controller::HTTP_CREATED );
				}
				else
				{
					$status['create_status']["status"] = 'failure';
					$status['create_status']["message"] = 'Insert Error';
					// Postback
					$this->response( $status['create_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
				}
			}
			else
			{
				$status['create_status']["status"] = 'failure';
				$status['create_status']["message"] = 'POST Error';
				// Postback
				$this->response( $status['create_status'], REST_Controller::HTTP_UNPROCESSABLE_ENTITY );
			}
		}
		else
		{
				$status['create_status']["status"] = 'failure';
				$status['create_status']["message"] = validation_errors();
				// Postback
				$this->response( $status['create_status'], REST_Controller::HTTP_BAD_REQUEST );
		}

	}//#sectors_post
	

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
	|Updating Sector
	|---------------------------------------------------------------------------------
	*/
	public function sectors_put($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'repository';
		$check['active_page'] 	 = 'sectors';

		// Checking User Session Activity
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

		$sector = array();
			
		//Passing Data to Validate
		$this->form_validation->set_data( $this->put() );
		
		/*
		|---------------------------------------------------------------------------------
		| Get Sector From DB and Compare With Updatable One
		|---------------------------------------------------------------------------------
		*/
		$dbSectorInfo = $this->m_sectors->get_sectors($id)['items'][0];

		if ( $dbSectorInfo ) 
		{
			// Check For Same Name Change (upper/lower)
			if ( $dbSectorInfo['name'] != trim($this->put('name')) && strcasecmp($dbSectorInfo['name'], trim($this->put('name')))==0 ) 
			{
				$sector['name'] = $this->put('name');
			}
			elseif ( $dbSectorInfo['name'] !== trim($this->put('name')) ) 
			{
				$sector['name'] = $this->put('name');	
					$this->form_validation->set_rules('name','Business Sector', 'trim|required|is_unique[business_sectors.name]');	
			}
			
			// Checking For Sector Note Update
			if ( $this->put('note') !== false ) 
			{
				$sector['note'] = $this->put('note');
				$this->form_validation->set_rules('note','Note', 'trim');
			}
		}
		else
		{
			$status['update_status']["status"] = 'failure';
			$status['update_status']["message"] = 'Invalid Project. Refresh Page and Try Again';
			// Postback
			$this->response( $status['update_status'], REST_Controller::HTTP_BAD_REQUEST );
			return;
		}

		// Validating PUT Values
		if ( $this->form_validation->run() !== false ) 
		{
			$sector['id'] = $id;
				
			// Updatig Sector 
			$res = $this->m_sectors->update( $sector );
				
			if ( $res > 0 )
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
	}//#sectors_put


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
	|Destroying Business Sector
	|---------------------------------------------------------------------------------
	*/
	public function sectors_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$check['active_section'] = 'repository';
		$check['active_page'] 	 = 'sectors';

		// Checking User Session Activity
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

		//Validate After Delete

		$res = $this->m_sectors->delete($id);
		if ( $res['status'] ) 
		{
			$status['delete_status']["status"] = 'success';
			$status['delete_status']["message"] = 'Delete Success.';
			$this->response( $status['delete_status'], REST_Controller::HTTP_OK );
		}
		else
		{
			$status['delete_status']["status"] = 'failure';
			$status['delete_status']["message"] = 'Cannot delete. Sector Exists in table '.$res['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR  );
		}

	}//#sectors_delete




	



//-->END of "c_sectors" Controller class
}
 ?>