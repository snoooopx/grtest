<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* settings class for site settings DB manipulations
*/
class M_settings extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function get_all($key='')
	{
		$condition = [];
		if ( $key !== '' ) 
		{
			$condition['name'] = $key;
		}

		$res = $this->db->select('*')
						->from('app_configs')
						->get();

		if ($res->num_rows() > 0) {
			$configs = [];
			foreach ($res->result_array() as $key => $value) 
			{
				$config[$value['name']]=$value['value'];
			}
			return $config;
		} else {
			return false;
		}
	}

	public function update($configs='')
	{
		
		return $this->db->update_batch('app_configs',$configs, 'name');
	}


}


 ?>