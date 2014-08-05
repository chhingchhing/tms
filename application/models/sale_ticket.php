<?php
class Sale_ticket extends CI_Model
{
	
	function get_payment_data($payments_by_sale,$sales_totals)
	{
		$payment_data = array();
				
		foreach($payments_by_sale as $sale_id => $payment_rows)
		{
			$total_sale_balance = $sales_totals[$sale_id];
			usort($payment_rows, array('Sale', '_sort_payments_for_sale'));
			
			foreach($payment_rows as $row)
			{
				$payment_amount = $row['payment_amount'] <= $total_sale_balance ? $row['payment_amount'] : $total_sale_balance;
				
				if (!isset($payment_data[$row['payment_type']]))
				{
					$payment_data[$row['payment_type']] = array('payment_type' => $row['payment_type'], 'payment_amount' => 0 );
				}
				
				if ($total_sale_balance != 0)
				{
					$payment_data[$row['payment_type']]['payment_amount'] += $payment_amount;
				}
				
				$total_sale_balance-=$payment_amount;
			}
		}
		
		return $payment_data;
	}
	
	static function _sort_payments_for_sale($a,$b)
	{
		if ($a['payment_amount'] == $b['payment_amount']);
		{
			return 0;
		}
		
		if ($a['payment_amount']< $b['payment_amount'])
		{
			return -1;
		}
		
		return 1;
	}

	/**
	 * added for cash register
	 * insert a log for track_cash_log
	 * @param array $data
	 */
	
	function update_register_log($data) {
		$this->db->where('shift_end','0000-00-00 00:00:00');
		$this->db->where('employeeID',$this->session->userdata('person_id'));
		return $this->db->update('register_log', $data) ? true : false;		
	}

	function get_current_register_log()
	{
		$this->db->from('register_log');
		$this->db->where('shift_end','0000-00-00 00:00:00');
		$this->db->where('employeeID',$this->session->userdata('person_id'));
		$query = $this->db->get();
		if($query->num_rows())
		return $query->row();
		else
		return false;
	
	 }
	
	function update($sale_data, $sale_id)
	{
		$this->db->where('sale_id', $sale_id);
		$success = $this->db->update('sales',$sale_data);
		
		return $success;
	}
	

	function save ($office_name,$category,$items, $seat_no,$item_number,$item_vol,$times_departure,$dates_departure, $hotels,$rooms, $deposit_price, $customer_id,$employee_id,$commissioner_id,$commissioner_price,$comment,$show_comment_on_receipt,$payments,$sale_id=false, $suspended = 0, $cc_ref_no = '', $change_sale_date=false)
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
			$this->delete($sale_id, true, $category);
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

		foreach($items as $line=>$item)
		{
			if (isset($item['ticket_id']))
			{
				if (!$this->Supplier->check_duplicate_hotel($hotels[$item['line']-1])) {
					$this->add_new_hotel_supplier($hotels[$item['line']-1]);
				}
				$cur_item_info = $this->ticket->get_info($item['ticket_id']);

				/*if ($customer_id) {
					$customer = $this->Customer->get_info($customer_id);
				} else {
				}*/

				$sales_items_data = array
				(
					'orderID'=>$sale_id,
					'ticketID'=>$item['ticket_id'],
					'line'=>$item['line'],
					'description'=>$item['description'],
					'room_number' => $rooms ? $rooms[$item['line']-1] : '',
					'hotel_name' => $hotels ? $hotels[$item['line']-1] : '',
					'issue_date'=>date('Y-m-d H:i:s'),
					'time_departure'=>$times_departure ? $times_departure[$item['line']-1] : '00:00:00',
					'date_departure'=>$dates_departure ? $dates_departure[$item['line']-1] : '0000-00-00',
					'quantity_purchased'=>$item['quantity'],
					'discount_percent'=>$item['discount'],
					'item_cost_price' => $cur_item_info->actual_price,
					'item_unit_price'=>$item['price'],
					'seat_number' => $seat_no ? $seat_no[$item['line']-1] : null,
					'item_number' => $item_number ? $item_number[$item['line']-1] : null,
					'item_vol' => $item_vol ? $item_vol[$item['line']-1] : null
				);

				$this->db->insert('detail_orders_tickets',$sales_items_data);
				
				//Update stock quantity
				// $item_data = array('quantity'=>$cur_item_info->quantity - $item['quantity']);
				// $result = $this->ticket->save($item_data,$item['ticket_id']);

				//Ramel Inventory Tracking
				//Inventory Count Details
				$qty_buy = -$item['quantity'];
				$sale_remarks = strtoupper($office_name).' '.$sale_id;
                                
				$inv_data = array
				(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$item['ticket_id'],
					'trans_user'=>$employee_id,
					'trans_comment'=>$sale_remarks,
					'trans_inventory'=>$qty_buy,
					'type_items' => $category
				);
				$this->Inventory->insert($inv_data);	
			}
			else
			{
				$cur_item_kit_info = $this->Item_kit->get_info($item['item_kit_id'], $category);

				$sales_item_kits_data = array
				(
					'sale_id'=>$sale_id,
					'item_kitID'=>$item['item_kit_id'],
					'line'=>$item['line'],
					'description'=>$item['description'],
					'quantity_purchased'=>$item['quantity'],
					'discount_percent'=>$item['discount'],
					'item_kit_cost_price' => $cur_item_kit_info->cost_price == NULL ? 0.00 : $cur_item_kit_info->cost_price,
					'item_kit_unit_price'=>$item['price']
				);


				$this->db->insert('orders_item_kits',$sales_item_kits_data);

				
				foreach($this->ticket_kit->get_info_item_kits($item['item_kit_id']) as $item_kit_item)
				{
					// $cur_item_info = $this->ticket->get_info($item_kit_item->ticket_id);

					// $item_data = array('quantity'=>$cur_item_info->quantity - ($item['quantity'] * $item_kit_item->quantity));
					// $this->ticket->save($item_data,$item_kit_item->ticket_id);

					//Ramel Inventory Tracking
					//Inventory Count Details
					$qty_buy = -$item['quantity'] * $item_kit_item->quantity;
					$sale_remarks = strtoupper($office_name).' '.$sale_id;
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
		
		$sale_id = str_pad($sale_id, 6, '0',STR_PAD_LEFT);
		return $sale_id;
	}
        
        //CHEN
	function delete($sale_id, $all_data = false, $category)
	{
		$office = $this->session->userdata("office_number");
		$employee_id=$this->Employee->get_logged_in_employee_info()->employee_id;
		
		$this->db->select('ticketID, quantity_purchased');
		$this->db->from('detail_orders_tickets');
		$this->db->where('orderID', $sale_id);

		foreach($this->db->get()->result_array() as $sale_item_row)
		{
			// $cur_item_info = $this->ticket->get_info($sale_item_row['ticketID']);	
			// $item_data = array('quantity'=>$cur_item_info->quantity + $sale_item_row['quantity_purchased']);
			// $this->ticket->save($item_data,$sale_item_row['ticketID']);
		
			$sale_remarks = strtoupper($office).' '.$sale_id;
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$sale_item_row['ticketID'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$sale_remarks,
				'trans_inventory'=>$sale_item_row['quantity_purchased'],
				'type_items' => $category
				);
			$this->Inventory->insert($inv_data);
		}
		
		$this->db->select('item_kitID, quantity_purchased');
		$this->db->from('orders_item_kits');
		$this->db->where('sale_id', $sale_id);

		foreach($this->db->get()->result_array() as $sale_item_kit_row)
		{
			foreach($this->item_kit_ticket->get_info($sale_item_kit_row['item_kitID']) as $item_kit_item)
			{
				// $cur_item_info = $this->ticket->get_info($item_kit_item->ticket_id);
				
				// $item_data = array('quantity'=>$cur_item_info->quantity + ($sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity));
				// $this->ticket->save($item_data,$item_kit_item->ticket_id);

				$sale_remarks = strtoupper($office).' '.$sale_id;
				$inv_data = array
				(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$item_kit_item->ticket_id,
					'trans_user'=>$employee_id,
					'trans_comment'=>$sale_remarks,
					'trans_inventory'=>$sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity,
					'type_items' => $category
				);
				$this->Inventory->insert($inv_data);					
			}
		}
		
		
		if ($all_data)
		{
			//Run these queries as a transaction, we want to make sure we do all or nothing
			$this->db->trans_start();
			$this->db->delete('sales_payments', array('sale_id' => $sale_id)); 
			$this->db->trans_start();
			$this->db->delete('detail_orders_tickets', array('orderID' => $sale_id)); 
			$this->db->trans_start();
			$this->db->delete('orders_item_kits', array('sale_id' => $sale_id));
			$this->db->trans_complete();
		}

		$this->db->where('order_id', $sale_id);
		return $this->db->update('orders', array('deleted' => 1));
	}

	// Get sale items
	function get_sale_items($sale_id)
	{
		$query = $this->db->where('orderID',$sale_id)
				->get("detail_orders_tickets");              
		return $query;
	}

	function get_sale_item_kits($sale_id)
	{
		$query = $this->db->where('sale_id',$sale_id)
				->get("orders_item_kits");
		return $query;
	}
	
	function get_sale_items_taxes($sale_id)
	{
		$query = $this->db->query('SELECT name, percent, cumulative, item_unit_price as price, quantity_purchased as quantity, discount_percent as discount '.
		'FROM '. $this->db->dbprefix('sales_items_taxes'). ' JOIN '.
		$this->db->dbprefix('detail_orders_tickets'). ' USING (sale_ticket_id, ticket_id, line) WHERE '.$this->db->dbprefix('sales_items_taxes').".sale_ticket_id = $sale_id");
		if (!$query) {
			return;
		}
		return $query->result_array();
	}

	function get_sale_item_kits_taxes($sale_id)
	{
		$query = $this->db->query('SELECT name, percent, cumulative, item_kit_unit_price as price, quantity_purchased as quantity, discount_percent as discount '.
		'FROM '. $this->db->dbprefix('sales_item_kits_taxes'). ' JOIN '.
		$this->db->dbprefix('orders_item_kits'). ' USING (sale_id, item_kitID, line) WHERE '.$this->db->dbprefix('sales_item_kits_taxes').".saleID = $sale_id");
		if (!$query) {
			return;
		}
		return $query->result_array();		
	}




	// Report
	public function create_sales_tickets_temp_table($params)
	{
		$where = '';
		
		if (isset($params['start_date']) && isset($params['end_date']))
		{
			$where = 'WHERE sale_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'" and office ="'.$params['office'].'"';
			
			if ($this->config->item('hide_suspended_sales_in_reports'))
			{
				$where .=' and suspended = 0';
			}
		}
		elseif ($this->config->item('hide_suspended_sales_in_reports'))
		{
			$where .='WHERE suspended = 0 and office = "'.$params['office'].'"';
		}
		
		$this->_create_sales_tickets_temp_table_query($where);
	}

	function _create_sales_tickets_temp_table_query($where)
	{
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('sales_tickets_temp')."
		(SELECT ".$this->db->dbprefix('orders').".deposit, ".$this->db->dbprefix('orders').".category, ".$this->db->dbprefix('orders').".deleted as deleted, sale_time, date(sale_time) as sale_date, 
		".$this->db->dbprefix('detail_orders_tickets').".orderID as ID, comment,payment_type, customer_id, employee_id, commisioner_id,
		".$this->db->dbprefix('tickets').".ticket_id, ".$this->db->dbprefix('tickets').".supplierID, quantity_purchased, item_cost_price, item_unit_price, 
		discount_percent, (item_unit_price*quantity_purchased - discount_percent) as subtotal,
		".$this->db->dbprefix('detail_orders_tickets').".line as line, ".$this->db->dbprefix('detail_orders_tickets').".description as description,
		commision_price, NULL as item_kit_id, item_number, time_departure, date_departure, seat_number, hotel_name, room_number, company_name, ".$this->db->dbprefix('detail_orders_tickets').".issue_date,  

		ROUND( (item_unit_price*quantity_purchased - discount_percent),2) as total,

		(item_unit_price*quantity_purchased - discount_percent) - (item_cost_price*quantity_purchased) as profit,
        (item_unit_price * quantity_purchased - discount_percent) - (item_cost_price * quantity_purchased) - (commision_price)  as profit_inclod_com_price
		FROM ".$this->db->dbprefix('detail_orders_tickets')."
		INNER JOIN ".$this->db->dbprefix('orders')." ON  ".$this->db->dbprefix('detail_orders_tickets').'.orderID='.$this->db->dbprefix('orders').'.order_id'."
		INNER JOIN ".$this->db->dbprefix('tickets')." ON  ".$this->db->dbprefix('detail_orders_tickets').'.ticketID='.$this->db->dbprefix('tickets').'.ticket_id'."
		LEFT OUTER JOIN ".$this->db->dbprefix('suppliers')." ON  ".$this->db->dbprefix('tickets').'.supplierID='.$this->db->dbprefix('suppliers').'.supplier_id'."

		$where
		GROUP BY ID, ticket_id, line) 
		UNION ALL
		(SELECT ".$this->db->dbprefix('orders').".deposit, ".$this->db->dbprefix('orders').".category, ".$this->db->dbprefix('orders').".deleted as deleted, sale_time, date(sale_time) as sale_date, 
		".$this->db->dbprefix('orders_item_kits').".sale_id as ID, comment,payment_type, customer_id, employee_id, commisioner_id,
		".$this->db->dbprefix('item_kits').".item_kit_id, '' as supplierID, quantity_purchased, item_kit_cost_price, item_kit_unit_price, 
		discount_percent, (item_kit_unit_price*quantity_purchased - discount_percent) as subtotal,
		".$this->db->dbprefix('orders_item_kits').".line as line, ".$this->db->dbprefix('orders_item_kits').".description as description,
		
		commision_price, ".$this->db->dbprefix('orders_item_kits').".item_kitID as item_kit_id, NULL as item_number, NULL as time_departure, NULL as date_departure, NULL as seat_number, NULL as hotel_name, NULL as room_number, 
		NULL as company_name, NULL as issue_date, 
		
		ROUND((item_kit_unit_price*quantity_purchased - discount_percent),2) as total,
		(item_kit_unit_price*quantity_purchased - discount_percent) - (item_kit_cost_price*quantity_purchased) as profit,
        (item_kit_unit_price * quantity_purchased - discount_percent) - (item_kit_cost_price * quantity_purchased) - (commision_price)  as profit_inclod_com_price
		FROM ".$this->db->dbprefix('orders_item_kits')."
		INNER JOIN ".$this->db->dbprefix('orders')." ON  ".$this->db->dbprefix('orders_item_kits').'.sale_id='.$this->db->dbprefix('orders').'.order_id'."
		INNER JOIN ".$this->db->dbprefix('item_kits')." ON  ".$this->db->dbprefix('orders_item_kits').'.item_kitID='.$this->db->dbprefix('item_kits').'.item_kit_id'."
		$where
		GROUP BY ID, line) ORDER BY ID, line");
	}

	function undelete($category, $sale_id)
	{
		$employee_id = $this->Employee->get_logged_in_employee_info()->employee_id;
		$office_name = $this->session->userdata("office_number");
		
		$this->db->select('ticketID, quantity_purchased');
		$this->db->from('detail_orders_tickets');
		$this->db->where('orderID', $sale_id);

		foreach($this->db->get()->result_array() as $sale_item_row)
		{
			// $cur_item_info = $this->ticket->get_info($sale_item_row['ticketID']);	
			// // $item_data = array('quantity'=>$cur_item_info->quantity - $sale_item_row['quantity_purchased']);
			// $this->ticket->save($item_data,$sale_item_row['ticketID']);
		
			$sale_remarks = strtoupper($office_name).' '.$sale_id;
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$sale_item_row['ticketID'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$sale_remarks,
				'trans_inventory'=>-$sale_item_row['quantity_purchased'],
				'type_items' => $category
				);
			$this->Inventory->insert($inv_data);
		}
		
		$this->db->select('item_kitID, quantity_purchased');
		$this->db->from('orders_item_kits');
		$this->db->where('sale_id', $sale_id);
		
		foreach($this->db->get()->result_array() as $sale_item_kit_row)
		{
			foreach($this->item_kit_ticket->get_info($sale_item_kit_row['item_kitID']) as $item_kit_item)
			{

				$sale_remarks = strtoupper($office_name).' '.$sale_id;
				$inv_data = array
				(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$item_kit_item->ticket_id,
					'trans_user'=>$employee_id,
					'trans_comment'=>$sale_remarks,
					'trans_inventory'=>-$sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity,
					'type_items' => $category
				);
				$this->Inventory->insert($inv_data);					
			}
		}	
		$this->db->where('order_id', $sale_id);
		return $this->db->update('orders', array('deleted' => 0));
	}

	// Add new supplier when add new from sale item each of them
	function add_new_hotel_supplier($company_name) {
		$person_data = array(
			'first_name'=>$this->input->post('first_name'),
			'last_name'=>$this->input->post('last_name'),
			'email'=>$this->input->post('email'),
			'phone_number'=>$this->input->post('phone_number'),
			'address_1'=>$this->input->post('address_1'),
			'address_2'=>$this->input->post('address_2'),
			'city'=>$this->input->post('city'),
			'state'=>$this->input->post('state'),
			'zip'=>$this->input->post('zip'),
			'country'=>$this->input->post('country'),
			'comments'=>$this->input->post('comments')
		);

		if ($this->input->post('new_supplier_type')) {
            $supplier_types = array(
                'supplier_type_name' => $this->input->post('new_supplier_type')
            );
            $this->Supplier->add_new_supplier_type($supplier_types);
            $supplier_typeID = $supplier_types['supplier_type_id'];
        }
		$supplier_data=array(
			'company_name'=>$company_name,
			'account_number'=>$this->input->post('account_number')=='' ? null:$this->input->post('account_number'),
			'supplier_typeID' => $this->input->post('supplier_type') != 0 ? $this->input->post('supplier_type') : $supplier_typeID
		);
		$this->Supplier->save($person_data,$supplier_data);
	}

}
?>
