<?php
class Appfile extends CI_Model 
{		
	function get($office_id)
	{
		$query = $this->db->get_where('app_files', array('officeID' => $office_id), 1);
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		
		return "";
		
	}
	
	function save($file_name,$file_data, $office_id = false)
	{
		$file_data=array(
		'file_name'=>$file_name,
		'file_data'=>$file_data,
		'officeID' => $office_id
		);

		if (!$office_id or !$this->exists($office_id))
		{
			if ($this->db->insert('app_files',$file_data))
			{
				return $this->db->insert_id();
			}
			
			return false;
		}

		$this->db->where('officeID', $office_id);
		if ($this->db->update('app_files',$file_data))
		{
			return $office_id;
		}
		
		return false;
	}
		
	function delete($office_id)
	{
		return $this->db->delete('app_files', array('officeID' => $office_id)); 
	}

	function exists($office_id) {
        $this->db->from('app_files');
        $this->db->where('officeID', $office_id);
        $query = $this->db->get();

        return ($query->num_rows() == 1);
    }

}

?>