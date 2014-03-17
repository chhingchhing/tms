<?php

class Guide extends CI_Model {
    /*
     * Check commissioner exists
     */

    /* count guide */

    function count_all() {
        $this->db->from('guides');
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }

    /* result all item */

    function get_all($limit = 10000, $offset = 0, $col = 'guide_id', $order = 'desc') {
        //$this->db->select('*');
        $this->db->from('guides');
        $this->db->where('guides.deleted', 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    /* search count all guide */

    function search($search, $limit = 1000, $offset = 0, $column = 'guide_fname', $orderby = 'asc') {

        if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('guides') . ".*, guide_fname, guide_lname, guide_type, email
	           	from " . $this->db->dbprefix('guides') . "
	           	where CONCAT(`guide_fname`,' ',`guide_lname`) like '" . $this->db->escape_like_str($search) . "%' and ".$this->db->dbprefix('guides').".deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 
				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('guides');
            $this->db->where("(guide_type LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			guide_type LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			guide_id LIKE '%" . $this->db->escape_like_str($search) . "%' or  
            email LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`guide_fname`,' ',`guide_lname`) LIKE '%" . $this->db->escape_like_str($search) . "%') and ".$this->db->dbprefix('guides').".deleted=0");
           
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    function search_count_all($search, $limit = 10000, $offset = 0, $column = 'guide_fname', $orderby = 'asc') {

        if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('guides') . ".*, " . ".guide_fname,
                             " . $this->db->dbprefix('guides') . ".guide_lname,
                             " . $this->db->dbprefix('guides') . ".email,
                                 " . $this->db->dbprefix('guides') . ".guide_type
	           	from " . $this->db->dbprefix('guides') . "
	           	where guide_id like '" . $this->db->escape_like_str($search) . "%' and deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('guides');
            $this->db->where("(guide_type LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			guide_type LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			guide_id LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            email LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`guide_fname`,' ',`guide_lname`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    //get guide for excel export
    function get_guides($limit = 10000, $offset = 0, $col = 'guide_id', $order = 'desc') {
        $this->db->select('*');
        $this->db->from('guides');
        $this->db->where("guides.deleted", 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    function delete_list($guide_ids) {
        $this->db->where_in('guide_id', $guide_ids);
        return $this->db->update('guides', array('deleted' => 1));
    }

    function exists($guide_id) {
        $this->db->from('guides');
        $this->db->where('guide_id', $guide_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    /*
      Gets information about a particular commissioner
     */

    function get_info($guide_id) {
        $this->db->from('guides');
        $this->db->where('guide_id', $guide_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('guides');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }

            return $item_obj;
        }
    }

    function save(&$guide_data, $guide_id = false) {
        if (!$guide_id or !$this->exists($guide_id)) {
            if ($this->db->insert('guides', $guide_data)) {
                $guide_data['guide_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }
        $this->db->where('guide_id', $guide_id);
        return $this->db->update('guides', $guide_data);
    }

    /*
      Get search suggestions to find commissioner
     */

    function get_search_suggestions($search, $limit = 25) {
        $suggestions = array();

        $this->db->from('guides');

        if ($this->config->item('speed_up_search_queries')) {
            $this->db->where("(guide_fname LIKE '" . $this->db->escape_like_str($search) . "%' or 
				guide_lname LIKE '" . $this->db->escape_like_str($search) . "%' or 
				CONCAT(`guide_fname`,' ',`guide_lname`) LIKE '" . $this->db->escape_like_str($search) . "%') and deleted=0");
        } else {
            $this->db->where("(guide_fname LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			guide_lname LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`guide_fname`,' ',`guide_lname`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
        }
        $this->db->order_by("guide_lname", "asc");
        $by_name = $this->db->get();
        foreach ($by_name->result() as $row) {
            $suggestions[] = array('label' => $row->guide_fname . ' ' . $row->guide_lname);
        }

        $this->db->from('guides');
        $this->db->where('deleted', 0);
        $this->db->like("guide_type", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("guide_type", "asc");
        $by_phone_number = $this->db->get();
        foreach ($by_phone_number->result() as $row) {
//            $suggestions[] = array('value' => $row->guide_id, 'label' => $row->tel);
            $suggestions[] = array('label' => $row->guide_type);
        }

        $this->db->from('guides');
        $this->db->where('deleted', 0);
        $this->db->like("email", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("email", "asc");
        $by_email = $this->db->get();
        foreach ($by_email->result() as $row) {
//            $suggestions[] = array('value' => $row->guide_id, 'label' => $row->tel);
            $suggestions[] = array('label' => $row->email);
        }

        //only return $limit suggestions
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }

    /*
      Gets information about a particular commissioner
     */

    function get_information($guide_id) {
        $this->db->from('guides');
        $this->db->where('guide_id', $guide_id);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get all the fields from customer table
            $fields = $this->db->list_fields('guides');

            //append those fields to base parent object, we we have a complete empty object
            foreach ($fields as $field) {
                $person_obj->$field = '';
            }
            return $person_obj;
        }
    }
    
     function check_duplicate($first_name, $last_name) {
        $query = $this->db
                ->where("guide_fname", $first_name)
                ->where("guide_lname", $last_name)
                ->where("deleted", 0)
                ->get("guides");
        if ($query->num_rows() > 0) {
            return true;
        }
    }

}
