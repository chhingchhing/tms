<?php
require_once ("secure_area.php");
class Dashboard extends Secure_area 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		$this->load->view("main");
	}

	function world(){
		$logged_in_employee_info=$this->Employee->get_logged_in_employee_info();
		$office = substr($this->uri->segment(3), -1);
		$this->session->set_userdata("office_number", $this->uri->segment(3));
		$data['allowed_modules']=$this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id);//get officle allowed
		$this->load->view('worlds/home',$data);
	}
	
	function logout()
	{
		$this->Employee->logout();
	}
}
?>