<?php

class Ticket extends CI_Model {

    /*
      Determines if a given item_id is an item
     */
    function get_times_departure($order_id)
    {
        $this->db->select("time_departure");
        $this->db->from('detail_orders_tickets');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();
        foreach ($query->result_array() as $rows) {
            $data[] = $rows['time_departure'];
        }
        return $data;
    }

    function get_date_departures($order_id)
    {
        $this->db->select("date_departure");
        $this->db->from('detail_orders_tickets');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();
        foreach ($query->result_array() as $rows) {
            $data[] = $rows['date_departure'];
        }
        return $data;
    }

// get hotel name of each order of item
    function get_hotels($order_id)
    {
        $this->db->select("hotel_name");
        $this->db->from('detail_orders_tickets');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();
        foreach ($query->result_array() as $rows) {
            $data[] = $rows['hotel_name'];
        }
        return $data;
    }

// get room number of each order of item
    function get_room_numbers($order_id)
    {
        $this->db->select("room_number");
        $this->db->from('detail_orders_tickets');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();
        foreach ($query->result_array() as $rows) {
            $data[] = $rows['room_number'];
        }
        return $data;
    }

    function get_item_number($order_id)
    {
        $this->db->select('item_number');
        $this->db->from('detail_orders_tickets');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();

        foreach ($query->result_array() as $rows) {
            $data[] = $rows['item_number'];
        }
        return $data;
    }

      // Get seat number of each ticket
    // After edit change sale
    function get_seat_no($order_id)
    {
        $this->db->select('seat_number');
        $this->db->from('detail_orders_tickets');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();

        foreach ($query->result_array() as $rows) {
            $data[] = $rows['seat_number'];
        }
        return $data;
    }

    // Get item vol of bus
    function get_item_vol($order_id)
    {
        $this->db->select('item_vol');
        $this->db->from('detail_orders_tickets');
        $this->db->where('orderID',$order_id);
        $query = $this->db->get();

        foreach ($query->result_array() as $rows) {
            $data[] = $rows['item_vol'];
        }
        return $data;
    }


//  insert new ticket
    function set_ticket($item_data, $item_id) {
        if ($item_id == "") {
            $success_insert = $this->db->insert('tickets', $item_data);
            if ($success_insert) {
                redirect('tickets/tickets/world_1');
            } else {
                redirect('tickets/tickets/world_1');
            }
        } else {
            $this->db->where('ticket_id', $item_id);
            $success_update = $this->db->update('tickets', $item_data);
            if ($success_update) {
                redirect('tickets/tickets/world_1');
            } else {
                redirect('tickets/tickets/world_1');
            }
        }
    }

    //select destination id
    function get_destinationID() {
        $destination_id = $this->db->select('*')
                ->get('destinations');
        $option[] = lang("items_none");
        if ($destination_id->num_rows() > 0) {
            foreach ($destination_id->result() as $destination_id) {
                $option[$destination_id->destinate_id] = $destination_id->destination_name;
            }
        }
        return $option;
    }

    //select ticket type 
    function get_ticket_type() {
        $ticket_type_id = $this->db->select("*")
                ->group_by("ticket_type_name")
                ->get('tickets_types');
        $option[] = lang("items_none");
        if ($ticket_type_id->num_rows() > 0) {
            foreach ($ticket_type_id->result() as $ticket_type_id) {
                $option[$ticket_type_id->ticket_type_id] = $ticket_type_id->ticket_type_name;
            }
        }
        return $option;
    }
// function get_category_suggestions($search)
//     {
//      $suggestions = array();
//      $this->db->distinct();
//      $this->db->select('category');
//      $this->db->from('items');
//      $this->db->like('category', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
//      $this->db->where('deleted', 0);
//      $this->db->order_by("category", "asc");
//      $by_category = $this->db->get();
//      foreach($by_category->result() as $row)
//      {
//          $suggestions[]=array('label' => $row->category);
//      }

//      return $suggestions;
//     }



    function exists($item_id) {
        $this->db->from('tickets');
        $this->db->where('ticket_id', $item_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    // check order if duplicated data of item ticket code
    function exists_item_number($item_number) {
        $this->db->from('detail_orders_tickets');
        $this->db->where('item_number', $item_number);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    /*
      Returns all the items
     */

    function get_all($limit = 10000, $offset = 0, $col = 'ticket_name', $order = 'asc') {
        $this->db->from('tickets');
        $this->db->join('tickets_types', 'tickets.ticket_typeID = tickets_types.ticket_type_id');
        $this->db->join('destinations', 'tickets.destinationID = destinations.destinate_id');
        $this->db->where('tickets.deleted', 0);
        $this->db->order_by($col, $order);
        $this->db->limit($limit);
        $this->db->offset($offset);
        return $this->db->get();
    }

    function account_number_exists($item_number) {
        $this->db->from('tickets');
        $this->db->where('code_ticket', $item_number);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    function count_all() {
        $this->db->from('tickets');
        $this->db->where('deleted', 0);
        return $this->db->count_all_results();
    }

    /*
      Gets information about a particular item
     */

    function get_info($ticket_id) {
        $this->db->from('tickets');
        $this->db->join("destinations", "tickets.destinationID = destinations.destinate_id", "left");
        $this->db->join("tickets_types", "tickets.ticket_typeID = tickets_types.ticket_type_id", "left");
        $this->db->join("suppliers", "tickets.supplierID = suppliers.supplier_id", "left");
        $this->db->where('ticket_id', $ticket_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            //Get empty base parent object, as $item_id is NOT an item
            $item_obj = new stdClass();

            //Get all the fields from items table
            $fields = $this->db->list_fields('tickets');

            foreach ($fields as $field) {
                $item_obj->$field = '';
            }

            return $item_obj;
        }
    }

    /*
      Get an item id given an item number
     */

    function get_item_id($item_number) {
        $this->db->from('tickets');
        $this->db->where('code_ticket', $item_number);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row()->item_id;
        }

        return false;
    }

    /*
      Gets information about multiple items
     */

    function get_multiple_info($item_ids) {
        $this->db->from('tickets');
        $this->db->where_in('ticket_id', $item_ids);
        $this->db->order_by("ticket_id", "asc");
        return $this->db->get();
    }

    /*
      Inserts or updates a ticket
     */

    function save(&$item_data, $item_id = false) {
        if (!$item_id or !$this->exists($item_id)) {
            if ($this->db->insert('tickets', $item_data)) {
                $item_data['ticket_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }
        $this->db->where('ticket_id', $item_id);
        return $this->db->update('tickets', $item_data);
    }

    /*
      Inserts or updates a ticket type
     */

    function add_ticket_type(&$item_ticket_type, $item_id = false) {
        if (!$this->exists_ticket_type($item_ticket_type)) {
            if ($this->db->insert('tickets_types', $item_ticket_type)) {
                $item_ticket_type['ticket_type_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }
        $this->db->from('tickets_types');
        $this->db->where('ticket_type_name', $item_ticket_type['ticket_type_name']);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $item_ticket_type['ticket_type_id'] = $row->ticket_type_id;
            return true;
        }
    }

    // check order if duplicated data of item ticket type
    function exists_ticket_type($item_ticket_type) {
        $this->db->from('tickets_types');
        $this->db->where('ticket_type_name', $item_ticket_type['ticket_type_name']);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    // check order if duplicated data of item destination
    function exists_destination($item_destination) {
        $this->db->from('destinations');
        $this->db->where('destination_name', $item_destination['destination_name']);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

    /*
      Inserts or updates a destination
     */

    function add_destination(&$item_destination, $item_id = false) {
        if (!$this->exists_destination($item_destination)) {
            if ($this->db->insert('destinations', $item_destination)) {
                $item_destination['destination_id'] = $this->db->insert_id();
                return true;
            }
            return false;
        }
        $this->db->from('destinations');
        $this->db->where('destination_name', $item_destination['destination_name']);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $item_destination['destinate_id'] = $row->destinate_id;
            return true;
        }
    }

    /*
      Updates multiple items at once
     */

    function update_multiple($item_data, $item_ids, $select_inventory = 0) {
        if (!$select_inventory) {
            $this->db->where_in('item_id', $item_ids);
        }
        return $this->db->update('tickets', $item_data);
    }

    /*
      Deletes one item
     */

    function delete($item_id) {

        $this->db->where('ticket_id', $item_id);
        return $this->db->update('tickets', array('deleted' => 1));
    }

    /*
      Deletes a list of items
     */

    function delete_list($item_ids, $select_inventory) {
        if (!$select_inventory) {
            $this->db->where_in('ticket_id', $item_ids);
        }
        return $this->db->update('tickets', array('deleted' => 1));
    }

    /*
      Get search suggestions to find items
     */

    function get_search_suggestions($search, $limit = 25) {
        $suggestions = array();
        $this->db->from('tickets');
        $this->db->join('tickets_types', 'tickets.ticket_typeID = tickets_types.ticket_type_id');
        $this->db->join('destinations', 'tickets.destinationID = destinations.destinate_id');
        $this->db->like('ticket_name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->where('tickets.deleted', 0);
        $this->db->order_by("ticket_id", "asc");
        $by_ticket_name = $this->db->get();
        foreach ($by_ticket_name->result() as $row) {
            $suggestions[] = array('label' => $row->ticket_name);
        }


   //      $this->db->from('tickets');
   //      $this->db->join('tickets_types', 'tickets.ticket_typeID = tickets_types.ticket_type_id');
   //      if ($this->config->item('speed_up_search_queries')) {
   //          $this->db->where("(ticket_name LIKE '" . $this->db->escape_like_str($search) . "%' 
			// 	CONCAT(`ticket_name`) LIKE '" . $this->db->escape_like_str($search) . "%') and tickets.deleted=0");
   //      } else {
   //          $this->db->where("(ticket_name LIKE '%" . $this->db->escape_like_str($search) . "%'  or 
			// CONCAT(`ticket_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and tickets.deleted=0");
   //      }
   //      $this->db->order_by("ticket_name", "asc");
   //      $by_name = $this->db->get();
   //      foreach ($by_name->result() as $row) {
   //          $suggestions[] = array('label' => $row->ticket_name);
   //      }

        // $this->db->from('tickets');
        // $this->db->join('tickets_types', 'tickets.ticket_typeID = tickets_types.ticket_type_id');
        // $this->db->join('destinations', 'tickets.destinationID = destinations.destinate_id');
        // $this->db->where('tickets.deleted', 0);
        // $this->db->like("destination_name", $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        // $this->db->order_by("destination_name", "asc");
        // $destination = $this->db->get();
        // foreach ($destination->result() as $row) {
        //     $suggestions[] = array('label' => $row->destination_name);
        // }

        //only return $limit suggestions
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }

    // Check duplicate data when add new record
    function check_duplicate_data($ticket_name, $tDestination, $tType) {
        $query = $this->db->where('ticket_name', $ticket_name)
                ->where("destinationID", $tDestination)
                ->where("ticket_typeID", $tType)
                ->where("deleted", 0)
                ->get('tickets');

        if ($query->num_rows() > 0) {
            return true;
        }
    }

    function get_tickets_search_suggestions($search, $limit = 25) {
        $suggestions = array();

        $this->db->from('tickets');
        $this->db->join('tickets_types', 'tickets.ticket_typeID = tickets_types.ticket_type_id');
        $this->db->join('destinations', 'tickets.destinationID = destinations.destinate_id');
        $this->db->where('deleted', 0);
        $this->db->like('destination_name', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->order_by("destination_name", "asc");
        $by_name = $this->db->get();
        foreach ($by_name->result() as $row) {
            $suggestions[] = array('value' => $row->ticket_id, 'label' => $row->destination_name);
        }

        //only return $limit suggestions
        if (count($suggestions > $limit)) {
            $suggestions = array_slice($suggestions, 0, $limit);
        }
        return $suggestions;
    }

    function get_category_suggestions($search) {
        $suggestions = array();
        $this->db->distinct();
        $this->db->select('category');
        $this->db->from('items');
        $this->db->like('category', $search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
        $this->db->where('deleted', 0);
        $this->db->order_by("category", "asc");
        $by_category = $this->db->get();
        foreach ($by_category->result() as $row) {
            $suggestions[] = array('label' => $row->category);
        }

        return $suggestions;
    }

    /*
      Preform a search on items
     */

    function search($search, $limit = 20, $offset = 0, $column = 'ticket_name', $orderby = 'asc') {
        if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('tickets') . ".*, " . $this->db->dbprefix('tickets_types') . ".deleted, " . $this->db->dbprefix('tickets_types') . ".ticket_type_name
	           	from " . $this->db->dbprefix('tickets_types') . "
	           	join " . $this->db->dbprefix('tickets') . " ON " . $this->db->dbprefix('tickets_types') . ".ticket_type_id = " . $this->db->dbprefix('tickets') . ".ticket_typeID
	           	where ticket_name like '" . $this->db->escape_like_str($search) . "%' and deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") union

			 	(select " . $this->db->dbprefix('tickets') . ".*, " . $this->db->dbprefix('tickets_types') . ".deleted, " . $this->db->dbprefix('tickets_types') . ".ticket_type_name
	           	from " . $this->db->dbprefix('tickets_types') . "
	           	join " . $this->db->dbprefix('tickets') . " ON " . $this->db->dbprefix('tickets_types') . ".ticket_type_id = " . $this->db->dbprefix('tickets') . ".ticket_typeID
	           	where ticket_type_name like '" . $this->db->escape_like_str($search) . "%' and ".$this->db->dbprefix('tickets').".deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 
				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('tickets');
            $this->db->join('tickets_types', 'tickets.ticket_typeID = tickets_types.ticket_type_id');
            $this->db->join('destinations', 'tickets.destinationID = destinations.destinate_id');

            $this->db->where("(ticket_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            destination_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            CONCAT(`ticket_name`,' ',`ticket_type_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and " . $this->db->dbprefix('tickets') . ".deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
    }

    function search_count_all($search, $limit = 10000, $column = 'ticket_name', $orderby = 'asc') {
        if ($this->config->item('speed_up_search_queries')) {
            $query = "
			select *
			from (
	           	(select " . $this->db->dbprefix('tickets') . ".*, " . $this->db->dbprefix('tickets_types') . ".deleted, " . $this->db->dbprefix('tickets_types') . ".ticket_type_name
	           	from " . $this->db->dbprefix('tickets_types') . "
	           	join " . $this->db->dbprefix('tickets') . " ON " . $this->db->dbprefix('tickets_types') . ".ticket_type_id = " . $this->db->dbprefix('tickets') . ".ticket_typeID
	           	where ticket_name like '" . $this->db->escape_like_str($search) . "%' and ".$this->db->dbprefix('tickets').".deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") union

			 	(select " . $this->db->dbprefix('tickets') . ".*, " . $this->db->dbprefix('tickets_types') . ".deleted, " . $this->db->dbprefix('tickets_types') . ".ticket_type_name
	           	from " . $this->db->dbprefix('tickets_types') . "
	           	join " . $this->db->dbprefix('tickets') . " ON " . $this->db->dbprefix('tickets_types') . ".ticket_type_id = " . $this->db->dbprefix('tickets') . ".ticket_typeID
	           	where ticket_type_name like '" . $this->db->escape_like_str($search) . "%' and ".$this->db->dbprefix('tickets').".deleted = 0
	           	order by `" . $column . "` " . $orderby . " limit " . $this->db->escape($limit) . ") 
				
			) as search_results
			order by `" . $column . "` " . $orderby . " limit " . $this->db->escape((int) $offset) . ', ' . $this->db->escape((int) $limit);

            return $this->db->query($query);
        } else {
            $this->db->from('tickets');
            $this->db->join('tickets_types', 'tickets.ticket_typeID = tickets_types.ticket_type_id');
            $this->db->join('destinations', 'tickets.destinationID = destinations.destinate_id');

            $this->db->where("(ticket_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            destination_name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
            CONCAT(`ticket_name`,' ',`ticket_type_name`) LIKE '%" . $this->db->escape_like_str($search) . "%') and " . $this->db->dbprefix('tickets') . ".deleted=0");
            $this->db->order_by($column, $orderby);
            $this->db->limit($limit);
            return $this->db->get()->num_rows();
        }
    }

    function get_categories() {
        $this->db->select('category');
        $this->db->from('items');
        $this->db->where('deleted', 0);
        $this->db->distinct();
        $this->db->order_by("category", "asc");

        return $this->db->get();
    }


    /*
      Get an destination id given an id
     */

    function report_destination_name($destination_id) {
        $this->db->from('destinations');
        $this->db->where('destinate_id', $destination_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row()->destination_name;
        }

        return false;
    }

    /*
      Get an by transportation id given an id
     */

    function report_transport_by($ticket_type_id) {
        $this->db->from('tickets_types');
        $this->db->where('ticket_type_id', $ticket_type_id);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row()->ticket_type_name;
        }

        return false;
    }

    // Check duplication destination of each new ticket
    function check_duplicate_destination($tDestination) {
        $query = $this->db->where('destination_name', $tDestination)
                ->where("deleted", 0)
                ->get('destinations');

        if ($query->num_rows() > 0) {
            return true;
        }
    }

}

?>
