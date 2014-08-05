<?php
require_once("report.php");
class Detailed_sales extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(
			'summary' => array(
				array('data'=>lang('reports_sale_id'), 'align'=> 'left'), 
				array('data'=>lang('reports_date'), 'align'=> 'left'), 
				array('data'=>lang('reports_items_purchased'), 'align'=> 'left'), 
				array('data'=>lang('reports_sold_by'), 'align'=> 'left'), 
				array('data'=>lang('reports_sold_to'), 'align'=> 'left'), 
				array('data'=>lang('summary_reports_ticket_commissioner'), 'align'=> 'right'),
				array('data'=>lang('summary_reports_ticket_commissioner_price'), 'align'=> 'right'),
				array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
				array('data'=>lang('reports_total'), 'align'=> 'right'), 
				array('data'=>lang('reports_profit'), 'align'=> 'right'), 
				array('data'=>lang('reports_payment_type'), 'align'=> 'right'), 
				array('data'=>lang('reports_comments'), 'align'=> 'right')
				),
			'details' => array(
				array('data'=>lang('reports_item_number'), 'align'=> 'left'), 
				array('data'=>lang('reports_name'), 'align'=> 'left'),  
				array('data'=>lang('reports_description'), 'align'=> 'left'), 
				array('data'=>lang('reports_quantity_purchased'), 'align'=> 'left'), 
				array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
				array('data'=>lang('sales_deposit'), 'align'=> 'right'), 
				array('data'=>lang('sales_balance'), 'align'=> 'right'), 
				array('data'=>lang('reports_total'), 'align'=> 'right'), 
				array('data'=>lang('reports_profit'), 'align'=> 'right'),
				array('data'=>lang('reports_discount'), 'align'=> 'right')
				)
		);		
	}
	
	public function getData()
	{
		/*$result = mysql_query("SHOW COLUMNS FROM cgate_sales_tickets_temp");
if (!$result) {
  echo 'Impossible d\'exécuter la requête : ' . mysql_error();
  exit;
}
if (mysql_num_rows($result) > 0) {
  while ($row = mysql_fetch_assoc($result)) {
     print_r($row);
  }
}*/

		$this->db->select('deposit, ID, sale_date, issue_date, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, 
			customer.person_id as customer_id, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(profit_inclod_com_price),sum(item_cost_price) as cost_price, sum(commision_price) as total_com_price, 
			sum(total) as total, sum(profit) as profit, payment_type, comment, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name, commision_price', false);
		$this->db->from('sales_tickets_temp');
		$this->db->join('people as employee', 'sales_tickets_temp.employee_id = employee.person_id');
		$this->db->join('people as customer', 'sales_tickets_temp.customer_id = customer.person_id', 'left');
		$this->db->join('commissioners as commissioner', 'sales_tickets_temp.commisioner_id = commissioner.commisioner_id', 'left');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where($this->db->dbprefix('sales_tickets_temp').'.deleted', 0);
		$this->db->group_by('ID');
		$this->db->order_by('sale_date');

		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		foreach($data['summary'] as $key=>$value)
		{
			$this->db->select('item_number, item_kit_number, tickets.ticket_name as item_name, item_kits.name as item_kit_name, quantity_purchased, sales_tickets_temp.description, subtotal,total, profit, discount_percent, deposit,profit_inclod_com_price');
			$this->db->from('sales_tickets_temp');
			$this->db->join('tickets', 'sales_tickets_temp.ticket_id = tickets.ticket_id', 'left');
			$this->db->join('item_kits', 'sales_tickets_temp.item_kit_id = item_kits.item_kit_id', 'left');
			$this->db->where('ID = '.$value['ID']);
			$data['details'][$key] = $this->db->get()->result_array();
		}
		
		return $data;
	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total,sum(item_cost_price) as cost_price, sum(profit) as profit,sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price');
		$this->db->from('sales_tickets_temp');
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