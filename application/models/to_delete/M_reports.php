<?php
/**
* M_reports class
*/
class M_reports	extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function generate_report($params = false, $type)
	{
		$lgdus = $this->session->userdata('logged_in');

		if ( $params ) 
		{
			$condition = [];
			
			#################################################################
			// tsm -> ts_type = 1 (Get Timesheet main sections only)
			#################################################################
			$condition['tsm.ts_type']='1';
			
			#################################################################
			// tsm -> activity_id = 1 (Get Billable client only)
			#################################################################
			$condition['tsm.activity_id']='1';
			
			#################################################################
			// tsts -> status_id = 3 ( Get Approved timesheets only)
			#################################################################
			$condition['tsts.status_id'] = '3';

			#################################################################
			// Check for Client Select
			#################################################################
			if ($params['client_id'] != '00') 
			{
				$condition['pr.client_id'] = $params['client_id'];
			}

			#################################################################
			// Check for User Select
			#################################################################
			if ($params['user_id'] != '00') 
			{
				$condition['tsts.user_id'] = $params['user_id'];
			}
			$need_ass_join = false;
			#################################################################
			// Check For Assignment
			#################################################################
			if ( $type == 'uc' OR $type == 'mx' OR $type == 'cu' ) 
			{
				// if One Assignment selected
				if ($params['ass_id'] != '00' && $params['ass_id'] != '-1') 
				{
					$condition['pr.ass_id'] = $params['ass_id'];
					
					if ($lgdus['viewAllReports'] != 1) 
					{
						if ($lgdus['ceo'] != 1 && $lgdus['is_admin'] != 1 && $lgdus['head_of_dep'] == 1)// Check for head of dep
						{
							$need_ass_join = true;
							$condition['das.dep_id'] = $lgdus['dep_id'];
						}
						elseif ($lgdus['ceo'] != 1 && $lgdus['is_admin'] != 1 && $lgdus['head_of_dep'] != 1)// Check for user
						{
							$condition['tsts.user_id'] = $lgdus['id'];	
						}
					}
				}
				else
				{	
					if ($lgdus['viewAllReports'] != 1) 
					{
						if ($lgdus['ceo'] != 1 && $lgdus['is_admin'] != 1 && $lgdus['head_of_dep'] == 1)
						{
							$need_ass_join = true;
							$condition['das.dep_id'] = $lgdus['dep_id'];
							
						}
						elseif ($lgdus['ceo'] != 1 && $lgdus['is_admin'] != 1 && $lgdus['head_of_dep'] != 1)
						{
							$condition['tsts.user_id'] = $lgdus['id'];
						}
					}
					
				}
			}
			else
			{
				return false;
			}

			$full_wds = ' tsm.wd1+tsm.wd2+tsm.wd3+tsm.wd4+tsm.wd5+tsm.wd6+tsm.wd7 ';
			#################################################################
			// Check Date and genearate query string for Time SUM
			#################################################################
			if ($params['from_wstart_date'] !='' && $params['to_wend_date']!='' 
					&& $params['from_date'] !='' && $params['to_date']!='') 
			{
				$condition['tswfrom.w_start >='] = $params['from_wstart_date'];
				$condition['tswfrom.w_end <='] 	 = $params['to_wend_date'];

				//Check for Same Day / Same Week Report
				if ( !$params['same_day'] && !$params['same_week']) 
				{
					################################################################################################
					// When "from" and "to" were not in same week,
					// Check if "from" and "to" were between db`s w_start and w_end range
					// Pass appropriate WD`s to current weeks(e.g. wd4,wd5,wd6,wd7 only for "from") SUM aggregation
					// Other Weeks are aggregating completely
					################################################################################################
				/*	$date_string =' SUM( CASE WHEN DATE(\''.$params['from_date'].'\')>=tswfrom.w_start '
												.'AND DATE(\''.$params['from_date'].'\')<=tswfrom.w_end '
									 		.'THEN '.$params['from_str'].' '
									  		.'WHEN DATE(\''.$params['to_date'].'\')>=tswfrom.w_start '
												.'AND DATE(\''.$params['to_date'].'\')<=tswfrom.w_end '
									 		.'THEN '.$params['to_str']
									 		.' ELSE '.$full_wds.' END) as time';*/
					$date_string =' SUM( CASE WHEN DATE(\''.$params['from_date'].'\') BETWEEN tswfrom.w_start AND   tswfrom.w_end '
									 		.' THEN '.$params['from_str'].' '
									  		.' WHEN DATE(\''.$params['to_date'].'\') BETWEEN tswfrom.w_start AND tswfrom.w_end '
									 		.' THEN '.$params['to_str']
									 		.' ELSE '.$full_wds.' End) as time ';
				}
				else
				{
					// When "from" and "to" are same date, get current WD
					$date_string = ' SUM('.$params['from_str'].') as time ';
				}

			}
			else
			{
				$date_string = ' SUM('.$full_wds.') as time ';
			}

			#################################################################
			// Generate Query "Group by / Order by" Statements
			#################################################################
			if ($type == 'uc' || $type == 'mx') 
			{

				$this->db->group_by('tsts.user_id, pr.client_id, pr.id');
				$this->db->order_by('u.name asc, c.name asc');
			}
			else if ($type == 'cu')
			{
				$this->db->group_by('pr.client_id, pr.id, tsts.user_id');
				$this->db->order_by('c.name asc, u.name asc');
			}
			
			
			#################################################################
			// Get Result
			#################################################################
			$this->db->select('
								u.id as user_id,
								CONCAT(u.name," ",u.middle," ",u.sname) as user,
								c.name as client,
								pr.code project_code,'
								.$date_string
							)
							->from('ts_main as tsm')
							->join('ts_timesheets as tsts', 'tsts.id = tsm.ts_id', 'left')
							->join('ts_weeks as tswfrom', 'tswfrom.id = tsts.ts_week_id', 'left')
							//->join('ts_weeks as tswto', 'tswto.id = tsts.ts_week_id', 'left')
							->join('projects as pr', 'pr.id = tsm.project_id', 'left')
							/*->join('dep_assignments as das', 'das.ass_id=pr.ass_id', 'left')*/
							->join('app_users as u', 'u.id=tsts.user_id', 'left')
							->join('clients as c', 'c.id=pr.client_id', 'left');
							
			if ($need_ass_join) 
			{
				$this->db->join('dep_assignments as das', 'das.ass_id=pr.ass_id', 'left');
			}
			$this->db->where($condition);
			$res = $this->db->get();
			//$q = $this->db->get_compiled_select();
			//echo $q;
			//exit;
			return $res->result_array();

		}
		else
		{
			return false;
		}
	}


// >> End of m_reports
}
?>

