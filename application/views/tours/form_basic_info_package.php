<div class="form-group">
    <?php echo form_label(lang('item_kits_add_item') . ':', 'item', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php echo form_input(array(
            'name'=>'item',
            'id'=>'item',
            'class'=>'form-control'
        ));?>
        <?php echo form_hidden("item_id", $item_kit_info->item_kit_id); ?>
    </div>
</div>

<div class="form-group">
    <table id="item_kit_items" class="table">
        <tr>
            <th><?php echo lang('common_delete');?></th>
            <th><?php echo lang('item_kits_item');?></th>
            <th><?php echo lang('item_kits_quantity');?></th>
        </tr>
        
        <?php foreach ($this->Item_kit_items->get_info($item_kit_info->item_kit_id) as $item_kit_item) {?>
            <tr>
                <?php
                $item_info = $this->tour->get_info($item_kit_item->tour_id);
                ?>
                <td><a href="#" onclick='return deleteItemKitRow(this);'>X</a></td>
                <td><?php echo $item_info->tour_name; ?></td>
                <td><input class='form-control input-sm quantity' onchange="calculateSuggestedPrices();" id='item_kit_item_<?php echo $item_kit_item->tour_id ?>' type='text' size='3' name=item_kit_item[<?php echo $item_kit_item->tour_id ?>] value='<?php echo $item_kit_item->quantity ?>'/></td>
            </tr>
        <?php } ?>
    </table>
</div>

<div class="form-group">
    <?php echo form_label(lang('items_item_number') . ':', 'name', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php echo form_input(array(
        'name'=>'item_kit_number',
        'id'=>'item_kit_number',
        'value'=>$item_kit_info->item_kit_number,
        'class'=>"form-control")
    );?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label(lang('item_kits_name') . ':', 'name', array('class' => 'col-sm-4 control-label required')); ?>
    <div class="col-sm-8">
        <?php echo form_input(array(
        'name'=>'name',
        'id'=>'name',
        'value'=>$item_kit_info->name,
        'class'=>'form-control')
    );?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label(lang('items_category') . ':', 'category', array('class' => 'col-sm-4 control-label required')); ?>
    <div class="col-sm-8">
        <?php echo form_input(array(
        'name'=>'category',
        'id'=>'category',
        'value'=>$item_kit_info->category,
        'class'=>'form-control')
    );?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label(lang('items_cost_price') . ':', 'cost_price', array('class' => 'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php echo form_input(array(
        'name'=>'cost_price',
        'id'=>'cost_price',
        'value'=>$item_kit_info->cost_price,
        'class'=>'form-control')
    );?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label(lang('items_unit_price') . ':', 'unit_price', array('class' => 'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php echo form_input(array(
        'name'=>'unit_price',
        'id'=>'unit_price',
        'value'=>$item_kit_info->unit_price,
        'class'=>'form-control')
    );?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label(lang('item_kits_description') . ':', 'description', array('class' => 'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php echo form_textarea(array(
        'name'=>'description',
        'id'=>'description',
        'value'=>$item_kit_info->description,
        'rows'=>'5',
        'cols'=>'17',
        'class'=>'form-control')
    );?>
    </div>
</div>
