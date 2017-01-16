<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_positions Model Class
*/
class M_positions extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	/*
	|---------------------------------------------------------------------------------
	|Get Postitoin List
	|---------------------------------------------------------------------------------
	*/
	public function get_positions($pos_id=0, $criterions=FALSE)
	{
		$condition = array();
		
		if ( $pos_id != 0 ) 
		{
			$condition = array('pos.id'=>$pos_id);
		}
		if ( $criterions['q'] !== FALSE ) {
			
		}

		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->like('pos.name', $criterions['q']);
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}
		
		//GET RESULT
 		$this->db->select( 'pos.id, 
 							pos.name, 
 							pos.note, 
 							dep.name as department, 
 							dep.id as depId,
 							1' );
		$this->db->from( 'positions as pos' );
		$this->db->join( 'departments as dep', 'dep.id=pos.dep_id', 'left' );
		$this->db->where($condition);

		$res =	$this->db->get();
		//GET TOTAL
		$totalRecords = $this->db->count_all_results('positions');


		if ( $res->num_rows() > 0 ) 
		{
			$finalResult['items'] = $res->result_array();
			
			if ( $criterions['q'] == FALSE )
			{
				$finalResult['itemCount'] = $totalRecords;
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->like('name', $criterions['q']);
				$this->db->from('positions');
				$this->db->where($condition);

				$totalFilteredRecords = $this->db->count_all_results();
				
				$finalResult['itemFilteredCount'] = $totalFilteredRecords;
				$finalResult['itemCount'] = $totalFilteredRecords;
			}


			return $finalResult;
		}
		else
		{
			return FALSE;
		}
	}

	/*
	|---------------------------------------------------------------------------------
	|Insert Position
	|---------------------------------------------------------------------------------
	*/
	public function insert( $position )
	{
		$res = $this->db->insert( 'positions', $position );
		if ( $res ) {
			return $this->db->insert_id();
		}
		return $res;

	}

	/*
	|---------------------------------------------------------------------------------
	|Update Position
	|---------------------------------------------------------------------------------
	*/
	public function update( $position )
	{
		$this->db->where( array( 'id'=> $position['id'] ) );
		$this->db->update( 'positions', $position );
		
		return $this->db->affected_rows();
	}

	/*
	|---------------------------------------------------------------------------------
	| Check Before Delete
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
									  'tbl' => 'users',
								  'to_show' => 'Users',
								   'col_id' => 'position_id',
									'value' => $id 
								)
						);

		// Check For User Constraints
		foreach ($checkable_tables as $searchable)
		{
			$res = $this->db->select()
							->from($searchable['tbl'])
							->where($searchable['col_id'],$searchable['value'])
							->get();
			
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
	|Delete Position
	|---------------------------------------------------------------------------------
	*/
	public function delete( $pos_id )
	{
		// Check Job Title existence in another tables
		$check_result = $this->check_before_delete($pos_id);

		if ( $check_result['status'] == false ) 
		{
			return $check_result;
		}

		// Delete Job Title
		$this->db->where( array( 'id'=> $pos_id ) );

		$res = $this->db->delete( 'positions' );
		
		if ( $this->db->affected_rows() ) 
		{
			return array('status' => true,'message' => 'success');
		}
		else
		{
			return  array('status' => false,'message' => 'failure');
		}
	}



//-->End of "m_positions" Model Class
}
 ?>