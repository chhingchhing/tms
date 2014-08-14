<?php
class Sale_lib
{
	var $CI;

  	function __construct()
	{
		$this->CI =& get_instance();
	}

	function get_cart()
	{
		if($this->CI->session->userdata('cart') === false)
			$this->set_cart(array());
                    
		return $this->CI->session->userdata('cart');
	}

	function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('cart',$cart_data);
	}

	//Alain Multiple Payments
	function get_payments()
	{
		if($this->CI->session->userdata('payments') === false)
			$this->set_payments(array());

		return $this->CI->session->userdata('payments');
	}

	//Alain Multiple Payments
	function set_payments($payments_data)
	{
		$this->CI->session->set_userdata('payments',$payments_data);
	}
	
	function get_change_sale_date() 
	{
		return $this->CI->session->userdata('change_sale_date') ? $this->CI->session->userdata('change_sale_date') : '';
	}
	function clear_change_sale_date() 	
	{
		$this->CI->session->unset_userdata('change_sale_date');
		
	}
	function clear_change_sale_date_enable() 	
	{
		$this->CI->session->unset_userdata('change_sale_date_enable');
	}
	function set_change_sale_date_enable($change_sale_date_enable)
	{
		$this->CI->session->set_userdata('change_sale_date_enable',$change_sale_date_enable);
	}
	
	function get_change_sale_date_enable() 
	{
		return $this->CI->session->userdata('change_sale_date_enable') ? $this->CI->session->userdata('change_sale_date_enable') : '';
	}
	
	function set_change_sale_date($change_sale_date)
	{
		$this->CI->session->set_userdata('change_sale_date',$change_sale_date);
	}
	
	function get_comment() 
	{
		return $this->CI->session->userdata('comment') ? $this->CI->session->userdata('comment') : '';
	}

	function get_comment_on_receipt() 
	{
		return $this->CI->session->userdata('show_comment_on_receipt') ? $this->CI->session->userdata('show_comment_on_receipt') : '';
	}

	function set_comment($comment) 
	{
		$this->CI->session->set_userdata('comment', $comment);
	}
	
	function set_comment_on_receipt($comment_on_receipt) 
	{
		$this->CI->session->set_userdata('show_comment_on_receipt', $comment_on_receipt);
	}

	function clear_comment() 	
	{
		$this->CI->session->unset_userdata('comment');
		
	}
	
	function clear_show_comment_on_receipt() 	
	{
		$this->CI->session->unset_userdata('show_comment_on_receipt');
		
	}
	
	function get_email_receipt() 
	{
		return $this->CI->session->userdata('email_receipt');
	}

	function set_email_receipt($email_receipt) 
	{
		$this->CI->session->set_userdata('email_receipt', $email_receipt);
	}

	function clear_email_receipt() 	
	{
		$this->CI->session->unset_userdata('email_receipt');
	}
	
	function get_save_credit_card_info() 
	{
		return $this->CI->session->userdata('save_credit_card_info');
	}

	function set_save_credit_card_info($save_credit_card_info) 
	{
		$this->CI->session->set_userdata('save_credit_card_info', $save_credit_card_info);
	}

	function clear_save_credit_card_info() 	
	{
		$this->CI->session->unset_userdata('save_credit_card_info');
	}
	
	function get_use_saved_cc_info() 
	{
		return $this->CI->session->userdata('use_saved_cc_info');
	}

	function set_use_saved_cc_info($use_saved_cc_info) 
	{
		$this->CI->session->set_userdata('use_saved_cc_info', $use_saved_cc_info);
	}

	function clear_use_saved_cc_info() 	
	{
		$this->CI->session->unset_userdata('use_saved_cc_info');
	}
	
	function get_partial_transactions()
	{
		return $this->CI->session->userdata('partial_transactions');
	}
	
	function add_partial_transaction($partial_transaction)
	{
		$partial_transactions = $this->CI->session->userdata('partial_transactions');
		$partial_transactions[] = $partial_transaction;
		$this->CI->session->set_userdata('partial_transactions', $partial_transactions);
	}
	
	function delete_partial_transactions()
	{
		$this->CI->session->unset_userdata('partial_transactions');
	}

	function add_payment($payment_type,$payment_amount,$payment_date = false)
	{
			$payments=$this->get_payments();
			$payment = array(
				'payment_type'=>$payment_type,
				'payment_amount'=>$payment_amount,
				'payment_date' => $payment_date !== FALSE ? $payment_date : date('Y-m-d H:i:s')
			);
			
			$payments[]=$payment;
			$this->set_payments($payments);
			return true;
	}
	
	public function get_payment_ids($payment_type)
	{
		$payment_ids = array();
		
		$payments=$this->get_payments();
		
		for($k=0;$k<count($payments);$k++)
		{
			if ($payments[$k]['payment_type'] == $payment_type)
			{
				$payment_ids[] = $k;
			}
		}
		
		return $payment_ids;
	}
	
	public function get_payment_amount($payment_type)
	{
		$payment_amount = 0;
		if (($payment_ids = $this->get_payment_ids($payment_type)) !== FALSE)
		{
			$payments=$this->get_payments();
			
			foreach($payment_ids as $payment_id)
			{
				$payment_amount += $payments[$payment_id]['payment_amount'];
			}
		}
		
		return $payment_amount;
	}
	
	//Alain Multiple Payments
	function delete_payment($payment_ids)
	{
		$payments=$this->get_payments();
		if (is_array($payment_ids))
		{
			foreach($payment_ids as $payment_id)
			{
				unset($payments[$payment_id]);
			}
		}
		else
		{
			unset($payments[$payment_ids]);			
		}
		$this->set_payments(array_values($payments));
	}
	
	//Alain Multiple Payments
	function empty_payments()
	{
		$this->CI->session->unset_userdata('payments');
	}

	function get_payments_totals()
	{
		$subtotal = 0;
		foreach($this->get_payments() as $payments)
		{

		    	$subtotal+=$payments['payment_amount'];

		}

		return to_currency_no_money($subtotal);
	}

	//Alain Multiple Payments
	function get_amount_due($sale_id = false)
	{
		$amount_due=0;
		$payment_total = $this->get_payments_totals();
		$sales_total=$this->get_total($sale_id);
		$amount_due=to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_amount_due_round($sale_id = false)
	{
		$amount_due=0;
		$payment_total = $this->get_payments_totals();
		$sales_total= $this->CI->config->item('round_cash_on_sales') ?  round_to_nearest_05($this->get_total($sale_id)) : $this->get_total($sale_id);
		$amount_due=to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_customer()
	{
		if(!$this->CI->session->userdata('customer'))
			$this->set_customer(-1);

		return $this->CI->session->userdata('customer');
	}

	function set_customer($customer_id)
	{
		$this->CI->session->set_userdata('customer',$customer_id);
	}

	function get_seat_no()
	{
		if(!$this->CI->session->userdata('array_seat_no'))
			$this->set_seat_no(NULL);

		return $this->CI->session->userdata('array_seat_no');
	}

	function set_seat_no($array_seat_no)
	{
		$this->CI->session->set_userdata('array_seat_no',$array_seat_no);
	}

	function empty_seat_no() {
		$this->CI->session->unset_userdata('array_seat_no');
	}


	function get_item_number()
	{
		if(!$this->CI->session->userdata('array_item_number'))
			$this->set_item_number(NULL);

		return $this->CI->session->userdata('array_item_number');
	}

	function set_item_number($array_item_number)
	{
		$this->CI->session->set_userdata('array_item_number',$array_item_number);
	}

	function empty_item_number() {
		$this->CI->session->unset_userdata('array_item_number');
	}

	function get_item_vol()
	{
		if(!$this->CI->session->userdata('array_item_vol'))
			$this->set_item_vol(NULL);

		return $this->CI->session->userdata('array_item_vol');
	}

	function set_item_vol($array_item_vol)
	{
		$this->CI->session->set_userdata('array_item_vol',$array_item_vol);
	}

	function empty_item_vol() {
		$this->CI->session->unset_userdata('array_item_vol');
	}

	function get_date_departures()
	{
		if(!$this->CI->session->userdata('array_date_departure'))
			$this->set_date_departures('');

		return $this->CI->session->userdata('array_date_departure');
	}

	function set_date_departures($array_date_departure)
	{
		$this->CI->session->set_userdata('array_date_departure',$array_date_departure);
	}

	function empty_date_departures() {
		$this->CI->session->unset_userdata('array_date_departure');
	}    

	function get_return_dates()
	{
		if(!$this->CI->session->userdata('array_return_date'))
                    $this->set_return_dates('');
		return $this->CI->session->userdata('array_return_date');
	}

	function set_return_dates($array_return_date)
	
	{
		$this->CI->session->set_userdata('array_return_date',$array_return_date);
	}

	function empty_return_dates() {
		$this->CI->session->unset_userdata('array_return_date');
	}
        
        function get_rent_dates()
	{
		if(!$this->CI->session->userdata('array_rent_date'))
			$this->set_rent_dates(''); 
		return $this->CI->session->userdata('array_rent_date');
	}

	function set_rent_dates($array_rent_date)
	{
		$this->CI->session->set_userdata('array_rent_date',$array_rent_date);
	}

	function empty_rent_dates() {
		$this->CI->session->unset_userdata('array_rent_date');
	}
        

	function get_times_departure()
	{
		if(!$this->CI->session->userdata('array_times_departure'))
			$this->set_times_departure('');

		return $this->CI->session->userdata('array_times_departure');
	}

	function set_times_departure($array_times_departure)
	{
		$this->CI->session->set_userdata('array_times_departure',$array_times_departure);
	}

	function empty_times_departure() {
		$this->CI->session->unset_userdata('array_times_departure');
	}

	function get_hotels()
	{
		if(!$this->CI->session->userdata('array_hotels'))
			$this->set_hotels('');

		return $this->CI->session->userdata('array_hotels');
	}

	function set_hotels($array_hotels)
	{
		$this->CI->session->set_userdata('array_hotels',$array_hotels);
	}

	function empty_hotels() {
		$this->CI->session->unset_userdata('array_hotels');
	}

	function get_room_numbers()
	{
		if(!$this->CI->session->userdata('array_room_numbers'))
			$this->set_room_numbers('');

		return $this->CI->session->userdata('array_room_numbers');
	}

	function set_room_numbers($array_room_numbers)
	{
		$this->CI->session->set_userdata('array_room_numbers',$array_room_numbers);
	}

	function empty_room_numbers() {
		$this->CI->session->unset_userdata('array_room_numbers');
	}

	function get_commissioner()
	{
		if(!$this->CI->session->userdata('commissioner'))
			$this->set_commissioner(-1);

		return $this->CI->session->userdata('commissioner');
	}

	function set_commissioner($commissioner_id)
	{
		$this->CI->session->set_userdata('commissioner',$commissioner_id);
	}

	function get_commissioner_price()
	{
		if(!$this->CI->session->userdata('commissioner_price'))
			$this->set_commissioner_price(0);

		return $this->CI->session->userdata('commissioner_price');
	}

	function set_commissioner_price($commissioner_price)
	{
		$this->CI->session->set_userdata('commissioner_price',$commissioner_price);
	}

	function empty_price_commissioner()
	{
		$this->CI->session->unset_userdata('commissioner_price');
	}

	function get_deposit_price()
	{
		if(!$this->CI->session->userdata('deposit_price'))
			$this->set_deposit_price(0);

		return $this->CI->session->userdata('deposit_price');
	}

	function set_deposit_price($deposit_price)
	{
		$this->CI->session->set_userdata('deposit_price',$deposit_price);
	}

	function empty_price_deposit()
	{
		$this->CI->session->unset_userdata('deposit_price');
	}

	/*Start*/
	function get_tip_price()
	{
		if(!$this->CI->session->userdata('tip_price'))
			$this->set_tip_price(1);

		return $this->CI->session->userdata('tip_price');
	}

	function set_tip_price($tip_price)
	{
		$this->CI->session->set_userdata('tip_price',$tip_price);
	}

	function empty_tip_price() {
		$this->CI->session->unset_userdata('tip_price');
	}
	/*End*/

	function get_time_departure()
	{
		if(!$this->CI->session->userdata('time_departure'))
			$this->set_time_departure('00:00');

		return $this->CI->session->userdata('time_departure');
	}

	function set_time_departure($time_departure)
	{
		$this->CI->session->set_userdata('time_departure',$time_departure);
	}


	function get_guide()
	{
		if(!$this->CI->session->userdata('guide'))
			$this->set_guide(-1);

		return $this->CI->session->userdata('guide');
	}

	function set_guide($guide_id)
	{
		$this->CI->session->set_userdata('guide',$guide_id);
	}

	function delete_guide()
	{
		$this->CI->session->unset_userdata('guide');
	}

	function get_mode()
	{
		if(!$this->CI->session->userdata('sale_mode'))
			$this->set_mode('sale');

		return $this->CI->session->userdata('sale_mode');
	}

	function set_mode($mode)
	{
		$this->CI->session->set_userdata('sale_mode',$mode);
	}

	function get_massager()
	{
		if(!$this->CI->session->userdata('massager'))
			$this->set_massager(-1);

		return $this->CI->session->userdata('massager');
	}

	function set_massager($person_id)
	{
		$this->CI->session->set_userdata('massager',$person_id);
	}

	function add_item($item_id,$quantity=1, $discount=0,$price=null,$description=null,$serialnumber=null)
	{
		//make sure item exists
		if(!$this->CI->Item->exists(is_numeric($item_id) ? (int)$item_id : -1))	
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}
		else
		{
			$item_id = (int)$item_id;
		}
		//Alain Serialization and Description

		//Get all items in the cart so far...
		$items = $this->get_cart();

        //We need to loop through all items in the cart.
        //If the item is already there, get it's key($updatekey).
        //We also need to get the next key that we are going to use in case we need to add the
        //item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

        $maxkey=0;                       //Highest key so far
        $itemalreadyinsale=FALSE;        //We did not find the item yet.
	$insertkey=0;                    //Key to use for new entry.
	$updatekey=0;                    //Key to use to update(quantity)

		foreach ($items as $item)
		{
            //We primed the loop so maxkey is 0 the first time.
            //Also, we have stored the key in the element itself so we can compare.

			if($maxkey <= $item['line'])
			{
				$maxkey = $item['line'];
			}

			if(isset($item['item_id']) && $item['item_id']==$item_id)
			{
				$itemalreadyinsale=TRUE;
				$updatekey=$item['line'];
				
				if($this->CI->Item->get_info($item_id)->description==$items[$updatekey]['description'] && $this->CI->Item->get_info($item_id)->name==lang('sales_giftcard'))
					{
						return false;
					}
			}
		}

	$insertkey=$maxkey+1;
        $today =  strtotime(date('Y-m-d'));
        $price_to_use=( isset($this->CI->Item->get_info($item_id)->start_date) && isset($this->CI->Item->get_info($item_id)->end_date) && isset($this->CI->Item->get_info($item_id)->promo_price)  && ( strtotime($this->CI->Item->get_info($item_id)->start_date) <= $today) && (strtotime($this->CI->Item->get_info($item_id)->end_date) >= $today) ?  $this->CI->Item->get_info($item_id)->promo_price :  $this->CI->Item->get_info($item_id)->unit_price);

		//array/cart records are identified by $insertkey and item_id is just another field.
		$item = array(($insertkey)=>
		array(
			'item_id'=>$item_id,
			'line'=>$insertkey,
			'name'=>$this->CI->Item->get_info($item_id)->name,
			'item_number'=>$this->CI->Item->get_info($item_id)->item_number,
			'description'=>$description!=null ? $description: $this->CI->Item->get_info($item_id)->description,
			'serialnumber'=>$serialnumber!=null ? $serialnumber: '',
			'allow_alt_description'=>$this->CI->Item->get_info($item_id)->allow_alt_description,
			'is_serialized'=>$this->CI->Item->get_info($item_id)->is_serialized,
			'quantity'=>$quantity,
                        'discount'=>$discount,
			'price'=>$price!=null ? $price:$price_to_use
			)
		);

		//Item already exists and is not serialized, add to quantity
		if($itemalreadyinsale && ($this->CI->Item->get_info($item_id)->is_serialized ==0) )
		{
			$items[$updatekey]['quantity']+=$quantity;
		}
		else
		{
			//add to existing array
			$items+=$item;
		}
                
                var_dump('sale_lib ->edit_item = '.($this->set_cart($items)));
                var_dump('sale_lib -> edit_item ->$items= '.$items);
		$this->set_cart($items);
		return true;
	}
	
	function add_item_kit($external_item_kit_id_or_item_number,$quantity=1,$discount=0,$price=null,$description=null)
	{
		if (strpos($external_item_kit_id_or_item_number, 'KIT') !== FALSE)
		{
			//KIT #
			$pieces = explode(' ',$external_item_kit_id_or_item_number);
			$item_kit_id = (int)$pieces[1];	
		}
		else
		{
			$item_kit_id = $this->CI->Item_kit->get_item_kit_id($external_item_kit_id_or_item_number);
		}
		
		//make sure item exists
		if(!$this->CI->Item_kit->exists($item_kit_id))	
		{
			return false;
		}
		
		if ( $this->CI->Item_kit->get_info($item_kit_id)->unit_price == null)
		{
			foreach ($this->CI->Item_kit_items->get_info($item_kit_id) as $item_kit_item)
			{
				for($k=0;$k<$item_kit_item->quantity;$k++)
				{
					$this->add_item($item_kit_item->item_id, $quantity);
				}
			}
			
			return true;
		}
		else
		{
			$items = $this->get_cart();

	        //We need to loop through all items in the cart.
	        //If the item is already there, get it's key($updatekey).
	        //We also need to get the next key that we are going to use in case we need to add the
	        //item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

	        $maxkey=0;                       //Highest key so far
	        $itemalreadyinsale=FALSE;        //We did not find the item yet.
			$insertkey=0;                    //Key to use for new entry.
			$updatekey=0;                    //Key to use to update(quantity)

			foreach ($items as $item)
			{
	            //We primed the loop so maxkey is 0 the first time.
	            //Also, we have stored the key in the element itself so we can compare.

				if($maxkey <= $item['line'])
				{
					$maxkey = $item['line'];
				}

				if(isset($item['item_kit_id']) && $item['item_kit_id']==$item_kit_id)
				{
					$itemalreadyinsale=TRUE;
					$updatekey=$item['line'];
				}
			}

			$insertkey=$maxkey+1;
			
			//array/cart records are identified by $insertkey and item_id is just another field.
			$item = array(($insertkey)=>
			array(
				'item_kit_id'=>$item_kit_id,
				'line'=>$insertkey,
				'item_kit_number'=>$this->CI->Item_kit->get_info($item_kit_id)->item_kit_number,
				'name'=>$this->CI->Item_kit->get_info($item_kit_id)->name,
				'description'=>$description!=null ? $description: $this->CI->Item_kit->get_info($item_kit_id)->description,
				'quantity'=>$quantity,
                                'discount'=>$discount,
				'price'=>$price!=null ? $price: $this->CI->Item_kit->get_info($item_kit_id)->unit_price
				)
			);

			//Item already exists and is not serialized, add to quantity
			if($itemalreadyinsale)
			{
				$items[$updatekey]['quantity']+=$quantity;
			}
			else
			{
				//add to existing array
				$items+=$item;
			}

			$this->set_cart($items);
			return true;
		}
	}

	function get_quantity_already_added($item_id)
	{
		$items = $this->get_cart();
		$quanity_already_added = 0;
		foreach ($items as $item)
		{
			if(isset($item['item_id']) && $item['item_id']==$item_id)
			{
				$quanity_already_added+=$item['quantity'];
			}
		}
		
		//Check Item Kist for this item
		$all_kits = $this->CI->Item_kit_items->get_kits_have_item($item_id);

		foreach($all_kits as $kits)
		{
		    $kit_quantity = $this->get_kit_quantity_already_added($kits['item_kit_id']);
		    if($kit_quantity > 0)
		    {
				$quanity_already_added += ($kit_quantity * $kits['quantity']);
		    }
		}
		return $quanity_already_added;
	}
	
	function get_kit_quantity_already_added($kit_id)
	{
	    $items = $this->get_cart();
	    $quanity_already_added = 0;
	    foreach ($items as $item)
	    {
		    if(isset($item['item_kit_id']) && $item['item_kit_id']==$kit_id)
		    {
				$quanity_already_added+=$item['quantity'];
		    }
	    }

	    return $quanity_already_added;
	}

	function get_kit_id($line_to_get)
	{
	    $items = $this->get_cart();

	    foreach ($items as $line=>$item)
	    {
		if($line==$line_to_get)
		{
		    return isset($item['item_kit_id']) ? $item['item_kit_id'] : -1;
		}
	    }
	    return -1;
	}
        
    function edit_item($line,$description,$serialnumber,$quantity,$discount,$price,$massager=false, $item_id=false, $commission_massager=NULL, $commission_receptionist=NULL)
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{
			if ($massager == false) {
				$items[$line]['description'] = $description;
				$items[$line]['serialnumber'] = $serialnumber;
				$items[$line]['quantity'] = $quantity;
				$items[$line]['discount'] = $discount;
				$items[$line]['price'] = $price;
			} else {
				$is_outside_staff = 0;
				if ($massager == "") {
			        $massager == null;
			    } else {
			    	$item_info = $this->CI->massage_item->get_info($items[$line]['item_massage_id']);
					$commission_massager = $item_info->commission_price_massager;
			
					if ($massager != NULL) {
					    $employee_info = $this->CI->Employee->get_info($massager);
					    $is_outside_staff = $employee_info->is_outside_staff;   
					}

					if ($is_outside_staff != 0) {
					    $commission_massager = $item_info->outside_staff_fee;
					}
			    }

				$items[$line]['description'] = $description;
				$items[$line]['serialnumber'] = $serialnumber;
				$items[$line]['quantity'] = $quantity;
				$items[$line]['discount'] = $discount;
				$items[$line]['price'] = $price;
				$items[$line]['massager'] = $massager;
				$items[$line]['commission_massager'] = $commission_massager;
				$items[$line]['commission_receptionist'] = $commission_receptionist;
			}
                        
			$this->set_cart($items);          
		}

		return false;
	}
        
	// Check record of item kit with category
	function is_valid_item_kit($item_kit_id, $category)
	{
		//KIT #
		$pieces = explode(' ',$item_kit_id);

		if(count($pieces)==2 && $pieces[0] == 'KIT')
		{
			return $this->CI->Item_kit->exists($pieces[1], $category);
		}
		else
		{
			return $this->CI->Item_kit->get_item_kit_id($item_kit_id, $category) !== FALSE;
		}
	}

	function get_valid_item_kit_id($item_kit_id, $category)
	{
		//KIT #
		$pieces = explode(' ',$item_kit_id);

		if(count($pieces)==2 && $pieces[0] == 'KIT')
		{
			return $pieces[1];
		}
		else
		{
			return $this->CI->Item_kit->get_item_kit_id($item_kit_id, $category);
		}
	}

	function get_suspended_sale_id()
	{
		return $this->CI->session->userdata('suspended_sale_id');
	}
	
	function set_suspended_sale_id($suspended_sale_id)
	{
		$this->CI->session->set_userdata('suspended_sale_id',$suspended_sale_id);
	}
	
	function delete_suspended_sale_id()
	{
		$this->CI->session->unset_userdata('suspended_sale_id');
	}
	
	function get_change_sale_id()
	{
		return $this->CI->session->userdata('change_sale_id');
	}
	
	function set_change_sale_id($change_sale_id)
	{
		$this->CI->session->set_userdata('change_sale_id',$change_sale_id);
	}
	
	function delete_change_sale_id()
	{
		$this->CI->session->unset_userdata('change_sale_id');
	}

	function empty_cart()
	{
		$this->CI->session->unset_userdata('cart');
	}

	function delete_customer()
	{
		$this->CI->session->unset_userdata('customer');
	}

	function delete_commissioner()
	{
		$this->CI->session->unset_userdata('commissioner');
	}

	function delete_massger()
	{
		$this->CI->session->unset_userdata('massager');
	}

	function empty_time_departure()
	{
		$this->CI->session->unset_userdata('time_departure');
	}

	function empty_time_in()
	{
		$this->CI->session->unset_userdata('time_in');
	}

	function empty_time_out()
	{
		$this->CI->session->unset_userdata('time_out');
	}

	function clear_mode()
	{
		$this->CI->session->unset_userdata('sale_mode');
	}

	function clear_all()
	{
		$this->clear_mode();
		$this->empty_cart();
		$this->clear_comment();
		$this->clear_show_comment_on_receipt();
		$this->clear_change_sale_date();
		$this->clear_change_sale_date_enable();
		$this->clear_email_receipt();
		$this->empty_payments();
		$this->delete_customer();
		$this->delete_commissioner();
		$this->delete_guide();
		$this->empty_rent_dates();
		$this->empty_return_dates();
		$this->empty_time_departure();
		$this->empty_date_departures();
		$this->empty_times_departure();
		$this->empty_hotels();
		$this->empty_room_numbers();
		$this->empty_price_deposit();
		$this->empty_time_in();
		$this->empty_time_out();
		$this->empty_seat_no();
		$this->empty_item_vol();
		$this->empty_item_number();
		$this->empty_price_commissioner();
		$this->delete_suspended_sale_id();
		$this->delete_change_sale_id();
		$this->delete_partial_transactions();
		$this->clear_save_credit_card_info();
		$this->clear_use_saved_cc_info();
		$this->delete_massger();
		$this->empty_tip_price();
	}

	function get_taxes($sale_id = false)
	{
		$taxes = array();
		
		if ($sale_id)
		{
			$taxes_from_sale = array_merge($this->CI->Sale->get_sale_items_taxes($sale_id), $this->CI->Sale->get_sale_item_kits_taxes($sale_id));
			
			foreach($taxes_from_sale as $key=>$tax_item)
			{
				$name = $tax_item['percent'].'% ' . $tax_item['name'];
			
				if ($tax_item['cumulative'])
				{
					$prev_tax = ($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($taxes_from_sale[$key-1]['percent'])/100);
					$tax_amount=(($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100) + $prev_tax)*(($tax_item['percent'])/100);					
				}
				else
				{
					$tax_amount=($tax_item['price']*$tax_item['quantity']-$tax_item['price']*$tax_item['quantity']*$tax_item['discount']/100)*(($tax_item['percent'])/100);
				}

				if (!isset($taxes[$name]))
				{
					$taxes[$name] = 0;
				}
				$taxes[$name] += $tax_amount;
			}
		}
		else
		{
			$customer_id = $this->get_customer();
			$customer = $this->CI->Customer->get_info($customer_id);

			//Do not charge sales tax if we have a customer that is not taxable
			if (!$customer->taxable and $customer_id!=-1)
			{
			   return array();
			}

			foreach($this->get_cart() as $line=>$item)
			{
				$tax_info = isset($item['item_id']) ? $this->CI->Item_taxes->get_info($item['item_id']) : $this->CI->Item_kit_taxes->get_info($item['item_kit_id']);
				foreach($tax_info as $key=>$tax)
				{
					$name = $tax['percent'].'% ' . $tax['name'];
				
					if ($tax['cumulative'])
					{
						$prev_tax = ($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)*(($tax_info[$key-1]['percent'])/100);
						$tax_amount=(($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100) + $prev_tax)*(($tax['percent'])/100);					
					}
					else
					{
						$tax_amount=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100)*(($tax['percent'])/100);
					}

					if (!isset($taxes[$name]))
					{
						$taxes[$name] = 0;
					}
					$taxes[$name] += $tax_amount;
				}
			}
		}
		
		return $taxes;
	}
	
	function get_items_in_cart()
	{
		$items_in_cart = 0;
		foreach($this->get_cart() as $item)
		{
		    $items_in_cart += $item['quantity'];
		}
		
		return $items_in_cart;
	}
        
        //calculate bike
        function get_items_in_cart_bike(){
            $items_in_cart = 0;
		foreach($this->get_cart() as $item)
		{
		    $items_in_cart += $item['number_of_day'];
		}
		
		return $items_in_cart;
        }
	
	function get_subtotal()
	{
		$subtotal = 0;
                
		foreach($this->get_cart() as $item)
		{
		    $subtotal += ($item['price']*$item['quantity'] - $item['discount']);
		}
		return to_currency_no_money($subtotal);
	}
        
	// For getting total of sale for only ticket
	function get_total($sale_id = false)
	{           
		$total = 0;
		foreach($this->get_cart() as $item)
		{
                    $total+=($item['price']*$item['quantity'] - $item['discount']);
		}
		return to_currency_no_money($total);
	}

	
// calculate bike
          // calculate for bike
//        function get_subtotal_bike(){
//            $subtotal = 0;
//                
////                 var_dump('get_subtotal -> total out of foreach bike= '.$total);
//                
//		foreach($this->get_cart() as $item)
//		{
////                     var_dump('get_subtotal -> total in of foreach top= '.$total);
//                           
//		    $subtotal += ($item['price']*$item['number_of_day'] - $item['discount']);
//                    
////                     var_dump('get_subtotal -> total in of foreach bottom= '. $subtotal);die('get_subtotal');
//		}
//		return to_currency_no_money($subtotal);
//        }
//
//	// For getting total of sale for only ticket
//	function get_total_bike($sale_id = false)
//	{           
//		$total = 0;
//		foreach($this->get_cart() as $item)
//		{
//            
//                    $total+=($item['price']*$item['number_of_day'] - $item['discount']);
//            
//		}
//		return to_currency_no_money($total);
//	}

	function get_total_in_riels($total, $default_currency)
	{
		$currency_rate = $this->CI->currency_model->get_currency_rate_by_type_name($default_currency)->currency_value;
        $total_in_riels = $total * $currency_rate;
		return to_currency_no_money($total_in_riels);
	}
        
//     function edit_item_bike($line,$description,$serialnumber,$quantity,$discount,$num_day,$price)
//	{
//		$items = $this->get_cart();
//		if(isset($items[$line]))
//		{
//			$items[$line]['description'] = $description;
//			$items[$line]['serialnumber'] = $serialnumber;
//			$items[$line]['quantity'] = $quantity;
//			$items[$line]['discount'] = $discount;
//                        $items[$line]['number_of_day'] = $num_day;
//			$items[$line]['price'] = $price;
//                        
//			$this->set_cart($items);          
//		}
//
//		return false;
//	}
        
}
?>