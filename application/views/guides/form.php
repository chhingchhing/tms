<!-- Modal -->
<div class="modal fade" id="guides" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang("sales_guide_info"); ?></h4>
                <h4 class="modal-title" id="myModalLabel"><?php echo validation_errors(); ?></h4>
            </div>

            <div class="modal-body">
                <?php
                echo form_open('guides/save', array('id' => 'guides_form', 'method' => 'post', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal'));
                ?>

                <!--Message show-->
                <div id="error"><div id="getSmsError"></div><span class="cross">X</span>
                    <?php echo form_hidden("baseURL", base_url()); ?>
                    <?php echo form_hidden("controller_name", $controller_name); ?>
                </div>
                
                <div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>
                <ul id="error_message_box"></ul>
                <div style="display: none;">
                    <?php
                    echo form_input(array(
                        'name' => 'guide_id',
                        'id' => 'guideId',
                        'disable' => 'disable',
                        'value' => $person_info->guide_id)
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
                            'id' => 'first_name',
                            'required' => 'required',
                            'value' => '')
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
                            'id' => 'last_name',
                            'required' => 'required',
                            'value' => '')
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-4 control-label">
                        <?php echo form_label(lang('common_email') . ':', 'email'); ?>
                    </label>
                    <div class="col-sm-8">

                        <?php
                        echo form_input(array(
                            'name' => 'email',
                            'id' => 'email',
                            'value' => '')
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="gender" class="col-sm-4 control-label">
                        <?php echo form_label(lang('common_gender') . ':', 'gender'); ?>
                    </label>
                    <div class="col-sm-8">
                        <?php
//                        echo form_dropdown('gender', $gender);
                        $gender = array('Female'=>'Female','Male'=>'Male');
                        echo form_dropdown('gender',$gender);
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="guide_type" class="col-sm-4 control-label">
                        <?php echo form_label(lang('common_guide_type') . ':', 'guide_type'); ?>
                    </label>
                    <div class="col-sm-8">

                        <?php
                        echo form_input(array(
                            'name' => 'guide_type',
                            'id' => 'guide_type',
                            'value' => '')
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
                            'id' => 'phone_number',
                            'value' => '')
                        );
                        ?>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close');?></button>
                <?php
//                echo form_submit(array(
//                    'name' => 'btnSubmitGuide',
//                    'id' => 'submit_guide',
//                    'value' => lang('common_submit'),
//                    'class' => 'submit_button float_right btn btn-primary')
//                );
                ?>
                  <?php
                  
                if ($controller_name == "guides") {
                    $btnGuide = "btnSubmitGuide";
                } else {
                    $btnGuide = "submit_guide";
                }
                
                echo form_submit(array(
                    'name' => 'btnGuide',
                    'id' => $btnGuide,
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

