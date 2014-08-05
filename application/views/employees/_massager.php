<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang("employees_basic_information"); ?></h4>
        </div>
        <div class="modal-body">
            <?php
            $current_employee_editing_self = $this->Employee->get_logged_in_employee_info()->employee_id == $person_info->employee_id;
            echo form_open('massages/save_massager/' . $person_info->employee_id, array('id' => 'massager_form', 'class'=>'form-horizontal'));
            ?>
            <div id="required_fields_message"><b>*</b><?php echo lang('common_fields_required_message'); ?></div>
            <ul id="error_message_box"></ul>
            <div id="error" style="display: none;"><div id="getSmsError"></div><span class="cross">X</span></div>
            <fieldset id="employee_basic_info">
                <?php $this->load->view("people/form_basic_info"); ?>
                <?php echo form_hidden("person_id", $person_info->employee_id); ?>
                <div class="form-group">
                    <?php echo form_label(lang('employees_permission') . ':', 'permission', array('class' => 'col-sm-4 control-label required')); ?>
                    <div class="col-sm-8">
                        <?php
                        echo form_dropdown('position', $positions, $person_info->position_id, 'class= "form-control"');
                        ?>
                    </div>
                </div>
            </fieldset>
            <br>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php
                echo form_submit(array(
                    'name' => 'submitMassager',
                    'id' => 'submitMassager',
                    'value' => lang('common_submit'),
                    'class' => 'btn submit_button float_right btn-primary')
                ); //footer button for form in employee
                ?>

                <?php
                echo form_close();
                ?>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->