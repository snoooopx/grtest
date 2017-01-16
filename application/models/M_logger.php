<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* m_logger Model Class Logging Operations
*/
class M_logger extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}


	public function loggish( $text, $type, $user=false )
	{
		$file = "audit.txt";
       	date_default_timezone_set("Asia/Yerevan");
       	$data_txt = "[". date('Y/m/d - H:i:s') . "][" . $user . "][". $type ."]-->" . $text .PHP_EOL;

        $fh = fopen($file, "a+") or die("Could not open log file.");
        fwrite($fh, $data_txt ) or die("Could not write file!");
        fclose($fh);
	}








//-->End of m_logger Model Class
}
 ?>