<?php
class Massage_kit extends CI_Model
{
	/*
	Determines if a given item_id is an item kit
	*/
	// Chhingchhing *
	function exists($item_kit_id)
	{
		// echo $item_kit_id;
		$query = $this->db->where("category", "tickets")
				->where('item_kit_id',$item_kit_id)
				->get("item_kits");

		return ($query->num_rows() == 1);
	}


	/*
	Get an item_kit_id given an item kit number
	*/
	function get_item_kit_id($item_kit_id)
	{
		$query = $this->db->where('item_kit_id',$item_kit_id)
				->where("category", "tickets")
				->get("item_kits");

		if($query->num_rows()==1)
		{
			return $query->row()->item_kit_id;
		}

		return false;
	}

	/*
	Gets information about a particular item kit
	*/
	function get_info($item_kit_id)
	{
		$query = $this->db->where('item_kit_id',$item_kit_id)
				->where("category", "tickets")
				->get("item_kits");
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_kit_id is NOT an item kit
			$item_obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('item_kits');

			foreach ($fields as $field)
			{
				$item_obj->$field='';
			}

			return $item_obj;
		}
	}

	/*
	Gets item kit items for a particular item kit
	Gets item kit items ticket 
	get_info() in item_kit_item
	*/
	function get_info_item_kits($item_kit_id)
	{
		$this->db->from('item_kits_tickets');
		$this->db->where('item_kit_id',$item_kit_id);
		//return an array of item kit items for an item
		return $this->db->get()->result();
	}

	/**
	 * Get kits with item
	 * @param type $ite_id
	 * @return type
	 * Get kits with item for item ticket
	 */
	function get_kits_have_item($item_id)
	{
		// echo $item_id.'Item_kit_ticket';
	    $this->db->from('item_kits_tickets');
	    $this->db->where('ticket_id',$item_id);	 
	    // var_dump($this->db->get()->result_array())   ;
	    return $this->db->get()->result_array();
	}

	//Search tickets item as kit or package
	function get_item_kit_search_suggestions($search, $limit=25)
	{
		$suggestions = array();

		$by_name = $this->db->like('name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
					->where('deleted',0)
					->where('category',"tickets")
					->order_by("name", "asc")
					->get("item_kits");
		foreach($by_name->result() as $row)
		{
			$suggestions[]=array('value' => 'KIT '.$row->item_kit_id, 'label' => $row->name);
		}
		
		$by_item_kit_number = $this->db->like('item_kit_number', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
					->where('deleted',0)
					->where('category',"tickets")
					->order_by("item_kit_number", "asc")
					->get("item_kits");
		foreach($by_item_kit_number->result() as $row)
		{
			$suggestions[]=array('value' => 'KIT '.$row->item_kit_id, 'label' => $row->item_kit_number);
		}
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
		
	}


}