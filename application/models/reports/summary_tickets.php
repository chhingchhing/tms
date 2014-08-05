<?php

require_once("report.php");

class Summary_tickets extends Report {

    function __construct() {
        parent::__construct();
    }

    public function getDataColumns()
    {
        return array(
            array('data'=>lang('summary_reports_ticket'), 'align'=> 'left'),
            array('data'=>lang('summary_reports_ticket_issue'), 'align'=> 'left'),
            array('data'=>lang('summary_reports_ticket_departure'), 'align'=> 'center'),
            array('data'=>lang('summary_reports_ticket_by'), 'align'=> 'left'), 
            array('data'=>lang('summary_reports_ticket_destination'), 'align'=> 'right'), 
            array('data'=>lang('summary_reports_ticket_seat'), 'align'=> 'right'), 
            array('data'=>lang('summary_reports_ticket_time_departure'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_hotel_name'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_room'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_company_name'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_num_guest'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_code_ticket'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_price'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_total'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_deposite'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_balance'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_commissioner'), 'align'=> 'right'),
            // array('data'=>lang('summary_reports_ticket_time_sale'), 'align'=> 'right'),
            array('data'=>lang('summary_reports_ticket_seller'), 'align'=> 'right')
        );
    }

    public function getData()
    {
        $this->db->select('ID, deposit, issue_date, time_departure, date_departure, ticket_typeID, destinationID, seat_number, time_departure, hotel_name, 
         room_number, company_name,tickets.quantity, item_number, commisioner_id, employee_id, item_unit_price, sum(quantity_purchased) as quantity_purchased, 
         commision_price, sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit, descriptions, 
         ticket_name, sale_date, '.$this->db->dbprefix('sales_tickets_temp').'.deleted '
         );
        $this->db->from('sales_tickets_temp');
        $this->db->join('tickets', 'sales_tickets_temp.ticket_id = tickets.ticket_id');
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
        $this->db->order_by('item_number');

        return $this->db->get()->result_array();        
    }

    public function getSummaryData()
    {
        $this->db->select('sum(subtotal) as subtotal, sum(total) as total,sum(item_cost_price) as cost_price, sum(profit) as profit,sum(commision_price) as total_com_price, sum(profit_inclod_com_price) as profit_inclod_com_price');
        $this->db->from('sales_tickets_temp');
        $this->db->join('tickets', 'sales_tickets_temp.ticket_id = tickets.ticket_id');
        if ($this->params['sale_type'] == 'sales')
        {
            $this->db->where('quantity_purchased > 0');
        }
        elseif ($this->params['sale_type'] == 'returns')
        {
            $this->db->where('quantity_purchased < 0');
        }
        $this->db->where($this->db->dbprefix('sales_tickets_temp').'.deleted', 0);
        return $this->db->get()->row_array();
    }

}

?>