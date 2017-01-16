<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| m_cart Model Class for cart db manipulations
*/
class M_cart extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	/*
	#########################################################################################
	#	Get Full Cart
	#########################################################################################
	*/
	public function get_cart( $client_id, $anonimous_id )
	{
		$condition = [];
		if ( isset($client_id) && $client_id ) {
			$condition['client_id'] = $client_id;
		}

		if ( isset($anonimous_id) && $anonimous_id) {
			$condition['anonimous_id'] = $anonimous_id;
		}

		/*if ($anonimous_id && $client_id==false) {
			return array( 'sets' => [], 'attrs' => [] );
		}*/
		$res = $this->db->select('
									c.id,
									c.set_id,
									s.name as set_name,
									s.featured_image,
									c.set_products,
									c.qty,
									c.unit_price,
									c.mmt_id,
									mmt.name as mmt_name
								')
						->from('mb_cart c')
						->join('mb_sets as s', 	 's.id=c.set_id', 	'left')
						->join('app_mmt as mmt', 'mmt.id=c.mmt_id', 'left')
						->where($condition)
						->get();

		/*echo $this->db->get_compiled_select();
		exit;*/
		// Generate and union attributes and coupon queries
		// because of with join cannot get coupon. it`s attr_id is null
		$this->db->select('
							ca.id,
							ca.anonimous_id,
							ca.client_id,
							ca.set_id,
							ca.attr_id,
							ca.custom_text,
							ca.qty,
							ca.unit_price,
							ca.type,
							a.name,
							ca.coupon_code,
							ca.coupon_discount_type,
							ca.coupon_discount_value,
							mmt.name as mmt_name,
							a.attrgroup_id,
							a.featured_image,
							a.allow_user_text
						')
			  ->from('mb_cart_attributes ca')
			  ->join('mb_attributes as a', 'a.id=ca.attr_id', 'left')
			  ->join('app_mmt as mmt', 'mmt.id=a.mmt_id')
			  ->where($condition);
			  //->get();

		$q1 = $this->db->get_compiled_select();
		// Adding type=coupon in condition for getting cart coupon only
		$condition['type'] = 'coupon';
		$this->db->select('
							id,
							anonimous_id,
							client_id,
							set_id,
							attr_id,
							custom_text,
							qty,
							unit_price,
							type,
							1 as name,
							coupon_code,
							coupon_discount_type,
							coupon_discount_value,
							1 as mmt_name,
							1 as attrgroup_id,
							1 featured_image,
							1 allow_user_text
							')
						  ->from('mb_cart_attributes')
						  ->where($condition);
						  //->get();

		$q2 = $this->db->get_compiled_select();
		$q = $q1 . ' UNION DISTINCT ' . $q2;

		$res_attrs = $this->db->query($q);
		
		return array(
						'sets' => $res->result_array(),
						'attrs' => $res_attrs->result_array()
			);
	}


	/*########################################################################
	# Function for getting cart client/anonimous count items count
	########################################################################*/
	public function get_cart_count()
	{
		$anonimous_id = $this->session->userdata('anonimousc');
		$client = $this->session->userdata('fclient_logged_in');
		//Check For Logged In Client
		if ( $client !== null ) {
			$client_id = $client['id'];
			$anonimous_id = false;
		} elseif($anonimous_id !== null) {
			$client_id = false;	
		} else {
			return 0;
		}
		$this->load->model('m_cart');
		return $this->m_cart->get_cart_items_count($client_id,$anonimous_id);
	}


	/*
	#########################################################################################
	#	Get Cart Items Count
	#########################################################################################
	*/
	public function get_cart_items_count($client_id,$anonimous_id)
	{
		if (isset($client_id) && $client_id) {
			$condition['client_id'] = $client_id;
		}

		if (isset($anonimous_id) && $anonimous_id) {
			$condition['anonimous_id'] = $anonimous_id;
		}

		$this->db->where($condition);
		$res = $this->db->count_all_results('mb_cart');
		return $res;
	}
	/*
	#########################################################################################
	#	Insert New Item Into Cart
	#########################################################################################
	*/
	public function add_item($item)
	{
		$res1 = $this->delete_cart_item('item',  $item['set']['client_id'], $item['set']['anonimous_id'], $item['set']['set_id']);
		//$res2 = $this->delete_cart_item('attributes', $item['set']['client_id'], $item['set']['anonimous_id'], $item['set']['set_id']);
		$res3 = $this->insert_cart_item($item['set']);
		$res4 = $this->insert_cart_item_attrs($item['attrs']);
		
		if ( $res1 /*&& $res2*/ && $res3 && $res4 ) {
			return true;
		} else {
			return false;
		}

	}

	/*
	#########################################################################################
	#	Insert New Item Into DB
	#########################################################################################
	*/
	public function insert_cart_item($item)
	{
		if (isset($item)) {
			$this->db->insert('mb_cart',$item);
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
	/*
	#########################################################################################
	#	Insert New Item Attribute Into DB
	#########################################################################################
	*/
	public function insert_cart_item_attrs($attrs)
	{
		if (isset($attrs)) {
			$this->db->insert_batch('mb_cart_attributes', $attrs);
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/*
	#########################################################################################
	#	Update Cart item Qty
	#########################################################################################
	*/
	public function update_item( $client_id, $anonimous_id, $set_id, $qty)
	{
		$condition = [];
		if (isset($client_id) && $client_id) {
			$condition['client_id'] = $client_id;
		}

		if (isset($anonimous_id) && $anonimous_id) {
			$condition['anonimous_id'] = $anonimous_id;
		}

		if (isset($set_id)  && $set_id !== '') {
			$condition['set_id'] = $set_id;
		} else {
			return false;
		}

		$this->db->set( 'qty', $qty )
				 ->where( $condition )
				 ->update( 'mb_cart' );

		if ( $this->db->affected_rows() > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	/*
	#########################################################################################
	#	Remove Item From Cart
	#########################################################################################
	*/
	public function delete_cart_item($type='', $client_id, $anonimous_id, $set_id='')
	{
		$condition = [];
		if (isset($client_id) && $client_id && $client_id!=0) {
			$condition['client_id'] = $client_id;
		}

		if (isset($anonimous_id) && $anonimous_id) {
			$condition['anonimous_id'] = $anonimous_id;
		}

		// if set_id is not empty remove specified set
		// else remove all if type is 'all'
		if (isset($set_id) && $set_id !== '') {
			$condition['set_id'] = $set_id;
		}

		//return $condition;
		$this->db->where($condition);
		$status = [];

		if ($type == 'item' || $type == 'all') {
			try {
					$this->db->delete('mb_cart_attributes');
					$this->db->where($condition);
					$this->db->delete('mb_cart');
					$status['status'] = 'success';
					$status['message'] = $this->db->affected_rows().$client_id
.'-'.$anonimous_id;
			} catch (Exception $e) {
				$status['status'] = 'failure';
				$status['message'] = $e->getMessage();
			}
		} else if( $type == 'attributes') {
			$this->db->delete('mb_cart_attributes');
		} else {
			return false;
		}
		return $status;
	}


	/*
	#########################################################################################
	#	Empty Cart
	#########################################################################################
	*/
	public function clear_cart($client_id, $anonimous_id, $set_id )
	{
		$res1 = $this->delete_cart_item('item', $client_id, $anonimous_id, $set_id );
		//$res2 = $this->delete_cart_item('attributes', $client_id, $anonimous_id );
		if ($res1 ) {
			return true;
		} else {
			return false;
		}
	}

	/*
	#########################################################################################
	#	Associate Anonimous Cart With Logged in client
	#########################################################################################
	*/
	public function assoc_anon_client($client_id,$anonimous_id)
	{
		// 0.0 Get Client cart
		$client_cart_items = $this->get_cart($client_id,false);
		//print_r(array_column($client_cart_items['sets'],'set_id'));
		if (!empty($client_cart_items['sets'])) 
		{
			// 0.1 Delete Client items From Anon Cart
			$this->db->where('anonimous_id', $anonimous_id);
			$this->db->where_in('set_id', array_column($client_cart_items['sets'],'set_id'));
			$this->db->delete('mb_cart');

			// 0.2 Delete Client items Attributes From Anon Cart
			$this->db->where('anonimous_id', $anonimous_id);
			$this->db->where_in('set_id', array_column($client_cart_items['sets'],'set_id'));
			$this->db->delete('mb_cart_attributes');
		}

		// 0.3 Update anonimous Cart client_id 
		$this->db->set('client_id', $client_id);
		$this->db->where('anonimous_id', $anonimous_id);
		$this->db->update('mb_cart');

		// 0.4 Update anonimous Cart Attributes client_id 
		$this->db->set('client_id', $client_id);
		$this->db->where('anonimous_id', $anonimous_id);
		$this->db->update('mb_cart_attributes');

		// 0.5 Update client Cart Anon id
		$this->db->set('anonimous_id', $anonimous_id);
		$this->db->where('client_id', $client_id);
		$this->db->update('mb_cart');

		// 0.6 Update client Cart Attributes Anon id
		$this->db->set('anonimous_id', $anonimous_id);
		$this->db->where('client_id', $client_id);
		$this->db->update('mb_cart_attributes');
	}

	/*
	#########################################################################################
	#	Get Coupon Code
	#########################################################################################
	*/
	public function get_coupon($coupon) 
	{
		if (isset($coupon) and $coupon) {
			$this->load->model('m_coupons');
			$res = $this->m_coupons->get_coupons(0, false,array('code'=>$coupon));

			if ( isset($res['items'][0]) && !empty($res['items'][0])) {
				return $res['items'][0];
			}
			return false;
		}
	}
	
	/*
	#########################################################################################
	#	Insert Coupon Code
	#########################################################################################
	*/
	public function insert_coupon($coupon)
	{
		//Delete Old
		$this->delete_coupon($coupon);
		//Insert New
		$this->db->insert('mb_cart_attributes',$coupon);
		if ($this->db->insert_id()) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/*
	#########################################################################################
	#	Delete Coupon Code
	#########################################################################################
	*/
	public function delete_coupon($coupon)
	{
		$condition=[];
		$condition['type'] 			= $coupon['type'];
		$condition['client_id'] 	= $coupon['client_id'];
		$condition['anonimous_id'] 	= $coupon['anonimous_id'];
		// Delete
		$this->db->where($condition);
		$this->db->delete('mb_cart_attributes');
		if (!$this->db->affected_rows()) {
			return 1;
		} else {
			return true;
		}
	}

	/*
	#########################################################################################
	#	Get Shipping Zones
	#########################################################################################
	*/
	public function get_shipping_zones($id='')
	{
		if ($id!='') 
		{
			$this->db->where('id',$id);
		}
		$res = $this->db->select()
						->from('mb_shipping_zones')
						->get();

		if ($res->num_rows() > 0) {
			return $res->result_array();
		} else {
			return false;
		}
	}
	/*
	#########################################################################################
	#	Get Shipping Types / Periods
	#########################################################################################
	*/
	public function get_shipping_types_periods()
	{
		$res = $this->db->select('
									sht.name as type,
									shp.name as period,
									sht.is_everyday

								')
						->from('mb_shipping_types as sht')
						->join('mb_shipping_type_periods as shtp', 'shtp.shpt_id=sht.id', 'left')
						->join('mb_shipping_periods as shp', 'shtp.shpp_id=shp.id', 'left')
						->get();

		if ($res->num_rows() > 0) {
			return $res->result_array();
		} else {
			return false;
		}				

	}

	/*
	#########################################################################################
	#	Get Shipping Types
	#########################################################################################
	*/
	public function get_shipping_types()
	{
		$res = $this->db->select('
									sht.id,
									sht.shtcode,
									sht.name as type,
									sht.is_everyday

								')
						->from('mb_shipping_types as sht')
						->get();

		if ($res->num_rows() > 0) {
			return $res->result_array();
		} else {
			return false;
		}				

	}

	/*
	#########################################################################################
	#	Get Shipping Periods
	#########################################################################################
	*/
	public function get_shipping_periods()
	{
		$res = $this->db->select('
									shp.id,
									shp.name as period
								')
						->from('mb_shipping_periods as shp')
						->get();

		if ($res->num_rows() > 0) {
			return $res->result_array();
		} else {
			return false;
		}				

	}


	/*
	#########################################################################################
	#	Get Payment Methods
	#########################################################################################
	*/
	public function get_payment_methods()
	{
		$res = $this->db->select()
						->from('mb_payment_methods')
						->get();

		if ($res->num_rows() > 0) {
			return $res->result_array();
		} else {
			return false;
		}				

	}


	/*
	#########################################################################################
	#	Get Last Order Id
	#########################################################################################
	*/
	public function get_next_order_id()
	{
		$res = $this->db->select('MAX(o_id) as o_id')
						->from('mb_orders')
						->order_by('o_id','desc')
						->get();

		if ($res->num_rows()>0 && isset($res->result_array()[0]['o_id']) && $res->result_array()[0]['o_id']) 
		{
			$current = $res->result_array()[0]['o_id'];
			$current +=1;
			return $current;
		} else {
			return 3400;
		}
	}


	/*
	#########################################################################################
	#	Insert New order
	#########################################################################################
	*/
	public function insert_order( $order_data )
	{
		$this->db->insert('mb_orders', $order_data);

		if ($this->db->insert_id()) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}



	/*
	#########################################################################################
	#	Insert order Items
	#########################################################################################
	*/
	public function insert_order_items( $item_data )
	{
		$this->db->insert_batch('mb_order_items', $item_data);
		if ($this->db->insert_id()) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/*
	#########################################################################################
	#	Insert order Item attributes
	#########################################################################################
	*/
	public function insert_order_item_attributes( $attr_data )
	{
		$this->db->insert_batch('mb_order_item_attributes', $attr_data);
		if ($this->db->insert_id()) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/*
	#########################################################################################
	#	Insert order History
	#########################################################################################
	*/
	public function insert_order_history( $attr_data )
	{
		$this->db->insert('mb_order_history', $attr_data);
		if ($this->db->insert_id()) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/*
	#########################################################################################
	#	Get order(s)
	#########################################################################################
	*/
	/*function get_orders( $client_id=0, $order_id=0, $criterions=FALSE )
	{
		$select = '
					o.id,
					o.o_status_id,
					ost.name as order_status,
				  	CONCAT( o.o_id_prfx, o_id ) as order_id,
				  	o.created,
				  	o.total,
				  	o.shp_price,
				  	o.shp_type_id,
				  	shpt.name as shp_type,
				  	o.shp_city,
				  	o.shp_street,
				  	o.shp_bld,
				  	o.shp_apt,
				  	o.shp_date,
				  	o.shp_period_id,
				  	shpp.name as shp_period,
				  	o.coupon_code,
				  	o.pmt_type_id
				';

		$condition =array();

		if ($client_id!=0) 
		{
			$condition['cl.id'] = $client_id;
		}

		// Check for all/single Select 
		if ( $order_id == 0 && $criterions == false )
		{
			$this->db->order_by('o.o_id','desc');
		}
		elseif( $order_id != 0 && $criterions == false )
		{
			$condition = array( 'o.id' => $order_id );
		}
		else
		{
			$this->db->order_by('o.o_id','desc');
		}

		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== FALSE ) 
		{
			$oartPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $oartPage );
			$this->db->like('o.o_id', $criterions['q']);
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		// Building Statement
		$this->db->select( $select )
				 ->from( 'mb_orders as o' )
				 ->join( 'mb_clients cl', 			 'cl.id=o.client_id', 	'left')
				 ->join( 'mb_order_statuses ost', 	 'ost.st_id=o.o_status_id', 		'left')
				 ->join( 'mb_payment_methods pm', 	 'pm.id=o.pmt_type_id', 			'left')
				 ->join( 'mb_payment_statuses pst',  'pst.ps_id=o.pmt_status_id', 	'left')
				 ->join( 'mb_shipping_periods shpp', 'shpp.id=o.shp_period_id', 	'left')
				 ->join( 'mb_shipping_types shpt',   'shpt.id=o.shp_type_id', 	'left')
				 ->join( 'mb_shipping_zones shpz', 	 'shpz.id=o.shp_zone_id', 	'left')
				 ->where( $condition );

		// Get Result
		$res = $this->db->get();
		

		if ( $res->num_rows() > 0 ) 
		{
			$finalResult['items'] = $res->result_array();
			
			if ( $criterions == FALSE )
			{
				//GET TOTAL
				$totalRecords = $this->db->count_all_results('mb_orders');
				$finalResult['itemCount'] = $totalRecords;
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->like('o_id', $criterions['q']);
				$this->db->from('mb_orders');

				$totalFilteredRecords = $this->db->count_all_results();
				
				$finalResult['itemFilteredCount'] = $totalFilteredRecords;
				$finalResult['itemCount'] = $totalFilteredRecords;
			}


			return $finalResult;
		}
		else
		{
			$finalResult['items'] = [];
			$finalResult['itemCount'] = 0;
			$finalResult['itemFilteredCount'] = 0;
			return $finalResult;
		}
	}//#get_orders*/

}
?>