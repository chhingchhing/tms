<div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<?php
echo form_open('items/save/'.$item_info->item_id,array('id'=>'item_form'));
?>
<fieldset id="item_basic_info">
<legend><?php echo lang("items_basic_information"); ?></legend>

<div class="field_row clearfix">
<?php echo form_label(lang('items_item_number').':', 'name',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'item_number',
		'id'=>'item_number',
		'value'=>$item_info->item_number)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_name').':', 'name',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'name',
		'id'=>'name',
		'value'=>$item_info->name)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_category').':', 'category',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'category',
		'id'=>'category',
		'value'=>$item_info->category)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_supplier').':', 'supplier',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('supplier_id', $suppliers, $selected_supplier);?>
	</div>
</div>

<?php
if ($this->Employee->has_module_action_permission('items','see_cost_price', $this->Employee->get_logged_in_employee_info()->person_id) or $item_info->name=="")
{
?>
<div class="field_row clearfix">
<?php echo form_label(lang('items_cost_price').':', 'cost_price',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'cost_price',
		'size'=>'8',
		'id'=>'cost_price',
		'value'=>$item_info->cost_price)
	);?>
	</div>
</div>
<?php
}
else
{
	echo form_hidden('cost_price', $item_info->cost_price);
}
?>

<div class="field_row clearfix">
<?php echo form_label(lang('items_unit_price').':', 'unit_price',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'unit_price',
		'size'=>'8',
		'id'=>'unit_price',
		'value'=>$item_info->unit_price)
	);?>
	</div>
</div>


<div class="field_row clearfix">
<?php echo form_label(lang('items_promo_price').':', 'promo_price',array('class'=>'wide')); ?>
    <div class='form_field'>
    <?php echo form_input(array(
        'name'=>'promo_price',
        'size'=>'8',
        'id'=>'promo_price',
        'value'=>$item_info->promo_price)
    );?>
    </div>
	<br />
	<table><tr>
	<td><?php echo lang('items_promo_start_date'); ?>: </td>
	<td>
	<div class='form_field'>
	<?php echo form_input(array(
        'name'=>'hdn_start_date',
        'size'=>'8',
        'id'=>'hdn_start_date',
        'style'=>'float:left',
        'value'=>date(get_date_format(), strtotime($item_info->start_date)))
    );?>       
    </div>
	</td>
	<td>
    <div id="promo_end_date">
	<?php echo lang('items_promo_end_date'); ?>: </td><td>
    <div class='form_field'>
    <?php echo form_input(array(
        'name'=>'hdn_end_date',
        'size'=>'8',
        'id'=>'hdn_end_date',
        'style'=>'float:left',
        'value'=>date(get_date_format(), strtotime($item_info->end_date)))
    );?> 
    </div>
	</td>
	</tr></table>
	
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_tax_1').':', 'tax_percent_1',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tax_names[]',
		'id'=>'tax_name_1',
		'size'=>'8',
		'value'=> isset($item_tax_info[0]['name']) ? $item_tax_info[0]['name'] : $this->config->item('default_tax_1_name'))
	);?>
	<?php echo form_input(array(
		'name'=>'tax_percents[]',
		'id'=>'tax_percent_name_1',
		'size'=>'3',
		'value'=> isset($item_tax_info[0]['percent']) ? $item_tax_info[0]['percent'] : $default_tax_1_rate)
	);?>
	%
	<?php echo form_hidden('tax_cumulatives[]', '0'); ?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_tax_2').':', 'tax_percent_2',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tax_names[]',
		'id'=>'tax_name_2',
		'size'=>'8',
		'value'=> isset($item_tax_info[1]['name']) ? $item_tax_info[1]['name'] : $this->config->item('default_tax_2_name'))
	);?>
	<?php echo form_input(array(
		'name'=>'tax_percents[]',
		'id'=>'tax_percent_name_2',
		'size'=>'3',
		'value'=> isset($item_tax_info[1]['percent']) ? $item_tax_info[1]['percent'] : $default_tax_2_rate)
	);?>
	%
	<?php echo form_checkbox('tax_cumulatives[]', '1', isset($item_tax_info[1]['cumulative']) && $item_tax_info[1]['cumulative'] ? (boolean)$item_tax_info[1]['cumulative'] : (boolean)$default_tax_2_cumulative); ?>
    <span class="cumulative_label">
	<?php echo lang('common_cumulative'); ?>
    </span>
	</div>
</div>


<div class="field_row clearfix">
<?php echo form_label(lang('items_quantity').':', 'quantity',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'quantity',
		'id'=>'quantity',
		'value'=> is_numeric($item_info->quantity) ? to_quantity($item_info->quantity) : '')
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_reorder_level').':', 'reorder_level',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'reorder_level',
		'id'=>'reorder_level',
		'value'=>$item_info->reorder_level)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_location').':', 'location',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'location',
		'id'=>'location',
		'value'=>$item_info->location)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_description').':', 'description',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'description',
		'id'=>'description',
		'value'=>$item_info->description,
		'rows'=>'5',
		'cols'=>'17')
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_allow_alt_desciption').':', 'allow_alt_description',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_checkbox(array(
		'name'=>'allow_alt_description',
		'id'=>'allow_alt_description',
		'value'=>1,
		'checked'=>($item_info->allow_alt_description)? 1  :0)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_is_serialized').':', 'is_serialized',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_checkbox(array(
		'name'=>'is_serialized',
		'id'=>'is_serialized',
		'value'=>1,
		'checked'=>($item_info->is_serialized)? 1 : 0)
	);?>
	</div>
</div>

<?php
echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>lang('common_submit'),
	'class'=>'submit_button float_right')
);
?>
</fieldset>
<?php
echo form_close();
?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
    setTimeout(function(){$(":input:visible:first","#item_form").focus();},100);
    
	$('#hdn_start_date').datePicker();
	$('#hdn_end_date').datePicker();
   

	$( "#category" ).autocomplete({
		source: "<?php echo site_url('items/suggest_category');?>",
		delay: 10,
		autoFocus: false,
		minLength: 0
	});

	var submitting = false;

	$('#item_form').validate({
		submitHandler:function(form)
		{
				$.post('<?php echo site_url("items/check_duplicate");?>', {term: $('#name').val()},function(data) {
			<?php if(!$item_info->item_id) {  ?>
			if(data.duplicate)
				{
					
					if(confirm(<?php echo json_encode(lang('items_duplicate_exists'));?>))
						{
								if (submitting) return;
								submitting = true;
								$(form).mask(<?php echo json_encode(lang('common_wait')); ?>);
								$(form).ajaxSubmit({
								success:function(response)
									{
										submitting = false;
										tb_remove();
										post_item_form_submit(response);
									},
									dataType:'json'
									});
						}
					else 
						{
							return false;
						}
				}
			<?php }  else ?>
			 
				{
								if (submitting) return;
								submitting = true;
								$(form).mask(<?php echo json_encode(lang('common_wait')); ?>);
								$(form).ajaxSubmit({
								success:function(response)
									{
										submitting = false;
										tb_remove();
										post_item_form_submit(response);
									},
									dataType:'json'
									});

				}} , "json")
				.error(function() { //alert("an AJAX error occurred!"); 
				});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
		<?php if(!$item_info->item_id) {  ?>
			item_number:
			{
				remote: 
				    { 
					url: "<?php echo site_url('items/item_number_exists');?>", 
					type: "post"
					
				    } 
			},
		<?php } ?>
			name:"required",
			category:"required",
			cost_price:
			{
				required:true,
				number:true
			},

			unit_price:
			{
				required:true,
				number:true
			},
			tax_percent:
			{
				required:true,
				number:true
			},
			quantity:
			{
				required:true,
				number:true
			},
			reorder_level:
			{
				required:true,
				number:true
			}
   		},
		messages:
		{
			<?php if(!$item_info->item_id) {  ?>
			item_number:
			{
				remote: <?php echo json_encode(lang('items_item_number_exists')); ?>
				   
			},
			<?php } ?>
			name:<?php echo json_encode(lang('items_name_required')); ?>,
			category:<?php echo json_encode(lang('items_category_required')); ?>,
			cost_price:
			{
				required:<?php echo json_encode(lang('items_cost_price_required')); ?>,
				number:<?php echo json_encode(lang('items_cost_price_number')); ?>
			},
			unit_price:
			{
				required:<?php echo json_encode(lang('items_unit_price_required')); ?>,
				number:<?php echo json_encode(lang('items_unit_price_number')); ?>
			},
			tax_percent:
			{
				required:<?php echo json_encode(lang('items_tax_percent_required')); ?>,
				number:<?php echo json_encode(lang('items_tax_percent_number')); ?>
			},
			quantity:
			{
				required:<?php echo json_encode(lang('items_quantity_required')); ?>,
				number:<?php echo json_encode(lang('items_quantity_number')); ?>
			},
			reorder_level:
			{
				required:<?php echo json_encode(lang('items_reorder_level_required')); ?>,
				number:<?php echo json_encode(lang('items_reorder_level_number')); ?>
			}

		}
	});
});
</script>