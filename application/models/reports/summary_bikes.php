<?php

require_once("report.php");

class Summary_bikes extends Report {

    function __construct() {
        parent::__construct();
    }

    public function getDataColumns()
    {
        return array(
            array('data'=>lang('reports_sale_id'), 'align'=> 'left'),
            array('data'=>lang('summary_reports_ticket_issue'), 'align'=> 'left'),
            array('data'=>lang('summary_reports_bike_date_rent'), 'align'=> 'center'),
            array('data'=>lang('summary_reports_bike_date_return'), 'align'=> 'left'), 
            array('data'=>lang('bikes_bike_types'), 'align'=> 'right'), 
            array('data'=>lang('summary_reports_massage_cost'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_sale_price'), 'align'=> 'right'),
            array('data'=>lang('sales_quantity'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_disc'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_massage_commission_price'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_total'), 'align'=> 'right'),
            array('data'=>lang('reports_profit'), 'align'=> 'right'), 
            array('data'=>lang('summary_reports_massage_comment'), 'align'=> 'right'),
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
      
        $this->db->select('ID, issue_date,bike_types,actual_price, sell_price, discount_percent,comment,number_of_day, commision_price ,date_time_in, date_time_out, commisioner_id, employee_id, sum(quantity_of_bike) as quantity_purchased, 
         sum(subtotal) as subtotal, sum(total) as total,  sum(profit) as profit, sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price,
         sale_date, '.$this->db->dbprefix('sales_bikes_temp').'.deleted '
         );
        $this->db->from('sales_bikes_temp');
        $this->db->join('items_bikes', 'sales_bikes_temp.item_bikeID = items_bikes.item_bike_id');
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
        $this->db->order_by('item_bikeID');
        
        return $this->db->get()->result_array();        
    }

    public function getSummaryData()
    {
      
        $this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price,');
        $this->db->from('sales_bikes_temp');
        $this->db->join('items_bikes', 'sales_bikes_temp.item_bikeID = items_bikes.item_bike_id');
        
        if ($this->params['sale_type'] == 'sales')
        {
            $this->db->where('quantity_of_bike > 0');
        }
        elseif ($this->params['sale_type'] == 'returns')
        {
            $this->db->where('quantity_of_bike < 0');
        }
        $this->db->where($this->db->dbprefix('sales_bikes_temp').'.deleted', 0);
        return $this->db->get()->row_array();
    }

}

?>