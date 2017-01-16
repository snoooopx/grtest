<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_attributes Class 
*/
class M_attributes extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}

	
	/*
	|---------------------------------------------------------------------------------
	| Get (all) Attributes(s)
	|---------------------------------------------------------------------------------
	*/
	function get_attributes( $attribute_id=0, $brief=FALSE, $criterions=FALSE, $qstr=false )
	{
		//Get Logged In User From Session
		$logged_in_user = $this->session->userdata('logged_in');

		// Condition Array for Where Clause
		$condition = array();

		$full_select = '
						attr.id,
						attr.name,
						attr.created,
						attr.last_modified,
						attr.description,
						attr.unit_price as price,
						attr.featured_image,
						attr.mmt_id,
						mmt.name as mmt_name,
						mmt.sign,
						mmt.sign_image,
						attr.attrgroup_id,
						attrgr.name as attrgroup_name,
						attr.allow_user_text,
						attr.is_active
					';
						/*bt.name btype*/
		
		$brief_select = '
						attr.id,
						attr.name,
						attrgr.name as attrgroup_name,
						attr.unit_price as price,
						attr.mmt_id,
						mmt.name as mmt_name,
						attr.allow_user_text
					';
		
		$select = '';

		$is_brief = false;
		// Check for all/singe Select and Get Type (brief/..)
		if ( $attribute_id == 0 && $brief === FALSE ) 
		{
			$select = $full_select;
		}
		else if ( $attribute_id == 0 && $brief == TRUE )
		{
			$is_brief = true;
			$select = $brief_select;
			$condition['attr.is_active'] = '1';
		}
		else if ( $attribute_id > 0 && $brief == FALSE )
		{
			$select = $full_select;
			$condition['attr.id'] = $attribute_id;
		}
		else if ( $attribute_id > 0 && $brief == TRUE )
		{
			$is_brief = true;
			$select = $brief_select;
			$condition['attr.id'] = $attribute_id;
			$condition['attr.is_active'] = '1' ;
		}
		else
		{
			$is_brief = true;
			$select = $brief_select;
		}

		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->group_start();
			$this->db->like('attr.name', $criterions['q']);
			$this->db->group_end();
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		if ($is_brief) 
		{
			$this->db->group_start();
			$this->db->like('attr.name',$qstr);
			$this->db->or_like('attrgr.name', $qstr);
			$this->db->group_end();
		}
		// Building Statement
		$this->db->select($select)
				 ->from('mb_attributes as attr')
				 ->join('mb_attribute_groups as attrgr', 'attrgr.id=attr.attrgroup_id', 'left')
				 ->join('app_mmt as mmt', 'mmt.id=attr.mmt_id', 'left')
				 ->where($condition);
	
		//echo $this->db->get_compiled_select();
		//print_r($condition);
		//die;
		// Get Result
		$res = $this->db->get();
		

		if ( $res->num_rows() > 0 ) 
		{
			$totalFilteredRecords = $res->num_rows();
			
			$finalResult['items'] = $res->result_array();
			
			$this->db->select('attr.id')
					 ->from('mb_attributes as attr' )
					 ->where( $condition );
			

			if ( $criterions['q'] == FALSE )
			{
				//GET TOTAL
				$res_total_records = $this->db->get();
				$finalResult['itemCount'] = $res_total_records->num_rows();
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->group_start()
							->like('attr.name', $criterions['q'])
						->group_end();
				
				$res_total_filtered_records = $this->db->get();
					
				$totalFilteredRecords = $res_total_filtered_records->num_rows();
				
				$finalResult['numrows'] = $totalFilteredRecords;
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
	}//#get_attributes



	/*
	|---------------------------------------------------------------------------------
	| Insert Attributes
	|---------------------------------------------------------------------------------
	*/
	function insert( $attribute )
	{
		$res = $this->db->insert( 'mb_attributes', $attribute );

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
	|---------------------------------------------------------------------------------
	| Update Attributes Info
	|---------------------------------------------------------------------------------
	*/
	function update( $attribute )
	{
		$this->db->where( 'id', $attribute['id'] );
		$this->db->update( 'mb_attributes', $attribute );
		return $this->db->affected_rows();
	}
	

	/*
	|---------------------------------------------------------------------------------
	| Check Before Delete Attributes
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
									  'tbl' => 'mb_set_attributes',
								  'to_show' => 'Сеты',
								   'col_id' => 'attr_id',
									'value' => $id 
								),
							array(
									  'tbl' => 'mb_cart_attributes',
								  'to_show' => 'Корзина',
								   'col_id' => 'attr_id',
									'value' => $id 
								),
							array(
									  'tbl' => 'mb_order_item_attributes',
								  'to_show' => 'Ордеры',
								   'col_id' => 'attr_id',
									'value' => $id 
								)
							// add order table validation
						);

		// Check For User Constraints
		foreach ($checkable_tables as $searchable)
		{
			$res = $this->db->select()
							->from($searchable['tbl'])
							->where($searchable['col_id'],$searchable['value'])
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
	| Delete Attributes
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'mb_attributes' );
		
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
|End of m_attributes Class
|---------------------------------------------------------------------------------
*/
}
?>