<?php
function is_sale_integrated_cc_processing()
{
	$CI =& get_instance();

	$cc_payment_amount = $CI->sale_lib->get_payment_amount(lang('sales_cash'));

	return $CI->config->item('enable_credit_card_processing') && $cc_payment_amount != 0;
	// return $cc_payment_amount != 0;
}
?>