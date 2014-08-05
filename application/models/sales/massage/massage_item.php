<?php

class Massage_item extends CI_Model {

    //Search for massage item for sale
    function get_item_search_suggestions_for_sale($search, $limit = 25) {
        $suggestions = array();
        $by_name = $this->db
                ->join('massages_types', 'items_massages.massage_typesID = massages_types.massage_type_id', 'left')
                ->where('items_massages.deleted', 0)
                ->like('massage_name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both')
                ->order_by("massage_name", "asc")
                ->get("items_massages");
//        var_dump($by_name);

        foreach ($by_name->result() as $row) {
            $suggestions[] = array('value' => $row->item_massage_id, 'label' => $row->massage_name);
        }

        //only return $limit suggestions
        if (count($suggestions -> $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }

    /*
      Gets information about a particular item
     */

    function get_info($item_id) {
        $query = $this->db->where('item_massage_id', $item_id)->get("items_massages");
        // echo 'get_info ***************';
        // var_dump($query->row());
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('items_massages');

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

    // chhing *
    function exists($massage_id) {
        $query = $this->db->where('item_massage_id', $massage_id)->get("items_massages");

        return ($query->num_rows() == 1);
    }

    /*
      Get an item id given an item number
     */

    // Get item number of massage
    function get_item_id($code_massage) {
        $query = $this->db->where('massage_name', $code_massage)->get("items_massages");

        if ($query->num_rows() == 1) {
            return $query->row()->item_massage_id;
        }

        return false;
    }

}