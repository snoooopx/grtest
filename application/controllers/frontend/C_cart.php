<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'libraries/REST_Controller.php';
/*
| cart class for cart manipulations
*/
class C_cart extends REST_Controller{

	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('m_cart');
		$this->load->helper('cookie');
	}

	/*########################################################################
	# Do not allow direct get connections to cart submit 
	# Redirect to checkout page
	########################################################################*/
	public function submit_get()
	{
		redirect('checkout/cart');
	}

	/*#########################################################################
	# Submit cart
	#########################################################################*/
	public function submit_post()
	{
		$client = $this->session->userdata('fclient_logged_in');
		//$this->response($client);
		//Check For Logged In Client
		if ( $client !== null ) {
			$client_id = $client['id'];
		} else {
			$this->response(array('status'=>'failure', 'message'=>'Войдите в систему для продолжения!!!'), REST_Controller::HTTP_BAD_REQUEST);
		}

		// Check For Submit
		if ($this->post('btnSbmtCart') !== null) 
		{
			// Get Cart// cart avalilability is checked in get_cart function
			$cart = $this->m_cart->get_cart($client_id, false);
			
			// Collect Insertable Order Data
			$order = $this->collect_order_data( $cart );

			if (isset($order['failure']) && $order['failure']['status'] == 'failure' ) {
				$this->response($order['failure'], REST_Controller::HTTP_BAD_REQUEST);
				return;
			}

			// Set Shipping validation Data
			$shp_info = $this->form_validation->set_data($this->post());

			// Set Rules for Shipping Validation
			//$this->set_shipping_validation_rules($this->post());
			if ($this->form_validation->run() !== false ) 
			{
				$order['client_id']	= $client['id'];
				$res_ord_ins = $this->m_cart->insert_order($order);

				if ($res_ord_ins) 
				{
					// Get Order Table ID
					$order_tb_id = $res_ord_ins;
					$items 		 = $this->collect_order_items_data( $cart['sets'], $order_tb_id );
					$res_ord_itms_ins = $this->m_cart->insert_order_items($items);
					if ($res_ord_itms_ins) 
					{
						$attrs = $this->collect_order_item_attrs_data($cart['attrs'], $order_tb_id);
						$res_ord_itms_attrs_ins = $this->m_cart->insert_order_item_attributes($attrs);
						//$this->response($res_ord_itms_ins);

						// Write Order History
						$history = [];
						$history['order_id'] = $order_tb_id;
						$history['date'] = date('Y-m-d H:i:s');
						$history['description'] = $client['fname'] . ' Добавил новый ордер';
						$this->m_cart->insert_order_history($history);

						// Clear Client Cart
						$this->m_cart->delete_cart_item('all', $client_id, false );

						//Generate mail
						// Load & Initialize CI Mail Library
						$this->init_sendmail();
						$message = $this->generate_client_order_mail($order, $cart['sets'], $cart['attrs']);
						/*'<!DOCTYPE html>
									<html>
									<head>
										<title></title>
									</head>
									<body>'
									.'<p>Ваш заказ #'.$order['o_id_prfx'].$order['o_id'].' на MakBaker.ru.</p><br/>'
										/*foreach ($items as $item) {
											'<tr>'
												.'<td>'
													
												.'</td>'
											.'</tr>'
										}*/
									/*.'</body></html>';*/
						$this->email->from('sales@testmeto.com');
						$this->email->to($order['shp_email']);
						$this->email->bcc('admin@testmeto.com');
						$this->email->subject('Новый заказ #'.$order['o_id_prfx'].$order['o_id']);
						$this->email->message($message);
						$this->email->send();

						// Send mail

						// Open Checkout Success page
						$this->session->set_flashdata('ch_sucss_msg','Ваш заказ успешно создан. С вами скоро свяжутся.');
						//redirect('checkout/success');
						$this->response(array('status'=>'success', 'location'=>'success','message'=>'Ваш заказ успешно создан. С вами скоро свяжутся.', 'email'=>$message));
					}
				}
				else {
					$this->session->set_flashdata('ch_err_msg','Невозможно создаь Ваш ордер!!! Перейдите в корзину и попробуйте снова!');		
					redirect('checkout/error');
				}
			}
			else {
				$this->response(array('status'=>'failure','message'=>validation_errors()), REST_Controller::HTTP_BAD_REQUEST );
			}
		}
		else {
			$this->response(array('status'=>'failure','message'=>'Post Error.'), REST_Controller::HTTP_BAD_REQUEST);
		}
	}
	/*#########################################################################
	// Collect Insertable Data For Order Info
	#########################################################################*/
	public function collect_order_data($cart)
	{
		$order = [];
		$total = 0;
		$coupon_code = '';
		$coupon_discount_type = '';
		$coupon_discount_value = '';
		
		date_default_timezone_set("Europe/Moscow");
		// Get Last Order id
		$order['o_id']				= $this->m_cart->get_next_order_id();
		$order['o_id_prfx'] 		= 'MB-';
		$order['created'] 			= date('Y-m-d H:i:s');
		$order['modified']			= '';
		$order['o_status_id'] 		= 10; // новый ордер
		
		//$order['shp_status_id']		= $this->post('');
		
		// Check For Cart Non Emptiness 
		// And Get Cart Total (item + attribute prices)
		// TOTAL is calculated without discount
		// And Get Coupon Code/Type/Value
		if (!empty($cart['sets']) && !empty($cart['attrs'])) 
		{
			// Get Sets total
			foreach ($cart['sets'] as $set) {
				//$set_attr_total = 0;
				// Get Attributes total and coupon
				foreach ($cart['attrs'] as $attr) {
					// Get Coupon Code/Type/Value
					if (isset($attr['type']) && $attr['type'] == 'coupon') {
						$coupon_code 			= $attr['coupon_code'];
						$coupon_discount_type 	= $attr['coupon_discount_type'];
						$coupon_discount_value 	= $attr['coupon_discount_value'];
					}

					// Calculate Set Attributes price
					//if ($attr['set_id'] == $set['set_id']) {
						//$set_attr_total += ($attr['unit_price']*$attr['qty']);
					//}
				}	
				//Calculate Cart Items+Attributes Total Price
				$total += $set['qty'] * $set['unit_price'];
			}	
		} else {
			return array('failure' =>array('status'=>'failure','message'=>'Корзина пуста или отсутствуют аттрибуты набора.'));
		}

		$order['total']				= $total;
		$order['mmt_id'] 			= 1;
		$order['coupon_code']		= $coupon_code;

		$this->form_validation->set_rules('shpType', 	'Способ доставки', 	'trim|required|in_list[1,2]');
		$this->form_validation->set_rules('shpName', 	'Имя, Фамилия', 	'trim|required|max_length[255]');
		$this->form_validation->set_rules('shpPhone', 	'Телефон', 	 		'trim|required|max_length[255]');
		$this->form_validation->set_rules('shpEmail', 	'Почта', 		 	'trim|required|valid_email|max_length[255]');
		$order['shp_type_id']	= trim($this->post('shpType'));
		$order['shp_name']	 	= trim($this->post('shpName'));
		$order['shp_phone']	 	= trim($this->post('shpPhone'));
		$order['shp_email']	 	= trim($this->post('shpEmail'));

		if ( $this->post('shpType') !== null and $this->post('shpType') == 1 ) {
			$this->form_validation->set_rules('shpZone', 	'Зона Доставки', 	'trim|required');
			$this->form_validation->set_rules('shpCity', 	'Город', 			'trim|required|max_length[255]');
			$this->form_validation->set_rules('shpStreet',  'Улица', 			'trim|required|max_length[255]');
			$this->form_validation->set_rules('shpBld', 	'Дом', 				'trim|required|max_length[255]');
			$this->form_validation->set_rules('shpApt', 	'Кв., Офис и т.д.', 'trim|max_length[255]');
			
			$order['shp_zone_id']	= trim($this->post('shpZone'));
			$order['shp_city']	 	= trim($this->post('shpCity'));
			$order['shp_street']	= trim($this->post('shpStreet'));
			$order['shp_bld']		= trim($this->post('shpBld'));
			$order['shp_apt']		= trim($this->post('shpApt'));

			if ( $this->post('shpZone') !== null && $this->post('shpZone')) {
				//Get Shipping Zone
				$zone = $this->m_cart->get_shipping_zones( $this->post('shpZone') );
				if (!$zone && empty($zone)) 
				{
					return array('failure'=>array('status'=>'failure','message'=>'Неверная зона.'));
				}
				// Check if total is in min range and set min price for selected zone if min
				// Else Set 0/0
				if ( $total < $zone[0]['min_price'] ) {
					$order['is_min_total']		= 1;
					$order['min_total_price']	= $zone[0]['min_price'];
				} else {
					$order['is_min_total']		= 0;
					$order['min_total_price']	= 0;	
				}
				$order['shp_price']			= $zone[0]['next_day_price'];
			}
		}
		$this->form_validation->set_rules('shpDate', 	 'Дата', 	 	 'trim|required');
		$this->form_validation->set_rules('shpTime', 	 'Время', 	 	 'trim|required');
		$this->form_validation->set_rules('shpPayType',  'Метод оплаты', 'trim|required|max_length[3]');
		$this->form_validation->set_rules('shpComment',	 'Комментарий',  'trim|max_length[255]');
		$order['shp_date']	 	= trim($this->post('shpDate'));
		$order['shp_period_id']	= trim($this->post('shpTime'));
		$order['pmt_type_id'] 	= trim($this->post('shpPayType'));
		$order['user_note']	 	= trim($this->post('shpComment'));
		$order['pmt_status_id']	= 20;// ожидание оплаты

		return $order;
	}

	/*#########################################################################
	// Collect Insertable Data For Order Items(Sets) Info
	#########################################################################*/
	public function collect_order_items_data( $sets, $order_id )
	{
		$items = [];

		foreach ($sets as $id =>$set) {
			$items[$id]['order_id'] 	= $order_id;
			$items[$id]['set_id'] 		= $set['set_id'];
			$items[$id]['set_products']	= $set['set_products'];
			$items[$id]['qty'] 			= $set['qty'];
			$items[$id]['unit_price'] 	= $set['unit_price'];
			$items[$id]['mmt_id'] 		= $set['mmt_id'];
		}

		return $items;
	}

	/*#########################################################################
	// Collect Insertable Data For Order Attributes	
	#########################################################################*/
	public function collect_order_item_attrs_data( $attributes, $order_id )
	{
		$attrs_data = [];

		foreach ($attributes as $id =>$attrs) 
		{
			if ($attrs['type'] != 'coupon') {
				$attrs_data[$id]['order_id']	= $order_id;
				$attrs_data[$id]['set_id']		= $attrs['set_id'];
				$attrs_data[$id]['attr_id']		= $attrs['attr_id'];
				$attrs_data[$id]['custom_text']	= $attrs['custom_text'];
				$attrs_data[$id]['qty']			= $attrs['qty'];
				$attrs_data[$id]['unit_price']	= $attrs['unit_price'];
				$attrs_data[$id]['mmt_id']		= '1';
			}
		}

		return $attrs_data;
	}

	/*
	---------------------------------------------------------------------------
	# Function for inserting new item into cart / add to cart
	---------------------------------------------------------------------------
	*/
	public function action_post()
	{
		if ( $this->post('item') !== null ) 
		{
			$this->load->model('m_sets');
		
			date_default_timezone_set("Europe/Moscow");
			$item = [];
			$item['set']['anonimous_id'] = $this->session->userdata('anonimousc');

			$client = $this->session->userdata('fclient_logged_in');

			//Check For Logged In Client
			if ( $client !== null ) {
				$item['set']['client_id'] = $client['id'];
			} else {
				$item['set']['client_id'] = false;	
			}
			
			$post = $this->post('item');

			// Check For Existing and active Set
			$res_set = $this->m_sets->get_sets($post['set_id']);
			
			// Check for cart Insertable "Set" Existence
			if (isset($res_set['items']) && isset($res_set['items'][0])) 
			{
				// Check for Product existence in Set
				if (!isset($post['setproducts']) && empty($post['setproducts'])) {
					$this->response(array('status'=>'failure','message'=>'Добавьте продукты в набор!!!'), REST_Controller::HTTP_BAD_REQUEST);	
				}
				// Check Set Type
				//if Static Get "Set" Products from Db
				if ( $res_set['items'][0]['type'] == 'static' ){
					$res_set_items = $this->m_sets->get_set_items($post['set_id'],true);
					if ( $res_set_items ) {
						$item['set']['set_products'] = json_encode($res_set_items);
					}
				}
				//IF Custom get "Set" Products from POST
				else if ( $res_set['items'][0]['type'] == 'custom' )
				{
					$temp_set_products = [];

					foreach ($post['setproducts'] as $prod) {
						$temp_set_products[ $prod['pr_id'] ] = array(
																		 'id' => $prod['pr_id'],
																	   'name' => $prod['name'],
																	    'qty' => $prod['qty']
																    );
					}
					$item['set']['set_products'] = json_encode($temp_set_products);
				}
				else {
					$this->response(array('status'=>'failure','message'=>'Повреждённый набор.'), REST_Controller::HTTP_BAD_REQUEST);	
				}
			} else {
				$this->response(array('status'=>'failure','message'=>'Несуществующий набор.'), REST_Controller::HTTP_BAD_REQUEST);
			}

			$item['set']['created']			= date('Y-m-d H:i:s');
			$item['set']['set_id']			= $post['set_id'];
			$item['set']['qty'] 			= $post['qty'];
			$item['set']['unit_price'] 		= $post['last_price'];
			$item['set']['mmt_id'] 			= '1';

			if (isset($post['attrs']) && !empty($post['attrs'])) 
			{
				foreach ($post['attrs'] as $key => $attr) 
				{
					$item['attrs'][$key]['anonimous_id'] = $item['set']['anonimous_id'];
					$item['attrs'][$key]['client_id'] 	 = $item['set']['client_id'];
					$item['attrs'][$key]['set_id']		 = $item['set']['set_id'];
					$item['attrs'][$key]['attr_id']		 = $attr['attr_id'];
					$item['attrs'][$key]['qty']			 = '1';
					$item['attrs'][$key]['unit_price']	 = $attr['price'];
					$item['attrs'][$key]['custom_text']	 = $attr['text'];
				}
			}
			else
			{
				$this->response(array('status'=>'failure','message'=>'Аттрибуты не выбраны.'), REST_Controller::HTTP_BAD_REQUEST);	
			}


			$res = $this->m_cart->add_item($item);
			if ($res) {
				$cart_count = $this->m_cart->get_cart_items_count( $item['set']['client_id'], $item['set']['anonimous_id'] );
				$this->response(array('status'=>'success','message'=>'Добавлен в корзину.','cqty'=>$cart_count), REST_Controller::HTTP_OK);	
			} else {
				$this->response(array('status'=>'failure','message'=>'Произашла ошибка. Перезагрузите страницу и попробуйте снова.'), REST_Controller::HTTP_BAD_REQUEST);	
			}
		}
	}
	/*
	// Function for updating existing item info in cart / update item
	*/
	public function action_put($id='')
	{
		if ( isset($id) && $this->put('qty') !== null ) {
			//Get Anonimous ID From Session
			$anonimous_id = $this->session->userdata('anonimousc');
			//Get Client From Session
			$client = $this->session->userdata('fclient_logged_in');
			//Check For Logged In Client
			if ( $client !== null ) {
				$client_id = $client['id'];
				$anonimous_id=false;
			} else {
				$client_id = false;	
			}
			//Update Item Qty
			$res_upd = $this->m_cart->update_item($client_id,$anonimous_id, $id, $this->put('qty'));
			// Check Update Result
			if ( $res_upd ) {
				$this->response(array('status'=>'success','message'=>'Item update Success'), REST_Controller::HTTP_OK);
			} else {
				$this->response(array('status'=>'failure','message'=>'Item update Failure'), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response(array('status'=>'failure','message'=>'post failure'), REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	/*
	# Clear Cart All Items
	*/
	public function clear_post()
	{
		//Get Anonimous ID From Session
		$anonimous_id = $this->session->userdata('anonimousc');
		//Get Client From Session
		$client = $this->session->userdata('fclient_logged_in');
		//Check For Logged In Client
		if ( $client !== null ) {
			$client_id = $client['id'];
			$anonimous_id = false;
		} else {
			$client_id = false;	
		}

		// Check For Clear Full Cart
		if ($this->post('action')!==NULL && $this->post('action')=='clear') {
			$res = $this->m_cart->delete_cart_item('all', $client_id, $anonimous_id );
			if ($res) {
				$this->response(array('status'=>'success','message'=>'Cart cleared'), REST_Controller::HTTP_OK);
			} else {
				$this->response(array('status'=>'failure','message'=>'Error Clearing Cart'), REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response(array('status'=>'failure','message'=>'Bad Request'), REST_Controller::HTTP_BAD_REQUEST);
		}
	}


	/*
	//	function for removing item form cart
	*/
	public function action_delete($id='')
	{
		//Get Anonimous ID From Session
		$anonimous_id = $this->session->userdata('anonimousc');
		//Get Client From Session
		$client = $this->session->userdata('fclient_logged_in');
		//Check For Logged In Client
		if ( $client !== null ) {
			$client_id = $client['id'];
			$anonimous_id = false;
		} else {
			$client_id = false;	
		}
		// Check For Clear Single Item
		if ($this->delete('action')!==NULL && $this->delete('action')=='clear_item' && $id !== '') {
				$res = $this->m_cart->delete_cart_item('item', $client_id, $anonimous_id, $id );
				//$this->response($res);
				if ($res['status'] == 'success') {
					$this->response(array('status'=>'success','message'=>$res['message']), REST_Controller::HTTP_OK);
				} elseif ($res['status'] == 'failure') {
					$this->response(array('status'=>'failure','message'=>$res['message']), REST_Controller::HTTP_BAD_REQUEST);
				}	
		} 
		else {
			$this->response(array('status'=>'failure','message'=>'Bad Request'), REST_Controller::HTTP_BAD_REQUEST);
		} 
	}

	/*
	# Check for coupon existence and add
	*/
	public function check_and_applycoupon_post()
	{
		if ($this->post('coupon') !== NULL) 
		{
			$res = $this->m_cart->get_coupon($this->post('coupon'));

			if ($res) //Check Existence
			{
				if ($res['is_enabled'] == '0') //Check is Enabled
				{
					$this->response(array('status'=>'failure','message'=>'Купон не активен.'), REST_Controller::HTTP_BAD_REQUEST);
				}
				else
				{
					date_default_timezone_set("Europe/Moscow");
					$end_date = DateTime::createFromFormat('Y-m-d',$res['end_date']);
					//$this->response($end_date->format('Y-m-d').date('Y-m-d'));
					if ( date('Y-m-d') > $end_date->format('Y-m-d') ) // Check Expiration
					{
						$this->response(array('status'=>'failure','message'=>'Купон истек в "'.$end_date->format('Y-m-d').'"'), REST_Controller::HTTP_BAD_REQUEST);
					}
					else
					{
						//Get Anonimous ID From Session
						$anonimous_id = $this->session->userdata('anonimousc');
						//Get Client From Session
						$client = $this->session->userdata('fclient_logged_in');
						
						//Check For Logged In Client
						if ( $client !== null ) {
							$client_id = $client['id'];
							$anonimous_id=0;
						} else {
							$client_id = 0;	
						}

						$coupon_ins = [];
						$coupon_ins['anonimous_id']  	 	 = $anonimous_id;
						$coupon_ins['client_id'] 	 	 	 = $client_id;
						$coupon_ins['type']		 	 	 	 = 'coupon';
						$coupon_ins['coupon_code']	 	 	 = $res['code'];
						$coupon_ins['coupon_discount_type']	 = $res['type'];
						$coupon_ins['coupon_discount_value'] = $res['discount'];
						//$this->response($coupon_ins);
						$res_ins = $this->m_cart->insert_coupon($coupon_ins);
						if ($res_ins) {
							if ($res['type'] == 'percent'){
                                $label = '%';
                            } else if($res['type'] == 'fix') {
                                $label = 'руб.';   
                            }
							$this->response(array('status'=>'success','message'=>'Valid Coupon','type'=>$res['type'], 'discount'=>$res['discount'], 'label'=> $label), REST_Controller::HTTP_OK);
						} else {
							$this->response(array('status'=>'failure','message'=>'Проблема использования этого кода.'), REST_Controller::HTTP_BAD_REQUEST);
						}
					}
				}
			}
			else
			{
				$this->response(array('status'=>'failure','message'=>'Не правильный код.'), REST_Controller::HTTP_BAD_REQUEST);
			}
		}
		else
		{
			$this->response(array('status'=>'failure','message'=>'Bad Request.'), REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	/*
	# Delete coupon From Cart
	*/
	public function delete_coupon_delete($code)
	{
		if (isset($code) && $code)  {
			//Get Anonimous ID From Session
			$anonimous_id = $this->session->userdata('anonimousc');
			//Get Client From Session
			$client = $this->session->userdata('fclient_logged_in');
			
			//Check For Logged In Client
			if ( $client !== null ) {
				$client_id = $client['id'];
				$anonimous_id=0;
			} else {
				$client_id = 0;	
			}
			$coupon_del = [];
			$coupon_del['code'] 		= $code;
			$coupon_del['type'] 		= 'coupon';
			$coupon_del['client_id'] 	= $client_id;
			$coupon_del['anonimous_id'] = $anonimous_id;
			$res = $this->m_cart->delete_coupon($coupon_del);
			if ($res == 1 || $res) {
				$status = array('status'=>'success','message'=>'Success.');
				$this->response($status, REST_Controller::HTTP_OK);	
			} else {
				$status = array('status'=>'failure','message'=>'Something went wrong.');
				$this->response($status, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);	
			}

		} else {
			$this->response(array('status'=>'failure','message'=>'Bad Request.'), REST_Controller::HTTP_BAD_REQUEST);
		}
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

	public function generate_client_order_mail($info, $order_items, $attributes)
	{
		$message =  '<table class="table table-striped">
			            <thead>
			            <tr>
			              <th>Название</th>
			              <th>Описание</th>
			              <th>Аттрибуты</th>
			              <th>Цена</th>
			              <th>Количество</th>
			              <th>Общая цена</th>
			            </tr>
			            </thead>
			            <tbody>';
        if (isset($order_items) && $order_items) {
			foreach ($order_items as $item) {
				$message .='<tr>';

				$message .=	'<td>'. $item['set_name'] .'</td>';
				
				$message .='<td>';
				
				$temp_items = json_decode($item['set_products'], true);
				if (isset($temp_items)) {
					$message .= "<small><ol>";
					foreach ($temp_items as $product) {
					$message .= '<li>'.$product['name'] .' - '. $product['qty'] .'шт.'.'</li>';
					}
					$message .= "</ol></small>";
				}
				
				$message .= '</td>';
				
				$message .= '<td>';
				if (isset($attributes) && $attributes){
					$message .='<ol>';
					foreach ($attributes as $attr){
						if ($attr['type'] !=='coupon') {
							if ($attr['set_id'] == $item['set_id']) {
								$message .= '<li>'
								.' | '.$attr['name']
								.'-' .$attr['qty'].'шт.'
								.'=> '  .$attr['unit_price']
								.' ' .$attr['mmt_name']
								.'</li>'; 
							}
						}
					}
					$message .= '</ol>';
				}
				$message .= '</td>';
				$message .= '<td>';
				$message .= number_format($item['unit_price'],0,'',',') . $item['mmt_name'];
				$message .= '</td>';
				$message .= '<td>';
				$message .= $item['qty']; 
				$message .= '</td>';
				$message .= '<td>';
				$message .= number_format($item['unit_price']*$item['qty'],0,'',',') . $item['mmt_name'];
				$message .= '</td></tr>';
			}
			$message.='</tbody></table>';
		}
		return $message;
	}

}
 ?>
