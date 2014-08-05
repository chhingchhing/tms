<?php $this->load->view("partial/header"); ?>

<div class="panel panel-info">
    <div class="panel-heading">
        <?php $this->load->view("reports/partial/panel_tab"); ?>
    </div>
    <div class="panel-body">
        <!-- Panel content -->
        <div class="table-responsive">
        
        	<?php $this->load->view("reports/partial/conditions"); ?>

        	<form name="salesReportGenerator" action="<?php echo site_url("reports/sales_generator_$controller_name/".$this->session->userdata('office_number').'/'.$controller_name); ?>" method="post">
        	<?php $this->load->view("reports/partial/form_conditions"); ?>
        	</form>

        </div>
    </div>
    <?php 
    if (isset($results)) echo $results;
?>
</div>

<?php $this->load->view("partial/footer"); ?>