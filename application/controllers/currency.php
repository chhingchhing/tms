<?php

class Currency extends Secure_area {

	function __construct() {
		parent::__construct("currency");
		$this->load->model(array("currency_model"));
	}



}