<?php

require_once 'secure_area.php';

class Transportations extends Secure_area {

    function __construct() {
        parent::__construct('transportations');
        $this->load->model(array('transportation'));
    }

    //change text to check line endings
    //new line endings

    function index() {
        $this->transportations();
    }

    function transportations() { 
        $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        $office = substr($this->uri->segment(3), -1);
        $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed

        $this->check_action_permission('search');

        $config['base_url'] = site_url('transportations/transportations/' . $this->uri->segment(3));
        $config['total_rows'] = $this->transportation->count_all();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        $data['form_width'] = $this->get_form_width();

        $data['per_page'] = $config['per_page'];
        $data['manage_transportation_data'] = $this->transportation->get_all();
        if ($this->uri->segment(4)) {
            $data['manage_table'] = $this->sorting($this->uri->segment(4));
        } else {

            $data['per_page'] = $config['per_page'];
            $data['manage_table'] = get_transportations_table($this->transportation->get_all($data['per_page']), $this);
        }
        $this->load->view('transportations/manage', $data);
    }

    /* shorting for pagegination */

    function sorting($offset) {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        if ($search) {
            $config['total_rows'] = $this->transportation->search_count_all($search);
            $table_data = $this->transportation->search($search, $per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'transport_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        } else {
            $config['tpaginationotal_rows'] = $this->transportation->count_all();
            $table_data = $this->transportation->get_all($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'transport_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        }
        $config['base_url'] = site_url('transportations/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_transportations_table($table_data, $this);

        return $data['manage_table'];
    }

    function get_form_width() {
        return 550;
    }

//    /* added for excel expert */  
    
    function excel_export($template = 0) {
        $data = $this->transportation->get_transports()->result_object();
        $this->load->helper('report');
        $rows = array();
        $row = array(lang('transports_transport_id'), lang('transports_company_name'),lang('transports_taxi_name'), lang('transports_phone'), lang('transports_mark'), lang('transports_action '));
        
        $n = 1;
        $rows[] = $row;
        foreach ($data as $r) {
            $row = array(
                $n ++,
                $r->company_name,
                $r->taxi_fname,
                $r->taxi_lname,
                $r->phone,
                $r->mark,
            );
            $rows[] = $row;
        }

        $content = array_to_csv($rows);

        if ($template) {
            force_download('transports_export_mass_update.csv', $content);
        } else {
            force_download('transports_export.csv', $content);
        }
        exit;
    }
    
    //delete guide from tbl guide
    
    function delete() {

        $this->check_action_permission('delete');
        $transports_to_delete = $this->input->post('ids');
        if ($this->transportation->delete_list($transports_to_delete)) {
            echo "true";
        } else {
            echo "false";
        }
    }
    
    // edit teransports
    function viewJSON($item_id = -1) {
        $this->check_action_permission('add_update');
        $data['person_info'] = $this->transportation->get_info($item_id);
        echo json_encode($data['person_info']);
    }
    
    function save($transport_id = -1) {
        $transport_data = array(
            'company_name' => $this->input->post("company_name"),
            'taxi_fname' => $this->input->post("taxi_fname"),
            'taxi_lname' => $this->input->post("taxi_lname"),
            'phone' => $this->input->post("phone"),
            'mark' => $this->input->post("mark"),                   
            'deleted' => 0
        );
        if ($this->transportation->save($transport_data, $transport_id)) {
            //New transport
            if ($transport_id == -1) {
                echo json_encode(array('success' => true, 'message' => lang('customers_successful_adding') . ' ' .
                    $transport_data['company_name'] . ' ' . $transport_data['taxi_fname']. ' ' . $transport_data['taxi_lname'], 'transport_id' => $transport_data['transport_id']));
            } else { //previous customer
                echo json_encode(array('success' => true, 'message' => lang('customers_successful_updating') . ' ' .
                    $transport_data['company_name'] . ' ' . $transport_data['taxi_fname']. ' ' . $transport_data['taxi_lname'], 'transport_id' => $transport_id));
            }
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('customers_error_adding_updating') . ' ' .
                $transport_data['company_name'] . ' ' . $transport_data['taxi_fname']. ' ' . $transport_data['taxi_lname'], 'transport_id' => -1));
        }
    }
    
     function check_duplicate() {
        $company_name = $this->input->post('company_name');
        $taxi_fname = $this->input->post('taxi_fname');
        $taxi_lname = $this->input->post('taxi_lname');
        echo json_encode(array('duplicate' => $this->transportation->check_duplicate( $company_name, $taxi_fname,$taxi_lname)));
    }

    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->transportation->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'company_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc'); 
        $config['base_url'] = site_url('transportations/transportations' . $this->uri->segment(3)); 
        $config['total_rows'] = $this->transportation->search_count_all($search);
        $config['per_page'] = $per_page;                                                   
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_transportation_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    function suggest() {
        $suggestions = $this->transportation->get_transport_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }
 
}
