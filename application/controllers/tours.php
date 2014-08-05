<?php

require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");

class Tours extends Secure_area implements iData_controller {

    function __construct() {
        parent::__construct('tours');
        $this->load->library('sale_tour_lib');
        $this->load->library('sale_lib');
        $this->load->model(array('sale','tour','sale_tour','sales/tours/tour_kit',
            'sales/tours/tour_item','sales/tours/item_kit_tour','commissioner','guide', 'ticket','supplier','currency_model'));
	}
	
	//change text to check line endings
	//new line endings

	function index()
	{		
        $logged_in_employee_info=$this->Employee->get_logged_in_employee_info();
		$data['allowed_modules'] = $this->check_module_accessable();

		$suppliers = array('' => lang('items_none'));
        foreach($this->Supplier->get_all()->result_array() as $row)
        {
            $suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
        }
        $data['supplier']=$suppliers;
        // $data['destination_id'] = $this->ticket->get_destinationID();
		
		$this->check_action_permission('search');
		$config['base_url'] = site_url('tours/tours/' . $this->uri->segment(3));
		$config['total_rows'] = $this->tour->count_all();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        $data['per_page'] = $config['per_page'];
        $data['destination_id'] = $this->tour->get_destinationID();
        $data['supplierId'] = $this->tour->select_supplier_id();
        if ($this->uri->segment(4)) {
            $data['manage_table'] = $this->sorting($this->uri->segment(4));
        } else {

            $data['per_page'] = $config['per_page'];
            $data['manage_table'] = get_tour_manage_table($this->tour->get_tours($data['per_page']), $this);
        }

        $this->load->view('tours/manage_tour', $data);
    }

    function get_tour($tour, $tour_id) {
        $tour_id = $this->input->post("tour_id");
        $tour = array(
            'tour_name' => $this->input->post('tour_name'),
            'action_name_key' => $this->input->post('action_name_key'),
            'sort' => $this->input->post('sort')
        );

        $insert_tour = $this->input->post('submit');
        if (isset($insert_tour)) {
            return $this->tour->insert_tour($tour, $tour_id);
        } else {
            echo '<script>alert("Pelase input data");</script>';
            redirect('tours/tours/world_1');
        }
    }

    function viewJSON($tour_id = -1) {
        $this->check_action_permission('add_update');
        $suppliers = array('' => lang('items_none'));
        foreach($this->Supplier->get_all()->result_array() as $row)
        {
            $suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
        }
        $data['supplier']=$suppliers;
        $data['destination_id'] = $this->tour->get_destinationID(); 
        $data['supplierId'] = $this->tour->select_supplier_id();
        $data['tour_info'] = $this->tour->get_info($tour_id); 

        $this->load->view("tours/_form", $data);
    }

    function sorting($offset) {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        if ($search) {
            $config['total_rows'] = $this->tour->search_count_all($search);
            $table_data = $this->tour->search($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'tour_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        } else {
            $config['total_rows'] = $this->tour->count_all();
            $table_data = $this->tour->get_tours($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'tour_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        }
        $config['base_url'] = site_url('tours/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_tour_manage_table($table_data, $this);
        return $data['manage_table'];
    }

    function check_duplicate() {
        $name = $this->input->post('tour_name');
        $destination = $this->input->post('destinationID');
        echo json_encode(array('duplicate' => $this->tour->check_duplicate($name, $destination)));
    }

    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->tour->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'tour_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        $config['base_url'] = site_url('tours/tours' . $this->uri->segment(3));
        $config['total_rows'] = $this->tour->search_count_all($search);
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_tour_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    /*
      Gives search suggestions based on what is being searched for
     */

    function suggest() {
        $suggestions = $this->tour->get_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

    function get_row() {
        $item_id = $this->input->post('row_id');
        $data_row = get_item_data_row($this->Item->get_info($item_id), $this);
        echo $data_row;
    }

    function view($item_id = -1) {
        $this->check_action_permission('add_update');
        // Alain promotion price...
        $this->load->helper('report');
        $data = array();
        $data['months'] = get_months();
        $data['days'] = get_days();
        $data['years'] = get_years();
        $data['end_years'] = array(date("Y") + 1 => date("Y") + 1) + $data['years'];

        $data['item_info'] = $this->Item->get_info($item_id);
        $data['item_tax_info'] = $this->Item_taxes->get_info($item_id);
        $suppliers = array('' => lang('items_none'));
        foreach ($this->Supplier->get_all()->result_array() as $row) {
            $suppliers[$row['person_id']] = $row['company_name'] . ' (' . $row['first_name'] . ' ' . $row['last_name'] . ')';
        }

        $data['suppliers'] = $suppliers;
        $data['selected_supplier'] = $this->Item->get_info($item_id)->supplier_id;
        $data['default_tax_1_rate'] = ($item_id == -1) ? $this->config->item('default_tax_1_rate') : '';
        $data['default_tax_2_rate'] = ($item_id == -1) ? $this->config->item('default_tax_2_rate') : '';
        $data['default_tax_2_cumulative'] = ($item_id == -1) ? $this->config->item('default_tax_2_cumulative') : '';
        //Alain Promo Price
        if ($item_id == -1) {
            $data['selected_start_year'] = 0;
            $data['selected_start_month'] = 0;
            $data['selected_start_day'] = 0;
            $data['selected_end_year'] = 0;
            $data['selected_end_month'] = 0;
            $data['selected_end_day'] = 0;
        } else {
            list($data['selected_start_year'], $data['selected_start_month'], $data['selected_start_day']) = explode('-', $data['item_info']->start_date);
            list($data['selected_end_year'], $data['selected_end_month'], $data['selected_end_day']) = explode('-', $data['item_info']->end_date);
        }
        $this->load->view("items/form", $data);
    }
    
    function save($tour_id = -1) {  
        // $tour_id = $this->input->post("tour_id");
        $this->check_action_permission('add_update');

        if ($this->input->post('newDes')) {
            $destination = array(
                'destination_name' => $this->input->post('newDes')
            );
            $this->ticket->add_destination($destination);
            $destination_id = $destination['destination_id'];
        }
        
        $tour = array(
            'tour_name' => $this->input->post('tour_name'),
            'destinationID' => $this->input->post('destinationID') == '0' ? $destination_id: $this->input->post('destinationID'),
            'supplier_id' => $this->input->post('supplier_id') == '0' ? null : $this->input->post('supplier_id'),
            'actual_price' => $this->input->post('actual_price') ? $this->input->post('actual_price') : 0.00,
            'sale_price' => $this->input->post('sale_price') ? $this->input->post('sale_price') : 0.00,
            'description' => $this->input->post('description') ? $this->input->post('description') : "",
            // 'departure_date' => $this->input->post('dates') ? date("Y-m-d", strtotime($this->input->post('dates'))) : "",
            // 'departure_time' => $this->input->post('times') ? $this->input->post('times') : "",
            'by' => $this->input->post('by') ? $this->input->post('by') : "",
        );
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
       
        $employee_id = $this->Employee->get_logged_in_employee_info()->employee_id;
        $cur_item_info = $this->tour->get_info($tour_id);

        if ($this->tour->save($tour, $tour_id)) {
            //New item
            if ($tour_id == -1) {
                echo json_encode(array('success' => true, 'message' => lang('items_successful_adding') . ' ' .
                    $tour['tour_name'], 'tour_id' => $tour['tour_id']));
            } else { //previous item
                echo json_encode(array('success' => true, 'message' => lang('items_successful_updating') . ' ' .
                    $tour['name'], 'tour_id' => $tour_id));
            }

            $inv_data = array
                (
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_items' => $tour_id,
                'trans_user' => $employee_id,
                'trans_comment' => lang('items_manually_editing_of_quantity'),
                'trans_inventory' => $cur_item_info ? $this->input->post('quantity') - $cur_item_info->quantity : $this->input->post('quantity'),
                'type_items' => strtolower(get_class())
            );
            $this->Inventory->insert($inv_data);
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('items_error_adding_updating') . ' ' .
                $tour['name'], 'tour_id' => -1));
        }
    }

    //delete tour from tbl tours
    function delete() {
        $this->check_action_permission('delete');
        $tours_to_delete = $this->input->post('checkedID');
        if ($this->tour->delete_list($tours_to_delete)) {
            echo json_encode(array('success' => true, 'message' => lang('tours_successful_deleted')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('tours_cannot_be_deleted')));
        }
    }

    /* added for excel expert */  

    function excel_export($template = 0) {
        $data = $this->tour->get_tours()->result_object();
        $this->load->helper('report');
        $rows = array();
        $row = array(lang('tour_tour_id'), lang('tour_tour_name'), lang('tour_action_name_key'), lang('tour_sort'), lang('tour_deleted'));

        $n = 1;
        $rows[] = $row;
        foreach ($data as $r) {
            $row = array(
                $n ++,
                $r->tour_name,
                $r->action_name_key,
                $r->sort,
                $r->deleted,
            );
            $rows[] = $row;
        }

        $content = array_to_csv($rows);

        if ($template) {
            force_download('tours_export_mass_update.csv', $content);
        } else {
            force_download('tours_export.csv', $content);
        }
        exit;
    }

    /*
      get the width for the add/edit form
     */

    function get_form_width() {
        return 550;
    }

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
        $data['allowed_modules'] = $this->check_module_accessable();

        $data['controller_name'] = strtolower(get_class());
        $data['cart'] = $this->sale_lib->get_cart();
        $data['modes'] = "Sale";
        $data['mode'] = $this->sale_lib->get_mode();
        $data['destination_id'] = $this->ticket->get_destinationID();
        $data['gender'] = array('Female' => lang('common_female'), 'Male' => lang('common_male'));
        $data['dates_departure']=$this->sale_lib->get_date_departures();
        $data['times_departure']=$this->sale_lib->get_times_departure();

        // $data['supplier'] = $this->supplier->get_suppliers();
        $suppliers = array('' => lang('items_none'));
        foreach($this->Supplier->get_all()->result_array() as $row)
        {
            $suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
        }
        $data['supplier']=$suppliers;
        
        $data['items_in_cart'] = $this->sale_lib->get_items_in_cart();
        $data['subtotal'] = $this->sale_lib->get_subtotal();
        $data['total'] = $this->sale_tour_lib->get_total();
        $data['items_module_allowed'] = $this->Employee->has_module_permission('tours', $person_info->employee_id);
        $data['comment'] = $this->sale_lib->get_comment();
        $data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
        $data['email_receipt'] = $this->sale_lib->get_email_receipt();
        $data['payments_total'] = $this->sale_lib->get_payments_totals();
        $data['amount_due'] = $this->sale_tour_lib->get_amount_due();
        $data['payments'] = $this->sale_lib->get_payments();
        $data['commissioner_price'] = $this->sale_lib->get_commissioner_price();
        $data['change_sale_date_enable'] = $this->sale_lib->get_change_sale_date_enable();
        $data['change_sale_date'] = $this->sale_lib->get_change_sale_date();
        $data['deposit_price'] = $this->sale_lib->get_deposit_price();

        // Starting payment type
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
        // End of payment type

        // $data['payment_options'] = "Cash";

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
        if ($commissioner_id != -1) {
            $info_comm = $this->commissioner->get_info($commissioner_id);
            $data['commissioner'] = $info_comm->first_name . ' ' . $info_comm->last_name . ($info_comm->tel == '' ? '' : ' (' . $info_comm->tel . ')');
        }

        $guide_id = $this->sale_lib->get_guide();
        if ($guide_id != -1) {
            $info_guide = $this->guide->get_info($guide_id);
            $data['guide_id'] = $guide_id;
            $data['guides'] = $info_guide->guide_fname . ' ' . $info_guide->guide_lname . ($info_guide->tel == '' ? '' : ' (' . $info_guide->tel . ')');
        }

        if ($is_ajax) {
            $this->load->view("sales/register", $data);
        } else {
            $this->load->view("sales/register_initial", $data);
        }
    }

    function _payments_cover_total() {
        $total_payments = 0;

        foreach ($this->sale_lib->get_payments() as $payment) {
            $total_payments += $payment['payment_amount'];
        }

        /* Changed the conditional to account for floating point rounding */
        if (( $this->sale_lib->get_mode() == 'sale' ) && ( ( to_currency_no_money($this->sale_tour_lib->get_total()) - $total_payments ) > 1e-6 )) {
            return false;
        }

        return true;
    }

    // Search customer for sale process
    /* Customer in sales */
    function customer_search() {
        $suggestions = $this->Customer->get_customer_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
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
    function select_commissioner() {
        $data = array();
        $commissioner_id = $this->input->post("term");
        if ($this->commissioner->exists($commissioner_id)) {
            $this->sale_lib->set_commissioner($commissioner_id);
        } else {
            $data['error'] = lang('sales_unable_to_add_commissioner');
        }
        $this->_reload($data);
    }

    function set_commissioner_price() {
        $commissioner_price = $this->input->post("commissioner_price");
        $this->sale_lib->set_commissioner_price($commissioner_price);
        $this->_reload();
    }

    function item_search_suggestion() {
        $suggestions = $this->tour_item->get_item_search_suggestions_for_sale($this->input->post('term'), 100);
        $suggestions = array_merge($suggestions, $this->tour_kit->get_item_kit_search_suggestions($this->input->post('term'), 100));
        echo json_encode($suggestions);
    }

    function delete_customer() {
        $this->sale_lib->delete_customer();
        $this->_reload();
    }

    function delete_commissioner() {
        $this->sale_lib->delete_commissioner();
        $this->_reload();
    }

    function add() {

        $data = array();
        $mode = $this->sale_lib->get_mode();
        $item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");
        $quantity = $mode == "sale" ? 1 : -1;

        if ($this->sale_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt, strtolower(get_class()))) {
            //As surely a Kit item , do out of stock check
            // $item_kit_id = $this->sale_lib->get_valid_item_kit_id($item_id_or_number_or_item_kit_or_receipt, strtolower(get_class()));

            // Store in session Cart
            $this->sale_tour_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $quantity, strtolower(get_class()));
        }
       /* elseif($this->sale_tour_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
        {
            //As surely a Kit item , do out of stock check
            $item_kit_id = $this->sale_tour_lib->get_valid_item_kit_id($item_id_or_number_or_item_kit_or_receipt);
            $this->sale_tour_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $quantity, strtolower(get_class()));
        }*/
        else {
            if (!$this->sale_tour_lib->add_item($item_id_or_number_or_item_kit_or_receipt, $quantity, strtolower(get_class()))) {
                $data['error'] = lang('sales_unable_to_add_item');
            }
        }

        $this->_reload($data);
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
        $seat_no = $this->input->post("seat_no");
        $date_departure = $this->input->post("dates");
        $time_departure = $this->input->post("times");
        $index = $this->input->post("line")-1;

        if ($this->form_validation->run() != FALSE) {
            $this->sale_lib->edit_item($line, $description, $serialnumber, $quantity, $discount, $price);
        } else {
            $data['error'] = lang('sales_error_editing_item');
        }
        // Check of avaliable for sale in bellow

        // Set date departure 
        $array_date_departure = $this->sale_lib->get_date_departures();
        $new_array_date_departure = $this->insertArrayIndex($array_date_departure, $date_departure, $index);
        $this->sale_lib->set_date_departures($new_array_date_departure);
 
        // Set time departure 
        $array_time_departure = $this->sale_lib->get_times_departure();
        $new_array_time_departure = $this->insertArrayIndex($array_time_departure, $time_departure, $index);
        $this->sale_lib->set_times_departure($new_array_time_departure);

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

    function delete_item($office, $item_number) {
        $this->sale_tour_lib->delete_item($item_number);
        $this->_reload();
    }

    // Select guide
    function select_guide() {
        $data = array();

        $guide_id = $this->input->post("term");
        
        if ($this->guide->exists($guide_id))
        {
            $this->sale_lib->set_guide($guide_id);
        } else {
            $data['error'] = lang('sales_unable_to_add_guide');
        }
        $this->_reload($data);
    }

    function delete_guide() {
        $this->sale_lib->delete_guide();
        $this->_reload();
    }

    // Set time departure
    function set_time_departure() {
        $this->sale_lib->set_time_departure($this->input->post("times"));
        $this->_reload();
    }

    //Alain Multiple Payments
    function add_payment() {
        $data = array();
        $this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'required');

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

        if (!$this->sale_lib->add_payment($payment_type, $payment_amount, $payment_total)) {
            $data['error'] = lang('sales_unable_to_add_payment');
        }

        $this->_reload($data);
    }

    //Alain Multiple Payments
    function delete_payment($payment_id) {
        $this->sale_lib->delete_payment($payment_id);
        $this->_reload();
    }

    function complete() {
        $person_info = $this->Employee->get_logged_in_employee_info();
        $data['allowed_modules'] = $this->check_module_accessable();

        $office_name = $this->session->userdata("office_number");
        $data['dates_departure']=$this->sale_lib->get_date_departures();
        $data['times_departure']=$this->sale_lib->get_times_departure();
        $data['is_sale'] = TRUE;
        $data['cart'] = $this->sale_lib->get_cart();
        $data['subtotal'] = $this->sale_lib->get_subtotal();
        $data['total'] = $this->sale_tour_lib->get_total();
        // $data['total_in_riels'] = $this->sale_lib->get_total_in_riels($data['total'], $this->config->item('default_currency'));
        $data['total_in_riels'] = $this->sale_lib->get_total_in_riels($data['total'], $this->Office->get_office_default_currency($office_name));
        $data['receipt_title'] = lang('sales_receipt');
        $data['deposit_price'] = $this->sale_lib->get_deposit_price();
        $guide_id = $this->sale_lib->get_guide();
        $customer_id = $this->sale_lib->get_customer();
        $commissioner_id = $this->sale_lib->get_commissioner();
        $commissioner_price = $this->sale_lib->get_commissioner_price();
        $employee_id = $person_info->employee_id;

        $data['controller_name'] = strtolower(get_class());
        $data['comment'] = $this->sale_lib->get_comment();
        $data['payments'] = $this->sale_lib->get_payments();
        $data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
        $emp_info = $this->Employee->get_info($employee_id);
        $data['amount_change'] = $this->sale_tour_lib->get_amount_due_round() * -1;
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;
        $data['ref_no'] = $this->session->flashdata('ref_no') ? $this->session->flashdata('ref_no') : '';

        $data['change_sale_date'] = $this->sale_lib->get_change_sale_date_enable() ? $this->sale_lib->get_change_sale_date() : false;
        $old_date = $this->sale_lib->get_change_sale_id() ? $this->sale->get_info($this->sale_lib->get_change_sale_id())->row_array() : false;
        $old_date = $old_date ? date(get_date_format() . ' ' . get_time_format(), strtotime($old_date['sale_time'])) : date(get_date_format() . ' ' . get_time_format());
        $data['transaction_time'] = $this->sale_lib->get_change_sale_date_enable() ? date(get_date_format() . ' ' . get_time_format(), strtotime($this->sale_lib->get_change_sale_date())) : $old_date;

        if ($customer_id != -1) {
            $cust_info = $this->Customer->get_info($customer_id);
            $data['customer'] = $cust_info->first_name . ' ' . $cust_info->last_name . ($cust_info->company_name == '' ? '' : ' (' . $cust_info->company_name . ')');
        }
        if ($commissioner_id != -1) {
            $comm_info = $this->commissioner->get_info($commissioner_id);
            $data['commissioner'] = $comm_info->first_name . ' ' . $comm_info->last_name . ($comm_info->tel == '' ? '' : ' (' . $comm_info->tel . ')');
        }

        if ($guide_id != -1) {
            $info_guide = $this->guide->get_info($guide_id);
            $data['guides'] = $info_guide->guide_fname . ' ' . $info_guide->guide_lname . ($info_guide->tel == '' ? '' : ' (' . $info_guide->tel . ')');
        }

        $suspended_change_sale_id = $this->sale_lib->get_suspended_sale_id() ? $this->sale_lib->get_suspended_sale_id() : $this->sale_lib->get_change_sale_id();
        //SAVE sale to database
        $data['sale_id'] = strtoupper($office_name).' ' . $this->sale_tour->save($office_name,strtolower(get_class()), $data['cart'], $guide_id, $customer_id, $employee_id, $commissioner_id, $commissioner_price,$data['times_departure'],$data['dates_departure'], $data['comment'], $data['show_comment_on_receipt'], $data['payments'], $suspended_change_sale_id, 0, $data['ref_no'], $data['change_sale_date']);

        if ($data['sale_id'] == strtoupper($office_name).' -1') {
            $data['error_message'] = '';
            // Sale_helper, location helpers/sale_helper.php
            if (is_sale_integrated_cc_processing()) {
                $data['error_message'].=lang('sales_credit_card_transaction_completed_successfully') . '. ';
            }
            $data['error_message'] .= lang('sales_transaction_failed');
        } 
        /*else {
            if ($this->sale_lib->get_email_receipt() && !empty($cust_info->email)) {
                $this->load->library('email');
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->from($this->config->item('email'), $this->config->item('company'));
                $this->email->to($cust_info->email);

                $this->email->subject(lang('sales_receipt'));
                $this->email->message($this->load->view("sales/receipt_email", $data, true));
                $this->email->send();
            }
        }*/
        $this->load->view("sales/receipt", $data);
        $this->sale_lib->clear_all();
    }

    // Change sale on receipt
    function change_sale($office, $sale_id) {
        $this->check_action_permission('edit_sale');
        $this->sale_lib->clear_all();
        $this->sale_tour_lib->copy_entire_sale($sale_id, strtolower(get_class()));
        $this->sale_lib->set_change_sale_id($sale_id);

        $this->_reload(array(), false);
    }

    // edit report
    function edit($office, $sale_id)
    {
        $data = array();
        $data['office'] = $this->Office->get_office_name($office);
        // $data['office'] = $office;

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

    // Save sale tours after report detail
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
        $this->sale_tour_lib->copy_entire_sale($sale_id);
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
        $this->load->view("sales/receipt",$data);
        $this->sale_lib->clear_all();

    }
    
    
     // Delete entire sale after report detail
    function delete_entire_sale($office, $sale_id)
    {
        $data = array();
        $data['allowed_modules'] = $this->check_module_accessable();

        if ($this->sale_tour->delete($sale_id, false, $office))
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
        
        if ($this->sale_tour->undelete($office, $sale_id))
        {
            $data['success'] = true;
        }
        else
        {
            $data['success'] = false;
        }
        
        $this->load->view('sales/undelete', $data);
        
    }
    
    
    // Check box to set change sale date enable
    function set_change_sale_date_enable() {
        $this->sale_lib->set_change_sale_date_enable($this->input->post('change_sale_date_enable'));
        if (!$this->sale_lib->get_change_sale_date()) {
            $this->sale_lib->set_change_sale_date(date(get_date_format()));
        }
    }

    // Check box to set the comment on receipt
    function set_comment_on_receipt() {
        $this->sale_lib->set_comment_on_receipt($this->input->post('show_comment_on_receipt'));
    }

    // Set comment to session for putting on receipt
    function set_comment() {
        $this->sale_lib->set_comment($this->input->post('comment'));
    }

    function set_change_sale_date() {
        $this->sale_lib->set_change_sale_date($this->input->post('change_sale_date'));
    }

    function set_deposit_price()
    {
        $deposit_price = $this->input->post("deposit_price");
        $this->sale_lib->set_deposit_price($deposit_price);
        $this->_reload();
    }
 
    /**
    * Starting For Package/Kit
    */
    /* Show Tour Package */
    function list_package()
    { 
        $logged_in_employee_info=$this->Employee->get_logged_in_employee_info();
        $data['allowed_modules'] = $this->check_module_accessable();

        /*$suppliers = array('' => lang('items_none'));
        foreach($this->Supplier->get_all()->result_array() as $row)
        {
            $suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
        }
        $data['supplier']=$suppliers;*/
        // $data['destination_id'] = $this->ticket->get_destinationID();
        
        $this->check_action_permission('search');
        $config['base_url'] = site_url('tours/list_package/' . $this->uri->segment(3));
        $config['total_rows'] = $this->tour->count_all_package();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
        $config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        $data['per_page'] = $config['per_page'];
        /*$data['destination_id'] = $this->tour->get_destinationID();
        $data['supplierId'] = $this->tour->select_supplier_id();*/
        if ($this->uri->segment(4)) {
            $data['manage_table'] = $this->sorting_package($this->uri->segment(4));
        } else {

            $data['per_page'] = $config['per_page'];
            $data['manage_table'] = get_item_kits_manage_table($this->tour->get_all_package($data['per_page']), $this);
        }

        $this->load->view('tours/manage_tour', $data);
    }

    function sorting_package($offset) {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        if ($search) {
            $config['total_rows'] = $this->tour->search_count_all_package($search);
            $table_data = $this->tour->search_package($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        } else {
            $config['total_rows'] = $this->tour->count_all_package();
            $table_data = $this->tour->get_all_package($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        }
        $config['base_url'] = site_url('tours/sorting_package');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_Item_kits_manage_table_data_rows($table_data, $this);
        return $data['manage_table'];
    }

    /* List all Tour Package */
    function view_package($item_kit_id=-1, $category)
    {
        $this->check_action_permission('add_update'); 
        $data['controller_name'] = strtolower(get_class());
        $data['item_kit_info']=$this->tour->get_info_package($item_kit_id, $category);
        $this->load->view("tours/_form_package",$data);
    }

    /**
    * Search item tour for adding to Package Tour
    */
    /*function item_search()
    {
        $suggestions = $this->tour->get_item_search_suggestions($this->input->post('term'),100);
        echo json_encode($suggestions);
    }*/
    function item_search() {
        $suggestions = $this->tour->get_item_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

    function get_info($item_id=-1)
    {
        echo json_encode($this->tour->get_info($item_id));
    }

    function save_package($item_kit_id=-1)
    {
        $this->check_action_permission('add_update');       
        $item_kit_data = array(
            'item_kit_number'=>$this->input->post('item_kit_number')=='' ? null:$this->input->post('item_kit_number'),
            'name'=>$this->input->post('name'),
            'category'=>$this->input->post('category'),
            'unit_price'=>$this->input->post('unit_price')=='' ? null:$this->input->post('unit_price'),
            'cost_price'=>$this->input->post('cost_price')=='' ? null:$this->input->post('cost_price'),
            'description'=>$this->input->post('description')
        );
        
        if($this->tour->save_package($item_kit_data,$item_kit_id))
        {
            //New item kit
            if($item_kit_id==-1)
            {
                echo json_encode(array('success'=>true,'message'=>lang('item_kits_successful_adding').' '.
                $item_kit_data['name'],'item_kit_id'=>$item_kit_data['item_kit_id']));
                $item_kit_id = $item_kit_data['item_kit_id'];
            }
            else //previous item
            {
                echo json_encode(array('success'=>true,'message'=>lang('item_kits_successful_updating').' '.
                $item_kit_data['name'],'item_kit_id'=>$item_kit_id));
            }
            
            if ($this->input->post('item_kit_item'))
            {
                $item_kit_items = array();
                foreach($this->input->post('item_kit_item') as $item_id => $quantity)
                {
                    $item_kit_items[] = array(
                        'tour_id' => $item_id,
                        'quantity' => $quantity
                        );
                }
            
                $this->tour->save_package_item($item_kit_items, $item_kit_id);
            }
            
            /*$item_kits_taxes_data = array();
            $tax_names = $this->input->post('tax_names');
            $tax_percents = $this->input->post('tax_percents');
            $tax_cumulatives = $this->input->post('tax_cumulatives');
            for($k=0;$k<count($tax_percents);$k++)
            {
                if (is_numeric($tax_percents[$k]))
                {
                    $item_kits_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0' );
                }
            }
            $this->Item_kit_taxes->save($item_kits_taxes_data, $item_kit_id);*/
        }
        else//failure
        {
            echo json_encode(array('success'=>false,'message'=>lang('item_kits_error_adding_updating').' '.
            $item_kit_data['name'],'item_kit_id'=>-1));
        }

    }

    function check_duplicate_package()
    {
        echo json_encode(array('duplicate'=>$this->tour->check_duplicate_package($this->input->post('term'))));

    }

    /*
    Search for package
    */
    function search_package()
    {
        $this->check_action_permission('search');
        $search=$this->input->post('search');
        $per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
        $search_data=$this->tour->search_package($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
        $config['base_url'] = site_url('tours/search_package');
        $config['total_rows'] = $this->tour->search_count_all_package($search);
        $config['per_page'] = $per_page ;
        $this->pagination->initialize($config);             
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table']=get_Item_kits_manage_table_data_rows($search_data,$this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    /*
    Gives search suggestions based on what is being searched for
    */
    function suggest_search_package()
    {
        $suggestions = $this->tour->get_search_package_suggestions($this->input->post('term'),100);
        echo json_encode($suggestions);
    }

    /*
    Delete package
    */
    function delete_package()
    {
        $this->check_action_permission('delete');       
        $item_kits_to_delete=$this->input->post('checkedID');

        if($this->tour->delete_list_package($item_kits_to_delete))
        {
            echo json_encode(array('success'=>true,'message'=>lang('item_kits_successful_deleted').' '.
            count($item_kits_to_delete).' '.lang('item_kits_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success'=>false,'message'=>lang('item_kits_cannot_be_deleted')));
        }
    }

}

?>