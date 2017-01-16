<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_products Class 
*/
class M_products extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}

	
	/*
	|---------------------------------------------------------------------------------
	| Get (all) Products(s)
	|---------------------------------------------------------------------------------
	*/
	function get_products( $product_id=0, $brief=FALSE, $criterions=FALSE, $qstr=false )
	{
		//Get Logged In User From Session
		$logged_in_user = $this->session->userdata('logged_in');

		// Condition Array for Where Clause
		$condition = array();

		$full_select = '
						p.id,
						p.sku,
						p.name,
						p.created,
						p.last_modified,
						p.description,
						p.custom_box_avatar as avatar,
						p.featured_image,
						p.desert_id,
						p.flavor_id,
						p.color_id,
						des.name as desert_type,
						col.name as color_name,
						fl.name  as flavor_name,
						p.weight,
						p.unit_price as price,
						p.mmt_id,
						mmt.name as mmt_name,
						mmt.sign,
						mmt.sign_image,
						p.use_in_set,
						p.show_in_gallery,
						p.is_active
					';
						/*bt.name btype*/
		
		$brief_select = '
						p.id,
						p.sku,
						p.name,
						p.unit_price as price,
						p.mmt_id,
						mmt.name as mmt_name,
					';
		
		$select = '';

		$is_brief = false;
		// Check for all/singe Select and Get Type (brief/..)
		if ( $product_id == 0 && $brief === FALSE ) 
		{
			$select = $full_select;
		}
		else if ( $product_id == 0 && $brief == TRUE )
		{
			$is_brief = true;
			$select = $brief_select;
			$condition['p.is_active'] = '1';
			$condition['p.use_in_set'] = '1';
			
		}
		else if ( $product_id > 0 && $brief == FALSE )
		{
			$select = $full_select;
			$condition['p.id'] = $product_id;
		}
		else if ( $product_id > 0 && $brief == TRUE )
		{
			$is_brief = true;
			$select = $brief_select;
			$condition['p.id'] = $product_id;
			$condition['p.is_active'] = '1' ;
			$condition['p.use_in_set'] = '1';
		}
		else
		{
			$is_brief = true;
			$select = $brief_select;
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
			$this->db->or_like('p.sku', $criterions['q']);
			$this->db->group_end();
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		// Building Statement
		if ($is_brief) 
		{
			
			$this->db->like('p.name', $qstr);

			$this->db->select($select)
					 ->from('mb_products as p')
					 ->join('app_mmt as mmt', 'mmt.id=p.mmt_id', 'left')
					 ->where($condition);
		}
		else
		{
			$this->db->select($select)
					 ->from('mb_products as p')
					 ->join('mb_deserts as des', 'des.id=p.desert_id', 'left')
					 ->join('mb_flavors as fl', 'fl.id=p.flavor_id', 'left')
					 ->join('mb_colors as col', 'col.id=p.color_id', 'left')
					 ->join('app_mmt as mmt', 'mmt.id=p.mmt_id', 'left')
					 ->where($condition);
		}
	
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
					 ->from('mb_products as p' )
					 ->where( $condition );
			

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
							->or_like('p.sku', $criterions['q'])
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
	}//#get_products


	// Get User Clients
	public function get_user_clients($user_id=false)
	{
		// Condition Array for Where Clause
		$condition_manager = array();
		$condition_product_team = array();
		$select_string = '';
		if ($user_id) 
		{
			$condition_manager['p.manager_id'] = $user_id;
			$condition_product_team['pt.user_id'] = $user_id;
			
			$res = $this->db->select('c.id,c.name as text')
							->from('mb_products as p')
							->join('clients as c','c.id=p.client_id','left')
							->join('product_team as pt','p.id=pt.product_id','left')
							->join('app_users as u', 'u.id=pt.user_id', 'left')
							->group_start()
							 	->where( $condition_manager )
							 	->or_where( $condition_product_team )
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
					->from('product_team as pt')
					->join('mb_products as p','p.id=pt.product_id','left')
					->join('app_users as u','u.id = pt.user_id','left')
					->where($condition);

			$q_team = $this->db->get_compiled_select();

			$this->db->select('p.manager_id, CONCAT( u.name, " ", u.middle, " ", u.sname ) as text')
					->from('mb_products as p')
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


	public function get_product_operations( $product_id=false )
	{
		if ( $product_id ) 
		{
			$res = $this->db->select('op.id, op.name as text')
					->from('mb_products as p')
					->join('ass_operations as asop', 'p.ass_id=asop.ass_id', 'left')
					->join('operations as op', 'op.id=asop.oper_id','right')
					->where('p.id', $product_id)
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

	// Get Products Team
	public function get_product_team( $product_id=false )
	{
		if ( $product_id ) 
		{
			$res =$this->db->select('pt.user_id, u.name')
							->from('product_team as pt')
							->join('app_users as u','u.id=pt.user_id','left')
							->where('pt.product_id',$product_id)
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
	| Insert Products
	|---------------------------------------------------------------------------------
	*/
	function insert( $product )
	{
		$res = $this->db->insert( 'mb_products', $product );

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
	| Update Products Info
	|---------------------------------------------------------------------------------
	*/
	function update( $product )
	{
		$this->db->where( 'id', $product['id'] );
		$this->db->update( 'mb_products', $product );
		return $this->db->affected_rows();
	}
	

	/*
	|---------------------------------------------------------------------------------
	| Check Before Delete Products
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
									  'tbl' => 'mb_set_items',
								  'to_show' => 'Сеты',
								   'col_id' => 'item_id',
									'value' => $id 
								)
							// add order table validation
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
	| Delete Products
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'mb_products' );
		
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
	| Insert Products Team
	|---------------------------------------------------------------------------------
	*/
	public function insert_product_team( $team )
	{
		return $this->db->insert_batch('product_team', $team);
	}
	
	/*
	|---------------------------------------------------------------------------------
	| Delete Products Team
	|---------------------------------------------------------------------------------
	*/
	public function delete_product_team( $product_id=false )
	{
		$condition = [];
		
		if ( $product_id ) 
		{
			$condition['product_id'] = $product_id;
		}
		else
		{
			return false;
		}
		
		$this->db->where($condition);
		$this->db->delete('product_team');

		return $this->db->affected_rows();
	}



/*
|---------------------------------------------------------------------------------
|End of m_products Class
|---------------------------------------------------------------------------------
*/
}
?>