<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang("currency_basic_information"); ?></h4>
            <h4 class="modal-title" id="myModalLabel"><?php echo validation_errors(); ?></h4>
        </div>

        <div class="modal-body">
            <?php
            echo form_open('currency/save/' . $currency_info->currency_id, array('id' => 'currency_form', 'post', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal'));
            ?>
            <!--Ms ticket code-->
            <div id="error" style="display: none;"><div id="getSmsError"></div><span class="cross">X</span>
                <?php echo form_hidden("baseURL", base_url()); ?>
            </div>
            <div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
            <ul id="error_message_box"></ul>
            <div style="display: none;">
                <?php
                echo form_input(array(
                    'name' => 'currency_id',
                    'id' => 'currency_id',
                    'disabple' => 'disabple',
                    'value' => $currency_info->currency_id)
                );
                ?>
            </div>
            <div class="form-group">
                <label for="currency_type_name" class="col-sm-4 control-label">
                    <?php echo form_label(lang('currency_type_name') . ':', 'currency_type_name', array('class' => 'required')); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    echo form_input(array(
                        'name' => 'currency_type_name',
                        'id' => 'currency_type_name',
                        'value' => $currency_info->currency_type_name)
                    );
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="currency_value" class="col-sm-4 control-label">
                    <?php echo form_label(lang('currency_currency_value'), 'currency_value', array('class' => 'required')); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    echo form_input(array(
                        'name' => 'currency_value',
                        'id' => 'currency_value',
                        'value' => $currency_info->currency_value)
                    );
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="currency_symbol" class="col-sm-4 control-label">
                    <?php echo form_label(lang('currency_currency_symbol'), 'currency_symbol'); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    echo form_input(array(
                        'name' => 'currency_symbol',
                        'id' => 'currency_symbol',
                        'value' => $currency_info->symbol)
                    );
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="mark" class="col-sm-4 control-label">
                    <?php echo form_label(lang('common_description') . ':', 'mark'); ?>
                </label>
                <div class="col-sm-8">
                    <?php
                    echo form_hidden("mark", $currency_info->mark);
                    echo form_textarea(array(
                        'name' => 'mark',
                        'id' => 'mark',
                        'value' => $currency_info->mark)
                    );
                    ?>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close');?></button>
            <?php
            if ($controller_name == "currency") {
                $btnName = "submit_currency";
            } else {
                $btnName = "btn_submit_currency";
            }
            echo form_submit(array(
                'name' => $btnName,
                'id' => 'submit_currency',
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