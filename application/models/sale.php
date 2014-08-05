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
		$this->db->where('order_id', $sale_id);
		$success = $this->db->update('orders',$sale_data);
		
		return $success;
	}

	function get_sale_item_kits($sale_id)
	{
		$query = $this->db->where('sale_id',$sale_id)
				->get("orders_item_kits");
		return $query;
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

	function get_massager($order_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id',$order_id);
		return $this->Employee->get_info($this->db->get()->row()->massager_id);
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

	function get_deposit_price($sale_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id',$sale_id);
		return $this->db->get()->row()->deposit;
	}
	
	function get_all_suspended($category)
	{
		$query = $this->db
			->where("deleted", 0)
			->where("suspended", 1)
			->where("category", $category)
			->order_by("order_id") 
			->get("orders");
		return $query;
	}
}
?>