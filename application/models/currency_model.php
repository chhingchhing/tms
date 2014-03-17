<?php

class Currency_model extends CI_Model {

	function get_currency_rate_by_type_name($currency_type_name) {

		$query = $this->db
			->where("currency_type_name", $currency_type_name)
			->get("currency_types");
		return $query->row()->currency_value;
	}

}