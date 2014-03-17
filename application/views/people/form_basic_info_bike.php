<div class="field_row clearfix">
    <label for="bike_code" class="col-sm-4 control-label">
        <?php echo form_label(lang('bikes_bike_code') . ':', 'bike_code', array('class' => 'required')); ?>
    </label>
    <div class="form_field">

        <?php
        echo form_input(array(
            'name' => 'bike_code',
            'id' => 'bike_code',
            'value' =>'')
        );
        ?>
    </div>
</div>

<div class="field_row clearfix">
    <label for="unit_price" class="col-sm-4 control-label">
        <?php echo form_label(lang('bikes_unit_price') . ':', 'unit_price'); ?>
    </label>
    <div class="form_field">
<?php
echo form_input(array(
    'name' => 'unit_price',
    'id' => 'unit_price',
    'value' => '')
);
?>
    </div>
</div>

<div class="field_row clearfix">
    <label for="actual_price" class="col-sm-4 control-label">
<?php echo form_label(lang('bikes_actual_price') . ':', 'actual_price'); ?>
    </label>
    <div class="form_field">
<?php
echo form_input(array(
    'name' => 'actual_price',
    'id' => 'actual_price',
    'value' => '')
);
?>
    </div>
</div>

<div class="field_row clearfix">
    <label for="bike_types" class="col-sm-4 control-label">
        <?php echo form_label(lang('bikes_bike_types') . ':', 'bike_types'); ?>
    </label>
    <div class="form_field">
        <?php $bike_types = array("Please select one" => "Please select one", "Giant" => "Giant", "Khmer Bike" => "Khmer Bike"); ?>
        <?php echo form_dropdown("bike_types", $bike_types); ?>
       
    </div>
</div>
