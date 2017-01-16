<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* M_clients Class 
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
	| Get (all) client(s)
	|---------------------------------------------------------------------------------
	*/
	function get_clients( $client_id=0, $criterions=FALSE )
	{
		$select = '
				  	cl.id,
					cl.fname,
					cl.sname,
					cl.email,
					cl.phone,
					claddr.city_id,
					claddr.street,
					claddr.bld,
					claddr.apt,
					cl.date_of_birth,
					cl.password,
					cl.created,
					cl.last_password_change,
					cl.is_subscribed,
					cl.login_enabled,
					cl.is_activated,
					cl.activation_code
				';

		$condition =array();

		// Check for all/single Select 
		if ( $client_id == 0 && $criterions == false )
		{
			return false;
		}
		elseif( $client_id != 0 && $criterions == false )
		{
			$condition = array( 'cl.id' => $client_id );
		}

		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->group_start();
			$this->db->like('cl.fname', $criterions['q']);
			$this->db->or_like('cl.sname', $criterions['q']);
			$this->db->or_like('cl.email', $criterions['q']);
			$this->db->or_like('cl.phone', $criterions['q']);
			$this->db->group_end();
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		// Building Statement
		$this->db->select( $select )
				 ->from( 'mb_clients as cl' )
				 ->join( 'mb_client_addresses as claddr', 'claddr.client_id=cl.id', 'left')
				 ->where( $condition )
				 ->order_by('cl.fname');

		// Get Result
		$res = $this->db->get();
		

		if ( $res->num_rows() > 0 ) 
		{
			$finalResult['items'] = $res->result_array();
			
			if ( $criterions['q'] == FALSE )
			{
				//GET TOTAL
				$totalRecords = $this->db->count_all_results('mb_clients');
				$finalResult['itemCount'] = $totalRecords;
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->group_start();
				$this->db->like('cl.fname', $criterions['q']);
				$this->db->or_like('cl.sname', $criterions['q']);
				$this->db->or_like('cl.email', $criterions['q']);
				$this->db->or_like('cl.phone', $criterions['q']);
				$this->db->group_end();
				$this->db->from('mb_clients as cl');

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
	}//#get_clients


	/*
	|---------------------------------------------------------------------------------
	|Checking Login Info
	|---------------------------------------------------------------------------------
	*/
	function check_login( $email, $password )
	{
		$this->db->select('
							cl.id, 
							cl.fname, 
							cl.sname, 
							cl.email,
							cl.is_activated,
							cl.login_enabled,
							cl.password'
						);
		$this->db->from('mb_clients as cl');
		$this->db->where( array( 'email'=> $email, 'login_enabled'=> 1 ) );

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
				$is_customer = '1';

				//User Session Info
				$user_session_info = array(
											'id' 			=> $res['id'],
											'is_customer'  	=> $is_customer,
											'fname' 		=> $res['fname'],
											'sname' 		=> $res['sname'],
											'email' 		=> $res['email'],
											'is_activated' 	=> $res['is_activated'],
											'login_enabled' => $res['login_enabled'],
										);

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
	| Insert Client Info
	|---------------------------------------------------------------------------------
	*/
	function insert_info( $info )
	{
		$res = $this->db->insert( 'mb_clients', $info );

		if ( $res ){
			return $this->db->insert_id();
		} else {
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
		$this->db->update( 'mb_clients', $client );
		return $this->db->affected_rows();
	}
	
	
	
	/*
	|---------------------------------------------------------------------------------
	| Insert Activation Code
	|---------------------------------------------------------------------------------
	*/
	function insert_act_code($client_id,$code)
	{
		$res = $this->db->insert('mb_client_act_codes', array('client_id'=>$client_id, 'act_code'=>$code));
		if ($res) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/*
	|---------------------------------------------------------------------------------
	| Activate Client
	|---------------------------------------------------------------------------------
	*/
	function activate_client($act_code)
	{

		$qstr = 'update mb_clients as cl
						left join mb_client_act_codes as cac
							on cac.client_id=cl.id
				 set cl.is_activated = 1
				 where cac.act_code='.$this->db->escape($act_code);

		/*$this->db->set('is_activated','1');
		$this->db->where('act_code',$act_code);
		$this->db->where('mb_clients.id','mb_client_act_codes.client_id');
		$res = $this->db->update('mb_clients mb_client_act_codes');*/


		$res = $this->db->query($qstr);
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
									  'tbl' => 'mb_order_items',
								  'to_show' => 'Ордеры',
								   'col_id' => 'client_id',
									'value' => $id 
								)
						);

		// Check For User Constraints
		foreach ($checkable_tables as $searchable)
		{
			$res = $this->db->select()
							->from($searchable['tbl'])
							->where($searchable['col_id'], $searchable['value'])
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
	| Delete client
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'mb_clients' );
		
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
	// Get Client addresses
	*/
	public function get_client_addresses($id)
	{
		$res = $this->db->select('
									claddr.id,
									claddr.city_id,
									
									claddr.street,
									claddr.bld,
									claddr.apt,
									is_default
								')
						->from('mb_client_addresses as claddr')
						->where('claddr.client_id', $id)
						->get();
		if ($res->num_rows()>0) 
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
	| Insert Client Addresses
	|---------------------------------------------------------------------------------
	*/
	function insert_address( $address )
	{
		$res = $this->db->insert( 'mb_client_addresses', $address );

		if ( $res ){
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	/*
	|---------------------------------------------------------------------------------
	| Update Client Address
	|---------------------------------------------------------------------------------
	*/
	function update_address( $cl_addr )
	{
		$this->db->where( 'client_id', $cl_addr['client_id'] );
		$this->db->update( 'mb_client_addresses', $cl_addr );
		return $this->db->affected_rows();
	}


	/*
	| Delete Client address
	*/
	public function delete_address($id)
	{
		$this->db->where('client_id',$id);
		$res = $this->db->delete('mb_client_addresses');
	}

/*
|---------------------------------------------------------------------------------
|End of M_clients Class
|---------------------------------------------------------------------------------
*/
}
?>