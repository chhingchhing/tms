<?php

class Bike_item extends CI_Model {

    //Search for bike item for rent
    function get_item_search_suggestions_for_sale($search, $limit = 25) {
        
        $suggestions = array();
        $by_name = $this->db
                -> where('items_bikes.deleted', 0)
                -> where('items_bikes.available', 1)
                -> like('bike_types', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
                -> order_by("bike_types", "asc")
                -> get("items_bikes");
        foreach ($by_name->result() as $row) {
            $suggestions[] = array('value' => $row->item_bike_id,'label' => $row->bike_types);
        }
        
        $by_code = $this->db
                -> where('items_bikes.deleted', 0)
                -> where('items_bikes.available', 1)
               -> like('bike_code', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
                -> order_by("bike_code", "asc")
                -> get("items_bikes");

        foreach ($by_code->result() as $row) {
            $suggestions[] = array('value' => $row->item_bike_id,'label' => $row->bike_code);
        }
        
        $status = $this->db
                -> where('items_bikes.deleted', 0)
                -> where('items_bikes.available', 1)
               -> like('available', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
                -> order_by("bike_code", "asc")
                -> get("items_bikes");

        foreach ($status->result() as $row) {
            $suggestions[] = array('value' => $row->item_bike_id,'label' => $row->available);
        }
        
         $num_day = $this->db
                -> where('number_of_day')
               -> like('number_of_day', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
                -> order_by("item_bikeID", "asc")
                -> get("detail_orders_bikes");

        foreach ($status->result() as $row) {
            $suggestions[] = array('label' => $row->number_of_day);
        }
        
        //only return $limit suggestions
        if (count($suggestions -> $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
//        var_dump($suggestions);die('die');
        return $suggestions;
    }

    /*
      Gets information about a particular item
     */

    function get_info($item_id) {
        $query = $this->db->where('item_bike_id', $item_id)->get("items_bikes");
        // echo 'get_info ***************';
        // var_dump($query->row());
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('items_bikes');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }
            // echo 'return';
            return $item_obj;
        }
    }

    /*
      Determines if a given item_id is an item
     */

    // chen *
    function exists($bike_id) {
//        echo $bike_id;die('bike id');
        $query = $this->db->where('item_bike_id', $bike_id)->get("items_bikes");

        return ($query->num_rows() == 1);
    }

    /*
      Get an item id given an item number
     */

    // Get item number of bike
    function get_item_id($code_bike) {
        
        $query = $this->db->where('bike_code', $code_bike)->get("items_bikes");

        if ($query->num_rows() == 1) {
            return $query->row()->item_bike_id;
        }

        return false;
    }
    
//     function get_item_id($code_bike) {
//        
//        $query = $this->db->where('bike_types', $code_bike)->get("items_bikes");
//
//        if ($query->num_rows() == 1) {
//            return $query->row()->item_bike_id;
//        }
//
//        return false;
//    }

}