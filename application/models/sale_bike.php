<?php

class Sale_bike extends CI_Model {

    function exists($item_id) {
        $query = $this->db->where('order_id', $item_id)
                ->get('orders');

        return ($query->num_rows() == 1);
    }
    
    //get items rent bike
    function get_sale_items($sale_id) {
        $query = $this->db->where('orderID', $sale_id)
                ->get("detail_orders_bikes");
        return $query;
    }

    function get_customer($sale_id) {
        $this->db->from('orders');
        $this->db->where('order_id', $sale_id);
        return $this->Customer->get_info($this->db->get()->row()->customer_id);
    }
    
    function save ($office_name,$category,$items,$rent_dates, $return_dates,$time_in, $time_out, $deposit_price, $customer_id,$employee_id,$commissioner_id,$commissioner_price,$comment,$show_comment_on_receipt,$payments,$sale_id=false, $suspended = 0, $cc_ref_no = '', $change_sale_date=false)
	{
		if(count($items)==0)
			return -1;

		$payment_types='';
		foreach($payments as $payment_id=>$payment)
		{
			$payment_types = $payment_types.$payment['payment_type'].': '.to_currency($payment['payment_amount']).'<br />';
		}
                        
			$sales_data = array(
				'customer_id'=> $this->Customer->exists($customer_id) ? $customer_id : null,
				'employee_id'=>$employee_id,
				'payment_type'=>$payment_types,
				'deposit'=>$deposit_price,
				'comment'=>$comment,
				'show_comment_on_receipt'=> $show_comment_on_receipt ?  $show_comment_on_receipt : 0,
				'suspended'=>$suspended,
				'deleted' => 0,
				'cc_ref_no' => $cc_ref_no,
				'commision_price' => $commissioner_price,
				'commisioner_id' => $this->commissioner->exists($commissioner_id) ? $commissioner_id : null,
				'category' => $category,
				'office' => $office_name
			);
                
		if($sale_id)
		{
			$old_date=$this->Sale->get_info($sale_id)->row_array();
			$sales_data['sale_time']=$old_date['sale_time'];
			if($change_sale_date) 
			{
				$sale_time = strtotime($change_sale_date);
				if($sale_time !== FALSE)
				{
					$sales_data['sale_time']=date('Y-m-d', strtotime($change_sale_date)).' 00:00:00';
				}
			}
		}
		else
		{
			$sales_data['sale_time'] = date('Y-m-d H:i:s');
		}
	   
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
                
		if ($sale_id)
		{
			//Delete previoulsy sale so we can overwrite data
//                        echo 'sale_id';
			$this->delete($sale_id, true, $category); 
//                        echo $sale_id;die(' sale id');
			$this->db->where('order_id', $sale_id);
			$this->db->update('orders', $sales_data);
		}
		else
		{
			$this->db->insert('orders',$sales_data);
			$sale_id = $this->db->insert_id();
		}
                
		foreach($payments as $payment_id=>$payment)
		{
			$sales_payments_data = array
			(
				'sale_id'=>$sale_id,
				'payment_type'=>$payment['payment_type'],
				'payment_amount'=>$payment['payment_amount'],
				'payment_date' => $payment['payment_date'],
				'sale_type' => $category
			);
                        
			$this->db->insert('sales_payments',$sales_payments_data);
		}
                $this->db->trans_start();
		foreach($items as $line=>$item)
		{
			if (isset($item['item_bike_id']))
			{
                            //set available to 0 for ready rent bikes
                            $available_data = array('available' => 0);
                            $this->db->where('item_bike_id', $item['item_bike_id']);
                            $this->db->update('items_bikes',$available_data);
                            
                            $cur_item_info = $this->bike->get_info($item['item_bike_id']);

				if ($customer_id){
                                       
					$sales_items_data = array
					(
						'orderID'=>$sale_id,
						'item_bikeID'=>$item['item_bike_id'],
						'line'=>$item['line'],
						'room_number' => $customer->room_number,
						'issue_date'=>date('Y-m-d H:i:s'),
						'date_time_out'=>$rent_dates ? $rent_dates[$item['line']-1] : '0000-00-00',
						'date_time_in'=>$return_dates ? $return_dates[$item['line']-1] : '0000-00-00',
						'time_in' => $time_in,
                                                'time_out' => $time_out,
                                                'quantity_of_bike'=>$item['quantity'],
                                                'number_of_day'=>(round(abs(strtotime($return_dates[$item['line']-1])- strtotime($rent_dates[$item['line']-1]))/86400)) + 1 ,
						'discount_percent'=>$item['discount'],
						'actual_price' => $cur_item_info->actual_price,
						'sell_price'=>$item['price']
					);
				} else {
					 
					$sales_items_data = array
					(
						'orderID'=>$sale_id,
						'item_bikeID'=>$item['item_bike_id'],
						'line'=>$item['line'],
						'issue_date'=>date('Y-m-d H:i:s'),
						'date_time_out'=>$rent_dates ? $rent_dates[$item['line']-1] : '0000-00-00',
						'date_time_in'=>$return_dates ? $return_dates[$item['line']-1] : '0000-00-00',
						'time_in' => $time_in,
                                                'time_out' => $time_out,
                                                'quantity_of_bike'=>$item['quantity'],
                                                'number_of_day'=>(round(abs(strtotime($return_dates[$item['line']-1])- strtotime($rent_dates[$item['line']-1]))/86400)) + 1 ,
						'discount_percent'=>$item['discount'],
						'actual_price' => $cur_item_info->actual_price,
						'sell_price'=>$item['price']
					);
				}

				$this->db->insert('detail_orders_bikes',$sales_items_data);
				
				//Update stock quantity
				// $item_data = array('quantity'=>$cur_item_info->quantity - $item['quantity']);
				// $result = $this->ticket->save($item_data,$item['ticket_id']);

				//Ramel Inventory Tracking
				//Inventory Count Details
                                
				$qty_buy = -$item['quantity'];
				$sale_remarks = strtoupper($office_name).' '.$sale_id;
//                                echo 'trans_inventory';die('die hery');
				
                                $inv_data = array
				(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$item['item_bike_id'],
					'trans_user'=>$employee_id,
					'trans_comment'=>$sale_remarks,
					'trans_inventory'=>$qty_buy,
					'type_items' => $category
				);
				$this->Inventory->insert($inv_data);	
			}
			else
			{
                            //not yet edit
				$cur_item_kit_info = $this->Item_kit->get_info($item['item_kit_id'], $category);

				$sales_item_kits_data = array
				(
					'sale_id'=>$sale_id,
					'item_kitID'=>$item['item_kit_id'],
					'line'=>$item['line'],
					'description'=>$item['description'],
					'quantity_of_bike'=>$item['quantity'],
					'discount_percent'=>$item['discount'],
					'item_kit_cost_price' => $cur_item_kit_info->cost_price == NULL ? 0.00 : $cur_item_kit_info->cost_price,
					'item_kit_unit_price'=>$item['price']
				);

				$this->db->insert('orders_item_kits',$sales_item_kits_data);

				//not yet edit
				foreach($this->ticket_kit->get_info_item_kits($item['item_kit_id']) as $item_kit_item)
				{
					// $cur_item_info = $this->ticket->get_info($item_kit_item->ticket_id);

					// $item_data = array('quantity'=>$cur_item_info->quantity - ($item['quantity'] * $item_kit_item->quantity));
					// $this->ticket->save($item_data,$item_kit_item->ticket_id);

					//Ramel Inventory Tracking
					//Inventory Count Details
					$qty_buy = -$item['quantity'] * $item_kit_item->quantity;
					$sale_remarks = strtoupper($office_name).' '.$sale_id;
                                        
//                                        echo 'trans_inventory one';die('die one');
                                        
					$inv_data = array
					(
						'trans_date'=>date('Y-m-d H:i:s'),
						'trans_items'=>$item_kit_item->ticket_id,
						'trans_user'=>$employee_id,
						'trans_comment'=>$sale_remarks,
						'trans_inventory'=>$qty_buy,
						'type_items' => $category
					);
					$this->Inventory->insert($inv_data);
				}
			}
		}
                 
               $this->db->trans_complete();
               
		if ($this->db->trans_status() === FALSE)
		{
			return -1;
		}

		return $sale_id;
	}
        
        //chen
        function delete($sale_id, $all_data = false, $category)
	{
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		
		$this->db->select('item_bikeID, quantity_of_bike');
		$this->db->from('detail_orders_bikes');
		$this->db->where('orderID', $sale_id);

		foreach($this->db->get()->result_array() as $sale_item_row)
		{
			// $cur_item_info = $this->ticket->get_info($sale_item_row['ticketID']);	
			// $item_data = array('quantity'=>$cur_item_info->quantity + $sale_item_row['quantity_purchased']);
			// $this->ticket->save($item_data,$sale_item_row['ticketID']);
		
			$sale_remarks = strtoupper($this->session->userdata("office_number")).' '.$sale_id;
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$sale_item_row['item_bikeID'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$sale_remarks,
				'trans_inventory'=>$sale_item_row['quantity_of_bike'],
				'type_items' => $category
			);
         
			$this->Inventory->insert($inv_data);
		}
                

//		$this->db->select('item_kitID, quantity_of_bike');

//		//not yet edit
//		$this->db->select('item_kitID, quantity_purchased');

//		$this->db->from('orders_item_kits');
//		$this->db->where('sale_id', $sale_id);
//                
//                foreach($this->db->get()->result_array() as $sale_item_kit_row)
//		{

//                    //NOT YET DONE

//			foreach($this->item_kit_ticket->get_info($sale_item_kit_row['item_kitID']) as $item_kit_item)
//			{
//				// $cur_item_info = $this->ticket->get_info($item_kit_item->ticket_id);
//				
//				// $item_data = array('quantity'=>$cur_item_info->quantity + ($sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity));
//				// $this->ticket->save($item_data,$item_kit_item->ticket_id);
//
//				$sale_remarks ='CGATE '.$sale_id;
//				$inv_data = array
//				(
//					'trans_date'=>date('Y-m-d H:i:s'),
//					'trans_items'=>$item_kit_item->ticket_id,
//					'trans_user'=>$employee_id,
//					'trans_comment'=>$sale_remarks,
//					'trans_inventory'=>$sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity,
//					'type_items' => $category
//				);
//				$this->Inventory->insert($inv_data);					
//			}
//		}
//		
		
		if ($all_data)
		{
			//Run these queries as a transaction, we want to make sure we do all or nothing
			$this->db->trans_start();
			$this->db->delete('sales_payments', array('sale_id' => $sale_id)); 
			$this->db->trans_start();
			$this->db->delete('detail_orders_bikes', array('orderID' => $sale_id)); 
			$this->db->trans_start();
			$this->db->delete('orders_item_kits', array('sale_id' => $sale_id));
			$this->db->trans_complete();
		}

		$this->db->where('order_id', $sale_id);
		return $this->db->update('orders', array('deleted' => 1));
        }
 // Report
   public function create_sales_bikes_temp_table($params) {
        $where = '';

        if (isset($params['start_date']) && isset($params['end_date'])) {
        	$where = 'WHERE sale_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'" and office ="'.$params['office'].'"';
            if ($this->config->item('hide_suspended_sales_in_reports')) {
                $where .=' and suspended = 0';
            }
        } elseif ($this->config->item('hide_suspended_sales_in_reports')) {
            $where .='WHERE suspended = 0';
        }
        $this->_create_sales_bikes_temp_table_query($where);
    }

    function _create_sales_bikes_temp_table_query($where) {

        $this->db->query("CREATE TEMPORARY TABLE " . $this->db->dbprefix('sales_bikes_temp') . "
		(SELECT office, " . $this->db->dbprefix('orders') . ".order_id as ID, " . $this->db->dbprefix('orders') . ".deleted as deleted, sale_time, date(sale_time) as sale_date, " . $this->db->dbprefix('detail_orders_bikes') . ".item_bikeID, payment_type, comment,  customer_id, employee_id, category, commisioner_id,
		" . $this->db->dbprefix('items_bikes') . ".item_bike_id, " . $this->db->dbprefix('items_bikes') . ".bike_code, quantity_of_bike, available, number_of_day, sell_price, commision_price,
		discount_percent, (sell_price * quantity_of_bike - sell_price * quantity_of_bike * discount_percent / 100) as subtotal,
		" . $this->db->dbprefix('detail_orders_bikes') . ".line as line, ". $this->db->dbprefix('detail_orders_bikes') . ".actual_price as act ,
		
                date_time_in, date_time_out, " . $this->db->dbprefix('detail_orders_bikes') . ".issue_date, 

		ROUND( (sell_price * quantity_of_bike - sell_price * quantity_of_bike * discount_percent / 100),2) as total,

		(sell_price * quantity_of_bike - sell_price * quantity_of_bike * discount_percent / 100) - (".$this->db->dbprefix('detail_orders_bikes').".actual_price"." * quantity_of_bike) as profit,
                (sell_price * quantity_of_bike - sell_price * quantity_of_bike * discount_percent / 100) - (".$this->db->dbprefix('detail_orders_bikes').".actual_price"." * quantity_of_bike) - (commision_price)  as profit_inclod_com_price
		FROM " . $this->db->dbprefix('detail_orders_bikes') . "
		INNER JOIN " . $this->db->dbprefix('orders') . " ON  " . $this->db->dbprefix('detail_orders_bikes') . '.orderID=' . $this->db->dbprefix('orders') . '.order_id' . "
		INNER JOIN " . $this->db->dbprefix('items_bikes') . " ON  " . $this->db->dbprefix('detail_orders_bikes') . '.item_bikeID = ' . $this->db->dbprefix('items_bikes') . '.item_bike_id' . "
                    
		$where
                    GROUP BY ID, item_bikeID, line) ORDER BY ID");
    }

}

?>
