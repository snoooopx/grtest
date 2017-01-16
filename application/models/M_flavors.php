<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* M_flavors Class 
*/
class M_flavors extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}

	
	/*
	|---------------------------------------------------------------------------------
	| Get (all) flavor(s)
	|---------------------------------------------------------------------------------
	*/
	function get_flavors( $flavor_id=0, $criterions=FALSE )
	{
		$select = '
					fl.id, 
					fl.name, 
					fl.description
				';

		$condition =array();

		// Check for all/singe Select and Get Type (brief/..)
		if ( $flavor_id > 0 )
		{
			$condition = array( 'fl.id' => $flavor_id );
		}

		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->like('fl.name', $criterions['q']);
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		// Building Statement
		$this->db->select( $select )
				 ->from( 'mb_flavors as fl' )
				 ->where( $condition )
				 ->order_by('fl.name');

		// Get Result
		$res = $this->db->get();
		

		if ( $res->num_rows() > 0 ) 
		{
			$finalResult['items'] = $res->result_array();
			
			if ( $criterions['q'] == FALSE )
			{
				//GET TOTAL
				$totalRecords = $this->db->count_all_results('mb_flavors');
				$finalResult['itemCount'] = $totalRecords;
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->like('name', $criterions['q']);
				$this->db->from('mb_flavors');

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
	}//#get_flavors


	/*
	|---------------------------------------------------------------------------------
	| Insert Flavor
	|---------------------------------------------------------------------------------
	*/
	function insert( $flavor )
	{
		$res = $this->db->insert( 'mb_flavors', $flavor );

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
	| Update Flavor Info
	|---------------------------------------------------------------------------------
	*/
	function update( $flavor )
	{
		$this->db->where( 'id', $flavor['id'] );
		$this->db->update( 'mb_flavors', $flavor );
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
									  'tbl' => 'mb_products',
								  'to_show' => 'Продукты',
								   'col_id' => 'flavor_id',
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
	| Delete flavor
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'mb_flavors' );
		
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
|End of M_flavors Class
|---------------------------------------------------------------------------------
*/
}
?>