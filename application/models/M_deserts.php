<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* M_deserts Class 
*/
class M_deserts extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}

	
	/*
	|---------------------------------------------------------------------------------
	| Get (all) desert(s)
	|---------------------------------------------------------------------------------
	*/
	function get_deserts( $desert_id=0, $criterions=FALSE, $shop_params=false )
	{
		$select = '
					des.id, 
					des.name, 
					des.description,
					des.show_in_menu,
					des.show_in_footer,
					des.is_enabled,
					des.parent_id
				';

		$condition =array();

		// Check for all/single Select 
		if ( $desert_id == 0 && $criterions == false && $shop_params !== false)
		{
			$condition = array( 'des.is_enabled' => '1' );
			$this->db->order_by('des.sort_order','asc');
		}
		elseif( $desert_id != 0 && $criterions == false )
		{
			$condition = array( 'des.id' => $desert_id );
		}
		else
		{
			$this->db->order_by('des.name');
		}
		
		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->like('des.name', $criterions['q']);
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		// Building Statement
		$this->db->select( $select )
				 ->from( 'mb_deserts as des' )
				 ->where( $condition );
				 

		// Get Result
		$res = $this->db->get();
		

		if ( $res->num_rows() > 0 ) 
		{
			$finalResult['items'] = $res->result_array();
			
			if ( $criterions['q'] == FALSE )
			{
				//GET TOTAL
				$totalRecords = $this->db->count_all_results('mb_deserts');
				$finalResult['itemCount'] = $totalRecords;
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->like('name', $criterions['q']);
				$this->db->from('mb_deserts');

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
	}//#get_deserts


	/*
	|---------------------------------------------------------------------------------
	| Insert Desert
	|---------------------------------------------------------------------------------
	*/
	function insert( $desert )
	{
		$res = $this->db->insert( 'mb_deserts', $desert );

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
	| Update Desert Info
	|---------------------------------------------------------------------------------
	*/
	function update( $desert )
	{
		$this->db->where( 'id', $desert['id'] );
		$this->db->update( 'mb_deserts', $desert );
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
								   'col_id' => 'desert_id',
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
	| Delete desert
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'mb_deserts' );
		
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
|End of M_deserts Class
|---------------------------------------------------------------------------------
*/
}
?>