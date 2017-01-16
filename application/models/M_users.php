<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_users Class 
*/
class M_users extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}
	
	//User Configs Defaults
	private $user_configs_defaults = array(
									'viewAllReports'  => '0',
									'combinedSidebar' => '0'

								);
	public function get_default_configs()
	{
		return $this->user_configs_defaults;
	}

	/*
	|---------------------------------------------------------------------------------
	|Checking Login Info
	|---------------------------------------------------------------------------------
	*/
	function check_login( $login, $password )
	{

		$this->db->select('
							u.id, 
							u.login, 
							u.name, 
							u.middle, 
							u.sname,
							u.avatar,
							u.is_active as isActive,
							u.is_admin as isAdmin,
							u.password'
						);
		$this->db->from('app_users as u');
		$this->db->where( array( 'login'=> $login ) );

		//Getting User info via Login
		$res_login = $this->db->get();
				
		if ( $res_login->num_rows() > 0 ) 
		{
			$res = $res_login->result_array()[0];
			//Checking For Valid Password
			//On Success Return User Info
			//On Fail 	 Return false
			//if ( $res['password'] == $this->m_validation->hash_password( $password, $res['salt'] ) ) 
			if ( password_verify( $password, $res['password'] ) )
			{
				//Get User Configs From DB
				$user_configs = $this->get_user_settings($res['id']);
				
				$ceo = 0;
				$head_of_dep = 0;
				$position_temp = "";
				$is_customer = '0';
				$is_manager = '1';


				//User Session Info
				$user_session_info = array(
											'id' 			=> $res['id'],
											'login' 		=> $res['login'],
											'is_active' 	=> $res['isActive'],
											'is_admin'  	=> $res['isAdmin'],
											'is_manager'  	=> $is_mnager,
											'is_customer'  	=> $is_customer,
											'name' 			=> $res['name'],
											'middle' 		=> $res['middle'],
											'sname' 		=> $res['sname'],
											/*'avatar' 		=> $res['avatar'],*/
											'dep_id' 		=> '0',
											'dep_head_id'	=> '0',
											'position' 		=> '0',
											'head_of_dep' 	=> '0',
											'ceo' 			=> '0'
										);

				//Adding User Configs From DB To User Session Info
				foreach ($user_configs as $key => $value) {
					$user_session_info[$key] = $value;
				}

				return $user_session_info;
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
	|Get (all) User(s)
	|---------------------------------------------------------------------------------
	*/
	function get_users( $user_id=0, $brief=false, $criterions=false, $as_catalog=false )
	{

		$full_select = 'u.id, 
						u.name, 
						u.middle, 
						u.sname, 
						CONCAT(u.name, " ", u.middle, " ", u.sname) as fullName,
						u.login, 
						u.sex, 
						u.email, 
						u.phone, 
						u.address, 
						
						
						u.is_active as isActive
						';

		$brief_select = 'u.id, 
						u.name, 
						u.middle, 
						u.sname';
		
		$select = '';
		$condition =array();

		if ( $user_id == 0 && $brief === false ) 
		{
			$select = $full_select;
			//$condition = array();
		}
		else if ( $user_id == 0 && $brief == true )
		{
			$select = $brief_select;
			//$condition = array('u.status' => '1' );
		}
		else if ( $user_id > 0 && $brief == false )
		{
			$select = $full_select;
			$condition = array( 'u.id' => $user_id );
		}
		else if ( $user_id > 0 && $brief == true )
		{
			$select = $brief;
			//$condition = array( 'u.id' => $user_id, 'u.status' => '1' );
		}
		else
		{
			$select = $brief;
		}

		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== false ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			
			$this->db->limit($recordCount, $startPage );
			$this->db->like('CONCAT(u.name," ",u.middle," ",u.sname)', $criterions['q'] );
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}
		else
		{
			$this->db->order_by('u.name', 'asc');	
		}


		$this->db->select( $select )
					->from( 'app_users as u' )
					->where( $condition );

		$res = $this->db->get();
		
		//GET TOTAL
		$totalRecords = $this->db->count_all_results('app_users');

		if ( $res->num_rows() > 0 ) 
		{
			// Get Items
			$finalResult['items'] = $res->result_array();
			
			if ( $criterions['q'] == false )
			{
				$finalResult['itemCount'] = $totalRecords;
			}
			else
			{
				// GET FILTERED TOTAL
				$this->db->like('CONCAT(name," ",middle," ",sname)', $criterions['q']);
				$this->db->from('app_users');

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
	| Get User List
	| @param criterion 2- for selecting Dep users
	| @param criterion 3- for selecting All users
	|---------------------------------------------------------------------------------
	*/
/*	public function get_user_list($criterion)
	{
		$condition = [];
		
		if ( $criterion['type'] == 2 ) 
		{
			$condition['pos.dep_id'] = $criterion['dep_id'];
			$this->db->where($condition);
		} 
		else if( $criterion['type'] == 3 )
		{
			$this->db->where_not_in('u.id','1');
		}
		

		$res = $this->db->select("
									u.id,
									u.middle,
									u.name,
									u.sname
								")
						->from('app_users as u')
						->order_by('u.name', 'asc')
						->get();

		if ($res->num_rows() > 0) {
				return $res->result_array();
		}else{
				return false;
		}
				
	}*/

	/*
	|---------------------------------------------------------------------------------
	|Insert User
	|---------------------------------------------------------------------------------
	*/
	function insert( $user )
	{
		$res = $this->db->insert( 'app_users', $user );

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
	|Insert Permissions
	|---------------------------------------------------------------------------------
	*/
	public function insert_permissions($id)
	{	
		$this->load->model('m_config');
		//Get Sections
		$res_sections = $this->m_config->get_sections(1);
		
		if ( $res_sections ) 
		{
			$i = 0;
			$new_user_perms=[];
			foreach ($res_sections as $key => $value) 
			{
				$new_user_perms[$i]['user_id'] 			= $id;
				$new_user_perms[$i]['section_id'] 		= $value['section_id'];
				$new_user_perms[$i]['subsection_id'] 	= $value['subsection_id'];
				$new_user_perms[$i]['section_seq'] 		= $value['section_seq'];
				$new_user_perms[$i]['subsection_seq'] 	= $value['subsection_seq'];
				$i++;
			}
			
			$insert_result = $this->db->insert_batch('app_permissions', $new_user_perms);
			//if ( $insert_result == $i+1) {
			return $insert_result;
			//}

		}
		else
		{
			return false;
		}
	}

	/*
	|---------------------------------------------------------------------------------
	|Update Permissions
	|---------------------------------------------------------------------------------
	*/
	public function update_permissions( $perms )
	{
		$this->db->trans_begin();
		foreach ( $perms as $section ) 
		{
			$this->db->where(array('section_id' => $section['section_id'],
									'subsection_id' => $section['subsection_id'],
									'user_id' => $section['user_id']));
			$this->db->update('app_permissions', $section);
		}

		if ( $this->db->trans_status() !== false )
		{
			$this->db->trans_commit();
			return true;
		}
		else
		{
			$this->db->trans_rollback();
			return false;
		}
	}

	/*
	|---------------------------------------------------------------------------------
	|Update User Info
	|---------------------------------------------------------------------------------
	*/
	function update( $user )
	{
		$this->db->where( 'id', $user['id'] );
		$this->db->update( 'app_users', $user );
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
							/*array(
									  'tbl' => 'ts_timesheets',
								  'to_show' => 'Timesheets',
								   'col_id' => 'user_id',
									'value' => $id 
								),
							array(
									  'tbl' => 'ts_history',
								  'to_show' => 'History',
								   'col_id' => 'user_id',
									'value' => $id 
								),
							array(
									  'tbl' => 'projects',
								  'to_show' => 'history',
								   'col_id' => 'manager_id',
									'value' => $id 
								),
							array(
								  	  'tbl' => 'project_team',
								  'to_show' =>'Project Team',
								   'col_id' => 'user_id',
								    'value' => $id 
								),
							array(
								  	  'tbl' => 'departments',
								  'to_show' =>'Departments',
								   'col_id' => 'head_id',
								    'value' => $id 
								),
							array(
								      'tbl' => 'company',
								  'to_show' =>'Company',
								   'col_id' => 'head_id',
								    'value' => $id 
								)*/
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
	|Delete User
	|---------------------------------------------------------------------------------
	*/
	function delete( $id )
	{	
		// return false for Supervisor User
		if ($id==1) {
			return false;
		}

		// Check user existence in another tables
		$check_result = $this->check_before_delete($id);

		if ( $check_result['status'] == false ) 
		{
			return $check_result;
		}
		$this->db->trans_begin();

		//Delete From Back
		// 1. Delete User Permissions from App Permissions Table
		// 2. Delete User From Users Table

		$this->db->where('user_id', $id);
		$res_perms = $this->db->delete('app_permissions');
		
		$this->db->where('id', $id);
		$res_user = $this->db->delete('app_users');
		
		if ( $this->db->trans_status() !== false AND $res_user !== false AND $res_perms !== false )
		{
			$this->db->trans_commit();
			return array('status' => true,'message'=>'success');
		}
		else
		{
			$this->db->trans_rollback();
			return  array('status' => false,'message'=> 'failure');;
			
		}
	}


	/*
	|---------------------------------------------------------------------------------
	| Get User Settings
	|---------------------------------------------------------------------------------
	*/
	public function get_user_settings($user_id)
	{
		$res = $this->db->select('user_id,name,value')
						->from('app_user_configs')
						->where('user_id',$user_id)
						->get();

		return $this->make_human( $res->result_array() );
	}


	/*
	|---------------------------------------------------------------------------------
	| Update User Settings
	|---------------------------------------------------------------------------------
	*/
	public function update_user_settings($user_id, $settings)
	{
		$res = $this->db->query('INSERT INTO app_user_configs(user_id,name,value)
							VALUES (' . $user_id .', "'. $settings["key"] .'", "'. $settings["value"]. '") '.
						' ON DUPLICATE KEY UPDATE value =' . $settings["value"] );
		return $res;
	}


	/*
	 Make Getted user settings readable
	*/
	public function make_human($settings)
	{
		//Get Default Settings		
		$default_configs = $this->get_default_configs();

		$readable = [];

		// Check And Return Correct Configs
		if (!empty($settings)) 
		{
			foreach ($settings as $config) 
			{
				$readable[$config['name']] = $config['value'];
			}

			foreach ($default_configs as $key=>$value) 
			{
				if (!array_key_exists($key,$readable)) 
				{
					$readable[$key] = $value;
				}
			}
			return $readable;
		}
		else
		{
			return $default_configs;
		}
	}

	/*
	# Change user Password 
	*/
	public function change_password( $user_id, $password )
	{
		$userpass = array('password'=> $this->m_validation->hash_password( $password ));
		$this->db->where('id',$user_id);
		$this->db->update('app_users',$userpass);
		if ($this->db->affected_rows() > 0) {
			return $this->db->affected_rows();
		} else {
			return false;
		}
	}

/*
|---------------------------------------------------------------------------------
|End of m_users Class
|---------------------------------------------------------------------------------
*/
}
 ?>