<?php 
$this->load->view("partial/header");
?>   
<div class="panel panel-danger" id="no_access">
	<div class="panel-heading">Error Page</div>
	<div class="panel-body">
		<div>
		<?php
		echo lang('error_no_permission_module').' '.$module_name; 
		?> 	
		</div>
		<div><?php echo img("images/no_access.png"); ?></div>
	</div>
	  <!-- <div class="panel-footer">Panel footer</div> -->
</div>
<div style="height: 10px"></div>

<?php 
$this->load->view("partial/footer");
?>