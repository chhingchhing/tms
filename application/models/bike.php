<?php

class Bike extends CI_Model {
    /*
      Determines if a given person_id is a customer
     */

    function count_all() {
        $this->db->from('items_bikes');
       // $this->db->where('available', 1);
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }
    /*
      Gets information about multiple items
     */

    function get_multiple_info($item_ids) {
        $this->db->from('items_bikes');
        $this->db->where_in('item_bike_id', $item_ids);
        $this->db->order_by("item_bike_id", "desc");
        return $this->db->get();
    }
    function exists($item_id) {
        $this->db->from('items_bikes');
        $this->db->where('item_bike_id', $item_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function get_all($limit = 10000, $offset = 0, $col = 'item_bike_id', $order = 'desc') {
        $this->db->from('items_bikes');
        $this->db->where('items_bikes.deleted', 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    /*
      Gets information about a particular massage
     */

    function get_info($bike_id) {
        $this->db->from('items_bikes');
        $this->db->join("suppliers", "items_bikes.supplierID = suppliers.supplier_id", "left");
        $this->db->where('item_bike_id', $bike_id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $massage_id is NOT an customer
            $bike_obj = parent::get_info(-1);

            //Get all the fields from items_massages table
            foreach ($fields as $field) {
                $fields = $this->db->list_fields('items_bikes');

                //append those fields to base parent object, we we have a complete empty object
                $bike_obj->$field = '';
            }
            return $bike_obj;
        }
    }

    /*
      Inserts or updates a bike
     */

    function save(&$bike_data, $bike_id = false) {
        if (!$bike_id or !$this->exists($bike_id)) {
            if ($this->db->insert('items_bikes', $bike_data)) {
                $bike_data['item_bike_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }
        $this->db->where('item_bike_id', $bike_id);
        return $this->db->update('items_bikes', $bike_data);
    }

    function check_duplicate($code_bike) {
        $query = $this->db
                ->where("bike_code", $code_bike)
                ->where("available", 1)
                ->where("deleted", 0)
                ->get("items_bikes");
        if ($query->num_rows() > 0) {
            return true;
        }
    }

    /*
      Deletes one tbl items_i
     */

    function delete($bikes_ids) {
        $this->db->where('item_bike_id', $bikes_ids);
        return $this->db->update('items_bikes', array('deleted' => 1, 'available' => 0));
    }

    /*
      Deletes a list of bike
     */

    function delete_list($bikes_ids) {
        $this->db->where_in('item_bike_id ', $bikes_ids);
        return $this->db->update('items_bikes', array('deleted' => 1, 'available' => 0));
    }

    // start suggest search

    function get_search_suggestions($search, $limit = 25) {
        $suggestions = array();

        $this->db->from('items_bikes');

        if ($this->config->item('speed_up_search_queries')) {
            $this->db->where("(bike_types LIKE '" . $this->db->escape_like_str($search) . "%' or 
			available LIKE '" . $this->db->escape_like_str($search) . "%') and items_bikes.available= 1 and deleted=0 ");
//CONCAT(`massage_name`,' ',`massage_desc`) LIKE '".$this->db->escape_like_str($search)."%') and deleted=0");
        } else {
            $this->db->where("(bike_types LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			available LIKE '%" . $this->db->escape_like_str($search) . "%') and available = 1 and deleted=0 ");
//CONCAT(`massage_name`,' ',`massage_desc`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
        }

        $this->db->order_by("bike_types", "desc");
        $by_name = $this->db->get();
        foreach ($by_name->result() as $row) {
//            $suggestions[] = array('label' => $row->bike_types,'label'=>$row->bike_code);
            $suggestions[] = array('label' => $row->bike_types.' '. $row->bike_code);
        }

        $this->db->from('items_bikes');
        $this->db->where('items_bikes.available', 1);
        $this->db->where('items_bikes.deleted', 0);
        $this->db->like("bike_types", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("bike_code", "desc");
        $by_bike_type = $this->db->get();
        foreach ($by_bike_type->result() as $row) {
            $suggestions[] = array('label' => $row->bike_types.' '. $row->bike_code);
//             $suggestions[] = array('label' => $row->bike_types,'label'=>$row->bike_code);
            //$suggestions[] = array('label' => $row->bike_types);
        }
        
        $this->db->from('items_bikes');
        $this->db->where('items_bikes.available', 1);
        $this->db->where('items_bikes.deleted', 0);
        $this->db->like("bike_code", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("bike_code", "desc");
        $by_code_bike = $this->db->get();
        foreach ($by_code_bike->result() as $row) {
//            $suggestions[] = array('label' => $row->bike_types.' '. $row->bike_code);
            $suggestions[] = array('label' => $row->bike_code);
        }
        
        $this->db->from('items_bikes');
        $this->db->where('items_bikes.available', 1);
        $this->db->where('items_bikes.deleted', 0);
        $this->db->like("available", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("bike_code", "desc");
        $available = $this->db->get();
        foreach ($available->result() as $row) {
            $suggestions[] = array('label' => $row->available);
        }
        
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }

    //end suggest search
    //start search 

    /* search count all guide */

    function search($search, $limit= 10000, $offset = 0, $column = 'bike_code', $orderby = 'desc') {

        if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('items_bikes') . ".*,bike_types,bike_code,available
	           	from " . $this->db->dbprefix('items_bikes') . "
	           	where item_bike_id like '" . $this->db->escape_like_str($search) . "%' and deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 
				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);
            return $this->db->query($query);
        } else {
            $this->db->from('items_bikes');
            $this->db->where("(bike_types LIKE '%" . $this->db->escape_like_str($search) . "%' or 
                                bike_code LIKE '%" . $this->db->escape_like_str($search) . "%' or 
                                available LIKE '%" . $this->db->escape_like_str($search) . "%' or     
            item_bike_id LIKE '%" . $this->db->escape_like_str($search) . "%')");
			// item_bike_id LIKE '%" . $this->db->escape_like_str($search) . "%' and deleted = 0)");
            $this->db->where("deleted", 0);
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    function search_count_all($search, $limit = 10000, $offset = 0, $column = 'bike_code', $orderby = 'desc') {

        if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('items_bikes') . ".*,bike_types,bike_code,available
	           	from " . $this->db->dbprefix('items_bikes') . "
	           	where bike_types like '" . $this->db->escape_like_str($search) . "%' and available = 1 and deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 
				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('items_bikes');
            $this->db->where("(bike_types LIKE '%" . $this->db->escape_like_str($search) . "%' or
                        bike_code LIKE '%" . $this->db->escape_like_str($search) . "%' or 
                        available LIKE '%" . $this->db->escape_like_str($search) . "%' or
			item_bike_id LIKE '%" . $this->db->escape_like_str($search) . "%' and available = 1 and deleted = 0)");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }
    //end search

     function get_rent_dates($order_id)
    {
        $this->db->select("date_time_out");
        $this->db->from('detail_orders_bikes');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();
        foreach ($query->result_array() as $rows) {
            $data[] = $rows['date_time_out'];
        }
        return $data;
    }

     function get_return_dates($order_id)
    {
        $this->db->select("date_time_in");
        $this->db->from('detail_orders_bikes');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();
        foreach ($query->result_array() as $rows) {
            $data[] = $rows['date_time_in'];
        }
        return $data;
    }
    
     function get_time_in($order_id)
    {
        $this->db->from('detail_orders_bikes');
        $this->db->where('orderID',$order_id);
        return $this->db->get()->row()->time_in;
    }
    function get_time_out($order_id)
    {
        $this->db->from('detail_orders_bikes');
        $this->db->where('orderID',$order_id);
        return $this->db->get()->row()->time_out;
    }

}

?>
