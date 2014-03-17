<?php

class Transportation extends CI_Model {
    /*
     * Check commissioner exists
     */
    
    /*count transport*/
    function count_all() {
        $this->db->from('transports');
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }
    
    /*result all item*/
    function get_all($limit = 10000, $offset = 0, $col = 'transport_id', $order = 'desc') {
        $this->db->from('transports');
        $this->db->where('transports.deleted', 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
        
    }
    
    /*search count all transports*/
    
     function search($search, $limit = 10000, $offset = 0, $column = 'company_name', $orderby = 'desc') {

        if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('transports') . ".*, " . ".company_name,taxi_fname,taxi_lname,
	           	from " . $this->db->dbprefix('transports') . "
	           	where transport_id like '" . $this->db->escape_like_str($search) . "%' and deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('transports');
            $this->db->where("(company_name LIKE '%" . $this->db->escape_like_str($search) . "%' or  
            phone LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			transport_id LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`taxi_fname`,' ',`taxi_lname`) LIKE '%"  . $this->db->escape_like_str($search) . "%') and deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }
    
    function search_count_all($search, $limit = 10000, $offset = 0, $column = 'company_name', $orderby = 'desc') {
        
         if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('transports') . ".*, " . ".company_name,taxi_fname,taxi_lname,
	           	from " . $this->db->dbprefix('transports') . "
	           	where transport_id like '" . $this->db->escape_like_str($search) . "%' and deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('transports');
            $this->db->where("(company_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			transport_id LIKE '%" . $this->db->escape_like_str($search) . "%' or 
			CONCAT(`taxi_fname`,' ',`taxi_lname`) LIKE '%"  . $this->db->escape_like_str($search) . "%') and deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }
//    get guide for excel export
      function get_transports($limit = 10000, $offset = 0, $col = 'transport_id', $order = 'desc') {
        $this->db->select('*');
        $this->db->from('transports');
        $this->db->where("transports.deleted", 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }
//    
    function delete_list($transport_ids) {
        $this->db->where_in('transport_id', $transport_ids);
        return $this->db->update('transports', array('deleted' => 1));
    }
    
    function exists($transport_id) {
        $this->db->from('transports');
        $this->db->where('transport_id', $transport_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    /*
      Gets information about a particular commissioner
     */

    function get_info($transport_id) {
        $this->db->from('transports');
        $this->db->where('transport_id', $transport_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('transports');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }

            return $item_obj;
        }
    }

    function save(&$transport_data, $transport_id = false) {
        if (!$transport_id or !$this->exists($transport_id)) {
            if ($this->db->insert('transports', $transport_data)) {
                $transport_data['transport_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }
        $this->db->where('transport_id', $transport_id);
        return $this->db->update('transports', $transport_data);
    }
    
     function check_duplicate($company_name, $taxi_fname,$taxi_lname) {
        $query = $this->db
            ->where("company_name", $company_name)
            ->where("taxi_fname", $taxi_fname)
            ->where("taxi_lname", $taxi_lname)
            ->where("deleted", 0)
            ->get("transports");
       if ($query->num_rows() > 0) {
           return true;
       }
    }

    /*
      Get search suggestions to find commissioner
     */

     function get_transport_search_suggestions($search, $limit = 25) {
        $suggestions = array();

        $this->db->from('transports');
        $this->db->where('deleted', 0);
        $this->db->like("company_name",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("company_name", "asc");     
        $by_company_name = $this->db->get();
        foreach($by_company_name->result() as $row)
        {
            $suggestions[]=array('label' => $row->company_name);        
        }

        $this->db->from('transports');
        $this->db->where('deleted', 0);
        $this->db->like("taxi_fname",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("company_name", "asc");     
        $by_taxi_fname = $this->db->get();
        foreach($by_taxi_fname->result() as $row)
        {
            $suggestions[]=array('label' => $row->taxi_fname);        
        }
        
        $this->db->from('transports');
        $this->db->where('deleted', 0);
        $this->db->like("taxi_lname", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("taxi_lname", "desc");
        $by_phone_number = $this->db->get();
        foreach ($by_phone_number->result() as $row) {
            $suggestions[] = array('label' => $row->taxi_lname);
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

    function get_information($transport_id) {
        $this->db->from('transports');
        $this->db->where('transport_id', $transport_id);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get all the fields from customer table
            $fields = $this->db->list_fields('transports');

            //append those fields to base parent object, we we have a complete empty object
            foreach ($fields as $field) {
                $person_obj->$field = '';
            }
            return $person_obj;
        }
    }

}
