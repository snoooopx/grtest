<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php";
/**
* Excel Class For using PHPExcel Library
*/
class Excel extends PHPExcel
{
	public function __construct()
	{
		parent::__construct();
	}
}