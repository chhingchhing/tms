<?php

class Tour extends CI_Model {
    /*
      Determines if a given item_id is an item
     */

    function get_tours($limit = 10000, $offset = 0, $col = 'tour_id', $order = 'desc') {
        $this->db->select('*');
        $this->db->from('tours');
        $this->db->join('suppliers', 'suppliers.supplier_id = tours.supplier_id', 'left');
        $this->db->join('destinations', 'tours.destinationID = destinations.destinate_id', 'left');
        $this->db->where("tours.deleted", 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }
    
    // Add destination and return id insert
   /* function add_destination(&$item_destination) {
            if ($this->db->insert('destinations', $item_destination)) {
                $item_destination['destination_id'] = $this->db->insert_id();
                return true;
            }
            return false;
    }*/

    function count_all() {
        $this->db->from('tours');
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }

	function exists($tour_id)
	{
		$this->db->from('tours');
		$this->db->where('tour_id',$tour_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

/* Insert tour record */
    function insert_tour($tour, $tour_id) {
        if ($tour_id == "") {
            $success_insert_tour = $this->db->insert('tours', $tour);
            if ($success_insert_tour) {
                redirect('tours/tours/world_1');
            } else {
                echo '<script>alert("Can not insert new tour");</script>';
                redirect('tours/tours/world_1');
            }
        } else {
            $this->db->where('tour_id', $tour_id);
            $succes_update = $this->db->update('tours', $tour);
            if ($succes_update) {
                redirect('tours/tours/world_1');
            } else {
                echo '<script>alert("Can not update tour");</script>';
                redirect('tours/tours/world_1');
            }
        }
    }
	
    function get_info($tour_id) {
        $this->db->from('tours');
        $this->db->where('tour_id', $tour_id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
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

    /*
      Deletes one tours
     */
    function delete($tour_ids) {
        $this->db->where('tour_id', $tour_ids);
        return $this->db->update('tours', array('deleted' => 1));
    }

    /*
      Deletes a list of customers
     */

    function delete_list($tour_ids) {
        $this->db->where_in('tour_id', $tour_ids);
        return $this->db->update('tours', array('deleted' => 1));
    }

//    get_search_suggestion
    function get_search_suggestions($search, $limit = 25) {
        $suggestions = array();

        $this->db->from('tours');
        $this->db->where('tours.deleted', 0);
        $this->db->like("tour_name", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("tour_name", "desc");
        $by_tour_type = $this->db->get();
        foreach ($by_tour_type->result() as $row) {
            $suggestions[] = array('label' => $row->tour_name);
        }
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }

        $this->db->from('tours');
        if ($this->config->item('speed_up_search_queries')) {
            $this->db->where("(tour_name LIKE '" . $this->db->escape_like_str($search) . "%' or 
			description LIKE '" . $this->db->escape_like_str($search) . "%') and tours.deleted=0");
        } else {
            $this->db->where("(tour_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			description LIKE '%" . $this->db->escape_like_str($search) . "%')");
        }

        $this->db->order_by("tour_name", "desc");
        $by_name = $this->db->get();
        foreach ($by_name->result() as $row) {
            $suggestions[] = array('label' => $row->tour_name);
        }

        $this->db->from('tours');
        $this->db->where('tours.deleted', 0);
        $this->db->like("description", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("tour_name", "desc");
        $by_tour_type = $this->db->get();
        foreach ($by_tour_type->result() as $row) {
            $suggestions[] = array('label' => $row->tour_name);
        }
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }
//    end_search_suggestion

    /* Check duplicate of tour record */
    function check_duplicate($name, $destination) {
        $query = $this->db
            ->where("tour_name", $name)
            ->where("destinationID", $destination)
            ->where("deleted", 0)
            ->get("tours");
       if ($query->num_rows() > 0) {
           return true;
       }
    }

    //start search

    function search($search, $limit = 1000, $offset = 0, $column = 'tour_id', $orderby = 'desc') {

        if ($this->config->item('speed_up_search_queries')) {

// "(select ".$this->db->dbprefix('people').".*, ".$this->db->dbprefix('employees').".username, ".$this->db->dbprefix('employees').".deleted
//             from ".$this->db->dbprefix('employees')."
//             join ".$this->db->dbprefix('people')." ON ".$this->db->dbprefix('employees').".person_id = ".$this->db->dbprefix('people').".person_id
//             where first_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
//             order by `".$column."` ".$orderby.")"
// $this->db->join('suppliers', 'suppliers.supplier_id = tours.supplier_id');
//         $this->db->join('destinations', 'tours.destinationID = destinations.destinate_id');


            $query = "
				select *
				from (
		           	(select " . $this->db->dbprefix('tours') . ".tour_name
					, " . $this->db->dbprefix('tours') . ".description, " . $this->db->dbprefix('tours') . ".deleted
		           	from " . $this->db->dbprefix('tours') . "
                    left join ".$this->db->dbprefix('suppliers')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('tours').".supplier_id 
                    left join ".$this->db->dbprefix('destinations')." ON ".$this->db->dbprefix('destinations').".destinate_id = ".$this->db->dbprefix('tours').".destinationID 
		           	where tour_name like '" . $this->db->escape_like_str($search) . "%' and ".$this->db->dbprefix('tours').".deleted = 0
		           	order by `" . $column . "` " . $orderby . ") as search_results
				order by `" . $column . "` " . $orderby . " limit " . (int) $offset . "," . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('tours');
            $this->db->join('suppliers', 'suppliers.supplier_id = tours.supplier_id', 'left');
            $this->db->join('destinations', 'tours.destinationID = destinations.destinate_id', 'left');
            $this->db->where("(tour_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			description LIKE '%" . $this->db->escape_like_str($search) . "%' ) and ".$this->db->dbprefix('tours').".deleted = 0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    function search_count_all($search, $limit = 10000,$offset = 0, $column = 'tour_id', $orderby = 'desc') {

        if ($this->config->item('speed_up_search_queries')) {
            $query = "
				select *
				from (
		           	(select " . $this->db->dbprefix('tours') . ".tour_name
					, " . $this->db->dbprefix('tours') . ".description, " . $this->db->dbprefix('tours') . ".deleted
		           	from " . $this->db->dbprefix('tours') . "
                    join ".$this->db->dbprefix('suppliers')." ON ".$this->db->dbprefix('suppliers').".supplier_id = ".$this->db->dbprefix('tours').".supplier_id 
                    join ".$this->db->dbprefix('destinations')." ON ".$this->db->dbprefix('destinations').".destinate_id = ".$this->db->dbprefix('tours').".destinationID 
		           	where tour_name like '" . $this->db->escape_like_str($search) . "%' and ".$this->db->dbprefix('tours').".deleted = 0
		           	order by `" . $column . "` " . $orderby . ") as search_results
				order by `" . $column . "` " . $orderby . " limit " . (int) $offset . "," . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('tours');
            $this->db->join('suppliers', 'suppliers.supplier_id = tours.supplier_id');
            $this->db->join('destinations', 'tours.destinationID = destinations.destinate_id');
            $this->db->where("(tour_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			description LIKE '%" . $this->db->escape_like_str($search) . "%' ) and ".$this->db->dbprefix('tours').".deleted = 0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    /*
    Inserts or updates a item tour
    */
    function save(&$tour_data,$tour_id=false)
    { 
        if (!$tour_id or !$this->exists($tour_id))
        {
            if($this->db->insert('tours',$tour_data))
            {
                $tour_data['tour_id']=$this->db->insert_id();
                return true;
            } 
            return false;
        }

        $this->db->where('tour_id', $tour_id);
        return $this->db->update('tours',$tour_data);
    }

    //end search
//    function get_categories() {
//        $this->db->select('category');
//        $this->db->from('items');
//        $this->db->where('deleted', 0);
//        $this->db->distinct();
//        $this->db->order_by("category", "desc");
//
//        return $this->db->get();
//    }
//
//    function cleanup() {
//        $item_data = array('item_number' => null);
//        $this->db->where('deleted', 1);
//        return $this->db->update('items', $item_data);
//    }


    /*
    * Take destination name by item id 
    */
    function get_destination_by_item($destinate_id) {
        $query = $this->db->where('destinate_id',$destinate_id)
                ->get("destinations");
        if($query->num_rows()==1)
        {
            return $query->row()->destination_name;
        }

        return false;
    }
    
     //select destination id
    function get_destinationID() {
        $destination_id = $this->db->select('*')
                ->get('destinations');
        $option[] = "--Select Destination--";
        if ($destination_id->num_rows() > 0) {
            foreach ($destination_id->result() as $destination_id) {
                $option[$destination_id->destinate_id] = $destination_id->destination_name;
            }
        }
        return $option;
    }
    
     function select_supplier_id(){
         $data = $this->db->select("*")
                 ->get("suppliers");
         $option[] = "Please select one";
         if($data->num_rows() > 0){
             foreach($data->result() as $item){
                 $option[$item->supplier_id] = $item->company_name;
             }
         }
         return $option;
       }
        /*
      Gets information about multiple items
     */

    function get_multiple_info($item_ids) {
        $this->db->from('tours');
        $this->db->where_in('tour_id', $item_ids);
        $this->db->order_by("tour_id", "desc");
        return $this->db->get();
    }

    /**
    * Starting For Package/Kit
    */
    function get_item_search_suggestions($search,$limit=25)
    {
     $suggestions = array();

     $this->db->from('tours');
     $this->db->where('deleted',0);
     $this->db->like('tour_name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
     $this->db->order_by("tour_name", "asc");
     $by_name = $this->db->get();
     foreach($by_name->result() as $row)
     {
         $suggestions[]=array('value' => $row->tour_id, 'label' => $row->tour_name);
     }

     /*$this->db->from('items');
     $this->db->where('deleted',0);
     $this->db->like('item_number', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
     $this->db->order_by("item_number", "asc");
     $by_item_number = $this->db->get();
     foreach($by_item_number->result() as $row)
     {
         $suggestions[]=array('value' => $row->tour_id, 'label' => $row->item_number);
     }*/

     //only return $limit suggestions
     if(count($suggestions > $limit))
     {
         $suggestions = array_slice($suggestions, 0,$limit);
     }
     return $suggestions;

    }

    function check_duplicate_package($term)
    {
        $this->db->from('item_kits');
        $this->db->where('deleted',0);      
        $query = $this->db->where("name = ".$this->db->escape($term));
        $query=$this->db->get();
        
        if($query->num_rows()>0)
        {
            return true;
        }
        
    }

    /*
    Inserts or updates an item kit
    */
    function save_package(&$item_kit_data,$item_kit_id=false)
    {
        if (!$item_kit_id or !$this->exists_package($item_kit_id))
        {
            if($this->db->insert('item_kits',$item_kit_data))
            {
                $item_kit_data['item_kit_id']=$this->db->insert_id();
                return true;
            }
            return false;
        }

        $this->db->where('item_kit_id', $item_kit_id);
        return $this->db->update('item_kits',$item_kit_data);
    }

    /*
    Determines if a given item_id is an item kit
    */
    function exists_package($item_kit_id, $category)
    {
        $this->db->from('item_kits');
        $this->db->where('item_kit_id',$item_kit_id);
        // $this->db->where('category',$category);
        $query = $this->db->get();

        return ($query->num_rows()==1);
    }

    /*
    Inserts or updates an item kit's items
    */
    function save_package_item(&$item_kit_items_data, $item_kit_id)
    {
        //Run these queries as a transaction, we want to make sure we do all or nothing
        $this->db->trans_start();

        $this->delete_package_item($item_kit_id);
        
        foreach ($item_kit_items_data as $row)
        {
            $row['item_kit_id'] = $item_kit_id;
            $this->db->insert('item_kits_tours',$row);       
        }
        
        $this->db->trans_complete();
        return true;
    }

    /*
    Deletes item kit items given an item kit
    */
    function delete_package_item($item_kit_id)
    {
        return $this->db->delete('item_kits_tours', array('item_kit_id' => $item_kit_id)); 
    }

    /*
    Count all for package item
    */
    function count_all_package() {
        $this->db->from('item_kits');
        $this->db->where('deleted',0);
        return $this->db->count_all_results();
    }

    /*
    Search count all package item
    */
    function search_count_all_package($search)
    {
        $this->db->from('item_kits');
        
        if ($this->config->item('speed_up_search_queries'))
        {
            $this->db->where("name LIKE '".$this->db->escape_like_str($search)."%' or 
            description LIKE '".$this->db->escape_like_str($search)."%'");
        }
        else
        {
            $this->db->where("(name LIKE '%".$this->db->escape_like_str($search).
            "%' or item_kit_number LIKE '%".$this->db->escape_like_str($search)."%' or
            description LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");  
        }
        $this->db->order_by("name", "asc");
        $result=$this->db->get();               
        return $result->num_rows(); 
    }

    /*
    Preform a search on items kits
    */
    function search_package($search, $limit=16,$offset=0,$column='name',$orderby='asc')
    {
        $this->db->from('item_kits');
        
        if ($this->config->item('speed_up_search_queries'))
        {
            $this->db->where("name LIKE '".$this->db->escape_like_str($search)."%' or 
            description LIKE '".$this->db->escape_like_str($search)."%'");
        }
        else
        {
            $this->db->where("(name LIKE '%".$this->db->escape_like_str($search).
            "%' or item_kit_number LIKE '%".$this->db->escape_like_str($search)."%' or
            description LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");  
        }
        $this->db->order_by($column, $orderby);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();    
    }

    /*
    Returns all the item kits
    */
    function get_all_package($limit=10000, $offset=0,$col='name',$ord='asc')
    {
        $this->db->from('item_kits');
        $this->db->where('deleted',0);
        $this->db->order_by($col, $ord);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    /*
    Gets information about a particular item kit
    */
    function get_info_package($item_kit_id, $category)
    { 
        $this->db->from('item_kits');
        $this->db->where('item_kit_id',$item_kit_id);
        $this->db->where('category',$category);
        
        $query = $this->db->get();

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
    Get search suggestions to find kits
    */
    function get_search_package_suggestions($search,$limit=25)
    {
        $suggestions = array();

        $this->db->from('item_kits');
        $this->db->like('name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->where('deleted',0);
        $this->db->order_by("name", "asc");
        $by_name = $this->db->get();
        foreach($by_name->result() as $row)
        {
            $suggestions[]=array('label' => $row->name);
        }
        
        $this->db->from('item_kits');
        $this->db->like('item_kit_number', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->where('deleted',0);
        $this->db->order_by("item_kit_number", "asc");
        $by_item_kit_number = $this->db->get();
        foreach($by_item_kit_number->result() as $row)
        {
            $suggestions[]=array('label' => $row->item_kit_number);
        }

        //only return $limit suggestions
        if(count($suggestions > $limit))
        {
            $suggestions = array_slice($suggestions, 0,$limit);
        }
        return $suggestions;

    }

    /*
    Deletes a list of item kits
    */
    function delete_list_package($item_kit_ids)
    {
        $this->db->where_in('item_kit_id',$item_kit_ids);
        return $this->db->update('item_kits', array('deleted' => 1));
    }

    /*
      Determines if a given item_id is an item
     */
    function get_times_departure($order_id)
    {
        $this->db->select("departure_time");
        $this->db->from('detail_orders_tours');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();
        foreach ($query->result_array() as $rows) {
            $data[] = $rows['departure_time'];
        }
        return $data;
    }

    function get_date_departures($order_id)
    {
        $this->db->select("departure_date");
        $this->db->from('detail_orders_tours');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();
        foreach ($query->result_array() as $rows) {
            $data[] = $rows['departure_date'];
        }
        return $data;
    }

}

?>
