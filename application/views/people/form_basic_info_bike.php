<div class="form-group">
        <?php echo form_label(lang('bikes_bike_code') . ':', 'bike_code', array('class' => 'col-sm-4 control-label required')); ?>
    <div class="col-sm-8">

        <?php
        echo form_input(array(
            'name' => 'bike_code',
            'id' => 'bike_code',
            'class'=>'form-control',
            'value' =>$bike_info->bike_code)
        );
        ?>
    </div>
</div>

<div class="form-group">
<?php echo form_label(lang('bikes_actual_price') . ':', 'actual_price', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
<?php
echo form_input(array(
    'name' => 'actual_price',
    'id' => 'actual_price',
    'class'=>'form-control',
    'value' => $bike_info->actual_price)
);
?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label(lang('bikes_unit_price') . ':', 'unit_price', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
<?php
echo form_input(array(
    'name' => 'unit_price',
    'id' => 'unit_price',
    'class'=>'form-control',
    'value' => $bike_info->unit_price)
);
?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label(lang('bikes_bike_types') . ':', 'bike_types', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php $bike_types = array("Please select one" => "Please select one", "Giant" => "Giant", "Khmer Bike" => "Khmer Bike"); ?>
        <?php echo form_dropdown("bike_types", $bike_types, $bike_info->bike_types, "class = 'form-control'"); ?>
       
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_supplier') . ':', 'supplier', array('class' => 'col-sm-4 control-label required')); ?>
    <div class="col-sm-8">
        <?php
        echo form_dropdown('supplier', $suppliers, $bike_info->supplierID, 'class = "form-control"');
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_description') . ':', 'sale_price', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php
        echo form_hidden("descriptions", $ticket_info->descriptions);
        echo form_textarea(array(
            'name' => 'description',
            'id' => 'description',
            'class'=>'form-control',
            'value' => $bike_info->description)
        );
        ?>
    </div>
</div>
