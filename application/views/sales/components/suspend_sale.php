<div id="suspend_cancel" class="row-fluid">
	<div id="suspend" class="col-md-6" <?php if(count($cart) > 0){ echo "style='visibility: visible;'";}?>>				
		<?php
		// Only show this part if there are Items already in the sale.
		if(count($cart) > 0){ ?>
				<div class='small_button btn btn-xs btn-warning' id='suspend_sale_button'> 
					<span><?php echo lang('sales_suspend_sale');?></span>
				</div>
		<?php }	?>
	</div>
	<div id="cancel" class="col-md-6" <?php if(count($cart) > 0){  echo "style='visibility: visible;'";}?>>											
		<?php
		// Only show this part if there are Items already in the sale.
		if(count($cart) > 0){ ?>
			<?php echo form_open("$controller_name/cancel_sale",array('id'=>'cancel_sale_form')); ?>
				<div class='small_button btn btn-xs btn-default' id='cancel_sale_button'>
					<span><?php echo lang('sales_cancel_sale'); ?></span>
				</div>
			</form>
		<?php } ?>
	</div>
	<div style="clear: both"></div>
</div>