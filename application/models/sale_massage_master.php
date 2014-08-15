<?php

class Sale_massage_master extends CI_Model {

    // Report
    public function create_sales_massages_temp_table($params) {
        $where = '';

        if (isset($params['start_date']) && isset($params['end_date'])) {
            $where = 'WHERE sale_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'" and office ="'.$params['office'].'"';
            //Set groupby paramater
            $groupBy = 'massager_id';
            if (isset($params['condition_master'])) {
                if ($params['condition_master'] == 'massager') {
                    $groupBy = 'massager_id';
                    $where = 'WHERE sale_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'" and office ="'.$params['office'].'"';
                } else if ($params['condition_master'] == 'receptionist') {
                    $groupBy = 'employee_id';
                    $where = 'WHERE sale_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'" and office ="'.$params['office'].'" and discount_percent=0';
                } else if ($params['condition_master'] == 'commissioner') {
                    $groupBy = 'commisioner_id';
                } 
            }

            if (isset($params['massager_id'])) {
                $where .= ' and massager_id = '.$params['massagerID'].'';
            }
            
            /*$where = 'WHERE sale_time BETWEEN "'.$params['start_date'].'" and "'.$params['end_date'].'" and office ="'.$params['office'].'"';*/
            if ($this->config->item('hide_suspended_sales_in_reports')) {
                $where .=' and suspended = 0';
            }
        } elseif ($this->config->item('hide_suspended_sales_in_reports')) {
            $where .='WHERE suspended = 0';
        }
        $this->_create_sales_massages_temp_table_query($where, $groupBy);
    }

    function _create_sales_massages_temp_table_query($where, $groupBy) {

        $this->db->query("CREATE TEMPORARY TABLE " . $this->db->dbprefix('sales_massages_temp') . "
        (SELECT office, ".$this->db->dbprefix('orders').".deposit, " . $this->db->dbprefix('orders') . ".order_id as ID, " . $this->db->dbprefix('orders') . ".deleted, sale_time, date(sale_time) as sale_date, 
        " . $this->db->dbprefix('detial_orders_massages') . ".id_order_massage, comment,payment_type, customer_id, employee_id,category, commisioner_id,
        " . $this->db->dbprefix('items_massages') . ".item_massage_id, NULL as item_kit_id, " . $this->db->dbprefix('items_massages') . ".supplierID, quantity_purchased, unit_price, sale_price, commision_price, ".$this->db->dbprefix('detial_orders_massages').".massager_id,
        discount_percent, sum(commission_massager) as commission_massager, (sale_price * quantity_purchased - discount_percent) as subtotal,
        " . $this->db->dbprefix('detial_orders_massages') . ".line as line, " . $this->db->dbprefix('detial_orders_massages') . ".  
        massage_name as name_of_massage, time_in, time_out, " . $this->db->dbprefix('detial_orders_massages') . ".issue_date,  
        ROUND( (sale_price * quantity_purchased - discount_percent),2) as total,
        (sale_price * quantity_purchased - discount_percent) - (unit_price * quantity_purchased) as profit,
                (sale_price * quantity_purchased - discount_percent) - (unit_price * quantity_purchased) - (commision_price)  as profit_inclod_com_price
        FROM " . $this->db->dbprefix('detial_orders_massages') . "
        INNER JOIN " . $this->db->dbprefix('orders') . " ON  " . $this->db->dbprefix('detial_orders_massages') . '.id_order_massage=' . $this->db->dbprefix('orders') . '.order_id' . "
        INNER JOIN " . $this->db->dbprefix('items_massages') . " ON  " . $this->db->dbprefix('detial_orders_massages') . '.item_massage_id=' . $this->db->dbprefix('items_massages') . '.item_massage_id' . "
        LEFT OUTER JOIN " . $this->db->dbprefix('suppliers') . " ON  " . $this->db->dbprefix('items_massages') . '.supplierID=' . $this->db->dbprefix('suppliers') . '.supplier_id' . "

        

        $where
        GROUP BY ID) 
        UNION ALL
        (SELECT office, ".$this->db->dbprefix('orders').".deposit, ".$this->db->dbprefix('orders').".order_id as ID, ".$this->db->dbprefix('orders').".deleted, sale_time, date(sale_time) as sale_date, 
        NULL as id_order_massage, comment,payment_type, customer_id, employee_id, NULL as category, commisioner_id,
        NULL as item_massage_id, 
        ".$this->db->dbprefix('item_kits').".item_kit_id, '' as supplierID, quantity_purchased, item_kit_cost_price as unit_price, item_kit_unit_price as sale_price, commision_price, NULL as massager_id,       
        discount_percent, NULL as commission_massager, (item_kit_unit_price*quantity_purchased - discount_percent) as subtotal,
        ".$this->db->dbprefix('orders_item_kits').".line as line, NULL as name_of_massage, NULL as time_in, NULL as time_out, NULL as issue_date,
        ROUND((item_kit_unit_price*quantity_purchased - discount_percent),2) as total,
        (item_kit_unit_price*quantity_purchased - discount_percent) - (item_kit_cost_price*quantity_purchased) as profit,
        (item_kit_unit_price * quantity_purchased - discount_percent) - (item_kit_cost_price * quantity_purchased) - (commision_price)  as profit_inclod_com_price      
        
        FROM ".$this->db->dbprefix('orders_item_kits')."
        INNER JOIN ".$this->db->dbprefix('orders')." ON  ".$this->db->dbprefix('orders_item_kits').'.sale_id='.$this->db->dbprefix('orders').'.order_id'."
        INNER JOIN ".$this->db->dbprefix('item_kits')." ON  ".$this->db->dbprefix('orders_item_kits').'.item_kitID='.$this->db->dbprefix('item_kits').'.item_kit_id'."


        
        $where
        GROUP BY ID) ORDER BY ID");

    }
}

?>
