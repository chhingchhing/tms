<?php $this->load->view("partial/header"); ?>
<div id="register_container" class="sales">
	<?php $this->load->view("sales/register"); ?>
</div>
<?php 
$this->load->view('customers/form');
$this->load->view('bikes/form');
$this->load->view('sales/suspended');
$this->load->view('tickets/form');
$this->load->view('commissioners/form');
$this->load->view('guides/form');
$this->load->view('tours/form');
$this->load->view('massages/form');
$this->load->view('employees/massager');
?>
<?php $this->load->view("partial/footer"); ?>
