<?php
function get_barcode_data($item_ids)
	{
		$CI =& get_instance();
		$result = array();

		$item_ids = explode('~', $item_ids);
		foreach ($item_ids as $item_id)
		{
			$item_info = $CI->Item->get_info($item_id);
			if($CI->config->item('barcode_price_include_tax'))
			{
			$total_tax=0;
			$tax_info = isset($item_id) ? $CI->Item_taxes->get_info($item_id) : $CI->Item_kit_taxes->get_info($item_id);
				foreach($tax_info as $key=>$tax)
				{
					$name = $tax['percent'].'% ' . $tax['name'];

					if ($tax['cumulative'])
					{
						$prev_tax = $item_info->unit_price*(($tax_info[$key-1]['percent'])/100);
						$tax_amount=($item_info->unit_price + $prev_tax)*(($tax['percent'])/100);					
					}
					else
					{
						$tax_amount=$item_info->unit_price*(($tax['percent'])/100);
					}

					if (!isset($taxes[$name]))
					{
						$taxes[$name] = 0;
					}
					$total_tax+= $tax_amount;
				}

			$result[] = array('name' =>$item_info->name.': '.to_currency($item_info->unit_price+$total_tax), 'id'=> number_pad($item_id, 11));
		  }
		  else
		  { 
		    $result[] = array('name' =>$item_info->name.': '.to_currency($item_info->unit_price), 'id'=> number_pad($item_id, 11));
		  }
		
		}
	
	return $result;
	}

?>