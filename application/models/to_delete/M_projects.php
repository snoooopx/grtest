<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_projects Class 
*/
class M_projects extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}

	
	/*
	|---------------------------------------------------------------------------------
	| Get (all) Project(s)
	|---------------------------------------------------------------------------------
	*/
	function get_projects( $project_id=0, $brief=FALSE, $criterions=FALSE )
	{
		//Get Logged In User From Session
		$logged_in_user = $this->session->userdata('logged_in');

		// Condition Array for Where Clause
		$condition = array();
		$condition_manager = array();
		$condition_project_team = array();
		// show permitted Projects for Department
		if ( $logged_in_user['is_admin'] != 1 && $logged_in_user['ceo'] != 1 && $logged_in_user['head_of_dep'] != 1 )
		{
			//$condition['das.dep_id'] = $logged_in_user['dep_id'];
			$condition_manager['p.manager_id'] = $logged_in_user['id'];
			$condition_project_team['pt.user_id'] = $logged_in_user['id'];
			$this->db->group_start()
					 	->where( $condition_manager )
					 	->or_where( $condition_project_team )
					 ->group_end();
			$isAdmin = false;
		}
		else
		{
			$isAdmin = true;
		}

		if ( $logged_in_user['ceo'] != 1 && $logged_in_user['head_of_dep'] == 1 ) 
		{
			$condition['das.dep_id'] = $logged_in_user['dep_id'];
		}

		$full_select = '
						p.id,
						p.name,
						p.code,
						p.manager_id,
						concat (u.name, " ", u.middle, " ", u.sname) as manager,
						p.client_id,
						c.name as client,
						p.ass_id,
						ass.name as assignment,
						p.creation_date,
						p.start_date as agrSD,
						p.end_date as agrED,
						p.actual_start_date as actSD,
						p.actual_end_date as actED,
						p.is_visible,
						p.status_id as project_status_id,
						ps.name as project_status,
						p.apt_status_id,
						apts.name as apt_status,
						p.note,
						GROUP_CONCAT( CONCAT( utm.name, " ", utm.middle, " ", utm.sname ) separator ", " ) as team_names,
						GROUP_CONCAT( pt.user_id ) as team_ids_str,
					';
						/*bt.name btype*/

		$brief_select = '
						p.id,
						p.name,
						p.code,
						p.manager_id,
						concat (u.name, " ", u.middle, " ", u.sname) as manager
					';
		
		$select = '';


		// Check for all/singe Select and Get Type (brief/..)
		if ( $project_id == 0 && $brief === FALSE ) 
		{
			$select = $full_select;
		}
		else if ( $project_id == 0 && $brief == TRUE )
		{
			$select = $brief_select;
			$condition['p.is_visible'] = '1';
		}
		else if ( $project_id > 0 && $brief == FALSE )
		{
			$select = $full_select;
			$condition['p.id'] = $project_id;
		}
		else if ( $project_id > 0 && $brief == TRUE )
		{
			$select = $brief;
			$condition['p.id'] 		   = $project_id;
			$condition['p.is_visible'] = '1' ;
		}
		else
		{
			$select = $brief;
		}

		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->group_start();
			$this->db->like('p.name', $criterions['q']);
			$this->db->or_like('p.code', $criterions['q']);
			$this->db->group_end();
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		// Building Statement
		$temp = $this->db->select( $select )
				 ->from( 'projects as p' )
				 ->join( 'clients as c',		 	'c.id = p.client_id',		 'left' )
				 ->join( 'assignments as ass', 		'ass.id = p.ass_id', 		 'left' )
				 ->join( 'app_users as u', 			 	'u.id = p.manager_id', 		 'left' )
				 ->join( 'project_team as pt', 		'pt.project_id = p.id', 	 'left' )
				 ->join( 'app_users as utm', 		 	'utm.id = pt.user_id', 		 'left' )
				 ->join( 'project_statuses as ps',  'ps.id = p.status_id', 		 'left' )
				 ->join( 'apt_statuses as apts',  	'apts.id = p.apt_status_id', 'left' )
				 ->join( 'dep_assignments as das', 	'das.ass_id=p.ass_id', 		 'left' )
				 ->where( $condition )
			
				 ->group_by('p.id');
	
		//echo $this->db->get_compiled_select();
		//print_r($condition);
		//die;
		// Get Result
		$res = $this->db->get();
		

		if ( $res->num_rows() > 0 ) 
		{
			$totalFilteredRecords = $res->num_rows();
			
			$finalResult['items'] = $res->result_array();
			
			$this->db->select('p.id')
					 ->from('projects as p' )
					 ->join( 'project_team as pt', 		'pt.project_id = p.id', 	 'left' )
					 ->join('dep_assignments as das', 'das.ass_id=p.ass_id', 'left' )
					 ->where( $condition )
					 ->group_by('p.id');
			
			if ( !$isAdmin ) 
			{
				//$this->db->where('das.dep_id', $logged_in_user['dep_id']);
				//$condition['das.dep_id'] = $logged_in_user['dep_id'];
				$this->db->group_start()
						 	->where( $condition_manager )
						 	->or_where( $condition_project_team )
						 ->group_end();
			}


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
							->like('p.name', $criterions['q'])
							->or_like('p.code', $criterions['q'])
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
	}//#get_projects


	// Get User Clients
	public function get_user_clients($user_id=false)
	{
		// Condition Array for Where Clause
		$condition_manager = array();
		$condition_project_team = array();
		$select_string = '';
		if ($user_id) 
		{
			$condition_manager['p.manager_id'] = $user_id;
			$condition_project_team['pt.user_id'] = $user_id;
			
			$res = $this->db->select('c.id,c.name as text')
							->from('projects as p')
							->join('clients as c','c.id=p.client_id','left')
							->join('project_team as pt','p.id=pt.project_id','left')
							->join('app_users as u', 'u.id=pt.user_id', 'left')
							->group_start()
							 	->where( $condition_manager )
							 	->or_where( $condition_project_team )
							->group_end()
							->group_by('c.name')
							->get();
			return $res->result_array();
		}
		else
		{
			return false;
		}
	}

	// Get Client Users
	public function get_client_users($client_id=false)
	{
		// Condition Array for Where Clause
		$condition = array();
		if ($client_id) 
		{
			$condition['p.client_id'] = $client_id;
			
			$this->db->select('u.id, CONCAT( u.name, " ", u.middle, " ", u.sname ) as text')
					->from('project_team as pt')
					->join('projects as p','p.id=pt.project_id','left')
					->join('app_users as u','u.id = pt.user_id','left')
					->where($condition);

			$q_team = $this->db->get_compiled_select();

			$this->db->select('p.manager_id, CONCAT( u.name, " ", u.middle, " ", u.sname ) as text')
					->from('projects as p')
					->join('app_users as u', 'u.id=p.manager_id', 'left')
					->where($condition);

			$q_manager = $this->db->get_compiled_select();


			$res = $this->db->query($q_team. ' UNION ' . $q_manager .' ORDER BY text asc');

			return $res->result_array();
		}
		else
		{
			return false;
		}
	}


	public function get_project_operations( $project_id=false )
	{
		if ( $project_id ) 
		{
			$res = $this->db->select('op.id, op.name as text')
					->from('projects as p')
					->join('ass_operations as asop', 'p.ass_id=asop.ass_id', 'left')
					->join('operations as op', 'op.id=asop.oper_id','right')
					->where('p.id', $project_id)
					->get();
			if ( $res->num_rows() >0 ) 
			{
				return $res->result_array();
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	// Get Project Team
	public function get_project_team( $project_id=false )
	{
		if ( $project_id ) 
		{
			$res =$this->db->select('pt.user_id, u.name')
							->from('project_team as pt')
							->join('app_users as u','u.id=pt.user_id','left')
							->where('pt.project_id',$project_id)
							->get();
			if ( $res->num_rows() >0 ) 
			{
				return $res->result_array();
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/*
	|---------------------------------------------------------------------------------
	| Insert Project
	|---------------------------------------------------------------------------------
	*/
	function insert( $project )
	{
		$res = $this->db->insert( 'projects', $project );

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
	| Update Project Info
	|---------------------------------------------------------------------------------
	*/
	function update( $project )
	{
		$this->db->where( 'id', $project['id'] );
		$this->db->update( 'projects', $project );
		return $this->db->affected_rows();
	}
	

	/*
	|---------------------------------------------------------------------------------
	| Check Before Delete Project
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
									  'tbl' => 'ts_main',
								  'to_show' => 'Timesheets',
								   'col_id' => 'project_id',
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
	| Delete Project
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'projects' );
		
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
	| Insert Project Team
	|---------------------------------------------------------------------------------
	*/
	public function insert_project_team( $team )
	{
		return $this->db->insert_batch('project_team', $team);
	}
	
	/*
	|---------------------------------------------------------------------------------
	| Delete Project Team
	|---------------------------------------------------------------------------------
	*/
	public function delete_project_team( $project_id=false )
	{
		$condition = [];
		
		if ( $project_id ) 
		{
			$condition['project_id'] = $project_id;
		}
		else
		{
			return false;
		}
		
		$this->db->where($condition);
		$this->db->delete('project_team');

		return $this->db->affected_rows();
	}



/*
|---------------------------------------------------------------------------------
|End of m_projects Class
|---------------------------------------------------------------------------------
*/
}
?>