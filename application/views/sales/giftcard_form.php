<div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<?php
echo form_open('items/save/'.$item_id,array('id'=>'giftcard_form'));

?>
<fieldset id="giftcard_basic_info">
<legend><?php echo lang("giftcards_basic_information"); ?></legend>

<div class="field_row clearfix">
<?php echo form_label(lang('giftcards_giftcard_number').':', 'name',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'description',
		'size'=>'8',
		'id'=>'description'
		)
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label(lang('giftcards_card_value').':', 'name',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'unit_price',
		'size'=>'8',
		'id'=>'unit_price')
	);?>
	</div>
</div>

<div style="display:none">
<?php echo form_input(array(
		'name'=>'item_number',
		'id'=>'item_number',
		'value'=>lang('sales_giftcard'))
	);?>
<?php echo form_input(array(
		'name'=>'name',
		'id'=>'name',
		'value'=>lang('sales_giftcard'))
	);?>
<?php echo form_input(array(
		'name'=>'category',
		'id'=>'category',
		'value'=>lang('sales_giftcard'))
	);?>
<?php echo form_input(array(
		'name'=>'quantity',
		'id'=>'quantity',
		'value'=>1000)
	);?>
<?php echo form_input(array(
		'name'=>'allow_alt_description',
		'id'=>'allow_alt_description',
		'value'=>1)
	);?>
<?php echo form_input(array(
		'name'=>'is_serialized',
		'id'=>'is_serialized',
		'value'=>1)
	);?>
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
    setTimeout(function(){$(":input:visible:first","#giftcard_form").focus();},100);
	var submitting = false;
	$('#giftcard_form').validate({
		submitHandler:function(form)
		{
			if (submitting) return;
			submitting = true;
			$(form).mask(<?php echo json_encode(lang('common_wait')); ?>);
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_giftcard_form_submit(response);
				submitting = false;
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
			description:
			{
				required:true,
				remote: 
				    { 
					url: "<?php echo site_url('giftcards/giftcard_exists');?>", 
					type: "post"
					
				    } 
			},
			unit_price:
			{
				required:true,
				number:true
			}
   		},
		messages:
		{
			description:
			{
				required:<?php echo json_encode(lang('giftcards_number_required')); ?>,
				remote:<?php echo json_encode(lang('giftcards_exists')); ?>
			},
			unit_price:
			{
				required:<?php echo json_encode(lang('giftcards_value_required')); ?>,
				number:<?php echo json_encode(lang('giftcards_value')); ?>
			}
		}
	});
});
</script>