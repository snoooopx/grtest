<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_assignments Class 
*/
class M_assignments extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}

	
	/*
	|---------------------------------------------------------------------------------
	| Get (all) Client(s)
	|---------------------------------------------------------------------------------
	*/
	function get_assignments( $assignment_id=0, $brief = FALSE, $criterions=FALSE )
	{
		$logged_in_user = $this->session->userdata('logged_in');

		$where_in_subselect = 'select depas.ass_id from dep_assignments as depas where depas.dep_id = '. $logged_in_user['dep_id'] .' group by depas.ass_id';

		// Condition Array for Where Clause
		$condition =array();
		
		// show permitted assignments for departments/admins/users/CEO/..
		if ( $logged_in_user['is_admin'] == 1 )
		{
			$isAdmin = true;
		}
		else if( $logged_in_user['ceo'] == 1 )
		{
			$isAdmin = true;
		}
		else
		{
			$this->db->where_in('das.ass_id', $where_in_subselect, false);
			$isAdmin = false;
		}
		
		$select = '
					ass.id, 
					ass.name, 
					ass.description, 
					ass.is_visible,
					GROUP_CONCAT( distinct d.name separator ", " ) as dep_names,
					GROUP_CONCAT( distinct das.dep_id ) as dep_ids_str,
					GROUP_CONCAT( distinct o.name separator ", " ) as oper_names,
					GROUP_CONCAT( distinct asop.oper_id ) as oper_ids_str,
				';


		// Check for all/singe Select and Get Type (brief/..)
		if ( $assignment_id == 0 && $brief == TRUE )
		{
			$condition['ass.is_visible'] = '1';
		}
		else if ( $assignment_id > 0 && $brief == FALSE )
		{
			$condition['ass.id'] = $assignment_id;
		}
		else if ( $assignment_id > 0 && $brief == TRUE )
		{
			$condition['ass.id'] = $assignment_id;
			$condition['ass.is_visible'] = '1';
		}

		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->like('ass.name', $criterions['q']);
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		// Building Statement
		$this->db->select( $select )
				 ->from( 'assignments as ass' )
				 ->join( 'dep_assignments as das', 'das.ass_id = ass.id', 'left' )
				 ->join( 'departments as d', 'd.id = das.dep_id', 'left' )
				 ->join( 'ass_operations as asop', 'asop.ass_id = ass.id', 'left' )
				 ->join( 'operations as o', 'o.id = asop.oper_id', 'left' )
				 ->where( $condition )
				 ->group_by('ass.id');

		//$this->db->having($havingCondition);
		// Get Result
		$res = $this->db->get();

		/*$res = $this->db->get_compiled_select();
		echo $res;
		die;*/

		if ( $res->num_rows() > 0 ) 
		{
			$finalResult['items'] = $res->result_array();
			
			if ( !$isAdmin ) 
			{
				$this->db->group_start();
				$this->db->where_in('das.ass_id', $where_in_subselect, false);
				$this->db->group_end();
			}

			$this->db->select('count(ass.id)');
			$this->db->from('assignments ass');
			$this->db->join('dep_assignments as das', 'das.ass_id=ass.id', 'left');
			$this->db->where($condition);
			$this->db->group_by('ass.id');
			

			if ( $criterions['q'] == FALSE )
			{
				$res = $this->db->get();
				//GET TOTAL
				$totalRecords = $res->num_rows();
				
				$finalResult['itemCount'] = $totalRecords;
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->like('ass.name', $criterions['q']);
				$res = $this->db->get();
				
				$totalFilteredRecords = $res->num_rows();
				
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
	}//#get_assignments


	/*
	|---------------------------------------------------------------------------------
	| Insert Assignment
	|---------------------------------------------------------------------------------
	*/
	function insert( $assignment )
	{
		$res = $this->db->insert( 'assignments', $assignment );

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
	| Update Assignment Info
	|---------------------------------------------------------------------------------
	*/
	function update( $assignment )
	{
		$this->db->where( 'id', $assignment['id'] );
		$this->db->update( 'assignments', $assignment );
		return $this->db->affected_rows();
	}

	/*
	|---------------------------------------------------------------------------------
	| Check Before Delete Assignment
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
									  'tbl' => 'projects',
								  'to_show' => 'Projects',
								   'col_id' => 'ass_id',
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
	| Delete Client
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'assignments' );
		
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
	|Insert Assignements Operation
	|---------------------------------------------------------------------------------
	*/
	public function insert_ass_operations( $ass_operations )
	{
		$res = $this->db->insert_batch( 'ass_operations', $ass_operations );
		if ( $res ) 
		{
			return $this->db->insert_id();
		}
		return $res;

	}
	

	/*
	|---------------------------------------------------------------------------------
	| Delete Assignement Operation
	|---------------------------------------------------------------------------------
	*/
	public function delete_ass_operations( $ass_id=false, $oper_id=false )
	{
		$condition = [];
		
		if ( $ass_id ) 
		{
			$condition['ass_id'] = $ass_id;
		}

		if ( $oper_id ) 
		{
			$condition['oper_id'] = $oper_id;
		}

		if ( $ass_id || $oper_id ) 
		{
			$this->db->where($condition);
			$this->db->delete('ass_operations');
			return $this->db->affected_rows();
		}
		else
		{
			return false;
		}
	}



/*
|---------------------------------------------------------------------------------
|End of m_assignments Class
|---------------------------------------------------------------------------------
*/
}
?>