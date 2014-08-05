<?php $this->load->view("partial/header"); ?>
<div id="home_module_list" class="panel panel-info row wrap_module_home">
    <div class="panel-heading">
        <h3 class="panel-title" id="wel_home"><p><?php echo lang('common_welcome_message'); ?></p></h3>
    </div>
  
        <div class="col-12 col-sm-12 col-lg-12 module_item panel-body" id="moudle_icon_home">
        	<?php 
        	$office_id = $this->Office->get_office_id($this->session->userdata("office_number"));
                $office_info = $this->Office->get_info($office_id);
        	?>
            <p> <?php echo lang('common_welcome').$office_info->ofc_company; ?> </p>
        </div>

    
</div>
<br>
<?php $this->load->view("partial/footer");?>