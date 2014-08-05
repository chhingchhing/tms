<?php
require_once ("secure_area.php");
// class No_Access extends CI_Controller 
class No_Access extends Secure_area 
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index($module_id='')
	{
		$data['allowed_modules'] = $this->check_module_accessable();
		$data['module_name']=$this->Module->get_module_name($module_id);
		$this->load->view('no_access',$data);
	}
}
?>