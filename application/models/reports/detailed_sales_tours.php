<?php
require_once("report.php");
class Detailed_sales_tours extends Report
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
				array('data'=>lang('summary_reports_massage_qty'), 'align'=> 'left'), 
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
				array('data'=>lang('tours_tour_name'), 'align'=> 'left'),  
                array('data'=>lang('tours_destination_name'), 'align'=> 'left'),
                array('data'=>lang('tours_departure_date'), 'align'=> 'left'),
                array('data'=>lang('tours_departure_time'), 'align'=> 'left'),
                array('data'=>lang('tours_by'), 'align'=> 'left'),
                array('data'=>lang('tours_supplier'), 'align'=> 'left'),
				array('data'=>lang('sales_quantity'), 'align'=> 'left'),
				array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
				array('data'=>lang('reports_total'), 'align'=> 'right'), 
				array('data'=>lang('reports_profit'), 'align'=> 'right'),
				array('data'=>lang('reports_discount'), 'align'=> 'right'),
				array('data'=>lang('tours_desc'), 'align'=> 'right')
				)
		);		
	}
	
	public function getData()
	{
		$this->db->select('ID, sale_date, issue_date,destination ,tour_deposit, tour_by,tour_descr, company_name, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, departure_date, departure_time, issue_date,
			customer.person_id as customer_id, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(profit_inclod_com_price),sum(item_cost_price) as cost_price, sum(commision_price) as total_com_price, 
			sum(total) as total, sum(profit) as profit, payment_type, comment, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name, commision_price', false);
		$this->db->from('sales_tours_temp');            
		$this->db->join('people as employee', 'sales_tours_temp.employee_id = employee.person_id');          
		$this->db->join('people as customer', 'sales_tours_temp.customer_id = customer.person_id', 'left');
		$this->db->join('commissioners as commissioner', 'sales_tours_temp.commisioner_id = commissioner.commisioner_id', 'left');
        
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where($this->db->dbprefix('sales_tours_temp').'.deleted', 0);
		$this->db->group_by('ID');
		$this->db->order_by('sale_date');
                
		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		foreach($data['summary'] as $key=>$value)
		{       
			$this->db->select('tour_name,quantity_purchased, subtotal,total, profit, discount_percent, profit_inclod_com_price');
			$this->db->from('sales_tours_temp');
			$this->db->join('tours', 'sales_tours_temp.tour_id = tours.tour_id', 'left');
			$this->db->where('ID = '.$value['ID']);
			$data['details'][$key] = $this->db->get()->result_array();
		}
		return $data;
	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(item_cost_price) as cost_price, sum(profit) as profit,sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price');
		$this->db->from('sales_tours_temp');
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