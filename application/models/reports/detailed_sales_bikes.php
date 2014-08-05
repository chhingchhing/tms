<?php
require_once("report.php");
class Detailed_sales_bikes extends Report
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
				array('data'=>lang('summary_reports_bike_qty_rent'), 'align'=> 'left'), 
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
//				array('data'=>lang('reports_item_number'), 'align'=> 'left'), 
				array('data'=>lang('reports_name'), 'align'=> 'left'),  
                                array('data'=>lang('reports_date'), 'align'=> 'left'),
//				array('data'=>lang('reports_description'), 'align'=> 'left'), 
                                array('data'=>lang('summary_reports_bike_date_rent'), 'align'=> 'left'),
                                array('data'=>lang('summary_reports_bike_date_return'), 'align'=> 'left'),
				array('data'=>lang('summary_reports_bike_qty_day'), 'align'=> 'left'),
				array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
				array('data'=>lang('reports_total'), 'align'=> 'right'), 
				array('data'=>lang('reports_profit'), 'align'=> 'right'),
				array('data'=>lang('reports_discount'), 'align'=> 'right')
			)
		);		
	}
	
	public function getData()
	{
		$this->db->select('ID, sale_date, issue_date, sum(quantity_of_bike) as items_purchased, number_of_day, CONCAT(employee.first_name," ",employee.last_name) as employee_name, date_time_in, date_time_out, issue_date,
			customer.person_id as customer_id, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(profit_inclod_com_price),sum(act) as cost_price, sum(commision_price) as total_com_price, 
			sum(total) as total, sum(profit) as profit, payment_type, comment, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name, commision_price', false);
		$this->db->from('sales_bikes_temp');
              
		$this->db->join('people as employee', 'sales_bikes_temp.employee_id = employee.person_id');
           
		$this->db->join('people as customer', 'sales_bikes_temp.customer_id = customer.person_id', 'left');
		$this->db->join('commissioners as commissioner', 'sales_bikes_temp.commisioner_id = commissioner.commisioner_id', 'left');
        
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_of_bike > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_of_bike < 0');
		}
		$this->db->where($this->db->dbprefix('sales_bikes_temp').'.deleted', 0);
		$this->db->group_by('ID');
		$this->db->order_by('sale_date');
                
		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		foreach($data['summary'] as $key=>$value)
		{       
			$this->db->select('bike_types, items_bikes.bike_types as bike_types,number_of_day, quantity_of_bike, subtotal,total, profit, discount_percent, profit_inclod_com_price');
			$this->db->from('sales_bikes_temp');
			$this->db->join('items_bikes', 'sales_bikes_temp.item_bikeID = items_bikes.item_bike_id', 'left');
			$this->db->where('ID = '.$value['ID']);
			$data['details'][$key] = $this->db->get()->result_array();
		}
		return $data;
	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(act) as cost_price, sum(profit) as profit,sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price');
		$this->db->from('sales_bikes_temp');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_of_bike > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_of_bike < 0');
		}
		$this->db->where('deleted', 0);
		return $this->db->get()->row_array();
	}
}
?>