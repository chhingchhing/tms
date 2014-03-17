<?php
require_once ("person_controller.php");
class Customers extends Person_controller
{
	function __construct()
	{
		parent::__construct('customers');
	}
		
	function index()
	{
		$this->customers();
	}

	function customers() {
        $this->session->set_userdata("cur_page", $this->uri->segment(3));
        $this->session->set_userdata("pagination", $this->uri->segment(4));

		$logged_in_employee_info=$this->Employee->get_logged_in_employee_info();
		$office = substr($this->uri->segment(3), -1);
		$data['allowed_modules']=$this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id);//get officle allowed
		
        $this->check_action_permission('add_update');
		$data['person_info']=$this->Employee->get_info($employee_id);
		$data['all_modules']=$this->Module->get_all_modules();
                
		$this->check_action_permission('search');
		// $config['base_url'] = site_url('customers/sorting');
		$config['base_url'] = site_url('customers/customers/'.$this->uri->segment(3));
		$config['total_rows'] = $this->Customer->count_all();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		
		$config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();
		$data['per_page'] = $config['per_page'];
		if ($this->uri->segment(4)) {
			$data['manage_table'] = $this->sorting($this->uri->segment(4));
		} else {
			$data['manage_table']=get_people_manage_table($this->Customer->get_all($data['per_page']),$this);
		}
		$this->load->view('people/manage',$data);
	}
	
	function sorting($offset)
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		if ($search)
		{
			$config['total_rows'] = $this->Customer->search_count_all($search);
			$table_data = $this->Customer->search($search,$per_page,$offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		else
		{
			$config['total_rows'] = $this->Customer->count_all();
			$table_data = $this->Customer->get_all($per_page,$offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		$config['base_url'] = site_url('customers/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_people_manage_table_pagination($table_data,$this);
		return $data['manage_table'];
		
	}
	
	/*
	Returns customer table data rows. This will be called with AJAX.
	*/
	function search()
	{
		$this->check_action_permission('search');
		$search = $this->input->post('search');
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Customer->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		$config['base_url'] = site_url('customers/customers/'.$this->uri->segment(3));
		$config['total_rows'] = $this->Customer->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_people_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Customer->get_search_suggestions($this->input->post('term'),100);
		echo json_encode($suggestions);
	}
	
	/*
	Loads the customer edit form
	*/
	function view($customer_id=-1)
	{
		$this->check_action_permission('add_update');
		$data['person_info']=$this->Customer->get_info($customer_id);
		echo json_encode($data['person_info']);
	}
	
	function account_number_exists()
	{
		if($this->Customer->account_number_exists($this->input->post('account_number')))
		echo 'false';
		else
		echo 'true';
		
	}
	
	/*
	Inserts/updates a customer
	*/
	function save($customer_id=-1)
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
		$customer_data=array(
			'company_name' => $this->input->post('company_name'),
			'account_number'=>$this->input->post('account_number')=='' ? null:$this->input->post('account_number'),
			'taxable'=>$this->input->post('taxable')=='' ? 0:1,
			'hotel_name' => $this->input->post('hotel_name'),
			'room_number' => $this->input->post('room_number')
		);
		
		if ($this->input->post('delete_cc_info'))
		{
			$customer_data['cc_token'] = NULL;
			$customer_data['cc_preview'] = NULL;
		}
		
		$result = $this->Customer->saved($person_data,$customer_data,$customer_id);
		if($result)
		{
			if ($this->config->item('mailchimp_api_key'))
			{
				$this->Person->update_mailchimp_subscriptions($this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('mailing_lists'));
			}
			//New customer
			if($customer_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>lang('customers_successful_adding').' '.
				$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>$customer_data['customer_id']));
			}
			else //previous customer
			{
				echo json_encode(array('success'=>true,'message'=>lang('customers_successful_updating').' '.
				$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>$customer_id));
			}
		}
		else//failure
		{	
			echo json_encode(array('success'=>false,'message'=>lang('customers_error_adding_updating').' '.
			$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>-1));
		}
	}
	
	/*
	This deletes customers from the customers table
	*/
	function delete()
	{
		$this->check_action_permission('delete');
		$customers_to_delete=$this->input->post('checkedID');
		if($this->Customer->delete_list($customers_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('customers_successful_deleted').' '.
			count($customers_to_delete).' '.lang('customers_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('customers_cannot_be_deleted')));
		}
	}
	
	function excel()
	{
		$data = file_get_contents("import_customers.csv");
		$name = 'import_customers.csv';
		force_download($name, $data);
	}
	
	function check_duplicate()
	{
		$term = $this->input->post('first_name')." ".$this->input->post('last_name');
		echo json_encode(array('duplicate'=>$this->Customer->check_duplicate($term)));
	}
	/* added for excel expert */
	function excel_export($template=0) {
		$data = $this->Customer->get_all()->result_object();
		$this->load->helper('report');
		$rows = array();
		$row = array(lang('common_first_name'),lang('common_last_name'),lang('common_email'),lang('common_phone_number'),lang('common_address_1'),lang('common_address_2'),lang('common_city'),	lang('common_state'),lang('common_zip'),lang('common_country'),lang('common_comments'),lang('customers_account_number'),lang('customers_taxable'),lang('customers_company_name'),lang('customers_customer_id'));
		
		
		$rows[] = $row;
		foreach ($data as $r) {
			$row = array(
				$r->first_name,
				$r->last_name,
				$r->email,
				$r->phone_number,
				$r->address_1,
				$r->address_2,
				$r->city,
				$r->state,
				$r->zip,
				$r->country,
				$r->comments,
				$r->account_number,
				$r->taxable ? 'y' : '',
				$r->company_name,
				$r->person_id
			);
			$rows[] = $row;
		}
		
		$content = array_to_csv($rows);
		
		if($template)
		{
			force_download('customers_export_mass_update.csv', $content);
		}
		else
		{
			force_download('customers_export.csv', $content);
		}
		exit;
	}
	function do_excel_import()
	{
		$controller = "customers";
		$function = "customers";		
		set_time_limit(0);
		$this->check_action_permission('add_update');
		$this->db->trans_start();
				
		$msg = 'do_excel_import';
		$failCodes = array();
		if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK)
		{
			$this->session->set_userdata('error', show_message("Import Excel failure!", "error"));
            $this->redirection($controller, $function);
		}
		else
		{
			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{
				//Skip first row
				fgetcsv($handle);
				while (($data = fgetcsv($handle)) !== FALSE) 
				{
					$person_data = array(
					'first_name'=>$data[0],
					'last_name'=>$data[1],
					'email'=>$data[2],
					'phone_number'=>$data[3],
					'address_1'=>$data[4],
					'address_2'=>$data[5],
					'city'=>$data[6],
					'state'=>$data[7],
					'zip'=>$data[8],
					'country'=>$data[9],
					'comments'=>$data[10]
					);
					
					$customer_data=array(
					'account_number'=>$data[11]=='' ? null:$data[11],
					'taxable'=>$data[12] != '' and $data[12] != '0' and strtolower($data[12]) != 'n' ? '1' : '0',
					'company_name' => $data[13],
					);
					
					if(!$this->Customer->saved($person_data,$customer_data))
					{	
						$this->session->set_userdata('error', show_message("Customer duplicate account_ID!", "error"));
			            $this->redirection($controller, $function);
					}
				}
			}
			else 
			{
				$this->session->set_userdata('error', show_message("Your upload is not support format!", "error"));
	            $this->redirection($controller, $function);
			}
		}
		$success = $this->db->trans_complete();
		if ($success) {
			$this->session->set_userdata('success', show_message("Import was successfully!", "success"));
            $this->redirection($controller, $function);
		}
	}	

	function do_excel_import_update()
	{
		$controller = "customers";
		$function = "customers";
		set_time_limit(0);
		$this->check_action_permission('add_update');
		$this->db->trans_start();
		$msg = 'do_excel_import';
		$failCodes = array();
		if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK)
		{
			$this->session->set_userdata('error', show_message("Import Excel failure!", "error"));
            $this->redirection($controller, $function);
		}
		else
		{
			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{
				//Skip first row
				fgetcsv($handle);
				while (($data = fgetcsv($handle)) !== FALSE) 
				{
					$person_data = array(
					'first_name'=>$data[0],
					'last_name'=>$data[1],
					'email'=>$data[2],
					'phone_number'=>$data[3],
					'address_1'=>$data[4],
					'address_2'=>$data[5],
					'city'=>$data[6],
					'state'=>$data[7],
					'zip'=>$data[8],
					'country'=>$data[9],
					'comments'=>$data[10]
					);
					
					$customer_data=array(
						'account_number'=>$data[11]=='' ? null:$data[11],
						'taxable'=>$data[12] != '' and $data[12] != '0' and strtolower($data[12]) != 'n' ? '1' : '0',
						'company_name' => $data[13],
					);
					if(!$this->Customer->exists($data[14]))
					{
						$this->Customer->saved($person_data,$customer_data,$data[14]);
					}
					else 
					{	
						$this->session->set_userdata('error', show_message("Customer duplicate account_ID!", "error"));
			            $this->redirection($controller, $function);
					}
				}
			}
			else 
			{
				$this->session->set_userdata('error', show_message("Your upload is not support format!", "error"));
	            $this->redirection($controller, $function);
			}
		}
		$success = $this->db->trans_complete();
		if ($success) {
			$this->session->set_userdata('success', show_message("Customer import was successfully!", "success"));
            $this->redirection($controller, $function);
		} else {
			$this->session->set_userdata('error', show_message("Cannot import excel to update!", "error"));
            $this->redirection($controller, $function);
		}
	}

	function redirection($controller, $function){
		if ($this->session->userdata('per_page')) {
            redirect($controller . '/' . $function . '/' . $this->session->userdata('cur_page') . '/' . $this->session->userdata('pagination') . '?limit=' . $this->session->userdata('per_page'));
        }
        redirect($controller . '/' . $function . '/' . $this->session->userdata('cur_page') . '/' . $this->session->userdata('pagination'));
	}
	
	
	function cleanup()
	{
		$cleanup = $this->Customer->cleanup();
		if ($cleanup) {
			redirect("customers/customers");
		}
	}
	
	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{			
		return 550;
	}

	/*
	Gets one row for a person manage table. This is called using AJAX to update one row.
	*/
	function get_row()
	{
		$person_id = $this->input->post('row_id');
		$data_row=get_person_data_row($this->Customer->get_info($person_id),$this);
		echo $data_row;
	}


/* Upload file testing with jQuery but work smoothly */
	function upload() {
		$output_dir = "uploads/";

		if(isset($_FILES["myfile"]))
		{
			//Filter the file types , if you want.
			if ($_FILES["myfile"]["error"] > 0)
			{
			  echo "Error: " . $_FILES["file"]["error"] . "<br>";
			}
			else
			{
				//move the uploaded file to uploads folder;
		    	move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir. $_FILES["myfile"]["name"]);
		    
		   	 echo "Uploaded File :".$_FILES["myfile"]["name"];
			}

		}
	}

}
?>