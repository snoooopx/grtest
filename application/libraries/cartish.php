<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Cartish class for cart manipulations in session
*/
class Cartish
{
	
	function __construct()
	{
		
	}
	private static  $default_attr_items = array(
											'qty' =>'0',
										  'price' => '0.00',
										 'custom_text'=>''
									  );

	private static $default_set_items = array(
											'qty' =>'0',
										  'price' => '0.00'
										);

	private static $cart_name = 'macart';


	/*private static $cart = array(
							'sets' => array(
												'def_set_id' => array(
																		'qty' =>'0',
																	  'price' => '0.00'
																   'set_attrs' => array(
																					 	'def_attr_id'
											   												 		array(
																												'qty' =>'0',
																											  'price' => '0.00'
														   												 'custom_text'=>''
																										  )
																						)
																	)
											),
						);*/

	public function cart_name()
	{
		return Cartish::$cart_name;
	}

	//Generate Cart Array
	public function generate()
	{

		$def_set_id = 0;
		$def_attr_id = 0;
		$cart_array = [];
		$cart_array[Cartish::$cart_name]['sets'] = [];//[$def_set_id] = Cartish::$default_set_items;
		//$cart_array[Cartish::$cart_name]['set_attrs'][$def_set_id][$def_attr_id] = Cartish::$default_attr_items;
		return $cart_array;
	}

	public function get_cart()
	{
		$CI =& get_instance();
		return $CI->session->userdata($this->cart_name());
	}

	// Function for inserting new item into cart
	public function add_item($item)
	{
		$CI =& get_instance();
		if ($CI->session->userdata($this->cart_name()) == null)
		{
			$this->generate();
		}

		$cartish = $CI->session->userdata($this->cart_name());
		$set_id = $item['id'];
		
		if ( isset($cartish['sets'][$set_id]) ) 
		{
			$this->clear_cart();
		}
		
		$cartish = $this->process_temp_item( $cartish, $item );

		$CI->session->set_userdata($this->cart_name(), $cartish);
	}

	// Function for updating existing item info in cart
	public function update_item($id='')
	{
		
	}

	//function for removing item form cart
	public function delete_item($id='')
	{
		
	}

	public function clear_cart()
	{
		$CI =& get_instance();
		$CI->session->unset_userdata($this->cart_name());
	}
	
	public function process_temp_item($temp_cart, $item)
	{
		// Collect Set Values
		$set_id = $item['id'];
		$temp_cart['sets'][ $set_id ]['id']    = $set_id;
		$temp_cart['sets'][ $set_id ]['qty']   = $item['qty'];
		$temp_cart['sets'][ $set_id ]['price'] = $item['price'];
		
		// Collect Attributes Values
		foreach ($item['attrs'] as $key => $attr) 
		{
			$temp_cart[ 'sets' ][ $set_id ][ 'set_attrs' ][ $key ][ 'id' ] 		= $attr['id'];
			$temp_cart[ 'sets' ][ $set_id ][ 'set_attrs' ][ $key ][ 'qty' ]   	= $attr['qty'];
			$temp_cart[ 'sets' ][ $set_id ][ 'set_attrs' ][ $key ][ 'price' ] 	= $attr['price'];
			$temp_cart[ 'sets' ][ $set_id ][ 'set_attrs' ][ $key ][ 'text' ] 	= $attr['text'];
		}
		return $temp_cart;
	}

	
}
 ?>