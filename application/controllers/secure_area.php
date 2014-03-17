<?php
class Secure_area extends CI_Controller 
{
	var $module_id;
	
	/*
	Controllers that are considered secure extend Secure_area, optionally a $module_id can
	be set to also check if a user can access a particular module in the system.
	*/
	function __construct($module_id=null)
	{
		parent::__construct();
		$this->module_id = $module_id;	
		$this->load->model('Employee');
		if(!$this->Employee->is_logged_in())
		{
			redirect('login');
		}
		
		if(!$this->Employee->has_module_permission($this->module_id,$this->Employee->get_logged_in_employee_info()->employee_id))
		{
			redirect('no_access/'.$this->module_id);
		}
		
		//load up global data
		$logged_in_employee_info=$this->Employee->get_logged_in_employee_info();
		$data['allowed_offices']=$this->Module->get_allowed_offices($logged_in_employee_info->employee_id);//get officle allowed
	
		$data['user_info']=$logged_in_employee_info;
		$this->load->vars($data);
	}
	
	function check_action_permission($action_id)
	{
		if (!$this->Employee->has_module_action_permission($this->module_id, $action_id, $this->Employee->get_logged_in_employee_info()->employee_id))
		{
			redirect('no_access/'.$this->module_id);
		}
	}

	// Check the module that allow for each user login
    public function check_module_accessable(){
        $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        $office = substr($this->uri->segment(3), -1);
        return $this->Module->get_allowed_modules($office, $logged_in_employee_info->employee_id); //get officle allowed
    }

}

?>