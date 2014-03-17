<!-- Modal -->
<div class="modal fade" id="bikes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang("bikes_basic_information"); ?></h4>
            </div>

            <div class="modal-body">
                <?php
//                echo form_open('bikes/get_bike' . $person_info->person_id, array('id' => 'bike_form', 'post', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal'));
               echo form_open('bikes/save', array('id' => 'bikes_form','method' =>'post', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal'));
                ?>
                <!--display validation message-->
                <div id="error"><div id="getSmsError"></div><span class="cross">X</span>
                     <?php echo form_hidden("baseURL", base_url()); ?>
                    <?php echo form_hidden("controller_name", $controller_name); ?>
                </div>
                <div id="required_fields_message"><b>*</b><?php echo lang('common_fields_required_message'); ?></div>
                <ul id="error_message_box"> </ul>
                 <div style="display: none;">
                    <?php
                    echo form_input(array(
                        'name' => 'item_bike_id',
                        'id' => 'item_bike_id',
                        'disable' => 'disable',
                        'value' => $person_info->item_bike_id)
                    );
                    ?>
                </div>
                <?php $this->load->view("people/form_basic_info_bike"); ?>

                <?php if ($person_info->cc_token && $person_info->cc_preview) { ?>
                    <div class="form-group">	
                        <label for="account_number" class="col-sm-4 control-label">
                            <?php echo form_label(lang('customers_delete_cc_info') . ':', 'delete_cc_info'); ?>
                        </label>
                        <div class="col-sm-8">
                            <?php echo form_checkbox('delete_cc_info', '1'); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close');?></button>
                <?php
//                echo form_submit(array(
//                    'name' => 'submit',
//                    'id' => 'submitBike',
//                    'value' => lang('common_submit'),
//                    'class' => 'submit_button float_right btn btn-primary')
//                );
                ?>
                
                <?php
                if ($controller_name == "bikes" && $this->uri->segment(2) == "sales") {
                    $btn_id = "submitBike";
                } else {
                    $btn_id = "saveSubmitBike";
                }
                echo form_submit(array(
                    'name' => 'submit',
                    'id' =>  $btn_id,
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

