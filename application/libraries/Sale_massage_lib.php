<?php

class Sale_massage_lib {

	var $CI;

	function __construct() {
		$this->CI = & get_instance();
	}

	function get_cart() {
		if ($this->CI->session->userdata('cart') === false)
			$this->set_cart(array());

		return $this->CI->session->userdata('cart');
	}

	function set_cart($cart_data) {
		$this->CI->session->set_userdata('cart', $cart_data);
	}

	//Alain Multiple Payments
	function get_payments() {
		if ($this->CI->session->userdata('payments') === false)
			$this->set_payments(array());

		return $this->CI->session->userdata('payments');
	}

	//Alain Multiple Payments
	function set_payments($payments_data) {
		$this->CI->session->set_userdata('payments', $payments_data);
	}

	function get_change_sale_date() {
		return $this->CI->session->userdata('change_sale_date') ? $this->CI->session->userdata('change_sale_date') : '';
	}

	function clear_change_sale_date() {
		$this->CI->session->unset_userdata('change_sale_date');
	}

	function clear_change_sale_date_enable() {
		$this->CI->session->unset_userdata('change_sale_date_enable');
	}

	function set_change_sale_date_enable($change_sale_date_enable) {
		$this->CI->session->set_userdata('change_sale_date_enable', $change_sale_date_enable);
	}

	function get_change_sale_date_enable() {
		return $this->CI->session->userdata('change_sale_date_enable') ? $this->CI->session->userdata('change_sale_date_enable') : '';
	}

	function set_change_sale_date($change_sale_date) {
		$this->CI->session->set_userdata('change_sale_date', $change_sale_date);
	}

	function get_comment() {
		return $this->CI->session->userdata('comment') ? $this->CI->session->userdata('comment') : '';
	}

	function get_comment_on_receipt() {
		return $this->CI->session->userdata('show_comment_on_receipt') ? $this->CI->session->userdata('show_comment_on_receipt') : '';
	}

	function set_comment($comment) {
		$this->CI->session->set_userdata('comment', $comment);
	}

	function set_comment_on_receipt($comment_on_receipt) {
		$this->CI->session->set_userdata('show_comment_on_receipt', $comment_on_receipt);
	}

	function clear_comment() {
		$this->CI->session->unset_userdata('comment');
	}

	function clear_show_comment_on_receipt() {
		$this->CI->session->unset_userdata('show_comment_on_receipt');
	}

	function get_email_receipt() {
		return $this->CI->session->userdata('email_receipt');
	}

	function set_email_receipt($email_receipt) {
		$this->CI->session->set_userdata('email_receipt', $email_receipt);
	}

	function clear_email_receipt() {
		$this->CI->session->unset_userdata('email_receipt');
	}

	function get_save_credit_card_info() {
		return $this->CI->session->userdata('save_credit_card_info');
	}

	function set_save_credit_card_info($save_credit_card_info) {
		$this->CI->session->set_userdata('save_credit_card_info', $save_credit_card_info);
	}

	function clear_save_credit_card_info() {
		$this->CI->session->unset_userdata('save_credit_card_info');
	}

	function get_use_saved_cc_info() {
		return $this->CI->session->userdata('use_saved_cc_info');
	}

	function set_use_saved_cc_info($use_saved_cc_info) {
		$this->CI->session->set_userdata('use_saved_cc_info', $use_saved_cc_info);
	}

	function clear_use_saved_cc_info() {
		$this->CI->session->unset_userdata('use_saved_cc_info');
	}

	function get_partial_transactions() {
		return $this->CI->session->userdata('partial_transactions');
	}

	function add_partial_transaction($partial_transaction) {
		$partial_transactions = $this->CI->session->userdata('partial_transactions');
		$partial_transactions[] = $partial_transaction;
		$this->CI->session->set_userdata('partial_transactions', $partial_transactions);
	}

	function delete_partial_transactions() {
		$this->CI->session->unset_userdata('partial_transactions');
	}

	function add_payment($payment_type, $payment_amount, $payment_date = false) {
		$payments = $this->get_payments();
		$payment = array(
		    'payment_type' => $payment_type,
		    'payment_amount' => $payment_amount,
		    'payment_date' => $payment_date !== FALSE ? $payment_date : date('Y-m-d H:i:s')
		);

		$payments[] = $payment;
		$this->set_payments($payments);
		return true;
	}

	public function get_payment_ids($payment_type) {
		$payment_ids = array();

		$payments = $this->get_payments();
		for ($k = 0; $k < count($payments); $k++) {
			if ($payments[$k]['payment_type'] == $payment_type) {
				$payment_ids[] = $k;
			}
		}
		return $payment_ids;
	}

	public function get_payment_amount($payment_type) {
		$payment_amount = 0;
		if (($payment_ids = $this->get_payment_ids($payment_type)) !== FALSE) {
			$payments = $this->get_payments();

			foreach ($payment_ids as $payment_id) {
				$payment_amount += $payments[$payment_id]['payment_amount'];
			}
		}
		return $payment_amount;
	}

	//Alain Multiple Payments
	function delete_payment($payment_ids) {
		$payments = $this->get_payments();
		if (is_array($payment_ids)) {
			foreach ($payment_ids as $payment_id) {
				unset($payments[$payment_id]);
			}
		} else {
			unset($payments[$payment_ids]);
		}
		$this->set_payments(array_values($payments));
	}

	//Alain Multiple Payments
	function empty_payments() {
		$this->CI->session->unset_userdata('payments');
	}

	function get_payments_totals() {
		$subtotal = 0;
		foreach ($this->get_payments() as $payments) {
			$subtotal += $payments['payment_amount'];
		}
		return to_currency_no_money($subtotal);
	}

	//Alain Multiple Payments
	function get_amount_due($sale_id = false) {
		$amount_due = 0;
		$payment_total = $this->get_payments_totals();
		$sales_total = $this->get_total($sale_id);
		$amount_due = to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_amount_due_round($sale_id = false) {
		$amount_due = 0;
		$payment_total = $this->get_payments_totals();
		$sales_total = $this->CI->config->item('round_cash_on_sales') ? round_to_nearest_05($this->get_total($sale_id)) : $this->get_total($sale_id);
		$amount_due = to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_customer() {
		if (!$this->CI->session->userdata('customer'))
                    echo'hello customer';
			$this->set_customer(-1);

		return $this->CI->session->userdata('customer');
	}

	function set_customer($customer_id) {
		$this->CI->session->set_userdata('customer', $customer_id);
	}

	function get_mode() {
		if (!$this->CI->session->userdata('sale_mode'))
			$this->set_mode('sale');

		return $this->CI->session->userdata('sale_mode');
	}

	function set_mode($mode) {
		$this->CI->session->set_userdata('sale_mode', $mode);
	}

           // Retrieve data of sale via id
	function copy_entire_sale($sale_id, $category)
	{
		$this->CI->sale_lib->empty_cart();
        $this->CI->sale_lib->delete_customer();
		foreach($this->CI->Sale_massage->get_sale_items($sale_id)->result() as $row)
		{      
            $this->add_item($row->item_massage_id,$row->quantity_purchased, $row->discount_percent,$row->sale_price, $row->massager_id, $row->commission_massager, $row->commission_receptionist);
		}
		foreach($this->CI->Sale->get_sale_payments($sale_id)->result() as $row)
		{
			$this->CI->sale_lib_sms->add_payment($row->payment_type,$row->payment_amount, $row->payment_date);
		}
               
		$this->CI->sale_lib->set_customer($this->CI->Sale->get_customer($sale_id)->customer_id);
		$this->CI->sale_lib_sms->set_time_in($this->CI->massage->get_time_in($sale_id));
		$this->CI->sale_lib_sms->set_time_out($this->CI->massage->get_time_out($sale_id));

		$this->CI->sale_lib->set_massager($this->CI->Sale->get_massager($sale_id)->employee_id);
		$this->CI->sale_lib->set_commissioner($this->CI->Sale->get_commissioner($sale_id)->commisioner_id);
        $this->CI->sale_lib->set_commissioner_price($this->CI->Sale->get_commission_price($sale_id));
		$this->CI->sale_lib->set_comment($this->CI->Sale->get_comment($sale_id));
		$this->CI->sale_lib->set_comment_on_receipt($this->CI->Sale->get_comment_on_receipt($sale_id));
               
	}
	
       
    
	function add_item($item_id, $quantity = 1, $discount = 0, $price = null, $massager_id = null, $commission_massager=null, $commission_receptionist=null, $description = null, $serialnumber = null) {
               
		//make sure item exists
		if (!$this->CI->massage_item->exists(is_numeric($item_id) ? (int) $item_id : -1)) {
			//try to get item id given an item_number
			$item_id = $this->CI->massage_item->get_item_id($item_id);

			if (!$item_id)
				return false;
		}
		else {
			$item_id = (int) $item_id;
		}
		//Alain Serialization and Description
		//Get all items in the cart so far...
		$items = $this->get_cart();

		//We need to loop through all items in the cart.
		//If the item is already there, get it's key($updatekey).
		//We also need to get the next key that we are going to use in case we need to add the
		//item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

		$maxkey = 0;		       //Highest key so far
		$itemalreadyinsale = FALSE;	//We did not find the item yet.
		$insertkey = 0;		    //Key to use for new entry.
		$updatekey = 0;		    //Key to use to update(quantity)

		foreach ($items as $item) {
			//We primed the loop so maxkey is 0 the first time.
			//Also, we have stored the key in the element itself so we can compare.

			if ($maxkey <= $item['line']) {
				$maxkey = $item['line'];
			}

			if (isset($item['item_massage_id']) && $item['item_massage_id'] == $item_id) {
				$itemalreadyinsale = TRUE;
				$updatekey = $item['line'];

				if ($this->CI->massage_item->get_info($item_id)->massage_name == $items[$updatekey]['massage_name'] && $this->CI->massage_item->get_info($item_id)->massage_desc == lang('sales_giftcard')) {
					return false;
				}
			}
		}

		$insertkey = $maxkey + 1;

		$today = strtotime(date('Y-m-d'));
		// $price_to_use=( isset($this->CI->Item->get_info($item_id)->start_date) && isset($this->CI->Item->get_info($item_id)->end_date) && isset($this->CI->Item->get_info($item_id)->promo_price)  && ( strtotime($this->CI->Item->get_info($item_id)->start_date) <= $today) && (strtotime($this->CI->Item->get_info($item_id)->end_date) >= $today) ?  $this->CI->Item->get_info($item_id)->promo_price :  $this->CI->Item->get_info($item_id)->unit_price);
		$price_to_use = $this->CI->massage_item->get_info($item_id)->price_one;


		// Check is out side employee massager
		/*$item_info = $this->CI->massage_item->get_info($item_id);
		$commission_massager = $item_info->commission_price_massager;

		$is_outside_staff = 0;
		if ($item['massager'] != NULL) {
		    $employee_info = $this->Employee->get_info($item['massager']);
		    $is_outside_staff = $employee_info->is_outside_staff;   
		}

		if ($is_outside_staff != 0) {
		    $commission_massager = $item_info->outside_staff_fee;
		}*/


		//array/cart records are identified by $insertkey and item_id is just another field.
		$item = array(($insertkey) =>
		    array(
			'item_massage_id' => $item_id,
			'line' => $insertkey,
			'name' => $this->CI->massage_item->get_info($item_id)->massage_name,
			'description' => $description != null ? $description : $this->CI->massage_item->get_info($item_id)->massage_desc,
			'quantity' => $quantity,
			'discount' => $discount,
			'price' => $price != null ? $price : $price_to_use,
			// 'commission_massager' => $this->CI->massage_item->get_info($item_id)->commission_price_massager,
			'commission_massager' => $commission_massager != null ? $commission_massager : $this->CI->massage_item->get_info($item_id)->commission_price_massager,
			'commission_receptionist' => $commission_receptionist != null ? $commission_receptionist : $this->CI->massage_item->get_info($item_id)->commission_price_receptionist,
			'massager' => $massager_id,
		    )
		);

		//Item already exists and is not serialized, add to quantity
		if ($itemalreadyinsale) {
			//$items[$updatekey]['quantity'] += $quantity;
			$items += $item;
		} else {
			//add to existing array
			$items += $item;
		}

		$this->set_cart($items);
		return true;
	}

	// Chhing edition doing *
	function add_item_kit($external_item_kit_id_or_item_number, $quantity = 1, $discount = 0, $price = null, $description = null) {
		if (strpos($external_item_kit_id_or_item_number, 'KIT') !== FALSE) {
			//KIT #
			$pieces = explode(' ', $external_item_kit_id_or_item_number);
			$item_kit_id = (int) $pieces[1];
		} else {
			$item_kit_id = $this->CI->massage_kit->get_item_kit_id($external_item_kit_id_or_item_number);
		}

		//make sure item exists
		if (!$this->CI->massage_kit->exists($item_kit_id)) {
			return false;
		}

		if ($this->CI->massage_kit->get_info($item_kit_id)->unit_price == null) {
			foreach ($this->CI->item_kit_massage->get_info($item_kit_id) as $item_kit_item) {
				for ($k = 0; $k < $item_kit_item->quantity; $k++) {
					$this->add_item($item_kit_item->item_kit_id, $quantity);
				}
			}

			return true;
		} else {
			$items = $this->get_cart();

			//We need to loop through all items in the cart.
			//If the item is already there, get it's key($updatekey).
			//We also need to get the next key that we are going to use in case we need to add the
			//item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

			$maxkey = 0;		       //Highest key so far
			$itemalreadyinsale = FALSE;	//We did not find the item yet.
			$insertkey = 0;		    //Key to use for new entry.
			$updatekey = 0;		    //Key to use to update(quantity)


			foreach ($items as $item) {
				//We primed the loop so maxkey is 0 the first time.
				//Also, we have stored the key in the element itself so we can compare.

				if ($maxkey <= $item['line']) {
					$maxkey = $item['line'];
				}

				if (isset($item['item_kit_id']) && $item['item_kit_id'] == $item_kit_id) {
					$itemalreadyinsale = TRUE;
					$updatekey = $item['line'];
				}
			}

			$insertkey = $maxkey + 1;


// Check again
			//array/cart records are identified by $insertkey and item_id is just another field.
			$item = array(($insertkey) =>
			    array(
				'item_kit_id' => $item_kit_id,
				'line' => $insertkey,
				'item_kit_number' => $this->CI->massage_kit->get_info($item_kit_id)->item_kit_number,
				'name' => $this->CI->massage_kit->get_info($item_kit_id)->name,
				'description' => $description != null ? $description : $this->CI->massage_kit->get_info($item_kit_id)->description,
				'quantity' => $quantity,
				'discount' => $discount,
				'price' => $price != null ? $price : $this->CI->massage_kit->get_info($item_kit_id)->unit_price
			    )
			);

			//Item already exists and is not serialized, add to quantity
			if ($itemalreadyinsale) {
				$items[$updatekey]['quantity'] += $quantity;
			} else {
				//add to existing array
				$items += $item;
			}

			$this->set_cart($items);

			return true;
		}
	}

	// this function below maybe not use for ordering massage
	function out_of_stock($item_id) {
		//make sure item exists
		if (!$this->CI->massage_item->exists($item_id)) {
			//try to get item id given an item_number
			$item_id = $this->CI->massage_item->get_item_id($item_id);

			if (!$item_id)
				return false;
		}

		$item = $this->CI->massage_item->get_info($item_id);
		$quanity_added = $this->get_quantity_already_added($item_id);

		if ($item->quantity - $quanity_added < 0) {
			return true;
		}

		return false;
	}

	// Check out of stock of kit item *
	function out_of_stock_kit($kit_id) {
		//Make sure Item kit exist
		if (!$this->CI->massage_kit->exists($kit_id))
			return FALSE;

		//Get All Items for Kit
		$kit_items = $this->CI->massage_kit->get_info_item_kits($kit_id);

		//Check each item
		// Out of stock
		foreach ($kit_items as $itm) {
			$item = $this->CI->massage_item->get_info($itm->item_massage_id);
			// var_dump($item);
			$item_already_added = $this->get_quantity_already_added($itm->item_massage_id);
// echo $item_already_added;
			// echo $item->quantity;
			if ($item->quantity - $item_already_added < 0) {
				// echo 'true';
				return true;
			}
		}
		// echo 'false';
		return false;
	}

	function get_quantity_already_added($item_id) {
		// echo $item_id.' id';
		$items = $this->get_cart();
		$quanity_already_added = 0;
		foreach ($items as $item) {
			if (isset($item['ticket_id']) && $item['ticket_id'] == $item_id) {
				$quanity_already_added += $item['quantity'];
			}
		}

		//Check Item Kist for this item
		// $all_kits = $this->CI->item_kit_ticket->get_kits_have_item($item_id);
		$all_kits = $this->CI->massage_kit->get_kits_have_item($item_id);

		foreach ($all_kits as $kits) {
			$kit_quantity = $this->get_kit_quantity_already_added($kits['item_kit_id']);
			if ($kit_quantity > 0) {
				$quanity_already_added += ($kit_quantity * $kits['quantity']);
			}
		}
		return $quanity_already_added;
	}

	function get_kit_quantity_already_added($kit_id) {
		$items = $this->get_cart();
		$quanity_already_added = 0;
		foreach ($items as $item) {
			if (isset($item['item_kit_id']) && $item['item_kit_id'] == $kit_id) {
				$quanity_already_added+=$item['quantity'];
			}
		}
		return $quanity_already_added;
	}

	function get_item_id($line_to_get) {
		$items = $this->get_cart();

		foreach ($items as $line => $item) {
			if ($line == $line_to_get) {
				return isset($item['item_massage_id']) ? $item['item_massage_id'] : -1;
			}
		}

		return -1;
	}

	function get_kit_id($line_to_get) {
		$items = $this->get_cart();

		foreach ($items as $line => $item) {
			if ($line == $line_to_get) {
				return isset($item['item_kit_id']) ? $item['item_kit_id'] : -1;
			}
		}
		return -1;
	}

	function is_kit_or_item($line_to_get) {
		$items = $this->get_cart();
		foreach ($items as $line => $item) {
			if ($line == $line_to_get) {
				if (isset($item['item_massage_id'])) {
					return 'item';
				} elseif ($item['item_kit_id']) {
					return 'kit';
				}
			}
		}
		return -1;
	}

	function edit_item($line, $description, $serialnumber, $quantity, $discount, $price) {
		$items = $this->get_cart();
		if (isset($items[$line])) {
			$items[$line]['description'] = $description;
			$items[$line]['quantity'] = $quantity;
			$items[$line]['discount'] = $discount;
			$items[$line]['price'] = $price;
			$this->set_cart($items);
		}

		return false;
	}

	function is_valid_receipt($receipt_sale_id) {
		//POS #
		$pieces = explode(' ', $receipt_sale_id);

		if (count($pieces) == 2 && $pieces[0] == 'POS') {
			return $this->CI->Sale_massage->exists($pieces[1]);
		}

		return false;
	}

	function is_valid_item_kit($item_kit_id) {
		//KIT #
		$pieces = explode(' ', $item_kit_id);

		if (count($pieces) == 2 && $pieces[0] == 'KIT') {
			return $this->CI->massage_kit->exists($pieces[1]);
		}
		/* else
		  {
		  // $testing = $this->CI->ticket_kit->get_item_kit_id($item_kit_id) !== FALSE;
		  // echo '$testing';
		  // var_dump($testing);
		  return $this->CI->ticket_kit->get_item_kit_id($item_kit_id) !== FALSE;
		  } */
		// chhingchhing
		return false;
	}

	function get_valid_item_kit_id($item_kit_id) {
		//KIT #
		$pieces = explode(' ', $item_kit_id);

		if (count($pieces) == 2 && $pieces[0] == 'KIT') {
			return $pieces[1];
		} else {
			return $this->CI->massage_kit->get_item_kit_id($item_kit_id);
		}
	}
 
//	function return_entire_sale($receipt_sale_id) {
//		//POS #
//		$pieces = explode(' ', $receipt_sale_id);
//		$sale_id = $pieces[1];
//
//		$this->empty_cart();
//		$this->delete_customer();
//
//		foreach ($this->CI->
//		->get_sale_items($sale_id)->result() as $row) {
//			$this->add_item($row->item_kitID, -$row->quantity_purchased, $row->discount_percent, $row->item_kit_unit_price, $row->description, $row->serialnumber);
//			$this->add_item($row->item_massage_id, -$row->massage_name, $row->issue_date, $row->unit_price, $row->time_in, $row->time_out);
//		}
//		foreach ($this->CI->sale_massage->get_sale_item_kits($sale_id)->result() as $row) {
//			$this->add_item_kit('KIT ' . $row->item_kitID, -$row->quantity_purchased, $row->discount_percent, $row->item_kit_unit_price, $row->description);
//		}
//		$this->set_customer($this->CI->sale_massage->get_customer($sale_id)->customer_id);
//	}
        function return_entire_sale($receipt_sale_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_sale_id);
		$sale_id = $pieces[1];

		$this->CI->sale_lib_sms->empty_cart();
		$this->CI->sale_lib_sms->delete_customer();

		foreach($this->CI->Sale_massage->get_sale_items($sale_id)->result() as $row)
		{
			// $this->add_item($row->item_kitID,-$row->quantity_purchased,$row->discount_percent,$row->item_kit_unit_price,$row->description,$row->serialnumber);
			$this->add_item($row->item_massage_id,-$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description);
		}
//		foreach($this->CI->sale_ticket->get_sale_item_kits($sale_id)->result() as $row)
//		{
//			 
//		}
		$this->CI->sale_lib_sms->set_customer($this->CI->Sale_massage->get_customer($sale_id)->customer_id);
	}

//	function copy_entire_sale($sale_id) {
//		$this->empty_cart();
//		$this->delete_customer();
//
//		foreach ($this->CI->Sale->get_sale_items($sale_id)->result() as $row) {
//			$this->add_item($row->item_id, $row->quantity_purchased, $row->discount_percent, $row->item_unit_price, $row->description, $row->serialnumber);
//		}
//		foreach ($this->CI->Sale->get_sale_item_kits($sale_id)->result() as $row) {
//			$this->add_item_kit('KIT ' . $row->item_kit_id, $row->quantity_purchased, $row->discount_percent, $row->item_kit_unit_price, $row->description);
//		}
//		foreach ($this->CI->Sale->get_sale_payments($sale_id)->result() as $row) {
//			$this->add_payment($row->payment_type, $row->payment_amount, $row->payment_date);
//			;
//		}
//		$this->set_customer($this->CI->Sale->get_customer($sale_id)->person_id);
//		$this->set_comment($this->CI->Sale->get_comment($sale_id));
//		$this->set_comment_on_receipt($this->CI->Sale->get_comment_on_receipt($sale_id));
//	}

//	function delete_item($line) {
//		$items = $this->get_cart();
//		$item_id = $this->get_item_id($line);
//		if ($this->CI->Giftcard->get_giftcard_id($this->CI->Item->get_info($item_id)->description)) {
//			$this->CI->Giftcard->delete_completely($this->CI->Item->get_info($item_id)->description);
//		}
//		unset($items[$line]);
//		$this->set_cart($items);
//	}
        function delete_item($line)
	{
		$items=$this->CI->sale_lib_sms->get_cart();
		$item_id  = $this->get_item_id($line);
		if($this->CI->Giftcard->get_giftcard_id($this->CI->massage->get_info($item_id)->description))
		{
			$this->CI->Giftcard->delete_completely($this->CI->massage->get_info($item_id)->description);
		}
		unset($items[$line]);
		$this->CI->sale_lib_sms->set_cart($items);
	}

	function get_suspended_sale_id() {
		return $this->CI->session->userdata('suspended_sale_id');
	}

	function set_suspended_sale_id($suspended_sale_id) {
		$this->CI->session->set_userdata('suspended_sale_id', $suspended_sale_id);
	}

	function delete_suspended_sale_id() {
		$this->CI->session->unset_userdata('suspended_sale_id');
	}

	function get_change_sale_id() {
		return $this->CI->session->userdata('change_sale_id');
	}

	function set_change_sale_id($change_sale_id) {
		$this->CI->session->set_userdata('change_sale_id', $change_sale_id);
	}

	function delete_change_sale_id() {
		$this->CI->session->unset_userdata('change_sale_id');
	}

	function empty_cart() {
		$this->CI->session->unset_userdata('cart');
	}

	function delete_customer() {
		$this->CI->session->unset_userdata('customer');
	}

	function clear_mode() {
		$this->CI->session->unset_userdata('sale_mode');
	}

	function clear_all() {
		$this->clear_mode();
		$this->empty_cart();
		$this->clear_comment();
		$this->clear_show_comment_on_receipt();
		$this->clear_change_sale_date();
		$this->clear_change_sale_date_enable();
		$this->clear_email_receipt();
		$this->empty_payments();
		$this->delete_customer();
		$this->delete_suspended_sale_id();
		$this->delete_change_sale_id();
		$this->delete_partial_transactions();
		$this->clear_save_credit_card_info();
		$this->clear_use_saved_cc_info();
	}

	function get_taxes($sale_id = false) {

		$taxes = array();
		if ($sale_id) {
			$taxes_from_sale = array_merge($this->CI->Sale_massage->get_sale_items_taxes($sale_id), $this->CI->Sale_massage->get_sale_item_kits_taxes($sale_id));

			foreach ($taxes_from_sale as $key => $tax_item) {
				$name = $tax_item['percent'] . '% ' . $tax_item['name'];

				if ($tax_item['cumulative']) {
					$prev_tax = ($tax_item['price'] * $tax_item['quantity'] - $tax_item['price'] * $tax_item['quantity'] * $tax_item['discount'] / 100) * (($taxes_from_sale[$key - 1]['percent']) / 100);
					$tax_amount = (($tax_item['price'] * $tax_item['quantity'] - $tax_item['price'] * $tax_item['quantity'] * $tax_item['discount'] / 100) + $prev_tax) * (($tax_item['percent']) / 100);
				} else {
					$tax_amount = ($tax_item['price'] * $tax_item['quantity'] - $tax_item['price'] * $tax_item['quantity'] * $tax_item['discount'] / 100) * (($tax_item['percent']) / 100);
				}

				if (!isset($taxes[$name])) {
					$taxes[$name] = 0;
				}
				$taxes[$name] += $tax_amount;
			}
		} else {
			$customer_id = $this->CI->sale_lib_sms->get_customer();
			$customer = $this->CI->Customer->get_info($customer_id);
                      
			//Do not charge sales tax if we have a customer that is not taxable
			if (!$customer->taxable and $customer_id != -1) {
				return array();
			}

			foreach ($this->CI->sale_lib_sms->get_cart() as $line => $item) {
				$tax_info = isset($item['item_massage_id']) ? $this->CI->Item_taxes->get_info($item['item_massage_id']) : $this->CI->Item_kit_taxes->get_info($item['item_kit_id']);
				foreach ($tax_info as $key => $tax) {
					$name = $tax['percent'] . '% ' . $tax['name'];

					if ($tax['cumulative']) {
						$prev_tax = ($item['price'] * $item['quantity'] - $item['price'] * $item['quantity'] * $item['discount'] / 100) * (($tax_info[$key - 1]['percent']) / 100);
						$tax_amount = (($item['price'] * $item['quantity'] - $item['price'] * $item['quantity'] * $item['discount'] / 100) + $prev_tax) * (($tax['percent']) / 100);
					} else {
						$tax_amount = ($item['price'] * $item['quantity'] - $item['price'] * $item['quantity'] * $item['discount'] / 100) * (($tax['percent']) / 100);
					}

					if (!isset($taxes[$name])) {
						$taxes[$name] = 0;
					}
					$taxes[$name] += $tax_amount;
				}
			}
		}
//        var_dump($taxes);
		return $taxes;
	}

	function get_items_in_cart() {
		$items_in_cart = 0;
		foreach ($this->get_cart() as $item) {
			$items_in_cart += $item['quantity'];
		}

		return $items_in_cart;
	}

	function get_subtotal() {
		$subtotal = 0;
		foreach ($this->get_cart() as $item) {
			$subtotal+=($item['price'] * $item['quantity'] - $item['discount']);
		}
		return to_currency_no_money($subtotal);
	}

	function get_total($sale_id = false) {
		$total = 0;
		foreach ($this->get_cart() as $item) {
			$total+=($item['price'] * $item['quantity'] - $item['discount']);
		}

		// foreach ($this->get_taxes($sale_id) as $tax) {
		// 	$total+=$tax;
		// }

		return to_currency_no_money($total);
	}

}

?>