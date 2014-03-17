<?php
class Item_kit_ticket extends CI_Model
{
	/*
	Gets item kit items for a particular item kit
	*/
	function get_info($item_kit_id)
	{
		$this->db->from('item_kits_tickets');
		// $this->db->where('category','tickets');
		$this->db->where('item_kit_id',$item_kit_id);
		//return an array of item kit items for an item
		return $this->db->get()->result();
	}

	/**
	 * Get kits with item
	 * @param type $ite_id
	 * @return type
	 */
	function get_kits_have_item($item_id)
	{
	    $this->db->from('item_kits_tickets');
	    $this->db->where('ticket_id',$item_id);	    
	    return $this->db->get()->result_array();
	}


}