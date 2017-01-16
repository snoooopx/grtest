<?php defined('BASEPATH') OR exit('No direct script access allowed');

if( !function_exists('check_ongoing_orders') ){
	function check_ongoing_orders(){
		$ci_this = &get_instance();
		
		$ci_this->load->model('m_orders');
		$res_qty = $ci_this->m_orders->get_ongoing_order_qty();
		
		return $res_qty;
	}
}

?>