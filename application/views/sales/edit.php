<?php $this->load->view("partial/header"); ?>

<div class="panel panel-info">
    <div class="panel-heading">
        <div class="row" id="title_bar">
            <div id="page_title" class="col-10 col-sm-10 col-lg-10" style="margin-bottom:8px;">
                <table id="title_bar">
					<tr>
						<td id="title_icon">
							<img src='<?php echo base_url()?>images/menubar/<?php echo $controller_name; ?>.png' alt='title icon' />
						</td>
						<td id="title"><?php echo lang('sales_register')." - ".lang('sales_edit_sale'); ?> CGATE <?php echo $sale_info['sale_id']; ?></td>
					</tr>
				</table>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div id="edit_sale_wrapper" class="col-md-10">
			<?php echo form_open("$controller_name/save_sales/$office/".$sale_info['order_id'],array('class' => 'form-horizontal', 'role' => 'form','id'=>'sales_edit_form')); ?>
			<ul id="error_message_box"></ul>

			<div class="form-group">
			    <label for="sales_receipt" class="col-sm-4 control-label">
			        <?php echo form_label(lang('sales_receipt') . ':', 'sales_receipt'); ?>
			    </label>
			    <div class="col-sm-8">
			        <?php echo anchor($controller_name.'/receipt/'.$office.'/'.$sale_info['order_id'], 'CGATE '.$sale_info['order_id'], array('target' => '_blank'));?>
			    </div>
			</div>

			<div class="form-group">
			    <label for="sales_date" class="col-sm-4 control-label">
			        <?php echo form_label(lang('sales_date') . ':', 'date'); ?>
			    </label>
			    <div class="col-sm-8">
			        <?php echo form_input(array('name'=>'date','value'=>date(get_date_format(), strtotime($sale_info['sale_time'])), 'id'=>'date', 'class' => 'form-control', 'style'=>'width: 80%'));?>
			    </div>
			</div>

			<div class="form-group">
			    <label for="sales_customer" class="col-sm-4 control-label">
			        <?php echo form_label(lang('sales_customer') . ':', 'customer'); ?>
			    </label>
			    <div class="col-sm-8">
			        <?php echo form_dropdown('customer_id', $customers, $sale_info['customer_id'], 'id="customer_id" class="form-control"');?>
					<?php //if ($sale_info['customer_id']) { ?>
						<?php //echo anchor($controller_name .'/email_receipt/'.$office.'/'.$sale_info['order_id'], lang('sales_email_receipt'), array('id' => 'email_receipt'));?>
					<?php //}?>
			    </div>
			</div>

			<?php 
			if ($controller_name == "tickets") { ?>
			<div class="form-group">
			    <label for="sales_commissioner" class="col-sm-4 control-label">
			        <?php echo form_label(lang('sales_commissioner') . ':', 'commissioner'); ?>
			    </label>
			    <div class="col-sm-8">
			        <?php echo form_dropdown('commissioner_id', $commissioners, $sale_info['commissioner_id'], 'id="commissioner_id" class="form-control"');?>
			    </div>
			</div>
			<div class="form-group">
			    <label for="sales_price_commissioner" class="col-sm-4 control-label">
			        <?php echo form_label(lang('sales_price_commissioner') . ':', 'commissioner_price'); ?>
			    </label>
			    <div class="col-sm-8">
			        <?php echo form_input(array('name'=>'commissioner_price','value'=>$sale_info['commision_price'], 'id'=>'commissioner_price', 'class' => 'form-control', 'style'=>'width: 80%'));?>
			    </div>
			</div>
			<?php }
			?>
			<div class="form-group">
                            <?php 
                                if($controller_name == "massages"){
                                
                                }  else {
                            ?>
			    <label for="sales_deposit" class="col-sm-4 control-label">
			        <?php echo form_label(lang('sales_deposit') . ':', 'deposit'); ?>
			    </label>
			    <div class="col-sm-8">
			        <?php echo form_input(array('name'=>'deposit_price','value'=>$sale_info['deposit'], 'id'=>'deposit_price', 'class' => 'form-control', 'style'=>'width: 80%'));?>
			    </div>
                                <?php };?>
			</div>

			<div class="form-group">
			    <label for="sales_employee" class="col-sm-4 control-label">
			        <?php echo form_label(lang('sales_employee') . ':', 'employee'); ?>
			    </label>
			    <div class="col-sm-8">
			        <?php echo form_dropdown('employee_id', $employees, $sale_info['employee_id'], 'id="employee_id" class="form-control"');?>
			    </div>
			</div>

			<!-- <div class="form-group"> -->
			    <!-- <label for="sales_comments_receipt" class="col-sm-4 control-label"> -->
			        <?php //echo form_label(lang('sales_comments_receipt') . ':', 'sales_comments_receipt'); ?>
			    <!-- </label> -->
			    <!-- <div class="col-sm-8"> -->
			        <?php /*echo form_checkbox(array(
								'name'=>'show_comment_on_receipt',
								'id'=>'show_comment_on_receipt',
								'value'=>'1',
								'checked'=>(boolean)$sale_info['show_comment_on_receipt'])
							);*/
					?>
			    <!-- </div> -->
			<!-- </div> -->

			<div class="form-group">
			    <label for="sales_comment" class="col-sm-4 control-label">
			        <?php echo form_label(lang('sales_comment') . ':', 'comment'); ?>
			    </label>
			    <div class="col-sm-8">
			        <?php echo form_textarea(array('name'=>'comment','value'=>$sale_info['comment'],'rows'=>'4','cols'=>'23', 'id'=>'comment', 'class' => 'form-control'));?>
			    </div>
			</div>

	           <?php
				echo form_submit(array(
					'name'=>'submitSaleEditForm',
					'id'=>'submit',
					'value'=>lang('common_submit'),
					'class'=>'submit_button float_right btn btn-primary')
				);
				?>
	          <?php 
	            echo form_close();
	          ?>
	          <?php if ($sale_info['deleted'])
				{
				?>
				<?php echo form_open("$controller_name/undelete/$office/".$sale_info['order_id'],array('id'=>'sales_undelete_form')); ?>
					<?php
					echo form_submit(array(
						'name'=>'submitUndeleteSale',
						'id'=>'submit',
						'value'=>lang('sales_undelete_entire_sale'),
						'class'=>'submit_button float_right btn btn-primary')
					);
					?>
				</form>
				<?php
				}
				else
				{
				?>
				<?php 
				 if ($this->Employee->has_module_action_permission('sales', 'edit_sale', $this->Employee->get_logged_in_employee_info()->employee_id)){
				?>
				<?php
				echo form_open("$controller_name/change_sale/$office/".$sale_info['order_id'],array('id'=>'sales_change_form')); ?>
					<?php
					echo form_submit(array(
						'name'=>'submit',
						'id'=>'submit',
						'value'=>lang('sales_change_sale'),
						'class'=>'change_button float_right btn btn-primary')
					);
				}
					?>
				</form>
				<?php echo form_open("$controller_name/delete_entire_sale/$office/".$sale_info['order_id'],array('id'=>'sales_delete_form')); ?>
					<?php
					echo form_submit(array(
						'name'=>'submitDeleteEntireSale',
						'id'=>'submit',
						'value'=>lang('sales_delete_entire_sale'),
						'class'=>'delete_button float_right btn btn-primary')
					);
					?>
				</form>
				<?php
				}
				?>

		</div>
    </div>
</div>

<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$('#date').datePicker({startDate: '<?php echo get_js_start_of_time_date(); ?>'});

// $(document).ready(function()
// {	
// 	alert("hii");
// 	jQuery("#email_receipt").click(function()
// 	{
// 		$.get($(this).attr('href'), function()
// 		{
// 			alert("<?php echo lang('sales_receipt_sent'); ?>")
// 		});
		
// 		return false;
// 	});

// 	jQuery("body").on("click", "#email_receipt", function(){
// 		$.get($(this).attr('href'), function()
// 		{
// 			alert("<?php echo lang('sales_receipt_sent'); ?>")
// 		});
		
// 		return false;
// 	});


// 	$('#date').datePicker({startDate: '<?php echo get_js_start_of_time_date(); ?>'});
// 	$("#sales_delete_form").submit(function()
// 	{
// 		if (!confirm('<?php echo lang("sales_delete_confirmation"); ?>'))
// 		{
// 			return false;
// 		}
// 	});
	
// 	$("#sales_undelete_form").submit(function()
// 	{
// 		if (!confirm('<?php echo lang("sales_undelete_confirmation"); ?>'))
// 		{
// 			return false;
// 		}
// 	});
	
// 	$('#sales_edit_form').validate({
// 		submitHandler:function(form)
// 		{
// 			$(form).ajaxSubmit({
// 			success:function(response)
// 			{
// 				if(response.success)
// 				{
// 					set_feedback(response.message,'success_message',false);
// 				}
// 				else
// 				{
// 					set_feedback(response.message,'error_message',true);	
					
// 				}
// 			},
// 			dataType:'json'
// 		});

// 		},
// 		errorLabelContainer: "#error_message_box",
//  		wrapper: "li",
// 		rules: 
// 		{
//    		},
// 		messages: 
// 		{
// 		}
// 	});
// });
</script>