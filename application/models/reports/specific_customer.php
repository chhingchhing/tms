<?php
require_once("report.php");
class Specific_customer extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(
			'summary' => array(
				array('data'=>lang('reports_sale_id'), 'align'=>'left'), 
				array('data'=>lang('reports_date'), 'align'=>'left'), 
				array('data'=>lang('reports_items_purchased'), 'align'=>'left'), 
				array('data'=>lang('reports_sold_by'), 'align'=>'left'), 
				array('data'=>lang('summary_reports_ticket_commissioner'), 'align'=> 'right'),
				array('data'=>lang('summary_reports_ticket_commissioner_price'), 'align'=> 'right'),
				array('data'=>lang('reports_subtotal'), 'align'=>'right'), 
				array('data'=>lang('reports_total'), 'align'=>'right'),  
				array('data'=>lang('reports_profit'), 'align'=>'right'), 
				array('data'=>lang('reports_payment_type'), 'align'=>'left'), 
				array('data'=>lang('reports_comments'), 'align'=>'left')
				),
			'details' => array(
				array('data'=>lang('reports_name'), 'align'=>'left'), 
				array('data'=>lang('reports_category'), 'align'=>'left'),
				array('data'=>lang('reports_serial_number'), 'align'=>'left'), 
				array('data'=>lang('reports_description'), 'align'=>'left'), 
				array('data'=>lang('reports_quantity_purchased'), 'align'=>'left'), 
				array('data'=>lang('reports_subtotal'), 'align'=>'right'), 
				array('data'=>lang('reports_total'), 'align'=>'right'), 
				array('data'=>lang('reports_profit'), 'align'=>'right'),
				array('data'=>lang('reports_discount'), 'align'=>'left')
				)
		);		
	}
	public function getDataColumnsSms()
	{
		return array(
			'summary' => array(
				array('data'=>lang('reports_sale_id'), 'align'=>'left'), 
				array('data'=>lang('reports_date'), 'align'=>'left'), 
				array('data'=>lang('reports_items_purchased'), 'align'=>'left'), 
				array('data'=>lang('reports_sold_by'), 'align'=>'left'), 
				array('data'=>lang('summary_reports_ticket_commissioner'), 'align'=> 'right'),
				array('data'=>lang('summary_reports_ticket_commissioner_price'), 'align'=> 'right'),
				array('data'=>lang('reports_subtotal'), 'align'=>'right'), 
				array('data'=>lang('reports_total'), 'align'=>'right'),  
				array('data'=>lang('reports_profit'), 'align'=>'right'), 
				array('data'=>lang('reports_payment_type'), 'align'=>'left'), 
				array('data'=>lang('reports_comments'), 'align'=>'left')
				),
			'details' => array(
				array('data'=>lang('reports_name'), 'align'=>'left'), 
//				array('data'=>lang('reports_category'), 'align'=>'left'),
                                array('data'=>lang('summary_reports_massage_time_in'), 'align'=> 'left'),
                                array('data'=>lang('summary_reports_massage_time_out'), 'align'=> 'left'),
				array('data'=>lang('summary_reports_massage_qty'), 'align'=> 'left'),  
                            array('data'=>lang('summary_reports_ticket_commissioner_price'), 'align'=> 'right'),
				array('data'=>lang('reports_subtotal'), 'align'=>'right'), 
				array('data'=>lang('reports_total'), 'align'=>'right'), 
				array('data'=>lang('reports_profit'), 'align'=>'right'),
				array('data'=>lang('reports_discount'), 'align'=>'left')
				)
		);		
	}
	
	public function getData()
	{
		$this->db->select('ID, sale_date, sale_time, issue_date, sum(quantity_purchased) as items_purchased, 
			CONCAT(customer.first_name," ",customer.last_name) as employee_name, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, 
			payment_type, comment, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name, commision_price', false);
		$this->db->from('sales_tickets_temp');
		$this->db->join('people as customer', 'sales_tickets_temp.employee_id = customer.person_id');
		$this->db->join('commissioners as commissioner', 'sales_tickets_temp.commisioner_id = commissioner.commisioner_id', 'left');
		$this->db->where('customer_id = '.$this->params['customer_id']);
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('sales_tickets_temp.deleted', 0);
		$this->db->group_by('ID');
		$this->db->order_by('sale_date');

		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		
		foreach($data['summary'] as $key=>$value)
		{
			$this->db->select('tickets.ticket_name as item_name, item_kits.name as item_kit_name, sales_tickets_temp.description, quantity_purchased, subtotal,total, profit, discount_percent');
			$this->db->from('sales_tickets_temp');
			$this->db->join('tickets', 'sales_tickets_temp.ticket_id = tickets.ticket_id', 'left');
			$this->db->join('item_kits', 'sales_tickets_temp.item_kit_id = item_kits.item_kit_id', 'left');
			$this->db->where('ID = '.$value['ID']);
			$data['details'][$key] = $this->db->get()->result_array();
		}
		
		return $data;
	}
	public function getDataSms()
	{
		$this->db->select('ID, sale_date, sale_time, issue_date, sum(quantity_purchased) as items_purchased, 
			CONCAT(customer.first_name," ",customer.last_name) as employee_name, sum(subtotal) as subtotal, time_in, time_out, sum(total) as total, sum(profit) as profit,, sum(profit_inclod_com_price),sum(unit_price) as cost_price, sum(commision_price) as total_com_price, 
			payment_type, comment, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name, commision_price', false);
		$this->db->from('sales_massages_temp');
		$this->db->join('people as customer', 'sales_massages_temp.employee_id = customer.person_id');
		$this->db->join('commissioners as commissioner', 'sales_massages_temp.commisioner_id = commissioner.commisioner_id', 'left');
		$this->db->where('customer_id = '.$this->params['customer_id']);
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('sales_massages_temp.deleted', 0);
		$this->db->group_by('ID');
		$this->db->order_by('sale_date');

		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		
		foreach($data['summary'] as $key=>$value)
		{
			$this->db->select('items_massages.massage_name as massage_name, quantity_purchased, subtotal,total, time_in, time_out, profit, discount_percent, profit_inclod_com_price');
			$this->db->from('sales_massages_temp');
			$this->db->join('items_massages', 'sales_massages_temp.item_massage_id = items_massages.item_massage_id', 'left');
//			$this->db->join('item_kits', 'sales_tickets_temp.item_kit_id = item_kits.item_kit_id', 'left');
			$this->db->where('ID = '.$value['ID']);
			$data['details'][$key] = $this->db->get()->result_array();
		}
		
		return $data;
	}
	
	public function getSummaryDataSms()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(unit_price) as cost_price, sum(profit) as profit,sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price');
		$this->db->from('sales_massages_temp');
		$this->db->where('customer_id = '.$this->params['customer_id']);
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('deleted', 0);
		return $this->db->get()->row_array();
	}
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit');
		$this->db->from('sales_tickets_temp');
		$this->db->where('customer_id = '.$this->params['customer_id']);
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('deleted', 0);
		return $this->db->get()->row_array();
	}
}
?>