<?php
class Giftcard extends CI_Model
{
	/*
	Determines if a given giftcard_id is an giftcard
	*/
	function exists( $giftcard_id )
	{
		$this->db->from('giftcards');
		$this->db->where('giftcard_id',$giftcard_id);
		$this->db->where('deleted',0);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

	/*
	Returns all the giftcards
	*/
	function get_all($limit=10000,$offset=0,$col='giftcard_number',$order='asc')
	{
		$this->db->from('giftcards');
		$this->db->where('deleted',0);
		$this->db->order_by($col, $order);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}
	
	function count_all()
	{
		$this->db->from('giftcards');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}

	/*
	Gets information about a particular giftcard
	*/
	function get_info($giftcard_id)
	{
		$this->db->from('giftcards');
		$this->db->where('giftcard_id',$giftcard_id);
		$this->db->where('deleted',0);
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $giftcard_id is NOT an giftcard
			$giftcard_obj=new stdClass();

			//Get all the fields from giftcards table
			$fields = $this->db->list_fields('giftcards');

			foreach ($fields as $field)
			{
				$giftcard_obj->$field='';
			}

			return $giftcard_obj;
		}
	}

	/*
	Get an giftcard id given an giftcard number
	*/
	function get_giftcard_id($giftcard_number,$deleted=false)
	{
		$this->db->from('giftcards');
		$this->db->where('giftcard_number',$giftcard_number);
		if(!$deleted)
		{
			$this->db->where('deleted',0);
		}
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->giftcard_id;
		}

		return false;
	}

	/*
	Gets information about multiple giftcards
	*/
	function get_multiple_info($giftcard_ids)
	{
		$this->db->from('giftcards');
		$this->db->where_in('giftcard_id',$giftcard_ids);
		$this->db->where('deleted',0);
		$this->db->order_by("giftcard_number", "asc");
		return $this->db->get();
	}

	/*
	Inserts or updates a giftcard
	*/
	function save(&$giftcard_data,$giftcard_id=false)
	{
		if (!$giftcard_id or !$this->exists($giftcard_id))
		{
			if($this->db->insert('giftcards',$giftcard_data))
			{
				$giftcard_data['giftcard_id']=$this->db->insert_id();
				return true;
			}
			return false;
		}

		$this->db->where('giftcard_id', $giftcard_id);
		return $this->db->update('giftcards',$giftcard_data);
	}

	/*
	Updates multiple giftcards at once
	*/
	function update_multiple($giftcard_data,$giftcard_ids)
	{
		$this->db->where_in('giftcard_id',$giftcard_ids);
		return $this->db->update('giftcards',$giftcard_data);
	}

	/*
	Deletes one giftcard
	*/
	function delete($giftcard_id)
	{
		$this->db->where('giftcard_id', $giftcard_id);
		return $this->db->update('giftcards', array('deleted' => 1));
	}
	
	function delete_completely($giftcard_id)
	{
		$this->db->where('giftcard_number', $giftcard_id);
		$this->db->delete('giftcards');
	}
	/*
	Deletes a list of giftcards
	*/
	function delete_list($giftcard_ids)
	{
		$this->db->where_in('giftcard_id',$giftcard_ids);
		return $this->db->update('giftcards', array('deleted' => 1));
 	}

 	/*
	Get search suggestions to find giftcards
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('giftcards');
		$this->db->like('giftcard_number', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->where('deleted',0);
		$this->db->order_by("giftcard_number", "asc");
		$by_number = $this->db->get();
		foreach($by_number->result() as $row)
		{
			$suggestions[]=array('label' => $row->giftcard_number);
		}

		$this->db->from('giftcards');
		$this->db->join('people','giftcards.customer_id=people.person_id');	
		
		if ($this->config->item('speed_up_search_queries'))
		{
			$this->db->where("(first_name LIKE '".$this->db->escape_like_str($search)."%' or 
				last_name LIKE '".$this->db->escape_like_str($search)."%' or 
				CONCAT(`first_name`,' ',`last_name`) LIKE '".$this->db->escape_like_str($search)."%') and ".$this->db->dbprefix('giftcards').".deleted=0");
		}
		else
		{
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
				last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
				CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and ".$this->db->dbprefix('giftcards').".deleted=0");
		}
		$this->db->order_by("last_name", "asc");		
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=array('label'=> $row->first_name.' '.$row->last_name);		
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}

	/*
	Preform a search on giftcards
	*/
	function search($search, $limit=20,$offset=0,$column="giftcard_number",$orderby='asc')
	{
		$this->db->from('giftcards');
		$this->db->join('people','giftcards.customer_id=people.person_id', 'left');	
		
		if ($this->config->item('speed_up_search_queries'))
		{
			$this->db->where("(first_name LIKE '".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '".$this->db->escape_like_str($search)."' or giftcard_number LIKE '".$this->db->escape_like_str($search)."%') and ".$this->db->dbprefix('giftcards').".deleted=0");
					$this->db->order_by($column, $orderby);

		}
		else
		{
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or giftcard_number LIKE '%".$this->db->escape_like_str($search)."%') and ".$this->db->dbprefix('giftcards').".deleted=0");		
		}
		$this->db->order_by($column, $orderby);
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();	
	}
	
	function search_count_all($search)
	{
		$this->db->from('giftcards');
		$this->db->join('people','giftcards.customer_id=people.person_id', 'left');	
		
		if ($this->config->item('speed_up_search_queries'))
		{
			$this->db->where("(first_name LIKE '".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '".$this->db->escape_like_str($search)."' or giftcard_number LIKE '".$this->db->escape_like_str($search)."%') and ".$this->db->dbprefix('giftcards').".deleted=0");
		}
		else
		{
			$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%' or giftcard_number LIKE '%".$this->db->escape_like_str($search)."%') and ".$this->db->dbprefix('giftcards').".deleted=0");		
		}
		
		$this->db->order_by("giftcard_number", "asc");
		
		$result=$this->db->get();	
			return $result->num_rows();
	}
	
	public function get_giftcard_value( $giftcard_number )
	{
		if ( !$this->exists( $this->get_giftcard_id($giftcard_number)))
			return 0;
		
		$this->db->from('giftcards');
		$this->db->where('giftcard_number',$giftcard_number);
		return $this->db->get()->row()->value;
	}
	
	function update_giftcard_value( $giftcard_number, $value )
	{
		$this->db->where('giftcard_number', $giftcard_number);
		$this->db->update('giftcards', array('value' => $value));
	}
}
?>
