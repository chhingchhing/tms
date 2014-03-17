<?php
require_once ("secure_area.php");
class Config extends Secure_area 
{
	function __construct()
	{
		parent::__construct('config');
	}

	function index() {
		$this->config();
	}
	
	function config()
	{	
        $this->session->set_userdata("cur_page", $this->uri->segment(3));
        $this->session->set_userdata("pagination", $this->uri->segment(4));

		$logged_in_employee_info=$this->Employee->get_logged_in_employee_info();
		$office = substr($this->uri->segment(3), -1);
		$data['allowed_modules']=$this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id);//get officle allowed


		$data['controller_name']=strtolower(get_class());
		$data['payment_options']=array(
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_check') => lang('sales_check'),
				lang('sales_giftcard') => lang('sales_giftcard'),
				lang('sales_debit') => lang('sales_debit'),
				lang('sales_credit') => lang('sales_credit')

		);
		
		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			$data['payment_options'][$additional_payment_type] = $additional_payment_type;
		}
		
		$this->load->view("config", $data);
	}
		
	function save()
	{
		if(!empty($_FILES["company_logo"]) && $_FILES["company_logo"]["error"] == UPLOAD_ERR_OK && ($_SERVER['HTTP_HOST'] !='demo.phppointofsale.com' && $_SERVER['HTTP_HOST'] !='demo.phppointofsalestaging.com'))
		{
			$allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
			$extension = strtolower(end(explode('.', $_FILES["company_logo"]["name"])));
			
			if (in_array($extension, $allowed_extensions))
			{
				$config['image_library'] = 'gd2';
				$config['source_image']	= $_FILES["company_logo"]["tmp_name"];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = TRUE;
				$config['width']	 = 170;
				$config['height']	= 60;
				$this->load->library('image_lib', $config); 
				$this->image_lib->resize();
				$company_logo = $this->Appfile->save($_FILES["company_logo"]["name"], file_get_contents($_FILES["company_logo"]["tmp_name"]), $this->config->item('company_logo'));
			}
		}
		elseif($this->input->post('delete_logo'))
		{
			$this->Appfile->delete($this->config->item('company_logo'));
		}
		
		$this->load->helper('directory');
		$valid_languages = directory_map(APPPATH.'language/', 1);
		$batch_save_data=array(
		'company'=>$this->input->post('company'),
		'address'=>$this->input->post('address'),
		'phone'=>$this->input->post('phone'),
		'email'=>$this->input->post('email'),
		'stock_alert_email'=>$this->input->post('stock_alert_email'),
		'fax'=>$this->input->post('fax'),
		'website'=>$this->input->post('website'),
		'default_tax_1_rate'=>$this->input->post('default_tax_1_rate'),		
		'default_tax_1_name'=>$this->input->post('default_tax_1_name'),		
		'default_tax_2_rate'=>$this->input->post('default_tax_2_rate'),	
		'default_tax_2_name'=>$this->input->post('default_tax_2_name'),
		'default_tax_2_cumulative' => $this->input->post('default_tax_2_cumulative') ? 1 : 0,		
		'currency_symbol'=>$this->input->post('currency_symbol'),
		'return_policy'=>$this->input->post('return_policy'),
		'language'=>in_array($this->input->post('language'), $valid_languages) ? $this->input->post('language') : 'english',
		'timezone'=>$this->input->post('timezone'),
		'date_format'=>$this->input->post('date_format'),
		'time_format'=>$this->input->post('time_format'),
		'print_after_sale'=>$this->input->post('print_after_sale') ? 1 : 0,
		'round_cash_on_sales'=>$this->input->post('round_cash_on_sales') ? 1 : 0,
		'automatically_email_receipt'=>$this->input->post('automatically_email_receipt') ? 1 : 0,
		'barcode_price_include_tax'=>$this->input->post('barcode_price_include_tax') ? 1 : 0,
		'hide_signature'=>$this->input->post('hide_signature') ? 1 : 0,
		'disable_confirmation_sale'=>$this->input->post('disable_confirmation_sale') ? 1 : 0,
		'track_cash' => $this->input->post('track_cash') ? 1 : 0,
		'mailchimp_api_key'=>$this->input->post('mailchimp_api_key'),
		'number_of_items_per_page'=>$this->input->post('number_of_items_per_page'),
		'additional_payment_types' => $this->input->post('additional_payment_types'),
		'hide_suspended_sales_in_reports' => $this->input->post('hide_suspended_sales_in_reports') ? 1 : 0,
		'speed_up_search_queries' => $this->input->post('speed_up_search_queries') ? 1 : 0,
		'receive_stock_alert' => $this->input->post('receive_stock_alert') ? 1 : 0,
		'enable_credit_card_processing'=>$this->input->post('enable_credit_card_processing') ? 1 : 0,		
		'merchant_id'=>$this->input->post('merchant_id'),
		'merchant_password'=>$this->input->post('merchant_password'),
		'default_payment_type'=> $this->input->post('default_payment_type'),
		);

		if (isset($company_logo))
		{
			$batch_save_data['company_logo'] = $company_logo;
		}
		elseif($this->input->post('delete_logo'))
		{
			$batch_save_data['company_logo'] = 0;
		}
		
		// if(($_SERVER['HTTP_HOST'] !='demo.phppointofsale.com' && $_SERVER['HTTP_HOST'] !='demo.phppointofsalestaging.com') && $this->Appconfig->batch_save($batch_save_data))
		if($this->Appconfig->batch_save($batch_save_data))
		{
			// echo json_encode(array('success'=>true,'message'=>lang('config_saved_successfully')));
			$this->session->set_userdata('success', show_message("Change config was successfully!", "success"));
            $this->redirection("config", "config");
		}
		else
		{
			// echo json_encode(array('success'=>false,'message'=>lang('config_saved_unsuccessfully')));
			$this->session->set_userdata('error', show_message("Cannot import excel to update!", "error"));
			$this->redirection("config", "config");
		}
	}
	
	function backup()
	{
		$this->load->dbutil();
		$prefs = array(
			'format'      => 'txt',             // gzip, zip, txt
			'add_drop'    => FALSE,              // Whether to add DROP TABLE statements to backup file
			'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
			'newline'     => "\n"               // Newline character used in backup file
    	);
		$backup =&$this->dbutil->backup($prefs);
		$backup = 'SET FOREIGN_KEY_CHECKS = 0;'."\n".$backup."\n".'SET FOREIGN_KEY_CHECKS = 1;';
		force_download('php_point_of_sale.sql', $backup);
	}
	
	function optimize()
	{
		$this->load->dbutil();
		$this->dbutil->optimize_database();
		echo json_encode(array('success'=>true,'message'=>lang('config_database_optimize_successfully')));
	}

	function redirection($controller, $function){
		redirect($controller . '/' . $function . '/' . $this->session->userdata('cur_page') . '/' . $this->session->userdata('pagination'));
	}

}
?>