<!-- <div id='TB_load'><img src='<?php //echo base_url()?>images/loading_animation.gif'/></div> -->
<?php if($this->sale_lib->get_change_sale_id()) { ?>
	<div id="editing_sale" > <?php echo lang('sales_editing_sale'); ?> <b> CGATE <?php echo $this->sale_lib->get_change_sale_id(); ?> </b> </div>
<?php } ?>

<div class="panel panel-info">

	<div class="panel-heading">
		<?php $this->load->view("sales/components/header_sale"); ?>
	</div>

	<?php 
	if ($warning_seat_no) { ?>
		<span id="getSmsError"><p><?php echo $warning_seat_no; ?></p></span><span class="crossing">X</span>
	<?php } elseif ($error) {?>
		<span id="getSmsError"><p><?php echo $error; ?></p></span><span class="crossing">X</span>
	<?php } elseif ($warning) {?>
		<span id="getSmsError"><p><?php echo $warning; ?></p></span><span class="crossing">X</span>
	<?php }
	?>
	<div class="panel-body">
        <!-- Panel content -->

        <div class="row" id="contents">
        	<div class="col-xs-12 col-sm-9" id="register_items_container">
        		<div id="register_holder" class="table-responsive">
					<?php $this->load->view("sales/components/register_sale"); ?>
				</div> <!--Register holder-->

				<div id="reg_item_base"></div>

				<?php if ($this->config->item('track_cash')) { ?>
				<div>
					<?php echo anchor(site_url('sales/closeregister?continue=home'), lang('sales_close_register')); ?>
				</div>
				<?php } ?>

				<?php if ($this->Employee->has_module_permission('giftcards', $this->Employee->get_logged_in_employee_info()->person_id)) {?>						
					<div id="new_giftcard_button_register" >			
								<?php echo 
							anchor("sales/new_giftcard/width~550",
							lang('sales_new_giftcard'),
							array('class'=>'thickbox none new', 
								'title'=>lang('sales_new_giftcard')));
						?> 
					</div>	
				<?php } ?>

				<div id="sales_search" >
					<?php 
// <<<<<<< HEAD
// 						echo anchor("reports/sales_generator_".$this->uri->segment(1)."/".$this->session->userdata("office_number")."/".$controller_name,
// =======
						echo anchor("reports/sales_generator_".$this->uri->segment(1)."/".$this->session->userdata("office_number").'/'.$this->uri->segment(1),
						lang('sales_search_reports'),
						array('class'=>'none btn btn-primary btn-sm', 
							'role' => 'button',
							'title'=>lang('sales_search_reports')));
					?> 
				</div>	
        	</div>

        	<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="over_all_sale_container" role="navigation">
        		<?php $this->load->view("sales/components/overall_sale"); ?>
        </div> <!-- row contents-->
	</div> <!-- End panel-body-->
</div>

</div>


