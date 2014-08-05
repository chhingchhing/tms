<?php

require_once("report.php");

class Summary_tours extends Report {

    function __construct() {
        parent::__construct();
    }

    public function getDataColumns()
    {
        return array(
            array('data'=>lang('reports_sale_id'), 'align'=> 'left'),
            array('data'=>lang('summary_reports_ticket_issue'), 'align'=> 'left'),
            array('data'=>lang('tours_departure_date'), 'align'=> 'center'),
            array('data'=>lang('tours_departure_time'), 'align'=> 'left'), 
            array('data'=>lang('tours_tour_name'), 'align'=> 'right'), 
            array('data'=>lang('tours_destination_name'), 'align'=> 'right'),
            array('data'=>lang('tours_supplier'), 'align'=> 'right'),
            array('data'=>lang('tours_by'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_sale_price'), 'align'=> 'right'),
            array('data'=>lang('sales_quantity'), 'align'=> 'right'),
           array('data'=>lang('reports_discount'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_commission_price'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_total'), 'align'=> 'right'),
            array('data'=>lang('reports_profit'), 'align'=> 'right'), 
            array('data'=>lang('summary_reports_ticket_commissioner'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_comment'), 'align'=> 'right'),
            array('data'=>lang('tours_desc'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_seller'), 'align'=> 'right')
        );
    }

    public function getData()
    {
        //this code on commet just use for show field 
        
//        $result = mysql_query("SHOW COLUMNS FROM cgate_sales_massages_temp");
//if (!$result) {
//   echo 'Impossible d\'exécuter la requête : ' . mysql_error();
//   exit;
//}
//if (mysql_num_rows($result) > 0) {
//   while ($row = mysql_fetch_assoc($result)) {
//      print_r($row);
//   }
//}
      
        $this->db->select('ID, issue_date,tour_name,item_cost_price,company_name, by, description, destination, item_unit_price, discount_percent,comment,description, commision_price , commisioner_id, employee_id, sum(quantity_purchased) as quantity_purchased, 
         sum(subtotal) as subtotal, sum(total) as total,  sum(profit) as profit, sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price,
         sale_date, '.$this->db->dbprefix('sales_tours_temp').'.departure_time, '.$this->db->dbprefix('sales_tours_temp').'.departure_date, '.$this->db->dbprefix('sales_tours_temp').'.deleted '
         );
        $this->db->from('sales_tours_temp');
        $this->db->join('tours', 'sales_tours_temp.tour_id = tours.tour_id');
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
        $this->db->order_by('issue_date');
        
        return $this->db->get()->result_array();        
    }

    public function getSummaryData()
    {
      
        $this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(item_cost_price) as cost_price, sum(profit) as profit, sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price,');
        $this->db->from('sales_tours_temp');
        $this->db->join('tours', 'sales_tours_temp.tour_id = tours.tour_id');
        
        if ($this->params['sale_type'] == 'sales')
        {
            $this->db->where('quantity_purchased > 0');
        }
        elseif ($this->params['sale_type'] == 'returns')
        {
            $this->db->where('quantity_purchased < 0');
        }
        $this->db->where($this->db->dbprefix('sales_tours_temp').'.deleted', 0);
        return $this->db->get()->row_array();
    }

}

?>