<div class="field_row clearfix">
	<label for="massage_name" class="col-sm-4 control-label">
		<?php echo form_label(lang('common_massage_name').':', 'massage_name',array('class'=>'required')); ?>
	</label>
    <div style="display: none;">
                <?php echo form_hidden("item_massage_id", $massage_info->item_massage_id);?>
    </div>
    <div class="form_field">

	<?php echo form_input(array(
		'name'=>'massage_name',
		'id'=>'massage_name',
		'value'=>$massage_info->massage_name)
	);?>
    </div>
</div>
<div class="field_row clearfix">
	<label for="massage_desc" class="col-sm-4 control-label">
		<?php echo form_label(lang('common_massage_desc').':', 'massage_desc',array('class'=>'required')); ?>
	</label>
	<div class="form_field">
	<?php echo form_input(array(
		'name'=>'massage_desc',
		'id'=>'massage_desc',
		'value'=>$massage_info->massage_desc)
	);?>
	</div>
</div>

<div class="field_row clearfix">
	<label for="supplier_id" class="col-sm-4 control-label">
		<?php echo form_label(lang('common_supplier_id').':', 'supplier_id'); ?>
	</label>
	<div class="form_field">
            <?php 
            echo form_dropdown("supplier_id", $supplierId, $massage_info->supplierID);
            ?>
            
	</div>
</div>

<div class="field_row clearfix">
	<label for="actual_price" class="col-sm-4 control-label">
		<?php echo form_label(lang('common_price_actual').':', 'actual_price'); ?>
	</label>
	<div class="form_field">
	<?php echo form_input(array(
		'name'=>'actual_price',
		'id'=>'actual_price',
		'value'=>$massage_info->actual_price)
	);?>
	</div>
</div>
<div class="field_row clearfix">
	<label for="price_one" class="col-sm-4 control-label">
		<?php echo form_label(lang('common_price_one').':', 'price_one'); ?>
	</label>
	<div class="form_field">
	<?php echo form_input(array(
		'name'=>'price_one',
		'id'=>'price_one',
		'value'=>$massage_info->price_one)
	);?>
	</div>
</div>

<div class="field_row clearfix" style="display: none;">
	<label for="massage_typesID" class="col-sm-4 control-label">
		<?php echo form_label(lang('common_massage_typesID').':', 'massage_typesID'); ?>
	</label>
	<div class="form_field">
            <?php echo form_dropdown("massage_typesID",$supplier_type_Id)?>
	</div>
</div>
