<?php

class Sale_bike_lib {

	var $CI;

	function __construct() {
		$this->CI = & get_instance();
	}

	function is_valid_receipt($receipt_sale_id) {
		//POS #
		$pieces = explode(' ', $receipt_sale_id);

		if (count($pieces) == 2 && $pieces[0] == 'POS') {
			return $this->CI->Sale_bike->exists($pieces[1]);
		}

		return false;
	}

	function is_valid_item_kit($item_kit_id) {
		//KIT #
		$pieces = explode(' ', $item_kit_id);

		if (count($pieces) == 2 && $pieces[0] == 'KIT') {
			return $this->CI->bike_kit->exists($pieces[1]);
		}
		/* else
		  {
		  // $testing = $this->CI->ticket_kit->get_item_kit_id($item_kit_id) !== FALSE;
		  // echo '$testing';
		  // var_dump($testing);
		  return $this->CI->ticket_kit->get_item_kit_id($item_kit_id) !== FALSE;
		  } */
		// chhen
		return false;
	}

	function get_valid_item_kit_id($item_kit_id) {
		//KIT #
		$pieces = explode(' ', $item_kit_id);

		if (count($pieces) == 2 && $pieces[0] == 'KIT') {
			return $pieces[1];
		} else {
			return $this->CI->bike_kit->get_item_kit_id($item_kit_id);
		}
	}
 

        function return_entire_sale($receipt_sale_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_sale_id);
		$sale_id = $pieces[1];

		$this->CI->sale_lib_bike->empty_cart();
		$this->CI->sale_lib_bike->delete_customer();

		foreach($this->CI->Sale_bike->get_sale_items($sale_id)->result() as $row)
		{
			// $this->add_item($row->item_kitID,-$row->quantity_purchased,$row->discount_percent,$row->item_kit_unit_price,$row->description,$row->serialnumber);
			$this->add_item($row->item_bike_id,-$row->quantity_of_bike,$row->discount_percent,$row->sell_price);
//			echo 'discount_percent: '.$row->discount_percent;
		}
//		foreach($this->CI->sale_ticket->get_sale_item_kits($sale_id)->result() as $row)
//		{
//			 
//		}
		$this->CI->sale_lib_bike->set_customer($this->CI->Sale_bike->get_customer($sale_id)->customer_id);
	}
        
        function get_item_id($line_to_get)
	{
		$items = $this->CI->sale_lib->get_cart();

		foreach ($items as $line=>$item)
		{
			if($line==$line_to_get)
			{
				return isset($item['item_bike_id']) ? $item['item_bike_id'] : -1;
			}
		}
		
		return -1;
	}
        
        function delete_item($line)
	{
		$items=$this->CI->sale_lib->get_cart();
		$item_id  = $this->get_item_id($line);
		if($this->CI->Giftcard->get_giftcard_id($this->CI->bike->get_info($item_id)->bike_types))
		{
			$this->CI->Giftcard->delete_completely($this->CI->bike->get_info($item_id)->bike_types);
		}
		unset($items[$line]);
		$this->CI->sale_lib->set_cart($items);
                
	}

	// Retrieve data of sale via id
	function copy_entire_sale($sale_id, $category)
	{
		$this->CI->sale_lib->empty_cart();
		$this->CI->sale_lib->delete_customer();
		foreach($this->CI->Sale_bike->get_sale_items($sale_id)->result() as $row)
		{
			$data = $this->add_item($row->item_bikeID,$row->quantity_of_bike, $category, $row->discount_percent,$row->actual_price,$row->description);
//			echo 'copy entire sale = '.$data;
		}
		foreach($this->CI->Sale->get_sale_item_kits($sale_id)->result() as $row)
		{
			//not yet edit
			$this->add_item_kit('KIT '.$row->item_kitID,$row->quantity_purchased, $category, $row->discount_percent,$row->item_kit_unit_price,$row->description);
		}

		foreach($this->CI->Sale->get_sale_payments($sale_id)->result() as $row)
		{
			$this->CI->sale_lib->add_payment($row->payment_type,$row->payment_amount, $row->payment_date);
		}

		$this->CI->sale_lib->set_customer($this->CI->Sale->get_customer($sale_id)->customer_id);
		$this->CI->sale_lib_sms->set_time_in($this->CI->bike->get_time_in($sale_id));
		$this->CI->sale_lib_sms->set_time_out($this->CI->bike->get_time_out($sale_id));
                
		$this->CI->sale_lib->set_rent_dates($this->CI->bike->get_rent_dates($sale_id));
		$this->CI->sale_lib->set_return_dates($this->CI->bike->get_return_dates($sale_id));
		$this->CI->sale_lib->set_commissioner($this->CI->Sale->get_commissioner($sale_id)->commisioner_id);
		$this->CI->sale_lib->set_commissioner_price($this->CI->Sale->get_commission_price($sale_id));
		$this->CI->sale_lib->set_deposit_price($this->CI->Sale->get_deposit_price($sale_id));
		$this->CI->sale_lib->set_comment($this->CI->Sale->get_comment($sale_id));
		$this->CI->sale_lib->set_comment_on_receipt($this->CI->Sale->get_comment_on_receipt($sale_id));

	}

	function add_item($item_id, $quantity = 1,$category, $discount = 0, $price = null) {
		//make sure item exists
		if (!$this->CI->bike_item->exists(is_numeric($item_id) ? (int) $item_id : -1)) {
			//try to get item id given an item_number
                    
			$item_id = $this->CI->bike_item->get_item_id($item_id);

			if (!$item_id)           
				return false;
		}
		else {
			$item_id = (int) $item_id;
		}
		//Alain Serialization and Description
		//Get all items in the cart so far...
        
        $items = $this->CI->sale_lib->get_cart();

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
			if (isset($item['item_bike_id']) && $item['item_bike_id'] == $item_id) {
				$itemalreadyinsale = TRUE;
				$updatekey = $item['line'];

				if ($this->CI->bike_item->get_info($item_id)->bike_code == $items[$updatekey]['bike_code'] && $this->CI->bike_item->get_info($item_id)->bike_types == lang('sales_giftcard')) {
					return false;
				}
			}
		}

		$insertkey = $maxkey + 1;

		$today = strtotime(date('Y-m-d'));
		// $price_to_use=( isset($this->CI->Item->get_info($item_id)->start_date) && isset($this->CI->Item->get_info($item_id)->end_date) && isset($this->CI->Item->get_info($item_id)->promo_price)  && ( strtotime($this->CI->Item->get_info($item_id)->start_date) <= $today) && (strtotime($this->CI->Item->get_info($item_id)->end_date) >= $today) ?  $this->CI->Item->get_info($item_id)->promo_price :  $this->CI->Item->get_info($item_id)->unit_price);
		$price_to_use = $this->CI->bike_item->get_info($item_id)->unit_price;

		//array/cart records are identified by $insertkey and item_id is just another field.
		$item = array(($insertkey) =>
		    array(
			'item_bike_id' => $item_id,
			'line' => $insertkey,
			'name' => $this->CI->bike_item->get_info($item_id)->bike_types,
                        'bike_code' => $this->CI->bike_item->get_info($item_id)->bike_code,
//			'description' => $description != null ? $description : $this->CI->massage_item->get_info($item_id)->massage_desc,
			'quantity' => $quantity,
			'discount' => $discount,
			// 'price' => $price != null ? $price : $price_to_use
			 'price' =>$price_to_use

		    )
		);

		//Item already exists and is not serialized, add to quantity
		if ($itemalreadyinsale) {
			$items[$updatekey]['quantity'] += $quantity;
		} else {
			//add to existing array
			$items += $item;
		}

                $this->CI->sale_lib->set_cart($items);
		return true;
	}
        

}

?>