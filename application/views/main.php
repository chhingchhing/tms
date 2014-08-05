<?php $this->load->view("partial/header"); ?>

<?php 
	$uri1 = $this->uri->segment(1);
	if (!$this->uri->segment(2)) {
		$this->load->view('partial/dashboard');
	}
?>

<?php $this->load->view("partial/footer"); ?>