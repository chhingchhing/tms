<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang("employees_basic_information"); ?></h4>
        </div>
        <div class="modal-body">
            <?php
            $current_employee_editing_self = $this->Employee->get_logged_in_employee_info()->employee_id == $person_info->employee_id;
            echo form_open('employees/save/' . $person_info->employee_id, array('id' => 'employee_form', 'class'=>'form-horizontal'));
            ?>
            <div id="required_fields_message"><b>*</b><?php echo lang('common_fields_required_message'); ?></div>
            <ul id="error_message_box"></ul>
            <div id="error" style="display: none;"><div id="getSmsError"></div><span class="cross">X</span></div>
            <fieldset id="employee_basic_info">
                <?php $this->load->view("people/form_basic_info"); ?>
            </fieldset>
            <br>
            <fieldset id="employee_login_info">
                <legend><?php echo lang("employees_login_info"); ?></legend>
                <div class="form-group"> 
                    <label for="comments" class="col-sm-4 control-label"> 
                        <?php echo form_label(lang('employees_username') . ':', 'username', array('class' => 'required')); ?>
                    </label>   
                    <div class='col-sm-8'>
                        <?php
                        echo form_hidden("person_id", $person_info->employee_id);
                        echo form_input(array(
                            'name' => 'username',
                            'id' => 'username',
                            'value' => $person_info->username,
                            'class'=>'form-control'));
                        ?>
                    </div>
                </div>

                <?php
                $password_label_attributes = $person_info->employee_id == "" ? array('class' => 'required') : array();
                ?>

                <div class="form-group">
                    <label for="comments" class="col-sm-4 control-label"> 
                        <?php echo form_label(lang('employees_password') . ':', 'password', $password_label_attributes); ?>
                    </label>
                    <div class='col-sm-8'>
                        <?php
                        echo form_password(array(
                            'name' => 'password',
                            'id' => 'password',
                            'class'=>'form-control'
                        ));
                        ?>
                    </div>
                </div>

                <div class="form-group"> 
                    <label for="comments" class="col-sm-4 control-label"> 
                        <?php echo form_label(lang('employees_repeat_password') . ':', 'repeat_password', $password_label_attributes); ?>
                    </label>
                    <div class='col-sm-8'>
                        <?php
                        echo form_password(array(
                            'name' => 'repeat_password',
                            'id' => 'repeat_password',
                            'class'=>'form-control'
                        ));
                        ?>
                    </div>
                </div>
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
            <fieldset id="office_info">
                <legend><?php echo lang("employees_permission_info"); ?></legend>
                <p><?php echo lang("employees_permission_desc_office"); ?></p>

                <ul id="permission_list">
                    <?php
                    foreach ($all_offices->result() as $office) {
                        $checkbox_options = array(
                            'name' => 'offices[]',
                            'value' => $office->office_id,
                            'checked' => $this->Employee->has_office_permission($office->office_id, $person_info->employee_id),
                            'class' => 'module_checkboxes'
                        );

                        if ($current_employee_editing_self && $checkbox_options['checked']) {
                            $checkbox_options['disabled'] = 'disabled';
                            echo form_hidden('offices[]', $office->office_id);
                        }
                        ?>
                        <li>    
                            <?php echo form_checkbox($checkbox_options); ?>
                            <span class="medium"><?php echo ucfirst($office->office_name); ?>:</span>
                            <span class="small"><?php echo ucfirst($office->office_name); ?></span>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            <!-- </fieldset> -->
            <!-- <br> -->
            <!-- <fieldset id="employee_permission_info"> -->
                <p><?php echo lang("employees_permission_desc"); ?></p>

                <ul id="permission_list">
                    <?php
                    foreach ($all_modules->result() as $module) {
                        $checkbox_options = array(
                            'name' => 'permissions[]',
                            'value' => $module->module_id,
                            'checked' => $this->Employee->has_module_permission($module->module_id, $person_info->employee_id),
                            'class' => 'module_checkboxes'
                        );

                        if ($current_employee_editing_self && $checkbox_options['checked']) {
                            $checkbox_options['disabled'] = 'disabled';
                            echo form_hidden('permissions[]', $module->module_id);
                        }
                        ?>
                        <li>    
                            <?php echo form_checkbox($checkbox_options); ?>
                            <span class="medium"><?php echo $this->lang->line('module_' . $module->module_id); ?>:</span>
                            <span class="small"><?php echo $this->lang->line('module_' . $module->module_id . '_desc'); ?></span>
                            <ul>
                                <?php
                                foreach ($this->Module_action->get_module_actions($module->module_id)->result() as $module_action) {
                                    $checkbox_options = array(
                                        'name' => 'permissions_actions[]',
                                        'value' => $module_action->module_id . "|" . $module_action->action_id,
                                        'checked' => $this->Employee->has_module_action_permission($module->module_id, $module_action->action_id, $person_info->employee_id)
                                    );
                                    if ($current_employee_editing_self && $checkbox_options['checked']) {
                                        $checkbox_options['disabled'] = 'disabled';
                                        echo form_hidden('permissions_actions[]', $module_action->module_id . "|" . $module_action->action_id);
                                    }
                                    ?>
                                    <li>
                                        <?php echo form_checkbox($checkbox_options); ?>
                                        <span class="medium"><?php echo $this->lang->line($module_action->action_name_key); ?></span> 
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                    ?>
                </ul>

            </fieldset>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <?php
                echo form_submit(array(
                    'name' => 'submitEmployee',
                    'id' => 'submitEmployee',
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