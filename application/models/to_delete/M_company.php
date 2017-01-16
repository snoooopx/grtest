<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_company Model Class for Company info CRUD
*/
class M_company extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	/*
	|---------------------------------------------------------------------------------
	|Read Company Info From DB
	|---------------------------------------------------------------------------------
	*/
	public function get_company()
	{
		$res = $this->db->select('
								 c.id,
								 c.name,
								 u.name as head,
								 c.logo'
								)
				->from('company as c')
				->join('app_users as u','u.id=c.head_id')
				->get();
		if ( $res->num_rows() > 0 ) 
		{
			return $res->result_array()[0];
		}
		else
		{
			return false;
		}
	}

	/*
	|---------------------------------------------------------------------------------
	|Insert Company 
	|---------------------------------------------------------------------------------
	*/
	public function insert( $company )
	{
		$this->db->insert( 'company', $company );

		return $this->db->insert_id();
	}

	/*
	|---------------------------------------------------------------------------------
	|Update Company 
	|---------------------------------------------------------------------------------
	*/
	public function update( $company )
	{
		
	}

	/*
	|---------------------------------------------------------------------------------
	|Delete Company 
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
	}



//-->End of m_company Model Class
}
 ?>