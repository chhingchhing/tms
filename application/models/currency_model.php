<?php

class Currency_model extends CI_Model {

    /**
    Get currency rate by type name of currency for calculating in currency rate
    */
	function get_currency_rate_by_type_name($currency_type_name) {

		$query = $this->db
			->where("currency_type_name", $currency_type_name)
			->get("currency_types");
		return $query->row();
	}

    /**
    check the gave currency_id is really currency type name in table
    */
    function exists($currency_id) {
        $this->db->from('currency_types');
        $this->db->where('currency_id', $currency_id);
        $query = $this->db->get();
        return ($query->num_rows() == 1);
    }

    /**
    Count all records of currency in table
    */
	function count_all() {
        $this->db->from('currency_types');
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }

    /**
      Returns all the items
     */
    function get_all($limit = 10000, $offset = 0, $col = 'currency_type_name', $order = 'asc') {
        $this->db->from('currency_types');
        $this->db->where('deleted', 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    /**
      Preform a search on items
     */

    function search($search, $limit = 20, $offset = 0, $column = 'currency_type_name', $orderby = 'asc') {
        if ($this->config->item('speed_up_search_queries')) {
            $query = "
            select *
            from (
                (select " . $this->db->dbprefix('currency_types') . ".*, " . ".currency_type_name,
                             " . $this->db->dbprefix('currency_types') . ".currency_value
                from " . $this->db->dbprefix('currency_types') . " or 
                where currency_type_name LIKE '" . $this->db->escape_like_str($search) . "%' or
                currency_value like '" . $this->db->escape_like_str($search) . "%' or
                CONCAT(`currency_type_name`,' ',`currency_value`) LIKE '" . $this->db->escape_like_str($search) . "%' or
                currency_id LIKE '" . $this->db->escape_like_str($search) . "%' and deleted = 0
                order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ")                
            ) as search_results
            order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);
// CONCAT(`first_name`,' ',`last_name`)
            return $this->db->query($query);
        } else {
            $this->db->from('currency_types');
            $this->db->where("(currency_type_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            currency_id LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            CONCAT(`currency_type_name`,' ',`currency_value`) LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            currency_value LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    /**
     Get search and count all
     */
    function search_count_all($search, $limit = 10000, $offset = 0, $column = 'currency_type_name', $orderby = 'asc') {
        if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('currency_types') . ".*, " . ".currency_type_name,
                             " . $this->db->dbprefix('currency_types') . ".currency_value
	           	from " . $this->db->dbprefix('currency_types') . "
	           	where currency_type_name like '" . $this->db->escape_like_str($search) . "%' and deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('currency_types');
            $this->db->where("(currency_type_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            currency_value LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    /**
      Gets information about a particular currency
     */

    function get_info($currency_id) {
        $this->db->from('currency_types');
        $this->db->where('currency_id', $currency_id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $massage_id is NOT an customer
            $obj = new stdClass();
            
            $fields = $this->db->list_fields('currency_types');

            //Get all the fields from items_massages table
            foreach ($fields as $field) {
                //append those fields to base parent object, we we have a complete empty object
                $obj->$field = '';
            }

            return $obj;
        }
    }

    /**
    * Check duplicate data of currency
    */
    function check_duplicate($currency_type_name, $currency_value) {
        $query = $this->db->where('currency_type_name', $currency_type_name)
                // ->or_where("currency_value", $currency_value)
                ->where("deleted", 0)
                ->get('currency_types');

        if ($query->num_rows() > 0) {
            return true;
        }
    }

    /**
    Add new or update record of currency
    */
    function save(&$currency_data, $currency_id = false) {
        if (!$currency_id or !$this->exists($currency_id)) {

            if ($this->db->insert('currency_types', $currency_data)) {
                $currency_data['currency_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }
        $this->db->where('currency_id', $currency_id);
        return $this->db->update('currency_types', $currency_data);
    }

    /**
      Get search suggestions to find currency
     */
    function get_search_suggestions($search, $limit = 25) {
        $suggestions = array();
        $this->db->from('currency_types');

        if ($this->config->item('speed_up_search_queries')) {
            $this->db->where("(currency_type_name LIKE '" . $this->db->escape_like_str($search) . "%' or 
                currency_value LIKE '" . $this->db->escape_like_str($search) . "%' or 
                CONCAT(`currency_type_name`,' ',`currency_value`) LIKE '" . $this->db->escape_like_str($search) . "%') and deleted=0");
        } else {
            $this->db->where("(currency_type_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            currency_value LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            CONCAT(`currency_type_name`,' ',`currency_value`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
        }
        $this->db->order_by("currency_type_name", "asc");
        $by_name = $this->db->get();
        foreach ($by_name->result() as $row) {
            $suggestions[] = array('value' => $row->currency_id, 'label' => $row->currency_type_name . ' ' . $row->currency_value);
        }

        //only return $limit suggestions
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }

    // Delete records from table currency
    function delete_list($commis_id) {
        $this->db->where_in('currency_id', $commis_id);
        return $this->db->update('currency_types', array('deleted' => 1));
    }
    
}