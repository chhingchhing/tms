<div class="form-group">
    <label for="tour_name" class="col-sm-4 control-label">
        <?php echo form_label(lang('tours_tour_name') . ':', 'tour_name', array('class' => 'required')); ?>
    </label>
    <div style="display: none;">
        <?php
        echo form_input(array(
            'name' => 'tour_id',
            'id' => 'tour_id',
            'class'=>'form-control',
            'value' => $tour_info->tour_id)
        );
        ?>
    </div>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'tour_name',
            'id' => 'tour_name',
            'class'=>'form-control',
            'value' => $tour_info->tour_name)
        );
        ?>
    </div>
</div>
<div class="form-group">
    <label for="destinationID" class="col-sm-4 control-label">
        <?php echo form_label(lang('tickets_destinationID') . ':', 'destinationID', array('class' => 'required')); ?>
    </label>
    <div class="col-sm-8">
        <?php
        echo form_dropdown('destinationID', $destination_id, $tour_info->destinationID,'class= "form-control"');
        ?>
        or
        <?php echo form_input(array(
            'name'=>'newDes',
            'id'=>'newDes',
            'class'=>'form-control',
            'value'=>'')
             );?>
    </div>
</div>
<div class="form-group">
	 <label for="supplierID" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_supplier') . ':', 'supplierID', array('class' => 'required')); ?>
    </label>
	<div class="col-sm-8">
            <?php 
            echo form_dropdown("supplier_id", $supplierId, $tour_info->supplier_id, 'class = "form-control"');
            ?>
            
	</div>
</div>
<div class="form-group">
    <label for="by" class="col-sm-4 control-label">
        <?php echo form_label(lang('tours_by') . ':', 'by'); ?>
    </label>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'by',
            'id' => 'by_tour',
            'class'=>'form-control',
            'value' => $tour_info->by)
        );
        ?>
    </div>
</div>

<div class="form-group">
    <label for="actual_price" class="col-sm-4 control-label">
        <?php echo form_label(lang('tickets_actual_price') . ':', 'actual_price'); ?>
    </label>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'actual_price',
            'id' => 'actual_price',
            'class'=>'form-control',
            'value' => $tour_info->actual_price)
        );
        ?>
    </div>
</div>
<div class="form-group">
    <label for="sale_price" class="col-sm-4 control-label">
        <?php echo form_label(lang('tickets_sale_price') . ':', 'sale_price'); ?>
    </label>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'sale_price',
            'id' => 'sale_price',
            'class'=>'form-control',
            'value' => $tour_info->sale_price)
        );
        ?>
    </div>
</div>
<div class="form-group">
    <label for="description" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_description') . ':', 'description'); ?>
    </label>
    <div class="col-sm-8">
        <?php
        echo form_textarea(array(
            'name' => 'description',
            'id' => 'description',
            'class'=>'form-control',
            'value' => $tour_info->description)
        );
        ?>
    </div>
</div> 

