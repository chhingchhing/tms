<?php

//require_once ("person_controller.php");
require_once 'secure_area.php';

//class Bikes extends Person_controller {
class Bikes extends Secure_area {

    function __construct() {
        parent::__construct('bikes');
        $this->load->model(array('bike','sales/bikes/bike_item','sale_bike'));
        $this->load->library('Sale_bike_lib');
        $this->load->library('sale_lib');
        
         $this->load->model(array('sale','commissioner','currency_model'));
    }

    function index() {
        $this->manage_bike();
    }

    function manage_bike() {
        $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        $office = substr($this->uri->segment(3), -1);
        $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed

        $this->check_action_permission('search');
        $config['base_url'] = site_url('bikes/bikes/' . $this->uri->segment(3));
        $config['total_rows'] = $this->bike->count_all();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 4;

        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        $data['per_page'] = $config['per_page'];
        $data['manage_bike_data'] = $this->bike->get_all();
        if ($this->uri->segment(4)) {
            $data['manage_table'] = $this->sorting($this->uri->segment(4));
        } else {
            $data['per_page'] = $config['per_page'];
            $data['manage_table'] = get_bike_manage_table($this->bike->get_all($data['per_page']), $this);
        }
        $this->load->view('bikes/manage_bike', $data);
    }

// delete massage from tbl items_massages 
    function delete() {
        $this->check_action_permission('delete');
        $bikes_to_delete = $this->input->post('ids');
        if($this->bike->delete_list($bikes_to_delete))
        {
            echo json_encode(array('success'=>true,'message'=>lang('bikes_successful_deleted').' '.
            count($bikes_to_delete).' '.lang('bikes_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success'=>false,'message'=>lang('bikes_cannot_be_deleted')));
        }
    }

    function sorting($offset) {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;

        if ($search) {
            $config['total_rows'] = $this->bike->search_count_all($search);

            $table_data = $this->bike->search($search, $per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'item_bike_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        } else {
            $config['tpaginational_rows'] = $this->bike->count_all();
            $table_data = $this->bike->get_all($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'item_bike_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        }
        $config['base_url'] = site_url('bikes/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_bike_manage_table($table_data, $this);
        return $data['manage_table'];
    }

    /*
      Returns massages table data rows. This will be called with AJAX.
     */

    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->bike->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'bike_code', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        $config['base_url'] = site_url('bikes/bikes' . $this->uri->segment(3));
        $config['total_rows'] = $this->bike->search_count_all($search);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_bike_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    /*
      Gives search suggestions based on what is being searched for
     */

    function suggest() {
        $suggestions = $this->bike->get_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

    function viewJSON($bike_id = -1) {
        $this->check_action_permission('add_update');
        $data['person_info'] = $this->bike->get_info($bike_id);
        echo json_encode($data['person_info']);
    }

    /*
      Inserts/updates a bike
     */

    function save($bike_id = -1) {
        
        $bike_data = array(
            'bike_code' => $this->input->post("bike_code"),
            'unit_price' => $this->input->post("unit_price"),
            'actual_price' => $this->input->post("actual_price"),
            'bike_types' => $this->input->post("bike_types"),
            'available' => 1
        );
        
        if ($this->bike->save($bike_data, $bike_id)) {
            //New customer
            if ($bike_id == -1) {
                echo json_encode(array('success' => true, 'message' => lang('customers_successful_adding') . ' ' .
                    $bike_data['bike_code'] . ' ' . $bike_data['unit_price'], 'bike_id' => $bike_data['item_bike_id']));
            } else { //previous customer
                echo json_encode(array('success' => true, 'message' => lang('customers_successful_updating') . ' ' .
                    $bike_data['bike_code'] . ' ' . $bike_data['unit_price'], 'bike_id' => $bike_id));
            }
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('customers_error_adding_updating') . ' ' .
                $bike_data['bike_code'] . ' ' . $bike_data['unit_price'], 'bike_id' => -1));
        }
    }

    function check_duplicate() {
        $code_bike = $this->input->post('bike_code');
        echo json_encode(array('duplicate' => $this->bike->check_duplicate($code_bike)));
    }

    /* added for excel expert */

    function excel_export($template = 0) {
        $data = $this->bike->get_all()->result_object();
        echo $data;
        $this->load->helper('report');
        $rows = array();
        $row = array(lang('common_item_module_id'), lang('bikes_bike_code'), lang('bikes_available'), lang('bikes_unit_price'), lang('bikes_actual_price'), lang('bikes_bike_types'));

        $n = 1;
        $rows[] = $row;
        foreach ($data as $r) {
            $row = array(
                $n++,
                $r->bike_code,
                $r->available,
                $r->unit_price,
                $r->actual_price,
                $r->bike_types
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
 // function sale use for show page when click on rent bike 
    function sales() {
        $this->sale_lib->clear_all();

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
        $office = substr($this->uri->segment(3), -1);
        $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $person_info->person_id); //get officle allowed
        $data['controller_name'] = strtolower(get_class());
        $data['cart'] = $this->sale_lib->get_cart();
        $data['date_departure']=$this->sale_lib->get_date_departures();
//        var_dump($data['dates_departure']); //not work
        $data['modes'] = "Sale";
        $data['mode'] = $this->sale_lib->get_mode();
        $data['items_in_cart'] = $this->sale_lib->get_items_in_cart();
        $data['subtotal'] = $this->sale_lib->get_subtotal();
//        var_dump($data['subtotal']);die('subtotal die'); // work
        $data['commissioner_price'] = $this->sale_lib->get_commissioner_price();
        $data['total'] = $this->sale_lib->get_total();
//        var_dump($data['total']);die('total die'); //work
        $data['items_module_allowed'] = $this->Employee->has_module_permission('bikes', $person_info->person_id);
//        var_dump($data['items_module_allowed']);die('items module allowed die'); // work boolean true
        $data['comment'] = $this->sale_lib->get_comment();
//        var_dump($data['comment']);die('comment die'); // work
        $data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
//        var_dump($data['show_comment_on_receipt']);die('die show comment on receipt'); //work
        $data['email_receipt'] = $this->sale_lib->get_email_receipt();
//        var_dump($data['email_receipt']);die('remail recidpt'); //return boolen false
        $data['payments_total'] = $this->sale_lib->get_payments_totals();
//        var_dump($data['payments_total']);die('payment total die');  //work
        $data['amount_due'] = $this->sale_lib->get_amount_due();
//        var_dump($data['amount_due']);die('amount due die'); //work

        $data['payments'] = $this->sale_lib->get_payments();
         $data['deposit_price'] = $this->sale_lib->get_deposit_price();
//        var_dump($data['deposit']);die('deposit die'); //work
//        var_dump($data['commissioner_price']);die('commssioner price die'); //work
        $data['change_sale_date_enable'] = $this->sale_lib->get_change_sale_date_enable();
//        var_dump($data['change_sale_date_enable']);die('change sale date enable'); //WORK
        $data['change_sale_date'] = $this->sale_lib->get_change_sale_date();
//        var_dump($data['change_sale_date']);die('change sale');  //wrok
        
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
                lang('sales_credit') => lang('sales_credit')
                );            
        }
        
        foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
        {
            $data['payment_options'][$additional_payment_type] = $additional_payment_type;
        }
        
        $customer_id = $this->sale_lib->get_customer();
//        echo $customer_id;die('customer id show'); // $customer_id = -1
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

    
        $commissioner_id = $this->sale_lib->get_commissioner();
        $data["commissioner_id"] = $commissioner_id;
        
        if($commissioner_id!=-1)
        {
            $info_comm = $this->commissioner->get_info($commissioner_id);
            $data['commissioner'] = $info_comm->first_name.' '.$info_comm->last_name.($info_comm->tel==''  ? '' :' ('.$info_comm->tel.')');
        }
        if ($is_ajax) {
            $this->load->view("sales/register", $data);
        } else {
            $this->load->view("sales/register_initial", $data);
        }
    }

    // search auto complete bike item rent function
    function item_search_suggestion() {
        $suggestions = $this->bike_item->get_item_search_suggestions_for_sale($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

     function add() {
        $data = array();
        $mode = $this->sale_bike_lib->get_mode();
//        echo $mode;die('mode die'); //work
//        $mode = $this->sale_lib->get_mode();
        $item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");

        $quantity = $mode == "sale" ? 1 : -1;
//        echo $quantity;die(); work == 1
        if ($this->sale_bike_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) && $mode == 'return') {
            $this->sale_bike_lib->return_entire_sale($item_id_or_number_or_item_kit_or_receipt);
       
        } 
//        elseif ($this->sale_bike_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt)) {
//            //As surely a Kit item , do out of stock check
//           echo 'else';
//            $item_kit_id = $this->sale_bike_lib->get_valid_item_kit_id($item_id_or_number_or_item_kit_or_receipt);
//
//            $out_of_stock_kit = $this->sale_bike_lib->out_of_stock_kit($item_kit_id);
//
//            if ($out_of_stock_kit) {
//                $data['warning'] = lang('sales_quantity_less_than_zero');
//            } else {
//                // Store in session Cart
//
//                /***===arrive here===***/
//                $this->sale_bike_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $quantity);
//            }
//        } 
        elseif (!$this->sale_bike_lib->add_item($item_id_or_number_or_item_kit_or_receipt, $quantity)) {
            echo 'elseif';
            $data['error'] = lang('sales_unable_to_add_item');
        }
//        if ($this->sale_massage_lib->out_of_stock($item_id_or_number_or_item_kit_or_receipt)) {
//            $data['warning'] = lang('sales_quantity_less_than_zero');
//        }

        $this->_reload($data);
    }
    
     // Search customer for rend bike process
    /* Customer in rent */
    function customer_search()
    {
        $suggestions = $this->Customer->get_customer_search_suggestions($this->input->post('term'),100);
        echo json_encode($suggestions);
    }
    
    // Select Customer for sale process
    function select_customer()
    {
        $data = array();
        $customer_id = $this->input->post("term");
        
        if ($this->Customer->account_number_exists($customer_id))
        {
            $customer_id = $this->Customer->customer_id_from_account_number($customer_id);
        }
        
        if ($this->Customer->exists($customer_id))
        {
            $this->sale_lib->set_customer($customer_id);
            if($this->config->item('automatically_email_receipt'))
            {
                $this->sale_lib->set_email_receipt(1);
            }
        }
        else
        {
            $data['error']=lang('sales_unable_to_add_customer');
        }
        $this->_reload($data);
    }
    
     function delete_customer()
    {
        $this->sale_lib->delete_customer();
        $this->_reload();
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
    
   //delete item in cart of bike
    function delete_item($office, $item_number)
    {
        $this->sale_bike_lib->delete_item($item_number);
        $this->_reload();
    }
    
     //Alain Multiple Payments
    function add_payment() {
        
        $data = array();
        $this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'required');
        echo 'add_payment';
        if ($this->form_validation->run() == FALSE) {
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
        
        if( !$this->sale_lib->add_payment( $payment_type, $payment_amount, $payment_total ) )
        {
            $data['error']=lang('sales_unable_to_add_payment');
        }

        $this->_reload($data);
    }
    
    // for input of commissioner price
     function set_commissioner_price()
    {
        $commissioner_price = $this->input->post("commissioner_price");
        $this->sale_lib->set_commissioner_price($commissioner_price);
        $this->_reload();
    }
    // for input for deposit 
    
    function set_deposit_price()
    {
        $deposit_price = $this->input->post("deposit_price");
        $this->sale_lib->set_deposit_price($deposit_price);
     
        $this->_reload();
    }
    
    // edit data item for bike in cart
    function edit_item($office, $line)
    {
       $data = array();

        $this->form_validation->set_rules('price', 'lang:items_price', 'required');
        $this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required');

        $description = $this->input->post("description");
        $serialnumber = $this->input->post("serialnumber");
        $price = $this->input->post("price");
        $quantity = $this->input->post("quantity");
        $discount = $this->input->post("discount");
        $date_departure = $this->input->post("dates");

        if ($this->form_validation->run() != FALSE) {
            $this->sale_lib->edit_item($line, $description, $serialnumber, $quantity, $discount, $price);
        } else {
            $data['error'] = lang('sales_error_editing_item');
        }
        
        // Set date departure 
        $array_date_departure = $this->sale_lib->get_date_departures();
        $new_array_date_departure = $this->insertArrayIndex($array_date_departure, $date_departure, $index);
        $this->sale_lib->set_date_departures($new_array_date_departure);

        $this->_reload($data);
    }
    
     /**
     * @insert a new array member at a given index
     * @param array $array
     * @param mixed $new_element
     * @param int $index
     * @return array
     */
     function insertArrayIndex($array, $new_element, $index) {
        $array[$index] = $new_element;
        return $array;
     }
   
}

?>