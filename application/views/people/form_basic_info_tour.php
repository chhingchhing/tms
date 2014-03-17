<div class="field_row clearfix">
	<label for="tour_name" class="col-sm-4 control-label">
		<?php echo form_label(lang('tours_tour_name').':', 'tour_name',array('class'=>'required')); ?>
	</label>
    <div style="display: none;">
                <?php echo form_input(array(
		'name'=>'tour_id',
		'id'=>'tour_id',
		'value'=>$person_info->tour_id)
	);?>
    </div>
	<div class="form_field">

	<?php echo form_input(array(
		'name'=>'tour_name',
		'id'=>'tour_name',
		'value'=>$person_info->tour_name)
	);?>
	</div>
</div>
<div class="field_row clearfix">
	<label for="action_name_key" class="col-sm-4 control-label">
		<?php echo form_label(lang('tours_action_name_key').':', 'action_name_key'); ?>
	</label>
	<div class="form_field">
	<?php echo form_input(array(
		'name'=>'action_name_key',
		'id'=>'action_name_key',
		'value'=>$person_info->action_name_key)
	);?>
	</div>
</div>
<div class="field_row clearfix">
	<label for="sort" class="col-sm-4 control-label">
		<?php echo form_label(lang('tours_sort').':', 'sort'); ?>
	</label>
	<div class="form_field">
	<?php echo form_input(array(
		'name'=>'sort',
		'id'=>'sort',
		'value'=>$person_info->sort)
	);?>
	</div>
</div>

