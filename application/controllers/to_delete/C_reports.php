<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';
/**
* reports class for Reports, options, and so on
*/
class C_reports extends REST_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_reports');
		//$this->load->model('m_validation');
		$this->load->library('form_validation');
		$this->load->model('m_clients');
		$this->load->model('m_assignments');
		$this->load->model('m_projects');
		$this->load->library('Excel');
	}


	public function index_get()
	{
		/*
		|---------------------------------------------------------------------------------
		| //Permission check Section
		|---------------------------------------------------------------------------------
		*/
		$data['active_section'] = 'settings';
		$data['active_page'] 	= 'reports';

		// Checking User Session Activity
		$data['userinfo'] = $this->m_validation->check_user_loggedin();

		// Checking for Requerted Page Permissions
		$allow = $this->m_validation->check_requested_page_perms( $data['active_section'], $data['active_page'] );

		############################################
		###****** READ PERMISSION CHECK *********###
		############################################	
		if ( $allow['read'] === FALSE )
		{
			// Loading Header File
			$this->load->view('templates/header', $data);
			// Getting Sidebar From Session
			$data['sidebar'] = $this->session->userdata('sidebar');
			// Loading Sidebar File
			$this->load->view('templates/sidebar', $data);
			//Loading Error Content
			$this->load->view('errors/error_550', $data);
			// Loading Footer File
			$this->load->view('templates/footer', $data);
			//exit;
			return;
		}

		// Load TS Helper For Getting missed Reports
		$this->load->helper('ts');
		$data['notify'] = get_notifications_helper($data['userinfo']['id']);

		// Passing Requested Page Permissions For Later Use in View
		$data['allow'] = $allow;

		// Loading Header File
		$this->load->view('templates/header', $data);
		
		// Getting Sidebar From Session
		$data['sidebar'] = $this->session->userdata('sidebar');

		// Loading Sidebar File
		$this->load->view('templates/sidebar', $data);

		######################################################################################################
		#################################### * End of Permission Check * #####################################
		######################################################################################################

		// Get Users
		//$data['user_list'] = $this->m_users->get_users()['items'];

		// Load user list
		if ( $data['userinfo']['ceo'] == 1 || $data['userinfo']['is_admin'] == 1) 
		{
			// select all
			$criterion['type'] = 3;
			// Get User List
			$data['user_list'] = $this->m_users->get_user_list($criterion);
		} 
		else if ( $data['userinfo']['head_of_dep'] == 1 )
		{
			// Select Department all
			$criterion['type'] = 2;
			$criterion['dep_id']  = $data['userinfo']['dep_id'];
			//print_r($criterion);
			// Get User List
			$data['user_list'] = $this->m_users->get_user_list($criterion);
		}
		else
		{
			// Select logged in user
			$data['user_list'][0]['id'] = $data['userinfo']['id'] ;
			$data['user_list'][0]['name'] = $data['userinfo']['name'] ;
			$data['user_list'][0]['middle'] = $data['userinfo']['middle'] ;
			$data['user_list'][0]['sname'] = $data['userinfo']['sname'] ;
		}

		// Get Clients
		$data['client_list'] = $this->m_clients->get_clients()['items'];

		// Get Assignmnets list
		$data['ass_list'] = $this->m_assignments->get_assignments(0,true)['items'];		

		// Loading Report Main Section 
		$this->load->view('pages/reports/main', $data);

		// Loading Scripts ( Modals/Buttons...)
		$data['scripts'] = $this->load->view('pages/reports/scripts', $data, true);

		// Loading Footer File
		$this->load->view('templates/footer', $data);
	}



	// Generte Report Function
	public function generate_get($id=0)
	{

		if ($this->get('user_id') !== false && $this->get('client_id') !== false && $this->get('from') !== false && $this->get('to') !== false && $this->get('from_x') !== false && $this->get('to_x') !== false && $this->get('type') !== false && $this->get('ass_id') !== false)
		{
			$params = [];
			$report_type 		 = trim($this->get('type'));
			$params['user_id'] 	 = trim($this->get('user_id'));
			$params['client_id'] = trim($this->get('client_id'));
			$params['ass_id'] 	 = trim($this->get('ass_id'));
			
			date_default_timezone_set('Asia/Yerevan');
				

			if (trim($this->get('from'))!='' && trim($this->get('to'))!='' && trim($this->get('from_x'))!='' && trim($this->get('to_x'))!='') 
			{
				$from = strtotime(trim($this->get('from')));
				$to = strtotime(trim($this->get('to')));

				$from_year 	= date('Y',$from);
				$from_wd 	= date('w',$from);
				$from_week 	= date('W',$from);
				$params['from_date'] 		= date('Y-m-d', $from);
				$params['from_wstart_date'] = date('Y-m-d',strtotime(trim($this->get('from_x'))));
				
				$to_year	= date('Y',$to);
				$to_wd 		= date('w',$to);
				$to_week 	= date('W',$to);
				$params['to_date'] 	 		= date('Y-m-d',$to);
				$params['to_wend_date'] 	= date('Y-m-d',strtotime(trim($this->get('to_x'))));




				/*if ( $from < $to ) 
				{
					$this->response(array('status'=>'failure','message'=>'Invalid Date Period.'));
					return;
				}*/
				if ($from_wd==0) 
				{
					$from_wd=7;
				}
				if ($to_wd==0) 
				{
					$to_wd=7;
				}

				/*
				$this->response($from_year.'/'.
$from_wd.'/'.
$from_week.'-->'.$to_year.'/'.
								$to_wd.'/'.
								$to_week);*/
				
				
				$params['same_day'] = false;
				$params['same_week'] = false;
				//Same Year / Same Week / Same WD
				if ( $from_year == $to_year && $from_week == $to_week && $from_wd == $to_wd ) 
				{
					$params['same_day'] = true;
					
					// Generate One Day Str
					$params['from_str'] = $params['to_str'] = ' tsm.wd'.$from_wd . ' ';
				}
				//Same Year / Same Week / different WD`s
				elseif( $from_year == $to_year && $from_week == $to_week && $from_wd != $to_wd )
				{
					$params['same_week'] = true;
					$params['from_str'] = '';
					$frmo_wd_temp = $from_wd;
					for ($i=0; $i <= ($to_wd-$from_wd); $i++) 
					{ 
						if ($i != ($to_wd-$from_wd) )
						{
							$operation = '+';
						}
						else
						{	
							$operation = '';
						}
						
						$params['from_str'] .= 'tsm.wd'. $frmo_wd_temp . $operation;
						$frmo_wd_temp++;
					}
					//$this->response($params['from_str']);
				
					
				}
				else
				{
					// Generate From First Week WD String
					switch ($from_wd) {
						case 1:
							$str_from = 'tsm.wd1+tsm.wd2+tsm.wd3+tsm.wd4+tsm.wd5+tsm.wd6+tsm.wd7';			
							break;
						case 2:
							$str_from = 'tsm.wd2+tsm.wd3+tsm.wd4+tsm.wd5+tsm.wd6+tsm.wd7';			
							break;
						case 3:
							$str_from = 'tsm.wd3+tsm.wd4+tsm.wd5+tsm.wd6+tsm.wd7';			
							break;
						case 4:
							$str_from = 'tsm.wd4+tsm.wd5+tsm.wd6+tsm.wd7';			
							break;
						case 5:
							$str_from = 'tsm.wd5+tsm.wd6+tsm.wd7';			
							break;
						case 6:
							$str_from = 'tsm.wd6+tsm.wd7';			
							break;
						case 7:
							$str_from = 'tsm.wd7';			
							break;

						default:
							$str_from = '';
							break;
					}

					// Generate To Last Week WD String
					switch ($to_wd) 
					{
						case 1:
							$str_to = 'tsm.wd1';
							break;
						case 2:
							$str_to = 'tsm.wd1+tsm.wd2';
							break;
						case 3:
							$str_to = 'tsm.wd1+tsm.wd2+tsm.wd3';
							break;
						case 4:
							$str_to = 'tsm.wd1+tsm.wd2+tsm.wd3+tsm.wd4';
							break;
						case 5:
							$str_to = 'tsm.wd1+tsm.wd2+tsm.wd3+tsm.wd4+tsm.wd5';
							break;
						case 6:
							$str_to = 'tsm.wd1+tsm.wd2+tsm.wd3+tsm.wd4+tsm.wd5+tsm.wd6';
							break;
						case 7:
							$str_to = 'tsm.wd1+tsm.wd2+tsm.wd3+tsm.wd4+tsm.wd5+tsm.wd6+tsm.wd7';
							break;

						default:
							$str_to = '';
							break;
					}
					$params['from_str'] = $str_from;
					$params['to_str'] = $str_to;
				}
			}
			else
			{	
				$params['from_date'] 		= '';
				$params['from_wstart_date'] = '';
				$params['to_date'] 	 		= '';
				$params['to_wend_date'] 	= '';
				$params['from_str']			= '';
				$params['to_str'] 			= '';
			}
			
			$projects = array();
			$pitush_project = '';
			$final_rep = array();
			$final_rep['report']['projects'] = $projects;

			if ( $report_type == 'uc' ) 
			{
				// User Report 
				$final_rep['report'] = $this->m_reports->generate_report($params,$report_type);
			}
			elseif( $report_type == 'cu' )
			{
				// Client Report
				$final_rep['report'] = $this->m_reports->generate_report($params,$report_type);
			}
			elseif( $report_type == 'mx')
			{
				// User Report 
				$res['report'] = $this->m_reports->generate_report($params,$report_type);
				if (isset($res['report'][0])) 
				{
					$pitush_project=$res['report'][0]['project_code'];
				}


				// Generate User Project Report
				$projects[] = $pitush_project;

				// Get Project Code list from response
				foreach ($res['report'] as $row) 
				{
					if ($pitush_project==$row['project_code'] || in_array($row['project_code'], $projects)) 
					{
						continue;
					}
					else
					{
						$projects[] = $row['project_code'];
						$pitush_project = $row['project_code'];
					}
				}
				
				$final_rep['report']['projects'] = $projects;
				/*
				Generate User Per Project Time Row
				eg.	user_id=>(
							user_name=>user,
							code=>time,
							code=>time,
							...
						)
				*/
				// itarate on user project timing row
				foreach ($res['report'] as $row) 
				{	
					// each row is an array which id is user_id
					// and contains user_name which is users "full name"
					$final_rep['report']['timing'][$row['user_id']]['user_name'] = $row['user'];

					// Iterate project list and set project time for current project
					// If 

					for ($i=0; $i < count($projects); $i++)
					{ 
						if ( $projects[$i] == $row['project_code'] )
						{
							$final_rep['report']['timing'][$row['user_id']][$projects[$i]] = $row['time'];
						}
						else
						{
							if (!isset( $final_rep['report']['timing'][$row['user_id']][$projects[$i]] ) || $final_rep['report']['timing'][$row['user_id']][$projects[$i]] == 0 ) 
							{
								$final_rep['report']['timing'][$row['user_id']][$projects[$i]] = 0;
							}
						}
					}
				}

			}


			// Check for export button click
			// For exp_type: 'xlsx' export Excel file
			if ( $this->get('is_exp') != false) 
			{
				if ( $this->get('exp_type') != false AND $this->get('exp_type') == 'xlsx')
				{
					
					$this->excel_export($final_rep);
				}	
			}
			else
			{
				$this->response($final_rep);
			}
		}
	}



	/*
	#
		Export Requested Report To Excel File
	#
	*/
	public function excel_export($final)
	{
		// Set properties
		$this->excel->getProperties()->setCreator("BDO TMSAPP")
								 	 ->setLastModifiedBy("BDO TMSAPP")
								 	 ->setTitle("Report")
								 	 ->setSubject("Report")
								 	 ->setDescription("Report For Matrix Type.")
								 	 ->setKeywords("BDO TMSAPP")
								 	 ->setCategory("BDO TMSAPP");

		$row = 1;
		// Fill Report Period
		$this->excel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(0,$row, 'from: '.$this->get('from').' > to:'.$this->get('to'));
		$row = $row + 1;
		// Fill Project list in First Row as Header
		for ($i=0; $i < count($final['report']['projects']) ; $i++) 
		{ 
			$this->excel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($i+1,$row, $final['report']['projects'][$i]);
		}
		
		$row = $row + 1;
		foreach ($final['report']['timing'] as $user_rep)
		{	$col = 0;
			$this->excel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($col,$row, $user_rep['user_name']);
			unset($user_rep['user_name']);

			foreach ($user_rep as $rep)
			{ 
				$col++;
				$this->excel->setActiveSheetIndex(0)
						->setCellValueByColumnAndRow($col,$row, $rep);
			}

			$row++;
		}
		
		// Rename sheet
		$this->excel->getActiveSheet()->setTitle('Matrix');


		$this->excel->setActiveSheetIndex(0);
			
	
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="report.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		ob_end_clean();
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		$objWriter->save('php://output');
	}

	/*
	#
		Get User Clients
	#
	*/
	public function ax_user_clients_get()
	{
		if ($this->get('user_id')) 
		{
			$user_id = trim($this->get('user_id'));
			$this->load->model('m_projects');
			$res = $this->m_projects->get_user_clients($user_id);
			$this->response($res,REST_Controller::HTTP_OK);
		}
		else
		{
			$get_status = array('status' => 'failure','message' =>'invalid parameters');

			$this->response($get_status,REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	/*
	#
		Get Client users
	#
	*/
	public function ax_client_users_get()
	{
		if ($this->get('client_id')) 
		{
			$client_id = trim($this->get('client_id'));
			$this->load->model('m_projects');
			$res = $this->m_projects->get_client_users($client_id);
			$this->response($res,REST_Controller::HTTP_OK);
		}
		else
		{
			$get_status = array('status' => 'failure','message' =>'invalid parameters');

			$this->response($get_status,REST_Controller::HTTP_BAD_REQUEST);
		}
	}



}
?>