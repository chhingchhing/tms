<?php

require_once ("person_controller.php");
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");

class Massages extends Person_controller {
    function __construct() {
        parent::__construct('massages');
        $this->load->model(array('massage'));
        $this->load->library('sale_massage_lib');
        $this->load->library('massage_lib');
        $this->load->library('sale_lib');
        $this->load->library('sale_lib_sms');
        $this->load->model(array('sale','massage', 'Sale_massage', 'sales/massage/massage_kit', 'sales/massage/massage_item', 'commissioner','currency_model'));
    }

    function index() {
        $this->manage_massage();
    }

    function manage_massage() {
        $data['allowed_modules'] = $this->check_module_accessable();

        $this->check_action_permission('search');
        $data['supplierId'] = $this->massage->select_supplier_id();
        $data['supplier_type_Id'] = $this->massage->select_massage_type_id();
        $config['base_url'] = site_url('massages/massages/' . $this->uri->segment(3));
        $config['total_rows'] = $this->massage->count_all();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        $data['per_page'] = $config['per_page'];

        if ($this->uri->segment(4)) {
            $data['supplierId'] = $this->massage->select_supplier_id();
            $data['supplier_type_Id'] = $this->massage->select_massage_type_id();
            $data['manage_table'] = $this->sorting($this->uri->segment(4));
        } else {
            $data['supplierId'] = $this->massage->select_supplier_id();
            $data['supplier_type_Id'] = $this->massage->select_massage_type_id();
            $data['per_page'] = $config['per_page'];
            $data['manage_table'] = get_massage_manage_table($this->massage->get_massages($data['per_page']), $this);
        }

        $this->load->view('massages/manage_massage', $data);
    }

    function check_duplicate_data() {
        $massageName = $this->input->post("massage_name");
        // $tDestination = $this->input->post("massage_desc");
        // $tType = $this->input->post("massage_typesID");
        echo json_encode(array('duplicate' => $this->massage->check_duplicate_data($massageName)));
    }

//      get data for inserting to tbl items_massages
    function get_massage($massage, $item_massage_id) {
        $item_massage_id = $this->input->post("item_massage_id");
        $massage = array(
            'massage_name' => $this->input->post('massage_name'),
            'massage_desc' => $this->input->post('massage_desc'),
            'supplierID' => $this->input->post('supplier_id'),
            'actual_price' => $this->input->post('actual_price'),
            'price_one' => $this->input->post('price_one'),
            'massage_typesID' => $this->input->post('massage_typesID')
        );

        $insert_massage = $this->input->post('submit_sms');
      
        if (isset($insert_massage)) {
            return $this->massage->insert_massage($massage, $item_massage_id);
        }else {
            redirect('massages/massages/world_1');
        }
    }

     function saveMassage($item_id = -1) {
        $item_id = $this->input->post("item_massage_id");
        $this->check_action_permission('add_update');
        $item_data = array(
            'massage_name' => $this->input->post('massage_name'),
            'massage_desc' => $this->input->post('massage_desc'),
            'supplierID' => $this->input->post('supplier_id'),
            'actual_price' => $this->input->post('actual_price'),
            'price_one' => $this->input->post('price_one'),
            'massage_typesID' => $this->input->post('massage_typesID'),
            'deleted' => '0'
    );
        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

         $insert_massage_one = $this->input->post('btn_submit_massages');
        if (isset($insert_massage_one)) {
            return $this->massage->insert_massage($item_data, $item_id);
        }

        $employee_id = $this->Employee->get_logged_in_employee_info()->employee_id;
        $cur_item_info = $this->massage->get_info($item_id);

        if ($this->massage->save($item_data, $item_id)) {
            //New item  
            if ($item_id == -1) {
                echo json_encode(array('success' => true, 'message' => lang('items_successful_adding') . ' ' .
                    $item_data['name'], 'item_massage_id' => $item_data['item_massage_id']));
                $item_id = $item_data['item_massage_id'];
            } else { //previous item
                echo json_encode(array('success' => true, 'message' => lang('items_successful_updating') . ' ' .
                    $item_data['name'], 'item_massage_id' => $item_id));
            }

            $inv_data = array
                (
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_items' => $item_id,
                'trans_user' => $employee_id,
                'trans_comment' => lang('items_manually_editing_of_quantity'),
                'trans_inventory' => $cur_item_info ? $this->input->post('quantity') - $cur_item_info->quantity : $this->input->post('quantity')
            );
            $this->Inventory->insert($inv_data);
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('items_error_adding_updating') . ' ' .
                $item_data['name'], 'item_massage_id' => -1));
        }
    }

 function saved($item_id = -1) {
        if ($item_id != -1) {
            $item_id = $this->input->post("item_massage_id");
        }
        $this->check_action_permission('add_update');
        $item_data = array(
            'massage_name' => $this->input->post('massage_name'),
            'massage_desc' => $this->input->post('massage_desc'),
            'supplierID' => $this->input->post('supplier_id'),
            'price_one' => $this->input->post('price_one'),
            'actual_price' => $this->input->post('actual_price'), 
            'commission_price_massager' => $this->input->post('commission_price_massager'), 
            'commission_price_receptionist' => $this->input->post('commission_price_receptionist'), 
            'outside_staff_fee' => $this->input->post('outside_staff_fee'), 
            'duration' => $this->input->post('duration'), 
            // 'massage_typesID' => $this->input->post('massage_typesID'),
            'deleted' => '0'
        );      

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $employee_id = $this->Employee->get_logged_in_employee_info()->employee_id;
        $result = $this->massage->save($item_data, $item_id);
        if ($result) {
            //New item  
            if ($item_id == -1) {
                echo json_encode(array('success' => true, 'message' => lang('items_successful_adding') . ' ' .
                    $item_data['massage_name'], 'item_massage_id' => $item_data['item_massage_id']));
            } else { //previous item
                echo json_encode(array('success' => true, 'message' => lang('items_successful_updating') . ' ' .
                    $item_data['massage_name'], 'item_massage_id' => $item_id));
            }
           
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('items_error_adding_updating') . ' ' .
                $item_data['massage_name'], 'item_massage_id' => -1));
        }
    }
    
//      delete massage from tbl items_massages 
    function delete() {
        $this->check_action_permission('delete');
        $massages_to_delete = $this->input->post('checkedID');
        if ($this->massage->delete_list($massages_to_delete)) {
            echo json_encode(array('success' => true, 'message' => lang('massages_successful_deleted')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('massages_cannot_be_deleted')));
        }
    }

    //Alain Multiple Payments
    function delete_payment($payment_id) {
        $this->sale_lib->delete_payment($payment_id);
        $this->_reload();
    }

    function edit_item($office, $line) {
        $data = array();

        $this->form_validation->set_rules('price', 'lang:items_price', 'required');
        $this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required');

        $description = $this->input->post("description");
        $serialnumber = $this->input->post("serialnumber");
        $price = $this->input->post("price");
        $quantity = $this->input->post("quantity");
        $discount = $this->input->post("discount");
        $massager = $this->input->post("each_massager");

        $item_id = $this->input->post("item_id");
        $commission_massager = $this->input->post("commission_massager");
        $commission_receptionist = $this->input->post("commission_receptionist");

        /*if ($discount == "" or $discount == 0) {
            $tip_price = $this->sale_lib->set_tip_price(1);
        }*/

        if ($this->form_validation->run() != FALSE) {
            // ($line,$description,$serialnumber,$quantity,$discount,$price,$massager=false)
            $this->sale_lib->edit_item($line, $description, $serialnumber, $quantity, $discount, $price, $massager, $item_id, $commission_massager, $commission_receptionist);
        } else {
            $data['error'] = lang('sales_error_editing_item');
        }

        $this->_reload($data);
    }

    
    function delete_item($office, $item_number) {
        $this->sale_massage_lib->delete_item($item_number);
        $this->_reload();
    }

    function sorting($offset) {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        if ($search) {
            $config['total_rows'] = $this->massage->search_count_all($search);
//			$table_data = $this->massage->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'item_massage_id' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
            $table_data = $this->massage->search($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'item_massage_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        } else {
            $config['total_rows'] = $this->massage->count_all();
//			$table_data = $this->massage->get_massages($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'item_massage_id' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
            $table_data = $this->massage->get_massages($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'item_massage_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        }
        $config['base_url'] = site_url('massages/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
//		$data['manage_table']=get_people_manage_table_data_rows($table_data,$this);
//		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));	
        $data['manage_table'] = get_massage_manage_table($table_data, $this);
        return $data['manage_table'];
    }

    /*
      Returns massages table data rows. This will be called with AJAX.
     */

    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;

        $search_data = $this->massage->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'massage_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        $config['base_url'] = site_url('massages/massages' . $this->uri->segment(3));
        $config['total_rows'] = $this->massage->search_count_all($search);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_massage_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    /*
      Gives search suggestions based on what is being searched for
     */

    function suggest() {
        $suggestions = $this->massage->get_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

    /*
      Loads the massage edit form
     */

    function get_info($massage_id = -1) {
        echo json_encode($this->massage->get_info($massage_id));
    }

    function view_massager($employee_id = -1) {
        $this->check_action_permission('add_update');
        $data['controller_name'] = strtolower(get_class());
        $data['person_info'] = $this->Employee->get_info($employee_id);
        $data['positions'] = array();
        foreach ($this->Module->get_all_positions()->result() as $position)
        {
            $data['positions'][$position->position_id] = $position->position_name;
        }

        $this->load->view("employees/_massager", $data);
    }

    function view($massage_id = -1) {
        $this->check_action_permission('add_update');
        $data['person_info'] = $this->massage->get_info($massage_id);
        $this->load->view("massages/form", $data);
    }

    function viewJSON($massage_id = -1) {
        $this->check_action_permission('add_update');
        $data['massage_info'] = $this->massage->get_info($massage_id);
        $data['supplierId'] = $this->massage->select_supplier_id();
        $this->load->view("massages/_form", $data);
    }

    function account_number_exists() {
        if ($this->massage->account_number_exists($this->input->post('account_number')))
            echo 'false';
        else
            echo 'true';
    }

    /*
      Inserts/updates a customer
     */

    function save($item_id = -1) {
        $this->check_action_permission('add_update');
        $item_data = array(
            'massage_name' => $this->input->post('massage_name'),
            'massage_desc' => $this->input->post('massage_desc'),
            'supplierID' => $this->input->post('supplier_id'),
            'price_one' => $this->input->post('price_one'),
            'actual_price' => $this->input->post('actual_price'), 
            'commission_price_massager' => $this->input->post('commission_price_massager'), 
            'commission_price_receptionist' => $this->input->post('commission_price_receptionist'), 
            'outside_staff_fee' => $this->input->post('outside_staff_fee'), 
            'duration' => $this->input->post('duration'), 
            // 'massage_typesID' => $this->input->post('massage_typesID'),
            'deleted' => '0'
        );       
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $employee_id = $this->Employee->get_logged_in_employee_info()->employee_id;
        $result = $this->massage->save($item_data, $item_id);
        if ($result) {
            //New item  
            if ($item_id == -1) {
                echo json_encode(array('success' => true, 'message' => lang('items_successful_adding') . ' ' .
                    $item_data['massage_name'], 'item_massage_id' => $item_data['item_massage_id']));
            } else { //previous item
                echo json_encode(array('success' => true, 'message' => lang('items_successful_updating') . ' ' .
                    $item_data['massage_name'], 'item_massage_id' => $item_id));
            }
           
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('items_error_adding_updating') . ' ' .
                $item_data['massage_name'], 'item_massage_id' => -1));
        }
    }

    /* added for excel expert */
    function excel_export($template = 0) {
        $data = $this->massage->get_massages()->result_object();
        $this->load->helper('report');
        $rows = array();
        $row = array(lang('common_item_module_id'), lang('common_massage_name'), lang('common_massage_desc'), lang('common_price_one'), lang('common_actual_price'), lang('common_massage_typesID'), lang('common_deleted'));

        $n = 1;
        $rows[] = $row;
        foreach ($data as $r) {
            $row = array(
                $n++,
                $r->massage_name,
                $r->massage_desc,
               // $r->price_one_haft,
                $r->supplierID,
               // $r->price_haft,
                $r->price_one,
                $r->actual_price,
                $r->deleted,
            );
            $rows[] = $row;
        }

        $content = array_to_csv($rows);

        if ($template) {
            force_download('massages_export_mass_update.csv', $content);
        } else {
            force_download('massages_export.csv', $content);
        }
        exit;
    }
	
    /*
      get the width for the add/edit form
     */
    function get_form_width() {
        return 550;
    }

// Search customer for sale process
    /* Customer in sales */
    function customer_search() {
        $suggestions = $this->Customer->get_customer_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

    /*
      Gets one row for a person manage table. This is called using AJAX to update one row.
     */

    function get_row() {
        $item_id = $this->input->post('row_id');
        $data_row = get_massage_data_row($this->massage->get_info($item_id), $this);
        echo $data_row;
    }

//  sale massage function
    function sales() {
        /*$office_id = $this->session->userdata("office_id");
        var_dump($office_id);
        $office_name = $this->session->userdata("office_number");
        var_dump($office_name); die();*/
        
        $this->sale_lib->clear_all();

        $this->sale_massage_lib->clear_all();
        $this->load->library("sale_massage_lib");
        $this->sale_massage_lib->empty_payments();
        $this->sale_massage_lib->delete_customer();

        $data['allowed_modules'] = $this->check_module_accessable();
        // this condition below not run   
        if ($this->config->item('track_cash')) {
            if ($this->input->post('opening_amount') != '') {
                $now = date('Y-m-d H:i:s');
                $cash_register = new stdClass();

                $cash_register->employee_id = $this->session->userdata('person_id');
                $cash_register->shift_start = $now;
                $cash_register->open_amount = $this->input->post('opening_amount');
                $cash_register->close_amount = 0;
                $cash_register->cash_sales_amount = 0;
                $this->Sale->insert_register($cash_register);
                redirect(site_url('sales'));
            } else if ($this->Sale->is_register_log_open()) {
                $this->_reload(array(), false);
            } else {
                $this->load->view('sales/opening_amount', $data);
            }
        } else {
            $this->_reload(array(), false);
        }
    }

    function reload() {
        $this->_reload();
    }

    function _reload($data = array(), $is_ajax = true) {
        $person_info = $this->Employee->get_logged_in_employee_info();
        $data['allowed_modules'] = $this->check_module_accessable();

        $data['controller_name'] = strtolower(get_class());
        $data['cart'] = $this->sale_lib_sms->get_cart();
        $data['seat_no'] = $this->sale_lib_sms->get_seat_no();
        $data['time_in'] = $this->sale_lib_sms->get_time_in();
        $data['time_out'] = $this->sale_lib_sms->get_time_out();
        //$data['modes']=array('sale'=>lang('sales_sale'),'return'=>lang('sales_return'));
        $data['modes']= "Sale";
        $data['mode'] = $this->sale_lib->get_mode();
        $data['items_in_cart'] = $this->sale_lib_sms->get_items_in_cart();
        $data['subtotal'] = $this->sale_lib_sms->get_subtotal();
        // $data['taxes'] = $this->sale_massage_lib->get_taxes();
        $data['total'] = $this->sale_lib_sms->get_total();
        $data['items_module_allowed'] = $this->Employee->has_module_permission('massages', $person_info->employee_id);
        $data['comment'] = $this->sale_lib_sms->get_comment();
        $data['show_comment_on_receipt'] = $this->sale_lib_sms->get_comment_on_receipt();
        $data['email_receipt'] = $this->sale_lib_sms->get_email_receipt();
        $data['payments_total'] = $this->sale_lib_sms->get_payments_totals();
        $data['amount_due'] = $this->sale_lib_sms->get_amount_due();

        $data['payments'] = $this->sale_lib->get_payments();
        $data['commissioner_price'] = $this->sale_lib->get_commissioner_price();
        $data['supplierId'] = $this->massage->select_supplier_id();
        $data['supplier_type_Id'] = $this->massage->select_massage_type_id();
        $data['change_sale_date_enable'] = $this->sale_lib->get_change_sale_date_enable();
        $data['change_sale_date'] = $this->sale_lib->get_change_sale_date();
        $data['tip_price'] = $this->sale_lib->get_tip_price();

//         $data['payment_options'] = "Cash";
         if ($this->config->item('enable_credit_card_processing'))
        {
            $data['payment_options']=array(
                lang('sales_cash') => lang('sales_cash'),
                lang('sales_check') => lang('sales_check'),
                lang('sales_credit') => lang('sales_credit'),
                lang('sales_giftcard') => lang('sales_giftcard')
            );                
        }
        else
        {
            $data['payment_options']=array(
                lang('sales_cash') => lang('sales_cash'),
                lang('sales_check') => lang('sales_check'),
                lang('sales_giftcard') => lang('sales_giftcard'),
                lang('sales_debit') => lang('sales_debit'),
                lang('sales_credit') => lang('sales_credit'),
                lang('sales_visa_card') => lang('sales_visa_card'),
                lang('sales_master_card') => lang('sales_master_card'),
                lang('sales_western_union') => lang('sales_western_union') 
                );            
        }
        
        foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
        {
            $data['payment_options'][$additional_payment_type] = $additional_payment_type;
        }


        $customer_id = $this->sale_lib->get_customer();

        if ($customer_id != -1) {
            $info = $this->Customer->get_info($customer_id);
            $data['customer'] = $info->first_name . ' ' . $info->last_name . ($info->company_name == '' ? '' : ' (' . $info->company_name . ')');

            $data['customer_email'] = $info->email;
            $data['customer_id'] = $customer_id;
            $data['customer_cc_token'] = $info->cc_token;
            $data['customer_cc_preview'] = $info->cc_preview;
            $data['save_credit_card_info'] = $this->sale_lib->get_save_credit_card_info();
            $data['use_saved_cc_info'] = $this->sale_lib->get_use_saved_cc_info();
        }
        $data['payments_cover_total'] = $this->_payments_cover_total();

        $commissioner_id = $this->sale_lib->get_commissioner();
        $data["commissioner_id"] = $commissioner_id;
        if($commissioner_id!=-1)
        {
            $info_comm = $this->commissioner->get_info($commissioner_id);
            $data['commissioner'] = $info_comm->first_name.' '.$info_comm->last_name.($info_comm->tel==''  ? '' :' ('.$info_comm->tel.')');
        }

        $person_id = $this->sale_lib->get_massager();
        $data["massager_id"] = $person_id;
        if ($person_id != -1) {
            $info_massager = $this->Employee->get_info($person_id);
            $data['massager'] = $info_massager->first_name . ' ' . $info_massager->last_name . ($info_massager->phone_number == '' ? '' : ' (' . $info_massager->phone_number . ')');
        }
        
        if ($is_ajax)
        {
            $this->load->view("sales/register",$data);
        }
        else
        {
            $this->load->view("sales/register_initial",$data);
        }
    }

    // search auto complete massage item sale function
    function item_search_suggestion() {
        $suggestions = $this->massage_item->get_item_search_suggestions_for_sale($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

    //delect customer in sale process
    function delete_customer() {
        $this->sale_lib->delete_customer();
        $this->_reload();
    }

    function set_comment_on_receipt() {
        $this->sale_lib->set_comment_on_receipt($this->input->post('show_comment_on_receipt'));
    }
    function set_comment(){
        $this->sale_lib->set_comment($this->input->post('comment'));
    }

    // add massage item
    function add() {
        
        $data = array();
        $mode = $this->sale_massage_lib->get_mode();
        $mode = $this->sale_lib_sms->get_mode();
        $item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");

        $quantity = $mode == "sale" ? 1 : -1;
        if ($this->sale_massage_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) && $mode == 'return') {
            $this->sale_massage_lib->return_entire_sale($item_id_or_number_or_item_kit_or_receipt);
        } elseif ($this->sale_massage_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt)) {
            //As surely a Kit item , do out of stock check
            $item_kit_id = $this->sale_massage_lib->get_valid_item_kit_id($item_id_or_number_or_item_kit_or_receipt);

            $out_of_stock_kit = $this->sale_massage_lib->out_of_stock_kit($item_kit_id);

            if ($out_of_stock_kit) {
                $data['warning'] = lang('sales_quantity_less_than_zero');
            } else {
                // Store in session Cart
                $this->sale_massage_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $quantity);
            }
        } elseif (!$this->sale_massage_lib->add_item($item_id_or_number_or_item_kit_or_receipt, $quantity)) {
            $data['error'] = lang('sales_unable_to_add_item');
        }

        if ($this->sale_massage_lib->out_of_stock($item_id_or_number_or_item_kit_or_receipt)) {
            $data['warning'] = lang('sales_quantity_less_than_zero');
        }

        $this->_reload($data);
    }

    function _payments_cover_total() {
        $total_payments = 0;

        foreach ($this->sale_lib_sms->get_payments() as $payment) {
            $total_payments += $payment['payment_amount'];
        }

        /* Changed the conditional to account for floating point rounding */
        if (( $this->sale_lib_sms->get_mode() == 'sale' ) && ( ( to_currency_no_money($this->sale_lib_sms->get_total()) - $total_payments ) > 1e-6 )) {
            return false;
        }

        return true;
    }

    //Alain Multiple Payments
    function add_payment() {

        $data = array();
        $this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo 'if';

            if ($this->input->post('payment_type') == lang('sales_giftcard'))
                $data['error'] = lang('sales_must_enter_numeric_giftcard');
            else
                $data['error'] = lang('sales_must_enter_numeric');

            $this->_reload($data);
            return;
        }

        $payment_type = $this->input->post('payment_type');
        $payment_total = $this->input->post('payment_total');
        $payment_amount = $this->input->post('amount_tendered');

        //var_dump($payment_total);

        

        if (!$this->sale_massage_lib->add_payment($payment_type, $payment_amount, $payment_total)) {
            $data['error'] = lang('sales_unable_to_add_payment');
        }
        $this->_reload($data);
    }

    function complete() {
        $person_info = $this->Employee->get_logged_in_employee_info();
        $office_name = $this->session->userdata("office_number");
       
        $data['allowed_modules'] = $this->check_module_accessable();
        $data['controller_name'] = strtolower(get_class());
        $data['is_sale'] = TRUE;
        $data['cart'] = $this->sale_massage_lib->get_cart();
        // $data['tip_price'] = $this->sale_lib->get_tip_price();

        $data['subtotal'] = $this->sale_massage_lib->get_subtotal();
        $data['total'] = $this->sale_massage_lib->get_total();      
        $data['total_in_riels'] = $this->sale_lib->get_total_in_riels($data['total'], $this->Office->get_office_default_currency($office_name));
        $data['receipt_title'] = lang('sales_receipt');
        $customer_id = $this->sale_lib->get_customer();
        $employee_id = $person_info->employee_id;
        $data['comment'] = $this->sale_lib->get_comment();
        $data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
        $emp_info = $this->Employee->get_info($employee_id);
        $data['payments'] = $this->sale_massage_lib->get_payments();
        $data['amount_change'] = $this->sale_massage_lib->get_amount_due_round() * -1;
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;
        $data['ref_no'] = $this->session->flashdata('ref_no') ? $this->session->flashdata('ref_no') : '';

        $commissioner_id=$this->sale_lib->get_commissioner();
        $commissioner_price = $this->sale_lib->get_commissioner_price();

        $data['change_sale_date'] = $this->sale_lib_sms->get_change_sale_date_enable() ? $this->sale_lib_sms->get_change_sale_date() : false;

        $old_date = $this->sale_lib_sms->get_change_sale_id() ? $this->Sale_massage->get_info($this->sale_lib_sms->get_change_sale_id())->row_array() : false;

        $old_date = $old_date ? date(get_date_format() . ' ' . get_time_format(), strtotime($old_date['sale_time'])) : date(get_date_format() . ' ' . get_time_format());

        $data['transaction_time'] = $this->sale_lib_sms->get_change_sale_date_enable() ? date(get_date_format() . ' ' . get_time_format(), strtotime($this->sale_lib_sms->get_change_sale_date())) : $old_date;

        if ($customer_id != -1) {
            $cust_info = $this->Customer->get_info($customer_id);
            $data['customer'] = $cust_info->first_name . ' ' . $cust_info->last_name . ($cust_info->company_name == '' ? '' : ' (' . $cust_info->company_name . ')');
        }

        if($commissioner_id!=-1)
        {
            $comm_info=$this->commissioner->get_info($commissioner_id);
            $data['commissioner']=$comm_info->first_name.' '.$comm_info->last_name.($comm_info->tel==''  ? '' :' ('.$comm_info->tel.')');
        }
        // $massager_id = $this->sale_lib->get_massager();

        // set variable time in for get time in from session of sale massage
        $data['time_in'] = $this->sale_lib_sms->get_time_in();
        // set variable time out for get time out from session of sale massge
        // $data['time_out'] = $this->sale_lib_sms->get_time_out();
        $office_info = $this->Office->get_info($this->session->userdata('office_id'));

        date_default_timezone_set($office_info->ofc_timezone);
        $date = date('m/d/Y h:i:s a', time());
        $dates = explode(' ', $date);

        $data['time_out'] = $dates['1'];
        
        $suspended_change_sale_id = $this->sale_lib->get_suspended_sale_id() ? $this->sale_lib->get_suspended_sale_id() : $this->sale_lib->get_change_sale_id() ;
        //SAVE sale to database
        $data['sale_id'] = strtoupper($office_name).' ' . $this->Sale_massage->save($office_name,$data['cart'], $customer_id, $employee_id, $commissioner_id, $commissioner_price,$data['controller_name'], $data['time_in'], $data['time_out'], $data['comment'], $data['show_comment_on_receipt'], $data['payments'], $suspended_change_sale_id, 0, $data['ref_no']);
 
        if ($data['sale_id'] == strtoupper($office_name).' -1') {
            $data['error_message'] = '';
            // Sale_helper, location helpers/sale_helper.php
            if (is_sale_integrated_cc_processing()) {
                $data['error_message'].=lang('sales_credit_card_transaction_completed_successfully') . '. ';
                // } else {
                //     $data['error_message'] .= lang('sales_transaction_failed');
            }
           
            $data['error_message'] .= lang('sales_transaction_failed');
        } else {
            if ($this->sale_massage_lib->get_email_receipt() && !empty($cust_info->email)) {
                $this->load->library('email');
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->from($this->config->item('email'), $this->config->item('company'));
                $this->email->to($cust_info->email);

                $this->email->subject(lang('sales_receipt'));
                $this->email->message($this->load->view("sales/receipt_email", $data, true));
                $this->email->send();
            }
        }

        $this->load->view("sales/receipt", $data);
         $this->sale_lib->clear_all();
         $this->sale_lib_sms->clear_all();
    }

    // Select Customer for sale process
    function select_customer() {
        $data = array();
        $customer_id = $this->input->post("term");

        if ($this->Customer->account_number_exists($customer_id)) {
            $customer_id = $this->Customer->customer_id_from_account_number($customer_id);
        }

        if ($this->Customer->exists($customer_id)) {
            $this->sale_lib->set_customer($customer_id);
            if ($this->config->item('automatically_email_receipt')) {
                $this->sale_lib->set_email_receipt(1);
            }
        } else {
            $data['error'] = lang('sales_unable_to_add_customer');
        }
        $this->_reload($data);
    }

   // Select commissioner
    function select_commissioner()
    {
        $data = array();
        $commissioner_id = $this->input->post("term");
        if ($this->commissioner->exists($commissioner_id))
        {
            $this->sale_lib->set_commissioner($commissioner_id);
        }
        else
        {
            $data['error']=lang('sales_unable_to_add_commissioner');
        }
        $this->_reload($data);
    }

    function delete_commissioner()
    {
        $this->sale_lib->delete_commissioner();
        $this->_reload();
    }

    function set_commissioner_price()
    {
        $commissioner_price = $this->input->post("commissioner_price");
        $this->sale_lib->set_commissioner_price($commissioner_price);
        $this->_reload();
    }

    // Change sale on receipt
    function change_sale($office, $sale_id)
    {
        $this->check_action_permission('edit_sale');
        $this->sale_lib_sms->clear_all();
        $this->sale_lib->clear_all();
        $this->sale_massage_lib->copy_entire_sale($sale_id, strtolower(get_class()));
        $this->sale_lib->set_change_sale_id($sale_id);

        $this->_reload(array(), false);
    }

    // Use as Glogal 
    function set_change_sale_date() 
    {
      $this->sale_lib->set_change_sale_date($this->input->post('change_sale_date'));
    }

    function set_change_sale_date_enable() 
    {
      $this->sale_lib->set_change_sale_date_enable($this->input->post('change_sale_date_enable'));
      if (!$this->sale_lib->get_change_sale_date())
      {
          $this->sale_lib->set_change_sale_date(date(get_date_format()));
      }
    }

    
    function start_cc_processing() {
        $service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/hcws/hcservice.asmx?WSDL' : 'https://hc.mercurypay.com/hcws/hcservice.asmx?WSDL';
        $cc_amount = to_currency_no_money($this->sale_massage_lib->get_payment_amount(lang('sales_credit')));
        $tax_amount = to_currency_no_money(($this->sale_massage_lib->get_total() - $this->sale_massage_lib->get_subtotal()) * ($cc_amount / $this->sale_massage_lib->get_total()));
        $customer_id = $this->sale_massage_lib->get_customer();
        $customer_name = '';
        if ($customer_id != -1) {
            $customer_info = $this->Customer->get_info($customer_id);
            $customer_name = $customer_info->first_name . ' ' . $customer_info->last_name;
        }

        if (!$this->sale_massage_lib->get_use_saved_cc_info()) {
            $invoice_number = substr((date('mdy')) . (time() - strtotime("today")) . ($this->Employee->get_logged_in_employee_info()->employee_id), 0, 16);

            $parameters = array(
                'request' => array(
                    'MerchantID' => $this->config->item('merchant_id'),
                    'Password' => $this->config->item('merchant_password'),
                    'TranType' => $cc_amount > 0 ? 'Sale' : 'Return',
                    'TotalAmount' => abs($cc_amount),
                    'PartialAuth' => 'On',
                    'Frequency' => 'OneTime',
                    'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->Employee->get_logged_in_employee_info()->employee_id,
                    'Invoice' => $invoice_number,
                    'Memo' => 'PHP POS ' . APPLICATION_VERSION,
                    'TaxAmount' => abs($tax_amount),
                    'CardHolderName' => $customer_name,
                    'ProcessCompleteUrl' => site_url('massages/finish_cc_processing'),
                    'ReturnUrl' => site_url('massages/cancel_cc_processing'),
                )
            );

            if (isset($customer_info) && $customer_info->zip) {
                $parameters['request']['AVSZip'] = $customer_info->zip;
            }

            $client = new SoapClient($service_url, array('trace' => TRUE));
            $result = $client->InitializePayment($parameters);
            $response_code = $result->InitializePaymentResult->ResponseCode;

            if ($response_code == 0) {
                $payment_id = $result->InitializePaymentResult->PaymentID;
                $hosted_checkout_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/CheckoutPOS.aspx' : 'https://hc.mercurypay.com/CheckoutPOS.aspx';
                $this->load->view('sales/hosted_checkout', array('payment_id' => $payment_id, 'hosted_checkout_url' => $hosted_checkout_url));
            } else {
                $this->_reload(array('error' => lang('sales_credit_card_processing_is_down')), false);
            }
        } elseif ($customer_info->cc_token) { //We have saved credit card information, process it
            $service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/tws/transactionservice.asmx?WSDL' : 'https://hc.mercurypay.com/tws/transactionservice.asmx?WSDL';
            $client = new SoapClient($service_url, array('trace' => TRUE));
            $invoice_number = substr((date('mdy')) . (time() - strtotime("today")) . ($this->Employee->get_logged_in_employee_info()->employee_id), 0, 16);

            $parameters = array(
                'request' => array(
                    'Token' => $customer_info->cc_token,
                    'MerchantID' => $this->config->item('merchant_id'),
                    'PurchaseAmount' => abs($cc_amount),
                    'PartialAuth' => FALSE,
                    'Frequency' => 'OneTime',
                    'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->Employee->get_logged_in_employee_info()->employee_id,
                    'Invoice' => $invoice_number,
                    'Memo' => 'PHP POS ' . APPLICATION_VERSION,
                    'TaxAmount' => abs($tax_amount),
                    'CardHolderName' => $customer_name,
                ),
                'password' => $this->config->item('merchant_password'),
            );

            if (isset($customer_info) && $customer_info->zip) {
                $parameters['request']['Zip'] = $customer_info->zip;
            }
            $result = $client->CreditSaleToken($parameters);

            $status = $result->CreditSaleTokenResult->Status;


            if ($status == 'Approved') {
                $token = $result->CreditSaleTokenResult->Token;
                $ref_no = $result->CreditSaleTokenResult->RefNo;

                $person_info = array('person_id' => $this->sale_massage_lib->get_customer());
                $customer_info = array('cc_token' => $token);
                $this->Customer->save($person_info, $customer_info, $this->sale_massage_lib->get_customer());
                $this->session->set_flashdata('ref_no', $ref_no);
                redirect(site_url('massages/complete'));
            } else {
                //If we have failed, remove cc token and cc preview
                $person_info = array('person_id' => $this->sale_massage_lib->get_customer());
                $customer_info = array('cc_token' => NULL, 'cc_preview' => NULL);
                $this->Customer->save($person_info, $customer_info, $this->sale_massage_lib->get_customer());

                //Clear cc token for using saved cc info
                $this->sale_massage_lib->clear_use_saved_cc_info();
                $this->_reload(array('error' => lang('sales_charging_card_failed_please_try_again')), false);
            }
        }
    }

    //set time in of sale massge
    function set_time_in() {
        $this->sale_lib_sms->set_time_in($this->input->post("times"));

        $this->_reload();
    }

    // set time out of sale massage

    function set_time_out() {
        $this->sale_lib_sms->set_time_out($this->input->post("times"));

        $this->_reload();
    }
    
     // edit report
     function edit($office, $sale_id)
    {
        $data = array();
        $data['office'] = $office;

        $data['customers'] = array('' => 'No Customer');
        foreach ($this->Customer->get_all()->result() as $customer)
        {
            $data['customers'][$customer->customer_id] = $customer->first_name . ' '. $customer->last_name;
        }

        $data['commissioners'] = array('' => 'No Commissioner');
        foreach ($this->commissioner->get_all()->result() as $commissioner)
        {
            $data['commissioners'][$commissioner->commisioner_id] = $commissioner->first_name . ' '. $commissioner->last_name;
        }

        $data['employees'] = array();
        foreach ($this->Employee->get_all()->result() as $employee)
        {
            $data['employees'][$employee->employee_id] = $employee->first_name . ' '. $employee->last_name;
        }

        $data['sale_info'] = $this->Sale->get_info($sale_id)->row_array();

        $data['allowed_modules'] = $this->check_module_accessable();
        $data['controller_name'] = strtolower(get_class());
     
        $this->load->view('sales/edit', $data);
    }
  // Save sale ticket after report detail
    function save_sales($office, $sale_id)
    {
        $sale_data = array(
            'sale_time' => date('Y-m-d', strtotime($this->input->post('date'))),
            'customer_id' => $this->input->post('customer_id') ? $this->input->post('customer_id') : null,
            'employee_id' => $this->input->post('employee_id'),
//            'commisioner_id' => $this->input->post('commissioner_id'),
            'commision_price' => $this->input->post('commissioner_price'),
            'deposit' => $this->input->post('deposit_price'),
            'comment' => $this->input->post('comment'),
            'show_comment_on_receipt' => $this->input->post('show_comment_on_receipt') ? 1 : 0
        );
        
        if ($this->Sale->update($sale_data, $sale_id))
        {
            echo json_encode(array('success'=>true,'message'=>lang('sales_successfully_updated')));
        }
        else
        {
            echo json_encode(array('success'=>false,'message'=>lang('sales_unsuccessfully_updated')));
        }
    }
    
     // Receipt of sale
    function receipt($office, $sale_id)
    {
        $data['allowed_modules'] = $this->check_module_accessable();
        $data['is_sale'] = FALSE;
        $sale_info = $this->Sale->get_info($sale_id)->row_array();
        $this->sale_lib->clear_all();
        $this->sale_lib_sms->clear_all();
        $this->sale_massage_lib->copy_entire_sale($sale_id);
        $data['cart']=$this->sale_lib->get_cart(); 
        $data['payments']=$this->sale_lib->get_payments();
        $data['show_payment_times'] = TRUE;
        $data['controller_name'] = strtolower(get_class());
        $data['subtotal']=$this->sale_lib->get_subtotal();
        $data['total']=$this->sale_lib->get_total($sale_id);
        $data['receipt_title']=lang('sales_receipt');
        $data['comment'] = $this->Sale->get_comment($sale_id);
        $data['show_comment_on_receipt'] = $this->Sale->get_comment_on_receipt($sale_id);
        $data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($sale_info['sale_time']));
        $customer_id=$this->sale_lib->get_customer();
        $emp_info=$this->Employee->get_info($sale_info['employee_id']);
        $data['payment_type']=$sale_info['payment_type'];
        $data['amount_change']=$this->sale_lib->get_amount_due($sale_id) * -1;
        $data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
        $data['ref_no'] = $sale_info['cc_ref_no'];
        // $data['total_in_riels'] = $this->sale_lib->get_total_in_riels($data['total'], $this->config->item('default_currency'));
        $data['total_in_riels'] = $this->sale_lib->get_total_in_riels($data['total'], $this->Office->get_office_default_currency($office));
        if($customer_id!=-1)
        {
            $cust_info=$this->Customer->get_info($customer_id);
            $data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' ('.$cust_info->company_name.')');
        }
        $data['sale_id']= strtoupper($this->session->userdata("office_number")).' '.$sale_id;
        
        // set variable time in for get time in from session of sale massage
        $data['time_in'] = $this->sale_lib_sms->get_time_in();
        // set variable time out for get time out from session of sale massge
        $data['time_out'] = $this->sale_lib_sms->get_time_out();

        $this->load->view("sales/receipt",$data);
        $this->sale_lib->clear_all();
        $this->sale_lib_sms->clear_all();

    }

     // Delete entire sale after report detail
    function delete_entire_sale($office, $sale_id)
    {
        $data = array();
        $data['allowed_modules'] = $this->check_module_accessable();

        if ($this->Sale_massage->delete($sale_id, false, $office))
        {
            $data['success'] = true;
        }
        else
        {
            $data['success'] = false;
        }
        
        $this->load->view('sales/delete', $data);
        
    }

    function undelete($office, $sale_id)
    {
        $data = array();
        $data['allowed_modules'] = $this->check_module_accessable();
        
        if ($this->Sale_massage->undelete($office, $sale_id))
        {
            $data['success'] = true;
        }
        else
        {
            $data['success'] = false;
        }
        
        $this->load->view('sales/undelete', $data);
        
    }
    
     function suspend()
    {
        $office_name = $this->session->userdata("office_number");
        $data['controller_name'] = strtolower(get_class());
        $data['cart'] = $this->sale_lib_sms->get_cart();
        /*$data['time_in'] = $this->sale_lib_sms->get_time_in();
        $data['time_out'] = $this->sale_lib_sms->get_time_out();*/
        // Get Time_zone of office
        $office_info = $this->Office->get_info($this->session->userdata('office_id'));

        date_default_timezone_set($office_info->ofc_timezone);
        $date = date('m/d/Y h:i:s a', time());
        $dates = explode(' ', $date);

        $data['time_in'] = $dates['1'];
        $data['time_out'] = $this->sale_lib_sms->get_time_out();
        //$data['modes']=array('sale'=>lang('sales_sale'),'return'=>lang('sales_return'));
        $data['modes']= "Sale";
        $data['mode'] = $this->sale_lib->get_mode();
        $data['items_in_cart'] = $this->sale_lib_sms->get_items_in_cart();
        $data['subtotal'] = $this->sale_lib_sms->get_subtotal();
        // $data['taxes'] = $this->sale_massage_lib->get_taxes();
        $data['total'] = $this->sale_lib_sms->get_total();
        $data['items_module_allowed'] = $this->Employee->has_module_permission('massages', $person_info->employee_id);
        $data['comment'] = $this->sale_lib_sms->get_comment();
        $data['show_comment_on_receipt'] = $this->sale_lib_sms->get_comment_on_receipt();
        $data['email_receipt'] = $this->sale_lib_sms->get_email_receipt();
        $data['payments_total'] = $this->sale_lib_sms->get_payments_totals();
        $data['amount_due'] = $this->sale_lib_sms->get_amount_due();

        $data['payments'] = $this->sale_lib->get_payments();
        $data['commissioner_price'] = $this->sale_lib->get_commissioner_price();
        $data['supplierId'] = $this->massage->select_supplier_id();
        $data['supplier_type_Id'] = $this->massage->select_massage_type_id();
        $data['change_sale_date_enable'] = $this->sale_lib->get_change_sale_date_enable();
        $data['change_sale_date'] = $this->sale_lib->get_change_sale_date();
        $data['ref_no'] = $this->session->flashdata('ref_no') ? $this->session->flashdata('ref_no') : '';

        $data['receipt_title'] = lang('sales_receipt');
        $data['transaction_time'] = date(get_date_format().' '.get_time_format());
        $customer_id = $this->sale_lib->get_customer();
        $employee_id = $this->Employee->get_logged_in_employee_info()->employee_id;

        $commissioner_id=$this->sale_lib->get_commissioner();
        $commissioner_price = $this->sale_lib->get_commissioner_price();
        $comment = $this->sale_lib->get_comment();
        $show_comment_on_receipt = $this->sale_lib->get_comment_on_receipt();
        $emp_info = $this->Employee->get_info($employee_id);
        //Alain Multiple payments
        $data['payments'] = $this->sale_lib->get_payments();
        $data['amount_change'] = $this->sale_lib->get_amount_due() * -1;
        $data['employee'] = $emp_info->first_name.' '.$emp_info->last_name;

        if($customer_id!=-1)
        {
            $cust_info = $this->Customer->get_info($customer_id);
            $data['customer'] = $cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' ('.$cust_info->company_name.')');
        }

        $total_payments = 0;

        foreach($data['payments'] as $payment)
        {
            $total_payments += $payment['payment_amount'];
        }
        // $massager_id = $this->sale_lib->get_massager();
        
        $sale_id = $this->sale_lib->get_suspended_sale_id();
        //SAVE sale to database
        // $tip_price = 0;

        $data['sale_id'] = strtoupper($office_name).' ' . $this->Sale_massage->save($office_name,$data['cart'], $customer_id, $employee_id, $commissioner_id, $commissioner_price,$data['controller_name'], $data['time_in'], $data['time_out'], $data['comment'], $data['show_comment_on_receipt'], $data['payments'], $sale_id, 1, $data['ref_no']);
        if ($data['sale_id'] == strtoupper($office_name).' -1')
        {
            $data['error_message'] = lang('sales_transaction_failed');
        }
        $this->sale_lib->clear_all();
        $this->sale_lib_sms->clear_all();
        $this->_reload(array('success' => lang('sales_successfully_suspended_sale')));

    }

    // Cancel sale
    function cancel_sale()
    {
        // if (!$salse_obj->_void_partial_transactions())
        if (!$this->_void_partial_transactions())
        {
            $this->_reload(array('error' => lang('sales_attempted_to_reverse_partial_transactions_failed_please_contact_support')), true);
        }
        
        $this->sale_lib->clear_all();
        $this->sale_lib_sms->clear_all();
        $this->_reload();

    }
     function _void_partial_transactions()
    {
        $void_success = true;
        
        if ($partial_transactions = $this->sale_lib->get_partial_transactions())
        {
            $service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/tws/transactionservice.asmx?WSDL': 'https://hc.mercurypay.com/tws/transactionservice.asmx?WSDL';
            
            foreach($partial_transactions as $partial_transaction)
            {
                $parameters = array(
                    'request' => $partial_transaction,
                    'password' => $this->config->item('merchant_password'),
                );
                
                $client = new SoapClient($service_url,array('trace' => TRUE));
                $result = $client->CreditReversalToken($parameters);
                
                $status = $result->CreditReversalTokenResult->Status;
                if ($status != 'Approved')
                {
                    unset($parameters['AcqRefData']);
                    unset($parameters['ProcessData']);
                    $result = $client->CreditVoidSaleToken($parameters);
                    $status = $result->CreditVoidSaleTokenResult->Status;
                    
                    if ($status != 'Approved')
                    {
                        $void_success = false;
                    }
                }
            }
        }      
        return $void_success;
    }
    
     // View/show sale that suspended
    function suspended()
    {
        $data = array();
        $data['suspended_sales'] = $this->sale->get_all_suspended(strtolower(get_class()))->result_array();
        echo json_encode($data['suspended_sales']);
    }

// Unsuspend on sale id and show to sale
    function unsuspend($office, $sale_id)
    {
        // $sale_id = $this->input->post('suspended_sale_id');
        $this->sale_lib->clear_all();
        $this->sale_lib_sms->clear_all();
        $this->sale_massage_lib->copy_entire_sale($sale_id, strtolower(get_class()));
        $this->sale_lib->set_suspended_sale_id($sale_id);
        $this->_reload(array(), false);
    }

    function delete_suspended_sale()
    {
        $suspended_sale_id = $this->input->post('suspended_sale_id');
        if ($suspended_sale_id)
        {
          $this->sale_lib->delete_suspended_sale_id();
             $this->Sale_massage->delete($suspended_sale_id);
        }
        $this->_reload(array('success' => lang('sales_successfully_deleted')), false);
    }

    /*
      Inserts/updates an employee
     */
    function save_massager($employee_id=-1)
    {
        $this->check_action_permission('add_update');
        $person_data = array(
            'first_name'=>$this->input->post('first_name'),
            'last_name'=>$this->input->post('last_name'),
            'email'=>$this->input->post('email'),
            'phone_number'=>$this->input->post('phone_number'),
            'address_1'=>$this->input->post('address_1'),
            'address_2'=>$this->input->post('address_2'),
            'city'=>$this->input->post('city'),
            'state'=>$this->input->post('state'),
            'zip'=>$this->input->post('zip'),
            'country'=>$this->input->post('country'),
            'comments'=>$this->input->post('comments')
        );
        
        $employee_data=array(
        'username'=>$this->input->post('first_name').'.'.$this->input->post('last_name'),
        'position_id' => $this->input->post("position")
        );

        if ($this->massage->save_massager($person_data,$employee_data, $employee_id))
        {            
            //New employee
            if($employee_id==-1)
            {
                echo json_encode(array('success'=>true,'message'=>lang('employees_successful_adding').' '.
                $person_data['first_name'].' '.$person_data['last_name'],'person_id'=>$employee_data['employee_id']));
            }
            else //previous employee
            {
                echo json_encode(array('success'=>true,'message'=>lang('employees_successful_updating').' '.
                $person_data['first_name'].' '.$person_data['last_name'],'person_id'=>$employee_id));
            }
        }
        else//failure
        {   
            echo json_encode(array('success'=>false,'message'=>lang('employees_error_adding_updating').' '.
            $person_data['first_name'].' '.$person_data['last_name'],'person_id'=>-1));
        }
    }

    function select_massager() {
        $data = array();
        $person_id = $this->input->post("term");
        if ($this->Employee->exists($person_id)) {
            $this->sale_lib->set_massager($person_id);
        } else {
            $data['error'] = lang('sales_unable_to_add_commissioner');
        }
        $this->_reload($data);
    }

    function delete_massager()
    {
        $this->sale_lib->delete_massger();
        $this->_reload();
    }

    function massager_search() {
        $suggestions = $this->Employee->search_massager_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }

}
?>