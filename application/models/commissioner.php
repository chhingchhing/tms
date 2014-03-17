<?php

class Commissioner extends CI_Model {
    /*
     * Check commissioner exists
     */

    function count_all() {
        $this->db->from('commissioners');
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }

    /* result all item */

    function get_all($limit = 10000, $offset = 0, $col = 'commisioner_id', $order = 'desc') {
        $this->db->from('commissioners');
        $this->db->where('commissioners.deleted', 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }


    function search_count_all($search, $limit = 10000, $offset = 0, $column = 'commisioner_id', $orderby = 'desc') {

        if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('commissioners') . ".*, " . ".first_name,
                             " . $this->db->dbprefix('commissioners') . ".last_name,
                                 " . $this->db->dbprefix('commissioners') . ".tel
	           	from " . $this->db->dbprefix('commissioners') . "
	           	where commisioner_id like '" . $this->db->escape_like_str($search) . "%' and deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('commissioners');
            $this->db->where("(tel LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            tel LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			commisioner_id LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    function search($search, $limit = 20, $offset = 0, $column = 'commisioner_id', $orderby = 'desc') {

        if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('commissioners') . ".*, " . ".first_name,
                             " . $this->db->dbprefix('commissioners') . ".last_name,
                                 " . $this->db->dbprefix('commissioners') . ".tel
	           	from " . $this->db->dbprefix('commissioners') . "
	           	where commisioner_id like '" . $this->db->escape_like_str($search) . "%' and deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('commissioners');
//            $this->db->join('tickets_types', 'tickets.ticket_id=tickets_types.ticket_type_id');
            $this->db->where("(tel LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			commisioner_id LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    function exists($commissioner_id) {
        $this->db->from('commissioners');
        $this->db->where('commisioner_id', $commissioner_id);
        $query = $this->db->get();
        return ($query->num_rows() == 1);
    }

    /*
      Gets information about a particular commissioner
     */

    function get_info($commissioner_id) {
        $this->db->from('commissioners');
        $this->db->where('commisioner_id', $commissioner_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('commissioners');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }

            return $item_obj;
        }
    }
    
    function save(&$commissioner_data, $commissioner_id = false) {
        if (!$commissioner_id or !$this->exists($commissioner_id)) {

            if ($this->db->insert('commissioners', $commissioner_data)) {
                $commissioner_data['commisioner_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }
        $this->db->where('commisioner_id', $commissioner_id);
        return $this->db->update('commissioners', $commissioner_data);
    }

    /*
      Get search suggestions to find commissioner
     */

    function get_search_suggestions($search, $limit = 25) {
        $suggestions = array();

        $this->db->from('commissioners');

        if ($this->config->item('speed_up_search_queries')) {
            $this->db->where("(first_name LIKE '" . $this->db->escape_like_str($search) . "%' or 
				last_name LIKE '" . $this->db->escape_like_str($search) . "%' or 
				CONCAT(`first_name`,' ',`last_name`) LIKE '" . $this->db->escape_like_str($search) . "%') and deleted=0");
        } else {
            $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
        }
        $this->db->order_by("last_name", "desc");
        $by_name = $this->db->get();
        foreach ($by_name->result() as $row) {
            $suggestions[] = array('value' => $row->commisioner_id, 'label' => $row->first_name . ' ' . $row->last_name);
        }

        $this->db->from('commissioners');
        $this->db->where('deleted', 0);
        $this->db->like("tel", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("tel", "desc");
        $by_phone_number = $this->db->get();
        foreach ($by_phone_number->result() as $row) {
            $suggestions[] = array('value' => $row->commisioner_id, 'label' => $row->tel);
        }

        //only return $limit suggestions
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }

    function search_suggestions($search, $limit = 25) {
        
        $suggestions = array();

        $this->db->from('commissioners');

        if ($this->config->item('speed_up_search_queries')) {
            $this->db->where("(first_name LIKE '" . $this->db->escape_like_str($search) . "%' or 
                last_name LIKE '" . $this->db->escape_like_str($search) . "%' or 
                CONCAT(`first_name`,' ',`last_name`) LIKE '" . $this->db->escape_like_str($search) . "%') and deleted=0");
        } else {
            $this->db->where("(first_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            last_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            CONCAT(`first_name`,' ',`last_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
        }
        $this->db->order_by("last_name", "desc");
        $by_name = $this->db->get();
        foreach ($by_name->result() as $row) {
            $suggestions[] = array('value' => $row->commisioner_id, 'label' => $row->first_name . ' ' . $row->last_name);
        }

        $this->db->from('commissioners');
        $this->db->where('deleted', 0);
        $this->db->like("tel", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("tel", "desc");
        $by_phone_number = $this->db->get();
        foreach ($by_phone_number->result() as $row) {
            $suggestions[] = array('value' => $row->commisioner_id, 'label' => $row->tel);
        }

        //only return $limit suggestions
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
//        var_dump($suggestions).'suggest data';
        return $suggestions;
    }

    /*
      Gets information about a particular commissioner
     */

    function get_information($commisioner_id) {
        $this->db->from('commissioners');
        $this->db->where('commisioner_id', $commisioner_id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get all the fields from customer table
            $fields = $this->db->list_fields('commissioners');

            //append those fields to base parent object, we we have a complete empty object
            foreach ($fields as $field) {
                $person_obj->$field = '';
            }

            return $person_obj;
        }
    }

    function check_duplicate($first_name, $last_name) {
        // echo $first_name;
        $query = $this->db
                ->where("first_name", $first_name)
                ->where("last_name", $last_name)
                ->where("deleted", 0)
                ->get("commissioners");
                // var_dump($query->num_rows());
        if ($query->num_rows() > 0) {
            return true;
        }
    }
    
    //    get guide for excel export
    function get_commis($limit = 10000, $offset = 0, $col = 'commisioner_id', $order = 'desc') {
        $this->db->select('*');
        $this->db->from('commissioners');
        $this->db->where("commissioners.deleted", 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }
    
    function delete_list($commis_id) {
        $this->db->where_in('commisioner_id', $commis_id);
        return $this->db->update('commissioners', array('deleted' => 1));
    }

}
