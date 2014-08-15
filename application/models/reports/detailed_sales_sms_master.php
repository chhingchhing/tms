<?php
require_once("report.php");
class Detailed_sales_sms_master extends Report
{
	function __construct()
	{
		parent::__construct();
	}

	public function getDataColumnsMassages()
	{
		return array(
			'details' => array(
				array('data'=>lang('reports_name'), 'align'=>'left'), 
                array('data'=>lang('summary_reports_massage_time_in'), 'align'=> 'left'),
                array('data'=>lang('summary_reports_massage_time_out'), 'align'=> 'left'),
				array('data'=>lang('summary_reports_massage_qty'), 'align'=> 'left'),  
                array('data'=>lang('summary_reports_ticket_commissioner_price'), 'align'=> 'right'),
				// array('data'=>lang('reports_subtotal'), 'align'=>'right'), 
				array('data'=>lang('reports_total'), 'align'=>'right'), 
				array('data'=>lang('reports_profit'), 'align'=>'right'),
				array('data'=>lang('reports_discount'), 'align'=>'left'),
				array('data'=>lang('summary_reports_massage_office'), 'align'=> 'right'),
				// array('data'=>lang('summary_reports_massage_massager'), 'align'=> 'right')
				)
		);
	}

	public function getDataColumns()
	{
		return array(
			'summary' => array(
				array('data'=>$this->params['condition_master'] == 'commissioner' ? lang('summary_reports_massage_commissioner') : lang('summary_reports_massage_massager'), 'align'=> 'right'),
				array('data'=>lang('summary_reports_massage_qty'), 'align'=> 'left'),  
				array('data'=>lang('summary_reports_ticket_commissioner_price'), 'align'=> 'right'),
				array('data'=>lang('reports_total'), 'align'=> 'right')
				),
			'details' => array(
				array('data'=>lang('reports_name'), 'align'=> 'left'),  
                array('data'=>lang('reports_date'), 'align'=> 'left'),
                array('data'=>lang('summary_reports_massage_time_in'), 'align'=> 'left'),
                array('data'=>lang('summary_reports_massage_time_out'), 'align'=> 'left'),
				array('data'=>lang('summary_reports_massage_qty'), 'align'=> 'left'),
				// array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
				array('data'=>lang('reports_total'), 'align'=> 'right'), 
				array('data'=>lang('reports_profit'), 'align'=> 'right'),
				array('data'=>lang('reports_discount'), 'align'=> 'right'),
				// array('data'=>lang('summary_reports_massage_massager'), 'align'=> 'right'),
				array('data'=>lang('summary_reports_massage_office'), 'align'=> 'right')
				)
		);		
	}

	public function getDataColumnsRecept()
	{
		return array(
			'summary' => array(
				array('data'=>lang('reports_receptionist'), 'align'=> 'right'),
				array('data'=>lang('summary_reports_massage_qty'), 'align'=> 'left'),  
				array('data'=>lang('summary_reports_ticket_commissioner_price'), 'align'=> 'right'),
				array('data'=>lang('reports_total'), 'align'=> 'right')
				),
			'details' => array(
				array('data'=>lang('reports_name'), 'align'=> 'left'),  
                array('data'=>lang('reports_date'), 'align'=> 'left'),
                array('data'=>lang('summary_reports_massage_time_in'), 'align'=> 'left'),
                array('data'=>lang('summary_reports_massage_time_out'), 'align'=> 'left'),
				array('data'=>lang('summary_reports_massage_qty'), 'align'=> 'left'),
				// array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
				array('data'=>lang('reports_total'), 'align'=> 'right'), 
				array('data'=>lang('reports_profit'), 'align'=> 'right'),
				array('data'=>lang('reports_discount'), 'align'=> 'right'),
				// array('data'=>lang('summary_reports_massage_massager'), 'align'=> 'right'),
				array('data'=>lang('summary_reports_massage_office'), 'align'=> 'right')
				)
		);		
	}
	
	public function getData()
	{
		$this->db->select('ID, discount_percent, office, issue_date, time_in, time_out, sum(commission_massager) as total_commission_price, sum(commision_price) as total_commissioner_price, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, employee_id, massager_id, '.$this->db->dbprefix('sales_massages_temp').'.commisioner_id, sum(subtotal) as subtotal, sum(profit_inclod_com_price), 
			sum(total) as total, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name', false);
		$this->db->from('sales_massages_temp'); 
		if ($this->params['condition_master'] == 'massager') {
			$this->db->join('people as employee', 'sales_massages_temp.massager_id = employee.person_id');
			$this->db->join('commissioners as commissioner', 'sales_massages_temp.commisioner_id = commissioner.commisioner_id', 'left'); 


		} else if ($this->params['condition_master'] == 'commissioner') {
			$this->db->join('commissioners as commissioner', 'sales_massages_temp.commisioner_id = commissioner.commisioner_id');
			$this->db->join('people as employee', 'sales_massages_temp.employee_id = employee.person_id', 'left');


		} else { // condition_master == receptionist
			// $this->db->join('people as employee', 'sales_massages_temp.employee_id = employee.person_id', 'left');
			$this->db->join('commissioners as commissioner', 'sales_massages_temp.commisioner_id = commissioner.commisioner_id', 'left'); 
		}


		/*$this->db->join('commissioners as commissioner', 'sales_massages_temp.commisioner_id = commissioner.commisioner_id', 'left'); 
		$this->db->join('people as employee', 'sales_massages_temp.employee_id = employee.person_id', 'left');
		$this->db->join('people as employee', 'sales_massages_temp.massager_id = employee.person_id', 'left');*/

// var_dump($this->db->get()->result_array()); die();

		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where($this->db->dbprefix('sales_massages_temp').'.deleted', 0);
		if ($this->params['condition_master'] == 'massager') {
			$this->db->group_by('massager_id');
		}


		if ($this->params['condition_master'] == 'commissioner') {
			$this->db->group_by('commisioner_id');
		}

		
		$this->db->order_by('office');

		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();

/*echo "string";
		var_dump($data['summary']); die();*/


		foreach($data['summary'] as $key=>$value)
		{       
			$this->db->select('customer_id, office, issue_date, time_in, time_out, massage_name, items_massages.massage_name as name_of_massage, quantity_purchased, subtotal,total, profit, discount_percent, profit_inclod_com_price');
			$this->db->from('sales_massages_temp');
			$this->db->join('items_massages', 'sales_massages_temp.item_massage_id = items_massages.item_massage_id', 'left');
			$this->db->join('people as employee', 'sales_massages_temp.employee_id = employee.person_id');
			$this->db->join('people as customer', 'sales_massages_temp.customer_id = customer.person_id', 'left');

			if ($this->params['condition_master'] == 'massager') {
				$this->db->where('massager_id = '.$value['massager_id']);
			}


			if ($this->params['condition_master'] == 'commissioner') {
				$this->db->where('commisioner_id = '.$value['commisioner_id']);
			}

// var_dump($this->db->get()->result_array()); die();
			$data['details'][$key] = $this->db->get()->result_array();
		}
		return $data;
	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(total) as total, sum(unit_price) as cost_price, sum(profit) as profit,sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price');

		$this->db->from('sales_massages_temp');

		if ($this->params['condition_master'] == 'massager') {
			$this->db->join('people as employee', 'sales_massages_temp.massager_id = employee.person_id');
		} else {
			$this->db->join('people as employee', 'sales_massages_temp.massager_id = employee.person_id', 'left');
		}

		$this->db->join('commissioners as commissioner', 'sales_massages_temp.commisioner_id = commissioner.commisioner_id', 'left');


		if ($this->params['condition_master'] == 'receptionist') {
			$this->db->where('discount_percent', 0);
		}
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where($this->db->dbprefix('sales_massages_temp').'.deleted', 0);
		return $this->db->get()->row_array();
	}

	// Specific massager
	// Get massage record master with massager_id

	function getDataMassagerMaster() {
		$this->db->select('ID, office, sale_date, sale_time, issue_date, time_in, time_out, commision_price, customer_id, massage_name, items_massages.massage_name as name_of_massage, quantity_purchased, subtotal,total, profit, discount_percent, profit_inclod_com_price');
			$this->db->from('sales_massages_temp');
			$this->db->join('items_massages', 'sales_massages_temp.item_massage_id = items_massages.item_massage_id', 'left');
			$this->db->join('people as employee', 'sales_massages_temp.employee_id = employee.person_id');
			$this->db->join('people as customer', 'sales_massages_temp.customer_id = customer.person_id', 'left');
			// $this->db->where('ID = '.$value['ID']);
			if ($this->params['condition_master'] == 'receptionist') {
				$this->db->where('employee_id = '.$this->params['employee_id']);
			} else {
				$this->db->where('massager_id = '.$this->params['massager_id']);
			}

			$data['details'][$key] = $this->db->get()->result_array();

		return $data;
	}

	public function getSummaryDataSms()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(unit_price) as cost_price, sum(profit) as profit,sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price');
		$this->db->from('sales_massages_temp');

		if ($this->params['condition_master'] == 'receptionist') {
			$this->db->where('employee_id = '.$this->params['employee_id']);
		} else {
			$this->db->where('massager_id = '.$this->params['massager_id']);
		}

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