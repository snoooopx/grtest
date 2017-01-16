<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_timesheets Model Class
*/
class M_timesheets extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	private $select_str_for_get_ts = "tsts.id, 
										tsts.created, 
										tsts.user_id,
										CONCAT(u.name,' ',u.middle,' ',u.sname) as user,
										ts_w.ts_year,
										ts_w.w_no,
										ts_w.w_start,
										ts_w.w_end, 
										count(tsm.id) as items_in_ts,
										COALESCE(sum(tsm.wd1)+ 
												 sum(tsm.wd2)+ 
												 sum(tsm.wd3)+ 
												 sum(tsm.wd4)+ 
												 sum(tsm.wd5)+ 
												 sum(tsm.wd6)+ 
												 sum(tsm.wd7),0) as total, 
										stat.name as status,
										sum(if( tsm.is_accepted=0 OR tsm.is_accepted=2,0,1 )) as readiness,
										tsts.status_id,
										1";

	private $select_str_for_get_full_ts = '
										tsts.id as ts_id,
										u.name as userToSubmit, 
										CONCAT(u.name," ",u.middle," ",u.sname ) as fullName,
										p.code,
										p.manager_id,
										upm.name as project_manager,
										tsts.user_id,
										tsts.status_id,
										stat.name as status,
										tsts.created,
										tsts.last_modified,
										tsacts.name as activity,
										tsacts.code as activity_code,
										tsabts.name as absence,
										tsw.w_no, 
										tsw.ts_year, 
										tsw.w_start,
										tsw.w_end,
										ops.name as operation,
										tsm.id as ts_main_id,
										tsm.ts_type,
										tsm.activity_id,
										tsm.project_id, 
										tsm.operation_id, 
										tsm.absence_id, 
										tsm.is_accepted, 
										tsm.wd1, 
										tsm.wd2, 
										tsm.wd3,
										tsm.wd4, 
										tsm.wd5, 
										tsm.wd6, 
										tsm.wd7,
										tsm.note';


/*
 ######   ######## ########   ########  ######  
##    ##  ##          ##         ##    ##    ## 
##        ##          ##         ##    ##       
##   #### ######      ##         ##     ######  
##    ##  ##          ##         ##          ## 
##    ##  ##          ##         ##    ##    ## 
 ######   ########    ##         ##     ######  
*/

/*
|---------------------------------------------------------------------------------
| @ts_id
| @page
| @per_page
| @sort
| @order
| @user
| @year
| @week
| @status
|---------------------------------------------------------------------------------
*/
	public function get_ts($filter_criterions=false, $with_status=false)
	{
		$logged_in_user = $this->session->userdata('logged_in');
		
		$select_str = $this->select_str_for_get_ts;
		$cond_subquery_dep_heads = "tsts.user_id in (select head_id from departments)";
		$condition =array();

		// Check for Pagination and Filtering Criterions 
		// And Build Statement
		if ( $filter_criterions !== FALSE ) 
		{
			if ( isset( $filter_criterions['ts_id'] ) ) 
			{
				$condition['tsts.id'] = $filter_criterions['ts_id'];
			}
			else
			{
				if ( isset( $filter_criterions['page']) && isset( $filter_criterions['per_page'] ) ) 
				{
					$startPage = ($filter_criterions['page']-1) * $filter_criterions['per_page'];
					$recordCount = $filter_criterions['per_page'];
				}

				/*
				| YEAR Filter
				*/
				if ( isset($filter_criterions['year']) && $filter_criterions['year'] == '0' )
				{
					$condition['ts_w.ts_year']	= Date('Y');
				}
				else if( !isset($filter_criterions['year']) )
				{
					$condition['ts_w.ts_year']	= Date('Y');	
				}
				else
				{
					$condition['ts_w.ts_year'] = $filter_criterions['year'];
				}

				// WEEK Filter
				if ( isset( $filter_criterions['week'] ) && $filter_criterions['week'] != '0' )
				{
					$condition['ts_w.w_no']	= $filter_criterions['week'];
				}

				// USER Filter
				if ( isset( $filter_criterions['user'] ) && $filter_criterions['user'] == '0' ) 
				{
					$condition['tsts.user_id'] = $logged_in_user['id'];
				}
				elseif(  !isset( $filter_criterions['user'] ) )
				{
					$condition['tsts.user_id'] = $logged_in_user['id'];
				}
				else
				{
					$condition['tsts.user_id'] = $filter_criterions['user'];
				}

				// Status Filter
				/*if ( isset( $filter_criterions['status1'] ) $filter_criterions['status'] == '0' ) 
				{
					$condition['tsts.status_id'] = $filter_criterions['status'];
				}*/

				$this->db->limit( $recordCount, $startPage );
				
				$this->db->order_by($filter_criterions['sort'], $filter_criterions['order']);
			}
		} 
		else if( $with_status !== false )
		{
			// 2- for getting pending to accept timesheets
			if ( $with_status == 2 ) 
			{
				$condition['tsts.status_id']=2;
				// Generating Condition
				if ( $logged_in_user['is_admin'] == '1' || $logged_in_user['ceo'] == '1' )
				{
					// get all pendings
					// status id in "ts_timesheets"
					// 2 - Created & Submitted
					$condition['tsts.status_id'] = 2;

				} 
				elseif ( $logged_in_user['head_of_dep'] == '1' ) 
				{
					// Get Dep all and Those Where He is Approver
					$condition['pos.dep_id'] 		= $logged_in_user['dep_id'];
					$condition['tsts.user_id !='] 	= $logged_in_user['id'];
				} 
				else
				{
					/* | Remove below commented section later if didn`t need | */

					// Get Those Where He is Approver
					//$condition['p.manager_id'] = $logged_in_user['id'];
					
					// Do Not Get Own Rows for Approve
					//$condition['tsts.user_id !='] = $logged_in_user['id'];

					$finalResult['items'] = [];
					$finalResult['itemCount'] = 0;
					$finalResult['itemFilteredCount'] = 0;
					return $finalResult;
				}
			}
		}
		/*print_r($condition);
		die;*/

		// Building Statement
		$this->db->select($select_str)
				->from('ts_timesheets as tsts')
				->join('ts_weeks as ts_w', 'ts_w.id=tsts.ts_week_id', 'left')
				->join('ts_main as tsm','tsts.id = tsm.ts_id','left')
				->join('ts_statuses as stat', 'tsts.status_id = stat.id', 'left')
				->join('app_users as u', 'u.id = tsts.user_id', 'left')
				->join('projects as p',	'p.id = tsm.project_id', 'left');

			  
	    if ($with_status !== false && $with_status == 2) 
	    {
			$this->db->join('positions as pos', 'pos.id=u.position_id','left');
			if ( $logged_in_user['is_admin'] == 1 || $logged_in_user['ceo'] == 1 )
			{
				$this->db->where('tsts.user_id in (select head_id from departments)');
			}

	    }
		
		$this->db->where($condition);
		
		$this->db->group_by('tsts.id');
		$this->db->order_by('u.name asc, ts_w.w_no desc');

		// Get Result
		$res = $this->db->get();
		
		/*$res = $this->db->get_compiled_select();
		echo $res;
		die;*/

		if ( $res->num_rows() > 0 ) 
		{
			$finalResult['items'] = $res->result_array();
			
			/*if ( !$isAdmin ) 
			{
				$this->db->where_in('dc.user_id', $where_in_subselect, false);
			}*/

			
			if ( $with_status !== false && $with_status == 2 ) 
		    {
				$finalResult['itemCount'] = $res->num_rows();
		    }
			else if ( $filter_criterions['q'] == FALSE )
			{
				$this->db->select('count(tsts.id)');
				$this->db->from('ts_timesheets as tsts');
				$this->db->join('ts_weeks as ts_w', 'ts_w.id=tsts.ts_week_id', 'left');
				$this->db->where($condition);
				$this->db->group_by('tsts.id');
				
				//GET TOTAL
				$res = $this->db->get();

				$finalResult['itemCount'] = $res->num_rows();
			}
			else
			{
				$this->db->select('count(tsts.id)');
				$this->db->from('ts_timesheets as tsts');
				$this->db->join('ts_weeks as ts_w', 'ts_w.id=tsts.ts_week_id', 'left');
				$this->db->where($condition);
				$this->db->group_by('tsts.id');
				
				//GET FILTERED TOTAL
				$this->db->group_start()
						->where($condition)
						->group_end();
				$res = $this->db->get();

				//$totalFilteredRecords = $this->db->count_all_results();
				
				$finalResult['itemFilteredCount'] = $res->num_rows();
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
	}//#get_timesheets


/*
######## ##     ## ##       ##         ########  ######  
##       ##     ## ##       ##            ##    ##    ## 
##       ##     ## ##       ##            ##    ##       
######   ##     ## ##       ##            ##     ######  
##       ##     ## ##       ##            ##          ## 
##       ##     ## ##       ##            ##    ##    ## 
##        #######  ######## ########      ##     ######  

*/
/*
|---------------------------------------------------------------------------------
	@request_type
	1- full ts
	2- pendings
| Get Pendings
| // Get pending Timesheets based on logged in user perms
| // if CEO OR Admin 	-> get all
| // if Head Of Dep 	-> get Dep all and Those Where He is Approver
| // if Empl 			-> get Those Where He is Approver
| 
| ts_main column
|	@is_accepted 
|
|	0 - not needed to accept
|	1 - not accepted
|	2 - accepted
|	3 - rejected-returned to correct
|		
|---------------------------------------------------------------------------------
*/

public function get_full_ts( $request_type=0, $filter_criterions=false )
{
	
	$logged_in_user = $this->session->userdata('logged_in');
	$main_condition = [];
	$abs_condition = [];
	
	// Getting Rows Marked as Main
	$main_condition['tsm.ts_type'] = 1;
	// Getting Rows marked as Absence
	$abs_condition['tsm.ts_type'] = 2;
	
	// 2- pendings
	if ( $request_type == 2 && $filter_criterions == false ) 
	{
		# Getting Rows from "ts_main" where projects 
		# Need to be accepted (is_accepted = 1)
				
		$main_condition['tsm.is_accepted'] = 1;

		$abs_condition['tsm.is_accepted'] = 1;
		
		
		# Getting Rows from "ts_timesheets" 
		# Where timesheets were Submitted by owner (status_id=2)
				
		$main_condition['tsts.status_id'] = 2;
				
		$abs_condition['tsts.status_id'] = 2;

		// Get Those Where Logged in User is Approver
		$main_condition['p.manager_id'] 	= $logged_in_user['id'];
		
		// DO Not Get Own Rows for Approve
		$main_condition['tsts.user_id !='] 	= $logged_in_user['id'];
		$abs_condition['tsts.user_id !=']	= $logged_in_user['id'];
	} 
	elseif( $request_type == 1 && $filter_criterions != false )
	{
		$main_condition['tsts.user_id']	= $filter_criterions['user_id'];
		$abs_condition['tsts.user_id']	= $filter_criterions['user_id'];
		$main_condition['tsts.id']		= $filter_criterions['ts_id'];
		$abs_condition['tsts.id']		= $filter_criterions['ts_id'];
		
		if ( isset($filter_criterions['for_edit']) && $filter_criterions['for_edit'] ) 
		{
			$this->db->where_not_in('tsts.status_id',array('2','3'));
		}
	}
	else
	{
		return false;
	}

	$select_str = $this->select_str_for_get_full_ts;

	// Get Main Rows
	$this->db->select($select_str)
			->from('ts_main as tsm')
			->join('ts_timesheets as tsts', 		'tsts.id = tsm.ts_id', 'left')
			->join('ts_weeks as tsw', 				'tsts.ts_week_id = tsw.id', 'left')
			->join('projects as p', 				'p.id = tsm.project_id', 'left')
			->join('dep_assignments as das', 		'das.ass_id = p.ass_id AND das.dep_id='.$logged_in_user['dep_id'], 'left')
			->join('ts_activity_types as tsacts', 	'tsacts.id = tsm.activity_id', 'left')
			->join('ts_absence_types as tsabts', 	'tsabts.id = tsm.absence_id', 'left')
			->join('operations as ops', 			'ops.id = tsm.operation_id', 'left')
			->join('app_users as u', 					'u.id = tsts.user_id', 'left')
			->join('app_users as upm', 					'upm.id = p.manager_id', 'left')
			->join('ts_statuses as stat', 			'stat.id = tsts.status_id', 'left')
			//->join($ts_history_subquery, 			'tsh.ts_main_id = tsm.id', 'left')
			->where($main_condition);
		
			/*->order_by('u.name asc, tsw.ts_year desc, tsw.w_no desc');*/



	$query_main = $this->db->get_compiled_select();
	$query_abs = "";

	if ( $logged_in_user['is_admin'] == 1 || $logged_in_user['ceo'] == 1 || $logged_in_user['head_of_dep'] == 1 || $request_type == 1 )
	{
		// Paymani miji conditiony verevum el ka,
		// bayc qani vor verevi scripti ashxateluc heto ajn reset e linum,
		// harkavor e noric haytararel

		if ( isset($filter_criterions['for_edit']) && $filter_criterions['for_edit'] ) 
		{
			$this->db->where_not_in('tsts.status_id',array('2','3'));
		}
		// Get Absence Rows
		$this->db->select($select_str)
				->from('ts_main as tsm')
				->join('ts_timesheets as tsts', 		'tsts.id = tsm.ts_id', 'left')
				->join('ts_weeks as tsw', 				'tsts.ts_week_id = tsw.id', 'left')
				->join('projects as p', 				'p.id = tsm.project_id', 'left')
				->join('dep_assignments as das', 		'das.ass_id = p.ass_id AND das.dep_id='.$logged_in_user['dep_id'], 'left')
				->join('ts_activity_types as tsacts', 	'tsacts.id = tsm.activity_id', 'left')
				->join('ts_absence_types as tsabts', 	'tsabts.id = tsm.absence_id', 'left')
				->join('operations as ops', 			'ops.id = tsm.operation_id', 'left')
				->join('app_users as u', 					'u.id = tsts.user_id', 'left')
				->join('app_users as upm', 					'upm.id = p.manager_id', 'left')
				->join('positions as pos', 				'pos.id = u.position_id', 'left')
				->join('ts_statuses as stat', 			'stat.id = tsts.status_id', 'left')
				//->join($ts_history_subquery,			'tsh.ts_main_id = tsm.id', 'left')
				->where($abs_condition);
			
			/*->order_by('ORDER BY u.name asc, tsw.ts_year desc, tsw.w_no desc');*/
		$query_abs = 'UNION ALL (' . $this->db->get_compiled_select()  . ')';
		
	}
	
	$query_order_by = ' ORDER BY userToSubmit asc, ts_year desc, w_no desc, ts_main_id asc';

	$full_query= '(' . $query_main . ') ' . $query_abs . $query_order_by;

	$res =$this->db->query( $full_query );
	//$res_sel = $this->db->get_compiled_select();
	/*$res_sel = $this->db->last_query();*/
	/*echo $full_query;
	die;*/
	if ( $res->num_rows() > 0 ) 
	{
		return $res->result_array();
	}
	else
	{
		return false;
	}
	return;
}



/*
 ######   ######## ########       ##     ## ####  ######  ########  #######  ########  ##    ## 
##    ##  ##          ##          ##     ##  ##  ##    ##    ##    ##     ## ##     ##  ##  ##  
##        ##          ##          ##     ##  ##  ##          ##    ##     ## ##     ##   ####   
##   #### ######      ##          #########  ##   ######     ##    ##     ## ########     ##    
##    ##  ##          ##          ##     ##  ##        ##    ##    ##     ## ##   ##      ##    
##    ##  ##          ##          ##     ##  ##  ##    ##    ##    ##     ## ##    ##     ##    
 ######   ########    ##          ##     ## ####  ######     ##     #######  ##     ##    ##    
*/

/*
|---------------------------------------------------------------------------------
| Get TS History
|---------------------------------------------------------------------------------
*/
public function get_ts_history($ts_id=false,$user_id=false)
{
	$condition = [];
	if ( $ts_id )
	{
		$condition['tsh.ts_id'] = $ts_id;
	}

	if ( $user_id ) 
	{
		// 
		$condition['tsh.user_id'] = $user_id;
		date_default_timezone_set('Asia/Yerevan');
		$today = date("Y-m-d",strtotime('+1 days'));
		$seven_days_before = date("Y-m-d",strtotime('-7 days'));
		$this->db->where('tsh.action_date <=', $today);
		$this->db->where('tsh.action_date >=', $seven_days_before);
	}
	
	$this->db->select('
								tsh.action_date,
								tsh.ts_id,
								tsh.touched_object,
								tsh.comment,
								tsw.ts_year,
								tsw.w_no,
								u.id as performer_id,
								u.name as performer,
								utwo.id as target_id,
								utwo.name as target,
								u.avatar,
								proj.code,
								op.name as operation,
								act.name as action,
								act.label
							')
					->from('ts_history tsh')
					->join('ts_timesheets as tsts', 'tsts.id = tsh.ts_id', 'left')
					//->join('ts_main as tsm', 'tsh.ts_main_id = tsm.id', 'left')
					->join('app_users as u', 'u.id = tsh.user_id', 'left')
					->join('app_users as utwo', 'utwo.id = tsts.user_id', 'left')
					->join('ts_statuses as act', 'act.id = tsh.status_id', 'left')
					->join('projects as proj', 'proj.id = tsh.project_id', 'left')
					->join('operations as op', 'tsh.operation_id=op.id', 'left')
					->join('ts_weeks as tsw', 'tsts.ts_week_id = tsw.id', 'left')
					//->join('ts_activity_types as tsactt', 'tsm.activity_id = tsactt.id', 'left')
					//->join('ts_absence_types as tsabst', 'tsm.absence_id = tsabst.id', 'left')
					->where($condition);
	if ( $user_id ) 
	{
		
		$q1 = $this->db->get_compiled_select();
		$this->db->select('
								tsh.action_date,
								tsh.ts_id,
								tsh.touched_object,
								tsh.comment,
								tsw.ts_year,
								tsw.w_no,
								u.id as performer_id,
								u.name as performer,
								utwo.id as target_id,
								utwo.name as target,
								u.avatar,
								proj.code,
								op.name as operation,
								act.name as action,
								act.label
							')
				->from('ts_history tsh')
				->join('ts_timesheets as tsts', 'tsts.id = tsh.ts_id', 'left')
				//->join('ts_main as tsm', 'tsh.ts_main_id = tsm.id', 'left')
				->join('app_users as u', 'u.id = tsh.user_id', 'left')
				->join('app_users as utwo', 'utwo.id = tsts.user_id', 'left')
				->join('ts_statuses as act', 'act.id = tsh.status_id', 'left')
				->join('projects as proj', 'proj.id = tsh.project_id', 'left')
				->join('operations as op', 'tsh.operation_id=op.id', 'left')
				->join('ts_weeks as tsw', 'tsts.ts_week_id = tsw.id', 'left');
				//->join('ts_activity_types as tsactt', 'tsm.activity_id = tsactt.id', 'left')
				//->join('ts_absence_types as tsabst', 'tsm.absence_id = tsabst.id', 'left')
				//->where($condition);
				//->order_by('tsh.action_date','desc');
		$this->db->where('tsh.action_date <=', $today);
		$this->db->where('tsh.action_date >=', $seven_days_before);
		$this->db->where('tsts.user_id', $user_id);
		
		$q2 = $this->db->get_compiled_select();
		
		$q = $q1 . ' UNION ' . $q2 . ' order by action_date desc';
		$res = $this->db->query($q);
	}
	else
	{
		$this->db->order_by('tsh.action_date','desc');
		$res = $this->db->get();
	}
	/*echo $q;
	die();*/
	return $res->result_array();
	
}

/*
  ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##   
  ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##   
######### ######### ######### ######### ######### ######### ######### ######### ######### 
  ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##   
######### ######### ######### ######### ######### ######### ######### ######### ######### 
  ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##   
  ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##     ## ##   
*/

/*
|---------------------------------------------------------------------------------
| Get User Weeks 
|---------------------------------------------------------------------------------
*/
public function get_user_weeks($user,$year)
{
	$condition = array( 'tsw.ts_year' =>$year ,
						'tsts.user_id' => $user );
	$res = $this->db->select('tsw.w_no')
					->from('ts_timesheets as tsts')
					->join('ts_weeks as tsw','tsw.id=tsts.ts_week_id','left')
					->where($condition)
					->where_not_in('tsts.status_id',array('1','4'))
					->get();
	if ($res->num_rows() > 0) 
	{
		return $res->result_array();
	}
	else
	{
		return false;
	}
	
}


/*
|---------------------------------------------------------------------------------
| Insert TS History row
|---------------------------------------------------------------------------------
*/
public function insert_history_event($event)
{

	$this->db->insert('ts_history', $event);

	return $this->db->insert_id();
	
}



/*
|---------------------------------------------------------------------------------
| Insert TS INFO
|---------------------------------------------------------------------------------
*/
public function insert_ts_info($info)
{
	// Check TS Weeks
	$res = $this->check_ts_week( $info['ts_year'], $info['w_no'] );

	// if exists get id and pass to ts_info
	if ($res) 
	{
		$info['ts_week_id'] = $res;
	}
	else
	{
		// if not exists create new 
		// get id and pass to ts_info
		$week_info = array( 'w_no'		=> $info['w_no'],
							'ts_year'	=> $info['ts_year'],
							'w_start'	=> $info['w_start'],
							'w_end'		=> $info['w_end']
							);

		// Insert New WEEK
		$res_week_ins = $this->insert_ts_week($week_info);

		if ($res_week_ins) 
		{
			$info['ts_week_id'] = $res_week_ins;
		}
		else
		{
			// on ts_week new value insert failure return false
			return false;
		}
	}
	
	unset( $info['ts_year'] );
	unset( $info['w_no'] );
	unset( $info['w_start'] );
	unset( $info['w_end'] );

	//Insert TS INFO
	$ts_id = $this->db->insert('ts_timesheets',$info);

	return $this->db->insert_id();
}

/*
|---------------------------------------------------------------------------------
| Update TS INFO
|---------------------------------------------------------------------------------
*/
public function update_ts_info($info)
{
	$this->db->where('id',$info['ts_id']);
	unset($info['ts_id']);
	// Update TS INFO
	$ts_id = $this->db->update('ts_timesheets',$info);

	return $this->db->affected_rows();
}

/*
|---------------------------------------------------------------------------------
| Update TS Project Status
|---------------------------------------------------------------------------------
*/

public function update_ts_proj_status($info, $reset=false)
{
	if (!$reset) 
	{
		$data['is_accepted'] = $info['is_accepted'];
		unset($info['is_accepted']);

		// passing ts_id/project_id/operation_id to where clause
		$this->db->where($info);
		// updating ts_main
		$this->db->update('ts_main',$data);

		return $this->db->affected_rows();
	}
	else
	{
		$data['is_accepted'] = 1;
		$this->db->where( 'ts_id', $info['ts_id'] );
		$this->db->where_in( 'is_accepted', array(2,3) );
		$this->db->update( 'ts_main', $data );
	}
}

/*
|---------------------------------------------------------------------------------
|  Insert TS MAIN
|---------------------------------------------------------------------------------
*/
public function insert_ts_main($main)
{
	// Returns number of inserted rows
	$res = $this->db->insert_batch('ts_main',$main);

	return $res;
	
}

/*
|---------------------------------------------------------------------------------
|  Insert TS ABSENCE
|---------------------------------------------------------------------------------
*/
/*public function insert_ts_absence($absence)
{
	// Returns number of inserted rows
	$res = $this->db->insert_batch('ts_absence',$absence);

	return $res;
	
}*/

/*
|---------------------------------------------------------------------------------
| Insert TS Week
|---------------------------------------------------------------------------------
*/
public function insert_ts_week( $week_info )
{
	$res = $this->db->insert( 'ts_weeks', $week_info );

	return $this->db->insert_id();
}

/*
|---------------------------------------------------------------------------------
|Update
|---------------------------------------------------------------------------------
*/

/*
|---------------------------------------------------------------------------------
| Delete TS Main
|---------------------------------------------------------------------------------
*/
public function delete_ts_main($ts_id)
{
	$this->db->delete('ts_main',array('ts_id'=>$ts_id));
	return $this->db->affected_rows();
}

/*
|---------------------------------------------------------------------------------
| Delete TS Absence
|---------------------------------------------------------------------------------
*/
/*public function delete_ts_absence($ts_id)
{
	$this->db->delete('ts_absence',array('ts_id'=>$ts_id));
	return $this->db->affected_rows();
}*/


/*
|---------------------------------------------------------------------------------
| Check Not Accepted Timesheet Projects
|---------------------------------------------------------------------------------
*/
public function is_ts_acceptable($ts_id=0)
{
	$condition['ts_id'] = $ts_id;

	$res =$this->db->select('*')
					->from('ts_main')
					->where('ts_id',$ts_id)
					->get();
	
	$allowed_to_accept = true;
	
	if ( $res->num_rows() > 0 ) 
	{
		$result = $res->result_array();

		foreach ($result as $proj) 
		{
			// check for not Accepted or Rejected projects
			if ( $proj['is_accepted'] == 1 OR $proj['is_accepted'] == 3 ) 
			{
				$allowed_to_accept = false;
			}
		}

		return $allowed_to_accept;
	}
	else
	{
		return false;
	}
}



/*
|---------------------------------------------------------------------------------
| Check TS WEEK
| Return " ID " if exists
|---------------------------------------------------------------------------------
*/
public function check_ts_week($year,$week)
{	

	$condition = array('ts_year'=> $year, 
						 'w_no' => $week);
	$res = $this->db->select('id')
					->from('ts_weeks')
					->where($condition)
					->get();

	if ( $res->num_rows() > 0 ) 
	{
		return $res->result_array()[0]['id'];
	}
	else
	{
		return false;
	}


}

/*
|---------------------------------------------------------------------------------
| Check TS Existence and Status for Year Week
|---------------------------------------------------------------------------------
*/
public function check_ts_status( $user, $year=false, $week=false, $ts_id=false )
{

	if ( $ts_id == false) 
	{
		$condition = array(  
							 'user_id' => $user,
						'ts_w.ts_year' => $year,
						   'ts_w.w_no' => $week
							);
	}
	else
	{
		$condition = array(  
							  'ts.id' => $ts_id,
							'user_id' => $user 
							);

	}
	$res = $this->db->select('ts.id, ts_s.name as status')
			->from('ts_timesheets as ts')
			->join('ts_weeks as ts_w', 'ts_w.id=ts.ts_week_id', 'left')
			->join('ts_statuses ts_s', 'ts.status_id=ts_s.id', 'left')
			->where($condition)
			->get();
	
	if ( $res->num_rows() > 0 ) 
	{
		return $res->result_array()[0]['status'];
	}
	else
	{
		return false;
	}
	
}

/*
|---------------------------------------------------------------------------------
| Get TS Activity Types
|---------------------------------------------------------------------------------
*/
public function get_activity_types($id=false)
{
	$condition = array();
	
	if ( $id ) 
	{
		$condition['id'] = $id;
	}
	
	$res = $this->db->select('id, code, name, note')
			->from('ts_activity_types')
			->where($condition)
			->get();

	if ( $res->num_rows() > 0 ) 
	{
		return $res->result_array();
	}
	else
	{
		return false;
	}
}

/*
|---------------------------------------------------------------------------------
| Get TS Absence Types
|---------------------------------------------------------------------------------
*/
public function get_absence_types($id=false)
{
	$condition = array();
	
	if ( $id ) 
	{
		$condition['id'] = $id;
	}
	
	$res = $this->db->select('id, name, note')
			->from('ts_absence_types')
			->where($condition)
			->order_by('sort_order','asc')
			->get();

	if ( $res->num_rows() > 0 ) 
	{
		return $res->result_array();
	}
	else
	{
		return false;
	}
}





//#--> END of m_timesheets Model Class
}





?>