<div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang("sales_commissioner_info"); ?></h4>
                <h4 class="modal-title" id="myModalLabel"><?php echo validation_errors(); ?></h4>
            </div>

            <div class="modal-body">
                <?php
                echo form_open('commissioners/save', array('id' => 'commissioners_form','method'=> 'post', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal'));
                ?>
                <!--Message show-->
                <div id="error" style="display: none;"><div id="getSmsError"></div><span class="cross">X</span>
                    <?php echo form_hidden("baseURL", base_url()); ?>
                    <?php echo form_hidden("controller_name", $controller_name); ?>
                </div>
                
                <div id="required_fields_message"><?php echo '<b>*</b>'.lang('common_fields_required_message'); ?></div>
                <ul id="error_message_box"></ul>
                <div style="display: none;">
                    <?php
                    echo form_input(array(
                        'name' => 'commis_id',
                        'id' => 'commisId',
                        'class'=> 'form-control',
                        'disable' => 'disable',
                        'value' => $commissioner_info->commisioner_id)
                    );
                    ?>
                </div>

                <div class="form-group">
                    <label for="first_name" class="col-sm-4 control-label">
                        <?php echo form_label(lang('common_first_name') . ':', 'first_name', array('class' => 'required')); ?>
                    </label>
                    <div class="col-sm-8">

                        <?php
                        echo form_input(array(
                            'name' => 'first_name',
                            'id' => 'firstname',
                            'class'=> 'form-control',
                            'required' => 'required',
                            'value' => $commissioner_info->first_name)
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="last_name" class="col-sm-4 control-label">
                        <?php echo form_label(lang('common_last_name') . ':', 'last_name', array('class' => 'required')); ?>
                    </label>
                    <div class="col-sm-8">

                        <?php
                        echo form_input(array(
                            'name' => 'last_name',
                            'id' => 'lastname',
                            'class'=> 'form-control',
                            'required' => 'required',
                            'value' => $commissioner_info->last_name)
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone_number" class="col-sm-4 control-label">
                        <?php echo form_label(lang('common_phone_number') . ':', 'phone_number'); ?>
                    </label>
                    <div class="col-sm-8">

                        <?php
                        echo form_input(array(
                            'name' => 'phone_number',
                            'id' => 'phoneNumber',
                            'class'=> 'form-control',
                            'value' => $commissioner_info->tel)
                        );
                        ?>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close');?></button>
                 <?php
                if ($controller_name == "commissioners") {
                    $btnId = "btnSubmitCommissioner";
                } else {
                    $btnId = "submit_commissioners";
                }
                echo form_submit(array(
                    'name' => 'btnCommis',
                    'id' => $btnId,
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