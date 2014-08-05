<?php
class tour_item extends CI_Model
{

	//Search for ticket item for sale
    function get_item_search_suggestions_for_sale($search,$limit=25)
    { 
        $suggestions = array();

        $by_name = $this->db->join('destinations','tours.destinationID = destinations.destinate_id', 'left')
        		->join('suppliers','tours.supplier_id = suppliers.supplier_id', 'left')
        		->where('tours.deleted',0)
        		->like('tour_name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
        		->order_by("tour_name", "asc")
        		->get("tours"); 
        foreach($by_name->result() as $row)
        {
            $suggestions[]=array('value' => $row->tour_id, 'label' => $row->tour_name);
        }

        $by_destination = $this->db->join('destinations','tours.destinationID = destinations.destinate_id', 'left')
    			->join('suppliers','tours.supplier_id = suppliers.supplier_id', 'left')
    			->where('tours.deleted',0)
   				->like('destination_name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
    			->order_by("destination_name", "asc")
    			->get("tours");
        foreach($by_destination->result() as $row)
        {
            $suggestions[]=array('value' => $row->tour_id, 'label' => $row->destination_name);
        }

        $by_supplier = $this->db->join('destinations','tours.destinationID = destinations.destinate_id', 'left')
                ->join('suppliers','tours.supplier_id = suppliers.supplier_id', 'left')
                ->where('tours.deleted',0)
                ->like('company_name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
                ->order_by("company_name", "asc")
                ->get("tours");
        foreach($by_supplier->result() as $row)
        {
            $suggestions[]=array('value' => $row->tour_id, 'label' => $row->company_name);
        }

        //only return $limit suggestions
        if(count($suggestions > $limit))
        {
            $suggestions = array_slice($suggestions, 0,$limit);
        }
        return $suggestions; 
 
    }

    /*
    Determines if a given item_id is an item
    */
    function exists($ticket_id)
    { 
        $query = $this->db->where('tour_id',$ticket_id)->get("tours");

        return ($query->num_rows()==1);
    }

    /*
    Get an item id given an item number
    */
    function get_item_id($tour_id)
    {
        $query = $this->db->where('tour_id',$tour_id)->get("tours");

        if($query->num_rows()==1)
        {
            return $query->row()->tour_id;
        }

        return false;
    }

    /*
    Gets information about a particular item
    */
    function get_info($item_id)
    {
        $query = $this->db->where('tour_id',$item_id)->get("tours");
        if($query->num_rows()==1)
        {
            return $query->row();
        }
        else
        { 
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj=new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('tours');

            foreach ($fields as $field)
            {
                $item_obj->$field='';
            } 
            return $item_obj;
        }
    }

} // End of Class
