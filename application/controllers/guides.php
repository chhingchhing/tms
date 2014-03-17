<?php

require_once 'secure_area.php';

class Guides extends Secure_area {

    function __construct() {
        parent::__construct('guides');
        $this->load->model(array('guide'));
    }

    //change text to check line endings
    //new line endings

    function index() {
        $this->guides();
    }

    function guides() {
        $this->check_action_permission('search');
        $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        $office = substr($this->uri->segment(3), -1);
        $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed

        $config['base_url'] = site_url('guides/guides/' . $this->uri->segment(3));
        $config['total_rows'] = $this->guide->count_all();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;

        $config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        $data['form_width'] = $this->get_form_width();

        $data['per_page'] = $config['per_page'];
        $data['manage_guide_data'] = $this->guide->get_all();
        if ($this->uri->segment(4)) {
            $data['manage_table'] = $this->sorting($this->uri->segment(4));
        } else {

            $data['per_page'] = $config['per_page'];
            $data['manage_table'] = get_guides_table($this->guide->get_all($data['per_page']), $this);
        }
        $this->load->view('guides/manage', $data);
    }

    /* shorting for pagegination */

    function sorting($offset) {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        if ($search) {
            $config['total_rows'] = $this->guide->search_count_all($search);
            $table_data = $this->guide->search($search, $per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'guide_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        } else {
            $config['tpaginationotal_rows'] = $this->guide->count_all();
            $table_data = $this->guide->get_all($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'guide_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        }
        $config['base_url'] = site_url('guides/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_guides_table($table_data, $this);

        return $data['manage_table'];
    }

    function get_form_width() {
        return 550;
    }

    // Select guide
    function select_guide() {
        $data = array();
        $guide_id = $this->input->post("guide");

        if ($this->guide->exists($guide_id)) {
            $this->sale_lib->set_guide($guide_id);
        } else {
            $data['error'] = lang('sales_unable_to_add_guide');
        }
        $this->_reload($data);
    }

    /* added for excel expert */  
    
    function excel_export($template = 0) {
        $data = $this->guide->get_guides()->result_object();
        $this->load->helper('report');
        $rows = array();
        $row = array(lang('guides_guide_id'), lang('guides_guide_name'),lang('guides_gender'), lang('guides_tel'), lang('guides_email'), lang('guides_type '));

        $n = 1;
        $rows[] = $row;
        foreach ($data as $r) {
            $row = array(
                $n ++,
                $r->guide_fname.$r->guide_lname,
                $r->gender,
                $r->tel,
                $r->email,
                $r->guide_type
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
    
    //delete guide from tbl guide
    
    function delete() {

        $this->check_action_permission('delete');
        $guides_to_delete = $this->input->post('ids');
        if ($this->guide->delete_list($guides_to_delete)) {
            echo json_encode(array('success' => true, 'message' => lang('guides_successful_deleted')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('guides_cannot_be_deleted')));
        }
    }
    // edit guide
    function viewJSON($item_id = -1) {
        $this->check_action_permission('add_update');
        $data['person_info'] = $this->guide->get_info($item_id);
//        var_dump($data['person_info']);die();
        echo json_encode($data['person_info']);
    }
    
    function save($guide_id = -1){
        $guide_data = array(
            'guide_fname' => $this->input->post("first_name"),
            'guide_lname' => $this->input->post("last_name"),
            'gender' => $this->input->post("gender"),
            'email' => $this->input->post("email"),
            'guide_type' => $this->input->post("guide_type"),
            'tel' => $this->input->post("phone_number"),
            'deleted' => 0
        );
        if ($this->guide->save($guide_data, $guide_id)) {
            //New customer
            if ($guide_id == -1) {
                echo json_encode(array('success' => true, 'message' => lang('customers_successful_adding') . ' ' .
                    $guide_data['guide_fname'] . ' ' . $guide_data['guide_lname'], 'guide_id' => $guide_data['guide_id']));
            } else { //previous customer
                echo json_encode(array('success' => true, 'message' => lang('customers_successful_updating') . ' ' .
                    $guide_data['guide_fname'] . ' ' . $guide_data['guide_lname'], 'guide_id' => $guide_id));
            }
        } else {//failure
            echo json_encode(array('success' => false, 'message' => lang('customers_error_adding_updating') . ' ' .
                $guide_data['guide_fname'] . ' ' . $guide_data['guide_lname'], 'guide_id' => -1));
        }
    }
      
    /*search guides*/
    
        
    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->guide->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'guide_fname', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc'); 
      
        $config['base_url'] = site_url('guides/guides' . $this->uri->segment(3)); 
        $config['total_rows'] = $this->guide->search_count_all($search);
        $config['per_page'] = $per_page;                                                   
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_guide_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    /*
      Gives search suggestions based on what is being searched for
     */

    /* Search commissioner */

    function guide_search() {
        $suggestions = $this->guide->get_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }
    
    //suggest for guide
     function suggest() {
        $suggestions = $this->guide->get_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }
    
      function check_duplicate() {
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        echo json_encode(array('duplicate' => $this->guide->check_duplicate($first_name, $last_name)));
    }
}
