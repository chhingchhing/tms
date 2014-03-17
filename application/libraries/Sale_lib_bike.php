<?php
class Sale_lib_bike
{
	var $CI;

  	function __construct()
	{
		$this->CI =& get_instance();
	}

	function set_customer($customer_id)
	{
		$this->CI->session->set_userdata('customer',$customer_id);
	}

	function empty_cart()
	{
		$this->CI->session->unset_userdata('cart');
	}

	function delete_customer()
	{
		$this->CI->session->unset_userdata('customer');
	}

}
?>