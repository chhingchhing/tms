<?php
class Sale_tour extends CI_Model
{
	
	function get_cash_sales_total_for_shift($shift_start, $shift_end)
    {
		$sales_totals = $this->get_sales_totaled_by_id($shift_start, $shift_end);
        $employee_id=$this->Employee->get_logged_in_employee_info()->employee_id;
        
		$this->db->select('sales_payments.sale_id, sales_payments.payment_type, payment_amount', false);
        $this->db->from('sales_payments');
        $this->db->join('sales','sales_payments.sale_id=sales.sale_id');
		$this->db->where('sale_time >=', $shift_start);
		$this->db->where('sale_time <=', $shift_end);
		$this->db->where('employee_id', $employee_id);
		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);
		
		$payments_by_sale = array();
		$sales_payments = $this->db->get()->result_array();
		
		foreach($sales_payments as $row)
		{
        	$payments_by_sale[$row['sale_id']][] = $row;
		}
		
		$payment_data = $this->Sale->get_payment_data($payments_by_sale,$sales_totals);
		
		if (isset($payment_data[lang('sales_cash')]))
		{
			return $payment_data[lang('sales_cash')]['payment_amount'];
		}
		
		return 0.00;
    }
	
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
	
	function get_sales_totaled_by_id($shift_start, $shift_end)
	{
		$where = 'WHERE sale_time BETWEEN "'.$shift_start.'" and "'.$shift_end.'"';
		$this->_create_sales_items_temp_table_query($where);
		
		$sales_totals = array();
		
		$this->db->select('sale_id, SUM(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->db->group_by('sale_id');
			
		foreach($this->db->get()->result_array() as $sale_total_row)
		{
			$sales_totals[$sale_total_row['sale_id']] = $sale_total_row['total'];
		}
		
		return $sales_totals;
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
	/*function insert_register($data) {
		return $this->db->insert('register_log', $data) ? $this->db->insert_id() : false;		
	}*/
	
	/*function is_register_log_open()
	{
		$this->db->from('register_log');
		$this->db->where('shift_end','0000-00-00 00:00:00');
		$this->db->where('employeeID',$this->session->userdata('person_id'));
		$query = $this->db->get();
		if($query->num_rows())
		return true	;
		else
		return false;
	
	 }
*/
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
	 // Chhingchhing 26 12 2013 *
	/*function exists($item_id)
	{
		$query = $this->db->where('order_id',$item_id)
		 		->get('orders');

		return ($query->num_rows()==1);
	}*/
	
	function update($sale_data, $sale_id)
	{
		$this->db->where('sale_id', $sale_id);
		$success = $this->db->update('sales',$sale_data);
		
		return $success;
	}
	

	function save ($office_name,$category,$items,$guide_id, $customer_id,$employee_id,$commissioner_id,$commissioner_price,$times_departure,$dates_departure,$comment,$show_comment_on_receipt,$payments,$sale_id=false, $suspended = 0, $cc_ref_no = '', $change_sale_date=false)
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
				'comment'=>$comment,
				'show_comment_on_receipt'=> $show_comment_on_receipt ?  $show_comment_on_receipt : 0,
				'suspended'=>$suspended,
				'deleted' => 0,
				'cc_ref_no' => $cc_ref_no,
				'commision_price' => $commissioner_price,
				'commisioner_id' => $this->commissioner->exists($commissioner_id) ? $commissioner_id : null,
				'guide_id' => $this->guide->exists($guide_id) ? $guide_id : null,
				'office' => $office_name
			);

		if($sale_id)
		{
			$old_date=$this->sale->get_info($sale_id)->row_array();
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
			$this->delete($sale_id, true);

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
			$re_pay = $this->db->insert('sales_payments',$sales_payments_data);
		}

		foreach($items as $line=>$item)
		{
			if (isset($item['tour_id']))
			{
				$cur_item_info = $this->tour->get_info($item['tour_id']);

				$cur_destination = $this->tour->get_destination_by_item($cur_item_info->destinationID);

				if ($customer_id) {
					$customer = $this->Customer->get_info($customer_id);
					$sales_items_data = array
					(
						'orderID'=>$sale_id,
						'tour_id'=>$item['tour_id'],
						'line'=>$item['line'],
						'description'=>$item['description'],
						'room_number' => $customer->room_number,
						'hotel_name' => $customer->hotel_name,
						'issue_date'=>date('Y-m-d H:i:s'),
						'departure_date' => $dates_departure ? $dates_departure[$item['line']-1] : '0000-00-00',
						'departure_time'=>$times_departure ? $times_departure[$item['line']-1] : '00:00:00',
						'quantity_purchased'=>$item['quantity'],
						'discount_percent'=>$item['discount'],
						'item_cost_price' => $cur_item_info->actual_price,
						'item_unit_price'=>$item['price'],
						'by' => $item['by'],
						'destination' => $cur_destination
					);
				} else {
					$sales_items_data = array
					( 
						'orderID'=>$sale_id,
						'tour_id'=>$item['tour_id'],
						'line'=>$item['line'],
						'description'=>$item['description'],
						'issue_date'=>date('Y-m-d H:i:s'),
						'departure_date' => $dates_departure ? $dates_departure[$item['line']-1] : '0000-00-00',
						'departure_time'=>$times_departure ? $times_departure[$item['line']-1] : '00:00:00',
						'quantity_purchased'=>$item['quantity'],
						'discount_percent'=>$item['discount'],
						'item_cost_price' => $cur_item_info->actual_price,
						'item_unit_price'=>$item['price'],
						'by' => $item['by'],
						'destination' => $cur_destination
					);
				}

				$re_de = $this->db->insert('detail_orders_tours',$sales_items_data);
				
				//Update stock quantity
				/*$item_data = array('quantity'=>$cur_item_info->quantity - $item['quantity']);
				$this->tour->save($item_data,$item['tour_id']); */

				//Ramel Inventory Tracking
				//Inventory Count Details
				$qty_buy = -$item['quantity'];
				$sale_remarks = strtoupper($office_name).' '.$sale_id;
				$inv_data = array
				(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$item['tour_id'],
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

				$re_or = $this->db->insert('orders_item_kits',$sales_item_kits_data);
				
				foreach($this->tour_kit->get_info_item_kits($item['item_kit_id']) as $item_kit_item)
				{
					$cur_item_info = $this->tour->get_info($item_kit_item->tour_id);

					//Ramel Inventory Tracking
					//Inventory Count Details
					$qty_buy = -$item['quantity'] * $item_kit_item->quantity;
					$sale_remarks = strtoupper($office_name).' '.$sale_id;
					$inv_data = array
					(
						'trans_date'=>date('Y-m-d H:i:s'),
						'trans_items'=>$item_kit_item->tour_id,
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
	
	/*function update_giftcard_balance($sale_id,$undelete=0)
	{
		//if gift card payment exists add the amount to giftcard balance
			$this->db->from('sales_payments');
			$this->db->like('payment_type',lang('sales_giftcard'));
			$this->db->where('sale_id',$sale_id);
			$sales_payment = $this->db->get();
			
			if($sales_payment->num_rows>=1)
			{
				foreach($sales_payment->result() as $row)
				{
					$giftcard_number=str_ireplace(lang('sales_giftcard').':','',$row->payment_type);
					$value=$row->payment_amount;
					if($undelete==0)
					{
						$this->db->set('value','value+'.$value,false);			
					}
					else
					{
						$this->db->set('value','value-'.$value,false);
					}
					$this->db->where('giftcard_number', $giftcard_number);
					$this->db->update('giftcards'); 
				}
			}
	
	}*/
	
	function delete($sale_id, $all_data = false)
	{
		$employee_id=$this->Employee->get_logged_in_employee_info()->employee_id;
		
		$this->db->select('tour_id, quantity_purchased');
		$this->db->from('detail_orders_tours');
		$this->db->where('orderID', $sale_id);

		foreach($this->db->get()->result_array() as $sale_item_row)
		{
			// $cur_item_info = $this->tour->get_info($sale_item_row['tour_id']);	
			// $item_data = array('quantity'=>$cur_item_info->quantity + $sale_item_row['quantity_purchased']);
			// $this->tour->save($item_data,$sale_item_row['tour_id']);
		
			$sale_remarks = strtoupper($this->session->userdata("office_number")).' '.$sale_id;
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$sale_item_row['tour_id'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$sale_remarks,
				'trans_inventory'=>$sale_item_row['quantity_purchased']
				);
			$this->Inventory->insert($inv_data);
		}
		
		$this->db->select('item_kitID, quantity_purchased');
		$this->db->from('orders_item_kits');
		$this->db->where('sale_id', $sale_id);

		foreach($this->db->get()->result_array() as $sale_item_kit_row)
		{
			foreach($this->item_kit_tour->get_info($sale_item_kit_row['item_kitID']) as $item_kit_item)
			{ 
				// $cur_item_info = $this->tour->get_info($item_kit_item->tour_id);			
				// $item_data = array('quantity'=>$cur_item_info->quantity + ($sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity));
				// $this->tour->save($item_data,$item_kit_item->tour_id);

				$sale_remarks = strtoupper($this->session->userdata("office_number")).' '.$sale_id;
				$inv_data = array
				(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$item_kit_item->tour_id,
					'trans_user'=>$employee_id,
					'trans_comment'=>$sale_remarks,
					'trans_inventory'=>$sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity
				);
				$this->Inventory->insert($inv_data);					
			}
		}	
		
		// $this->update_giftcard_balance($sale_id);

		if ($all_data)
		{
			//Run these queries as a transaction, we want to make sure we do all or nothing
			$this->db->trans_start();
			$this->db->delete('sales_payments', array('sale_id' => $sale_id)); 
			$this->db->delete('detail_orders_tours', array('orderID' => $sale_id)); 
			$this->db->delete('orders_item_kits', array('sale_id' => $sale_id));
			$this->db->trans_complete();
		}

		$this->db->where('order_id', $sale_id);
		return $this->db->update('orders', array('deleted' => 1));
	}
	
	function undelete($sale_id)
	{
		$employee_id=$this->Employee->get_logged_in_employee_info()->employee_id;
		
		$this->db->select('item_id, quantity_purchased');
		$this->db->from('sales_items');
		$this->db->where('sale_id', $sale_id);
		
		foreach($this->db->get()->result_array() as $sale_item_row)
		{
			$cur_item_info = $this->Item->get_info($sale_item_row['item_id']);	
			$item_data = array('quantity'=>$cur_item_info->quantity - $sale_item_row['quantity_purchased']);
			$this->Item->save($item_data,$sale_item_row['item_id']);
		
			$sale_remarks ='POS '.$sale_id;
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$sale_item_row['item_id'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$sale_remarks,
				'trans_inventory'=>-$sale_item_row['quantity_purchased']
				);
			$this->Inventory->insert($inv_data);
		}
		
		$this->update_giftcard_balance($sale_id,1);
		
		$this->db->select('item_kit_id, quantity_purchased');
		$this->db->from('sales_item_kits');
		$this->db->where('sale_id', $sale_id);
		
		foreach($this->db->get()->result_array() as $sale_item_kit_row)
		{
			foreach($this->Item_kit_items->get_info($sale_item_kit_row['item_kit_id']) as $item_kit_item)
			{
				$cur_item_info = $this->Item->get_info($item_kit_item->item_id);
				
				$item_data = array('quantity'=>$cur_item_info->quantity - ($sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity));
				$this->Item->save($item_data,$item_kit_item->item_id);

				$sale_remarks ='POS '.$sale_id;
				$inv_data = array
				(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$item_kit_item->item_id,
					'trans_user'=>$employee_id,
					'trans_comment'=>$sale_remarks,
					'trans_inventory'=>-$sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity
				);
				$this->Inventory->insert($inv_data);					
			}
		}	
		$this->db->where('sale_id', $sale_id);
		return $this->db->update('sales', array('deleted' => 0));
	}

	// Get sale items
	function get_sale_items($sale_id)
	{
		$query = $this->db->where('orderID',$sale_id)
				->get("detail_orders_tours");
		return $query;
	}

	/*function get_sale_item_kits($sale_id)
	{
		$query = $this->db->where('sale_id',$sale_id)
				->get("orders_item_kits");
		return $query;
	}*/
	
	//Chhingchhing must check
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
	
	//Chhingchhing must check
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

	/*function get_sale_payments($sale_id)
	{
		$this->db->from('sales_payments');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}*/

// Chhingchhing 26 12 2013
	/*function get_customer($sale_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id',$sale_id);
		return $this->Customer->get_info($this->db->get()->row()->customer_id);
	}
	
	function get_comment($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get()->row()->comment;
	}
		function get_comment_on_receipt($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get()->row()->show_comment_on_receipt;
	}*/
	

	//We create a temp table that allows us to do easy report/sales queries
	public function create_sales_tours_temp_table($params)
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
			$where .='WHERE suspended = 0';
		}
		
		$this->_create_sales_tours_temp_table_query($where);
	}
	function _create_sales_tours_temp_table_query($where) 
	{
		 $this->db->query("CREATE TEMPORARY TABLE " . $this->db->dbprefix('sales_tours_temp') . "
		(SELECT " . $this->db->dbprefix('orders') . ".order_id as ID, " . $this->db->dbprefix('orders') . ".deleted, sale_time, date(sale_time) as sale_date, 
		" . $this->db->dbprefix('detail_orders_tours') . ".orderID, comment,payment_type, customer_id,destination, employee_id,category, commisioner_id,
		" . $this->db->dbprefix('tours') . ".tour_id, " . $this->db->dbprefix('tours') . ".supplier_id, quantity_purchased, item_cost_price, item_unit_price, 
		commision_price,company_name, discount_percent, (item_unit_price * quantity_purchased - discount_percent) as subtotal,
		" .$this->db->dbprefix('detail_orders_tours').".deposit as tour_deposit,". $this->db->dbprefix('detail_orders_tours') . ".line as line, ". $this->db->dbprefix('detail_orders_tours') . ".by as tour_by, ". $this->db->dbprefix('detail_orders_tours') . ".	
            departure_date, ".  $this->db->dbprefix('tours').".tour_name as item_name, ".$this->db->dbprefix('detail_orders_tours').".description as tour_descr, ". $this->db->dbprefix('detail_orders_tours') . ".departure_time," . $this->db->dbprefix('detail_orders_tours') . ".issue_date,  
		ROUND( (item_unit_price * quantity_purchased -  discount_percent),2) as total,
		(item_unit_price * quantity_purchased - discount_percent) - (item_cost_price * quantity_purchased) as profit,
        (item_unit_price * quantity_purchased - discount_percent) - (item_cost_price * quantity_purchased) - (commision_price)  as profit_inclod_com_price
		FROM " . $this->db->dbprefix('detail_orders_tours') . "
		INNER JOIN " . $this->db->dbprefix('orders') . " ON  " . $this->db->dbprefix('detail_orders_tours') . '.orderID=' . $this->db->dbprefix('orders') . '.order_id' . "
		INNER JOIN " . $this->db->dbprefix('tours') . " ON  " . $this->db->dbprefix('detail_orders_tours') . '.tour_id=' . $this->db->dbprefix('tours') . '.tour_id' . "
		LEFT OUTER JOIN " . $this->db->dbprefix('suppliers') . " ON  " . $this->db->dbprefix('tours') . '.supplier_id=' . $this->db->dbprefix('suppliers') . '.supplier_id' . "
		$where
        GROUP BY ID, tour_id, line) 
		UNION ALL
		(SELECT ".$this->db->dbprefix('orders').".order_id as ID, ".$this->db->dbprefix('orders').".deleted, sale_time, date(sale_time) as sale_date, 
		".$this->db->dbprefix('orders_item_kits').".sale_id as orderID, comment,payment_type, customer_id, NULL as destination, employee_id, NULL as category, commisioner_id,
		".$this->db->dbprefix('item_kits').".item_kit_id as tour_id, '' as supplier_id, quantity_purchased, item_kit_cost_price as item_cost_price, item_kit_unit_price as item_unit_price, 		
		commision_price, NULL as company_name, discount_percent, (item_kit_unit_price*quantity_purchased - discount_percent) as subtotal,
		NULL as tour_deposit, ".$this->db->dbprefix('orders_item_kits').".line as line, NULL as tour_by, NULL as departure_date, name as item_name, ".$this->db->dbprefix('orders_item_kits').".description as tour_descr, NULL as departure_time, NULL as issue_date,
		ROUND((item_kit_unit_price*quantity_purchased - discount_percent),2) as total,
		(item_kit_unit_price*quantity_purchased - discount_percent) - (item_kit_cost_price*quantity_purchased) as profit,
		(item_kit_unit_price * quantity_purchased - discount_percent) - (item_kit_cost_price * quantity_purchased) - (commision_price)  as profit_inclod_com_price		
        
		FROM ".$this->db->dbprefix('orders_item_kits')."
		INNER JOIN ".$this->db->dbprefix('orders')." ON  ".$this->db->dbprefix('orders_item_kits').'.sale_id='.$this->db->dbprefix('orders').'.order_id'."
		INNER JOIN ".$this->db->dbprefix('item_kits')." ON  ".$this->db->dbprefix('orders_item_kits').'.item_kitID='.$this->db->dbprefix('item_kits').'.item_kit_id'."
		$where
		GROUP BY ID, line) ORDER BY ID, line");
	}
	
	public function get_giftcard_value( $giftcardNumber )
	{
		if ( !$this->Giftcard->exists( $this->Giftcard->get_giftcard_id($giftcardNumber)))
			return 0;
		
		$this->db->from('giftcards');
		$this->db->where('giftcard_number',$giftcardNumber);
		return $this->db->get()->row()->value;
	}
	
	function get_all_suspended()
	{
		$this->db->from('sales');
		$this->db->where('deleted', 0);
		$this->db->where('suspended', 1);
		$this->db->order_by('sale_id');
		return $this->db->get();
	}
}
?>
