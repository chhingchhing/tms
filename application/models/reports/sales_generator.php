<?php
require_once("report.php");
class Sales_generator extends Report
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
								array('data'=>lang('reports_total'), 'align'=> 'right'), 
								array('data'=>lang('reports_profit'), 'align'=> 'right'),
								array('data'=>lang('reports_discount'), 'align'=> 'right')
								)
					);		
	}
	public function getDataColumnsSms()
	{
		return array(
				'summary' => array(
								array('data'=>lang('reports_sale_id'), 'align'=> 'left'), 
								array('data'=>lang('reports_date'), 'align'=> 'left'), 
								array('data'=>lang('sales_duration'), 'align'=> 'left'), 
								array('data'=>lang('reports_sold_by'), 'align'=> 'left'), 
								array('data'=>lang('reports_sold_to'), 'align'=> 'left'), 
								// array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
								array('data'=>lang('reports_total'), 'align'=> 'right'), 
								array('data'=>lang('reports_profit'), 'align'=> 'right'), 
								array('data'=>lang('reports_payment_type'), 'align'=> 'right'), 
								array('data'=>lang('reports_comments'), 'align'=> 'right'),
								array('data'=>lang('summary_reports_massage_massager'), 'align'=> 'right')
								),
				'details' => array(
								array('data'=>lang('reports_item_number'), 'align'=> 'left'), 
								array('data'=>lang('reports_name'), 'align'=> 'left'), 
								array('data'=>lang('sales_duration'), 'align'=> 'left'), 
                                array('data'=>lang('summary_reports_massage_commission_price'), 'align'=> 'right'),
								// array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
								array('data'=>lang('reports_total'), 'align'=> 'right'), 
								array('data'=>lang('reports_profit'), 'align'=> 'right'),
								array('data'=>lang('reports_discount'), 'align'=> 'right'),
								array('data'=>lang('summary_reports_massage_massager'), 'align'=> 'right'),
								array('data'=>lang('reports_supplier'), 'align'=> 'right'),
								)
					);		
	}
	public function getDataColumnsBikes()
	{
		return array(
				'summary' => array(
								array('data'=>lang('reports_sale_id'), 'align'=> 'left'), 
								array('data'=>lang('reports_date'), 'align'=> 'left'), 
								array('data'=>lang('summary_reports_bike_qty_day'), 'align'=> 'left'), 
								array('data'=>lang('reports_sold_by'), 'align'=> 'left'), 
								array('data'=>lang('reports_sold_to'), 'align'=> 'left'), 
								array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
								array('data'=>lang('reports_total'), 'align'=> 'right'), 
								array('data'=>lang('reports_profit'), 'align'=> 'right'), 
								array('data'=>lang('reports_payment_type'), 'align'=> 'right'), 
								array('data'=>lang('reports_comments'), 'align'=> 'right')
								),
				'details' => array(
								array('data'=>lang('reports_sale_id'), 'align'=> 'left'), 
								array('data'=>lang('reports_name'), 'align'=> 'left'), 
								array('data'=>lang('summary_reports_bike_qty_rent'), 'align'=> 'left'), 
                                array('data'=>lang('summary_reports_massage_commission_price'), 'align'=> 'right'),
								array('data'=>lang('reports_subtotal'), 'align'=> 'right'), 
								array('data'=>lang('reports_total'), 'align'=> 'right'), 
								array('data'=>lang('reports_profit'), 'align'=> 'right'),
								array('data'=>lang('reports_discount'), 'align'=> 'right'),
								)
					);		
	}
	
	public function getDataColumnsTours()
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
		if ($this->params['matched_items_only'])
		{
			// $this->db->select('sale_id, sale_time, sale_date, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, payment_type, comment', false);
			$this->db->select('ID, deposit, issue_date, time_departure, date_departure, seat_number, time_departure, hotel_name, 
         room_number, company_name, item_number, commisioner_id, employee_id, item_unit_price, sum(quantity_purchased) as items_purchased, 
         commision_price, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, 
         sale_date, '.$this->db->dbprefix('sales_tickets_temp').'.deleted,
         CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name,
         payment_type, comment', false
         );
			$this->db->from('sales_tickets_temp');
			$this->db->join('people as employee', 'sales_tickets_temp.employee_id = employee.person_id');
			$this->db->join('people as customer', 'sales_tickets_temp.customer_id = customer.person_id', 'left');			
			$this->_searchSalesQueryParams();
			$this->db->where('sales_tickets_temp.deleted', 0);
			$this->db->where('sales_tickets_temp.category', 'tickets');
			$this->db->group_by('ID');
			$this->db->order_by('sale_date');	

			$data = array();
			$data['summary'] = $this->db->get()->result_array();
			$data['details'] = array();
			foreach($data['summary'] as $key=>$value)
			{
				// $this->db->select('item_number, item_kit_number, items.name as item_name, item_kits.name as item_kit_name, sales_items_temp.category, quantity_purchased, quantity_purchased as items_purchased, serialnumber, sales_items_temp.description, subtotal,total, tax, profit, discount_percent');
				$this->db->select('ID, deposit, issue_date, time_departure, date_departure, seat_number, time_departure, hotel_name, 
         room_number, company_name, item_number, commisioner_id, employee_id, item_unit_price, quantity_purchased, sum(quantity_purchased) as items_purchased, 
         commision_price, subtotal, total, profit, discount_percent, descriptions, 
         ticket_name, sale_date, '.$this->db->dbprefix('sales_tickets_temp').'.deleted '
         );
				$this->db->from('sales_tickets_temp');
				$this->db->join('tickets', 'sales_tickets_temp.ticket_id = tickets.ticket_id', 'left');
				$this->db->join('item_kits', 'sales_tickets_temp.item_kit_id = item_kits.item_kit_id', 'left');
				$this->db->where('ID = '.$value['ID']);
				$this->db->order_by('sale_date');
				$this->_searchSalesQueryParams();
				$this->db->where('sales_tickets_temp.deleted', 0);
			$this->db->where('sales_tickets_temp.category', 'tickets');
				$this->db->order_by('sale_date');	

				$data['details'][$key] = $this->db->get()->result_array();
			}
			return $data;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIds();
			// $this->db->select('ID, sale_time, sale_date, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, payment_type, comment', false);
			$this->db->select('ID, sale_time, deposit, issue_date, time_departure, date_departure, seat_number, time_departure, hotel_name, 
         room_number, company_name, item_number, commisioner_id, employee_id, item_unit_price, sum(quantity_purchased) as items_purchased, 
         commision_price, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, 
         sale_date, '.$this->db->dbprefix('sales_tickets_temp').'.deleted,
         CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, payment_type, comment', false
         );
			$this->db->from('sales_tickets_temp');
			$this->db->join('people as employee', 'sales_tickets_temp.employee_id = employee.person_id');
			$this->db->join('people as customer', 'sales_tickets_temp.customer_id = customer.person_id', 'left');
			$this->db->where('sales_tickets_temp.deleted', 0);
			$this->db->where('sales_tickets_temp.category', 'tickets');
			if (!empty($sale_ids))
			{
				$this->db->where_in('ID', $sale_ids);
			}
			else
			{
				$this->db->where('ID', -1);
			}
			$this->db->group_by('ID');
			$this->db->order_by('sale_date');
		
		
			$data = array();
			$data['summary'] = $this->db->get()->result_array();
			$data['details'] = array();
			foreach($data['summary'] as $key=>$value)
			{
				$this->db->select('item_number, item_kit_number, tickets.ticket_name as item_name, item_kits.name as item_kit_name, quantity_purchased, sales_tickets_temp.description, subtotal,total, profit, discount_percent');
				$this->db->from('sales_tickets_temp');
				$this->db->join('tickets', 'sales_tickets_temp.ticket_id = tickets.ticket_id', 'left');
				$this->db->join('item_kits', 'sales_tickets_temp.item_kit_id = item_kits.item_kit_id', 'left');
				$this->db->where('ID = '.$value['ID']);
				$data['details'][$key] = $this->db->get()->result_array();
			}
			return $data;
		}
	}
	
	public function getDataSms()
	{
		if ($this->params['matched_items_only'])
		{
			$this->db->select('ID, sale_time, category, sale_date,commision_price, sum(quantity_purchased) as items_purchased,
			 CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, 
			 sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, payment_type, sum(profit_inclod_com_price),
			 sum(unit_price) as cost_price, sum(commision_price) as total_com_price, comment, massager_id, 
			 CONCAT(supplier.first_name," ",supplier.last_name) as supplier_name, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name', false);
			$this->db->from('sales_massages_temp');
			$this->db->join('people as employee', 'sales_massages_temp.employee_id = employee.person_id');
			$this->db->join('people as massager', 'sales_massages_temp.massager_id = massager.person_id');
			$this->db->join('people as customer', 'sales_massages_temp.customer_id = customer.person_id', 'left');			
			$this->db->join('people as supplier', 'sales_massages_temp.supplierID = supplier.person_id', 'left');			
			$this->_searchSalesQueryParamsSms();
			$this->db->where('sales_massages_temp.deleted', 0);
			$this->db->where('sales_massages_temp.category', 'massages');
			$this->db->group_by('ID');
			$this->db->order_by('sale_date');	

			$data = array();
			$data['summary'] = $this->db->get()->result_array();
			$data['details'] = array();
			foreach($data['summary'] as $key=>$value)
			{
				$this->db->select('items_massages.massage_name as item_name, quantity_purchased, quantity_purchased as items_purchased, serialnumber, subtotal,total, profit, discount_percent, commissioner_name, category');
				$this->db->from('sales_massages_temp');
				$this->db->join('items_massages', 'sales_massages_temp.item_massage_id = items_massages.item_massage_id', 'left');
//				$this->db->join('item_kits', 'sales_massages_temp.item_kit_id = item_kits.item_kit_id', 'left');
				$this->db->where('ID = '.$value['ID']);
				$this->db->order_by('ID');
				$this->_searchSalesQueryParamsSms();
				$this->db->where('sales_massages_temp.deleted', 0);
				$this->db->order_by('sale_date');	

				$data['details'][$key] = $this->db->get()->result_array();
			}
			return $data;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIdsSms();
			$this->db->select('ID, sale_time, sale_date, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, 
				CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, payment_type, 
				CONCAT(supplier.first_name," ",supplier.last_name) as supplier_name, massager_id, comment, commision_price, category', false);
			$this->db->from('sales_massages_temp');
			$this->db->join('people as employee', 'sales_massages_temp.employee_id = employee.person_id');
			$this->db->join('people as massager', 'sales_massages_temp.massager_id = massager.person_id', 'left');
			$this->db->join('people as customer', 'sales_massages_temp.customer_id = customer.person_id', 'left');
			$this->db->join('people as supplier', 'sales_massages_temp.supplierID = supplier.person_id', 'left');
			$this->db->where('sales_massages_temp.deleted', 0);
			$this->db->where('sales_massages_temp.category', 'massages');
			if (!empty($sale_ids))
			{
				$this->db->where_in('ID', $sale_ids);
			}
			else
			{
				$this->db->where('ID', -1);
			}
			$this->db->group_by('ID');
			$this->db->order_by('sale_date');
			$data = array();
			$data['summary'] = $this->db->get()->result_array();

			$data['details'] = array();
			foreach($data['summary'] as $key=>$value)
			{
				$this->db->select('ID, items_massages.massage_name as item_name, quantity_purchased, subtotal,total, profit, discount_percent');
				$this->db->from('sales_massages_temp');
				$this->db->join('items_massages', 'sales_massages_temp.item_massage_id = items_massages.item_massage_id', 'left');
//				$this->db->join('item_kits', 'sales_items_temp.item_kit_id = item_kits.item_kit_id', 'left');
				$this->db->where('ID = '.$value['ID']);
				$data['details'][$key] = $this->db->get()->result_array();
			}
			return $data;
		}
	}
	
	public function getSummaryDataSms()
	{
		if ($this->params['matched_items_only'])
		{
			$this->db->select('sales_massages_temp.ID,category, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit,sum(quantity_purchased) as items_purchased,sum(commision_price) as total_com_price');
			$this->db->from('sales_massages_temp');
			$this->db->where('sales_massages_temp.deleted', 0);
			$this->_searchSalesQueryParams();
			$this->db->group_by('sale_date');
			$result = $this->db->get()->result_array();
			
			$return = array('subtotal' => 0, 'total' => 0,'tax' => 0, 'profit' => 0);
			foreach($result as $row)
			{
				$return['subtotal']+=$row['subtotal'];
				$return['total']+=$row['total'];
				$return['tax']+=$row['tax'];
				$return['profit']+=$row['profit'];
			}
			return $return;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIdsSms();
			$this->db->select('sum(subtotal) as subtotal, sum(total) as total,sum(unit_price) as cost_price, sum(profit) as profit, sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price');
			$this->db->from('sales_massages_temp');
			if (!empty($sale_ids))
			{
				$this->db->where_in('ID', $sale_ids);
			}
			else
			{
				$this->db->where('ID', -1);
			}
			$result = $this->db->get()->row_array();
			return $result;
		}
	}
	public function getDataTours()
	{
		if ($this->params['matched_items_only'])
		{
			$this->db->select('ID, sale_time,departure_date,destination,item_name, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name, tour_descr, category, company_name, sale_date,commision_price, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, payment_type, sum(profit_inclod_com_price),sum(item_cost_price) as cost_price, sum(commision_price) as total_com_price, comment, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name', false);
			$this->db->from('sales_tours_temp');
			$this->db->join('people as employee', 'sales_tours_temp.employee_id = employee.person_id');
			$this->db->join('people as customer', 'sales_tours_temp.customer_id = customer.person_id', 'left');			
			$this->_searchSalesQueryParamsTours();
			$this->db->where('sales_tours_temp.deleted', 0);
			$this->db->group_by('ID');
			$this->db->order_by('sale_date');	

			$data = array();
			$data['summary'] = $this->db->get()->result_array();
			$data['details'] = array();
			foreach($data['summary'] as $key=>$value)
			{
				$this->db->select('tour_name,destination,item_name, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name, commissioner_name,quantity_purchased, quantity_purchased as items_purchased, subtotal,total, profit, discount_percent, commissioner_name, category');
				$this->db->from('sales_tours_temp');
				$this->db->join('tours', 'sales_tours_temp.tour_id = tours.tour_id', 'left');
//				$this->db->join('item_kits', 'sales_massages_temp.item_kit_id = item_kits.item_kit_id', 'left');
				$this->db->where('ID = '.$value['ID']);
				$this->db->order_by('ID');
				$this->_searchSalesQueryParamsTours();
				$this->db->where('sales_tours_temp.deleted', 0);
				$this->db->order_by('sale_date');	

				$data['details'][$key] = $this->db->get()->result_array();
			}
			return $data;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIdsTours();
			$this->db->select('ID, sale_time,item_name, sale_date,departure_date,destination,company_name,tour_descr,departure_time,tour_by,tour_descr, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, payment_type, comment, commision_price, category', false);
			$this->db->from('sales_tours_temp');
			$this->db->join('people as employee', 'sales_tours_temp.employee_id = employee.person_id');
			$this->db->join('people as customer', 'sales_tours_temp.customer_id = customer.person_id', 'left');
			$this->db->where('sales_tours_temp.deleted', 0);
			if (!empty($sale_ids))
			{
				$this->db->where_in('ID', $sale_ids);
			}
			else
			{
				$this->db->where('ID', -1);
			}
			$this->db->group_by('ID');
			$this->db->order_by('sale_date');
		
		
			$data = array();
			$data['summary'] = $this->db->get()->result_array();
			$data['details'] = array();
			foreach($data['summary'] as $key=>$value)
			{
				$this->db->select('ID, item_name, quantity_purchased, subtotal,total, profit, discount_percent');
				$this->db->from('sales_tours_temp');
				$this->db->join('tours', 'sales_tours_temp.tour_id = tours.tour_id', 'left');
//				$this->db->join('item_kits', 'sales_items_temp.item_kit_id = item_kits.item_kit_id', 'left');
				$this->db->where('ID = '.$value['ID']);
				$data['details'][$key] = $this->db->get()->result_array();
			}
			return $data;
		}
	}
	
	public function getSummaryDataTours()
	{
		if ($this->params['matched_items_only'])
		{
			$this->db->select('sales_tours_temp.ID,category, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit,sum(quantity_purchased) as items_purchased,sum(commision_price) as total_com_price');
			$this->db->from('sales_tours_temp');
			$this->db->where('sales_tours_temp.deleted', 0);
			$this->_searchSalesQueryParams();
			$this->db->group_by('sale_date');
			$result = $this->db->get()->result_array();
			
			$return = array('subtotal' => 0, 'total' => 0,'tax' => 0, 'profit' => 0);
			foreach($result as $row)
			{
				$return['subtotal']+=$row['subtotal'];
				$return['total']+=$row['total'];
				$return['tax']+=$row['tax'];
				$return['profit']+=$row['profit'];
			}
			return $return;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIdsTours();
			$this->db->select('sum(subtotal) as subtotal, sum(total) as total,sum(item_cost_price) as cost_price, sum(profit) as profit, sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price');
			$this->db->from('sales_tours_temp');
			if (!empty($sale_ids))
			{
				$this->db->where_in('ID', $sale_ids);
			}
			else
			{
				$this->db->where('ID', -1);
			}
			$result = $this->db->get()->row_array();
			return $result;
		}
	}
        private function _getMatchingSaleIdsTours()
	{
		$this->db->select('ID, sum(quantity_purchased) as items_purchased, sum(total) as total', false);
		$this->db->from('sales_tours_temp');
		$this->_searchSalesQueryParams();
		$this->db->where('sales_tours_temp.deleted', 0);
		$this->db->group_by('ID');
		$this->db->order_by('sale_date');		
		$sales_matches = $this->db->get()->result_array();
		$sale_ids = array();
		foreach($sales_matches as $sale_match)
		{
			$sale_ids[] = $sale_match['ID'];
		}
		
		return $sale_ids;
	}
	public function getDataBikes()
	{
		if ($this->params['matched_items_only'])
		{
			$this->db->select('ID, sale_time, category, sale_date,commision_price, sum(number_of_day) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, payment_type, sum(profit_inclod_com_price),sum(actual_price) as cost_price, sum(commision_price) as total_com_price, comment, CONCAT(commissioner.first_name," ",commissioner.last_name) as commissioner_name', false);
			$this->db->from('sales_bikes_temp');
			$this->db->join('people as employee', 'sales_bikes_temp.employee_id = employee.person_id');
			$this->db->join('people as customer', 'sales_bikes_temp.customer_id = customer.person_id', 'left');			
			$this->_searchSalesQueryParamsBikes();
			$this->db->where('sales_bikes_temp.deleted', 0);
			$this->db->group_by('ID');
			$this->db->order_by('sale_date');	

			$data = array();
			$data['summary'] = $this->db->get()->result_array();
			$data['details'] = array();
			foreach($data['summary'] as $key=>$value)
			{
				$this->db->select('items_bikes.bike_types as item_name, quantity_of_bike, number_of_day as items_purchased, subtotal,total, profit, discount_percent, commissioner_name, category');
				$this->db->from('sales_bikes_temp');
				$this->db->join('items_bikes', 'sales_bikes_temp.item_bikeID = items_bikes.item_bike_id', 'left');
//				$this->db->join('item_kits', 'sales_massages_temp.item_kit_id = item_kits.item_kit_id', 'left');
				$this->db->where('ID = '.$value['ID']);
				$this->db->order_by('ID');
				$this->_searchSalesQueryParamsBikes();
				$this->db->where('sales_bikes_temp.deleted', 0);
				$this->db->order_by('sale_date');	

				$data['details'][$key] = $this->db->get()->result_array();
			}
			return $data;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIdsBikes();
			$this->db->select('ID, sale_time, sale_date, sum(number_of_day) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(customer.first_name," ",customer.last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, payment_type, comment, commision_price, category', false);
			$this->db->from('sales_bikes_temp');
			$this->db->join('people as employee', 'sales_bikes_temp.employee_id = employee.person_id');
			$this->db->join('people as customer', 'sales_bikes_temp.customer_id = customer.person_id', 'left');
			$this->db->where('sales_bikes_temp.deleted', 0);
			if (!empty($sale_ids))
			{
				$this->db->where_in('ID', $sale_ids);
			}
			else
			{
				$this->db->where('ID', -1);
			}
			$this->db->group_by('ID');
			$this->db->order_by('sale_date');
		
		
			$data = array();
			$data['summary'] = $this->db->get()->result_array();
			$data['details'] = array();
			foreach($data['summary'] as $key=>$value)
			{
				$this->db->select('ID, items_bikes.bike_types as item_name, quantity_of_bike, subtotal,total, profit, discount_percent');
				$this->db->from('sales_bikes_temp');
				$this->db->join('items_bikes', 'sales_bikes_temp.item_bikeID = items_bikes.item_bike_id', 'left');
//				$this->db->join('item_kits', 'sales_items_temp.item_kit_id = item_kits.item_kit_id', 'left');
				$this->db->where('ID = '.$value['ID']);
				$data['details'][$key] = $this->db->get()->result_array();
			}
			return $data;
		}
	}
	
	public function getSummaryDataBikes()
	{
		if ($this->params['matched_items_only'])
		{
			$this->db->select('sales_bikes_temp.ID,category, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit,sum(number_of_day) as items_purchased,sum(commision_price) as total_com_price');
			$this->db->from('sales_bikes_temp');
			$this->db->where('sales_bikes_temp.deleted', 0);
			$this->_searchSalesQueryParams();
			$this->db->group_by('sale_date');
			$result = $this->db->get()->result_array();
			
			$return = array('subtotal' => 0, 'total' => 0,'tax' => 0, 'profit' => 0);
			foreach($result as $row)
			{
				$return['subtotal']+=$row['subtotal'];
				$return['total']+=$row['total'];
				$return['tax']+=$row['tax'];
				$return['profit']+=$row['profit'];
			}
			return $return;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIdsBikes();
			$this->db->select('sum(subtotal) as subtotal, sum(total) as total,sum(act) as cost_price, sum(profit) as profit, sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price');
			$this->db->from('sales_bikes_temp');
			if (!empty($sale_ids))
			{
				$this->db->where_in('ID', $sale_ids);
			}
			else
			{
				$this->db->where('ID', -1);
			}
			$result = $this->db->get()->row_array();
			return $result;
		}
	}
	public function getSummaryData()
	{
		if ($this->params['matched_items_only'])
		{
			// $this->db->select('sales_items_temp.sale_id, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit,sum(quantity_purchased) as items_purchased');
			$this->db->select('ID, deposit, issue_date, time_departure, date_departure, seat_number, time_departure, hotel_name, 
         room_number, company_name, item_number, commisioner_id, employee_id, item_unit_price, quantity_purchased, 
         commision_price, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, 
         sale_date, '.$this->db->dbprefix('sales_tickets_temp').'.deleted '
         );
			$this->db->from('sales_tickets_temp');
			$this->db->where('sales_tickets_temp.deleted', 0);
			$this->db->where('sales_tickets_temp.category', 'tickets');
			$this->_searchSalesQueryParams();
			$this->db->group_by('ID');
			$result = $this->db->get()->result_array();
			
			$return = array('subtotal' => 0, 'total' => 0, 'profit' => 0);
			foreach($result as $row)
			{
				$return['subtotal']+=$row['subtotal'];
				$return['total']+=$row['total'];
				$return['profit']+=$row['profit'];
			}
			return $return;
		}
		else
		{
			$sale_ids = $this->_getMatchingSaleIds();
			$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit');
			$this->db->from('sales_tickets_temp');
			if (!empty($sale_ids))
			{
				$this->db->where_in('ID', $sale_ids);
			}
			else
			{
				$this->db->where('ID', -1);
			}
			$result = $this->db->get()->row_array();
			return $result;
		}
	}
	
	private function _getMatchingSaleIds()
	{
		$this->db->select('ID, sum(quantity_purchased) as items_purchased, sum(total) as total', false);
		$this->db->from('sales_tickets_temp');
		$this->_searchSalesQueryParams();
		$this->db->where('sales_tickets_temp.deleted', 0);
		$this->db->where('sales_tickets_temp.category', 'tickets');
		$this->db->group_by('ID');
		$this->db->order_by('sale_date');		
		$sales_matches = $this->db->get()->result_array();
		$sale_ids = array();
		foreach($sales_matches as $sale_match)
		{
			$sale_ids[] = $sale_match['ID'];
		}
		return $sale_ids;
	}
	private function _getMatchingSaleIdsSms()
	{
		$this->db->select('ID, sum(quantity_purchased) as items_purchased, sum(total) as total', false);
		$this->db->from('sales_massages_temp');
		$this->_searchSalesQueryParams();
		$this->db->where('sales_massages_temp.deleted', 0);
		$this->db->group_by('ID');
		$this->db->order_by('sale_date');		
		$sales_matches = $this->db->get()->result_array();
		$sale_ids = array();
		foreach($sales_matches as $sale_match)
		{
			$sale_ids[] = $sale_match['ID'];
		}
		return $sale_ids;
	}
	
	private function _getMatchingSaleIdsBikes()
	{
		$this->db->select('ID, sum(number_of_day) as items_purchased, sum(total) as total', false);
		$this->db->from('sales_bikes_temp');
		$this->_searchSalesQueryParams();
		$this->db->where('sales_bikes_temp.deleted', 0);
		$this->db->group_by('ID');
		$this->db->order_by('sale_date');		
		$sales_matches = $this->db->get()->result_array();
		$sale_ids = array();
		foreach($sales_matches as $sale_match)
		{
			$sale_ids[] = $sale_match['ID'];
		}
		
		return $sale_ids;
	}
	
	private function _searchSalesQueryParams()
	{
		$matchType = 'where';
		if ($this->params['matchType'] == 'matchType_Or') 
		{
			$matchType = 'or_where';			
		}
		
		if ($this->params['values'][0]['f'] != 0) 
		{
			foreach ($this->params['values'] as $w => $d) 
			{
				$ops = $this->params['ops'][$d['o']]; // Condition Operator
				if (count($d['i']) > 1) 
				{
					if ($d['o'] == 1) { $ops = $this->params['ops'][5]; }
					if ($d['o'] == 2) { $ops = $this->params['ops'][6]; }
				}

				if  ($d['f'] == 6 && $d['o'] == 10) 
				{ 
					// Sale Type
					$this->db->or_having('items_purchased > 0');
				} 
				elseif ($d['f'] == 6 && $d['o'] == 11) 
				{ 
					// Returns
					$this->db->or_having('items_purchased < 0');
				} 
				elseif ($d['f'] == 7) 
				{ 
					// Sale Amount
					if ($this->params['matchType'] == 'matchType_All')
					{
						$this->db->having('total '.str_replace("xx", join(", ", $d['i']), $ops));				
					}
					elseif($this->params['matchType'] == 'matchType_Or')
					{
						$this->db->or_having('total '.str_replace("xx", join(", ", $d['i']), $ops));				
					}
				}
				elseif($d['f'] == 11)
				{
					//Payment type
					foreach($d['i'] as $payment_type)
					{
						$this->db->or_like($this->params['tables'][$d['f']], $payment_type);
					}
				}
				else 
				{
					$this->db->{$matchType}($this->params['tables'][$d['f']].' '.str_replace("xx", join("', '", $d['i']), $ops));
				}
			}
		}
	}
}
?>