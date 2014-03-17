<?php

class Tour extends CI_Model {
    /*
      Determines if a given item_id is an item
     */

    function get_tours($limit = 10000, $offset = 0, $col = 'tour_id', $order = 'desc') {
        $this->db->select('*');
        $this->db->from('tours');
        $this->db->join('suppliers', 'suppliers.supplier_id = tours.supplier_id');
        $this->db->join('destinations', 'tours.destinationID = destinations.destinate_id');
        $this->db->where("tours.deleted", 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }
    
    // Add destination and return id insert
    function add_destination(&$item_destination) {
            if ($this->db->insert('destinations', $item_destination)) {
                $item_destination['destination_id'] = $this->db->insert_id();
                return true;
            }
            return false;
    }

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
//        $this->db->order_by("category", "asc");
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

}

?>
