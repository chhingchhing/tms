<?php

class Office extends CI_Model {
    /*
      Determines if a given office_name is an office
     */
      function get_office_id($office_alias_name) {
        $this->db->from('offices');
        $this->db->where('alias_name', $office_alias_name);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row()->office_id;
        }
        return false;
    }

    /*
      Determines if a given office_name is an office
     */
      function get_office_name($office_alias_name) {
        $this->db->from('offices');
        $this->db->where('alias_name', $office_alias_name);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row()->office_name;
        }
        return false;
    }

    /*
      Determines if a given office_name is an office
     */
      function get_office_default_currency($office_alias_name) {
        $this->db->from('offices');
        $this->db->join('offices_info', 'offices_info.officeID = offices.office_id');
        $this->db->where('alias_name', $office_alias_name);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row()->ofc_default_currency;
        }
        return false;
    }

    function exists($office_id) {
        $this->db->from('offices_info');
        $this->db->where('officeID', $office_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function existsing($office_id) {
        $this->db->from('offices');
        $this->db->where('office_id', $office_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    /**
    *  Count all records of offices in table
    */
  function count_all() {
    $this->db->from('offices');
    $this->db->join('offices_info', 'offices.office_id = offices_info.officeID', 'left');
    $this->db->where('offices.deleted', 0);
    return $this->db->count_all_results();
  }

    /**
    *    Returns all the items
     */
    function get_all($limit = 10000, $offset = 0, $col = 'office_name', $order = 'asc') {
      $this->db->from('offices');
      $this->db->join('offices_info', 'offices.office_id = offices_info.officeID', 'left');
      $this->db->where('offices.deleted', 0);
      $this->db->order_by($col, $order);
      $this->db->limit($limit);
      $this->db->offset($offset);
      return $this->db->get();
    }

    /**
    * Get search and count all
     */
    function search_count_all($search, $limit = 10000, $offset = 0, $column = 'office_name', $orderby = 'asc') {
        if ($this->config->item('speed_up_search_queries')) {
            $query = "
      select *
      from (
              (select " . $this->db->dbprefix('offices') . ".*, " . ".office_name,
                             " . $this->db->dbprefix('offices_info') . ".ofc_address
                             " . $this->db->dbprefix('offices_info') . ".ofc_email
                             " . $this->db->dbprefix('offices_info') . ".ofc_website
              from " . $this->db->dbprefix('offices') . "
              join ".$this->db->dbprefix('offices')." ON ".$this->db->dbprefix('offices_info').".officeID = ".$this->db->dbprefix('offices').".office_id
              where office_name like '" . $this->db->escape_like_str($search) . "%' and deleted = 0
              order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ")        
      ) as search_results
      order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('offices');
            $this->db->join('offices_info', 'offices.office_id = offices_info.officeID', 'left');
            $this->db->where("(office_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            ofc_address LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    /**
    *  Preform a search on items
     */
    function search($search, $limit = 20, $offset = 0, $column = 'office_name', $orderby = 'asc') {
        if ($this->config->item('speed_up_search_queries')) {
            $query = "
            select *
            from (
                (select " . $this->db->dbprefix('offices') . ".*, " . ".office_name,
                             " . $this->db->dbprefix('offices_info') . ".ofc_address
                from " . $this->db->dbprefix('offices') . " 
                join ".$this->db->dbprefix('offices_info')." ON ".$this->db->dbprefix('offices').".office_id = ".$this->db->dbprefix('offices_info').".officeID
                where office_name LIKE '" . $this->db->escape_like_str($search) . "%' or
                ofc_address like '" . $this->db->escape_like_str($search) . "%' or
                CONCAT(`office_name`,' ',`ofc_address`) LIKE '" . $this->db->escape_like_str($search) . "%' or
                office_id LIKE '" . $this->db->escape_like_str($search) . "%' and deleted = 0
                order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ")                
            ) as search_results
            order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);
            return $this->db->query($query);
        } else {
            $this->db->from('offices');
            $this->db->join('offices_info', 'offices_info.officeID = offices.office_id', 'left');
            $this->db->where("(office_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            ofc_address LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            CONCAT(`office_name`,' ',`ofc_address`) LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            office_id LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    /**
     * Gets information about a particular offices
     */
    function get_info($office_id) {
        $this->db->from('offices');
        $this->db->join('offices_info', 'offices.office_id = offices_info.officeID', 'left');
        $this->db->join('app_files', 'app_files.officeID = offices.office_id', 'left');
        $this->db->where('office_id', $office_id);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $massage_id is NOT an customer
            // $obj = new stdClass();
            $obj = $this->get_info_parents(-1);

            $fields = $this->db->list_fields('offices_info');

            //Get all the fields from items_massages table
            foreach ($fields as $field) {
                //append those fields to base parent object, we we have a complete empty object
                $obj->$field = '';
            }

            return $obj;
        }
    }

    /*
  Gets information about a office as an array.
  */
  function get_info_parents($office_id)
  {
    $query = $this->db->get_where('offices', array('office_id' => $office_id), 1);
    
    if($query->num_rows()==1)
    {
      return $query->row();
    }
    else
    {
      //create object with empty properties.
      $fields = $this->db->list_fields('offices');
      $person_obj = new stdClass;
      
      foreach ($fields as $field)
      {
        $person_obj->$field='';
      }
      
      return $person_obj;
    }
  }


  /*
  Inserts or updates a ticket
 */
  function save(&$batch_save_data, &$office_data,$office_id=false) 
  {
    $success=false;
    //Run these queries as a transaction, we want to make sure we do all or nothing
    $this->db->trans_start();
    if($this->save_office_data($office_data,$office_id))
    { 
      if (!$office_id or !$this->exists($office_id))
      {
        $batch_save_data['officeID'] = $office_data['office_id'];
        $success = $this->db->insert('offices_info',$batch_save_data);
      }
      else
      {
        $this->db->where('officeID', $office_id);
        $success = $this->db->update('offices_info',$batch_save_data);
      }
      
    } 
    
    $this->db->trans_complete();
    return $success;
  }

  /**
  * Save information of office
  */
  function save_office_data(&$office_data,$office_id)
  {
    if (!$office_id OR !$this->existsing($office_id)) {
      if ($this->db->insert("offices",$office_data)) {
        $office_data['office_id'] = $this->db->insert_id();
        return true;
      } else {
        return false;
      }
      
    } else {
      $this->db->where('office_id', $office_id);
      return $this->db->update('offices',$office_data);
    }
  }

  /*
  Deletes a list of offices
  */
  function delete_list($office_ids)
  {
    $this->db->where_in('office_id',$office_ids);
    return $this->db->update('offices', array('deleted' => 1));
  }

  // Search autocomplete
  function get_search_suggestions($search, $limit = 25) {
        $suggestions = array();
        $this->db->from('offices');
        $this->db->join('offices_info', 'offices.office_id = offices_info.officeID', 'left');
        $this->db->join('app_files', 'app_files.officeID = offices.office_id', 'left');

        if ($this->config->item('speed_up_search_queries')) {
            $this->db->where("(office_name LIKE '" . $this->db->escape_like_str($search) . "%' or 
                ofc_address LIKE '" . $this->db->escape_like_str($search) . "%' or 
                ofc_company LIKE '" . $this->db->escape_like_str($search) . "%' or 
                CONCAT(`office_name`,' ',`ofc_address`) LIKE '" . $this->db->escape_like_str($search) . "%') and deleted=0");
        } else {
            $this->db->where("(office_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            ofc_address LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            ofc_company LIKE '" . $this->db->escape_like_str($search) . "%' or 
            CONCAT(`office_name`,' ',`ofc_address`) LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
        }
        $this->db->order_by("office_name", "asc");
        $by_name = $this->db->get();
        foreach ($by_name->result() as $row) {
            $suggestions[] = array('value' => $row->office_id, 'label' => $row->office_name . ' ' . $row->ofc_address);
        }

        //only return $limit suggestions
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }


}