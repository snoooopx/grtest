<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_clients Class 
*/
class M_clients extends CI_Model
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
	function get_clients( $client_id=0, $brief=FALSE, $criterions=FALSE )
	{
		$logged_in_user = $this->session->userdata('logged_in');

		$where_in_subselect = 'select depcls.client_id 
							   from dep_clients as depcls 
							   where depcls.dep_id = '. $logged_in_user['dep_id'] .' 
							   group by depcls.client_id';

		$full_select = '
						c.id, 
						c.name, 
						CONCAT(c.name) as fullName,
						c.abbr, 
						c.phone,
						c.address, 
						c.contact_person, 
						c.email, 
						c.bank_acc, 
						c.reg_num, 
						c.tin,
						c.is_visible,
					GROUP_CONCAT( d.name separator ", " ) as dep_names,
					GROUP_CONCAT( dc.dep_id ) as dep_ids_str,

						(
							select GROUP_CONCAT( s.name separator ", " ) as sec_nam
							from client_sectors as sc
							left join business_sectors as s on s.id=sc.sector_id
							where sc.client_id=c.id
						) 
						as sec_names,
						(
							select GROUP_CONCAT( ss.id separator ", " ) as sec_ids
							from client_sectors as scsc
							left join business_sectors as ss on ss.id=scsc.sector_id
							where scsc.client_id=c.id
						) 
						as sec_ids_str,
					';
						/*bt.name btype*/

		$brief_select = '
						c.id, 
						c.name, 
						c.abbr, 
					';
		
		$select = '';

		$condition =array();

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
			$this->db->where_in('dc.client_id', $where_in_subselect, false);
			$isAdmin = false;
		}


		// Check for all/singe Select and Get Type (brief/..)
		if ( $client_id == 0 && $brief === FALSE ) 
		{
			$select = $full_select;
		}
		else if ( $client_id == 0 && $brief == TRUE )
		{
			$select = $brief_select;
			$condition = array('c.is_visible' => '1' );
		}
		else if ( $client_id > 0 && $brief == FALSE )
		{
			$select = $full_select;
			$condition = array( 'c.id' => $client_id );
		}
		else if ( $client_id > 0 && $brief == TRUE )
		{
			$select = $brief;
			$condition = array( 'c.id' => $client_id, 'c.is_visible' => '1' );
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
			$this->db->group_start()
					->like('c.name', $criterions['q'])
					->or_like('c.abbr', $criterions['q'])
					->group_end();
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}
		else
		{
			$this->db->order_by('c.name','asc');
		}

		// Building Statement
		$this->db->select( $select )
				 ->from( 'clients as c' )
				 /*->join( 'business_type as bt','bt.id = c.btype_id','left' )*/
				 ->join( 'dep_clients as dc','dc.client_id = c.id','left' )
				 ->join( 'departments as d', 'd.id = dc.dep_id', 'left' )
				 ->where( $condition )
				 ->group_by('c.id');

	
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
				$this->db->where_in('dc.client_id', $where_in_subselect, false);
			}

			$this->db->select('count(c.id)');
			$this->db->from('clients c');
			$this->db->join('dep_clients as dc', 'dc.client_id=c.id', 'left');
			$this->db->where($condition);
			$this->db->group_by('c.id');

			if ( $criterions['q'] == FALSE )
			{
				//GET TOTAL
				$res = $this->db->get();

				$finalResult['itemCount'] = $res->num_rows();
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->group_start()
							->like('c.name', $criterions['q'])
							->or_like('c.abbr', $criterions['q'])
						->group_end();
				//$this->db->from('clients');
				$res = $this->db->get();

				//$totalFilteredRecords = $this->db->count_all_results();
				
				$finalResult['itemFilteredCount'] = $res->num_rows();
				$finalResult['itemCount'] = $res->num_rows();//$totalFilteredRecords;
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
	}//#get_clients


	/*
	|---------------------------------------------------------------------------------
	| Insert Client
	|---------------------------------------------------------------------------------
	*/
	function insert( $client )
	{
		$res = $this->db->insert( 'clients', $client );

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
	| Update Client Info
	|---------------------------------------------------------------------------------
	*/
	function update( $client )
	{
		$this->db->where( 'id', $client['id'] );
		$this->db->update( 'clients', $client );
		return $this->db->affected_rows();
	}


	/*
	|---------------------------------------------------------------------------------
	| Check Before Delete Client
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
								   'col_id' => 'client_id',
									'value' => $id 
								)/*,
							array(
									  'tbl' => 'dep_clients',
								  'to_show' => 'Departments',
								   'col_id' => 'client_id',
									'value' => $id 
								),
							array(
									  'tbl' => 'client_sectors',
								  'to_show' => 'Sectors',
								   'col_id' => 'client_id',
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
		$res = $this->db->delete( 'clients' );
		
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
|End of m_clients Class
|---------------------------------------------------------------------------------
*/
}
?>