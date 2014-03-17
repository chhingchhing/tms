<?php
class Sale_tour_lib
{
	var $CI;

  	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model(array("sale_tour"));
	}

	function add_item($item_id,$quantity=1,$category,$discount=0,$price=null,$description=null,$serialnumber=null)
	{
		//make sure item exists
		if(!$this->CI->tour_item->exists(is_numeric($item_id) ? (int)$item_id : -1))	
		{
			//try to get item id given an item_number
			$item_id = $this->CI->tour_item->get_item_id($item_id);
 
			if(!$item_id)
				return false;
		}
		else
		{
			$item_id = (int)$item_id;
		}
		//Alain Serialization and Description

		//Get all items in the cart so far...
		$items = $this->CI->sale_lib->get_cart();

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

			if(isset($item['tour_id']) && $item['tour_id']==$item_id)
			{
				$itemalreadyinsale=TRUE;
				$updatekey=$item['line'];
			 
				if($this->CI->tour_item->get_info($item_id)->description==$items[$updatekey]['description'] && $this->CI->tour_item->get_info($item_id)->tour_name==lang('sales_giftcard'))
					{
						return false;
					}
			} 
		}

		$insertkey=$maxkey+1;

        $today =  strtotime(date('Y-m-d'));
        $price_to_use = $this->CI->tour_item->get_info($item_id)->sale_price;

		//array/cart records are identified by $insertkey and item_id is just another field.
		$item = array(($insertkey)=>
		array(
			'tour_id'=>$item_id,
			'line'=>$insertkey,
			'name'=>$this->CI->tour_item->get_info($item_id)->tour_name,
			'departure_date'=>$this->CI->tour_item->get_info($item_id)->departure_date,
			'departure_time'=>$this->CI->tour_item->get_info($item_id)->departure_time,
			'by'=>$this->CI->tour_item->get_info($item_id)->by,
			'description'=>$description!=null ? $description: $this->CI->tour_item->get_info($item_id)->description,
			'quantity'=>$quantity,
            'discount'=>$discount,
			'price'=>$price!=null ? $price:$price_to_use
			)
		);

		//Item already exists and is not serialized, add to quantity
		if($itemalreadyinsale )
		{
			$items[$updatekey]['quantity'] += $quantity;
		}
		else
		{
			//add to existing array
			$items += $item;
		}

		$this->CI->sale_lib->set_cart($items);
		return true;

	}
	
	
	function add_item_kit($external_item_kit_id_or_item_number,$quantity=1,$category,$discount=0,$price=null,$description=null)
	{
		if (strpos($external_item_kit_id_or_item_number, 'KIT') !== FALSE)
		{
			//KIT #
			$pieces = explode(' ',$external_item_kit_id_or_item_number);
			$item_kit_id = (int)$pieces[1];
		}
		else
		{
			$item_kit_id = $this->CI->Item_kit->get_item_kit_id($external_item_kit_id_or_item_number, $category);
		}
		
		//make sure item exists
		if(!$this->CI->Item_kit->exists($item_kit_id, $category))	
		{
			return false;
		}
		
		if ( $this->CI->Item_kit->get_info($item_kit_id, $category)->unit_price == null)
		{
			foreach ($this->CI->item_kit_tour->get_info($item_kit_id) as $item_kit_item)
			{ 
				for($k=0;$k<$item_kit_item->quantity;$k++)
				{
					$this->add_item($item_kit_item->item_kit_id, $quantity, $category);
				}
			}
			
			return true;
		}
		else
		{
			$items = $this->CI->sale_lib->get_cart();

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

			$insertkey = $maxkey + 1;


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
				$items[$updatekey]['quantity'] += $quantity;
			}
			else
			{
				//add to existing array
				$items += $item;
			}

			$this->CI->sale_lib->set_cart($items);

			return true;
		}
	} 
	
	/*function out_of_stock($item_id)
	{
		//make sure item exists
		if(!$this->CI->ticket_item->exists($item_id))
		{
			//try to get item id given an item_number
			$item_id = $this->CI->ticket_item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}
		
		$item = $this->CI->ticket_item->get_info($item_id);
		$quanity_added = $this->get_quantity_already_added($item_id);
		
		if ($item->quantity - $quanity_added <= 0)
		{
			return true;
		} elseif ($item->quantity == 0.00) {
			return true;
		}
		
		return false;
	}*/
	
	// Check out of stock of kit item *
	/*function out_of_stock_kit($kit_id)
	{
	    //Make sure Item kit exist
	    if(!$this->CI->ticket_kit->exists($kit_id)) return FALSE;

	    //Get All Items for Kit
	    $kit_items = $this->CI->ticket_kit->get_info_item_kits($kit_id);

	    //Check each item
	    // Out of stock
	    foreach ($kit_items as $itm)
	    {
			$item = $this->CI->ticket_item->get_info($itm->ticket_id);
			$item_already_added = $this->get_quantity_already_added($itm->ticket_id);
			if ($item->quantity - $item_already_added <= 0)
			{
		    	return true;
			} elseif ($item->quantity == 0.00) {
				return true;
			}
	    }
	    return false;
	}*/

	/*function get_quantity_already_added($item_id)
	{
		$items = $this->CI->sale_lib->get_cart();
		$quanity_already_added = 0;
		foreach ($items as $item)
		{
			if(isset($item['ticket_id']) && $item['ticket_id']==$item_id)
			{
				$quanity_already_added += $item['quantity'];
			}
		}
		
		//Check Item Kist for this item
		$all_kits = $this->CI->ticket_kit->get_kits_have_item($item_id);

		foreach($all_kits as $kits)
		{
		    $kit_quantity = $this->get_kit_quantity_already_added($kits['item_kit_id']);
		    if($kit_quantity > 0)
		    {
				$quanity_already_added += ($kit_quantity * $kits['quantity']);
		    }
		}
		return $quanity_already_added;
	}*/
	
	/*function get_kit_quantity_already_added($kit_id)
	{
	    $items = $this->CI->sale_lib->get_cart();
	    $quanity_already_added = 0;
	    foreach ($items as $item)
	    {
		    if(isset($item['item_kit_id']) && $item['item_kit_id']==$kit_id)
		    {
				$quanity_already_added+=$item['quantity'];
		    }
	    }
	    return $quanity_already_added;
	}*/

	/*function is_kit_or_item($line_to_get)
	{
	    $items = $this->CI->sale_lib->get_cart();
	    foreach ($items as $line=>$item)
	    {
		if($line==$line_to_get)
		{
		    if(isset($item['ticket_id']))
		    {
			return 'item';
		    }
		    elseif ($item['item_kit_id'])
		    {
			return 'kit';
		    }
		}
	    }
	    return -1;
	}*/


	/*function is_valid_receipt($receipt_sale_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_sale_id);

		if(count($pieces)==2 && $pieces[0] == 'POS')
		{
			return $this->CI->sale_ticket->exists($pieces[1]);
		}

		return false;
	}*/
	

	// function is_valid_item_kit($item_kit_id)
	// {
	// 	//KIT #
	// 	$pieces = explode(' ',$item_kit_id);

	// 	if(count($pieces)==2 && $pieces[0] == 'KIT')
	// 	{
	// 		return $this->CI->ticket_kit->exists($pieces[1]);
	// 	}
		/*else
		{
			// $testing = $this->CI->ticket_kit->get_item_kit_id($item_kit_id) !== FALSE;
			// echo '$testing';
			// var_dump($testing);
			return $this->CI->ticket_kit->get_item_kit_id($item_kit_id) !== FALSE;
		}*/
		// chhingchhing
	// 	return false;
	// }

	/*function get_valid_item_kit_id($item_kit_id)
	{
		//KIT #
		$pieces = explode(' ',$item_kit_id);

		if(count($pieces)==2 && $pieces[0] == 'KIT')
		{
			return $pieces[1];
		}
		else
		{
			return $this->CI->ticket_kit->get_item_kit_id($item_kit_id);
		}
	}*/


	/*function return_entire_sale($receipt_sale_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_sale_id);
		$sale_id = $pieces[1];

		$this->CI->sale_lib->empty_cart();
		$this->CI->sale_lib->delete_customer();

		foreach($this->CI->sale_ticket->get_sale_items($sale_id)->result() as $row)
		{
			// $this->add_item($row->item_kitID,-$row->quantity_purchased,$row->discount_percent,$row->item_kit_unit_price,$row->description,$row->serialnumber);
			$this->add_item($row->ticketID,-$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description);
		}
		foreach($this->CI->sale_ticket->get_sale_item_kits($sale_id)->result() as $row)
		{
			 
		}
		$this->CI->sale_lib->set_customer($this->CI->sale_ticket->get_customer($sale_id)->customer_id);
	}*/

	// Retrieve data of sale via id
	function copy_entire_sale($sale_id, $category)
	{
		$this->CI->sale_lib->empty_cart();
		$this->CI->sale_lib->delete_customer();

		foreach($this->CI->sale_tour->get_sale_items($sale_id)->result() as $row)
		{
			$this->add_item($row->tour_id,$row->quantity_purchased, $category, $row->discount_percent,$row->item_unit_price,$row->description);
		}
		foreach($this->CI->Sale->get_sale_item_kits($sale_id)->result() as $row)
		{
			$this->add_item_kit('KIT '.$row->item_kitID,$row->quantity_purchased, $category, $row->discount_percent,$row->item_kit_unit_price,$row->description);
		}

		foreach($this->CI->Sale->get_sale_payments($sale_id)->result() as $row)
		{
			$this->CI->sale_lib->add_payment($row->payment_type,$row->payment_amount, $row->payment_date);;
		}

		$this->CI->sale_lib->set_customer($this->CI->Sale->get_customer($sale_id)->customer_id);
		$this->CI->sale_lib->set_guide($this->CI->Sale->get_guide($sale_id)->guide_id);
		$this->CI->sale_lib->set_commissioner($this->CI->Sale->get_commissioner($sale_id)->commisioner_id);
		$this->CI->sale_lib->set_commissioner_price($this->CI->Sale->get_commission_price($sale_id));
		$this->CI->sale_lib->set_comment($this->CI->Sale->get_comment($sale_id));
		$this->CI->sale_lib->set_comment_on_receipt($this->CI->Sale->get_comment_on_receipt($sale_id));
	}

	/*function delete_suspended_sale_id()
	{
		$this->CI->session->unset_userdata('suspended_sale_id');
	}
	*/
	/*function get_change_sale_id()
	{
		return $this->CI->session->userdata('change_sale_id');
	}*/
	
	/*function set_change_sale_id($change_sale_id)
	{
		$this->CI->session->set_userdata('change_sale_id',$change_sale_id);
	}*/
	
	/*function delete_change_sale_id()
	{
		$this->CI->session->unset_userdata('change_sale_id');
	}*/

	/*function get_taxes($sale_id = false)
	{
		$taxes = array();

		$taxes_from_sale = array_merge($this->CI->sale_ticket->get_sale_items_taxes($sale_id), $this->CI->sale_ticket->get_sale_item_kits_taxes($sale_id));

		if ($sale_id && $taxes_from_sale)
		{
			$taxes_from_sale = array_merge($this->CI->sale_ticket->get_sale_items_taxes($sale_id), $this->CI->sale_ticket->get_sale_item_kits_taxes($sale_id));
			
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
			$customer_id = $this->CI->sale_lib->get_customer();
			$customer = $this->CI->Customer->get_info($customer_id);
			//Do not charge sales tax if we have a customer that is not taxable
			if (!$customer->taxable and $customer_id!=-1)
			{
			   return array();
			}

			foreach($this->CI->sale_lib->get_cart() as $line=>$item)
			{
				$tax_info = isset($item['ticket_id']) ? $this->CI->Item_taxes->get_info($item['ticket_id']) : $this->CI->Item_kit_taxes->get_info($item['item_kit_id']);
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
	}*/

	function delete_item($line)
	{
		$items=$this->CI->sale_lib->get_cart();
		$item_id  = $this->get_item_id($line);
		unset($items[$line]);
		$this->CI->sale_lib->set_cart($items);
	}

	function get_item_id($line_to_get)
	{
		$items = $this->CI->sale_lib->get_cart();

		foreach ($items as $line=>$item)
		{
			if($line==$line_to_get)
			{
				return isset($item['tour_id']) ? $item['tour_id'] : -1;
			}
		}
		
		return -1;
	}

	function get_total($sale_id = false)
	{
		$total = 0;
		foreach($this->CI->sale_lib->get_cart() as $item)
		{
            $total+=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100);
		}
		/*foreach($this->CI->sale_tour_lib->get_taxes($sale_id) as $tax)
		{
			$total+=$tax;
		}*/
		return to_currency_no_money($total);
	}

	//Alain Multiple Payments
	function get_amount_due($sale_id = false)
	{
		$amount_due=0;
		$payment_total = $this->CI->sale_lib->get_payments_totals();
		$sales_total=$this->get_total($sale_id);
		$amount_due=to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	}

	function get_amount_due_round($sale_id = false)
	{
		$amount_due=0;
		$payment_total = $this->CI->sale_lib->get_payments_totals();
		$sales_total= $this->CI->config->item('round_cash_on_sales') ?  round_to_nearest_05($this->get_total($sale_id)) : $this->get_total($sale_id);
		$amount_due=to_currency_no_money($sales_total - $payment_total);
		return $amount_due;
	} 
	
}
?>