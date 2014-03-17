<?php $this->load->view("partial/header"); ?>
<div id="edit_sale_wrapper" class="col-md-12">
<?php 
if ($success)
{
?>
	<div class="col-md-12 delete-message">
	  <h1 class="success"><?php echo lang('sales_delete_successful'); ?></h1>
	</div>
<?php	
}
else
{
?>
	<div class="col-md-12 delete-message">
	  <h1 class="fail"><?php echo lang('sales_delete_unsuccessful'); ?></h1>
	</div>
<?php
}
?>
</div>
<?php $this->load->view("partial/footer"); ?>