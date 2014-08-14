<div class="form-group">
	<?php echo form_label(lang('common_massage_name').':', 'massage_name',array('class'=>'col-sm-4 control-label required')); ?>
    <?php echo form_hidden("item_massage_id", $massage_info->item_massage_id);?>
    <div class='col-sm-8'>
	<?php echo form_input(array(
		'name'=>'massage_name',
		'id'=>'massage_name',
		'value'=>$massage_info->massage_name,
		'class'=>'form-control')
	);?>
    </div>
</div>

<div class="form-group">
	<?php echo form_label(lang('common_supplier_id').':', 'supplier_id', array('class'=>'col-sm-4 control-label')); ?>
	<div class='col-sm-8'>
        <?php 
        echo form_dropdown("supplier_id", $supplierId, $massage_info->supplierID, 'class="form-control"');
        ?>         
	</div>
</div>

<div class="form-group">
	<?php echo form_label(lang('common_price_actual').':', 'actual_price', array('class'=>'col-sm-4 control-label')); ?>
	<div class='col-sm-8'>
	<?php echo form_input(array(
		'name'=>'actual_price',
		'id'=>'actual_price',
		'value'=>$massage_info->actual_price,
		'class'=>'form-control')
	);?>
	</div>
</div>
<div class="form-group">
	<?php echo form_label(lang('common_price_one').':', 'price_one', array('class'=>'col-sm-4 control-label required')); ?>
	<div class='col-sm-8'>
	<?php echo form_input(array(
		'name'=>'price_one',
		'id'=>'price_one',
		'value'=>$massage_info->price_one,
		'class'=>'form-control')
	);?>
	</div>
</div>

<div class="form-group">
	<?php echo form_label(lang('massages_commission_price_massager').':', 'commission_price_massager', array('class'=>'col-sm-4 control-label')); ?>
	<div class='col-sm-8'>
	<?php echo form_input(array(
		'name'=>'commission_price_massager',
		'id'=>'commission_price_massager',
		'value'=>$massage_info->commission_price_massager,
		'class'=>'form-control')
	);?>
	</div>
</div>

<div class="form-group">
	<?php echo form_label(lang('massages_commission_price_receptionist').':', 'commission_price_receptionist', array('class'=>'col-sm-4 control-label')); ?>
	<div class='col-sm-8'>
	<?php echo form_input(array(
		'name'=>'commission_price_receptionist',
		'id'=>'commission_price_receptionist',
		'value'=>$massage_info->commission_price_receptionist,
		'class'=>'form-control')
	);?>
	</div>
</div>

<div class="form-group">
	<?php echo form_label(lang('massages_outside_staff_fee').':', 'outside_staff_fee', array('class'=>'col-sm-4 control-label')); ?>
	<div class='col-sm-8'>
	<?php echo form_input(array(
		'name'=>'outside_staff_fee',
		'id'=>'outside_staff_fee',
		'value'=>$massage_info->outside_staff_fee,
		'class'=>'form-control')
	);?>
	</div>
</div>

<div class="form-group">
	<?php echo form_label(lang('massages_duration').':', 'duration', array('class'=>'col-sm-4 control-label')); ?>
	<div class='col-sm-8'>
	<?php echo form_input(array(
		'name'=>'duration',
		'id'=>'duration',
		'value'=>$massage_info->duration,
		'class'=>'form-control')
	);?>
	</div>
</div>

<div class="form-group" style="display: none;">
	<?php echo form_label(lang('common_massage_typesID').':', 'massage_typesID', array('class'=>'col-sm-4 control-label')); ?>
	<div class='col-sm-8'>
        <?php echo form_dropdown("massage_typesID",$supplier_type_Id)?>
	</div>
</div>

<div class="form-group">
	<?php echo form_label(lang('common_massage_desc').':', 'massage_desc',array('class'=>'col-sm-4 control-label')); ?>
	<div class='col-sm-8'>
	<?php echo form_textarea(array(
		'name'=>'massage_desc',
		'id'=>'massage_desc',
		'value'=>$massage_info->massage_desc,
		'class'=>'form-control')
	);?>
	</div>
</div>
