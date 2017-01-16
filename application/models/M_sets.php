<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* M_sets Class 
*/
class M_sets extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_validation');
	}

	
	/*
	|---------------------------------------------------------------------------------
	| Get (all) set(s)
	|---------------------------------------------------------------------------------
	*/
	function get_sets( $set_id=0, $criterions=FALSE, $shop_params=FALSE )
	{
		$select = '
				  st.id,
				  st.name,
				  st.sku,
				  st.description,
				  st.created,
				  st.modified,
				  st.created_by,
				  st.modified_by,
				  st.defined_count,
				  st.price,
				  st.mmt_id,
				  st.in_desert_page,
				  mmt.name as mmt_name,
				  mmt.sign,
				  mmt.sign_image,
				  st.sort_order,
				  st.type,
				  st.featured_image,
				  st.status_id,
				  is_enabled,
				  is_new
				';

		$condition =array();

		// Check for all/single Select 
		if ( $set_id == 0 && $criterions == false && $shop_params != false )
		{
			$condition = array( 'st.is_enabled' => 1 );
			$this->db->order_by( 'st.type','asc' );
			$this->db->order_by( 'st.name','asc' );

			if($shop_params['desert_type'] == 'getnews')
			{
				$condition['st.is_new'] = '1' ;
			}
			else 
			{
				if ($shop_params['desert_type'] != 'getall') 
				{
					$condition['st.in_desert_page'] = $shop_params['desert_type'] ;
				}
			}
		}
		elseif( $set_id != 0 && $criterions == false )
		{
			$condition = array( 'st.id' => $set_id );
		}
		else
		{
			//$this->db->order_by('st.name');
		}

		// Check for Pagination Criterions 
		// And Build Statement
		if ( $criterions !== FALSE ) 
		{
			$startPage = ($criterions['page']-1) * $criterions['per_page'];
			$recordCount = $criterions['per_page'];
			$this->db->limit( $recordCount, $startPage );
			$this->db->like('st.name', $criterions['q']);
			$this->db->order_by($criterions['sort'], $criterions['order']);
		}

		// Building Statement
		$this->db->select( $select )
				 ->from( 'mb_sets as st' )
				 ->join('app_mmt as mmt', 'mmt.id=st.mmt_id', 'left')
				 ->where( $condition );

		// Get Result
		$res = $this->db->get();
		

		if ( $res->num_rows() > 0 ) 
		{
			$finalResult['items'] = $res->result_array();
			
			if ( $criterions == FALSE )
			{
				//GET TOTAL
				$totalRecords = $this->db->count_all_results('mb_sets');
				$finalResult['itemCount'] = $totalRecords;
			}
			else
			{
				//GET FILTERED TOTAL
				$this->db->like('name', $criterions['q']);
				$this->db->from('mb_sets');

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
	}//#get_sets


	/*
	|---------------------------------------------------------------------------------
	| Insert Set Info
	|---------------------------------------------------------------------------------
	*/
	function insert_set_info( $info )
	{
		$res = $this->db->insert( 'mb_sets', $info );

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
	| Insert Set Items
	|---------------------------------------------------------------------------------
	*/
	function insert_set_items( $items )
	{
		$res = $this->db->insert_batch( 'mb_set_items', $items );

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
	| Insert Set attrs
	|---------------------------------------------------------------------------------
	*/
	function insert_set_attrs( $attrs )
	{
		$res = $this->db->insert_batch( 'mb_set_attributes', $attrs );

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
	| Update Set Info
	|---------------------------------------------------------------------------------
	*/
	function update( $set )
	{
		$this->db->where( 'id', $set['id'] );
		$this->db->update( 'mb_sets', $set );
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
								   'col_id' => 'set_id',
									'value' => $id 
								),
							array(
									  'tbl' => 'mb_cart_attributes',
								  'to_show' => 'Корзина',
								   'col_id' => 'set_id',
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
	| Delete set
	|---------------------------------------------------------------------------------
	*/
	public function delete( $id )
	{
		
		$this->db->where( array( 'id'=> $id ) );
		$res = $this->db->delete( 'mb_sets' );
		
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
	// Get Set items
	*/
	public function get_set_items($id,$brieaf=FALSE)
	{
		if ($brieaf !== FALSE) 
		{
			$select_str = '
									items.id,
									items.set_id,
									items.item_id,
									items.qty,
									pr.name,
									pr.custom_box_avatar,
									pr.unit_price as price,
									pr.mmt_id as mmt_id,
									mmt.name as mmt_name
								';
		}
		else
		{
			$select_str = '
									items.set_id,
									items.item_id,
									items.qty,
									pr.name
								';
		}
		$res = $this->db->select('
									items.id,
									items.set_id,
									items.item_id,
									items.qty,
									pr.name,
									pr.custom_box_avatar,
									pr.unit_price as price,
									pr.mmt_id as mmt_id,
									mmt.name as mmt_name
								')
						->from('mb_set_items as items')
						->join('mb_products as pr', 'pr.id=items.item_id','left')
						->join('app_mmt as mmt', 'mmt.id=pr.mmt_id','left')
						->where('items.set_id', $id)
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
	// Get Set Attributes
	*/
	public function get_set_attrs($id)
	{
		$condition = array(
								  'is_active' => 1,
							'setattrs.set_id' => $id
						);
		$res = $this->db->select('
									setattrs.id,
									setattrs.attr_id,
									attr.allow_user_text,
									attr.name,
									attr.featured_image,
									attrgr.id as attrgroup_id,
									attrgr.name as attrgroup_name,
									attrgr.sort_order as attrgroup_sort_order,
									setattrs.unit_price as price,
									setattrs.mmt_id as mmt_id,
									mmt.name as mmt_name
								')
						->from('mb_set_attributes as setattrs')
						->join('mb_attributes as attr', 'attr.id=setattrs.attr_id', 'left')
						->join('mb_attribute_groups as attrgr', 'attrgr.id=attr.attrgroup_id', 'left')
						->join('app_mmt as mmt', 'mmt.id=attr.mmt_id','left')
						->where($condition)
						->order_by('attrgr.sort_order','asc')
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
	| Delete Set items
	*/
	public function delete_set_items($id)
	{
		$this->db->where('set_id',$id);
		$res = $this->db->delete('mb_set_items');
	}


	/*
	| Delse Set Attributes
	*/
		public function delete_set_attributes($id)
	{
		$this->db->where('set_id',$id);
		$res = $this->db->delete('mb_set_attributes');
	}



/*
|---------------------------------------------------------------------------------
|End of M_sets Class
|---------------------------------------------------------------------------------
*/
}
?>