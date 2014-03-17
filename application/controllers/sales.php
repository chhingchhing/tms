<?php
require_once ("secure_area.php");
class Sales extends Secure_area
{
	function __construct()
	{
		parent::__construct('sales');
		$this->load->library('sale_lib');
		$test = "hello test";
	}

	/*function index()
	{
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
				$this->load->view('sales/opening_amount');
			}
		} else {
			$this->_reload(array(), false);
		}
	}*/
	
	function closeregister() 
	{
		if (!$this->Sale->is_register_log_open()) 
		{
			redirect(site_url('home'));
			return;
		}
		$cash_register = $this->Sale->get_current_register_log();
		$continueUrl = $this->input->get('continue');
		if ($this->input->post('closing_amount') != '') {
			$now = date('Y-m-d H:i:s');
			$cash_register->shift_end = $now;
			$cash_register->close_amount = $this->input->post('closing_amount');
			$cash_register->cash_sales_amount = $this->Sale->get_cash_sales_total_for_shift($cash_register->shift_start, $cash_register->shift_end);			
			unset($cash_register->register_log_id);
			$this->Sale->update_register_log($cash_register);
			if ($continueUrl == 'logout') {
				redirect(site_url('home/logout'));
			} else {
				redirect(site_url('home'));
			}
		} else {
			$this->load->view('sales/closing_amount', array(
				'continue'=>$continueUrl ? "?continue=$continueUrl" : '',
				'closeout'=>to_currency($cash_register->open_amount + $this->Sale->get_cash_sales_total_for_shift($cash_register->shift_start, date("Y-m-d H:i:s")))
			));
		}
	}
	
	/*function item_search()
	{
		$suggestions = $this->Item->get_item_search_suggestions($this->input->get('term'),100);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_item_kit_search_suggestions($this->input->get('term'),100));
		echo json_encode($suggestions);
	}*/

/* Global function */
	// function customer_search()
	// {
	// 	$suggestions = $this->Customer->get_customer_search_suggestions($this->input->get('term'),100);
	// 	echo json_encode($suggestions);
	// }

	/*function select_customer()
	{
		$data = array();
		$customer_id = $this->input->post("customer");
		
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
	}*/

	function change_mode()
	{
		$mode = $this->input->post("mode");
		$this->sale_lib->set_mode($mode);
		$this->_reload();
	}
	
	function set_comment() 
	{
 	  $this->sale_lib->set_comment($this->input->post('comment'));
	}
	
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
	
	/*function set_comment_on_receipt() 
	{
 	  $this->sale_lib->set_comment_on_receipt($this->input->post('show_comment_on_receipt'));
	}*/
	
	function set_email_receipt()
	{
 	  $this->sale_lib->set_email_receipt($this->input->post('email_receipt'));
	}

	function set_save_credit_card_info() 
	{
 	  $this->sale_lib->set_save_credit_card_info($this->input->post('save_credit_card_info'));
	}
	
	function set_use_saved_cc_info()
	{
 	  $this->sale_lib->set_use_saved_cc_info($this->input->post('use_saved_cc_info'));
	}

	//Alain Multiple Payments
	/*function add_payment()
	{		
		$data=array();
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			if ( $this->input->post('payment_type') == lang('sales_giftcard') )
				$data['error']=lang('sales_must_enter_numeric_giftcard');
			else
				$data['error']=lang('sales_must_enter_numeric');
				
 			$this->_reload($data);
 			return;
		}
				
		$payment_type=$this->input->post('payment_type');
		$payment_total = $this->input->post('payment_total');		


		if ( $payment_type == lang('sales_giftcard') )
		{
			if(!$this->Giftcard->exists($this->Giftcard->get_giftcard_id($this->input->post('amount_tendered'))))
			{
				$data['error']=lang('sales_giftcard_does_not_exist');
				$this->_reload($data);
				return;
			}
			
			$payment_type=$this->input->post('payment_type').':'.$this->input->post('amount_tendered');
			$current_payments_with_giftcard = $this->sale_lib->get_payment_amount($payment_type);
			$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $current_payments_with_giftcard;
			if ( $cur_giftcard_value <= 0 && $this->sale_lib->get_total() > 0)
			{
				$data['error']=lang('sales_giftcard_balance_is').' '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) ).' !';
				$this->_reload($data);
				return;
			}
			elseif ( ( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ) > 0 )
			{
				$data['warning']=lang('sales_giftcard_balance_is').' '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ).' !';
			}
			$payment_amount=min( $this->sale_lib->get_amount_due(), $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) );
		}
		else
		{
			$payment_amount=$this->input->post('amount_tendered');
		}
		
		if( !$this->sale_lib->add_payment( $payment_type, $payment_amount, $payment_total ) )
		{
			$data['error']=lang('sales_unable_to_add_payment');
		}
		
		$this->_reload($data);
	}*/

	//Alain Multiple Payments
	/*function delete_payment($payment_id)
	{
		$this->sale_lib->delete_payment($payment_id);
		$this->_reload();
	}*/

	/*function add()
	{
		$data=array();
		$mode = $this->sale_lib->get_mode();
		$item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");
		$quantity = $mode=="sale" ? 1:-1;

		if($this->sale_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) && $mode=='return')
		{
			$this->sale_lib->return_entire_sale($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->sale_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			$this->sale_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt, $quantity);

			//As surely a Kit item , do out of stock check
			$item_kit_id = $this->sale_lib->get_valid_item_kit_id($item_id_or_number_or_item_kit_or_receipt);

			if($this->sale_lib->out_of_stock_kit($item_kit_id))
			{
				$data['warning'] = lang('sales_quantity_less_than_zero');
			}
		}
		else if(!$this->Item->get_info($item_id_or_number_or_item_kit_or_receipt)->description=="" && $this->Giftcard->get_giftcard_id($this->Item->get_info($item_id_or_number_or_item_kit_or_receipt)->description,true))
		{
			$data['error']=lang('sales_unable_to_add_item');
		}
		elseif(!$this->sale_lib->add_item($item_id_or_number_or_item_kit_or_receipt,$quantity))
		{
			$data['error']=lang('sales_unable_to_add_item');
		}
		
		if($this->sale_lib->out_of_stock($item_id_or_number_or_item_kit_or_receipt))
		{
			$data['warning'] = lang('sales_quantity_less_than_zero');
		}
		$this->_reload($data);
	}*/

	function edit_item($line)
	{
		$data= array();

		$this->form_validation->set_rules('price', 'lang:items_price', 'required|numeric');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required|numeric');

        $description = $this->input->post("description");
        $serialnumber = $this->input->post("serialnumber");
		$price = $this->input->post("price");
		$quantity = $this->input->post("quantity");
		$discount = $this->input->post("discount");


		if ($this->form_validation->run() != FALSE)
		{
			$this->sale_lib->edit_item($line,$description,$serialnumber,$quantity,$discount,$price);
		}
		else
		{
			$data['error']=lang('sales_error_editing_item');
		}
		
		if($this->sale_lib->is_kit_or_item($line) == 'item')
		{
			if($this->sale_lib->out_of_stock($this->sale_lib->get_item_id($line)))
			{
				$data['warning'] = lang('sales_quantity_less_than_zero');
			}
		}
		elseif($this->sale_lib->is_kit_or_item($line) == 'kit')
		{
		    if($this->sale_lib->out_of_stock_kit($this->sale_lib->get_kit_id($line)))
		    {
			    $data['warning'] = lang('sales_quantity_less_than_zero');
		    }
		}

		$this->_reload($data);
	}

	/*function delete_item($item_number)
	{
		$this->sale_lib->delete_item($item_number);
		$this->_reload();
	}*/
	/*function delete_item($item_number)
	{
		$this->sale_ticket_lib->delete_item($item_number);
		$this->_reload();
	}*/

	/*function delete_customer()
	{
		$this->sale_lib->delete_customer();
		$this->_reload();
	}*/
	
	/*function start_cc_processing()
	{
		$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/hcws/hcservice.asmx?WSDL': 'https://hc.mercurypay.com/hcws/hcservice.asmx?WSDL';
		$cc_amount = to_currency_no_money($this->sale_lib->get_payment_amount(lang('sales_credit')));
		$tax_amount = to_currency_no_money(($this->sale_lib->get_total() - $this->sale_lib->get_subtotal()) * ($cc_amount / $this->sale_lib->get_total()));
		$customer_id = $this->sale_lib->get_customer();
		$customer_name = '';
		if ($customer_id != -1)
		{
			$customer_info=$this->Customer->get_info($customer_id);
			$customer_name = $customer_info->first_name.' '.$customer_info->last_name;
		}
		
		if(!$this->sale_lib->get_use_saved_cc_info())
		{
			$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->Employee->get_logged_in_employee_info()->person_id), 0, 16);

			$parameters = array(
				'request' => array(
					'MerchantID' => $this->config->item('merchant_id'),
					'Password' => $this->config->item('merchant_password'),
					'TranType' => $cc_amount > 0 ? 'Sale' : 'Return',
					'TotalAmount' => abs($cc_amount),
					'PartialAuth' => 'On',
					'Frequency' => 'OneTime',
					'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->Employee->get_logged_in_employee_info()->person_id,
					'Invoice' => $invoice_number,
					'Memo' => 'PHP POS '.APPLICATION_VERSION,
					'TaxAmount' => abs($tax_amount),
					'CardHolderName' => $customer_name,
					'ProcessCompleteUrl' => site_url('sales/finish_cc_processing'),
					'ReturnUrl' => site_url('sales/cancel_cc_processing'),
				)
			);

			if (isset($customer_info) && $customer_info->zip)
			{
				$parameters['request']['AVSZip'] = $customer_info->zip;
			}

			$client = new SoapClient($service_url,array('trace' => TRUE));
			$result = $client->InitializePayment($parameters);
			$response_code = $result->InitializePaymentResult->ResponseCode;

			if ($response_code == 0)
			{
				$payment_id = $result->InitializePaymentResult->PaymentID;
				$hosted_checkout_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/CheckoutPOS.aspx' : 'https://hc.mercurypay.com/CheckoutPOS.aspx';
				$this->load->view('sales/hosted_checkout', array('payment_id' => $payment_id, 'hosted_checkout_url' =>$hosted_checkout_url ));
			}
			else
			{
				$this->_reload(array('error' => lang('sales_credit_card_processing_is_down')), false);
			}			
		}
		elseif($customer_info->cc_token) //We have saved credit card information, process it
		{
			$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/tws/transactionservice.asmx?WSDL': 'https://hc.mercurypay.com/tws/transactionservice.asmx?WSDL';
			$client = new SoapClient($service_url,array('trace' => TRUE));
			$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->Employee->get_logged_in_employee_info()->person_id), 0, 16);
			
			$parameters = array(
				'request' => array(
					'Token' => $customer_info->cc_token,
					'MerchantID' => $this->config->item('merchant_id'),
					'PurchaseAmount' => abs($cc_amount),
					'PartialAuth' => FALSE,
					'Frequency' => 'OneTime',
					'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->Employee->get_logged_in_employee_info()->person_id,
					'Invoice' => $invoice_number,
					'Memo' => 'PHP POS '.APPLICATION_VERSION,
					'TaxAmount' => abs($tax_amount),
					'CardHolderName' => $customer_name,
				),
				'password' => $this->config->item('merchant_password'),
			);

			if (isset($customer_info) && $customer_info->zip)
			{
				$parameters['request']['Zip'] = $customer_info->zip;
			}
			$result = $client->CreditSaleToken($parameters);
			
			$status = $result->CreditSaleTokenResult->Status;

			
			if ($status == 'Approved')	
			{
				$token =  $result->CreditSaleTokenResult->Token;
				$ref_no =  $result->CreditSaleTokenResult->RefNo;

				$person_info = array('person_id' => $this->sale_lib->get_customer());
				$customer_info = array('cc_token' => $token);
				$this->Customer->save($person_info,$customer_info,$this->sale_lib->get_customer());
				$this->session->set_flashdata('ref_no', $ref_no);
				redirect(site_url('sales/complete'));
			}
			else
			{
				//If we have failed, remove cc token and cc preview
				$person_info = array('person_id' => $this->sale_lib->get_customer());
				$customer_info = array('cc_token' => NULL, 'cc_preview' => NULL);
				$this->Customer->save($person_info,$customer_info,$this->sale_lib->get_customer());
				
				//Clear cc token for using saved cc info
				$this->sale_lib->clear_use_saved_cc_info();
				$this->_reload(array('error' => lang('sales_charging_card_failed_please_try_again')), false);
			}

		}
		
	}*/
	
	function finish_cc_processing()
	{
		$return_code = $this->input->get("ReturnCode");
		
		$service_url = (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'https://hc.mercurydev.net/hcws/hcservice.asmx?WSDL': 'https://hc.mercurypay.com/hcws/hcservice.asmx?WSDL';
		$parameters = array(
			'request' => array(
				'MerchantID' => $this->config->item('merchant_id'),
				'PaymentID' => $this->input->get('PaymentID'),
				'Password' => $this->config->item('merchant_password'),
			)
		);

		$client = new SoapClient($service_url,array('trace' => TRUE));
		$result = $client->VerifyPayment($parameters);
		$response_code = $result->VerifyPaymentResult->ResponseCode;
		$status = $result->VerifyPaymentResult->Status;
		$total_amount = $result->VerifyPaymentResult->Amount;
		$auth_amount = $result->VerifyPaymentResult->AuthAmount;
		
		$auth_code = $result->VerifyPaymentResult->AuthCode;
		$acq_ref_data = $result->VerifyPaymentResult->AcqRefData;
		$ref_no =  $result->VerifyPaymentResult->RefNo;
		$token =  $result->VerifyPaymentResult->Token;
		$masked_account = $result->VerifyPaymentResult->MaskedAccount;
		$process_data =  $result->VerifyPaymentResult->ProcessData;
				
		if ($response_code == 0 && $status == 'Approved')
		{
			$result = $client->AcknowledgePayment($parameters);
			$response_code = $result->AcknowledgePaymentResult;
			
			if ($response_code == 0 && $auth_amount == $total_amount)
			{
				$this->session->set_flashdata('ref_no', $ref_no);
				
				$info=$this->Customer->get_info($this->sale_lib->get_customer());
				
				//We want to save/update card:
				//1. User decides to save credit card info
				//2. We already have a saved credit and need to update
				if (($this->sale_lib->get_save_credit_card_info() or ($info->cc_token && $info->cc_preview)) && $this->sale_lib->get_customer() != -1)
				{
					$person_info = array('person_id' => $this->sale_lib->get_customer());
					$customer_info = array('cc_token' => $token, 'cc_preview' => $masked_account);
					$this->Customer->save($person_info,$customer_info,$this->sale_lib->get_customer());
				}
				redirect(site_url('sales/complete'));
			}
			elseif($response_code == 0 && $auth_amount < $total_amount)
			{
				$invoice_number = substr((date('mdy')).(time() - strtotime("today")).($this->Employee->get_logged_in_employee_info()->person_id), 0, 16);
				
				$partial_transaction = array(
					'AuthCode' => $auth_code,
					'Frequency' => 'OneTime',
					'Memo' => 'PHP POS '.APPLICATION_VERSION,
					'Invoice' => $invoice_number,
					'MerchantID' => $this->config->item('merchant_id'),
					'OperatorID' => (!defined("ENVIRONMENT") or ENVIRONMENT == 'development') ? 'test' : $this->Employee->get_logged_in_employee_info()->person_id,
					'PurchaseAmount' => $auth_amount,
					'RefNo' => $ref_no,
					'Token' => $token,
					'AcqRefData' =>$acq_ref_data,
					'ProcessData' => $process_data,
				);
				
				$this->sale_lib->delete_payment($this->sale_lib->get_payment_ids(lang('sales_credit')));
				$this->sale_lib->add_payment(lang('sales_partial_credit'), $auth_amount);
				$this->sale_lib->add_partial_transaction($partial_transaction);
				$this->_reload(array('warning' => lang('sales_credit_card_partially_charged_please_complete_sale_with_another_payment_method')), false);
			}
			else
			{
				$this->_reload(array('error' => lang('sales_acknowledge_payment_failed_please_contact_support')), false);
			}
		}
		else
		{
			$client->AcknowledgePayment($parameters);
			$this->_reload(array('error' => $result->VerifyPaymentResult->DisplayMessage), false);
		}		
	}
	
	function cancel_cc_processing()
	{
		$this->sale_lib->delete_payment($this->sale_lib->get_payment_ids(lang('sales_credit')));
		$this->_reload(array('error' => lang('sales_cc_processing_cancelled')), false);
	}
	
	/*function complete()
	{
		$data['is_sale'] = TRUE;
		$data['cart']=$this->sale_lib->get_cart();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=lang('sales_receipt');
		$customer_id=$this->sale_lib->get_customer();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$data['comment'] = $this->sale_lib->get_comment();
		$data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
		$emp_info=$this->Employee->get_info($employee_id);
		$data['payments']=$this->sale_lib->get_payments();
		$data['amount_change']=$this->sale_lib->get_amount_due_round() * -1;
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
		$data['ref_no'] = $this->session->flashdata('ref_no') ? $this->session->flashdata('ref_no') : '';
	
		$data['change_sale_date'] =$this->sale_lib->get_change_sale_date_enable() ?  $this->sale_lib->get_change_sale_date() : false;
		
		$old_date = $this->sale_lib->get_change_sale_id()  ? $this->Sale->get_info($this->sale_lib->get_change_sale_id())->row_array() : false;
		$old_date=  $old_date ? date(get_date_format().' '.get_time_format(), strtotime($old_date['sale_time'])) : date(get_date_format().' '.get_time_format());
		$data['transaction_time']= $this->sale_lib->get_change_sale_date_enable() ?  date(get_date_format().' '.get_time_format(), strtotime($this->sale_lib->get_change_sale_date())) : $old_date;
	
		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' ('.$cust_info->company_name.')');
		}
		
		$suspended_change_sale_id=$this->sale_lib->get_suspended_sale_id() ? $this->sale_lib->get_suspended_sale_id() : $this->sale_lib->get_change_sale_id() ;
		//SAVE sale to database
		$data['sale_id']='POS '.$this->Sale->save($data['cart'], $customer_id,$employee_id,$data['comment'],$data['show_comment_on_receipt'],$data['payments'], $suspended_change_sale_id, 0,$data['ref_no'],$data['change_sale_date']);
		if ($data['sale_id'] == 'POS -1')
		{
			$data['error_message'] = '';
			if (is_sale_integrated_cc_processing())
			{
				$data['error_message'].=lang('sales_credit_card_transaction_completed_successfully').'. ';
			}
			$data['error_message'] .= lang('sales_transaction_failed');
		}
		else
		{			
			if ($this->sale_lib->get_email_receipt() && !empty($cust_info->email))
			{
				$this->load->library('email');
				$config['mailtype'] = 'html';				
				$this->email->initialize($config);
				$this->email->from($this->config->item('email'), $this->config->item('company'));
				$this->email->to($cust_info->email); 

				$this->email->subject(lang('sales_receipt'));
				$this->email->message($this->load->view("sales/receipt_email",$data, true));	
				$this->email->send();
			}
		}
		$this->load->view("sales/receipt",$data);
		$this->sale_lib->clear_all();
	}*/
	
	function email_receipt($sale_id)
	{
		$sale_info = $this->Sale->get_info($sale_id)->row_array();
		$this->sale_lib->copy_entire_sale($sale_id);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes($sale_id);
		$data['total']=$this->sale_lib->get_total($sale_id);
		$data['receipt_title']=lang('sales_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();
		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$data['payment_type']=$sale_info['payment_type'];
		$data['amount_change']=$this->sale_lib->get_amount_due_round($sale_id) * -1;
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;
		$data['ref_no'] = $sale_info['cc_ref_no'];
		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' ('.$cust_info->company_name.')');
		}
		$data['sale_id']='POS '.$sale_id;
		if (!empty($cust_info->email))
		{
			$this->load->library('email');
			$config['mailtype'] = 'html';				
			$this->email->initialize($config);
			$this->email->from($this->config->item('email'), $this->config->item('company'));
			$this->email->to($cust_info->email); 

			$this->email->subject(lang('sales_receipt'));
			$this->email->message($this->load->view("sales/receipt_email",$data, true));	
			$this->email->send();
		}

		$this->sale_lib->clear_all();
	}
	
	/*function receipt($sale_id)
	{
		$data['is_sale'] = FALSE;
		$sale_info = $this->Sale->get_info($sale_id)->row_array();
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['show_payment_times'] = TRUE;
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes($sale_id);
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

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' ('.$cust_info->company_name.')');
		}
		$data['sale_id']='POS '.$sale_id;
		$this->load->view("sales/receipt",$data);
		$this->sale_lib->clear_all();

	}*/
	
	function edit($sale_id)
	{
		$data = array();

		$data['customers'] = array('' => 'No Customer');
		foreach ($this->Customer->get_all()->result() as $customer)
		{
			$data['customers'][$customer->person_id] = $customer->first_name . ' '. $customer->last_name;
		}

		$data['employees'] = array();
		foreach ($this->Employee->get_all()->result() as $employee)
		{
			$data['employees'][$employee->person_id] = $employee->first_name . ' '. $employee->last_name;
		}

		$data['sale_info'] = $this->Sale->get_info($sale_id)->row_array();
				
		
		$this->load->view('sales/edit', $data);
	}
	
	function delete($sale_id)
	{
		$data = array();
		
		if ($this->Sale->delete($sale_id))
		{
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		
		$this->load->view('sales/delete', $data);
		
	}
	
	function undelete($sale_id)
	{
		$data = array();
		
		if ($this->Sale->undelete($sale_id))
		{
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		
		$this->load->view('sales/undelete', $data);
		
	}
	
	function save($sale_id)
	{
		$sale_data = array(
			'sale_time' => date('Y-m-d', strtotime($this->input->post('date'))),
			'customer_id' => $this->input->post('customer_id') ? $this->input->post('customer_id') : null,
			'employee_id' => $this->input->post('employee_id'),
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
	
	function _payments_cover_total()
	{
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
	
	function reload()
	{
		$this->_reload();
	}
	
	function _reload($data=array(), $is_ajax = true)
	{
		$person_info = $this->Employee->get_logged_in_employee_info();
		$data['cart']=$this->sale_lib->get_cart();
		$data['modes']=array('sale'=>lang('sales_sale'),'return'=>lang('sales_return'));
		$data['mode']=$this->sale_lib->get_mode();
		$data['items_in_cart'] = $this->sale_lib->get_items_in_cart();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['items_module_allowed'] = $this->Employee->has_module_permission('items', $person_info->person_id);
		$data['comment'] = $this->sale_lib->get_comment();
		$data['show_comment_on_receipt'] = $this->sale_lib->get_comment_on_receipt();
		$data['email_receipt'] = $this->sale_lib->get_email_receipt();
		$data['payments_total']=$this->sale_lib->get_payments_totals();
		$data['amount_due']=$this->sale_lib->get_amount_due();
		$data['payments']=$this->sale_lib->get_payments();
		$data['change_sale_date_enable'] = $this->sale_lib->get_change_sale_date_enable();
		$data['change_sale_date'] = $this->sale_lib->get_change_sale_date();
		
		if ($this->config->item('enable_credit_card_processing'))
		{
			$data['payment_options']=array(
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_check') => lang('sales_check'),
				lang('sales_credit') => lang('sales_credit'),
				lang('sales_giftcard') => lang('sales_giftcard'));
				
		}
		else
		{
			$data['payment_options']=array(
				lang('sales_cash') => lang('sales_cash'),
				lang('sales_check') => lang('sales_check'),
				lang('sales_giftcard') => lang('sales_giftcard'),
				lang('sales_debit') => lang('sales_debit'),
				lang('sales_credit') => lang('sales_credit')
				);
				
		}
		
		foreach($this->Appconfig->get_additional_payment_types() as $additional_payment_type)
		{
			$data['payment_options'][$additional_payment_type] = $additional_payment_type;
		}

		$customer_id=$this->sale_lib->get_customer();
		if($customer_id!=-1)
		{
			$info=$this->Customer->get_info($customer_id);
			$data['customer']=$info->first_name.' '.$info->last_name.($info->company_name==''  ? '' :' ('.$info->company_name.')');
			$data['customer_email']=$info->email;
			$data['customer_id']=$customer_id;
			$data['customer_cc_token'] = $info->cc_token;
			$data['customer_cc_preview'] = $info->cc_preview;
			$data['save_credit_card_info'] = $this->sale_lib->get_save_credit_card_info();
			$data['use_saved_cc_info'] = $this->sale_lib->get_use_saved_cc_info();
		}
		$data['payments_cover_total'] = $this->_payments_cover_total();
		
		if ($is_ajax)
		{
			$this->load->view("sales/register",$data);
		}
		else
		{
			$this->load->view("sales/register_initial",$data);
		}
	}

    /*function cancel_sale()
    {
		if (!$this->_void_partial_transactions())
		{
			$this->_reload(array('error' => lang('sales_attempted_to_reverse_partial_transactions_failed_please_contact_support')), true);
		}
		
    	$this->sale_lib->clear_all();
    	$this->_reload();

    }*/

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
	
	/*function suspend()
	{
		$data['cart']=$this->sale_lib->get_cart();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=lang('sales_receipt');
		$data['transaction_time']= date(get_date_format().' '.get_time_format());
		$customer_id=$this->sale_lib->get_customer();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$comment = $this->sale_lib->get_comment();
		$comment = $this->sale_lib->get_comment();
		$show_comment_on_receipt = $this->sale_lib->get_comment_on_receipt();
		$emp_info=$this->Employee->get_info($employee_id);
		//Alain Multiple payments
		$data['payments']=$this->sale_lib->get_payments();
		$data['amount_change']=$this->sale_lib->get_amount_due() * -1;
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name.($cust_info->company_name==''  ? '' :' ('.$cust_info->company_name.')');
		}

		$total_payments = 0;

		foreach($data['payments'] as $payment)
		{
			$total_payments += $payment['payment_amount'];
		}
		
		$sale_id = $this->sale_lib->get_suspended_sale_id();
		//SAVE sale to database
		$data['sale_id']='POS '.$this->Sale->save($data['cart'], $customer_id,$employee_id,$comment,$show_comment_on_receipt,$data['payments'], $sale_id, 1);
		if ($data['sale_id'] == 'POS -1')
		{
			$data['error_message'] = lang('sales_transaction_failed');
		}
		$this->sale_lib->clear_all();
		$this->_reload(array('success' => lang('sales_successfully_suspended_sale')));
	}*/
	
	
	function new_giftcard()
	{
		$data = array();

		$data['item_id']=$this->Item->get_item_id(lang('sales_giftcard'));

		$this->load->view("sales/giftcard_form",$data);
	}
	
	/*function suspended()
	{
		$data = array();
		$data['suspended_sales'] = $this->Sale->get_all_suspended()->result_array();
		$this->load->view('sales/suspended', $data);
	}*/
	
	/*function change_sale($sale_id)
	{
		$this->check_action_permission('edit_sale');
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id);
		$this->sale_lib->set_change_sale_id($sale_id);
    	$this->_reload(array(), false);
	}*/
		
	/*function unsuspend()
	{
		$sale_id = $this->input->post('suspended_sale_id');
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_sale($sale_id);
		$this->sale_lib->set_suspended_sale_id($sale_id);
    	$this->_reload(array(), false);
	}*/
	
	/*function delete_suspended_sale()
	{
		$suspended_sale_id = $this->input->post('suspended_sale_id');
		if ($suspended_sale_id)
		{
			$this->sale_lib->delete_suspended_sale_id();
			$this->Sale->delete($suspended_sale_id);
		}
    	$this->_reload(array('success' => lang('sales_successfully_deleted')), false);
	}*/
}
?>