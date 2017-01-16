<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* M_orders Class 
*/
class M_orders extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}
	/*######################################################################################
	 # Get Order Statuses
	######################################################################################*/
	function get_order_statuses($id=0)
	{
		if($id!=0){
			$this->db->where('st_id',$id);
		}
		$res = $this->db->select()->from('mb_order_statuses')->order_by('sort_order','asc')->get();
		
		if($res->num_rows()>0){
			return $res->result_array();
		} else {
			return false;
		}
	}
	
	/*######################################################################################
	 # Get Payment Statuses
	######################################################################################*/
	function get_pmt_statuses($id=0)
	{
		if($id!=0){
			$this->db->where('ps_id',$id);
		}
		$res = $this->db->select()->from('mb_payment_statuses')->order_by('sort_order','asc')->get();
		
		if($res->num_rows()>0){
			return $res->result_array();
		} else {
			return false;
		}
	}
	
	/*######################################################################################
	 # Get Payment Statuses
	######################################################################################*/
	function get_ongoing_order_qty()
	{
		$res = [];
		$res['news'] = $this->db->where('o_status_id','10')->from('mb_orders')->count_all_results();
		$res['inprogress'] = $this->db->where('o_status_id','11')->from('mb_orders')->count_all_results();
			
		return $res;
	}
	
	/*######################################################################################
	 # Get (all) order(s)
	######################################################################################*/
	function get_orders($order_id=0, $criterions=FALSE, $client_id=0 )
	{
		$select = '
					o.id,
					o.o_status_id,
					ost.name as order_status,
					CONCAT( o.o_id_prfx, o_id ) as order_id,
					o.created,
					o.modified,
					o.total,
					mmt.name as mmt_name,
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
					shpz.name as shp_zone,
					o.coupon_code,
					o.pmt_type_id,
					pm.name as pmt_name,
					o.pmt_status_id,
					pst.name as pmt_status,
					CONCAT(cl.fname," ",cl.sname) as client_name,
					cl.email as client_email,
					cl.phone as client_phone,
					o.coupon_code,
					coupon.type as coupon_type,
					coupon.discount as coupon_discount,
					coupon.start_date as coupon_start_date,
					coupon.end_date as coupon_end_date
				';

		$condition =array();

		if ($client_id!=0) 
		{
			$condition['cl.id'] = $client_id;
		}
		else if($client_id==0 && $order_id==0 && $criterions == false)
		{
			return array('items'=>[]);
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
				 ->join( 'mb_clients cl', 			 'cl.id=o.client_id', 			'left')
				 ->join( 'mb_order_statuses ost', 	 'ost.st_id=o.o_status_id', 	'left')
				 ->join( 'mb_payment_methods pm', 	 'pm.id=o.pmt_type_id', 		'left')
				 ->join( 'mb_payment_statuses pst',  'pst.ps_id=o.pmt_status_id', 	'left')
				 ->join( 'mb_shipping_periods shpp', 'shpp.id=o.shp_period_id', 	'left')
				 ->join( 'mb_shipping_types shpt',   'shpt.id=o.shp_type_id', 		'left')
				 ->join( 'mb_shipping_zones shpz', 	 'shpz.id=o.shp_zone_id', 		'left')
				 ->join( 'mb_coupons coupon', 	 	 'coupon.code=o.coupon_code', 	'left')
				 ->join( 'app_mmt as mmt', 			  'mmt.id=o.mmt_id', 			'left')
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
	}//#get_orders


	/*######################################################################################
	 # Get FULL Order 
	######################################################################################*/
	public function get_full_order($order_id='',$client_id=0)
	{
		$res = [];
		$res['items'] 	= [];
		$res['attrs'] 	= [];
		$res['history'] = [];
		// Call get_orders function
		$res['info'] = $this->get_orders( $order_id, false, $client_id )['items'];
		
		if(!empty($res['info']))
		{
			// get order items (products are included in it)
			$res['items'] = $this->get_items($order_id);
			// get order item attributes 
			$res['attrs'] = $this->get_item_attributes($order_id);
			// get order history
			$res['history'] = $this->get_history($order_id);
		}

		return $res;

	}
	
	/*######################################################################################
	 # Get order items
	######################################################################################*/
	public function get_items($order_id)
	{
		// this order_id is tables ai value
		$res = $this->db->select('
									or_i.id,
									or_i.order_id,
									or_i.set_id,
									set.name as set_name,
									set.type as set_type,
									set.defined_count,
									set.featured_image,
									or_i.set_products,
									or_i.qty,
									or_i.unit_price,
									or_i.mmt_id,
									mmt.name as mmt_name
								')
						->from('mb_order_items as or_i')
						->join('mb_sets as set', 'set.id=or_i.set_id', 'left')
						->join('app_mmt as mmt', 'mmt.id=or_i.mmt_id', 'left')
						->where('order_id',$order_id)
						->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		} else {
			return false;
		}
	}

	/*######################################################################################
	 # Get order item attributes
	######################################################################################*/
	public function get_item_attributes($order_id)
	{
		// this order_id is tables ai value
		$res = $this->db->select('
									or_ia.id,
									or_ia.order_id,
									or_ia.set_id,
									or_ia.attr_id,
									attr.name as attr_name,
									attr.featured_image,
									attr.allow_user_text,
									attr.attrgroup_id as attrgr_id,
									attrgr.name as attrgr_name,
									or_ia.custom_text,
									or_ia.qty,
									or_ia.unit_price,
									or_ia.mmt_id,
									mmt.name as mmt_name

								')
						->from('mb_order_item_attributes as or_ia')
						->join('mb_attributes as attr', 'attr.id=or_ia.attr_id', 'left')
						->join('mb_attribute_groups as attrgr', 'attrgr.id=attr.attrgroup_id', 'left')
						->join('app_mmt as mmt', 'mmt.id=or_ia.mmt_id', 'left')
						->where('order_id', $order_id)
						->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		} else {
			return false;
		}
	}


	/*######################################################################################
	 # Get order history
	######################################################################################*/
	public function get_history($order_id)
	{
		// this order_id is tables ai value
		$res = $this->db->select('')
						->from('mb_order_history')
						->where('order_id',$order_id)
						->order_by('date','desc')
						->get();
		if ($res->num_rows() > 0) {
			return $res->result_array();
		} else {
			return false;
		}
	}


	/*
	|---------------------------------------------------------------------------------
	| Update Order Info
	|---------------------------------------------------------------------------------
	*/
	function update( $order )
	{
		$this->db->where( 'id', $order['id'] );
		$this->db->update( 'mb_orders', $order );
		return $this->db->affected_rows();
	}

	/*
	|---------------------------------------------------------------------------------
	| Check Before Delete User
	|---------------------------------------------------------------------------------
	*/
	public function check_before_delete($id)
	{
		// Return Message Variable
		$message='';
		// Return Status
		$status=true;

		// Array For checkable Tables with their ids
		$checkable_tables = 
					array(
							array(
									  'tbl' => 'mb_order_items',
								  'to_show' => 'Ордеры',
								   'col_id' => 'order_id',
									'value' => $id 
								),
							array(
									  'tbl' => 'mb_cart_attributes',
								  'to_show' => 'Корзина',
								   'col_id' => 'order_id',
									'value' => $id 
								)
						);

		// Check For User Constraints
		foreach ($checkable_tables as $searchable)
		{
			$res = $this->db->select()
							->from($searchable['tbl'])
							->where($searchable['col_id'], $searchable['value'])
							->get();
			
			//if exists generate message
			if ( $res->num_rows() > 0 ) 
			{
				$status = false;
				$message .= ' / '.$searchable['to_show'];
			}		
		}

		return array('status'=>$status,'message'=>$message);
	}

	/*
	|---------------------------------------------------------------------------------
	| Delete order
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'mb_orders' );
		
		if ( $this->db->affected_rows() ) 
		{
			return array('status' => true,'message' => 'success');
		}
		else
		{
			return  array('status' => false,'message' => 'failure');
		}
	}


	




/*
|---------------------------------------------------------------------------------
|End of M_orders Class
|---------------------------------------------------------------------------------
*/
}
?>