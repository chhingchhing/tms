<?php
class Module extends CI_Model 
{
    function __construct()
    {
        parent::__construct();
    }
	
	function get_module_name($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);
		
		if ($query->num_rows() ==1)
		{
			$row = $query->row();
			return lang($row->name_lang_key);
		}
		
		return lang('error_unknown');
	}
	
	function get_module_desc($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);
		if ($query->num_rows() ==1)
		{
			$row = $query->row();
			return lang($row->desc_lang_key);
		}
	
		return lang('error_unknown');	
	}
	
	function get_all_modules()
	{
		$this->db->from('modules');
		$this->db->order_by("sort", "asc");
		return $this->db->get();		
	}

	function get_all_offices()
	{
		$this->db->from('offices');
		$this->db->order_by("office_name", "asc");
		return $this->db->get();		
	}

	// Get allowed office
	function get_allowed_offices($person_id)
	{
		$this->db->from('offices');
		$this->db->join('permissions_office','permissions_office.office_id=offices.office_id');
		$this->db->where("permissions_office.person_id",$person_id);
		// $this->db->order_by("sort", "asc");
		return $this->db->get();		
	}

	// Get allowed modules
	function get_allowed_modules($office_id, $person_id)
	{
		$query = $this->db
			->from('permissions')
			->join('offices','offices.office_id=permissions.office_id')
			->join('modules', 'modules.module_id = permissions.module_id')
			->join('employees', 'employees.employee_id = permissions.person_id')
			->where("permissions.office_id",$office_id)
			->where("permissions.person_id", $person_id)
			->get();	
		return $query;
	}

	/*
	Returns all the positions
	*/
	function get_all_positions($limit=10000, $offset=0,$col='position_name',$order='asc')
	{
		$data = $this->db
			->order_by($col, $order)
			->limit($limit, $offset)
			->get("positions");
		return $data;
	}


}
?>
