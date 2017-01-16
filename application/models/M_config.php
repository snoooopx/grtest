<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Config Model Class for Fetching User Sidebar, Settings etc...
*/
class M_config extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
		
	}

	protected $backend = 'backend';
	private $gallery_dir = "application/assets/img/gallery";
	private $upload_dir = "application/assets/uploads";

	public function get_upload_dir()
	{
		return $this->upload_dir;
	}

	public function get_gallery_dir()
	{
		return $this->gallery_dir;
	}
	/*
	|---------------------------------------------------------------------------------
	|Get Sidebar Sections for Specified User With Permissions
	|---------------------------------------------------------------------------------
	*/
	function get_sections($user_id='0')
	{
		$db = $this->db;
/*
					ss.id sub_section_id,
					s.id sectionid,*/
		$db->select('
					s.name section_name,
					s.icon section_icon,
					s.link as section_link,
					s.color,
					ss.name subsection_name,
					ss.icon subsection_icon,
					ss.link as subsection_link,
					perms.id,
					perms.section_id,
					perms.subsection_id,
					perms.user_id,
					perms.c,
					perms.r,
					perms.u,
					perms.d,
					perms.section_seq,
					perms.subsection_seq
			')
			->from('app_permissions as perms')
			->join('app_sections as s','perms.section_id=s.id', 'left')
			->join('app_subsections as ss','perms.subsection_id=ss.id', 'left')
			->where(array( 
							'perms.user_id' => $user_id,
							's.is_active' 	=> 1,
							'ss.is_active' 	=> 1
						)
					)
			->order_by( 'perms.section_seq asc, perms.subsection_seq asc'  );

		$res = $db->get();
		
		if ( $res->num_rows() > 0 ) 
		{
			return $res->result_array();
		}
		else
		{
			return false;
		}
	}//#get_sections


	public function get_business_types()
	{
		$res = $this->db->select('id,name')
				 ->from('business_type')
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
	
	public function get_mmts($value='0')
	{
		$res = $this->db->select('
									id,
									name,
									sign,
									sign_image,
									sort_order
								')
						->from('app_mmt')
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

	/*
	Arrange Uploaded files to Gallery
	On success--> return moved file name
	else 	  --> false
	*/
	public function arrange_uploads_in_gallery($location,$file)
	{
		if ( !empty($location) && !empty($file) ) 
		{
			//to del//$avatar = trim($this->post('avatar'));
			$from = $this->upload_dir.DIRECTORY_SEPARATOR.$location.DIRECTORY_SEPARATOR.$file;
			$from_path_info = pathinfo($from);
		
			if (file_exists($from))
			{
				$to = $this->gallery_dir.DIRECTORY_SEPARATOR.$location.".".$from_path_info['extension'];
				
				if(rename($from,$to)){
					$this->remove_dir($this->upload_dir.DIRECTORY_SEPARATOR.$location);
					return $location.".".$from_path_info['extension'];
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
		else
		{
			return false;
		}

	}
	/*
	| Remove directory with folders and files
	*/
	protected function remove_dir($dir)
	{
        foreach (scandir($dir) as $item){
            if ($item == "." || $item == "..")
                continue;

            if (is_dir($item)){
                $this->remove_dir($item);
            } else {
                unlink(join(DIRECTORY_SEPARATOR, array($dir, $item)));
            }

        }
        rmdir($dir);
    }

	



	


//-->End of "m_config" Model Class
}
 ?>