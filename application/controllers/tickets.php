<?php
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");

class Tickets extends Secure_area implements iData_controller {

    function __construct() {
        parent::__construct('tickets');
        $this->load->library('sale_ticket_lib');
        $this->load->library('sale_lib');

        $this->load->model(array('Sale','ticket','Sale_ticket','sales/tickets/ticket_kit',
            'sales/tickets/ticket_item','sales/tickets/item_kit_ticket','commissioner','currency_model'));

    }

    /**
    *change text to check line endings
    *new line endings
    */

    function index() {
        $this->tickets();
    }

    function tickets() {
        $data['allowed_modules'] = $this->check_module_accessable();

        $this->check_action_permission('search');
        $config['base_url'] = site_url('tickets/tickets/' . $this->uri->segment(3));
        $config['total_rows'] = $this->ticket->count_all();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;

        $config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        $data['form_width'] = $this->get_form_width();
        $data['total_rows'] = $this->ticket->count_all();
        $data['per_page'] = $config['per_page'];
        $data['manage_ticket_data'] = $this->ticket->get_all();
        $data['destination_id'] = $this->ticket->get_destinationID();
        $suppliers = array('' => lang('items_none'));
        foreach($this->Supplier->get_all()->result_array() as $row)
        {
            $suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
        }
        $data['suppliers']=$suppliers;
        $data['ticket_type_id'] = $this->ticket->get_ticket_type();
        if ($this->uri->segment(4)) {
            $data['manage_table'] = $this->sorting($this->uri->segment(4));
        } else {
            $data['per_page'] = $config['per_page'];
            $data['manage_table'] = get_tickets_table($this->ticket->get_all($data['per_page']), $this);
        }
        $this->load->view('tickets/manage', $data);
    }

    function sorting($offset) {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        if ($search) {
            $config['total_rows'] = $this->ticket->search_count_all($search);
            $table_data = $this->ticket->search($search, $per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'ticket_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        } else {
            $config['tpaginationotal_rows'] = $this->ticket->count_all();
            $table_data = $this->ticket->get_all($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'ticket_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        }
        $config['base_url'] = site_url('tickets/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_tickets_table($table_data, $this);
        return $data['manage_table'];
    }

    /**
    get_supplier_manage_table_pagination
    */
    function find_item_info() {
        $item_number = $this->input->post('scan_item_number');
        echo json_encode($this->ticket->find_item_info($item_number));
    }

    function item_number_exists() {
        if ($this->ticket->account_number_exists($this->input->post('item_number')))
            echo 'false';
        else
            echo 'true';
    }

    function check_duplicate_data() {     
        $ticket_name = $this->input->post("ticket_name");
        $tDestination = $this->input->post("destination_id");
        $tType = $this->input->post("ticket_typeID");
        echo json_encode(array('duplicate' => $this->ticket->check_duplicate_data($ticket_name, $tDestination, $tType)));
    }
        
    /**
    *search function  
    */
    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->ticket->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'ticket_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        $config['base_url'] = site_url('tickets/tickets/' . $this->uri->segment(3));
        $config['total_rows'] = $this->ticket->search_count_all($search);
        $config['per_page'] = $per_page;
        
        $config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_ticket_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    /**
    *  Gives search suggestions based on what is being searched for
     */

    function suggest() {
        $suggestions = $this->ticket->get_search_suggestions($this->input->get('term'), 100);

        echo json_encode($suggestions);
    }

    function item_search() {
        $suggestions = $this->ticket->get_item_search_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }

    /**
      Gives search suggestions based on what is being searched for
     */

    function suggest_category() {
        $suggestions = $this->ticket->get_category_suggestions($this->input->get('term'));
        echo json_encode($suggestions);
    }

    function get_row() {
        $item_id = $this->input->post('row_id');
        $data_row = get_ticket_data_row($this->ticket->get_info($item_id), $this);
        echo $data_row;
    }

    function get_info($item_id = -1) {
        echo json_encode($this->ticket->get_info($item_id));
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

        $data['item_info'] = $this->ticket->get_info($item_id);
        $data['item_tax_info'] = $this->Item_taxes->get_info($item_id);
        $suppliers = array('' => lang('items_none'));
        foreach ($this->ticket->get_all()->result_array() as $row) {
            $suppliers[$row['person_id']] = $row['company_name'] . ' (' . $row['first_name'] . ' ' . $row['last_name'] . ')';
        }

        $data['suppliers'] = $suppliers;
        $data['selected_supplier'] = $this->ticket->get_info($item_id)->ticket_id;
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
        $this->load->view("ticket/form", $data);
    }

    /**
     * Loads the ticket json data
     */

    function viewJSON($item_id = -1) {
        $this->check_action_permission('add_update');
        $suppliers = array('' => lang('items_none'));
        foreach($this->Supplier->get_all()->result_array() as $row)
        {
            $suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
        }
        $data['suppliers']=$suppliers;
        $data['ticket_type_id'] = $this->ticket->get_ticket_type();
        $data['destination_id'] = $this->ticket->get_destinationID();
        $data['controller_name'] = strtolower(get_class());
        $data['ticket_info'] = $this->ticket->get_info($item_id);
        $this->load->view("tickets/_form", $data);
    }

    /**
   *Ramel Inventory Tracking
    */
    function inventory($item_id = -1) {
        $this->check_action_permission('add_update');
        $data['item_info'] = $this->ticket->get_info($item_id);
        $this->load->view("items/inventory", $data);
    }

    function count_details($item_id = -1) {
        $this->check_action_permission('add_update');
        $data['item_info'] = $this->Item->get_info($item_id);
        $this->load->view("items/count_details", $data);
    }

    function generate_barcodes($item_ids) {
        $data['items'] = get_barcode_data($item_ids);
        $data['scale'] = 2;
        $this->load->view("barcode_sheet", $data);
    }

    function generate_barcode_labels($item_ids) {
        $data['items'] = get_barcode_data($item_ids);
        $data['scale'] = 1;
        $this->load->view("barcode_labels", $data);
    }

    function bulk_edit() {
        $this->check_action_permission('add_update');
        $this->load->helper('report');
        $data = array();
        $data['months'] = get_months();
        $data['days'] = get_days();
        $data['years'] = get_years();
        $data['end_years'] = array(date("Y") + 1 => date("Y") + 1) + $data['years'];
        $data['selected_start_year'] = 0;
        $data['selected_start_month'] = 0;
        $data['selected_start_day'] = 0;
        $data['selected_end_year'] = 0;
        $data['selected_end_month'] = 0;
        $data['selected_end_day'] = 0;

        $suppliers = array('' => lang('items_do_nothing'), '-1' => lang('items_none'));
        foreach ($this->Supplier->get_all()->result_array() as $row) {
            $suppliers[$row['person_id']] = $row['company_name'] . ' (' . $row['first_name'] . ' ' . $row['last_name'] . ')';
        }
        $data['suppliers'] = $suppliers;
        $data['allow_alt_desciption_choices'] = array(
            '' => lang('items_do_nothing'),
            1 => lang('items_change_all_to_allow_alt_desc'),
            0 => lang('items_change_all_to_not_allow_allow_desc'));


        $data['serialization_choices'] = array(
            '' => lang('items_do_nothing'),
            1 => lang('items_change_all_to_serialized'),
            0 => lang('items_change_all_to_unserialized'));
        $this->load->view("items/form_bulk", $data);
    }

    function save($item_id = -1) {
        $this->check_action_permission('add_update');
        if ($this->input->post("input_ticket_type") != '') {
            $item_ticket_type = array(
                'ticket_type_name' => $this->input->post("input_ticket_type"),
                'deleted' => 0
            );
            $this->ticket->add_ticket_type($item_ticket_type);
            $type_id = $item_ticket_type['ticket_type_id'];
        }

        if ($this->input->post('destinationID')) {
            $destination = array(
                'destination_name' => $this->input->post('destinationID')
            );
            $this->ticket->add_destination($destination);
            $destinationID = $destination['destination_id'];
        }

        $item_data = array(
            'ticket_name' => $this->input->post('ticket_name'),
            'supplierID' => $this->input->post('supplier'),
            'destinationID' => $this->input->post('destination_id') != 0 ? $this->input->post('destination_id') : $destinationID,
            'ticket_typeID' => $this->input->post("input_ticket_type") != '' ? $type_id : $this->input->post('ticket_typeID'),
            'actual_price' => $this->input->post('actual_price') == '' ? null : $this->input->post('actual_price'),
            'sale_price' => $this->input->post('sale_price') == '' ? null : $this->input->post('sale_price'),
            'descriptions' => $this->input->post('description'),
            'deleted' => '0'
        );
        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $employee_id = $this->Employee->get_logged_in_employee_info()->employee_id;
        $cur_item_info = $this->ticket->get_info($item_id);
        if ($this->ticket->save($item_data, $item_id)) {
            //New item  
            if ($item_id == -1) {
                echo json_encode(array('success' => true, 'message' => lang('items_successful_adding') . ' ' .
                    $item_data['name'], 'ticket_id' => $item_data['ticket_id']));
                $item_id = $item_data['ticket_id'];
            } else { //previous item
                echo json_encode(array('success' => true, 'message' => lang('items_successful_updating') . ' ' .
                    $item_data['name'], 'ticket_id' => $item_id));
            }

            $inv_data = array
                (
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_items' => $item_id,
                'trans_user' => $employee_id,
                'trans_comment' => lang('items_manually_editing_of_quantity'),
                'trans_inventory' => $cur_item_info ? $this->input->post('quantity') - $cur_item_info->quantity : $this->input->post('quantity'),
                'type_items' => strtolower(get_class())
            );
            $this->Inventory->insert($inv_data);
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('items_error_adding_updating') . ' ' .
                $item_data['name'], 'ticket_id' => -1));
        }
    }


    function saved($item_id = -1) {
        if ($item_id != -1) {
            $item_id = $this->input->post("ticket_id");
        }
        // $item_id = $this->input->post("ticket_id");
        $this->check_action_permission('add_update');
        if ($this->input->post("input_ticket_type") != '') {
            $item_ticket_type = array(
                'ticket_type_name' => $this->input->post("input_ticket_type"),
                'deleted' => 0
            );
            $this->ticket->add_ticket_type($item_ticket_type);
            $type_id = $item_ticket_type['ticket_type_id'];
        }

        if ($this->input->post('destinationID')) {
            $destination = array(
                'destination_name' => $this->input->post('destinationID')
            );
            $this->ticket->add_destination($destination);
            $destinationID = $destination['destination_id'];
        }

        $item_data = array(
            'ticket_name' => $this->input->post('ticket_name'),
            'supplierID' => $this->input->post('supplier'),
            'destinationID' => $this->input->post('destination_id') != 0 ? $this->input->post('destination_id') : $destinationID,
            'ticket_typeID' => $this->input->post("input_ticket_type") != '' ? $type_id : $this->input->post('ticket_typeID'),
            'actual_price' => $this->input->post('actual_price') == '' ? null : $this->input->post('actual_price'),
            'sale_price' => $this->input->post('sale_price') == '' ? null : $this->input->post('sale_price'),
            'descriptions' => $this->input->post('description'),
            'deleted' => '0'
        );

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $employee_id = $this->Employee->get_logged_in_employee_info()->employee_id;
        $cur_item_info = $this->ticket->get_info($item_id);

        $result = $this->ticket->save($item_data, $item_id);
        if ($result) {
            //New item  
            if ($item_id == -1) {
                echo json_encode(array('success' => true, 'message' => lang('items_successful_adding') . ' ' .
                    $item_data['code_ticket'], 'ticket_id' => $item_data['ticket_id']));
            } else { //previous item
                echo json_encode(array('success' => true, 'message' => lang('items_successful_updating') . ' ' .
                    $item_data['code_ticket'], 'ticket_id' => $item_id));
            }

            $inv_data = array
                (
                'trans_date' => date('Y-m-d H:i:s'),
                'trans_items' => $item_id,
                'trans_user' => $employee_id,
                'trans_comment' => lang('items_manually_editing_of_quantity'),
                'trans_inventory' => $cur_item_info ? $this->input->post('quantity') - $cur_item_info->quantity : $this->input->post('quantity'),
                'type_items' => strtolower(get_class())
            );
            $this->Inventory->insert($inv_data);
           
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('items_error_adding_updating') . ' ' .
                $item_data['code_ticket'], 'ticket_id' => -1));
        }
    }



    //Ramel Inventory Tracking
    function save_inventory($item_id = -1) {
        $this->check_action_permission('add_update');
        $employee_id = $this->Employee->get_logged_in_employee_info()->employee_id;
        $cur_item_info = $this->ticket->get_info($item_id);
        $inv_data = array
            (
            'trans_date' => date('Y-m-d H:i:s'),
            'trans_items' => $item_id,
            'trans_user' => $employee_id,
            'trans_comment' => $this->input->post('trans_comment'),
            'trans_inventory' => $this->input->post('newquantity')
        );
        $this->Inventory->insert($inv_data);

        //Update stock quantity
        $item_data = array(
            'quantity' => $cur_item_info->quantity + $this->input->post('newquantity')
        );
        if ($this->ticket->save($item_data, $item_id)) {
            echo json_encode(array('success' => true, 'message' => lang('items_successful_updating') . ' ' .
                $cur_item_info->name, 'item_id' => $item_id));
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('items_error_adding_updating') . ' ' .
                $cur_item_info->name, 'item_id' => -1));
        }
    }

//---------------------------------------------------------------------Ramel

    function bulk_update() {
        $this->check_action_permission('add_update');
        $items_to_update = $this->input->post('item_ids');
        $select_inventory = $this->get_select_inventory();

        //clears the total inventory selection
        $this->clear_select_inventory();

        $item_data = array();

        foreach ($_POST as $key => $value) {
            if ($key == 'submit') {
                continue;
            }

            //This field is nullable, so treat it differently
            if ($key == 'supplier_id') {
                if ($value != '') {
                    $item_data["$key"] = $value == '-1' ? null : $value;
                }
            } elseif ($value != '' and !(in_array($key, array('item_ids', 'tax_names', 'tax_percents', 'tax_cumulatives', 'start_month', 'start_year', 'start_day', 'end_month', 'end_year', 'end_day', 'select_inventory')))) {
                $item_data["$key"] = $value;
            }
        }

        //Item data could be empty if tax information is being updated
        if (empty($item_data) || $this->ticket->update_multiple($item_data, $items_to_update, $select_inventory)) {
            $items_taxes_data = array();
            $tax_names = $this->input->post('tax_names');
            $tax_percents = $this->input->post('tax_percents');
            $tax_cumulatives = $this->input->post('tax_cumulatives');

            for ($k = 0; $k < count($tax_percents); $k++) {
                if (is_numeric($tax_percents[$k])) {
                    $items_taxes_data[] = array('name' => $tax_names[$k], 'percent' => $tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0');
                }
            }

            if (!empty($items_taxes_data)) {
                $this->Item_taxes->save_multiple($items_taxes_data, $items_to_update, $select_inventory);
            }

            echo json_encode(array('success' => true, 'message' => lang('items_successful_bulk_edit')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('items_error_updating_multiple')));
        }
    }

    function delete() {
        $this->check_action_permission('delete');
        $items_to_delete = $this->input->post('checkedID');

        if ($this->ticket->delete_list($items_to_delete)) {                                                                                          
            echo json_encode(array('success' => true, 'message' => lang('tickets_successful_deleted')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('tickets_cannot_be_deleted')));
        }
    }

    function excel() {
        $data = file_get_contents("import_items.csv");
        $name = 'import_items.csv';
        force_download($name, $data);
    }

    /* added for excel expert */

    function excel_export($template = 0) {
        $data = $this->ticket->get_all()->result_object();
        $this->load->helper('report');
        $rows = array();
        $row = array(lang('items_item_number'), lang('items_name'), lang('items_category'), lang('items_supplier_id'), lang('items_cost_price'), lang('items_unit_price'), lang('items_tax_1_name'), lang('items_tax_1_percent'), lang('items_tax_2_name'), lang('items_tax_2_percent'), lang('items_tax_2_cummulative'), lang('items_quantity'), lang('items_reorder_level'), lang('items_location'), lang('items_description'), lang('items_allow_alt_desciption'), lang('items_is_serialized'), lang('items_item_id'));

        $rows[] = $row;
        foreach ($data as $r) {
            $taxdata = $this->Item_taxes->get_info($r->ticket_id);
            if (sizeof($taxdata) >= 2) {
                $r->taxn = $taxdata[0]['ticket_id'];
                $r->taxp = $taxdata[0]['code_ticket'];
                $r->taxn1 = $taxdata[1]['destination_name'];
                $r->taxp1 = $taxdata[1]['ticket_type_name'];
                $r->cumulative = $taxdata[1]['cumulative'] ? 'y' : '';
            } else if (sizeof($taxdata) == 1) {
                $r->taxn = $taxdata[0]['name'];
                $r->taxp = $taxdata[0]['percent'];
                $r->taxn1 = '';
                $r->taxp1 = '';
                $r->cumulative = '';
            } else {
                $r->taxn = '';
                $r->taxp = '';
                $r->taxn1 = '';
                $r->taxp1 = '';
                $r->cumulative = '';
            }

            $row = array(
                $r->ticket_id,
                $r->code_ticket,
                $r->destination_name,
                $r->destination_name,
                $r->ticket_type_name,
                $r->unit_price,
                $r->taxn,
                $r->taxp,
                $r->taxn1,
                $r->taxp1,
                $r->cumulative,
                $r->quantity,
                $r->reorder_level,
                $r->location,
                $r->description,
                $r->allow_alt_description,
                $r->is_serialized ? 'y' : '',
                $r->item_id
            );

            $rows[] = $row;
        }

        $content = array_to_csv($rows);
        if ($template) {
            force_download('items_export_mass_update.csv', $content);
        } else {
            force_download('items_export.csv', $content);
        }
        exit;
    }

    function excel_import() {
        $this->check_action_permission('add_update');
        $this->load->view("items/excel_import", null);
    }

    function do_excel_import() {
        set_time_limit(0);
        $this->check_action_permission('add_update');
        $this->db->trans_start();
        $msg = 'do_excel_import';
        $failCodes = array();
        if ($_FILES['file_path']['error'] != UPLOAD_ERR_OK) {
            $msg = lang('items_excel_import_failed');
            echo json_encode(array('success' => false, 'message' => $msg));
            return;
        } else {
            if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE) {
                //Skip first row
                fgetcsv($handle);

                while (($data = fgetcsv($handle)) !== FALSE) {
                    $item_data = array(
                        'name' => $data[1],
                        'description' => $data[14],
                        'location' => $data[13],
                        'category' => $data[2],
                        'cost_price' => $data[4] != null ? $data[4] : 0,
                        'unit_price' => $data[5] != null ? $data[5] : 0,
                        'quantity' => $data[11] != null ? $data[11] : 0,
                        'reorder_level' => $data[12] != null ? $data[12] : 0,
                        'supplier_id' => $this->Supplier->exists($data[3]) ? $data[3] : $this->Supplier->find_supplier_id($data[3]),
                        'allow_alt_description' => $data[15] != '' and $data[15] != '0' and strtolower($data[15]) != 'n' ? '1' : '0',
                        'is_serialized' => $data[16] != '' and $data[16] != '0' and strtolower($data[16]) != 'n' ? '1' : '0',
                    );
                    $item_number = $data[0];

                    if ($item_number != "") {
                        $item_data['item_number'] = $item_number;
                    }

                    if ($this->Item->save($item_data)) {
                        $items_taxes_data = null;
                        //tax 1
                        if (is_numeric($data[7]) && $data[6] != '') {
                            $items_taxes_data[] = array('name' => $data[6], 'percent' => $data[7], 'cumulative' => '0');
                        }

                        //tax 2
                        if (is_numeric($data[9]) && $data[8] != '') {
                            $items_taxes_data[] = array('name' => $data[8], 'percent' => $data[9], 'cumulative' => $data[10] != '' and $data[10] != '0' and $data[10] != 'n' ? '1' : '0',);
                        }

                        // save tax values
                        if (count($items_taxes_data) > 0) {
                            $this->Item_taxes->save($items_taxes_data, $item_data['item_id']);
                        }

                        $employee_id = $this->Employee->get_logged_in_employee_info()->employee_id;
                        $emp_info = $this->Employee->get_info($employee_id);
                        $comment = 'Qty CSV Imported';
                        $excel_data = array
                            (
                            'trans_items' => $item_data['item_id'],
                            'trans_user' => $employee_id,
                            'trans_comment' => $comment,
                            'trans_inventory' => $data[11] != null ? $data[11] : 0,
                        );
                        $this->db->insert('inventory', $excel_data);
                        //------------------------------------------------Ramel
                    } else {//insert or update item failure
                        echo json_encode(array('success' => false, 'message' => lang('items_duplicate_item_ids')));
                        return;
                    }
                }
            } else {
                echo json_encode(array('success' => false, 'message' => lang('common_upload_file_not_supported_format')));
                return;
            }
        }

        $this->db->trans_complete();
        echo json_encode(array('success' => true, 'message' => lang('items_import_successful')));
    }

    function excel_import_update() {
        $this->check_action_permission('add_update');
        $this->load->view("items/excel_import_update", null);
    }

    function do_excel_import_update() {
        set_time_limit(0);
        $this->check_action_permission('add_update');
        $this->db->trans_start();
        $msg = 'do_excel_import';
        $failCodes = array();
        if ($_FILES['file_path']['error'] != UPLOAD_ERR_OK) {
            $msg = lang('items_excel_import_failed');
            echo json_encode(array('success' => false, 'message' => $msg));
            return;
        } else {
            if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE) {
                //Skip first row
                fgetcsv($handle);

                while (($data = fgetcsv($handle)) !== FALSE) {
                    $item_data = array(
                        'name' => $data[1],
                        'description' => $data[14],
                        'location' => $data[13],
                        'category' => $data[2],
                        'cost_price' => $data[4],
                        'unit_price' => $data[5],
                        'quantity' => $data[11],
                        'reorder_level' => $data[12],
                        'supplier_id' => $this->Supplier->exists($data[3]) ? $data[3] : $this->Supplier->find_supplier_id($data[3]),
                        'allow_alt_description' => $data[15] != '' and $data[15] != '0' and strtolower($data[15]) != 'n' ? '1' : '0',
                        'is_serialized' => $data[16] != '' and $data[16] != '0' and strtolower($data[16]) != 'n' ? '1' : '0',
                    );
                    $item_number = $data[0];

                    if ($item_number != "") {
                        $item_data['item_number'] = $item_number;
                    }
                    if ($this->Item->exists($data[17])) {
                        if ($this->Item->save($item_data, $data[17])) {
                            $items_taxes_data = null;
                            //tax 1
                            if (is_numeric($data[7]) && $data[6] != '') {
                                $items_taxes_data[] = array('name' => $data[6], 'percent' => $data[7], 'cumulative' => '0');
                            }

                            //tax 2
                            if (is_numeric($data[9]) && $data[8] != '') {
                                $items_taxes_data[] = array('name' => $data[8], 'percent' => $data[9], 'cumulative' => $data[10] != '' and $data[10] != '0' and $data[10] != 'n' ? '1' : '0',);
                            }

                            // save tax values
                            if (count($items_taxes_data) > 0) {
                                $this->Item_taxes->save($items_taxes_data, $data[17]);
                            }
                        }
                    } else {//insert or update item failure
                        echo json_encode(array('success' => false, 'message' => lang('items_duplicate_item_ids')));
                        return;
                    }
                }
            } else {
                echo json_encode(array('success' => false, 'message' => lang('common_upload_file_not_supported_format')));
                return;
            }
        }

        $this->db->trans_complete();
        echo json_encode(array('success' => true, 'message' => lang('items_import_successful')));
    }

    function cleanup() {
        $this->Item->cleanup();
        echo json_encode(array('success' => true, 'message' => lang('items_cleanup_sucessful')));
    }

    /*
      get the width for the add/edit form
     */

    function get_form_width() {
        return 550;
    }

    function select_inventory() {
        $this->session->set_userdata('select_inventory', 1);
    }

    function get_select_inventory() {
        return $this->session->userdata('select_inventory') ? $this->session->userdata('select_inventory') : 0;
    }

    function clear_select_inventory() {
        $this->session->unset_userdata('select_inventory');
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
        
        $data['allowed_modules'] = $this->check_module_accessable();

        $data['destination_id'] = $this->ticket->get_destinationID();
        $suppliers = array('' => lang('items_none'));
        foreach($this->Supplier->get_all()->result_array() as $row)
        {
            $suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
        }
        $data['suppliers']=$suppliers;

        $data['ticket_type_id'] = $this->ticket->get_ticket_type();
        $data['controller_name']=strtolower(get_class());
        $data['cart']=$this->sale_lib->get_cart();
        $data['seat_no']=$this->sale_lib->get_seat_no();
        $data['item_number']=$this->sale_lib->get_item_number();
        $data['item_vol']=$this->sale_lib->get_item_vol();
        $data['dates_departure']=$this->sale_lib->get_date_departures();
        $data['times_departure']=$this->sale_lib->get_times_departure();
        $data['hotel_names']=$this->sale_lib->get_hotels();
        $data['room_numbers']=$this->sale_lib->get_room_numbers();
        $data['modes']= "Sale";
        $data['mode']=$this->sale_lib->get_mode();
        $data['items_in_cart'] = $this->sale_lib->get_items_in_cart();
        $data['subtotal']=$this->sale_lib->get_subtotal();
        $data['total']=$this->sale_lib->get_total();
        $data['items_module_allowed'] = $this->Employee->has_module_permission('tickets', $person_info->employee_id);
        $data['comment'] = $this->sale_lib->get_comment();
        $data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
        $data['email_receipt'] = $this->sale_lib->get_email_receipt();
        $data['payments_total']=$this->sale_lib->get_payments_totals();
        $data['amount_due']=$this->sale_lib->get_amount_due();
        $data['payments']=$this->sale_lib->get_payments();
        $data['commissioner_price'] = $this->sale_lib->get_commissioner_price();
        $data['deposit_price'] = $this->sale_lib->get_deposit_price();

        $data['change_sale_date_enable'] = $this->sale_lib->get_change_sale_date_enable();
        $data['change_sale_date'] = $this->sale_lib->get_change_sale_date();

        // End of payment type
       // $data['payment_options'] = "Cash";
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
        if($customer_id!=-1)
        {
            $info = $this->Customer->get_info($customer_id);
            $data['customer'] = $info->first_name.' '.$info->last_name.($info->company_name==''  ? '' :' ('.$info->company_name.')');

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
        
        if ($is_ajax)
        {
            $this->load->view("sales/register",$data);
        }
        else
        {
            $this->load->view("sales/register_initial",$data);
        }
    }

   
    function _payments_cover_total() {
        $total_payments = 0;


        foreach($this->sale_lib->get_payments() as $payment)
        {
            $total_payments += $payment['payment_amount'];
        }

        /* Changed the conditional to account for floating point rounding */

        if ( ( $this->sale_lib->get_mode() == 'sale' ) && ( ( to_currency_no_money( $this->sale_lib->get_total() ) - $total_payments ) > 1e-6 ) )
        {
            return false;
        }

        return true;
    }

    function item_search_suggestion() {
        $suggestions = $this->ticket_item->get_item_search_suggestions_for_sale($this->input->post('term'), 100);
        $suggestions = array_merge($suggestions, $this->ticket_kit->get_item_kit_search_suggestions($this->input->post('term'), 100));
        echo json_encode($suggestions);
    }

    function add()
    {
        $data=array();
        $mode = $this->sale_lib->get_mode();
        $item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");
        $quantity = $mode=="sale" ? 1:-1;
       
        if($this->sale_ticket_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) && $mode=='return')
        {
            $this->sale_ticket_lib->return_entire_sale($item_id_or_number_or_item_kit_or_receipt);
        }
        elseif($this->sale_ticket_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
        {
            //As surely a Kit item , do out of stock check
            $item_kit_id = $this->sale_ticket_lib->get_valid_item_kit_id($item_id_or_number_or_item_kit_or_receipt);
            $this->sale_ticket_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $quantity);
        } else {
           
            if(!$this->sale_ticket_lib->add_item($item_id_or_number_or_item_kit_or_receipt,$quantity))
            {
                $data['error']=lang('sales_unable_to_add_item');
            }
        }

        $this->_reload($data);
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
        
        if( !$this->sale_lib->add_payment( $payment_type, $payment_amount, $payment_total ) )
        {
            $data['error']=lang('sales_unable_to_add_payment');
        }

        $this->_reload($data);
    }

    //Alain Multiple Payments
    function delete_payment($payment_id)
    {
        $this->sale_lib->delete_payment($payment_id);
        $this->_reload();
    }

    function complete()
    {
        $person_info = $this->Employee->get_logged_in_employee_info();
        $data['allowed_modules'] = $this->check_module_accessable();

        $office_name = $this->session->userdata("office_number");
        $data['is_sale'] = TRUE;
        $data['cart']=$this->sale_lib->get_cart();
        $data['seat_no'] = $this->sale_lib->get_seat_no();
        $data['item_number'] = $this->sale_lib->get_item_number();
        $data['item_vol'] = $this->sale_lib->get_item_vol();
        $data['dates_departure']=$this->sale_lib->get_date_departures();
        $data['times_departure']=$this->sale_lib->get_times_departure();
        $data['hotel_names']=$this->sale_lib->get_hotels();
        $data['room_numbers']=$this->sale_lib->get_room_numbers();
        $data['subtotal']=$this->sale_lib->get_subtotal();
        $data['total']=$this->sale_lib->get_total();
        // $data['total_in_riels'] = $this->sale_lib->get_total_in_riels($data['total'], $this->config->item('default_currency'));
        $data['total_in_riels'] = $this->sale_lib->get_total_in_riels($data['total'], $this->Office->get_office_default_currency($office_name));
        $data['receipt_title'] = lang('sales_receipt');
        $data['deposit_price'] = $this->sale_lib->get_deposit_price();
        $customer_id=$this->sale_lib->get_customer();
        $commissioner_id=$this->sale_lib->get_commissioner();
        $commissioner_price = $this->sale_lib->get_commissioner_price();
        $employee_id = $person_info->employee_id;
        $data['controller_name'] = strtolower(get_class());
        $data['comment'] = $this->sale_lib->get_comment();
        $data['payments']=$this->sale_lib->get_payments();
        $data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
        $emp_info = $this->Employee->get_info($employee_id);
        $data['amount_change']=$this->sale_lib->get_amount_due_round() * -1;
        $data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
        $data['ref_no'] = $this->session->flashdata('ref_no') ? $this->session->flashdata('ref_no') : '';

        $data['change_sale_date'] =$this->sale_lib->get_change_sale_date_enable() ?  $this->sale_lib->get_change_sale_date() : false;
        $old_date = $this->sale_lib->get_change_sale_id()  ? $this->Sale->get_info($this->sale_lib->get_change_sale_id())->row_array() : false;
  
        $old_date =  $old_date ? date(get_date_format().' '.get_time_format(), strtotime($old_date['sale_time'])) : date(get_date_format().' '.get_time_format());
      
        $data['transaction_time']= $this->sale_lib->get_change_sale_date_enable() ?  date(get_date_format().' '.get_time_format(), strtotime($this->sale_lib->get_change_sale_date())) : $old_date;
        
        if($customer_id!=-1)
        {
            $cust_info=$this->Customer->get_info($customer_id);
            $data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' ('.$cust_info->company_name.')');
        }
        if($commissioner_id!=-1)
        {
            $comm_info=$this->commissioner->get_info($commissioner_id);
            $data['commissioner']=$comm_info->first_name.' '.$comm_info->last_name.($comm_info->tel==''  ? '' :' ('.$comm_info->tel.')');
        }

        $suspended_change_sale_id = $this->sale_lib->get_suspended_sale_id() ? $this->sale_lib->get_suspended_sale_id() : $this->sale_lib->get_change_sale_id() ;

        //SAVE sale to database

        $data['sale_id']= strtoupper($office_name).' '.$this->Sale_ticket->save($office_name,strtolower(get_class()),$data['cart'], $data['seat_no'],$data['item_number'],$data['item_vol'],$data['times_departure'],$data['dates_departure'],$data['hotel_names'],$data['room_numbers'], $data['deposit_price'], $customer_id,$employee_id, $commissioner_id,$commissioner_price,$data['comment'],$data['show_comment_on_receipt'],$data['payments'], $suspended_change_sale_id, 0,$data['ref_no'],$data['change_sale_date']);
        if ($data['sale_id'] == strtoupper($office_name).' -1')
        {
            $data['error_message'] = '';
            // Sale_helper, location helpers/sale_helper.php
            if (is_sale_integrated_cc_processing())
            {
                $data['error_message'].=lang('sales_credit_card_transaction_completed_successfully').'. ';
            }
            $data['error_message'] .= lang('sales_transaction_failed');
        }
        /*else
        {
            if ($this->sale_lib->get_email_receipt() && !empty($cust_info->email))
            {

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
        $this->load->view("sales/receipt",$data);
        $this->sale_lib->clear_all();
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

    function set_commissioner_price()
    {
        $commissioner_price = $this->input->post("commissioner_price");
        $this->sale_lib->set_commissioner_price($commissioner_price);
        $this->_reload();
    }

    function set_deposit_price()
    {
        $deposit_price = $this->input->post("deposit_price");
        $this->sale_lib->set_deposit_price($deposit_price);
        $this->_reload();
    }

    function start_cc_processing()
    {
        $service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/hcws/hcservice.asmx?WSDL': 'https://hc.mercurypay.com/hcws/hcservice.asmx?WSDL';
        $cc_amount = to_currency_no_money($this->sale_lib->get_payment_amount(lang('sales_credit')));
        $tax_amount = to_currency_no_money(($this->sale_lib->get_total() - $this->sale_lib->get_subtotal()) * ($cc_amount / $this->sale_lib->get_total()));
        $customer_id = $this->sale_lib->get_customer();

        $customer_name = '';
        if ($customer_id != -1) {
            $customer_info = $this->Customer->get_info($customer_id);
            $customer_name = $customer_info->first_name . ' ' . $customer_info->last_name;
        }
        
        if(!$this->sale_lib->get_use_saved_cc_info())
        {
            $invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->Employee->get_logged_in_employee_info()->employee_id), 0, 16);


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
                    'ProcessCompleteUrl' => site_url('tickets/finish_cc_processing'),
                    'ReturnUrl' => site_url('tickets/cancel_cc_processing'),
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

                $person_info = array('person_id' => $this->sale_lib->get_customer());
                $customer_info = array('cc_token' => $token);
                $this->Customer->save($person_info,$customer_info,$this->sale_lib->get_customer());
                $this->session->set_flashdata('ref_no', $ref_no);
                redirect(site_url('tickets/complete'));
            } else {
                //If we have failed, remove cc token and cc preview
                $person_info = array('person_id' => $this->sale_lib->get_customer());
                $customer_info = array('cc_token' => NULL, 'cc_preview' => NULL);
                $this->Customer->save($person_info,$customer_info,$this->sale_lib->get_customer());

                //Clear cc token for using saved cc info
                $this->sale_lib->clear_use_saved_cc_info();
                $this->_reload(array('error' => lang('sales_charging_card_failed_please_try_again')), false);
            }
        }
    }

// Search customer for sale process
    /* Customer in sales */
    function customer_search()
    {
        $suggestions = $this->Customer->get_customer_search_suggestions($this->input->post('term'),100);
        echo json_encode($suggestions);
    }

    function suspend()
    {
        $office_name = $this->session->userdata("office_number");
        $data['seat_no'] = $this->sale_lib->get_seat_no();
        $data['item_number'] = $this->sale_lib->get_item_number();
        $data['item_vol'] = $this->sale_lib->get_item_vol();
        $data['dates_departure']=$this->sale_lib->get_date_departures();
        $data['times_departure']=$this->sale_lib->get_times_departure();
        $data['deposit_price'] = $this->sale_lib->get_deposit_price();
        $data['cart'] = $this->sale_lib->get_cart();
        $data['hotel_names']=$this->sale_lib->get_hotels();
        $data['room_numbers']=$this->sale_lib->get_room_numbers();
        $data['subtotal'] = $this->sale_lib->get_subtotal();
        $data['total'] = $this->sale_lib->get_total();
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
        
        $sale_id = $this->sale_lib->get_suspended_sale_id();
        //SAVE sale to database
        $data['sale_id']= strtoupper($office_name).' '.$this->Sale_ticket->save($office_name,strtolower(get_class()),$data['cart'],$data['seat_no'],$data['item_number'],$data['item_vol'],
            $data['times_departure'],$data['dates_departure'],$data['hotel_names'],$data['room_numbers'],$data['deposit_price'], $customer_id,$employee_id,$commissioner_id,$commissioner_price,$comment,$show_comment_on_receipt,$data['payments'], $sale_id, 1);
        if ($data['sale_id'] == strtoupper($office_name).' -1')
        {
            $data['error_message'] = lang('sales_transaction_failed');
        }
        $this->sale_lib->clear_all();
        $this->_reload(array('success' => lang('sales_successfully_suspended_sale')));
        // $this->_reload($data);
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
        $data['suspended_sales'] = $this->Sale->get_all_suspended(strtolower(get_class()))->result_array();
        echo json_encode($data['suspended_sales']);
    }

// Unsuspend on sale id and show to sale
    function unsuspend($office, $sale_id)
    {
        // $sale_id = $this->input->post('suspended_sale_id');
        $this->sale_lib->clear_all();
        $this->sale_ticket_lib->copy_entire_sale($sale_id, strtolower(get_class()));
        $this->sale_lib->set_suspended_sale_id($sale_id);
        $this->_reload(array(), false);
    }

    function delete_suspended_sale()
    {
        $suspended_sale_id = $this->input->post('suspended_sale_id');
        if ($suspended_sale_id)
        {
            $this->sale_lib->delete_suspended_sale_id();
            $this->sale_ticket->delete($suspended_sale_id);
        }
        $this->_reload(array('success' => lang('sales_successfully_deleted')), false);
    }

    // Receipt of sale
    function receipt($office, $sale_id)
    {
        $data['allowed_modules'] = $this->check_module_accessable();
 
        $office_name = $this->session->userdata("office_number");
        $data['is_sale'] = FALSE;
        $sale_info = $this->Sale->get_info($sale_id)->row_array();
        $this->sale_lib->clear_all();
        $this->sale_ticket_lib->copy_entire_sale($sale_id);
        $data['cart']=$this->sale_lib->get_cart();
        $data['seat_no'] = $this->sale_lib->get_seat_no();
        $data['item_number'] = $this->sale_lib->get_item_number();
        $data['item_vol'] = $this->sale_lib->get_item_vol();
        $data['dates_departure']=$this->sale_lib->get_date_departures();
        $data['times_departure']=$this->sale_lib->get_times_departure();
        $data['hotel_names']=$this->sale_lib->get_hotels();
        $data['room_numbers']=$this->sale_lib->get_room_numbers();
        $data['payments']=$this->sale_lib->get_payments();
        $data['show_payment_times'] = TRUE;
        $data['controller_name'] = strtolower(get_class());
        $data['subtotal']=$this->sale_lib->get_subtotal();
        // $data['taxes']=$this->sale_ticket_lib->get_taxes($sale_id);
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
        $data['sale_id']= strtoupper($office_name).' '.$sale_id;
        $this->load->view("sales/receipt",$data);
        $this->sale_lib->clear_all();

    }

    // Change sale on receipt
    function change_sale($office, $sale_id)
    {
        $this->check_action_permission('edit_sale');
        $this->sale_lib->clear_all();
        $this->sale_ticket_lib->copy_entire_sale($sale_id, strtolower(get_class()));
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

    function set_comment_on_receipt() 
    {
      $this->sale_lib->set_comment_on_receipt($this->input->post('show_comment_on_receipt'));
    }

    function set_comment() 
    {
      $this->sale_lib->set_comment($this->input->post('comment'));
    }

    function edit_item($office, $line)
    {
        $data= array();

        $this->form_validation->set_rules('price', 'lang:items_price', 'required');
        $this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required');

        $description = $this->input->post("description");
        $serialnumber = $this->input->post("serialnumber");
        $price = $this->input->post("price");
        $quantity = $this->input->post("quantity");
        $discount = $this->input->post("discount");
        $seat_no = $this->input->post("seat_no");
        $index = $this->input->post("line")-1;
        $item_number = $this->input->post("number");
        $item_vol = $this->input->post("vol");
        $date_departure = $this->input->post("dates");
        $time_departure = $this->input->post("times");
        $hotel_name = $this->input->post("company_name");
        $room_number = $this->input->post("room_number");
        
        if ($this->form_validation->run() != FALSE)
        {
            $this->sale_lib->edit_item($line,$description,$serialnumber,$quantity,$discount,$price);
            
        }
        else
        {
            $data['error']=lang('sales_error_editing_item');
        }
        // Set seat no as session of array
        $array_seat_no = $this->sale_lib->get_seat_no();
        if ($array_seat_no) {
            if (count($array_seat_no) < $this->sale_lib->get_items_in_cart()) {
                $key_index = array_search($item_vol, $this->sale_lib->get_item_vol());   // $key = 1;
                if($key_index == false) {
                    $array_seat_no[$index] = $seat_no;
                } else {
                    $key = array_search($seat_no, $array_seat_no);   // $key = 1;
                    if ($key == false) {    // Add new element into last index of array
                        $array_seat_no[$index] = $seat_no;
                    // } else {
                    //     if ($key != $index) {
                    //         $data['warning_seat_no'] = lang('sales_add_seat_no_error');
                    //     }
                    }
                }
            } else {
                $key_index = array_search($item_vol, $this->sale_lib->get_item_vol());   // $key = 1;
                if($key_index == false) {
                    $array_seat_no[$index] = $seat_no;                        
                } else {
                    $key = array_search($seat_no, $array_seat_no);   // $key = 1;
                    if ($key == false) {    // Add new element into last index of array
                        $array_seat_no[$index] = $seat_no;
                    // } else {
                    //     $data['warning_seat_no'] = lang('sales_add_seat_no_error');
                    }
                }
            } 
            $this->sale_lib->set_seat_no($array_seat_no);             
        } else {
            $array_seat_no[$index] = $seat_no;
            $this->sale_lib->set_seat_no($array_seat_no);
        }

        // Set item number (code ticket) as session array during sale
        if ($item_number != "") {
            $array_item_number = $this->sale_lib->get_item_number();
            // var_dump($array_item_number);
            /*foreach($array_item_number as $value){
                echo $value;
            }
            for($i=1; $i<count($array_item_number); $i++){
                echo "hi";
                echo $array_item_number[$i];
            }*/

            $key_item = array_search($item_number, $array_item_number);   // $key = 1;
            if ($key_item == false) {    // Not match value in array
                if ($this->Ticket->exists_item_number($item_number)) {
                    $data['warning'] = lang('sales_add_item_number_error');
                } else {
                    $array_item_number[$index] = $item_number;
                }
                $this->sale_lib->set_item_number($array_item_number); 
            } else {
                if ($key_item != $index) {
                    $data['warning'] = lang('sales_add_item_number_error_session');
                }
            }
        } 

        // Set vol/time bus for leave (Bus Number) as in session array
        $array_item_vol = $this->sale_lib->get_item_vol();
        $array_item_vol[$index] = $item_vol;
        $this->sale_lib->set_item_vol($array_item_vol);

        // Set date departure 
        $array_date_departure = $this->sale_lib->get_date_departures();
        $new_array_date_departure = $this->insertArrayIndex($array_date_departure, $date_departure, $index);
        $this->sale_lib->set_date_departures($new_array_date_departure);

        // Set time departure 
        $array_time_departure = $this->sale_lib->get_times_departure();
        $new_array_time_departure = $this->insertArrayIndex($array_time_departure, $time_departure, $index);
        $this->sale_lib->set_times_departure($new_array_time_departure);

        // Set room
        $array_hotels = $this->sale_lib->get_hotels();
        $new_arr_hotels = $this->insertArrayIndex($array_hotels, $hotel_name, $index);
        $this->sale_lib->set_hotels($new_arr_hotels);

        // Set hotel
        $array_room_numbers = $this->sale_lib->get_room_numbers();
        $new_arr_room_numbers = $this->insertArrayIndex($array_room_numbers, $room_number, $index);
        $this->sale_lib->set_room_numbers($new_arr_room_numbers);
        
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

    function delete_item($office, $item_number)
    {
        $this->sale_ticket_lib->delete_item($item_number);
        $this->_reload();
    }

    // Set time departure
    function set_time_departure()
    {
        $this->sale_lib->set_time_departure($this->input->post("times"));
        $this->_reload();
    }

    // Edit sale after report details
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
            'commisioner_id' => $this->input->post('commissioner_id')==''?NULL:$this->input->post('commissioner_id'),
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

    // Delete entire sale after report detail
    function delete_entire_sale($office, $sale_id)
    {
        $data = array();
        $data['allowed_modules'] = $this->check_module_accessable();

        if ($this->Sale_ticket->delete($sale_id, false, $office))
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
        
        if ($this->Sale_ticket->undelete($office, $sale_id))
        {
            $data['success'] = true;
        }
        else
        {
            $data['success'] = false;
        }
        
        $this->load->view('sales/undelete', $data);
        
    }

    // Check for duplicate destination of ticket
    function check_duplicate_destination() { 
        echo json_encode(array('duplicate' => $this->ticket->check_duplicate_destination($this->input->post("term"))));
    }
    
}
?>