<?php
class Appconfig extends CI_Model 
{
	
	function exists($key)
	{
		$this->db->from('app_config');	
		$this->db->where('app_config.key',$key);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	function get_all()
	{
		$this->db->from('app_config');
		$this->db->order_by("key", "asc");
		return $this->db->get();		
	}
	
	function get($key)
	{
		return $this->config->item($key);
	}
	
	function save($key,$value)
	{
		$config_data=array(
		'key'=>$key,
		'value'=>$value
		);
				
		if (!$this->exists($key))
		{
			return $this->db->insert('app_config',$config_data);
		}
		
		$this->db->where('key', $key);
		return $this->db->update('app_config',$config_data);		
	}
	
	function batch_save($data)
	{
		$success=true;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		foreach($data as $key=>$value)
		{
			if(!$this->save($key, $value))
			{
				$success=false;
				break;
			}
		}
		
		$this->db->trans_complete();		
		return $success;
		
	}
		
	function get_logo_image()
	{
		if ($this->session->userdata('office_number'))
		{
			$office = $this->Office->get_office_id($this->session->userdata("office_number"));
			return site_url('app_files/view/'.$office);
		// } elseif ($this->config->item('company_logo')) {
		// 	return site_url('app_files/view/'.$this->get('company_logo'));
		}
		return 'images/header/header_logo.png';
	}
		
	function get_additional_payment_types()
	{
		$return = array();
		$payment_types = $this->get('additional_payment_types');
		if ($payment_types)
		{
			$return = array_map('trim', explode(',',$payment_types));
		}
		
		return $return;
	}

	
	function get_default_currency()
	{
		$return = array();
		$default_currency = $this->get('default_currency');
		if ($default_currency)
		{
			$return = array_map('trim', explode(',',$default_currency));
		}
		
		return $return;
	}
}

?>