<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* M_attrgroups Class 
*/
class M_attrgroups extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}

	
	/*
	|---------------------------------------------------------------------------------
	| Get (all) attrgroup(s)
	|---------------------------------------------------------------------------------
	*/
	function get_attrgroups( $attrgroup_id=0, $criterions=FALSE )
	{
		$select = '
					atgr.id, 
					atgr.name, 
					atgr.description,
					atgr.is_enabled
				';

		$condition =array();

		// Check for all/singe Select and Get Type (brief/..)
		if ( $attrgroup_id > 0 )
		{
			$condition = array( 'atgr.id' => $attrgroup_id );
		}

		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->like('atgr.name', $criterions['q']);
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		// Building Statement
		$this->db->select( $select )
				 ->from( 'mb_attribute_groups as atgr' )
				 ->where( $condition )
				 ->order_by('atgr.name');

		// Get Result
		$res = $this->db->get();
		

		if ( $res->num_rows() > 0 ) 
		{
			$finalResult['items'] = $res->result_array();
			
			if ( $criterions['q'] == FALSE )
			{
				//GET TOTAL
				$totalRecords = $this->db->count_all_results('mb_attribute_groups');
				$finalResult['itemCount'] = $totalRecords;
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->like('name', $criterions['q']);
				$this->db->from('mb_attribute_groups');

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
	}//#get_attrgroups


	/*
	|---------------------------------------------------------------------------------
	| Insert AttrGroups
	|---------------------------------------------------------------------------------
	*/
	function insert( $attrgroup )
	{
		$res = $this->db->insert( 'mb_attribute_groups', $attrgroup );

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
	| Update AttrGroups Info
	|---------------------------------------------------------------------------------
	*/
	function update( $attrgroup )
	{
		$this->db->where( 'id', $attrgroup['id'] );
		$this->db->update( 'mb_attribute_groups', $attrgroup );
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
									  'tbl' => 'mb_attributes',
								  'to_show' => 'Аттрибуты',
								   'col_id' => 'attrgroup_id',
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
	| Delete attrgroup
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'mb_attribute_groups' );
		
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
|End of M_attrgroups Class
|---------------------------------------------------------------------------------
*/
}
?>