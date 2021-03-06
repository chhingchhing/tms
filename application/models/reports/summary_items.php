<?php
require_once("report.php");
class Summary_items extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_item'), 'align'=> 'left'),array('data'=>lang('reports_quantity'), 'align'=> 'center'),array('data'=>lang('reports_quantity_purchased'), 'align'=> 'left'), array('data'=>lang('reports_subtotal'), 'align'=> 'right'), array('data'=>lang('reports_total'), 'align'=> 'right'), array('data'=>lang('reports_tax'), 'align'=> 'right'),array('data'=>lang('reports_profit'), 'align'=> 'right'));
	}
	
	public function getData()
	{
		$this->db->select('name, items.quantity , sum(quantity_purchased) as quantity_purchased, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit');
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'sales_items_temp.item_id = items.item_id');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		$this->db->group_by('items.item_id');
		$this->db->order_by('name');

		return $this->db->get()->result_array();		
	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit');
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'sales_items_temp.item_id = items.item_id');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		return $this->db->get()->row_array();
	}
}
?>