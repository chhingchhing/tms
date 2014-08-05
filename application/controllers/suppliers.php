<?php
require_once ("person_controller.php");
class Suppliers extends Person_controller
{
	function __construct()
	{
		parent::__construct('suppliers');
	}
	
	
	function index()
	{
		$this->suppliers();
	}

	function suppliers() {
		$logged_in_employee_info=$this->Employee->get_logged_in_employee_info();
		$office = substr($this->uri->segment(3), -1);
		$data['allowed_modules']=$this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id);//get officle allowed

		$this->check_action_permission('search');
		$config['base_url'] = site_url('suppliers/suppliers/'.$this->uri->segment(3));
		$config['total_rows'] = $this->Supplier->count_all();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		
        $config['uri_segment'] = 4;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = round($choice);

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['controller_name']=strtolower(get_class());

		$data['suppliers_type'] = $this->Supplier->select_supplier_by_type();

		$data['form_width']=$this->get_form_width();
		if ($this->uri->segment(4)) {
			$data['manage_table'] = $this->sorting($this->uri->segment(4));
		} else {
			$data['per_page'] = $config['per_page'];
			$data['manage_table']=get_supplier_manage_table($this->Supplier->get_all($data['per_page']),$this);
		}
		$this->load->view('suppliers/manage',$data);
	}
	
	function sorting($offset)
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		if ($search)
		{
			$config['total_rows'] = $this->Supplier->search_count_all($search);
			$table_data = $this->Supplier->search($search,$per_page, $offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		else
		{
			$config['total_rows'] = $this->Supplier->count_all();
			$table_data = $this->Supplier->get_all($per_page,$offset ? $offset : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'company_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		$config['base_url'] = site_url('suppliers/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_supplier_manage_table_pagination($table_data,$this);
		return $data['manage_table'];
	}
	
	
	/* added for excel expert */
	function excel_export() {
		$data = $this->Supplier->get_all()->result_object();
		$this->load->helper('report');
		$rows = array();
		$row = array(lang('suppliers_company_name'),lang('common_first_name'),lang('common_last_name'),lang('common_email'),lang('common_phone_number'),lang('common_address_1'),lang('common_address_2'),lang('common_city'),	lang('common_state'),lang('common_zip'),lang('common_country'),lang('common_comments'),lang('suppliers_account_number'));
		
		$rows[] = $row;
		foreach ($data as $r) {
			$row = array(
				$r->company_name,
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
				$r->account_number
			);
			$rows[] = $row;
		}
		
		$content = array_to_csv($rows);

		force_download('suppliers_export' . '.csv', $content);
		exit;
	}
	/*
	Returns supplier table data rows. This will be called with AJAX.
	*/
	function search()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Supplier->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'company_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		$config['base_url'] = site_url('suppliers/suppliers/'.$this->uri->segment(3));
		$config['total_rows'] = $this->Supplier->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_supplier_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
		
	}

	/*function search_()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Customer->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		// $config['base_url'] = site_url('customers/search');
		$config['base_url'] = site_url('customers/customers/'.$this->uri->segment(3));
		$config['total_rows'] = $this->Customer->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_people_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
	}*/
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Supplier->get_search_suggestions($this->input->post('term'),100);
		echo json_encode($suggestions);
	}
	
	
	/*
	Loads the supplier edit form
	*/
	function view($supplier_id=-1)
	{
		$this->check_action_permission('add_update');		
		$data['person_info']=$this->Supplier->get_info($supplier_id);
		$this->load->view("suppliers/form",$data);
	}


	/*
	Loads the supplier json data
	*/
	function viewJSON($supplier_id=-1)
	{
            
		$this->check_action_permission('add_update');		
		$data['suppliers_type'] = $this->Supplier->select_supplier_by_type();
		$data['person_info']=$this->Supplier->get_info($supplier_id);
		$this->load->view("suppliers/_form", $data);
	}
	

	/*
	Inserts/updates a supplier
	*/
	/*function save_mine($supplier_id=-1)
	{
		$this->check_action_permission('add_update');
		if($this->Supplier->save($person_data,$supplier_data,$supplier_id))
		{
			if ($this->config->item('mailchimp_api_key'))
			{
				$this->Person->update_mailchimp_subscriptions($this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('mailing_lists'));
			}
			//New supplier
			if($supplier_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>lang('suppliers_successful_adding').' '.
				$supplier_data['company_name'],'person_id'=>$supplier_data['person_id']));
			}
			else //previous supplier
			{
				echo json_encode(array('success'=>true,'message'=>lang('suppliers_successful_updating').' '.
				$supplier_data['company_name'],'person_id'=>$supplier_id));
			}
		}
		else//failure
		{	
			echo json_encode(array('success'=>false,'message'=>lang('suppliers_error_adding_updating').' '.
			$supplier_data['company_name'],'person_id'=>-1));
		}
	}*/


		
	function account_number_exists($account_number)
	{
		if($this->Supplier->account_number_exists($account_number))
		return 'false';
		else
		return 'true';
		
	}
	/*
	This deletes suppliers from the suppliers table
	*/
	function delete()
	{
		$this->check_action_permission('delete');
		$suppliers_to_delete=$this->input->post('checkedID');
		if($this->Supplier->delete_list($suppliers_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('suppliers_successful_deleted').' '.
			count($suppliers_to_delete).' '.lang('suppliers_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('suppliers_cannot_be_deleted')));
		}
	}
	
	/*
	Gets one row for a supplier manage table. This is called using AJAX to update one row.
	*/
	function get_row()
	{
		$person_id = $this->input->post('row_id');
		$data_row=get_supplier_data_row($this->Supplier->get_info($person_id),$this);
	}
	
	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{			
		return 550;
	}



	/*
	Inserts/updates a supplier
	*/
	function save($supplier_id=-1) 
	{
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

		if ($this->input->post('new_supplier_type')) {
            $supplier_types = array(
                'supplier_type_name' => $this->input->post('new_supplier_type')
            );
            $this->Supplier->add_new_supplier_type($supplier_types);
            $supplier_typeID = $supplier_types['supplier_type_id'];
        }
		$supplier_data=array(
			'company_name'=>$this->input->post('company_name'),
			'account_number'=>$this->input->post('account_number')=='' ? null:$this->input->post('account_number'),
			'supplier_typeID' => $this->input->post('supplier_type') != 0 ? $this->input->post('supplier_type') : $supplier_typeID
		);

		$email = $this->input->post('email');
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$mailing_lists = $this->input->post('mailing_lists');

		$account_number_exists = $this->account_number_exists($this->input->post("account_number"));
		if ($account_number_exists == 'false') {
			$data = $this->Supplier->account_number_exists($this->input->post("account_number"));
			foreach($data->result() as $row){
				$supplier_id = $row->supplier_id;
			}
		}
		$result = $this->saved_supplier($supplier_id, $person_data, $supplier_data,$email,$first_name,$last_name,$mailing_lists);
	}


	function saved_supplier($supplier_id, $person_data, $supplier_data,$email,$first_name,$last_name,$mailing_lists)
	{
		$this->check_action_permission('add_update');
		$result = $this->Supplier->save($person_data,$supplier_data,$supplier_id);
		if($result)
		{
			if ($this->config->item('mailchimp_api_key'))
			{
				$this->Person->update_mailchimp_subscriptions($email,$first_name,$last_name,$mailing_lists);
			}
			
			//New supplier
			if($supplier_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>lang('suppliers_successful_adding').' '.
				$supplier_data['company_name'],'person_id'=>$supplier_data['supplier_id']));
			}
			else //previous supplier
			{
				echo json_encode(array('success'=>true,'message'=>lang('suppliers_successful_updating').' '.
				$supplier_data['company_name'],'person_id'=>$supplier_id));
			}
		}
		else//failure
		{	
			echo json_encode(array('success'=>false,'message'=>lang('suppliers_error_adding_updating').' '.
			$supplier_data['company_name'],'person_id'=>-1));
		}
	}

	function email_to() {

	}

	/*
	Returns hotel name from suppliers table data rows. This will be called with AJAX.
	*/
	function filter_hotels() {
        $suggestions = $this->Supplier->filter_hotels($this->input->post('term'), 100);
        echo json_encode($suggestions);
    }

    function check_hotel() {
        echo json_encode(array('duplicate' => $this->Supplier->check_duplicate_hotel($this->input->post("company_name"))));
    }


}
?>