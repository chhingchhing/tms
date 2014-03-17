<?php $this->load->view("partial/header"); ?>
<div id="home_module_list" class="panel panel-info row wrap_module_home">
    <div class="panel-heading">
        <h3 class="panel-title" id="wel_home"><p><?php echo lang('common_welcome_message'); ?></p></h3>
    </div>
  
        <div class="col-12 col-sm-12 col-lg-12 module_item panel-body" id="moudle_icon_home">
            <p> <?php echo lang('common_welcome').$this->config->item('company'); ?> </p>
        </div>

    
</div>
<br>
<?php $this->load->view("partial/footer");?>