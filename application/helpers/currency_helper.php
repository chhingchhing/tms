<?php
function to_currency($number)
{
	$CI =& get_instance();
	$currency_symbol = $CI->config->item('currency_symbol') ? $CI->config->item('currency_symbol') : '$';
	if($number >= 0)
	{
		return $currency_symbol.number_format($number, 2, '.', ',');
    }
    else
    {
    	return '-'.$currency_symbol.number_format(abs($number), 2, '.', ',');
    }
}

function round_to_nearest_05($amount)
{
	return round($amount * 2, 1) / 2;
}

function to_currency_no_money($number)
{
	return number_format($number, 2, '.', '');
}

function to_quantity($val)
{
		return $val == (int)$val ? (int)$val : $val ; 
			
}

?>