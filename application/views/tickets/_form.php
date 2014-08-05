<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang("tickets_basic_information"); ?></h4>
            <h4 class="modal-title" id="myModalLabel"><?php echo validation_errors(); ?></h4>
        </div>

        <div class="modal-body">
            <?php
            echo form_open('tickets/save/' . $ticket_info->ticket_id, array('id' => 'ticket_form', 'post', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal'));
            ?>

            <!--Ms ticket code-->
            <div id="error" style="display: none;"><div id="getSmsError"></div><span class="cross">X</span>
                <?php echo form_hidden("baseURL", base_url()); ?>
            </div>
            <div id="required_fields_message"><?php echo '<b>*</b>'.lang('common_fields_required_message'); ?></div>
            <ul id="error_message_box"></ul>
            <div style="display: none;">
                <?php
                echo form_input(array(
                    'name' => 'ticket_id',
                    'id' => 'ticket_id',
                    'class'=>'form-control',
                    'disabple' => 'disabple',
                    'value' => $ticket_info->ticket_id)
                );
                ?>
            </div>
            <div class="form-group">
                <label for="ticket_name" class="col-sm-4 control-label">
                    <?php echo form_label(lang('tickets_code_name') . ':', 'ticket_name', array('class' => 'required')); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    echo form_input(array(
                        'name' => 'ticket_name',
                        'id' => 'ticket_name',
                        'class'=> 'form-control',
                        'value' => $ticket_info->ticket_name)
                    );
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="destinationID" class="col-sm-4 control-label">
                    <?php echo form_label(lang('tickets_destinationID') . ':', 'destinationID', array('class' => 'required')); ?>
                </label>
                <div class="col-sm-8" id="update">
                    <?php echo form_dropdown('destination_id', $destination_id, $ticket_info->destinationID,'class= "form-control"'); ?>
                    <?php echo lang('common_or'); ?>
                    <?php
                    echo form_input(array(
                        'name' => 'destinationID',
                        'id' => 'destinationID',
                        'class'=> 'form-control',
                        'value' => '')
                    );
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="supplier" class="col-sm-4 control-label">
                    <?php echo form_label(lang('common_supplier') . ':', 'supplier', array('class' => 'required')); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    echo form_dropdown('supplier', $suppliers, $ticket_info->supplierID, 'class= "form-control"');
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="ticket_typeID" class="col-sm-4 control-label">
                    <?php echo form_label(lang('tickets_ticket_typeID') . ':', 'ticket_typeID', array('class' => 'required')); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    echo form_dropdown('ticket_typeID', $ticket_type_id, $ticket_info->ticket_typeID, 'class= "form-control" ');
                    ?> <?php echo lang('common_or'); ?> 
                    <?php
                    echo form_input(array(
                        'name' => 'input_ticket_type',
                        'id' => 'input_ticket_type',
                        'class'=> 'form-control',
                        'value' => '')
                    );
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="actual_price" class="col-sm-4 control-label">
                    <?php echo form_label(lang('tickets_actual_price') . '($):', 'actual_price'); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    echo form_input(array(
                        'name' => 'actual_price',
                        'id' => 'actual_price',
                        'class'=> 'form-control',
                        'value' => $ticket_info->actual_price)
                    );
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="sale_price" class="col-sm-4 control-label">
                    <?php echo form_label(lang('tickets_sale_price') . '($):', 'sale_price', array('class'=>"required")); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    echo form_input(array(
                        'name' => 'sale_price',
                        'required' => 'required',
                        'id' => 'sale_price',
                        'class'=> 'form-control',
                        'value' => $ticket_info->sale_price)
                    );
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-sm-4 control-label">
                    <?php echo form_label(lang('common_description') . ':', 'sale_price'); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    echo form_hidden("descriptions", $ticket_info->descriptions);
                    echo form_textarea(array(
                        'name' => 'description',
                        'id' => 'description',
                        'class'=> 'form-control',
                        'value' => $ticket_info->descriptions)
                    );
                    ?>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close');?></button>
            <?php
            if ($controller_name == "tickets" && $this->uri->segment(2) != "sales") {
                $btnName = "submit_ticket";
            } else {
                $btnName = "btn_submit_tickets";
            }
            echo form_submit(array(
                'name' => $btnName,
                'id' => 'submit_ticket',
                'value' => lang('common_submit'),
                'class' => 'submit_button float_right btn btn-primary')
            );
            ?>
            <?php 
            echo form_close();
            ?>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->