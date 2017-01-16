<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* c_fclients Controller Class for Cser Manipulation
*/
class C_fclients extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_clients');
		$this->load->model('m_settings');
		//$this->load->model('m_config');
		$this->load->model('m_validation');
		$this->load->library('form_validation');
		$this->load->model('m_cart');
		
	}

	
	/*########################################################################
	# Register New Client Page
	########################################################################*/
	public function register_index_get()
	{
		$data['settings'] = $this->m_settings->get_all();
		$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$this->load->view('frontend/templates/header',$data);
		$this->load->view('frontend/pages/clients/register.php',$data);
		$this->load->view('frontend/templates/footer', $data);
	}

	
	/*########################################################################
	# Do not allow direct get connections to register submit 
	# Redirect to register index
	########################################################################*/
	public function register_submit_get()
	{
		$this->register_index_get();
	}


	/*########################################################################
	# Submit New User Registration
	########################################################################*/
	public function register_submit_post()
	{
		$data['settings'] = $this->m_settings->get_all();
		$data['cart_items_count'] = $this->m_cart->get_cart_count();

		// Load Header
		$this->load->view('frontend/templates/header',$data);
		
		//Check For Form Submit
		if ( $this->input->post('btnSubmit') !== null ) 
		{
			// Validate Submitted Values
			if ( $this->form_validation->run('fclient_create') !== false ) 
			{
				// Load & Initialize CI Mail Library
				$this->init_sendmail();
				
				date_default_timezone_set("Europe/Moscow");
				
				// Collect Info
				// Password hashing is also in collect_info
				$info = $this->collect_info($this->input->post());
				//Generate Activation Code
				$activation_code = md5($info['fname'].$info['sname'].$info['email'].date('Y-m-d H:i:s').'Secret:C*uI8IS');
				
				//Insert New client
				$res = $this->m_clients->insert_info($info);
				if ($res) 
				{
					$this->m_clients->insert_act_code($res, $activation_code);
					$message ='<!DOCTYPE html>
								<html>
								<head>
									<title></title>
								</head>
								<body>'
								.'<p>Вы зарегистрировались на makbaker.pu пройдите по ссылке чтобы активировать ваш профиль.</p><br/>'
								.'<a href="testmeto.com/activateaccount?activationcode='.$activation_code.'">Активировать</a>'
								.'<br/><br/><p>Если ссылка не работает скопируте ссылку внизу.<p><br/>'
								.'<p>http://testmeto.com/activateaccount?activationcode='.$activation_code
								.'</p></body></html>';
					$this->email->from('activation@testmeto.com');
					$this->email->to($info['email']);
					$this->email->subject('Регистрация на MakBaker');
					$this->email->message($message);
					$this->email->send();


					$data['info'] = 'Сообщение об активации придет на вашу электронную почту в течении пяти минут!!!';
				}
				else {
					$data['info'] = 'Произашла ошибка создания нового профиля. Пожалуйста попробуйте снова!!!';
				}
				// Load Degister Success Page
				$this->load->view('frontend/pages/clients/register_success',$data);

			}else {
				$data['info'] = validation_errors();
				$this->load->view('frontend/pages/clients/register',$data);	
			}
		} else {
			$data['info'] = 'post err';
			$this->load->view('frontend/pages/clients/register',$data);	
		}
		$this->load->view('frontend/templates/footer', $data);
	}


	/*########################################################################
	# Load Login page Or Redirect to Home Page If is Logged in Client
	########################################################################*/
	public function login_get()
	{
		$data['settings'] = $this->m_settings->get_all();
		$this->load->view('frontend/templates/header',$data);
		$clientsession = $this->session->userdata('fclient_logged_in');
		if (isset($clientsession['fname'])) {
			//Go To Homepage
			redirect(site_url(),'refresh');
		} else {
			$data['clientsession'] = $clientsession;
			$this->load->view('frontend/pages/clients/login',$data);
		}
		$this->load->view('frontend/templates/footer', $data);
	}

	
	/*########################################################################
	# Do not allow direct get connections to validate_login
	# Redirect to Login_Get
	########################################################################*/
	public function validate_login_get()
	{
		$this->login_get();
	}

	
	/*########################################################################
	# Validationg Client Login Info
	########################################################################*/
	function validate_login_post()
    {
		$data['settings'] = $this->m_settings->get_all();
		//$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$this->load->view('frontend/templates/header',$data);

		if ( $this->input->post('btnSubmitLogin') !== null ) 
		{
			if($this->form_validation->run('fclient_login') == FALSE)
			{
				$data['info'] = validation_errors();
				//On Validation Error Redirect to Login Page
				$this->load->view('frontend/pages/clients/login', $data);
			}
			else
			{
				$clientinfo = $this->session->userdata('fclient_logged_in');

				if( $clientinfo['is_activated'] == '0' )
				{
					$data['info'] = "Профиль для пользователя <b>".$clientinfo['email']."</b> не активирован. Проверьте вашу Электронную почту.";
					$this->session->unset_userdata('fclient_logged_in');
					$this->load->view('frontend/pages/clients/login', $data );
				}
				else
				{
					//$this->load->helper('cookie');
					//$anonimous = get_cookie('anonimousc',true);

					$anonimous_id = $this->session->userdata('anonimousc');

					$client = $this->session->userdata('fclient_logged_in');
					
					$this->load->model('m_cart');

					// Associate Anonimous Cart With Logged in Users Cart
					$this->m_cart->assoc_anon_client( $client['id'], $anonimous_id );

					//Go To Homepage
					redirect(site_url());
				}
			}
		} else {
			//Go To Homepage
			redirect(site_url());
		}
		$this->load->view('frontend/templates/footer', $data);
    }//#validate_login_post


	/*########################################################################
	# Checking Client Pass in Database And Going Forward
	########################################################################*/
	public function check_and_go( $password )
	{
		// Getting Login From POST
		$email = $this->input->post('email');

		// Loading Date Helper
		$this->load->helper('date');

		// Loading Logger Class 
		$this->load->model('m_logger');

		// Checking Typed Login Password
		$check_result = $this->m_clients->check_login($email, $password);

		if( $check_result )
		{
			date_default_timezone_set("Asia/Yerevan");

			$check_result['last_login_time'] = date('Y-m-d H:i:s');

			//Setting Logged in Client Info Into Session
			$this->session->unset_userdata('fclient_logged_in');
			$this->session->set_userdata('fclient_logged_in', $check_result);

			//Log section
			$data_txt = 'Login Success for Client ==>'. $email;
			$this->m_logger->loggish($data_txt, 'info');
		}
		else
		{
			//log section
			$data_txt = 'Почта или пароль указаны неверно';
			$this->m_logger->loggish($data_txt, 'info');

			//Setting Validation Message
			$this->form_validation->set_message('check_and_go', $data_txt);
			return false;
		}
	}


	/*########################################################################
	# Function for Destroying Client Session and Logout
	########################################################################*/
	public function logout_get()
	{
		//Get Logged in Client Info From Session
		$clientinfo = $this->session->userdata('fclient_logged_in');
		if (isset($clientinfo['fname'])) 
		{
			$data_txt =  $clientinfo['fname'] . '- Successfully Logged Out.';

			$this->load->model('m_logger');
			$this->load->model('m_validation');
			$this->m_logger->loggish($data_txt, 'info');
			$this->m_validation->destroy_session();
			date_default_timezone_set("Europe/Moscow");
			$anon_id = md5(time().mt_rand());
			$this->session->set_userdata('anonimousc',$anon_id);
		}
		//Redirect to Login Page
		redirect(site_url('login'), 'refresh');
	}

	/*########################################################################
	# Initialize CI sendmail for mailing operations
	########################################################################*/
	private function init_sendmail()
	{
		$this->load->library('email');
		$config=[];
		$config['protocol'] = 'sendmail';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'utf-8';
		$config['mailtype'] = 'html';
		$config['wordwrap'] = TRUE;
		$this->email->initialize($config);
	}

	/*########################################################################
	# Collect Info For user Registration
	########################################################################*/
	private function collect_info($post)
	{
		$info['fname'] = html_escape($post['name1']);
		$info['sname'] = html_escape($post['name2']);
		$info['email'] = html_escape($post['email']);
		$info['phone'] = html_escape($post['phone']);
		/*$info['date_of_birth'] = $post['year'].'-'
								.$post['month'].'-'
								.$post['day'];*/
		$info['password'] = $this->m_validation->hash_password($post['password']);
		$info['created']  = date('Y-m-d H:i:s');
		$info['is_subscribed'] = 0;
		$info['login_enabled'] = 1;
		$info['is_activated']  = 0;

		return $info;
	}

	/*########################################################################
	# Activate User Account
	########################################################################*/
	public function activate_account_get()
	{
		$data['settings'] = $this->m_settings->get_all();
		//$data['cart_items_count'] = $this->m_cart->get_cart_count();
		$this->load->view('frontend/templates/header',$data);
		if ( $this->input->get('activationcode', true) !== null ) 
		{
			$res = $this->m_clients->activate_client($this->input->get('activationcode'),true);
			if ($res && $res>0) {
				$data['info']['status']  = 'success';
				$data['info']['message'] = 'Активация прошла успешно';
			} else {
				$data['info']['status']  = 'failure';
				$data['info']['message'] = 'Проблема активации профиля. Повторите попытку если не получится свяжитесь снами.';
			}
		} else {
			$data['info']['status']  = 'failure';
			$data['info']['message'] = 'Неправилная операция';
		}

		$this->load->view('frontend/pages/clients/activation_page',$data);
		$this->load->view('frontend/templates/footer', $data);
	}


	/*########################################################################
	# Update Client
	########################################################################*/
	public function client_actions_post()
	{
		$admin_user = $this->session->userdata('logged_in');
		$client = $this->session->userdata('fclient_logged_in');
		/*if ( $client['id'] != $this->post('client_id') || !isset($admin_user['id']) ) 
		{
			$this->response(array('status'=>'failure','message'=>'UNAUTHORIZED USER'), REST_Controller::HTTP_UNAUTHORIZED);	
		}*/
		

		//Check For Logged In Client
		/*if ( $client !== null ) {
			$this->load->model('m_clients');
			$data['clientinfo'] = $this->m_clients->get_clients($client['id'])['items'][0];
			// If client Not exists redirect to login page
			if (empty($data['clientinfo'])) {
				$this->response(array('status'=>'failure','message'=>'invalid user'), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			redirect(site_url('login'));
		}*/

		if ( $this->post('client_id') !== NULL 
				AND $this->post('name1') !== NULL 
					AND $this->post('name2') !== NULL 
						AND $this->post('phone') !== NULL 
							AND $this->post('city') !== NULL 
								AND $this->post('street') !== NULL 
									AND $this->post('bld') !== NULL 
										AND $this->post('apt') !== NULL 
											AND $this->post('password') !== NULL 
												AND $this->post('confirmPassword') !== NULL ) {

			$res = $this->m_clients->get_clients($this->post('client_id'))['items'];
			if (isset($res[0])) {
				$db_client = $res[0];
			} else {
				$this->response(array('status'=>'failure','message'=>'invalid user'), REST_Controller::HTTP_BAD_REQUEST);
			}
			
			$updatables = $this->check_and_init($db_client,$this->post());

			$this->form_validation->set_data($updatables);
			
			if ($this->form_validation->run() !== false) 
			{
				$cl_address = [];
				$cl_address['client_id']  = $this->post('client_id');
				$cl_address['city_id'] 	  = $updatables['city_id'];
				//$cl_address['address'] 	  = $updatables['address'];
				$cl_address['street'] 	  = $updatables['street'];
				$cl_address['bld'] 	  	  = $updatables['bld'];
				$cl_address['apt'] 	  	  = $updatables['apt'];
				$cl_address['is_default'] = 1;

				unset($updatables['city_id']);
				unset($updatables['street']);
				unset($updatables['bld']);
				unset($updatables['apt']);

				$updatables['id'] = $this->post('client_id');

				// Check for address existence for Client and insert/update
				$res_addr_get = $this->m_clients->get_client_addresses($updatables['id']);

				if (!$res_addr_get) {
					$res_addr = $this->m_clients->insert_address($cl_address);	
				} else {
					$res_addr = $this->m_clients->update_address($cl_address);
				}
				
				// Check For Password Change And Unset confirmPassword
				if (isset($updatables['password']) && $updatables['password']) {
					$updatables['password'] = $this->m_validation->hash_password($updatables['password']);
					unset($updatables['confirmPassword']);
				}

				//Update Client Info
				$res_upd = $this->m_clients->update($updatables);

				// Check For Update Success
				if ($res_upd || $res_addr) {
					$this->response(array('status'=>'success','message'=>'Личные данные успешно обновились.'), REST_Controller::HTTP_OK);
				} else {
					$this->response(array('status'=>'success','message'=>'Ничего не изменилось!'), REST_Controller::HTTP_BAD_REQUEST);
				}
			}
			else
			{
				$this->response( array('status' =>'failure','message'=>validation_errors()),  REST_Controller::HTTP_BAD_REQUEST );
			}
			/*$res = '';//$this->m_client->insert_address($addr);
			// insert check return
			if ( $res ) {
				$this->response(array('status'=>'success','message'=>'created', 'id'=>$res), REST_Controller::HTTP_CREATED);
			} else {
				$this->response(array('status'=>'failure','message'=>'unable to create'), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}*/
		} else {
			$this->response(array('status'=>'failure','message'=>'Проблема Обновления личных данных!'), REST_Controller::HTTP_BAD_REQUEST);
		}
	}





	/*########################################################################
	# CHECK getted values with db values and SET rules for validating
	# INIT array for insert/update 
	# return __> full insertable/updatable array
	########################################################################*/
	protected function check_and_init($db_values,$checkable_values)
	{
		// Check For Same Name Change (upper/lower)
		$db_name = mb_convert_case( $db_values['fname'], MB_CASE_LOWER, "UTF-8" ); 
		$post_name = mb_convert_case( trim($checkable_values['name1']), MB_CASE_LOWER, "UTF-8" );

		// Set Name Change Check
		// if it`s The Same Name But With Changed Case
		// No Need For Rules
		if ( $db_values['fname'] !== trim($checkable_values['name1']) && $db_name == $post_name )
		{
			$client['fname'] = trim($checkable_values['name1']); //*				
		}
		elseif ( $db_values['fname'] !== trim($checkable_values['name1']) ) 
		{
			$client['fname'] = trim($checkable_values['name1']); //*
			$this->form_validation->set_rules('fname', 'Название', 'trim|required');
		}

		// Check For Same SName Change (upper/lower)
		$db_sname = mb_convert_case( $db_values['fname'], MB_CASE_LOWER, "UTF-8" ); 
		$post_sname = mb_convert_case( trim($checkable_values['name1']), MB_CASE_LOWER, "UTF-8" );

		// SName
		if ( $db_values['sname'] !== trim($checkable_values['name2']) && $db_sname == $post_sname )
		{
			$client['sname'] = trim($checkable_values['name2']); //*				
		}
		elseif ( $db_values['sname'] !== trim($checkable_values['name2']) ) 
		{
			$client['sname'] = trim($checkable_values['name2']); //*
			//$this->form_validation->set_rules('name2', 'Название', 'trim');
		}

		// E-Mail Change Check
		if ( $db_values['email'] !== trim($checkable_values['email']) && strcasecmp($db_values['email'], trim($checkable_values['email']))==0 ) 
		{
			$client['email'] = trim($checkable_values['email']);
		}
		elseif ( $db_values['email'] !== trim($checkable_values['email']) ) 
		{
			$client['email'] = trim($checkable_values['email']);
			$this->form_validation->set_rules('email', 'Эл-почта', 'trim|required|valid_email|is_unique[app_users.email]');
		}

		// Password/password confirm
		if ($checkable_values['password'] !='' || $checkable_values['confirmPassword'] !='') 
		{
			$client['password']		 	  = trim($checkable_values['password']);
			$client['confirmPassword']	  = trim($checkable_values['confirmPassword']);
			$this->form_validation->set_rules('password', 		'Пароль', 			  'trim|required|min_length[8]');
			$this->form_validation->set_rules('confirmPassword', 'Подтвердить пароль', 'trim|required|matches[password]');
		}

		$client['phone']		= trim($checkable_values['phone']);
		$client['city_id']	 	= trim($checkable_values['city']);
		$client['street']		= trim($checkable_values['street']);
		$client['bld']		 	= trim($checkable_values['bld']);
		$client['apt']		 	= trim($checkable_values['apt']);
				
		$this->form_validation->set_rules('phone', 	 'Телефон', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('city_id', 'Город', 	'trim');
		$this->form_validation->set_rules('street',  'Улица', 	'trim|max_length[255]');
		$this->form_validation->set_rules('bld', 	 'Дом', 	'trim|max_length[255]');
		$this->form_validation->set_rules('apt', 	 'Кв., Офис и т.д.', 	'trim|max_length[255]');


		return $client;
	}

	

//End of c_clients Class
}
 ?>