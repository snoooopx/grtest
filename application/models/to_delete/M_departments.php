<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_departments Model Class
*/
class M_departments extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	/*
	|---------------------------------------------------------------------------------
	|Get Department List
	|---------------------------------------------------------------------------------
	*/
	public function get_departments($id=0, $criterions=FALSE)
	{
		$condition = array();
		
		if ( $id != 0 ) 
		{
			$condition = array('deps.id'=>$id);
		}
		
		//GET RESULT
		$res = $this->db->select( 'deps.id, 
								   deps.name as depName,
								   u.id   	 as depHeadId,
								   CONCAT(u.name," ",u.middle," ",u.sname) as head,
								   c.id   	 as depCompanyId,
								   c.name 	 as company,
								   1')
					->from( 'departments as deps' )
					->join( 'app_users as u', 'deps.head_id=u.id','left' )
					->join( 'company as c', 'deps.org_id=c.id','left' )
					->where($condition)
					->order_by('deps.name asc')
					->get();

		if ( $res->num_rows() > 0 ) 
		{
			return $res->result_array();
		}
		else
		{
			return FALSE;
		}
	}


	/*
	|---------------------------------------------------------------------------------
	| Get List of Department Clients
	|---------------------------------------------------------------------------------
	*/
	public function get_department_clients( $dep_id=false, $client_id=false )
	{
		if ( $dep_id==false && $client_id==false ) 
		{
			return false;
		}
		
		$condition = [];
		if( $dep_id )
		{
			$condition['dep_id'] = $dep_id;
		}

		if( $client_id )
		{
			$condition['client_id'] = $client_id;
		}

		$res = $this->db->select('dps.id, 
								  dps.name as department')
						->from('dep_clients dp_cl')
						->join('departmetns as dps','dps.id=dp_cl.dep_id','left')
						->where($condition)
						->get();

		if ( $res->num_rows > 0 ) 
		{
			return $res->result_array();
		}
		else
		{
			return [];
		}
	}

	/*
	|---------------------------------------------------------------------------------
	|Get Department List for Pagination
	|---------------------------------------------------------------------------------
	*/
	public function get_departments_paged($id=0, $criterions=FALSE)
	{
		$condition = array();
		
		if ( $id != 0 ) 
		{
			$condition = array('id'=>$id);
		}
		if ( $criterions['q'] !== FALSE ) {
			
		}

		if ( $criterions !== FALSE ) 
		{
			
		}
		
	//	/echo $criterions['page'].'*'.$criterions['perPage'];
	//	die;

		$startPage = ($criterions['page']-1) * $criterions['per_page'];
		$recordCount = $criterions['per_page'];
		//echo $startPage. " - ". $recordCount;
		//die;
		
		//GET RESULT
		$res = $this->db->select( 'deps.id, 
								   deps.name as depName,
								   u.id   	 as depHeadId,
								   CONCAT(u.name," ",u.middle," ",u.sname) as head,
								   c.id   	 as depCompanyId,
								   c.name 	 as company,
								   1')
					->from( 'departments as deps' )
					->join('app_users as u', 'deps.head_id=u.id','left')
					->join('company as c', 'deps.org_id=c.id','left')
					->limit( $recordCount, $startPage )
					->like('deps.name', $criterions['q'])
					->order_by($criterions['sort'], $criterions['order'])
					->get();
		//GET TOTAL
		$totalRecords = $this->db->count_all_results('departments');


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
				$this->db->like('deps.name', $criterions['q']);
				$this->db->from('departments');

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
	|Insert department
	|---------------------------------------------------------------------------------
	*/
	public function insert( $department )
	{
		$res = $this->db->insert( 'departments', $department );
		if ( $res ) {
			return $this->db->insert_id();
		}
		return $res;

	}


	/*
	|---------------------------------------------------------------------------------
	|Update department
	|---------------------------------------------------------------------------------
	*/
	public function update( $department )
	{
		$this->db->where( array( 'id'=> $department['id'] ) );
		$this->db->update( 'departments', $department );
		
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
									  'tbl' => 'positions',
								  'to_show' => 'Job Titles',
								   'col_id' => 'dep_id',
									'value' => $id 
								),
							array(
									  'tbl' => 'dep_assignments',
								  'to_show' => 'Assignments',
								   'col_id' => 'dep_id',
									'value' => $id 
								),
							array(
									  'tbl' => 'dep_clients',
								  'to_show' => 'Clients',
								   'col_id' => 'dep_id',
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
	|Delete department
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		// Check user existence in another tables
		$check_result = $this->check_before_delete($id);

		if ( $check_result['status'] == false ) 
		{
			return $check_result;
		}

		// Delete Department
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'departments' );
		
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
	|Insert Department Clients
	|---------------------------------------------------------------------------------
	*/
	public function insert_dep_clients( $dep_clients )
	{
		$res = $this->db->insert_batch( 'dep_clients', $dep_clients );
		if ( $res ) 
		{
			return $this->db->insert_id();
		}
		return $res;

	}


	/*
	|---------------------------------------------------------------------------------
	| Delete Department Clients
	|---------------------------------------------------------------------------------
	*/
	public function delete_dep_clients( $dep_id=false, $client_id=false )
	{
		$condition = [];
		
		if ( $dep_id ) 
		{
			$condition['dep_id'] = $dep_id;
		}

		if ( $client_id ) 
		{
			$condition['client_id'] = $client_id;
		}

		if ( $dep_id || $client_id ) 
		{
			$this->db->where($condition);
			$this->db->delete('dep_clients');
			return $this->db->affected_rows();
		}
		else
		{
			return false;
		}
	}



	/*
	|---------------------------------------------------------------------------------
	|Insert Department Assignements
	|---------------------------------------------------------------------------------
	*/
	public function insert_dep_assignments( $dep_assignments )
	{
		$res = $this->db->insert_batch( 'dep_assignments', $dep_assignments );
		if ( $res ) 
		{
			return $this->db->insert_id();
		}
		return $res;

	}
	

	/*
	|---------------------------------------------------------------------------------
	| Delete Department Assignements
	|---------------------------------------------------------------------------------
	*/
	public function delete_dep_assignments( $dep_id=false, $ass_id=false )
	{
		$condition = [];
		
		if ( $dep_id ) 
		{
			$condition['dep_id'] = $dep_id;
		}

		if ( $ass_id ) 
		{
			$condition['ass_id'] = $ass_id;
		}

		if ( $dep_id || $ass_id ) 
		{
			$this->db->where($condition);
			$this->db->delete('dep_assignments');
			return $this->db->affected_rows();
		}
		else
		{
			return false;
		}
	}


//-->End of "m_departments" Model Class
}
 ?>