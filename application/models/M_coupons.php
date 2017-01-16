<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* M_coupons Class 
*/
class M_coupons extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}

	
	/*
	|---------------------------------------------------------------------------------
	| Get (all) coupon(s)
	|---------------------------------------------------------------------------------
	*/
	function get_coupons( $coupon_id=0, $criterions=FALSE, $shop_params=FALSE )
	{
		$select = '
					c.id,
					c.code,
					c.description,
					c.type,
					c.discount,
					c.start_date,
					c.end_date,
					c.is_enabled
				';

		$condition =array();

		// Check for all/single Select 
		if ( $coupon_id == 0 && $criterions == false && $shop_params !== false )
		{
			$condition = array( 'c.code' => $shop_params['code'] );
		}
		elseif( $coupon_id != 0 && $criterions == false )
		{
			$condition = array( 'c.id' => $coupon_id );
		}

		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->like('c.code', $criterions['q']);
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		// Building Statement
		$this->db->select( $select )
				 ->from( 'mb_coupons as c' )
				 ->where( $condition )
				 ->order_by('c.code');

		// Get Result
		$res = $this->db->get();
		

		if ( $res->num_rows() > 0 ) 
		{
			$finalResult['items'] = $res->result_array();
			
			if ( $criterions['q'] == FALSE )
			{
				//GET TOTAL
				$totalRecords = $this->db->count_all_results('mb_coupons');
				$finalResult['itemCount'] = $totalRecords;
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->like('code', $criterions['q']);
				$this->db->from('mb_coupons');

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
	}//#get_coupons


	/*
	|---------------------------------------------------------------------------------
	| Insert Coupon Info
	|---------------------------------------------------------------------------------
	*/
	function insert( $info )
	{
		$res = $this->db->insert( 'mb_coupons', $info );

		if ( $res ){
			return $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}
	
	/*
	|---------------------------------------------------------------------------------
	| Insert Coupon Users
	|---------------------------------------------------------------------------------
	*/
	function insert_coupon_users( $users )
	{
		$res = $this->db->insert_batch( 'mb_coupon_users', $users );

		if ( $res )
		{
			return $this->db->insert_id();
		}
		else
		{
			return false;
		}
	}

	/*
	// Get Coupon users
	*/
	public function get_coupon_users($id)
	{
		$res = $this->db->select('
									cus.id,
									cus.user_id as client_id,
									cl.name,
								')
						->from('mb_coupon_users as cus')
						->join('mb_clients as cl', 'cl.id=cu.user_id','left')
						->where('cu.coupon_id', $id)
						->get();
		if ($res->num_rows()>0) 
		{
			return $res->result_array();
		}
		else
		{
			return false;
		}
	}


	/*
	| Delete Coupon items
	*/
	public function delete_coupon_users($id)
	{
		$this->db->where('coupon_id',$id);
		$res = $this->db->delete('mb_coupon_users');
	}



	/*
	|---------------------------------------------------------------------------------
	| Update Coupon Info
	|---------------------------------------------------------------------------------
	*/
	function update( $coupon )
	{
		$this->db->where( 'id', $coupon['id'] );
		$this->db->update( 'mb_coupons', $coupon );
		return $this->db->affected_rows();
	}

	/*
	|---------------------------------------------------------------------------------
	| Check Before Delete Coupon
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
							/*array(
									  'tbl' => 'mb_orders',
								  'to_show' => 'Ордеры',
								   'col_id' => 'coupon_id',
									'value' => $id 
								)*/
						);

		// Check For Coupon Constraints
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
	| Delete coupon
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'mb_coupons' );
		
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
|End of M_coupons Class
|---------------------------------------------------------------------------------
*/
}
?>