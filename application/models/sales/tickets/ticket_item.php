<?php
class ticket_item extends CI_Model
{

	//Search for ticket item for sale
    function get_item_search_suggestions_for_sale($search,$limit=25)
    {
        $suggestions = array();

        $by_name = $this->db->join('destinations','tickets.destinationID = destinations.destinate_id')
        		->join('tickets_types','tickets.ticket_typeID = tickets_types.ticket_type_id')
        		->where('tickets.deleted',0)
        		->like('ticket_name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
                ->or_like('destination_name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
                ->or_like('ticket_type_name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
        		->order_by("ticket_name", "asc")
        		->get("tickets");
        foreach($by_name->result() as $row)
        {
            $suggestions[]=array('value' => $row->ticket_id, 'label' => $row->ticket_name);
        }

        //only return $limit suggestions
        if(count($suggestions > $limit))
        {
            $suggestions = array_slice($suggestions, 0,$limit);
        }
        return $suggestions;

    }


    /*
	Gets information about a particular item
	*/
	function get_info($item_id)
	{
		$query = $this->db->where('ticket_id',$item_id)->get("tickets");
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('tickets');

			foreach ($fields as $field)
			{
				$item_obj->$field='';
			}
            // echo 'return';
			return $item_obj;
		}
	}

    /*
    Determines if a given item_id is an item
    */
    function exists($ticket_id)
    {
        $query = $this->db->where('ticket_id',$ticket_id)->get("tickets");

        return ($query->num_rows()==1);
    }

    /*
    Get an item id given an item number
    */
    // Get item number of ticket
    function get_item_id($code_ticket)
    {
        $query = $this->db->where('code_ticket',$code_ticket)->get("tickets");

        if($query->num_rows()==1)
        {
            return $query->row()->ticket_id;
        }

        return false;
    }

}