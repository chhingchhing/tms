<!-- Modal -->
<div class="modal fade" id="tickets" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang("tickets_basic_information"); ?></h4>
                <h4 class="modal-title" id="myModalLabel"><?php echo validation_errors(); ?></h4>
            </div>

            <div class="modal-body">
                <?php
                echo form_open('tickets/save' . $person_info->person_id, array('id' => 'ticket_form', 'post', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal'));
                ?>

                <!--Ms ticket code-->
                <div id="error"><div id="getSmsError"></div><span class="cross">X</span>
                    <?php echo form_hidden("baseURL", base_url()); ?>
                </div>
                <div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
                <ul id="error_message_box"></ul>
                <div style="display: none;">
                    <?php
                    echo form_input(array(
                        'name' => 'ticket_id',
                        'id' => 'ticket_id',
                        'disabple' => 'disabple',
                        'value' => $person_info->ticket_id)
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
                            'value' => $person_info->ticket_name)
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="destinationID" class="col-sm-4 control-label">
                        <?php echo form_label(lang('tickets_destinationID') . ':', 'destinationID', array('class' => 'required')); ?>
                    </label>
                    <div class="col-sm-8" id="update">
                        <?php echo form_dropdown('destination_id', $destination_id); ?>
                    <br><br>OR 
                        <?php
                        echo form_input(array(
                            'name' => 'destinationID',
                            'id' => 'destinationID',
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
                        echo form_dropdown('supplier', $suppliers);
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ticket_typeID" class="col-sm-4 control-label">
                        <?php echo form_label(lang('tickets_ticket_typeID') . ':', 'ticket_typeID', array('class' => 'required')); ?>
                    </label>
                    <div class="col-sm-8">
                        <?php
                        echo form_dropdown('ticket_typeID', $ticket_type_id);
                        ?> OR 
                        <?php
                        echo form_input(array(
                            'name' => 'input_ticket_type',
                            'id' => 'input_ticket_type',
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
                            'value' => $person_info->actual_price)
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
                            'value' => $person_info->sale_price)
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
                        echo form_hidden("descriptions");
                        echo form_textarea(array(
                            'name' => 'description',
                            'id' => 'description',
                            'value' => '')
                        );
                        ?>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close');?></button>
                <?php
                if ($controller_name == "tickets" && $this->uri->segment(2) == "sales") {
                    $btnName = "btn_submit_tickets";
                } else {
                    $btnName = "submit_ticket";
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
</div><!-- /.modal -->

