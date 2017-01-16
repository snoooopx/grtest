<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_sectors Model Class
*/
class M_sectors extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	/*
	|---------------------------------------------------------------------------------
	|Get Sector List
	|---------------------------------------------------------------------------------
	*/
	public function get_sectors($sec_id=0, $criterions=FALSE)
	{
		$condition = array();
		
		if ( $sec_id != 0 ) 
		{
			$condition = array('sec.id'=>$sec_id);
		}
		if ( $criterions['q'] !== FALSE ) {
			
		}

		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->like('sec.name', $criterions['q']);
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}
		else
		{
			$this->db->order_by('sec.name', 'asc');	
		}
		
		//GET RESULT
 		$this->db->select( 'sec.id, 
 							sec.name, 
 							sec.note, 
 							1' );
		$this->db->from( 'business_sectors as sec' );
		$this->db->where( $condition );

		$res =	$this->db->get();
		
		//GET TOTAL
		$totalRecords = $this->db->count_all_results('business_sectors');


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
				$this->db->from('business_sectors');

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
	}

	/*
	|---------------------------------------------------------------------------------
	|Insert Sector
	|---------------------------------------------------------------------------------
	*/
	public function insert( $sector )
	{
		$res = $this->db->insert( 'business_sectors', $sector );
		if ( $res ) 
		{
			return $this->db->insert_id();
		}
		return $res;

	}

	/*
	|---------------------------------------------------------------------------------
	|Insert Client Sectors
	|---------------------------------------------------------------------------------
	*/
	public function insert_client_sectors( $client_secs )
	{
		return $this->db->insert_batch( 'client_sectors', $client_secs );
	}

	/*
	|---------------------------------------------------------------------------------
	|Delete Client Sector
	|---------------------------------------------------------------------------------
	*/
	public function delete_client_sectors( $sec_id=false, $client_id=false )
	{
		$condition = [];
		
		if ( $sec_id ) 
		{
			$condition['sec_id'] = $sec_id;
		}

		if ( $client_id ) 
		{
			$condition['client_id'] = $client_id;
		}

		if ( $sec_id || $client_id ) 
		{
			$this->db->where($condition);
			$this->db->delete('client_sectors');
			return $this->db->affected_rows();
		}
		else
		{
			return false;
		}
	}

	/*
	|---------------------------------------------------------------------------------
	|Update Sector
	|---------------------------------------------------------------------------------
	*/
	public function update( $sector )
	{
		$this->db->where( array( 'id'=> $sector['id'] ) );
		$this->db->update( 'business_sectors', $sector );
		
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
									  'tbl' => 'client_sectors',
								  'to_show' => 'Clients',
								   'col_id' => 'sector_id',
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
	|Delete Sector
	|---------------------------------------------------------------------------------
	*/
	public function delete( $sec_id )
	{
		// Check Job Title existence in another tables
		$check_result = $this->check_before_delete($sec_id);

		if ( $check_result['status'] == false ) 
		{
			return $check_result;
		}

		$this->db->where( array( 'id'=> $sec_id ) );
		$res = $this->db->delete( 'business_sectors' );
		
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
	| Check For Same Business Sector Value When Editing
	|---------------------------------------------------------------------------------
	*/
	public function is_same( $id, $name )
	{
		//Select and Get Statement
		$res = $this->db->select('')
				->from('business_sectors')
				->where(array('id'=>$id,'name'=>$name))
				->get();

		if ( $res->num_rows() > 0 ) 
		{
			//Same Name
			return true;
		}
		else
		{
			//Another name
			return false;
		}
	}


//-->End of "m_sectors" Model Class
}
 ?>