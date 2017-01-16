<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class C_boss extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('m_settings');
		//Load Cart Model
		$this->load->model('m_cart');
		//$this->load->helper('cookie');
		//delete_cookie('anonimous');
		
		if ($this->session->userdata('anonimousc') === NULL) 
		{
			date_default_timezone_set("Europe/Moscow");
			$anon_id = md5(time().mt_rand());
			$this->session->set_userdata('anonimousc',$anon_id);
			//set_cookie('anonimousc',$anon_id,time());
		}
	}

	function index()
	{
		$this->home();
	}
/*
|---------------------------------------------------------------------------------
|site Function for Showing Some Significant or Not so Much Significant info
|---------------------------------------------------------------------------------
*/
	/*########################################################################
	# Home Page
	########################################################################*/
	function home()
	{
		$data['active_page'] = 'home';
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$data['settings'] = $this->m_settings->get_all();
		$this->load->view('frontend/templates/header',$data);
		$this->load->view('frontend/templates/main_home_top_bg');
		$this->load->view('frontend/pages/boss/home');
		$this->load->view('frontend/templates/footer', $data);

	}


	/*########################################################################
	# Shop Page
	########################################################################*/
	function shop($shop_type='',$desert_type_id='')
	{
		$data['active_page'] = 'shop';
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$data['settings'] = $this->m_settings->get_all();
		$this->load->model('m_deserts');
		$this->load->model('m_sets');
		//Get Desert List for Shop Type 'sets'
		if ($shop_type == 'sets') 
		{
			$desert_criterions = array( 'type'=>$shop_type );
			$data['desert_types'] = $this->m_deserts->get_deserts(0,false,$desert_criterions)['items'];
		}

		if ( $desert_type_id == '') 
		{
			$set_criterions = array( 'desert_type' => 'getall' );
			$data['set_list'] = $this->m_sets->get_sets(0,false,$set_criterions)['items'];
		} 
		elseif ( $desert_type_id == 'newsweets') 
		{
			$set_criterions = array( 'desert_type' => 'getnews' );
			$data['set_list'] = $this->m_sets->get_sets(0,false,$set_criterions)['items'];
		}
		else
		{
			$set_criterions = array( 'desert_type' => $desert_type_id );
			$data['set_list'] = $this->m_sets->get_sets(0,false,$set_criterions)['items'];
		}
		$data['shop_type'] = $shop_type;
		$data['desert_type'] = $desert_type_id;


		$this->load->view('frontend/templates/header', $data);
		$this->load->view('frontend/templates/main_top_bg');
		//$data['sidebar'] = $this->load->view('frontend/templates/sidebar_des_types',$data,true);
		$this->load->view('frontend/pages/boss/shop', $data);
		$this->load->view('frontend/templates/footer', $data);
		// Loading Header File
		//$this->load->view('frontend/pages/unser_construction.php');
	}

	/*########################################################################
	# Shop Item Page
	########################################################################*/
	public function shop_item($id='')
	{
		$data['active_page'] = 'shop';
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$data['settings'] = $this->m_settings->get_all();
		$this->load->model('m_sets');
		
		// Get set
		$data['set'] = $this->m_sets->get_sets($id)['items'][0];
		// Get set Items
		$data['set_item_list'] =  $this->m_sets->get_set_items($id);
		// Get set attributes
		$set_attr_list = $this->m_sets->get_set_attrs($id);

		// Make attributes humman readable
		$attr_result = [];
		if (!empty($set_attr_list)) {
			foreach ($set_attr_list as $attr) {
				$id = $attr['attrgroup_id'];
				if (isset($attr_result[$id])) {
					$attr_result[$id][$attr['attr_id']] = $attr;
				} else {
					$attr_result[$id][$attr['attr_id']] = $attr;	
				}
			}
		}

		$data['attr_list'] = $attr_result;
		$this->load->view('frontend/templates/header',$data);
		$data['sidebar'] ='';// $this->load->view('frontend/templates/sidebar_des_types',$data,true);
		$this->load->view('frontend/pages/boss/product', $data);
		$this->load->view('frontend/templates/footer', $data);
	}

	/*########################################################################
	# Events Page
	########################################################################*/
	public function events()
	{
		$data['active_page'] = 'events';
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$data['settings'] = $this->m_settings->get_all();
		$this->load->view('frontend/templates/header', $data);
		$this->load->view('frontend/templates/main_top_bg');
		$this->load->view('frontend/pages/boss/events', $data);
		$this->load->view('frontend/templates/footer', $data);	
	}

	/*########################################################################
	# Shipping and Delivery Page
	########################################################################*/
	public function delivery()
	{
		$data['active_page'] = 'delivery';
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$data['settings'] = $this->m_settings->get_all();
		$this->load->view('frontend/templates/header', $data);
		$this->load->view('frontend/templates/main_top_bg');
		$this->load->view('frontend/pages/boss/delivery', $data);
		$this->load->view('frontend/templates/footer', $data);	
	}


	/*########################################################################
	# Checkout Page
	########################################################################*/
	public function checkout()
	{
		//$anonimous_id = get_cookie('anonimousc',true);
		$data['active_page'] = 'checkout';
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$data['settings'] = $this->m_settings->get_all();
		$anonimous_id = $this->session->userdata('anonimousc');

		$client = $this->session->userdata('fclient_logged_in');

		//Check For Logged In Client
		if ( $client !== null ) {
			$client_id = $client['id'];
			$data['client_id'] = $client['id'];
			$this->load->model('m_clients');
			$data['clientinfo'] = $this->m_clients->get_clients($client['id'])['items'][0];
			$data['client_address_list'] = $this->m_clients->get_client_addresses($client_id);
			$anonimous_id = false;
		} else {
			$client_id = false;	
			$data['client_id'] = false;
		}
		//$data['cart_items_count'] = $this->m_cart->get_cart_items_count($client_id,$anonimous_id);
		
		// Get Shipping Types
		$data['shp_types'] = $this->m_cart->get_shipping_types();
		// Get Shipping Periods
		$data['shp_periods'] = $this->m_cart->get_shipping_periods();
		// Get Shipping Types / Periods
		$data['shp_types_periods'] = $this->m_cart->get_shipping_types_periods();
		// Get Shipping Zones
		$data['shp_zones'] = $this->m_cart->get_shipping_zones();
		// Get Payment Methods
		$data['pay_methods'] = $this->m_cart->get_payment_methods();
		/*echo $client_id.'=='.$anonimous_id;
		exit;*/
		$data['cart'] = $this->m_cart->get_cart( $client_id, $anonimous_id );
		$this->load->view('frontend/templates/header', $data);
		$this->load->view('frontend/pages/boss/checkout', $data);
		$this->load->view('frontend/templates/footer', $data);	
	}

	/*#########################################################################
	# Submit cart Success
	*#########################################################################*/
	public function checkout_success()
	{
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$data['settings'] = $this->m_settings->get_all();
		$data['info'] = $this->session->flashdata('ch_sucss_msg');
		$this->load->view('frontend/templates/header',$data);
		$this->load->view('frontend/templates/main_top_bg');
		$this->load->view('frontend/pages/boss/checkout_success',$data);
		$this->load->view('frontend/templates/footer', $data);
	}

	/*#########################################################################
	# Submit cart Error
	*#########################################################################*/
	public function checkout_error()
	{
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$data['settings'] = $this->m_settings->get_all();
		$data['info'] = $this->session->flashdata('ch_err_msg');
		$this->load->view('frontend/templates/header',$data);
		$this->load->view('frontend/templates/main_top_bg');
		$this->load->view('frontend/pages/boss/checkout_error',$data);
		$this->load->view('frontend/templates/footer', $data);
	}

	/*########################################################################
	# Client Profile Page
	########################################################################*/
	public function clientprofile($sub='')
	{
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$data['settings'] = $this->m_settings->get_all();
		$client = $this->session->userdata('fclient_logged_in');
		
		$this->load->view('frontend/templates/header',$data);
		//Check For Logged In Client
		if ( $client !== null && $sub == '' ) {
			$data['active_user_section'] = 'profile';
			$this->load->model('m_clients');
			$data['clientinfo'] = $this->m_clients->get_clients($client['id'])['items'][0];
			$data['cl_addr_list'] = $this->m_clients->get_client_addresses($client['id']);
			$data['profile_sidebar'] = $this->load->view('frontend/pages/clients/profile_sidebar', $data, true);
			$this->load->view('frontend/pages/clients/profile', $data);
			
		} elseif ( $client !== null && $sub == 'orders' ) {
			$data['active_user_section'] = 'userorders';
			//Load Model and Get orders
			$this->load->model('m_orders');
			// 1st 0 for order_id
			// 2nd fasle for criterion 
			// 3rd for client_id
			$data['orders'] = $this->m_orders->get_orders(0, false, $client['id'])['items'];

			// Get orders items
			// Get orders attributes
			$data['profile_sidebar'] = $this->load->view('frontend/pages/clients/profile_sidebar', $data, true);
			$this->load->view('frontend/pages/clients/profile_orders', $data);
		} else {
			redirect(site_url('login'));
		}

		$this->load->view('frontend/templates/footer', $data);	
	}
	
	
	/*########################################################################
	# Client Order View Page
	########################################################################*/
	public function view_order($id='')
	{
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$data['settings'] = $this->m_settings->get_all();
		$client = $this->session->userdata('fclient_logged_in');
		
		$this->load->view('frontend/templates/header',$data);
		//Check For Logged In Client
		if ( $client !== null ) {
			$data['active_user_section'] = 'userorders';
			// Load Model
      // get order for specified order id
			$this->load->model('m_orders');
      $data['order'] = $this->m_orders->get_full_order($id, false, $client['id']);
			//Get Order Statuses
			$data['order_statuses'] = $this->m_orders->get_order_statuses();
			//Get Payment Statuses
			$data['pmt_statuses'] = $this->m_orders->get_pmt_statuses();
			
			$data['profile_sidebar'] = $this->load->view('frontend/pages/clients/profile_sidebar', $data, true);
			$this->load->view('frontend/pages/clients/profile_order_details', $data);
		} else {
			redirect(site_url('login'));
		}

		$this->load->view('frontend/templates/footer', $data);	

	}
	

}
 ?>