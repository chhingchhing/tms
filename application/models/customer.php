<?php
class Customer extends Person
{	
	/*
	Determines if a given person_id is a customer
	*/
	function exists($person_id)
	{
		$this->db->from('customers');	
		$this->db->join('people', 'people.person_id = customers.customer_id');
		$this->db->where('customers.customer_id',$person_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}

	function account_number_exists($account_number)
	{
		$this->db->from('customers');	
		$this->db->where('account_number',$account_number);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	function customer_id_from_account_number($account_number)
	{
		$this->db->from('customers');	
		$this->db->where('account_number',$account_number);
		$query = $this->db->get();
		
		if ($query->num_rows()==1)
		{
			return $query->row()->customer_id;
		}
		
		return false;
	}
	
	/*
	Returns all the customers
	*/
	function get_all($limit=10000, $offset=0,$col='last_name',$order='asc')
	{
		$people=$this->db->dbprefix('people');
		$customers=$this->db->dbprefix('customers');
		$data=$this->db->query("SELECT * 
						FROM ".$people."
						STRAIGHT_JOIN ".$customers." ON 										                       
						".$people.".person_id = ".$customers.".customer_id
						WHERE deleted =0 ORDER BY ".$col." ". $order." 
						LIMIT  ".$offset.",".$limit);		
						
		return $data;
	}
	
	function count_all()
	{
		$this->db->from('customers');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular customer
	*/
	function get_info($customer_id)
	{
		$this->db->from('customers');	
		$this->db->join('people', 'people.person_id = customers.customer_id');
		$this->db->where('customers.customer_id',$customer_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $customer_id is NOT an customer
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from customer table
			$fields = $this->db->list_fields('customers');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			return $person_obj;
		}
	}
	
	/*
	Gets information about multiple customers
	*/
	function get_multiple_info($customer_ids)
	{
		$this->db->from('customers');
		$this->db->join('people', 'people.person_id = customers.customer_id');		
		$this->db->where_in('customers.customer_id',$customer_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
	
	/*
	Inserts or updates a customer
	*/
	function saved(&$person_data, &$customer_data,$customer_id=false) 
	{
		$success=false;
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		if(parent::saved($person_data,$customer_id))
		{ 
			if (!$customer_id or !$this->exists($customer_id))
			{
				$customer_data['customer_id'] = $person_data['person_id'];
				$success = $this->db->insert('customers',$customer_data);
			}
			else
			{
				$this->db->where('customer_id', $customer_id);
				$success = $this->db->update('customers',$customer_data);
			}
			
		} 
		
		$this->db->trans_complete();
		return $success;
	}
	
	/*
	Deletes one customer
	*/
	function delete($customer_id)
	{
		$this->db->where('customer_id', $customer_id);
		return $this->db->update('customers', array('deleted' => 1));
	}
	
	/*
	Deletes a list of customers
	*/
	function delete_list($customer_ids)
	{
		$this->db->where_in('customer_id',$customer_ids);
		return $this->db->update('customers', array('deleted' => 1));
 	}
	
	function check_duplicate($term)
	{
		$this->db->from('customers');
		$this->db->join('people','customers.customer_id=people.person_id');	
		$this->db->where('deleted',0);		
		$query = $this->db->where("CONCAT(first_name,' ',last_name) = ".$this->db->escape($term));
		$query=$this->db->get();
		if($query->num_rows()>0)
		{
			return true;
		}
		
		return false;
	}
 	/*
	Get search suggestions to find customers
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('customers');
		$this->db->join('people','customers.customer_id=people.person_id');
		
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
		
		$this->db->from('customers');
		$this->db->join('people','customers.customer_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("email",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("email", "asc");		
		$by_email = $this->db->get();
		foreach($by_email->result() as $row)
		{
			$suggestions[]=array('label'=> $row->email);		
		}

		$this->db->from('customers');
		$this->db->join('people','customers.customer_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("phone_number",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("phone_number", "asc");		
		$by_phone = $this->db->get();
		foreach($by_phone->result() as $row)
		{
			$suggestions[]=array('label'=> $row->phone_number);		
		}
		
		$this->db->from('customers');
		$this->db->join('people','customers.customer_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("account_number",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("account_number", "asc");		
		$by_account_number = $this->db->get();
		foreach($by_account_number->result() as $row)
		{
			$suggestions[]=array('label'=> $row->account_number);		
		}
		
		$this->db->from('customers');
		$this->db->join('people','customers.customer_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("company_name",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("company_name", "asc");		
		$by_company_name = $this->db->get();
		foreach($by_company_name->result() as $row)
		{
			$suggestions[]=array('label'=> $row->company_name);		
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	
	}
	
	/*
	Get search suggestions to find customers
	*/
	function get_customer_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('customers');
		$this->db->join('people','customers.customer_id=people.person_id');	
		
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
			$suggestions[]=array('value'=> $row->person_id, 'label' => $row->first_name.' '.$row->last_name);		
		}
		
		$this->db->from('customers');
		$this->db->join('people','customers.customer_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("account_number",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("account_number", "asc");		
		$by_account_number = $this->db->get();
		foreach($by_account_number->result() as $row)
		{
			$suggestions[]=array('value'=> $row->customer_id, 'label' => $row->account_number);		
		}

		$this->db->from('customers');
		$this->db->join('people','customers.customer_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("email",$search,$this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("email", "asc");		
		$by_email = $this->db->get();
		foreach($by_email->result() as $row)
		{
			$suggestions[]=array('value'=> $row->person_id, 'label' => $row->email);		
		}
		
		$this->db->from('customers');
		$this->db->join('people','customers.customer_id=people.person_id');	
		$this->db->where('deleted',0);		
		$this->db->like("phone_number",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("phone_number", "asc");		
		$by_phone_number = $this->db->get();
		foreach($by_phone_number->result() as $row)
		{
			$suggestions[]=array('value'=> $row->customer_id, 'label' => $row->phone_number);		
		}
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}

	/*
	Preform a search on customers
	*/
	function search($search, $limit=20,$offset=0,$column='last_name',$orderby='asc')
	{
			
		if ($this->config->item('speed_up_search_queries'))
		{
			$query = "
				select *
				from (
		           	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		           	from ".$this->db->dbprefix('customers')."
		           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		           	where first_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
		           	order by `".$column."` ".$orderby.") union

				 	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		           	from ".$this->db->dbprefix('customers')."
		           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		           	where last_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
		           	order by `".$column."` ".$orderby.") union

					(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		         	from ".$this->db->dbprefix('customers')."
		          	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		          	where email like '".$this->db->escape_like_str($search)."%' and deleted = 0
		          	order by `".$column."` ".$orderby.") union

					(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		        	from ".$this->db->dbprefix('customers')."
		        	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		        	where phone_number like '".$this->db->escape_like_str($search)."%' and deleted = 0
		        	order by `".$column."` ".$orderby.") union

					(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		      		from ".$this->db->dbprefix('customers')."
		      		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		      		where account_number like '".$this->db->escape_like_str($search)."%' and deleted = 0
		      		order by `".$column."` ".$orderby.") union

					(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		    		from ".$this->db->dbprefix('customers')."
		    		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		    		where CONCAT(`first_name`,' ',`last_name`)  like '".$this->db->escape_like_str($search)."%' and deleted = 0
		    		order by `".$column."` ".$orderby.")
				) as search_results
				order by `".$column."` ".$orderby." limit ".(int)$offset.",".$this->db->escape((int)$limit);
				return $this->db->query($query);

		}
		else
		{
			$this->db->from('customers');
			$this->db->join('people','customers.customer_id=people.person_id');		
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			account_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			company_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by($column,$orderby);
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
		           	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		           	from ".$this->db->dbprefix('customers')."
		           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		           	where first_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
		           	order by `last_name` asc limit ".$this->db->escape($limit).") union

				 	(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		           	from ".$this->db->dbprefix('customers')."
		           	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		           	where last_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
		           	order by `last_name` asc limit ".$this->db->escape($limit).") union

					(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		         	from ".$this->db->dbprefix('customers')."
		          	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		          	where email like '".$this->db->escape_like_str($search)."%' and deleted = 0
		          	order by `last_name` asc limit ".$this->db->escape($limit).") union

					(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		        	from ".$this->db->dbprefix('customers')."
		        	join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		        	where phone_number like '".$this->db->escape_like_str($search)."%' and deleted = 0
		        	order by `last_name` asc limit ".$this->db->escape($limit).") union

					(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		      		from ".$this->db->dbprefix('customers')."
		      		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		      		where account_number like '".$this->db->escape_like_str($search)."%' and deleted = 0
		      		order by `last_name` asc limit ".$this->db->escape($limit).") union

					(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('customers').".account_number
					, ".$this->db->dbprefix('customers').".taxable, ".$this->db->dbprefix('customers').".deleted
		    		from ".$this->db->dbprefix('customers')."
		    		join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('customers').".customer_id = ".$this->db->dbprefix('people').".person_id
		    		where CONCAT(`first_name`,' ',`last_name`)  like '".$this->db->escape_like_str($search)."%' and deleted = 0
		    		order by `last_name` asc limit ".$this->db->escape($limit).")
				) as search_results
				order by `last_name` asc limit ".$this->db->escape($limit);
				$result=$this->db->query($query);
			return $result->num_rows();
		}
		else
		{
			$this->db->from('customers');
			$this->db->join('people','customers.customer_id=people.person_id');		
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			email LIKE '%".$this->db->escape_like_str($search)."%' or 
			phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			account_number LIKE '%".$this->db->escape_like_str($search)."%' or 
			company_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");		
			$this->db->order_by("last_name", "asc");
			$this->db->limit($limit);
			$result=$this->db->get();				
			return $result->num_rows();		
		}

	}
	
	
	function cleanup()
	{
		$customer_data = array('account_number' => null);
		$this->db->where('deleted', 1);
		return $this->db->update('customers',$customer_data);
	}

}
?>
