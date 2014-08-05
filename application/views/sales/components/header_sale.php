<?php 
echo form_hidden("controller_name", $controller_name);
echo form_hidden("office_number", $this->session->userdata("office_number"));
?>

<div class="row" id="title_bar">
    <div id="title_icon" class="col-md-3">
    	<img src='<?php echo base_url() ?>images/menubar/<?php echo $controller_name ?>.png' alt='title icon' />
        <?php if($this->sale_lib->get_change_sale_id()) { ?>
        <?php 
        $office_id = $this->Office->get_office_id($this->session->userdata("office_number"));
        ?>

            <?php echo lang('sales_editing_sale'); ?> <b> W <?php echo $office_id.'-'.str_pad($this->sale_lib->get_change_sale_id(), 6, '0',STR_PAD_LEFT); ?> </b> 
        <?php } else {
            echo lang('sales_register');
        } ?>
    </div>
    <div class="col-md-6" id="reg_item_search">
        <div class="col-md-10">
        	<?php echo form_open("$controller_name/add",array('id'=>'add_'.$controller_name.'_item_form','role'=>'form', 'class'=>'form-inline form_add_item')); ?>
            
                <div id="input_sale" class="col-md-9">
            		<?php echo form_input(array('name'=>'item','id'=>'search_'.$controller_name,'size'=>'40', 'accesskey' => 'i','class'=>'form-control input-sm search_item'));?>
            	</div>
            	<div class="col-md-3">
            		<?php                       
                    echo anchor("#$controller_name",
    				"<div class='small_button'><span>".lang('sales_new_item')."</span></div>",
    				array('class'=>'thickbox none btn btn-primary btn-sm','title'=>lang('sales_new_item'),'role'=>'button', 'data-toggle' => 'modal', 'data-target' => "#$controller_name"));                
                    ?>
            	</div>
        	<?php echo form_close(); ?>
        </div>
        <div id="show_suspended_sales_button" class="col-md-2">
            <?php echo anchor("#suspended",
            "<div class='small_button'>".lang('sales_suspended_sales')."</div>",
            array('id'=>'show_suspended_sales_button','class'=>'thickbox none btn btn-primary btn-sm','title'=>lang('sales_suspended_sales'),'role'=>'button', 'data-toggle' => 'modal', 'data-target' => "#suspended"));
            ?>
        </div>	
    </div>
    <div id="reg_mode" class="col-md-3">
    	<?php echo form_open("$controller_name/change_mode",array('id'=>'mode_form')); ?>
		<div class="col-xs-6">
			<span><?php echo lang('sales_mode');?></span>
		</div>
		<div class="col-xs-5">
            <?php 
            echo form_hidden("mode", $modes);
            echo $modes;
            ?>
			<?php // echo form_dropdown('mode',$modes,$mode,'id="mode" class="form-control input-sm"'); ?>
		</div>		
		<?php echo form_close(); ?>
    </div>
</div>