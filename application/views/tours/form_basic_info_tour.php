<div class="form-group">
    <label for="tour_name" class="col-sm-4 control-label">
        <?php echo form_label(lang('tours_tour_name') . ':', 'tour_name', array('class' => 'required')); ?>
    </label>
    <div style="display: none;">
        <?php
        echo form_input(array(
            'name' => 'tour_id',
            'id' => 'tour_id',
            'value' => $tour_info->tour_id)
        );
        ?>
    </div>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'tour_name',
            'id' => 'tour_name',
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
        echo form_dropdown('destinationID', $destination_id, $tour_info->destinationID);
        ?>
        or
        <?php echo form_input(array(
            'name'=>'newDes',
            'id'=>'newDes',
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
            echo form_dropdown("supplier_id", $supplierId, $tour_info->supplier_id);
            ?>
            
	</div>
</div>
<div class="form-group">
    <label for="by" class="col-sm-4 control-label">
        <?php echo form_label(lang('tour_by') . ':', 'by'); ?>
    </label>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'by',
            'id' => 'by_tour',
            'value' => $tour_info->by)
        );
        ?>
    </div>
</div>

<div class="form-group">
    <label for="departure_date" class="col-sm-4 control-label">
        <?php echo form_label(lang('tour_departure_date') . ':', 'departure_date'); ?>
    </label>
    <div class="col-sm-8">
        <div class="bfh-datepicker" data-format="d-m-y" data-date="<?php echo (isset($tour_info->departure_date) && $tour_info->departure_date) ? date('d-m-Y', strtotime($tour_info->departure_date)) : 'today'; ?>">
        </div>
    </div>
</div>

<div class="form-group">
    <label for="departure_time" class="col-sm-4 control-label">
        <?php echo form_label(lang('sales_time_departure') . ':', 'departure_time'); ?>
    </label>
    <div class="col-sm-8">

        <div class="bfh-timepicker" data-time="<?php echo $tour_info->departure_time ? $tour_info->departure_time : "00:00" ?>">
            <div class="input-prepend bfh-timepicker-toggle" data-toggle="bfh-timepicker">
                <span class="add-on"><i class="icon-time"></i></span>
                <input type="text" class="input-medium" readonly>
            </div>
            <div class="bfh-timepicker-popover">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="hour">
                                <a class="next" href="#"><i class="icon-chevron-up"></i></a><br>
                                <input type="text" class="input-mini" readonly><br>
                                <a class="previous" href="#"><i class="icon-chevron-down"></i></a>
                            </td>
                            <td class="separator">:</td>
                            <td class="minute">
                                <a class="next" href="#"><i class="icon-chevron-up"></i></a><br>
                                <input type="text" class="input-mini" readonly><br>
                                <a class="previous" href="#"><i class="icon-chevron-down"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

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
            'value' => $tour_info->description)
        );
        ?>
    </div>
</div> 

