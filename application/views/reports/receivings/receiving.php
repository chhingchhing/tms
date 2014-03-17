<div id='TB_load'><img src='<?php echo base_url()?>images/loading_animation.gif'/></div>
<table>
	<tr>
		<td id="register_items_container">
			<table id="title_section">
				<tr>
					<td id="title_icon">
						<img src='<?php echo base_url()?>images/menubar/receivings.png' alt='title icon' />
					</td>
					<td id="title">
						<?php echo lang('receivings_register'); ?>
					</td>
					<td id="register_wrapper">
						<?php echo form_open("receivings/change_mode",array('id'=>'mode_form')); ?>
						<span><?php echo lang('receivings_mode') ?></span>
						<?php echo form_dropdown('mode',$modes,$mode, "id='mode'"); ?>
						</form>
					</td>
				</tr>
			</table>
			<div id="reg_item_search">
				<?php echo form_open("receivings/add",array('id'=>'add_item_form')); ?>
					<?php echo form_input(array('name'=>'item','id'=>'item','size'=>'30'));?>
					<div id="new_item_button_register" >
						<?php echo anchor("items/view/-1/width~550",
						"<div class='small_button'><span>".lang('sales_new_item')."</span></div>",
						array('class'=>'thickbox none','title'=>lang('sales_new_item')));?>
					</div>					
				</form>
			</div>
			<div id="register_holder">
			<table id="register">
				<thead>
					<tr>
						<th id="reg_item_del"></th>
						<th id="reg_item_name"><?php echo lang('receivings_item_name'); ?></th>
						<th id="reg_item_price"><?php echo lang('receivings_cost'); ?></th>
						<th id="reg_item_qty"><?php echo lang('receivings_quantity'); ?></th>
						<th id="reg_item_discount"><?php echo lang('receivings_discount'); ?></th>
						<th id="reg_item_total"><?php echo lang('receivings_total'); ?></th>
					</tr>
				</thead>
				<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='6' style="height:60px;border:none;">
<div class='warning_message' style='padding:7px;'><?php echo lang('sales_no_items_in_cart'); ?></div>
</td></tr>
<?php
}
else
{
	foreach(array_reverse($cart, true) as $line=>$item)
	{
		$cur_item_info = $this->Item->get_info($item['item_id']);?>			
		<tr>		
		<td colspan='6'>								
		<?php
		echo form_open("receivings/edit_item/$line");
	?>			<table>
					<tr id="reg_item_top">
						<td id="reg_item_del"><?php echo anchor("receivings/delete_item/$line",lang('common_delete'), array('class' => 'delete_item'));?></td>
						<td id="reg_item_name"><?php echo $item['name']; ?></td>
					<?php if ($items_module_allowed){ ?>
						<td id="reg_item_price"><?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6', 'id' => 'price_'.$line));?></td>
					<?php }else{ ?>
						<td id="reg_item_price"><?php echo $item['price']; ?></td>
					<?php echo form_hidden('price',$item['price']); ?>
					<?php }	?>
						<td id="reg_item_qty">
					<?php echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2', 'id' => 'quantity_'.$line));?>
						</td>
						<td id="reg_item_discount"><?php echo form_input(array('name'=>'discount','value'=>$item['discount'],'size'=>'3', 'id' => 'discount_'.$line));?></td>
						<td id="reg_item_total"><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
					</tr>
					<tr id="reg_item_bottom">
						<td id="reg_item_descrip_label"><?php echo lang('sales_description_abbrv').':';?></td>
						<td id="reg_item_descrip" colspan="5">
					<?php 
						echo $item['description'];
						echo form_hidden('description',$item['description']);
					?>
						</td>
					</tr>		
				</table>
			</form>		
			</td>
		</tr>	
	<?php
	}
}
?>
				</tbody>
			</table>
			</div>
			<div id="reg_item_base"></div>
		</td>
		<td style="width:8px;"></td>
		<td id="over_all_sale_container">
			<div id="overall_sale">
				
				<div id="suspend_cancel">
					<div id="cancel" <?php if(count($cart) > 0){ echo "style='visibility: visible;'";}?>>											
						<?php
						// Only show this part if there are Items already in the sale.
						if(count($cart) > 0){ ?>
							<?php echo form_open("receivings/cancel_receiving",array('id'=>'cancel_sale_form')); ?>
								<div class='small_button' id='cancel_sale_button'>
									<span>
										<?php 
										echo lang('receivings_cancel_receiving'); 
										?>
									</span>
								</div>
							</form>
						<?php } ?>
					</div>
				</div>

				<div id="customer_info_shell">
					<?php
					if(isset($supplier))
					{
						echo "<div id='customer_info_filled'>";
							echo '<div id="customer_name">'.character_limiter($supplier, 25).'</div>';
							echo '<div id="customer_email"></div>';
							echo '<div id="customer_edit">'.anchor("suppliers/view/$supplier_id/width~550", lang('common_edit'),  array('class'=>'thickbox none','title'=>lang('suppliers_update'))).'</div>';
							echo '<div id="customer_remove">'.anchor("receivings/delete_supplier", lang('sales_detach'),array('id' => 'delete_supplier')).'</div>';
						echo "</div>";
					}
					else
					{ ?>
						<div id='customer_info_empty'>
							<?php echo form_open("receivings/select_supplier",array('id'=>'select_supplier_form')); ?>
							<label id="customer_label" for="supplier">
								<?php echo lang('receivings_select_supplier'); ?>
							</label>
							<?php echo form_input(array('name'=>'supplier','id'=>'supplier','size'=>'30','value'=>lang('receivings_start_typing_supplier_name')));?>
							</form>
							<div id="add_customer_info">
								<div id="common_or">
									<?php echo lang('common_or'); ?>
								</div>
								<?php 
									echo anchor("suppliers/view/-1/width~550",
									"<div class='small_button' style='margin:0 auto;'><span>".lang('receivings_new_supplier')."</span></div>",
									array('class'=>'thickbox none','title'=>lang('receivings_new_supplier')));
								?>
							</div>
							<div class="clearfix">&nbsp;</div>
						</div>
					<?php } ?>
				</div>
			
				<div id='sale_details'>
					<table id="sales_items_total">
						<tr>
							<td class="left"><?php echo lang('sales_total'); ?>:</td>
							<td class="right"><?php echo to_currency($total); ?></td>
						</tr>
					</table>
				</div>
				
				<?php
				// Only show this part if there are Items already in the Table.
				if(count($cart) > 0){ ?>

					<div id="finish_sale">
						<?php echo form_open("receivings/complete",array('id'=>'finish_sale_form')); ?>

						<div id="make_payment" >
							<table id="make_payment_table">
								<tr id="mpt_top">
									<td>
										<?php echo lang('sales_payment').':   ';?>
	
										<?php echo form_dropdown('payment_type',$payment_options, $this->config->item('default_payment_type'));?>
									</td>
								</tr>
								<tr id="mpt_bottom">
									<td id="tender" colspan="2">
										<?php echo form_input(array('name'=>'amount_tendered','value'=>'','size'=>'10')); ?>
									</td>
								</tr>
							</table>
						</div>
						
						<label id="comment_label" for="comment"><?php echo lang('common_comments'); ?>:</label>
						<?php echo form_textarea(array('name'=>'comment', 'id' => 'comment', 'value'=>'','rows'=>'4'));?>
						
						<?php echo "<div class='small_button' id='finish_sale_button' style='float:right;margin-top:5px;'><span>".lang('receivings_complete_receiving')."</span></div>"; ?>
					</div>
				</form>
				<?php } ?>

			

			
			</div><!-- END OVERALL-->		
		</td>
	</tr>
</table>
<div id="feedback_bar"></div>

<script type="text/javascript">
<?php
if(isset($error))
{
	echo "set_feedback(".json_encode($error).",'error_message',false);";
}

if (isset($warning))
{
	echo "set_feedback(".json_encode($warning).",'warning_message',false);";
}

if (isset($success))
{
	echo "set_feedback(".json_encode($success).",'success_message',false);";
}
?>
</script>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	var my_ar = new Array ("reg_item_total","reg_item_discount", "reg_item_qty", "reg_item_price", "reg_item_stock", "reg_item_number", "reg_item_name", "reg_item_del"); 				
	for (i=0; i < my_ar.length; i++ ) 
	{					
		my_th = $("th#" + my_ar[i]);
		my_td = $("td#" + my_ar[i]);						
		my_td.each(function (i)
		{
			$(this).width(my_th.width());					
		});
	};
	
	$('a.thickbox, area.thickbox, input.thickbox').each(function(i) 
	{
		$(this).unbind('click');
    });
 	
	tb_init('a.thickbox, area.thickbox, input.thickbox');
	
	$('#add_item_form, #mode_form, #select_supplier_form').ajaxForm({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: receivingsSuccess});
	
	$( "#item" ).autocomplete({
		source: '<?php echo site_url("receivings/item_search"); ?>',
		delay: 10,
		autoFocus: false,
		minLength: 0,
		select: function(event, ui)
		{
 			event.preventDefault();
 			$( "#item" ).val(ui.item.value);
			$('#add_item_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: receivingsSuccess});
		},
		change: function(event, ui)
		{
			if ($(this).attr('value') != '' && $(this).attr('value') != <?php echo json_encode(lang('sales_start_typing_item_name')); ?>)
			{
				$("#add_item_form").ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: receivingsSuccess});
			}
	
    		$(this).attr('value',<?php echo json_encode(lang('sales_start_typing_item_name')); ?>);
		}
	});
	
	$("#cart_contents input").change(function()
	{
		var toFocusId = $(":input[type!=hidden]:eq("+($(":input[type!=hidden]").index(this) + 1) +")").attr('id');
		$(this.form).ajaxSubmit({target: "#register_container",beforeSubmit: receivingsBeforeSubmit, success: function()
		{
			receivingsSuccess();
			setTimeout(function(){$('#' + toFocusId).focus();}, 10);
			
		}
		});
	});
	
	setTimeout(function(){$('#item').focus();}, 10);
	
	$('#item,#supplier').click(function()
    {
    	$(this).attr('value','');
    });

	$('#mode').change(function()
	{
		$('#mode_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: receivingsSuccess});
	});

	$( "#supplier" ).autocomplete({
		source: '<?php echo site_url("receivings/supplier_search"); ?>',
		delay: 10,
		autoFocus: false,
		minLength: 0,
		select: function(event, ui)
		{
			$( "#supplier" ).val(ui.item.value);
			$('#select_supplier_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: receivingsSuccess});			
		}
	});

    $('#supplier').blur(function()
    {
    	$(this).attr('value',<?php echo json_encode(lang('receivings_start_typing_supplier_name')); ?>);
    });

    $("#finish_sale_button").click(function()
    {
    	if (confirm(<?php echo json_encode(lang("receivings_confirm_finish_receiving")); ?>))
    	{
    		$('#finish_sale_form').submit();
    	}
    });

    $("#cancel_sale_button").click(function()
    {
    	if (confirm(<?php echo json_encode(lang("receivings_confirm_cancel_receiving")); ?>))
    	{
			$('#cancel_sale_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: receivingsSuccess});
    	}
    });

	$('.delete_item, #delete_supplier').click(function(event)
	{
		event.preventDefault();
		$("#register_container").load($(this).attr('href'));	
	});

});


function post_item_form_submit(response)
{
	if(response.success)
	{
		$("#item").attr("value",response.item_id);
		$('#add_item_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: receivingsSuccess});
	}
}

function post_person_form_submit(response)
{
	if(response.success)
	{
		$("#supplier").attr("value",response.person_id);
		$('#select_supplier_form').ajaxSubmit({target: "#register_container", beforeSubmit: receivingsBeforeSubmit, success: receivingsSuccess});
	}
}

function receivingsBeforeSubmit(formData, jqForm, options)
{
	$("#finish_sale_button").hide();
	$("#TB_load").show();	
}

function receivingsSuccess(responseText, statusText, xhr, $form)
{
}

</script>