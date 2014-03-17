<?php

class Sale_massage extends CI_Model {

    public function get_info($sale_id) {
        $this->db->from('orders');
        $this->db->where('order_id', $sale_id);
        return $this->db->get();
    }

    function get_cash_sales_total_for_shift($shift_start, $shift_end) {
        $sales_totals = $this->get_sales_totaled_by_id($shift_start, $shift_end);
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;

        $this->db->select('sales_payments.sale_id, sales_payments.payment_type, payment_amount', false);
        $this->db->from('sales_payments');
        $this->db->join('sales', 'sales_payments.sale_id=sales.sale_id');
        $this->db->where('sale_time >=', $shift_start);
        $this->db->where('sale_time <=', $shift_end);
        $this->db->where('employee_id', $employee_id);
        $this->db->where($this->db->dbprefix('sales') . '.deleted', 0);

        $payments_by_sale = array();
        $sales_payments = $this->db->get()->result_array();

        foreach ($sales_payments as $row) {
            $payments_by_sale[$row['sale_id']][] = $row;
        }

        $payment_data = $this->Sale->get_payment_data($payments_by_sale, $sales_totals);

        if (isset($payment_data[lang('sales_cash')])) {
            return $payment_data[lang('sales_cash')]['payment_amount'];
        }

        return 0.00;
    }

    function get_payment_data($payments_by_sale, $sales_totals) {
        $payment_data = array();

        foreach ($payments_by_sale as $sale_id => $payment_rows) {
            $total_sale_balance = $sales_totals[$sale_id];
            usort($payment_rows, array('Sale', '_sort_payments_for_sale'));

            foreach ($payment_rows as $row) {
                $payment_amount = $row['payment_amount'] <= $total_sale_balance ? $row['payment_amount'] : $total_sale_balance;

                if (!isset($payment_data[$row['payment_type']])) {
                    $payment_data[$row['payment_type']] = array('payment_type' => $row['payment_type'], 'payment_amount' => 0);
                }

                if ($total_sale_balance != 0) {
                    $payment_data[$row['payment_type']]['payment_amount'] += $payment_amount;
                }
                $total_sale_balance-=$payment_amount;
            }
        }
        return $payment_data;
    }

    static function _sort_payments_for_sale($a, $b) {
        if ($a['payment_amount'] == $b['payment_amount'])
            ;
        {
            return 0;
        }

        if ($a['payment_amount'] < $b['payment_amount']) {
            return -1;
        }

        return 1;
    }

    function get_sales_totaled_by_id($shift_start, $shift_end) {
        $where = 'WHERE sale_time BETWEEN "' . $shift_start . '" and "' . $shift_end . '"';
        $this->_create_sales_items_temp_table_query($where);

        $sales_totals = array();

        $this->db->select('sale_id, SUM(total) as total', false);
        $this->db->from('sales_items_temp');
        $this->db->group_by('sale_id');

        foreach ($this->db->get()->result_array() as $sale_total_row) {
            $sales_totals[$sale_total_row['sale_id']] = $sale_total_row['total'];
        }

        return $sales_totals;
    }

    /**
     * added for cash register
     * insert a log for track_cash_log
     * @param array $data
     */
    function update_register_log($data) {
        $this->db->where('shift_end', '0000-00-00 00:00:00');
        $this->db->where('employeeID', $this->session->userdata('person_id'));
        return $this->db->update('register_log', $data) ? true : false;
    }

    function insert_register($data) {
        return $this->db->insert('register_log', $data) ? $this->db->insert_id() : false;
    }

    function is_register_log_open() {
        $this->db->from('register_log');
        $this->db->where('shift_end', '0000-00-00 00:00:00');
        $this->db->where('employeeID', $this->session->userdata('person_id'));
        $query = $this->db->get();
        if ($query->num_rows())
            return true;
        else
            return false;
    }

    function get_current_register_log() {
        $this->db->from('register_log');
        $this->db->where('shift_end', '0000-00-00 00:00:00');
        $this->db->where('employeeID', $this->session->userdata('person_id'));
        $query = $this->db->get();
        if ($query->num_rows())
            return $query->row();
        else
            return false;
    }

    function exists($item_id) {
        $query = $this->db->where('order_id', $item_id)
                ->get('orders');

        return ($query->num_rows() == 1);
    }

    function update($sale_data, $sale_id) {
        $this->db->where('sale_id', $sale_id);
        $success = $this->db->update('sales', $sale_data);

        return $success;
    }

    function save($items, $customer_id, $employee_id, $commissioner_id, $commissioner_price, $controller_name, $time_in, $time_out, $comment, $show_comment_on_receipt, $payments, $sale_id = false, $suspended = 0, $cc_ref_no = '', $change_sale_date = false) {

        if (count($items) == 0)
            return -1;

        $payment_types = '';
        foreach ($payments as $payment_id => $payment) {
            $payment_types = $payment_types . $payment['payment_type'] . ': ' . to_currency($payment['payment_amount']) . '<br />';
        }

        $sales_data = array(
            'customer_id' => $this->Customer->exists($customer_id) ? $customer_id : null,
            'employee_id' => $employee_id,
            'payment_type' => $payment_types,
            'comment' => $comment,
            'show_comment_on_receipt' => $show_comment_on_receipt ? $show_comment_on_receipt : 0,
            'suspended' => $suspended,
            'deleted' => 0,
            'cc_ref_no' => $cc_ref_no,
            'commision_price' => $commissioner_price,
            'commisioner_id' => $this->commissioner->exists($commissioner_id) ? $commissioner_id : null,
            'category' => $controller_name
        );
        if ($sale_id) {
            $old_date = $this->get_info($sale_id)->row_array();

            $sales_data['sale_time'] = $old_date['sale_time'];
            if ($change_sale_date) {
                $sale_time = strtotime($change_sale_date);
                if ($sale_time !== FALSE) {
                    $sales_data['sale_time'] = date('Y-m-d', strtotime($change_sale_date)) . ' 00:00:00';
                }
            }
        } else {
            $sales_data['sale_time'] = date('Y-m-d H:i:s');
        }
        //Run these queries as a transaction, we want to make sure we do all or nothing
        $this->db->trans_start();
        if ($sale_id) {
            //Del echo'Hello my edit sale';ete previoulsy sale so we can overwrite data
            $this->delete($sale_id, true, $controller_name);
            $this->db->where('order_id', $sale_id);
            $this->db->update('orders', $sales_data);
        } else {
            $result = $this->db->insert('orders', $sales_data);
            $sale_id = $this->db->insert_id();
        }
        // $this->db->trans_start();

        foreach ($payments as $payment_id => $payment) {

            $sales_payments_data = array
                (
                'sale_id' => $sale_id,
                'payment_type' => $payment['payment_type'],
                'payment_amount' => $payment['payment_amount'],
                'payment_date' => $payment['payment_date'],
                'sale_type' => $controller_name
            );
            $this->db->insert('sales_payments', $sales_payments_data);
        }
        $this->db->trans_start();
        foreach ($items as $line => $item) {
            if (isset($item['item_massage_id'])) {
                $cur_item_info = $this->massage->get_info($item['item_massage_id']);

                $sales_items_data = array
                    (
                    'id_order_massage' => $sale_id,
                    'item_massage_id' => $item['item_massage_id'],
                    'line' => $item['line'],
                    'issue_date' => date('Y-m-d H:i:s'),
                    'time_in' => $time_in,
                    'time_out' => $time_out,
                    'massage_name' => $cur_item_info->massage_name,
                    'unit_price' => $cur_item_info->actual_price,
                    'sale_price' => $item['price'],
                    'deposite' => $item['deposite'],
                    'discount_percent' => $item['discount'],
                    'quantity_purchased' => $item['quantity']
                );
                // insret sale information data into detial_orders_massages table
                $this->db->insert('detial_orders_massages', $sales_items_data);

                //Ramel Inventory Tracking
                //Inventory Count Details
                $qty_buy = -$item['quantity'];
                // $sale_remarks ='POS '.$sale_id;
                $sale_remarks = 'CGATE ' . $sale_id;
                $inv_data = array
                    (
                    'trans_date' => date('Y-m-d H:i:s'),
                    'trans_items' => $item['item_massage_id'],
                    'trans_user' => $employee_id,
                    'trans_comment' => $sale_remarks,
                    'trans_inventory' => $qty_buy,
                    'type_items' => $controller_name
                );

                $this->Inventory->insert($inv_data);
                //$this->db->trans_complete();
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return -1;
        }
        return $sale_id;
    }

    function update_giftcard_balance($sale_id, $undelete = 0) {
        //if gift card payment exists add the amount to giftcard balance
        $this->db->from('sales_payments');
        $this->db->like('payment_type', lang('sales_giftcard'));
        $this->db->where('sale_id', $sale_id);
        $sales_payment = $this->db->get();

        if ($sales_payment->num_rows >= 1) {
            foreach ($sales_payment->result() as $row) {
                $giftcard_number = str_ireplace(lang('sales_giftcard') . ':', '', $row->payment_type);
                $value = $row->payment_amount;
                if ($undelete == 0) {
                    $this->db->set('value', 'value+' . $value, false);
                } else {
                    $this->db->set('value', 'value-' . $value, false);
                }
                $this->db->where('giftcard_number', $giftcard_number);
                $this->db->update('giftcards');
            }
        }
    }

    function delete($sale_id, $all_data = false, $controller_name) {
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;

        $this->db->select('item_massage_id, quantity_purchased');
        $this->db->from('detial_orders_massages');
        $this->db->where('id_order_massage', $sale_id);
        $query = $this->db->get();
        foreach ($query->result_array() as $sale_item_row) {

            $sale_remarks = 'CGATE ' . $sale_id;
            $inv_data = array
                (
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_items' => $sale_item_row['item_massage_id'],
                'trans_user' => $employee_id,
                'trans_comment' => $sale_remarks,
                'trans_inventory' => $sale_item_row['quantity_purchased'],
                'type_items' => $controller_name
            );

            $this->Inventory->insert($inv_data);
        }
        if ($all_data) {
            //Run these queries as a transaction, we want to make sure we do all or nothing
            $this->db->trans_start();
            $this->db->delete('sales_payments', array('sale_id' => $sale_id));
            $this->db->trans_start();
            $this->db->delete('detial_orders_massages', array('id_order_massage' => $sale_id));
            $this->db->trans_complete();
        }

        $this->db->where('order_id', $sale_id);
        return $this->db->update('orders', array('deleted' => 1));
    }

    function undelete($sale_id) {
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;

        $this->db->select('item_id, quantity_purchased');
        $this->db->from('sales_items');
        $this->db->where('sale_id', $sale_id);

        foreach ($this->db->get()->result_array() as $sale_item_row) {
            $cur_item_info = $this->Item->get_info($sale_item_row['item_id']);
            $item_data = array('quantity' => $cur_item_info->quantity - $sale_item_row['quantity_purchased']);
            $this->Item->save($item_data, $sale_item_row['item_id']);

            $sale_remarks = 'POS ' . $sale_id;
            $inv_data = array
                (
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_items' => $sale_item_row['item_id'],
                'trans_user' => $employee_id,
                'trans_comment' => $sale_remarks,
                'trans_inventory' => -$sale_item_row['quantity_purchased']
            );
            $this->Inventory->insert($inv_data);
        }

        $this->update_giftcard_balance($sale_id, 1);

        $this->db->select('item_kit_id, quantity_purchased');
        $this->db->from('sales_item_kits');
        $this->db->where('sale_id', $sale_id);

        foreach ($this->db->get()->result_array() as $sale_item_kit_row) {
            foreach ($this->Item_kit_items->get_info($sale_item_kit_row['item_kit_id']) as $item_kit_item) {
                $cur_item_info = $this->Item->get_info($item_kit_item->item_id);

                $item_data = array('quantity' => $cur_item_info->quantity - ($sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity));
                $this->Item->save($item_data, $item_kit_item->item_id);

                $sale_remarks = 'POS ' . $sale_id;
                $inv_data = array
                    (
                    'trans_date' => date('Y-m-d H:i:s'),
                    'trans_items' => $item_kit_item->item_id,
                    'trans_user' => $employee_id,
                    'trans_comment' => $sale_remarks,
                    'trans_inventory' => -$sale_item_kit_row['quantity_purchased'] * $item_kit_item->quantity
                );
                $this->Inventory->insert($inv_data);
            }
        }
        $this->db->where('sale_id', $sale_id);
        return $this->db->update('sales', array('deleted' => 0));
    }

    //get items sale
    function get_sale_items($sale_id) {
        $query = $this->db->where('id_order_massage', $sale_id)
                ->get("detial_orders_massages");

        return $query;
    }

    function get_sale_payments($sale_id) {
        $this->db->from('sales_payments');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get();
    }

    function get_customer($sale_id) {
        $this->db->from('orders');
        $this->db->where('order_id', $sale_id);
        return $this->Customer->get_info($this->db->get()->row()->customer_id);
    }

    function get_comment($sale_id) {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->comment;
    }

    function get_comment_on_receipt($sale_id) {
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        return $this->db->get()->row()->show_comment_on_receipt;
    }

    public function get_giftcard_value($giftcardNumber) {
        if (!$this->Giftcard->exists($this->Giftcard->get_giftcard_id($giftcardNumber)))
            return 0;

        $this->db->from('giftcards');
        $this->db->where('giftcard_number', $giftcardNumber);
        return $this->db->get()->row()->value;
    }

    function get_all_suspended() {
        $this->db->from('sales');
        $this->db->where('deleted', 0);
        $this->db->where('suspended', 1);
        $this->db->order_by('sale_id');
        return $this->db->get();
    }

    function lookup($keyword) {
        $this->db->select('*')
                ->from('items_massages')
                ->like('massage_name', $keyword, 'after');
        $query = $this->db->get();
        return $query->result();
    }

    // Report
    public function create_sales_massages_temp_table($params) {
        $where = '';

        if (isset($params['start_date']) && isset($params['end_date'])) {
            $where = 'WHERE sale_time BETWEEN "' . $params['start_date'] . '" and "' . $params['end_date'] . '"';
            if ($this->config->item('hide_suspended_sales_in_reports')) {
                $where .=' and suspended = 0';
            }
        } elseif ($this->config->item('hide_suspended_sales_in_reports')) {
            $where .='WHERE suspended = 0';
        }
        $this->_create_sales_massages_temp_table_query($where);
    }

    function _create_sales_massages_temp_table_query($where) {

        $this->db->query("CREATE TEMPORARY TABLE " . $this->db->dbprefix('sales_massages_temp') . "
		(SELECT " . $this->db->dbprefix('orders') . ".order_id as ID, " . $this->db->dbprefix('orders') . ".deleted as deleted, sale_time, date(sale_time) as sale_date, " . $this->db->dbprefix('detial_orders_massages') . ".id_order_massage, comment,payment_type, customer_id, employee_id,category, commisioner_id,
		" . $this->db->dbprefix('items_massages') . ".item_massage_id, " . $this->db->dbprefix('items_massages') . ".supplierID, quantity_purchased, unit_price, sale_price, commision_price,
		discount_percent, (sale_price * quantity_purchased - sale_price * quantity_purchased * discount_percent / 100) as subtotal,
		" . $this->db->dbprefix('detial_orders_massages') . ".line as line, " . $this->db->dbprefix('detial_orders_massages') . ".
		
		massage_name as name_of_massage, time_in, time_out, " . $this->db->dbprefix('detial_orders_massages') . ".issue_date,  

		ROUND( (sale_price * quantity_purchased - sale_price * quantity_purchased * discount_percent / 100),2) as total,

		(sale_price * quantity_purchased - sale_price * quantity_purchased * discount_percent / 100) - (unit_price * quantity_purchased) as profit,
                (sale_price * quantity_purchased - sale_price * quantity_purchased * discount_percent / 100) - (unit_price * quantity_purchased) - (commision_price)  as profit_inclod_com_price
		FROM " . $this->db->dbprefix('detial_orders_massages') . "
		INNER JOIN " . $this->db->dbprefix('orders') . " ON  " . $this->db->dbprefix('detial_orders_massages') . '.id_order_massage=' . $this->db->dbprefix('orders') . '.order_id' . "
		INNER JOIN " . $this->db->dbprefix('items_massages') . " ON  " . $this->db->dbprefix('detial_orders_massages') . '.item_massage_id=' . $this->db->dbprefix('items_massages') . '.item_massage_id' . "
		LEFT OUTER JOIN " . $this->db->dbprefix('suppliers') . " ON  " . $this->db->dbprefix('items_massages') . '.supplierID=' . $this->db->dbprefix('suppliers') . '.supplier_id' . "

		$where
                    GROUP BY ID, item_massage_id, line) ORDER BY ID");
    }
}

?>
