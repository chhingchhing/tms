<?php
class Sale extends CI_Model
{
	public function get_info($sale_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id',$sale_id);
		return $this->db->get();
	}
	
	function get_cash_sales_total_for_shift($shift_start, $shift_end)
    {
		$sales_totals = $this->get_sales_totaled_by_id($shift_start, $shift_end);
        $employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
        
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
		$this->db->where('employee_id',$this->session->userdata('person_id'));
		return $this->db->update('register_log', $data) ? true : false;		
	}
	function insert_register($data) {
		return $this->db->insert('register_log', $data) ? $this->db->insert_id() : false;		
	}
	
	function is_register_log_open()
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

	function get_current_register_log()
	{
		$this->db->from('register_log');
		$this->db->where('shift_end','0000-00-00 00:00:00');
		$this->db->where('employee_id',$this->session->userdata('person_id'));
		$query = $this->db->get();
		if($query->num_rows())
		return $query->row();
		else
		return false;
	
	 }

	function exists($item_id)
	{
		$query = $this->db->where('order_id',$item_id)
		 		->get('orders');

		return ($query->num_rows()==1);
	}
	
	function update($sale_data, $sale_id)
	{
		$this->db->where('sale_id', $sale_id);
		$success = $this->db->update('sales',$sale_data);
		
		return $success;
	}
	
	function update_giftcard_balance($sale_id,$undelete=0)
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
	
	}
	
	function delete($sale_id, $all_data = false)
	{
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		
		$this->db->select('item_id, quantity_purchased');
		$this->db->from('sales_items');
		$this->db->where('sale_id', $sale_id);
		
		foreach($this->db->get()->result_array() as $sale_item_row)
		{
			$cur_item_info = $this->Item->get_info($sale_item_row['item_id']);	
			$item_data = array('quantity'=>$cur_item_info->quantity + $sale_item_row['quantity_purchased']);
			$this->Item->save($item_data,$sale_item_row['item_id']);
		
			$sale_remarks ='POS '.$sale_id;
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$sale_item_row['item_id'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$sale_remarks,
				'trans_inventory'=>$sale_item_row['quantity_purchased']
				);
			$this->Inventory->insert($inv_data);
		}
		
		$this->db->select('item_kit_id, quantity_purchased');
		$this->db->from('sales_item_kits');
		$this->db->where('sale_id', $sale_id);
		
		foreach($this->db->get()->result_array() as $sale_item_kit_row)
		{
			foreach($this->Item_kit_items->get_info($sale_item_kit_row['item_kit_id']) as $item_kit_item)
			{
				$cur_item_info = $this->Item->get_info($item_kit_item->item_id);
				
				$item_data = array('quantity'=>$cur_item_info->quantity + ($sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity));
				$this->Item->save($item_data,$item_kit_item->item_id);

				$sale_remarks ='POS '.$sale_id;
				$inv_data = array
				(
					'trans_date'=>date('Y-m-d H:i:s'),
					'trans_items'=>$item_kit_item->item_id,
					'trans_user'=>$employee_id,
					'trans_comment'=>$sale_remarks,
					'trans_inventory'=>$sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity
				);
				$this->Inventory->insert($inv_data);					
			}
		}
		
		
		$this->update_giftcard_balance($sale_id);
		
		
		if ($all_data)
		{
			//Run these queries as a transaction, we want to make sure we do all or nothing
			$this->db->trans_start();
			$this->db->delete('sales_payments', array('sale_id' => $sale_id)); 
			$this->db->delete('sales_items_taxes', array('sale_id' => $sale_id)); 
			$this->db->delete('sales_items', array('sale_id' => $sale_id)); 
			$this->db->delete('sales_item_kits_taxes', array('sale_id' => $sale_id)); 
			$this->db->delete('sales_item_kits', array('sale_id' => $sale_id)); 
			$this->db->trans_complete();			
		}

		$this->db->where('sale_id', $sale_id);
		return $this->db->update('sales', array('deleted' => 1));
	}
	
	function undelete($sale_id)
	{
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		
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
		$this->db->dbprefix('sales_items'). ' USING (sale_id, item_id, line) WHERE '.$this->db->dbprefix('sales_items_taxes').".sale_id = $sale_id");
		return $query->result_array();
	}
	
	function get_sale_item_kits_taxes($sale_id)
	{
		$query = $this->db->query('SELECT name, percent, cumulative, item_kit_unit_price as price, quantity_purchased as quantity, discount_percent as discount '.
		'FROM '. $this->db->dbprefix('sales_item_kits_taxes'). ' JOIN '.
		$this->db->dbprefix('sales_item_kits'). ' USING (sale_id, item_kit_id, line) WHERE '.$this->db->dbprefix('sales_item_kits_taxes').".sale_id = $sale_id");
		return $query->result_array();		
	}

	function get_sale_payments($sale_id)
	{
		$this->db->from('sales_payments');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}

	function get_customer($order_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id',$order_id);
		return $this->Customer->get_info($this->db->get()->row()->customer_id);
	}

	function get_commissioner($order_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id',$order_id);
		return $this->commissioner->get_information($this->db->get()->row()->commisioner_id);
	}

	function get_commission_price($sale_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id',$sale_id);
		return $this->db->get()->row()->commision_price;
	}

	function get_guide($order_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id',$order_id);
		return $this->guide->get_information($this->db->get()->row()->guide_id);
	}
	
	function get_comment($sale_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id',$sale_id);
		return $this->db->get()->row()->comment;
	}

	function get_comment_on_receipt($sale_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id',$sale_id);
		return $this->db->get()->row()->show_comment_on_receipt;
	}
	

	//We create a temp table that allows us to do easy report/sales queries
	public function create_sales_items_temp_table($params)
	{
		$where = '';
		
		if (isset($params['start_date']) && isset($params['end_date']))
		{
			$where = 'WHERE sale_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'"';
			
			if ($this->config->item('hide_suspended_sales_in_reports'))
			{
				$where .=' and suspended = 0';
			}
		}
		elseif ($this->config->item('hide_suspended_sales_in_reports'))
		{
			$where .='WHERE suspended = 0';
		}
		
		// $this->_create_sales_items_temp_table_query($where);
		var_dump($this->_create_sales_items_temp_table_query($where));
		echo mysql_error();
	}
	
	function _create_sales_items_temp_table_query($where)
	{
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('sales_tickets_temp')."
		(SELECT ".$this->db->dbprefix('orders').".deleted as deleted, sale_time, date(sale_time) as sale_date, ".$this->db->dbprefix('detail_orders_tickets').".orderID, comment,payment_type, customer_id, employee_id, 
		".$this->db->dbprefix('tickets').".ticket_id, NULL as item_kit_id, supplier_id, quantity_purchased, item_cost_price, item_unit_price, 
		discount_percent, (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		".$this->db->dbprefix('detail_orders_tickets').".line as line, ".$this->db->dbprefix('detail_orders_tickets').".description as description,
		ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)+ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100),2) 
		+((ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100),2) + (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100),2) as total,
		ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100),2) 
		+((ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100),2) + (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100) as tax,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) - (item_cost_price*quantity_purchased) as profit
		FROM ".$this->db->dbprefix('detail_orders_tickets')."
		INNER JOIN ".$this->db->dbprefix('orders')." ON  ".$this->db->dbprefix('detail_orders_tickets').'.orderID='.$this->db->dbprefix('orders').'.order_id'."
		INNER JOIN ".$this->db->dbprefix('tickets')." ON  ".$this->db->dbprefix('detail_orders_tickets').'.ticketID='.$this->db->dbprefix('tickets').'.ticket_id'."
		LEFT OUTER JOIN ".$this->db->dbprefix('suppliers')." ON  ".$this->db->dbprefix('tickets').'.supplierID='.$this->db->dbprefix('suppliers').'.supplier_id'."
		$where
		GROUP BY sale_id, item_id, line) 
		UNION ALL
		(SELECT ".$this->db->dbprefix('orders').".deleted as deleted, sale_time, date(sale_time) as sale_date, ".$this->db->dbprefix('orders_item_kits').".sale_id, comment,payment_type, customer_id, employee_id, 
		NULL as item_id, ".$this->db->dbprefix('item_kits').".item_kit_id, '' as supplier_id, quantity_purchased, item_kit_cost_price, item_kit_unit_price, category, 
		discount_percent, (item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		".$this->db->dbprefix('orders_item_kits').".line as line, '' as serialnumber, ".$this->db->dbprefix('orders_item_kits').".description as description,
		ROUND((item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)+ROUND((item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100),2) 
		+((ROUND((item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100),2) + (item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100),2) as total,
		ROUND((item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100),2) 
		+((ROUND((item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100)*(SUM(CASE WHEN cumulative != 1 THEN percent ELSE 0 END)/100),2) + (item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100))
		*(SUM(CASE WHEN cumulative = 1 THEN percent ELSE 0 END))/100) as tax,
		(item_kit_unit_price*quantity_purchased-item_kit_unit_price*quantity_purchased*discount_percent/100) - (item_kit_cost_price*quantity_purchased) as profit
		FROM ".$this->db->dbprefix('orders_item_kits')."
		INNER JOIN ".$this->db->dbprefix('orders')." ON  ".$this->db->dbprefix('orders_item_kits').'.sale_id='.$this->db->dbprefix('orders').'.order_id'."
		INNER JOIN ".$this->db->dbprefix('item_kits')." ON  ".$this->db->dbprefix('orders_item_kits').'.item_kitID='.$this->db->dbprefix('item_kits').'.item_kit_id'."
		$where
		GROUP BY sale_id, item_kit_id, line) ORDER BY sale_id, line");
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
		$query = $this->db
			// ->join("people", "people.person_id = customers.customer_id")
			// ->join("customers", "customers.customer_id = orders.customer_id")
			->join("people", "people.person_id = orders.customer_id")
			->where("deleted", 0)
			->where("suspended", 1)
			->order_by("order_id") 
			->get("orders");
		return $query;
	}
}
?>
