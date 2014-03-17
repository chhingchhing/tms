 <?php
require_once ("secure_area.php");

class Commissioners extends Secure_area {

    function __construct() {
        parent::__construct('commissioners');
        $this->load->model(array('commissioner'));
    }

    function index() {
        $this->commissioners();
    }

    function commissioners() {
        $data['allowed_modules'] = $this->check_module_accessable();
        $this->check_action_permission('search');

        $config['base_url'] = site_url('commissioners/commissioners/' . $this->uri->segment(3));
        $config['total_rows'] = $this->commissioner->count_all();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        $data['form_width'] = $this->get_form_width();

        $data['per_page'] = $config['per_page'];
        $data['manage_commissioner_data'] = $this->commissioner->get_all();
//        var_dump($data['manage_commissioner_data']);die();
        if ($this->uri->segment(4)) {
            $data['manage_table'] = $this->sorting($this->uri->segment(4));
        } else {
            $data['per_page'] = $config['per_page'];
            $data['manage_table'] = get_commissioners_table($this->commissioner->get_all($data['per_page']), $this);
        }
        $this->load->view('commissioners/manage', $data);
    }

    /* shorting for pagegination */

    function sorting($offset) {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        if ($search) {
            $config['total_rows'] = $this->commissioner->search_count_all($search);
            $table_data = $this->commissioner->search($search, $per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'commisioner_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        } else {
            $config['tpaginationotal_rows'] = $this->commissioner->count_all();
            $table_data = $this->commissioner->get_all($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'commisioner_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        }
        $config['base_url'] = site_url('commissioners/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_commissioners_table($table_data, $this);

        return $data['manage_table'];
    }

    function get_form_width() {
        return 550;
    }

    function check_duplicate() {
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        echo json_encode(array('duplicate' => $this->commissioner->check_duplicate($first_name, $last_name)));
    }

    /* Search commissioner */
//    function commissioner_search()
//    {
//        echo $this->input->post('term'); die();
//        $suggestions = $this->commissioner->get_search_suggestions($this->input->post('term'),100);
//        echo json_encode($suggestions);
//    }
    function save($commissioner_id = -1) {
        $this->check_action_permission('add_update');
    	$commissioner_data = array(
    		'first_name' => $this->input->post("first_name"),
    		'last_name' => $this->input->post("last_name"),
    		'tel' => $this->input->post("phone_number"),
    		'deleted' => 0
    		);
        
    	if ($this->commissioner->save($commissioner_data, $commissioner_id)) {
    		//New customer
                if($commissioner_id==-1)
                {
                        echo json_encode(array('success'=>true,'message'=>lang('customers_successful_adding').' '.
                        $commissioner_data['first_name'].' '.$commissioner_data['last_name'],'commissioner_id'=>$commissioner_data['commisioner_id']));                          
                }
                else //previous customer
                {
                        echo json_encode(array('success'=>true,'message'=>lang('customers_successful_updating').' '.
                        $commissioner_data['first_name'].' '.$commissioner_data['last_name'],'commissioner_id'=>$commissioner_id));
                }
    	}
        else//failure
        {
                echo json_encode(array('success'=>false,'message'=>lang('customers_error_adding_updating').' '.
                $commissioner_data['first_name'].' '.$commissioner_data['last_name'],'commissioner_id'=>-1));
        }
    }

    //    /* added for excel expert */  

    function excel_export($template = 0) {
        $data = $this->commissioner->get_commis()->result_object();
        $this->load->helper('report');
        $rows = array();
        $row = array(lang('commissioners_id'), lang('commissioners_name'), lang('commissioners_tel'));

        $n = 1;
        $rows[] = $row;
        foreach ($data as $r) {
            $row = array(
                $n ++,
                $r->first_name . ' ' . $r->last_name,
                $r->tel
            );
            $rows[] = $row;
        }

        $content = array_to_csv($rows);

        if ($template) {
            force_download('commissioners_export_mass_update.csv', $content);
        } else {
            force_download('commissioners_export.csv', $content);
        }
        exit;
    }

    //delete commissioner from tbl commissioners
    function delete() {
        $this->check_action_permission('delete');
        $ids = $this->input->post("ids");
        if($this->commissioner->delete_list($ids))
        {
            echo json_encode(array('success'=>true,'message'=>lang('commissioners_successful_deleted').' '.
            count($ids).' '.lang('commissioners_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success'=>false,'message'=>lang('commissioners_cannot_be_deleted')));
        }
    }

    // edit commissioner
    function viewJSON($item_id = -1) {
        $this->check_action_permission('add_update');
        $data['person_info'] = $this->commissioner->get_info($item_id);
        echo json_encode($data['person_info']);
    }

    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->commissioner->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'commisioner_id', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'desc');
        $config['base_url'] = site_url('commissioners/commissioners' . $this->uri->segment(3));
        $config['total_rows'] = $this->commissioner->search_count_all($search);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_commissioner_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    /* Search commissioner */

    function suggest() {
        $suggestions = $this->commissioner->get_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

    /*
    Loads the commissioner edit form
    */
    function view($commisioner_id=-1)
    {
//        echo $commisioner_id;die('commissioner id');
        $this->check_action_permission('add_update');
        $data['commissioner_info']=$this->commissioner->get_info($commisioner_id);
        echo json_encode($data['commissioner_info']);
    }
    
    /* Search commissioner */
    function commissioner_search()
    {
        $suggestions = $this->commissioner->search_suggestions($this->input->post('term'),100);
        echo json_encode($suggestions);
    }
}

