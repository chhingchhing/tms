<div id="required_fields_message"><?php echo lang('items_edit_fields_you_want_to_update'); ?></div>
<ul id="error_message_box"></ul>
<?php
echo form_open('items/bulk_update/',array('id'=>'item_form'));
?>
<fieldset id="item_basic_info">
<legend><?php echo lang("items_basic_information"); ?></legend>
<div class="field_row clearfix">	
<?php echo form_label(lang('items_name').':', 'name',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'name',
		'id'=>'name')
	);?>
	</div>
</div>



<div class="field_row clearfix">	
<?php echo form_label(lang('items_category').':', 'category',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'category',
		'id'=>'category')
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('items_supplier').':', 'supplier',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('supplier_id', $suppliers, '');?>
	</div>
</div>


<div class="field_row clearfix">	
<?php echo form_label(lang('items_cost_price').':', 'cost_price',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'cost_price',
		'size'=>'8',
		'id'=>'cost_price')
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('items_unit_price').':', 'unit_price',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'unit_price',
		'size'=>'8',
		'id'=>'unit_price')
	);?>
	</div>
</div>


<div class="field_row clearfix">
<?php echo form_label(lang('items_promo_price').':', 'promo_price',array('class'=>'wide')); ?>
    <div class='form_field'>
    <?php echo form_input(array(
        'name'=>'promo_price',
        'size'=>'8',
        'id'=>'unit_price')
    );?>
    </div><br>
    <div style="text-align:center;">
            <?php echo lang('items_promo_start_date'); ?>:
            <?php echo form_dropdown('start_month',$months, $selected_start_month, 'id="start_month"'); ?>
            <?php echo form_dropdown('start_day',$days, $selected_start_day, 'id="start_day"'); ?>
            <?php echo form_dropdown('start_year',$years, $selected_start_year, 'id="start_year"'); ?>
            | <?php echo lang('items_promo_end_date'); ?>:
            <?php echo form_dropdown('end_month',$months, $selected_end_month, 'id="end_month"'); ?>
            <?php echo form_dropdown('end_day',$days, $selected_end_day, 'id="end_day"'); ?>
            <?php echo form_dropdown('end_year',$end_years, $selected_end_year, 'id="end_year"'); ?>
            <input type="hidden" id="hdn_start_date" name="start_date" value="" />
            <input type="hidden" id="hdn_end_date" name="end_date" value="" />
    </div>
</div>

	
<div class="field_row clearfix">	
<?php echo form_label(lang('items_tax_1').':', 'tax_percent_1',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'tax_names[]',
		'id'=>'tax_name_1',
		'size'=>'8',
		'value'=> isset($item_tax_info[0]['name']) ? $item_tax_info[0]['name'] : lang('items_sales_tax'))
	);?>

	<?php echo form_input(array(
		'name'=>'tax_percents[]',
		'id'=>'tax_percent_name_1',
		'size'=>'3',
		'value'=> isset($item_tax_info[0]['percent']) ? $item_tax_info[0]['percent'] : '')
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
		'value'=> isset($item_tax_info[1]['name']) ? $item_tax_info[1]['name'] : '')
	);?>

	<?php echo form_input(array(
		'name'=>'tax_percents[]',
		'id'=>'tax_percent_name_2',
		'size'=>'3',
		'value'=> isset($item_tax_info[1]['percent']) ? $item_tax_info[1]['percent'] : '')
	);?>
	%
	<?php echo form_checkbox('tax_cumulatives[]', '1', isset($item_tax_info[1]['cumulative']) && $item_tax_info[1]['cumulative'] ? true : false); ?>
	<span class="cumulative_label">
	<?php echo lang('common_cumulative'); ?>
    </span>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('items_quantity').':', 'quantity',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'quantity',
		'id'=>'quantity',)
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('items_reorder_level').':', 'reorder_level',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'reorder_level',
		'id'=>'reorder_level')
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('items_location').':', 'location',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'location',
		'id'=>'location')
	);?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label(lang('items_description').':', 'description',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'description',
		'id'=>'description',
		'rows'=>'5',
		'cols'=>'17')		
	);?>
	</div>
</div>

<div class="field_row clearfix">

<?php echo form_label(lang('items_allow_alt_desciption').':', 'allow_alt_description',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('allow_alt_description', $allow_alt_desciption_choices);?>

	</div>

</div>



<div class="field_row clearfix">

<?php echo form_label(lang('items_is_serialized').':', 'is_serialized',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_dropdown('is_serialized', $serialization_choices);?>

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
	   $('#start_year,#start_month,#start_day,#end_year,#end_month,#end_day').change(function()
    {
        $("#hdn_start_date").val($("#start_year").val()+'-'+$("#start_month").val()+'-'+$('#start_day').val());
        $("#hdn_end_date").val($("#end_year").val()+'-'+$("#end_month").val()+'-'+$('#end_day').val());
    });

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
			if (submitting) return;
			if(confirm("<?php echo lang('items_confirm_bulk_edit') ?>"))
			{
				//Get the selected ids and create hidden fields to send with ajax submit.
				var selected_item_ids=get_selected_values();
				for(k=0;k<selected_item_ids.length;k++)
				{
					$(form).append("<input type='hidden' name='item_ids[]' value='"+selected_item_ids[k]+"' />");
				}
				
				submitting = true;
				$(form).mask(<?php echo json_encode(lang('common_wait')); ?>);
				$(form).ajaxSubmit({
				success:function(response)
				{
					tb_remove();
					post_bulk_form_submit(response);
					submitting = false;
				},
				dataType:'json'
				});
			}

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			unit_price:
			{
				number:true
			},
			tax_percent:
			{
				number:true
			},
			quantity:
			{
				number:true
			},
			reorder_level:
			{
				number:true
			}
   		},
		messages: 
		{
			unit_price:
			{
				number:<?php echo json_encode(lang('items_unit_price_number')); ?>
			},
			tax_percent:
			{
				number:<?php echo json_encode(lang('items_tax_percent_number')); ?>
			},
			quantity:
			{
				number:<?php echo json_encode(lang('items_quantity_number')); ?>
			},
			reorder_level:
			{
				number:<?php echo json_encode(lang('items_reorder_level_number')); ?>
			}

		}
	});
	
	
	
});


</script>