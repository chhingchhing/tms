<?php
class Employee extends Person
{
	/*
	Determines if a given person_id is an employee
	*/
	function exists($employee_id)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.employee_id');
		$this->db->where('employees.employee_id',$employee_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
		
	function employee_username_exists($username)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.employee_id');
		$this->db->where('employees.username',$username);
		$query = $this->db->get();
		
		
		if($query->num_rows()==1)
		{
			return $query->row()->username;
		}
	}	
	
	/*
	Returns all the employees
	*/
	function get_all($limit=10000, $offset=0,$col='last_name',$order='asc')
	{	
		$employees=$this->db->dbprefix('employees');
		$people=$this->db->dbprefix('people');
		$data=$this->db->query("SELECT * 
						FROM ".$people."
						STRAIGHT_JOIN ".$employees." ON 										                       
						".$people.".person_id = ".$employees.".employee_id
						WHERE deleted =0 ORDER BY ".$col." ". $order." 
						LIMIT  ".$offset.",".$limit);		
						
		return $data;
	}
	
	function count_all()
	{
		$this->db->from('employees');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular employee
	*/
	function get_info($employee_id)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.employee_id');
		$this->db->where('employees.employee_id',$employee_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $employee_id is NOT an employee
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from employee table
			$fields = $this->db->list_fields('employees');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}
	
	/*
	Gets information about multiple employees
	*/
	function get_multiple_info($employee_ids)
	{
		$this->db->from('employees');
		$this->db->join('people', 'people.person_id = employees.employee_id');		
		$this->db->where_in('employees.employee_id',$employee_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
        
	/*
	Inserts or updates an employee
	*/
	function save(&$person_data, &$employee_data, $office_data, &$permission_data, &$permission_action_data, $employee_id = false)
	{
		$success=false;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
			
		if(parent::saved($person_data,$employee_id))
		{
			if (!$employee_id or !$this->exists($employee_id))
			{
				$employee_data['employee_id'] = $employee_id = $person_data['person_id'];
				$success = $this->db->insert('employees',$employee_data);
                                
			}
			else
			{
				$this->db->where('employee_id', $employee_id);
				$success = $this->db->update('employees',$employee_data);		
			}

			//We have either inserted or updated a new employee, now lets set permissions. 
			if($success)
			{
				//First lets clear out any permissions the employee currently has.
				$success=$this->db->delete('permissions', array('person_id' => $employee_id));
				//Now insert the new permissions
				if($success)
				{
					foreach($office_data as $allowed_office) {
						foreach($permission_data as $allowed_module)
						{
							// if ($allowed_module != "sales") {
								$success = $this->db->insert('permissions',
								array(
								'office_id' => $allowed_office,
								'module_id' => $allowed_module,
								'person_id' => $employee_id));
							// }
						}
					}
				}
				
				//First lets clear out any permissions actions the employee currently has.
				$success=$this->db->delete('permissions_actions', array('person_id' => $employee_id));
				
				//Now insert the new permissions actions
				if($success)
				{
					foreach($permission_action_data as $permission_action)
					{
						list($module, $action) = explode('|', $permission_action);
						$success = $this->db->insert('permissions_actions',
						array(
						'module_id'=>$module,
						'action_id'=>$action,
						'person_id'=>$employee_id));
					}
				}

				//First lets clear out any permissions actions the employee currently has.
				$success=$this->db->delete('permissions_office', array('person_id' => $employee_id));
				
				//Now insert the new permissions actions
				if($success)
				{
					foreach($office_data as $office_id)
					{
						$success = $this->db->insert('permissions_office',
						array(
						'office_id'=>$office_id,
						'person_id'=>$employee_id));
					}
				}

			}
			
		}
		
		$this->db->trans_complete();		
		return $success;
	}
	
	/*
	Deletes one employee
	*/
	function delete($employee_id)
	{
		$success=false;
		
		//Don't let employee delete their self
		if($employee_id==$this->get_logged_in_employee_info()->employee_id)
			return false;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		//Delete permissions
		if($this->db->delete('permissions', array('person_id' => $employee_id)) && $this->db->delete('permissions_actions', array('person_id' => $employee_id)))
		{	
			$this->db->where('employee_id', $employee_id);
			$success = $this->db->update('employees', array('deleted' => 1));
		}
		$this->db->trans_complete();		
		return $success;
	}
	
	/*
	Deletes a list of employees
	*/
	function delete_list($employee_ids)
	{
		$success=false;
		
		//Don't let employee delete their self
		if(in_array($this->get_logged_in_employee_info()->employee_id,$employee_ids))
			return false;

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->where_in('employee_id',$employee_ids);
		//Delete permissions
		if ($this->db->delete('permissions'))
		{
			//delete from employee table
			$this->db->where_in('employee_id',$employee_ids);
			$success = $this->db->update('employees', array('deleted' => 1));
		}
		$this->db->trans_complete();		
		return $success;
 	}
	
		
	function check_duplicate($term)
	{
		$this->db->from('employees');
		$this->db->join('people','employees.employee_id=people.person_id');	
		$this->db->where('deleted',0);		
		$query = $this->db->where("CONCAT(first_name,' ',last_name) = ".$this->db->escape($term));
		$query=$this->db->get();
		
		if($query->num_rows()>0)
		{
			return true;
		}
	}
	/*
	Get search suggestions to find employees
	*/
	function get_search_suggestions($search,$limit=5)
	{
		$suggestions = array();
		
		$this->db->from('employees');
		$this->db->join('people','employees.employee_id=people.person_id');
		
		if ($this->config->item('speed_up_search_queries'))
		{
			$this->db->where("(first_name LIKE '".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '".$this->db->escape_like_str($search)."%') and deleted=0");
		}
		else
		{
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
		}
		
		$this->db->order_by("last_name", "asc");		
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=array('label'=> $row->first_name.' '.$row->last_name);		
		}
		
		$this->db->from('employees');
		$this->db->join('people','employees.employee_id=people.person_id');
		$this->db->where('deleted', 0);
		$this->db->like("email",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("email", "asc");		
		$by_email = $this->db->get();
		foreach($by_email->result() as $row)
		{
			$suggestions[]=array('label'=> $row->email);		
		}
		
		$this->db->from('employees');
		$this->db->join('people','employees.employee_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("username",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("username", "asc");		
		$by_username = $this->db->get();
		foreach($by_username->result() as $row)
		{
			$suggestions[]=array('label'=> $row->username);		
		}


		$this->db->from('employees');
		$this->db->join('people','employees.employee_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("phone_number",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("phone_number", "asc");		
		$by_phone = $this->db->get();
		foreach($by_phone->result() as $row)
		{
			$suggestions[]=array('label'=> $row->phone_number);		
		}
		
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	
	}
	
	
	
	/*
	Preform a search on employees
	*/
	function search($search, $limit=20,$offset=0,$column='last_name',$orderby='asc')
	{
		if ($this->config->item('speed_up_search_queries'))
		{

			$query = "
				select *
			from (
           	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
           	from ".$this->db->dbprefix('employees')."
           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
           	where first_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
           	order by `".$column."` ".$orderby.") union

		 	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
           	from ".$this->db->dbprefix('employees')."
           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
           	where last_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
           	order by `".$column."` ".$orderby.") union

			(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
         	from ".$this->db->dbprefix('employees')."
          	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
          	where email like '".$this->db->escape_like_str($search)."%' and deleted = 0
          	order by `".$column."` ".$orderby.") union

			(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
        	from ".$this->db->dbprefix('employees')."
        	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
        	where phone_number like '".$this->db->escape_like_str($search)."%' and deleted = 0
        	order by `".$column."` ".$orderby.") union

			(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
      		from ".$this->db->dbprefix('employees')."
      		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
      		where username like '".$this->db->escape_like_str($search)."%' and deleted = 0
      		order by `".$column."` ".$orderby.") union

			(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
    		from ".$this->db->dbprefix('employees')."
    		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
    		where CONCAT(`first_name`,' ',`last_name`)  like '".$this->db->escape_like_str($search)."%' and deleted = 0
    		order by `".$column."` ".$orderby.")
			) as search_results
			order by `".$column."` ".$orderby." limit ".$this->db->escape((int)$offset).', '.$this->db->escape((int)$limit);

			return $this->db->query($query);
		}
		else
		{
			$this->db->from('employees');
			$this->db->join('people','employees.employee_id=people.person_id');		
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			username LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by($column, $orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();
		}
	}
	
	function search_count_all($search, $limit=10000)
	{
		if ($this->config->item('speed_up_search_queries'))
		{

			$query = "
				select *
			from (
           	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
           	from ".$this->db->dbprefix('employees')."
           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
           	where first_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
           	order by `last_name` asc limit ".$this->db->escape($limit).") union

		 	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
           	from ".$this->db->dbprefix('employees')."
           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
           	where last_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
           	order by `last_name` asc limit ".$this->db->escape($limit).") union

			(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
         	from ".$this->db->dbprefix('employees')."
          	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
          	where email like '".$this->db->escape_like_str($search)."%' and deleted = 0
          	order by `last_name` asc limit ".$this->db->escape($limit).") union

			(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
        	from ".$this->db->dbprefix('employees')."
        	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
        	where phone_number like '".$this->db->escape_like_str($search)."%' and deleted = 0
        	order by `last_name` asc limit ".$this->db->escape($limit).") union

			(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
      		from ".$this->db->dbprefix('employees')."
      		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
      		where username like '".$this->db->escape_like_str($search)."%' and deleted = 0
      		order by `last_name` asc limit ".$this->db->escape($limit).") union

			(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
    		from ".$this->db->dbprefix('employees')."
    		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".employee_id = ".$this->db->dbprefix('people').".person_id
    		where CONCAT(`first_name`,' ',`last_name`)  like '".$this->db->escape_like_str($search)."%' and deleted = 0
    		order by `last_name` asc limit ".$this->db->escape($limit).")
			) as search_results
			order by `last_name` asc limit ".$this->db->escape($limit);

			$result=$this->db->query($query);
			return $result->num_rows();
		}
		else
		{
			$this->db->from('employees');
			$this->db->join('people','employees.employee_id=people.person_id');		
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			username LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by("last_name", "asc");
			$result=$this->db->get();				
			return $result->num_rows();
		}
	}
	
	/*
	Attempts to login employee and set session. Returns boolean based on outcome.
	*/
	function login($username, $password)
	{
		$query = $this->db->get_where('employees', array('username' => $username,'password'=>md5($password), 'deleted'=>0), 1);
		if ($query->num_rows() ==1)
		{
			$row=$query->row();
			$this->session->set_userdata('person_id', $row->employee_id);
			// $this->session->set_userdata('employee_id', $row->employee_id);
			return true;
		}
		return false;
	}
	
	/*
	Logs out a user by destorying all session data and redirect to login
	*/
	function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
	
	/*
	Determins if a employee is logged in
	*/
	function is_logged_in()
	{
		return $this->session->userdata('person_id')!=false;
	}
	
	/*
	Gets information about the currently logged in employee.
	*/
	function get_logged_in_employee_info()
	{
		if($this->is_logged_in())
		{
			return $this->get_info($this->session->userdata('person_id'));
		}
		
		return false;
	}
	
	function authentication_check($password)
	{
		$pd=$this->session->userdata('person_id');
	   $pass=md5($password);
		$query = $this->db->get_where('employees', array('employee_id' => $pd,'password'=>$pass), 1);
		return $query->num_rows() == 1;
	}
	
	/*
	Determins whether the employee specified employee has access the specific module.
	*/
	function has_module_permission($module_id,$person_id)
	{
		//if no module_id is null, allow access
		if($module_id==null)
		{
			return true;
		}
		$query = $this->db->get_where('permissions', array('person_id' => $person_id,'module_id'=>$module_id), 1);
		return $query->num_rows() == 1;
	}
	
	function has_module_action_permission($module_id, $action_id, $person_id) //sale, edit_sale, 1
	{
		//if no module_id is null, allow access
		if($module_id==null)
		{
			return true;
		}
		
		$query = $this->db->get_where('permissions_actions', array('person_id' => $person_id,'module_id'=>$module_id,'action_id'=>$action_id), 1);
		return $query->num_rows() == 1;
	}
	
	function get_employee_by_username_or_email($username_or_email)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.employee_id');
		$this->db->where('username',$username_or_email);
		$this->db->or_where('email',$username_or_email);
		$query = $this->db->get();
		
		if ($query->num_rows() == 1)
		{
			return $query->row();
		}
		
		return false;
	}
	
	function update_employee_password($employee_id, $password)
	{
		$employee_data = array('password' => $password);
		$this->db->where('employee_id', $employee_id);
		$success = $this->db->update('employees',$employee_data);
		
		return $success;
	}
		
	function cleanup()
	{
		$employee_data = array('username' => null);
		$this->db->where('deleted', 1);
		return $this->db->update('employees',$employee_data);
	}




	/*
	Gets information about a particular employee
	*/
	function get_office($employee_id)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.employee_id');
		$this->db->where('employees.employee_id',$employee_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $employee_id is NOT an employee
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from employee table
			$fields = $this->db->list_fields('employees');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}

	/*
	Determins whether the employee specified employee has access the specific office.
	*/
	function has_office_permission($office_id,$person_id)
	{
		//if no module_id is null, allow access
		if($office_id==null)
		{
			return true;
		}
		$query = $this->db->get_where('permissions_office', array('person_id' => $person_id,'office_id'=>$office_id), 1);
		return $query->num_rows() == 1;
	}

	// For provide the permission to see the result of summary report
	// Profit, total
	function has_owner_action_permission($position, $person_id) //sale, edit_sale, 1
	{
		$position_id = $this->employee_position_id($person_id);
		
		$query = $this->db->get_where('positions', array('position_id' => $position_id), 1);
		if($query->num_rows()==1)
		{
			if ($query->row()->position_name == $position) {
				return true;
			}
			return false;
		}
	}

	// Get position name of each user
	function employee_position_id($person_id)
	{
		$this->db->from('employees');	
		$this->db->join('people', 'people.person_id = employees.employee_id');
		$this->db->where('employees.employee_id',$person_id);
		$query = $this->db->get();
		
		
		if($query->num_rows()==1)
		{
			return $query->row()->position_id;
		}
	}	

	/*
	Get search suggestions to find employees
	*/
	function search_suggestions($search,$limit=5)
	{
		$suggestions = array();
		
		$this->db->from('employees');
		$this->db->join('people','employees.employee_id=people.person_id');
		
		if ($this->config->item('speed_up_search_queries'))
		{
			$this->db->where("(first_name LIKE '".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '".$this->db->escape_like_str($search)."%') and deleted=0");
		}
		else
		{
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
		}
		
		$this->db->order_by("last_name", "asc");		
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=array('value' => $row->employee_id, 'label'=> $row->first_name.' '.$row->last_name);		
		}
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	
	}
	
}
?>
