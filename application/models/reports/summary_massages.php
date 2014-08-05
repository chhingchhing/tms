<?php

require_once("report.php");

class Summary_massages extends Report {

    function __construct() {
        parent::__construct();
    }

    public function getDataColumns()
    {
        return array(
            array('data'=>lang('summary_reports_massage_id'), 'align'=> 'left'),
            array('data'=>lang('summary_reports_ticket_issue'), 'align'=> 'left'),
            array('data'=>lang('summary_reports_massage_time_in'), 'align'=> 'center'),
            array('data'=>lang('summary_reports_massage_time_out'), 'align'=> 'left'), 
            array('data'=>lang('summary_reports_massage_name'), 'align'=> 'right'), 
            array('data'=>lang('summary_reports_massage_cost'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_sale_price'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_qty'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_disc').'', 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_commission_price'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_total'), 'align'=> 'right'),
            array('data'=>lang('reports_profit'), 'align'=> 'right'), 
            array('data'=>lang('summary_reports_massage_comment'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_seller'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_massager'), 'align'=> 'right')
        );
    }

    public function getData()
    {
        //this code on commet just use for show field 
        
/*       $result = mysql_query("SHOW COLUMNS FROM cgate_sales_massages_temp");
if (!$result) {
  echo 'Impossible d\'exécuter la requête : ' . mysql_error();
  exit;
}
if (mysql_num_rows($result) > 0) {
  while ($row = mysql_fetch_assoc($result)) {
     print_r($row);
  }
}*/
      
        
        $this->db->select('ID, issue_date, discount_percent,comment, commision_price, unit_price, time_in, time_out, massage_typesID, name_of_massage, commisioner_id, employee_id, sale_price, sum(quantity_purchased) as quantity_purchased, 
         sum(subtotal) as subtotal, sum(total) as total, sum(unit_price) as cost_price, sum(profit) as profit, sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price, massager_id, 
         sale_date, '.$this->db->dbprefix('sales_massages_temp').'.deleted '
         );
        $this->db->from('sales_massages_temp');
        $this->db->join('items_massages', 'sales_massages_temp.item_massage_id = items_massages.item_massage_id');
        if ($this->params['sale_type'] == 'sales')
        {
            $this->db->where('quantity_purchased > 0');
        }
        elseif ($this->params['sale_type'] == 'returns')
        {
            $this->db->where('quantity_purchased < 0');
        }
        $this->db->where($this->db->dbprefix('sales_massages_temp').'.deleted', 0);
        $this->db->group_by('ID');
        $this->db->order_by('id_order_massage');
        
        return $this->db->get()->result_array();        
    }

    public function getSummaryData()
    {
      
        $this->db->select('sum(subtotal) as subtotal, sum(total) as total,sum(unit_price) as cost_price, sum(profit) as profit, sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price,');
        $this->db->from('sales_massages_temp');
        $this->db->join('items_massages', 'sales_massages_temp.item_massage_id = items_massages.item_massage_id');
        
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

}

?>