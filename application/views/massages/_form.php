<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang("massages_basic_information"); ?></h4>
        </div>
        <div class="modal-body">
            <?php
            if ($controller_name == "massages" && $this->uri->segment(2) == "sales") {
                echo form_open('massages/saved/' . $massage_info->item_massage_id, array('id' => 'massage_form', 'post', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal'));
            } else {
                echo form_open('massages/save/' . $massage_info->item_massage_id, array('id' => 'massage_form', 'post', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal'));
            }
            ?>
            <!--display validation message-->
            <div id="error" style="display: none;"><div id="getSmsError"></div><span class="cross">X</span>
                <?php echo form_hidden("baseURL", base_url()); ?>
            </div>
            <div id="required_fields_message"><b>*</b><?php echo lang('common_fields_required_message'); ?></div>
            <ul id="error_message_box"> </ul>
            <?php $this->load->view("people/form_basic_info_massage"); ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close'); ?></button>
            <?php
            if ($controller_name == "massages" && $this->uri->segment(2) == "sales") {
                $btnName = "btn_submit_massages";
            } else {
                $btnName = "submit_sms";
            }
            echo form_submit(array(
                'name' => $btnName,
                'id' => 'submit',
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