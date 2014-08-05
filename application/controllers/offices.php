<?php

require_once ("secure_area.php");

class Offices extends Secure_area {

    function __construct() {
        parent::__construct('offices');
        $this->load->model(array('Office', 'currency_model'));
    }

    function index() {
        $this->check_action_permission('search');
        
		$data['allowed_modules'] = $this->check_module_accessable();
        $config['base_url'] = site_url('offices/offices/' . $this->uri->segment(3));
        $config['total_rows'] = $this->Office->count_all();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        // $data['form_width'] = $this->get_form_width();
        $data['total_rows'] = $this->Office->count_all();
        $data['per_page'] = $config['per_page'];
        $data['manage_office_data'] = $this->Office->get_all();
        if ($this->uri->segment(4)) {
            $data['manage_table'] = $this->sorting($this->uri->segment(4));
        } else {
            $data['per_page'] = $config['per_page'];
            $data['manage_table'] = get_offices_table($this->Office->get_all($data['per_page']), $this);
        }

        $this->load->view('offices/manage', $data);
    }

    function sorting($offset) {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        if ($search) {
            $config['total_rows'] = $this->Office->search_count_all($search);
            $table_data = $this->Office->search($search, $per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'office_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        } else {
            $config['tpaginationotal_rows'] = $this->Office->count_all();
            $table_data = $this->Office->get_all($per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'office_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        }
        $config['base_url'] = site_url('offices/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_offices_table($table_data, $this);
        return $data['manage_table'];
    }

    /*
    Loads the offices edit form
    */
    function view($office_id = -1)
    {
        $this->check_action_permission('add_update');
        $data['allowed_modules'] = $this->check_module_accessable();

        $data['controller_name'] = strtolower(get_class());
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
		
		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			$data['payment_options'][$additional_payment_type] = $additional_payment_type;
		}

		$this->load->model("currency_model");
		$additional_currency = array('' => lang('items_none'));
        foreach ($this->currency_model->get_all()->result_array() as $row) {
            $additional_currency[$row['currency_type_name']] = $row['currency_type_name'] . ' (' . $row['currency_value'] . ')';
        }
        $data['currency_options'] = $additional_currency;

        $data['office_info']=$this->Office->get_info($office_id);

        $this->load->view('offices/manage', $data);
    }

    /*
    * Save config address of each office
    */
    function save()
    {
        $this->check_action_permission('add_update');
        $office_id = $this->input->post("office_id");        
        $this->load->helper('directory');
        $valid_languages = directory_map(APPPATH.'language/', 1);
        $batch_save_data=array(
            'ofc_company'=>$this->input->post('company'),
            'ofc_address'=>$this->input->post('address'),
            'ofc_phone'=>$this->input->post('phone'),
            'ofc_email'=>$this->input->post('email'),
            'ofc_stock_alert_email'=>$this->input->post('stock_alert_email'),
            'ofc_fax'=>$this->input->post('fax'),
            'ofc_website'=>$this->input->post('website'),
            // 'default_tax_1_rate'=>$this->input->post('default_tax_1_rate'),     
            // 'default_tax_1_name'=>$this->input->post('default_tax_1_name'),     
            // 'default_tax_2_rate'=>$this->input->post('default_tax_2_rate'), 
            // 'default_tax_2_name'=>$this->input->post('default_tax_2_name'),
            // 'default_tax_2_cumulative' => $this->input->post('default_tax_2_cumulative') ? 1 : 0,       
            'ofc_currency_symbol'=>$this->input->post('currency_symbol'),
            'ofc_return_policy'=>$this->input->post('return_policy'),
            'ofc_language'=>in_array($this->input->post('language'), $valid_languages) ? $this->input->post('language') : 'english',
            'ofc_timezone'=>$this->input->post('timezone'),
            'ofc_date_format'=>$this->input->post('date_format'),
            'ofc_time_format'=>$this->input->post('time_format'),
            'ofc_print_after_sale'=>$this->input->post('print_after_sale') ? 1 : 0,
            'ofc_round_cash_on_sales'=>$this->input->post('round_cash_on_sales') ? 1 : 0,
            'ofc_automatically_email_receipt'=>$this->input->post('automatically_email_receipt') ? 1 : 0,
            'ofc_barcode_price_include_tax'=>$this->input->post('barcode_price_include_tax') ? 1 : 0,
            'ofc_hide_signature'=>$this->input->post('hide_signature') ? 1 : 0,
            'ofc_disable_confirmation_sale'=>$this->input->post('disable_confirmation_sale') ? 1 : 0,
            'ofc_track_cash' => $this->input->post('track_cash') ? 1 : 0,
            'ofc_mailchimp_api_key'=>$this->input->post('mailchimp_api_key'),
            'ofc_number_of_items_per_page'=>$this->input->post('number_of_items_per_page'),
            'ofc_additional_payment_types' => $this->input->post('additional_payment_types'),
            'ofc_hide_suspended_sales_in_reports' => $this->input->post('hide_suspended_sales_in_reports') ? 1 : 0,
            'ofc_speed_up_search_queries' => $this->input->post('speed_up_search_queries') ? 1 : 0,
            'ofc_receive_stock_alert' => $this->input->post('receive_stock_alert') ? 1 : 0,
            'ofc_enable_credit_card_processing'=>$this->input->post('enable_credit_card_processing') ? 1 : 0,       
            'ofc_merchant_id'=>$this->input->post('merchant_id'),
            'ofc_merchant_password'=>$this->input->post('merchant_password'),
            'ofc_default_payment_type'=> $this->input->post('default_payment_type'),
            'ofc_default_currency'=> $this->input->post('default_currency'),
        );

        $office_data = array(
            'office_name' => $this->input->post('office_name'),
            'alias_name' => strtolower(str_replace(' ', "_", $this->input->post('office_name') )),
            'deleted' => 0
            );

        if (!empty($_FILES["company_logo"]))
        {
            $batch_save_data['ofc_company_logo'] = 1;
        }
        elseif($this->input->post('delete_logo'))
        {
            $batch_save_data['ofc_company_logo'] = 0;
        }

        if(($_SERVER['HTTP_HOST'] !='demo.phppointofsale.com' && $_SERVER['HTTP_HOST'] !='demo.phppointofsalestaging.com') && $this->Office->save($batch_save_data, $office_data, $office_id))
        // if($this->Appconfig->batch_save($batch_save_data))
        {
            if($office_id== '')
            {
                $this->session->set_userdata('success', show_message(lang('offices_successful_adding').' '.$batch_save_data['officeID'], "success"));
                $office_id = $batch_save_data['officeID'];
            }
            else //previous customer
            {
                $this->session->set_userdata('success', show_message(lang('offices_successful_updating').' '.$office_id, "success"));
            }

            if(!empty($_FILES["company_logo"]) && $_FILES["company_logo"]["error"] == UPLOAD_ERR_OK && ($_SERVER['HTTP_HOST'] !='demo.phppointofsale.com' && $_SERVER['HTTP_HOST'] !='demo.phppointofsalestaging.com'))
            {
                $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                $extension = strtolower(end(explode('.', $_FILES["company_logo"]["name"])));
                
                if (in_array($extension, $allowed_extensions))
                {
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $_FILES["company_logo"]["tmp_name"];
                    $config['create_thumb'] = FALSE;
                    $config['maintain_ratio'] = TRUE;
                    $config['width']     = 170;
                    $config['height']   = 60;
                    $this->load->library('image_lib', $config); 
                    $this->image_lib->resize();
                    $company_logo = $this->Appfile->save($_FILES["company_logo"]["name"], file_get_contents($_FILES["company_logo"]["tmp_name"]), $office_id);
                }
            }
            elseif($this->input->post('delete_logo'))
            {
                $this->Appfile->delete($office_id);
            }

            $this->redirection(strtolower(get_class())."/view/".$office_id);
        }
        else
        {
            if ($office_id != '') {
                $url = $this->redirection(strtolower(get_class())."/view/".$office_id);
            } else {
                $url = $this->redirection(strtolower(get_class())."/view");
            }
            $this->session->set_userdata('error', show_message(lang('offices_error_adding_updating'), "error"));
            $this->redirection($url);
        }
    }

    // Delete office as array from database
    function delete()
    {
        $this->check_action_permission('delete');
        $offices_to_delete=$this->input->post('checkedID');
        if($this->Office->delete_list($offices_to_delete))
        {
            echo json_encode(array('success'=>true,'message'=>lang('offices_successful_deleted').' '.
            count($offices_to_delete).' '.lang('offices_one_or_multiple')));
        }
        else
        {
            echo json_encode(array('success'=>false,'message'=>lang('offices_cannot_be_deleted')));
        }
    }

    /**
    *Export as Excel
    */
    function excel_export($template = 0) {
        $data = $this->Office->get_all()->result_object();
        $this->load->helper('report');
        $rows = array();
        $row = array(
            lang('offices_id'), 
            lang('offices_name'), 
            lang('offices_address'), 
            lang('common_phone_number'),
            lang('common_email'),
            lang('config_company'),
            lang('config_default_payment_type'),
            lang('config_return_policy_required')
            );

        $n = 1;
        $rows[] = $row;
        foreach ($data as $r) {
            $row = array(
                $n ++,
                $r->office_name,
                $r->ofc_address,
                $r->ofc_phone,
                $r->ofc_email,
                $r->ofc_company,
                $r->ofc_default_payment_type,
                $r->ofc_return_policy
            );
            $rows[] = $row;
        }

        $content = array_to_csv($rows);

        if ($template) {
            force_download('offices_export_mass_update.csv', $content);
        } else {
            force_download('offices_export.csv', $content);
        }
        exit;
    }

    //For autocomplete search
    function suggest() {
        $suggestions = $this->Office->get_search_suggestions($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

    // Work after select autocomplete result
    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->Office->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'office_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        $config['base_url'] = site_url('offices/offices/' . $this->uri->segment(3));
        $config['total_rows'] = $this->Office->search_count_all($search);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_offices_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

}
?>