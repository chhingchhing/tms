<?php
require_once("report.php");
class Inventory_summary extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_item_name'), 'align' => 'left'), array('data'=>lang('suppliers_supplier'), 'align'=>'left'), array('data'=>lang('reports_item_number'), 'align' => 'left'), array('data'=>lang('reports_description'), 'align' => 'left'), array('data' => lang('items_cost_price'), 'align' => 'right'),array('data' => lang('items_unit_price'), 'align' => 'right'), array('data'=>lang('reports_count'), 'align' => 'left'), array('data'=>lang('reports_reorder_level'), 'align' => 'left'));
	}
	
	public function getData()
	{
		$this->db->select('name, company_name, item_number, cost_price, unit_price, quantity, reorder_level, description');
		$this->db->from('items');
		$this->db->join('suppliers', 'items.supplier_id = suppliers.person_id', 'left outer');
		$this->db->where($this->db->dbprefix('items').'.deleted', 0);	
		$this->db->order_by('name');
		return $this->db->get()->result_array();

	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(cost_price * quantity) as inventory_total,sum(unit_price * quantity) as inventory_sale_total');
		$this->db->from('items');
		$this->db->where('deleted', 0);
		return $this->db->get()->row_array();
	}
}
?>