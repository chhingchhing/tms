<?php
class Item_kit_massage extends CI_Model
{
	/*
	Gets item kit items for a particular item kit
	*/
	function get_info($item_kit_id)
	{
		echo $item_kit_id;
		$this->db->from('item_kits_tickets');
		// $this->db->where('category','tickets');
		$this->db->where('item_kit_id',$item_kit_id);
		//return an array of item kit items for an item
		return $this->db->get()->result();
	}

	/*
	Determines if a given item_id is an item kit
	*/
	/*function exists($item_kit_id)
	{
		echo $item_kit_id;
		$this->db->from('item_kits');
		$this->db->where('item_kit_id',$item_kit_id);
		$query = $this->db->get();
var_dump($query);
		return ($query->num_rows()==1);
	}*/

	/**
	 * Get kits with item
	 * @param type $ite_id
	 * @return type
	 */
	function get_kits_have_item($item_id)
	{
		echo $item_id.'Item_kit_ticket';
	    $this->db->from('item_kits_tickets');
	    $this->db->where('ticket_id',$item_id);	    
	    return $this->db->get()->result_array();
	}


}