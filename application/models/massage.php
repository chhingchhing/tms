<?php
class Massage extends CI_Model
{	

    
    function get_time_in($order_id)
    {
        $this->db->from('detial_orders_massages');
        $this->db->where('id_order_massage',$order_id);
        return $this->db->get()->row()->time_in;
    }
    function get_time_out($order_id)
    {
        $this->db->from('detial_orders_massages');
        $this->db->where('id_order_massage',$order_id);
        return $this->db->get()->row()->time_out;
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
       
       function select_massage_type_id(){
           $data = $this->db->select("*")  
                            ->get("massages_types");
//           $option[]= "Please select one";
           if($data->num_rows()>0){
               
               foreach($data->result() as $massage_type){
                   $option[$massage_type->massage_type_id] = $massage_type->massage_type_name;
               }
           }
           return $option;            
       }
 
       function insert_massage(&$massage, $item_massage_id) {
        if ($item_massage_id == "") {
            $success_insert_massage = $this->db->insert('items_massages', $massage);
            if ($success_insert_massage) {
                redirect('massages/massages/world_1');
            } else {
                redirect('massages/massages/world_1');
            }
        }else{
            $this->db->where('item_massage_id',$item_massage_id);
            $succes_update = $this->db->update('items_massages', $massage);
             if ($succes_update) {
                redirect('massages/massages/world_1');
            } else {
                redirect('massages/massages/world_1');
            }
        }
    }

//    function exists($person_id)
//	{
//		$this->db->from('items_massages');	
//		$this->db->join('people', 'people.person_id = customers.customer_id');
//		$this->db->where('customers.person_id',$person_id);
//		$query = $this->db->get();
//		
//		return ($query->num_rows()==1);
//	}
        function exists($item_id)
	{
		$this->db->from('items_massages');
		$this->db->where('item_massage_id',$item_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

        function account_number_exists($account_number)
	{
		$this->db->from('customers');	
		$this->db->where('account_number',$account_number);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	function customer_id_from_account_number($account_number)
	{
		$this->db->from('customers');	
		$this->db->where('account_number',$account_number);
		$query = $this->db->get();
		
		if ($query->num_rows()==1)
		{
			return $query->row()->person_id;
		}
		
		return false;
	}
	
	/*
	Returns all the customers
	*/
//	function get_all($limit=10000, $offset=0,$col='last_name',$order='asc')
//	{
//		$people=$this->db->dbprefix('people');
//		$customers=$this->db->dbprefix('customers');
//		$data=$this->db->query("SELECT * 
//						FROM ".$people."
//						STRAIGHT_JOIN ".$customers." ON 										                       
//						".$people.".person_id = ".$customers.".person_id
//						WHERE deleted =0 ORDER BY ".$col." ". $order." 
//						LIMIT  ".$offset.",".$limit);		
//						
//		return $data;
//	}
        
        
//          function get_massages($limit=10000, $offset=0,$col='company_name',$order='asc'){
//            $people=$this->db->dbprefix('suppliers');
//		$massages=$this->db->dbprefix('items_massages');
//		$data=$this->db->query("SELECT * 
//						FROM ".$people."
//						STRAIGHT_JOIN ".$massages." ON 										                       
//						".$people.".supplier_id = ".$massages.".item_massage_id
//						WHERE deleted =0 ORDER BY ".$col." ".$order." 
//						LIMIT  ".$offset.",".$limit);		
//						
//		return $data;	
//        }
        
        function get_massages($limit=10000, $offset=0,$col='massage_name',$order='asc'){
            $this->db->select('*');
            $this->db->from('items_massages');
            $this->db->where("deleted", 0);
            $this->db->order_by($col, $order);
            $this->db->limit($limit);
            $this->db->offset($offset);
            return $this->db->get();
        }
	
	function count_all()
	{
		$this->db->from('items_massages');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular massage
	*/
	function get_info($massage_id)
	{
		$this->db->from('items_massages');	
		$this->db->join('suppliers', 'items_massages.supplierID = suppliers.supplier_id');
                $this->db->join('massages_types', 'items_massages.massage_typesID = massages_types.massage_type_id');
		$this->db->where('item_massage_id',$massage_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $massage_id is NOT an customer
			$massage_obj=parent::get_info(-1);
			
			//Get all the fields from items_massages table
			$fields = $this->db->list_fields('items_massages');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$massage_obj->$field='';
			}
			
			return $massage_obj;
		}
	}
        
        function save(&$item_data, $item_id = false) {
            if (!$item_id or !$this->exists($item_id)) {
                if ($this->db->insert('items_massages', $item_data)) {
                    $item_data['item_massage_id'] = $this->db->insert_id();
                    return true;
                }
                return false;
            }
            $this->db->where('item_massage_id', $item_id);
            return $this->db->update('items_massages', $item_data);
        }
	
	/*
	Deletes one massages
	*/
	function delete($massage_ids)
	{
		$this->db->where('item_massage_id', $massage_ids);
		return $this->db->update('items_massages', array('deleted' => 1));
	}
	
	/*
	Deletes a list of customers
	*/
	function delete_list($massage_ids)
	{
		$this->db->where_in('item_massage_id',$massage_ids);
		return $this->db->update('items_massages', array('deleted' => 1));
 	}

    function check_duplicate($term) {
        $this->db->from('items_massages');
        $this->db->where('deleted', 0);
        $query = $this->db->where("name = " . $this->db->escape($term));
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return true;
        }
    }

    function check_duplicate_data($tCode, $tDestination, $tType) {
        $query = $this->db->where('massage_name', $this->db->escape($tCode))
                ->where("massage_desc", $this->db->escape($tDestination))
                ->where("massage_typesID", $this->db->escape($tType))
                ->where("deleted", 0)
                ->get('items_massages');
        
        if ($query->num_rows() > 0) {
            return true;
        }
    }
 	/*
	Get search suggestions to find customers
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('items_massages');
                
               // $this->db->join('suppliers', 'items_massages.supplierID = suppliers.supplier_id', 'left');
//                $this->db->join('massages_types', 'items_massages.massage_typesID = massages_types.massage_type_id');
//		$this->db->where('item_massage_id',$massage_id);
                
                
		
		if ($this->config->item('speed_up_search_queries'))
		{
			$this->db->where("(massage_name LIKE '".$this->db->escape_like_str($search)."%' or 
			massage_desc LIKE '".$this->db->escape_like_str($search)."%') and items_massages.deleted=0 and massages_types.deleted=0");
//CONCAT(`massage_name`,' ',`massage_desc`) LIKE '".$this->db->escape_like_str($search)."%') and deleted=0");
		}
		else
		{
			$this->db->where("(massage_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			massage_desc LIKE '%".$this->db->escape_like_str($search)."%')");
//CONCAT(`massage_name`,' ',`massage_desc`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
		}
		
		$this->db->order_by("massage_name", "desc");		
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=array('label'=> $row->massage_name);		
		}
		
		$this->db->from('items_massages');
		$this->db->where('items_massages.deleted',0);	
		$this->db->like("massage_name",$search, $this->config->item('speed_up_search_queries') ? 'after' : 'both');
		$this->db->order_by("massage_name", "desc");		
		$by_massage_type = $this->db->get();
		foreach($by_massage_type->result() as $row)
		{
			$suggestions[]=array('label'=> $row->massage_name);		
		}
	
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	
	}
     
	function search($search, $limit=20,$offset=0,$column='massage_name',$orderby='asc')
	{	
		if ($this->config->item('speed_up_search_queries'))
		{
                    $query = "
				select *
				from (
		           	(select ".$this->db->dbprefix('items_massages').".massage_name
					, ".$this->db->dbprefix('items_massages').".massage_desc, ".$this->db->dbprefix('items_massages').".deleted
		           	from ".$this->db->dbprefix('items_massages')."
		           	join ".$this->db->dbprefix('suppliers')." ON ".$this->db->dbprefix('items_massages').".supplierID = ".$this->db->dbprefix('suppliers').".supplier_id
		           	where massage_name like '".$this->db->escape_like_str($search)."%' and ".$this->db->dbprefix('items_massages').".deleted = 0
		           	order by `".$column."` ".$orderby.") as search_results
				order by `".$column."` ".$orderby." limit ".(int)$offset.",".$this->db->escape((int)$limit);
                   
				return $this->db->query($query);

		}
		else
		{
			$this->db->from('items_massages');
			$this->db->where("(massage_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			massage_desc LIKE '%".$this->db->escape_like_str($search)."%' ) and deleted = 0");		
			$this->db->order_by($column,$orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();			
		}

	}

    function get_multiple_info($item_massage_id)
	{
		$this->db->from('items_massages');
		$this->db->where_in('item_massage_id',$item_massage_id);
		$this->db->order_by("massage_name", "asc");
		return $this->db->get();		
	}

	function search_count_all($search, $limit=10000,$column='massage_name',$orderby='asc')
	{
		if ($this->config->item('speed_up_search_queries'))
		{
			$query = "
				select *
				from (
		           	(select ".$this->db->dbprefix('suppliers').".*, ".$this->db->dbprefix('items_massages').".massage_name
					, ".$this->db->dbprefix('items_massages').".massage_desc, ".$this->db->dbprefix('items_massages').".deleted
		           	from ".$this->db->dbprefix('items_massages')."
		           	join ".$this->db->dbprefix('suppliers')." ON ".$this->db->dbprefix('items_massages').".supplierID = ".$this->db->dbprefix('suppliers').".supplier_id
		           	where massage_name like '".$this->db->escape_like_str($search)."%' and deleted = 0
		           	order by `".$column."` ".$orderby.") union
                                    
                                (select ".$this->db->dbprefix('massages_types').".*, ".$this->db->dbprefix('items_massages').".massage_name, ".$this->db->dbprefix('items_massages').".massage_desc, ".$this->db->dbprefix('items_massages').".deleted
	    		from ".$this->db->dbprefix('massages_types')."
	    		join ".$this->db->dbprefix('massages_types')." ON ".$this->db->dbprefix('items_massages').".massage_typesID = ".$this->db->dbprefix('massages_types').".massage_type_id
	    		where massage_desc like '".$this->db->escape_like_str($search)."%' and deleted = 0
	    		order by `".$column."` ".$orderby." limit ".$this->db->escape($limit).")
				) as search_results
				order by `".$column."` ".$orderby." limit ".(int)$offset.",".$this->db->escape((int)$limit);
				return $this->db->query($query);
		}
		else
		{
			
			$this->db->from('items_massages');
			$this->db->where("(massage_name LIKE '%".$this->db->escape_like_str($search)."%' or 
			massage_desc LIKE '%".$this->db->escape_like_str($search)."%') and deleted = 0 ");		
			$this->db->order_by($column,$orderby);
			$this->db->limit($limit);
			$this->db->offset($offset);
			return $this->db->get();		
		}

	}
	
	
//	function cleanup()
//	{
//		$customer_data = array('account_number' => null);
//		$this->db->where('deleted', 1);
//		return $this->db->update('customers',$customer_data);
//	}
        

}
?>
