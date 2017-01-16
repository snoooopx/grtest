<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* timesheet helper class
*/

if ( ! function_exists( 'check_not_created_ts_helper' ) ) 
{
	function check_not_created_ts_helper($user_id)
	{
		$needed_weeks = [];
		/*date_default_timezone_set('Asia/Yerevan');
		//$current_date = date('Y-m-d H:i');
		$current_year = date('Y');
		$current_week = date('W');

		// Get CI Instance
		$ci_this = &get_instance();

		$ci_this->load->model('m_timesheets');

		$res_weeks = $ci_this->m_timesheets->get_user_weeks( $user_id, $current_year );
		$needed_weeks = [];
		// 
		if( $res_weeks )
		{
			for ($i=1; $i < $current_week; $i++) 
			{ 
				if ( !in_array(array('w_no'=>$i), $res_weeks) ) 
				{
					array_push($needed_weeks, $i);
				}
			}
		}
		else
		{
			for ($i=1; $i < $current_week; $i++) 
			{ 
					array_push($needed_weeks, $i);
			}
		}*/
		return $needed_weeks;			
	}	
}


if ( ! function_exists( 'get_notifications_helper' ) ) 
{
	function get_notifications_helper($user_id)
	{
		$notifics['missed_tss'] = check_not_created_ts_helper($user_id);
		/*// Get Missed Timesheets

		// Get CI Instance
		$ci_this = &get_instance();

		// Load Model
		$ci_this->load->model('m_timesheets');
		
		// Get Timesheets to Accept
		//#######################################
		// false - for not using criterions
		// 2 - for getting pending to accept Timesheets 
		$notifics['pending_list'] = $ci_this->m_timesheets->get_ts(false,2);

		// Get Timesheet Projects to Accept
		//#######################################
		// 2 - request type-> pending ts projects
		// false - no criterions
		$notifics['pending_project_list'] = $ci_this->m_timesheets->get_full_ts(2,false);
*/
		return $notifics;
	}	
}

?>