<?php
class App_files extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function view($office_id)
	{ 
		$file = $this->Appfile->get($office_id);
		header("Content-type: ".get_mime_by_extension($file->file_name));
		echo $file->file_data;
	}
}