<?php
require_once ("secure_area.php");
class Currency extends Secure_area {

	function __construct() {
		parent::__construct("currency");
		$this->load->model(array("currency_model"));
	}

	function index() {
        $this->check_action_permission('search');
        
		$data['allowed_modules'] = $this->check_module_accessable();
        $config['base_url'] = site_url('currency/currency/' . $this->uri->segment(3));
        $config['total_rows'] = $this->currency_model->count_all();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;

        $config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        $data['form_width'] = $this->get_form_width();
        $data['total_rows'] = $this->currency_model->count_all();
        $data['per_page'] = $config['per_page'];
        $data['manage_currency_data'] = $this->currency_model->get_all();
        if ($this->uri->segment(4)) {
            $data['manage_table'] = $this->sorting($this->uri->segment(4));
        } else {
            $data['per_page'] = $config['per_page'];
            $data['manage_table'] = get_currency_table($this->currency_model->get_all($data['per_page']), $this);
        }
        $this->load->view('currency/manage', $data);
	}

	function sorting($offset) {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        if ($search) {
            $config['total_rows'] = $this->currency_model->search_count_all($search);
            $table_data = $this->currency_model->search($search, $per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'currency_type_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        } else {
            $config['tpaginationotal_rows'] = $this->currency_model->count_all();
            $table_data = $this->currency_model->get_all($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'currency_type_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        }
        $config['base_url'] = site_url('currency/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_currency_table($table_data, $this);
        return $data['manage_table'];
    }

    /*
    Loads the customer edit form
    */
    function view($currency_id = -1)
    {
        $this->check_action_permission('add_update');
        $data['controller_name'] = $this->uri->segment(1);
        $data['currency_info']=$this->currency_model->get_info($currency_id);
        $this->load->view("currency/_form", $data);
    }

    // Check for duplicate destination of ticket
    function check_duplicate() { 
        $currency_type_name = $this->input->post('currency_type_name');
        $currency_value = $this->input->post('currency_value');
        echo json_encode(array('duplicate' => $this->currency_model->check_duplicate($currency_type_name, $currency_value)));
    }

    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->currency_model->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'currency_type_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        $config['base_url'] = site_url('currency/currency/' . $this->uri->segment(3));
        $config['total_rows'] = $this->currency_model->search_count_all($search);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_currency_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    function suggest() {
        $suggestions = $this->currency_model->get_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

    function get_row() {

    }

    /**
    *Add new or update currency
    */
    function save($currency_id = -1) {
        $this->check_action_permission('add_update');
        $currency_data = array(
            'currency_type_name' => $this->input->post("currency_type_name"),
            'currency_value' => $this->input->post("currency_value"),
            'symbol' => $this->input->post("currency_symbol"),
            'mark' => $this->input->post("mark"),
            'deleted' => 0
            );
        
        if ($this->currency_model->save($currency_data, $currency_id)) {
            //New customer
                if($currency_id==-1)
                {
                        echo json_encode(array('success'=>true,'message'=>lang('currency_successful_adding').' '.
                        $currency_data['currency_type_name'].' '.$currency_data['currency_value'],'currency_id'=>$currency_data['currency_id']));                          
                }
                else //previous customer
                {
                        echo json_encode(array('success'=>true,'message'=>lang('currency_successful_updating').' '.
                        $currency_data['currency_type_name'].' '.$currency_data['currency_value'],'currency_id'=>$currency_id));
                }
        }
        else//failure
        {
                echo json_encode(array('success'=>false,'message'=>lang('currency_error_adding_updating').' '.
                $currency_data['currency_type_name'].' '.$currency_data['currency_value'],'currency_id'=>-1));
        }
    }

    /**
    *Export as Excel
    */
    function excel_export($template = 0) {
        $data = $this->currency_model->get_all()->result_object();
        $this->load->helper('report');
        $rows = array();
        $row = array(lang('currency_id'), lang('currency_type_name'), lang('currency_value'), lang('common_description'));

        $n = 1;
        $rows[] = $row;
        foreach ($data as $r) {
            $row = array(
                $n ++,
                $r->currency_type_name,
                $r->currency_value,
                $r->mark
            );
            $rows[] = $row;
        }

        $content = array_to_csv($rows);

        if ($template) {
            force_download('currency_export_mass_update.csv', $content);
        } else {
            force_download('currency_export.csv', $content);
        }
        exit;
    }

    //delete commissioner from tbl commissioners
    function delete() {
        $this->check_action_permission('delete');
        $ids = $this->input->post("checkedID");
        if($this->currency_model->delete_list($ids))
        {
            echo json_encode(array('success'=>true,'message'=>lang('currency_successful_deleted').' '.
            count($ids).' '.lang('currency_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success'=>false,'message'=>lang('currency_cannot_be_deleted')));
        }
    }

    function get_form_width() {
    }

}