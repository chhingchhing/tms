<?php
class Supplier extends Person
{	
	/*
	Determines if a given person_id is a customer
	*/
	function exists($person_id)
	{
		$this->db->from('suppliers');	
		$this->db->join('people', 'people.person_id = suppliers.supplier_id');
		$this->db->where('suppliers.supplier_id',$person_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	/*
	Returns all the suppliers
	*/
	function get_all($limit=10000, $offset=0,$col='company_name',$order='asc')
	{
		$people=$this->db->dbprefix('people');
		$suppliers=$this->db->dbprefix('suppliers');
		$data=$this->db->query("SELECT * 
						FROM ".$people."
						STRAIGHT_JOIN ".$suppliers." ON 										                       
						".$people.".person_id = ".$suppliers.".supplier_id
						WHERE deleted =0 ORDER BY ".$col." ".$order." 
						LIMIT  ".$offset.",".$limit);
		return $data;
	}
	
	/*********************Original**********************/
	function account_number_exists_editing($account_number)
	{
		$this->db->from('suppliers');	
		$this->db->where('account_number',$account_number);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
/*-=================================-*/

	function account_number_exists($account_number)
	{
		$this->db->from('suppliers');	
		$this->db->where('account_number',$account_number);
		$query = $this->db->get();
		
		// return ($query->num_rows()==1);
		return $query;
	}

	function count_all()
	{
		$this->db->from('suppliers');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular supplier
	*/
	function get_info($supplier_id)
	{
		$this->db->from('suppliers');	
		$this->db->join('people', 'people.person_id = suppliers.supplier_id');
		$this->db->where('suppliers.supplier_id',$supplier_id);
		$query = $this->db->get();
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $supplier_id is NOT an supplier
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from supplier table
			$fields = $this->db->list_fields('suppliers');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}
	
	/*
	Gets information about multiple suppliers
	*/
	function get_multiple_info($suppliers_ids)
	{
		$this->db->from('suppliers');
		$this->db->join('people', 'people.person_id = suppliers.supplier_id');		
		$this->db->where_in('suppliers.supplier_id',$suppliers_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
	
	/*
	Inserts or updates a suppliers
	*/
	function save(&$person_data, &$supplier_data,$supplier_id=false)
	{
		$success=false;
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		$result = parent::saved($person_data,$supplier_id);
		if($result)
		{
			if (!$supplier_id or !$this->exists($supplier_id))
			{
				// $supplier_data['person_id'] = $person_data['person_id'];
				$supplier_data['supplier_id'] = $person_data['person_id'];
				$success = $this->db->insert('suppliers',$supplier_data);
			}
			else
			{
				$this->db->where('supplier_id', $supplier_id);
				$success = $this->db->update('suppliers',$supplier_data);
			}
			
		}
		
		$this->db->trans_complete();
		return $success;
	}
	
	/*
	Deletes one supplier
	*/
	function delete($supplier_id)
	{
		$this->db->where('person_id', $supplier_id);
		return $this->db->update('suppliers', array('deleted' => 1));
	}
	
	/*
	Deletes a list of suppliers
	*/
	function delete_list($supplier_ids)
	{
		$this->db->where_in('supplier_id',$supplier_ids);
		return $this->db->update('suppliers', array('deleted' => 1));
 	}
 	
 	/*
	Get search suggestions to find suppliers
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('suppliers');
		$this->db->join('people','suppliers.supplier_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("company_name",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("company_name", "asc");		
		$by_company_name = $this->db->get();
		foreach($by_company_name->result() as $row)
		{
			$suggestions[]=array('label' => $row->company_name);		
		}

		
		$this->db->from('suppliers');
		$this->db->join('people','suppliers.supplier_id=people.person_id');	
		
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
			$suggestions[]=array('label' => $row->first_name.' '.$row->last_name);		
		}
		
		$this->db->from('suppliers');
		$this->db->join('people','suppliers.supplier_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("email",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("email", "asc");		
		$by_email = $this->db->get();
		foreach($by_email->result() as $row)
		{
			$suggestions[]=array('label' => $row->email);		
		}

		$this->db->from('suppliers');
		$this->db->join('people','suppliers.supplier_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("phone_number",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("phone_number", "asc");		
		$by_phone = $this->db->get();
		foreach($by_phone->result() as $row)
		{
			$suggestions[]=array('label' => $row->phone_number);		
		}
		
		$this->db->from('suppliers');
		$this->db->join('people','suppliers.supplier_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("account_number",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("account_number", "asc");		
		$by_account_number = $this->db->get();
		foreach($by_account_number->result() as $row)
		{
			$suggestions[]=array('label' => $row->account_number);		
		}
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	
	}
	
	/*
	Get search suggestions to find suppliers
	*/
	function get_suppliers_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('suppliers');
		$this->db->join('people','suppliers.supplier_id=people.person_id');	
		$this->db->where('deleted', 0);
		$this->db->like("company_name",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("company_name", "asc");		
		$by_company_name = $this->db->get();
		foreach($by_company_name->result() as $row)
		{
			$suggestions[]=array('value' => $row->person_id, 'label' => $row->company_name);	
		}


		$this->db->from('suppliers');
		$this->db->join('people','suppliers.supplier_id=people.person_id');	
		
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
			$suggestions[]=array('value' => $row->person_id, 'label' => $row->first_name.' '.$row->last_name);	
		}
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}
	/*
	Perform a search on suppliers
	*/
	function search($search, $limit=20,$offset=0,$column='company_name',$orderby='asc')
	{
		if ($this->config->item('speed_up_search_queries'))
		{
			$query = "
			select *
			from (
	           	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	           	from ".$this->db->dbprefix('suppliers')."
	           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	           	where first_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
	           	order by `".$column."` ".$orderby." limit ".$this->db->escape($limit).") union

			 	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	           	from ".$this->db->dbprefix('suppliers')."
	           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	           	where last_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
	           	order by `".$column."` ".$orderby." limit ".$this->db->escape($limit).") union

				(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	         	from ".$this->db->dbprefix('suppliers')."
	          	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	          	where email like '".$this->db->escape_like_str($search)."%' and deleted = 0
	          	order by `".$column."` ".$orderby." limit ".$this->db->escape($limit).") union

				(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	        	from ".$this->db->dbprefix('suppliers')."
	        	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	        	where phone_number like '".$this->db->escape_like_str($search)."%' and deleted = 0
	        	order by `".$column."` ".$orderby." limit ".$this->db->escape($limit).") union

				(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	      		from ".$this->db->dbprefix('suppliers')."
	      		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	      		where company_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
	      		order by `".$column."` ".$orderby." limit ".$this->db->escape($limit).") union

				(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	    		from ".$this->db->dbprefix('suppliers')."
	    		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	    		where CONCAT(`first_name`,' ',`last_name`)  like '".$this->db->escape_like_str($search)."%' and deleted = 0
	    		order by `".$column."` ".$orderby." limit ".$this->db->escape($limit).") union

				(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	    		from ".$this->db->dbprefix('suppliers')."
	    		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	    		where account_number like '".$this->db->escape_like_str($search)."%' and deleted = 0
	    		order by `".$column."` ".$orderby." limit ".$this->db->escape($limit).")
			) as search_results
			order by `".$column."` ".$orderby." limit ".$this->db->escape((int)$offset).', '.$this->db->escape((int)$limit);

			return $this->db->query($query);
		}
		else
		{
			$this->db->from('suppliers');
	 		$this->db->join('people','suppliers.supplier_id=people.person_id');
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			company_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			account_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by($column, $orderby);
	 		$this->db->limit($limit);
	 		$this->db->offset($offset);
	 		return $this->db->get();	
	 		
		}	
	}
	
	function search_count_all($search, $limit=10000,$column='company_name',$orderby='asc')
	{
		if ($this->config->item('speed_up_search_queries'))
		{
			$query = "
			select *
			from (
	           	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	           	from ".$this->db->dbprefix('suppliers')."
	           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	           	where first_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
	           	order by `last_name` asc limit ".$this->db->escape($limit).") union

			 	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	           	from ".$this->db->dbprefix('suppliers')."
	           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	           	where last_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
	           	order by `last_name` asc limit ".$this->db->escape($limit).") union

				(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	         	from ".$this->db->dbprefix('suppliers')."
	          	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	          	where email like '".$this->db->escape_like_str($search)."%' and deleted = 0
	          	order by `last_name` asc limit ".$this->db->escape($limit).") union

				(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	        	from ".$this->db->dbprefix('suppliers')."
	        	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	        	where phone_number like '".$this->db->escape_like_str($search)."%' and deleted = 0
	        	order by `last_name` asc limit ".$this->db->escape($limit).") union

				(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	      		from ".$this->db->dbprefix('suppliers')."
	      		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	      		where company_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
	      		order by `last_name` asc limit ".$this->db->escape($limit).") union

				(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	    		from ".$this->db->dbprefix('suppliers')."
	    		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	    		where CONCAT(`first_name`,' ',`last_name`)  like '".$this->db->escape_like_str($search)."%' and deleted = 0
	    		order by `last_name` asc limit ".$this->db->escape($limit).") union

				(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('suppliers').".company_name, ".$this->db->dbprefix('suppliers').".deleted, ".$this->db->dbprefix('suppliers').".account_number
	    		from ".$this->db->dbprefix('suppliers')."
	    		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('people').".person_id
	    		where account_number like '".$this->db->escape_like_str($search)."%' and deleted = 0
	    		order by `last_name` asc limit ".$this->db->escape($limit).")
			) as search_results
			order by `last_name` asc limit ".$this->db->escape($limit);

			$result=$this->db->query($query);
			return $result->num_rows();		}
		else
		{
			$this->db->from('suppliers');
	 		$this->db->join('people','suppliers.supplier_id=people.person_id');
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			company_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			account_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by("last_name", "asc");
	 		$this->db->limit($limit);
		$result=$this->db->get();				
		return $result->num_rows();		 		
		}	
	}
	
	function find_supplier_id($search)
	{
		if ($search)
		{
			$this->db->select("suppliers.supplier_id");
			$this->db->from('suppliers');
			$this->db->join('people','suppliers.supplier_id=people.person_id');
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '".$this->db->escape_like_str($search)."%' or
			company_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by("last_name", "asc");
			$query = $this->db->get();
		
			if ($query->num_rows() > 0)
			{
				return $query->row()->person_id;
			}
		}
		
		return null;
	}

	// Get supplier for select dropdown
	function get_suppliers() {
		$query = $this->db->select('*')
                ->get('suppliers');
        $option[] = "--Select Supplier--";
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $suppliers) {
                $option[$suppliers->supplier_id] = $suppliers->company_name;
            }
        }
        return $option;
	}

}
?>
