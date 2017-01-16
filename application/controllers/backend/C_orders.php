<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* C_orders Controller Class for orders Manipulation
*/
class C_orders extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_orders');
		$this->load->model('m_config');
		$this->load->model('m_orders');
		$this->load->model('m_validation');
		$this->load->library('form_validation');
	}

	
	private $order_featured_default_image	= 'bdc52e9f0197ed9d052b444891085cbf.png';

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
		$data['active_page'] 	= 'orders';

		// Checking User Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$data['allow'] = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		######################################################################################################
		###############################****** READ PERMISSION CHECK *********#################################
		######################################################################################################
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

		######################################################################################################
		#################################### * End of Permission Check * #####################################
		######################################################################################################
		// Load TS Helper For Getting missed Timesheets
		/*$this->load->helper('ts');
		$data['notify'] = get_notifications_helper($data['userinfo']['id']);*/
		
		// Loading Header File
		$this->load->view('templates/header', $data);
		
		// Getting Sidebar From Session
		$data['sidebar'] = $this->session->userdata('sidebar');

		// Loading Sidebar File
		$this->load->view('templates/sidebar', $data);


		// Loading order Create Form
		//$data['order_create_form'] = $this->load->view('pages/orders/create_form', $data, true);

		// Loading order Main Section 
		$this->load->view('pages/orders/main', $data);

		$data['gallery_directory'] = $this->m_config->get_gallery_dir();
		
		$data['upload_directory'] = $this->m_config->get_upload_dir();
		
		$data['featured_default_image'] = $this->order_featured_default_image;

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/orders/scripts', $data, true);

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
|Getting order List for Ajax Request
|---------------------------------------------------------------------------------
*/
	public function orders_get($id=0)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'orders_all';
		$data['active_page'] 	= 'orders';

		// Checking order Session Activity
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
		|  Getting order List from DB
		|  3rd Parameter Config for pagination
		|  2nd Parameter brief(FALSE) For Full Columns
		*/
		$data['order_list'] = $this->m_orders->get_orders($id, $getConfig);
		$this->response($data['order_list']);

	}//#orders_get



/*──────────────────────────────────────────────────────────────────────────────────────────────────────
─████████████───██████████████─██████████████─██████████████─██████████─██████─────────██████████████─
─██░░░░░░░░████─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─██░░░░░░██─██░░██─────────██░░░░░░░░░░██─
─██░░████░░░░██─██░░██████████─██████░░██████─██░░██████░░██─████░░████─██░░██─────────██░░██████████─
─██░░██──██░░██─██░░██─────────────██░░██─────██░░██──██░░██───██░░██───██░░██─────────██░░██─────────
─██░░██──██░░██─██░░██████████─────██░░██─────██░░██████░░██───██░░██───██░░██─────────██░░██████████─
─██░░██──██░░██─██░░░░░░░░░░██─────██░░██─────██░░░░░░░░░░██───██░░██───██░░██─────────██░░░░░░░░░░██─
─██░░██──██░░██─██░░██████████─────██░░██─────██░░██████░░██───██░░██───██░░██─────────██████████░░██─
─██░░██──██░░██─██░░██─────────────██░░██─────██░░██──██░░██───██░░██───██░░██─────────────────██░░██─
─██░░████░░░░██─██░░██████████─────██░░██─────██░░██──██░░██─████░░████─██░░██████████─██████████░░██─
─██░░░░░░░░████─██░░░░░░░░░░██─────██░░██─────██░░██──██░░██─██░░░░░░██─██░░░░░░░░░░██─██░░░░░░░░░░██─
─████████████───██████████████─────██████─────██████──██████─██████████─██████████████─██████████████─
──────────────────────────────────────────────────────────────────────────────────────────────────────
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
//order details page

public function details_get($order_id)
{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'orders_all';
		$data['active_page'] 	= 'orders';

		// Checking User Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$data['allow'] = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** READ PERMISSION CHECK *********###
		############################################	
		if ( $data['allow']['update'] === FALSE )
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
		######################################################################################################
		#################################### * End of Permission Check * #####################################
		######################################################################################################

		// // Load TS Helper For Getting missed Timesheets
		// $this->load->helper('ts');
		// $data['notify'] = get_notifications_helper($data['userinfo']['id']);
		
		// Loading Header File
		$this->load->view('templates/header', $data);
		// Getting Sidebar From Session
		$data['sidebar'] = $this->session->userdata('sidebar');
		// Loading Sidebar File
		$this->load->view('templates/sidebar', $data);

		// Get Order
		$data['order'] = $this->m_orders->get_full_order($order_id);
		if (!isset($data['order']['info'][0]) && empty($data['order']['info'][0])) 
		{
			redirect('backend/orders');
			return;
		}
		//Get Order Statuses
		$data['order_statuses'] = $this->m_orders->get_order_statuses();
		
		//Get Payment Statuses
		$data['pmt_statuses'] = $this->m_orders->get_pmt_statuses();
	
		//Get Order History
		$data['order_history'] = $this->m_orders->get_history($order_id);
	
		$this->load->view('pages/orders/details', $data);
		$this->load->view('templates/footer', $data);
}

	
/////////////////////////////////////////////////////////////////////////////////// 	
// 	Change Order Status
///////////////////////////////////////////////////////////////////////////////////
public function change_order_status_post()
{
	/*
	|---------------------------------------------------------------------------------
	| //Permission check Section
	|---------------------------------------------------------------------------------
	*/
	$data['active_section'] = 'orders_all';
	$data['active_page']	= 'orders';

	// Checking order Session Activity
	$data['userinfo'] = $this->m_validation->check_user_loggedin();

	// Checking for Requested Page Permissions
	$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

	############################################
	##****** UPDATE PERMISSION CHECK *********##
	############################################	
	if ( $allow['update'] === FALSE )
	{
		$status['update_status']["status"] = '403';
		$status['update_status']["message"] = 'You Don`t Have Permissions to Do This Action!!!';
		$this->response($status['update_status'],REST_Controller::HTTP_BAD_REQUEST);
		return;
	}
	
	// Check order existence
	if($this->post('status_id') !== null && $this->post('order_id') !== null && $this->post('pmt_status_id') !== null)
	{
		$order  = $this->m_orders->get_orders($this->post('order_id'));
		$status = $this->m_orders->get_order_statuses($this->post('status_id'));
		$pmt_status = $this->m_orders->get_pmt_statuses($this->post('pmt_status_id'));
		
		if(isset($order['items'][0]) && !empty($order['items'][0]) && $status && $pmt_status)
		{
			date_default_timezone_set("Europe/Moscow");
			$order_info = [];
			$order_info['id'] = trim($this->post('order_id'));
			$is_changed = false;
			$status_message = '';
			$pmt_status_message = '';
			$res_upd =false;
			
			//Check for order status change
			if($order['items'][0]['o_status_id'] != trim($this->post('status_id'))){
				$order_info['o_status_id'] = trim($this->post('status_id'));
				$status_message = '<br/>Статус с "'. $order['items'][0]['order_status'] . '" на "' .$status[0]['name'].'"';
				$is_changed = true;
			}
			
			//Check for order Payment status change
			if($order['items'][0]['pmt_status_id'] != trim($this->post('pmt_status_id'))){
				$order_info['pmt_status_id'] = trim($this->post('pmt_status_id'));
				$pmt_status_message = '<br/>Статус Платёжа с "'. $order['items'][0]['pmt_status'] . '" на "' .$pmt_status[0]['name'].'"';
				$is_changed = true;
			}
			if($is_changed == true){
				$order_info['modified'] = date('Y-m-d H:i:s');
				$res_upd = $this->m_orders->update($order_info);
			} else {
				$this->response(array('status'=>'failure','message'=>'Ничего не изменилось!!!'), REST_Controller::HTTP_BAD_REQUEST);
			}
			
			// check for update result
			if($res_upd)
			{
				$history = [];
				$history['order_id'] =  trim($this->post('order_id'));
				$history['date'] = date('Y-m-d H:i:s');
				$history['description'] = $data['userinfo']['name'] 
							. ' Изменил Ордер #'.$order['items'][0]['order_id']
							.  $status_message.' ' .$pmt_status_message;
				$this->load->model('m_cart');
				$this->m_cart->insert_order_history($history);
				$this->response(array('status'=>'success','message'=>'Статус успешно изменен!!!'), REST_Controller::HTTP_OK);
			}
			else
			{
				$this->response(array('status'=>'failure','message'=>'Ничего не изменилось!!!'), REST_Controller::HTTP_BAD_REQUEST);
			}
		}
		else
		{
			$this->response(array('status'=>'failure','message'=>'Неправильный ордер или статус!!!'), REST_Controller::HTTP_BAD_REQUEST);
		}
	}
	else
	{
		$this->response(array('status'=>'failure','message'=>'Неправильный Запрос!!!'), REST_Controller::HTTP_BAD_REQUEST);
	}
	// return 
	
}

	

	

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

	/*
	|---------------------------------------------------------------------------------
	|Destroying order
	|---------------------------------------------------------------------------------
	*/
	public function orders_delete($id)
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'orders_all';
		$data['active_page']	 = 'orders';

		// Checking order Session Activity
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
		$check_result = $this->m_orders->check_before_delete($id);

		// Check Delete and Response
		if ( $check_result['status'] ) 
		{
			//Delete order items
			$this->m_orders->delete_order_items($id);
			//Delete order attrs
			$this->m_orders->delete_order_attributes($id);
			//Delete order
			$res = $this->m_orders->delete($id);

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
			$status['delete_status']["message"] = 'Cannot delete. order Exists in some tables '.$check_result['message'];
			$this->response( $status['delete_status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR );
		}
	}//#orders_delete


/*
|
| Get full order
|
*/
public function get_full_get($id=false)
{
	if ( $this->get('action')!==null && $this->get('id') !== null ) 
	{
		if ($this->get('action') == 'e') 
		{
			if (isset($this->m_orders->get_orders($this->get('id'))['items'][0])) 
			{
				// get order
				$order_info = $this->m_orders->get_orders($this->get('id'))['items'][0];
			}
			else
			{
				$order_info = 'undefined';
			}

			// get order items
			$order_items = $this->m_orders->get_order_items($this->get('id'));
			// get order attrs	
			$order_attrs = $this->m_orders->get_order_attrs($this->get('id'));
			$resp = array(
							'status'=>'success',
							'attrs'=>$order_attrs,
							'info'=>$order_info,
							'items'=>$order_items
							);

			$this->response($resp, REST_Controller::HTTP_OK);
		}
		
	}
}






//End of C_orders Class
}
?>
