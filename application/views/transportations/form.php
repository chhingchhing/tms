<!-- Modal -->
<div class="modal fade" id="transportations" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang("transport_infor"); ?></h4>
                <h4 class="modal-title" id="myModalLabel"><?php echo validation_errors(); ?></h4>
            </div>

            <div class="modal-body">
                <?php
                echo form_open('transportations/save', array('id' => 'transportations_form','method' => 'post', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal'));
                ?>

                <!--Message show-->
                <div id="error"><div id="getSmsError"></div><span class="cross">X</span>
                     <?php echo form_hidden("baseURL", base_url()); ?>
                    <?php echo form_hidden("controller_name", $controller_name); ?>
                </div>
                
                <div id="required_fields_message"><?php echo '<b>*</b>'.lang('common_fields_required_message'); ?></div>
                <ul id="error_message_box"></ul>
                <div style="display: none;">
                    <?php
                    echo form_input(array(
                        'name' => 'transport_id',
                        'id' => 'transport_id',
                        'disabple' => 'disabple',
                        'value' => $person_info->transport_id)
                    );
                    ?>
                </div>

                <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">
                        <?php echo form_label(lang('transport_company_names') . ':', 'company_name', array('class' => 'required')); ?>
                    </label>
                    <div class="col-sm-8">

                        <?php
                        echo form_input(array(
                            'name' => 'company_name',
                            'id' => 'company_name',
                            'required' => 'required',
                            'value' => '')
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="taxi_fname" class="col-sm-4 control-label">
                        <?php echo form_label(lang('transports_taxi_fname') . ':', 'taxi_fname', array('class' => 'required')); ?>
                    </label>
                    <div class="col-sm-8">

                        <?php
                        echo form_input(array(
                            'name' => 'taxi_fname',
                            'id' => 'taxi_fname',
                            'required' => 'required',
                            'value' => '')
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="taxi_lname" class="col-sm-4 control-label">
                        <?php echo form_label(lang('transports_taxi_lname') . ':', 'taxi_lname', array('class' => 'required')); ?>
                    </label>
                    <div class="col-sm-8">

                        <?php
                        echo form_input(array(
                            'name' => 'taxi_lname',
                            'id' => 'taxi_lname',
                            'required' => 'required',
                            'value' => '')
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone" class="col-sm-4 control-label">
                        <?php echo form_label(lang('transport_phones') . ':', 'phone'); ?>
                    </label>
                    <div class="col-sm-8">

                        <?php
                        echo form_input(array(
                            'name' => 'phone',
                            'id' => 'phone',
                            'value' => '')
                        );
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="bike_types" class="col-sm-4 control-label">
                        <?php echo form_label(lang('transports_vehicle') . ':', 'vehicle'); ?>
                    </label>
                    <div class="col-sm-8">
                        <?php $vehicle = array("Please select one" => "Please select one", "bus" => "Bus", "ven" => "Ven", "tuk tuk" => "Tuk Tuk", "taxi" => "Taxi", "car" => "Car"); ?>
                        <?php echo form_dropdown("vehicle_type", $vehicle); ?>
                       
                    </div>
                </div>
              
                <div class="form-group">
                    <label for="mark" class="col-sm-4 control-label">
                        <?php echo form_label(lang('transport_marks') . ':', 'mark'); ?>
                    </label>
                    <div class="col-sm-8">

                        <?php
                        echo form_textarea(array(
                            'name' => 'mark',
                            'id' => 'mark',
                            'value' => '')
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close');?></button>
                <?php
                echo form_submit(array(
                    'name' => 'btnSubmitTransport',
                    'id' => 'submit_transport',
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

