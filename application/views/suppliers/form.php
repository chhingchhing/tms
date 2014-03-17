<!-- Modal -->
<div class="modal fade" id="suppliers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang("suppliers_basic_information"); ?></h4>
            </div>

            <div class="modal-body">

                <?php
                echo form_open('suppliers/save', array('id' => 'supplier_frm', 'class' => 'add_update supplier_frm', 'role' => 'form', 'class' => 'form-horizontal'));
                ?>
                <div id="error"><div id="getSmsError"></div><span class="cross">X</span></div>
                <div id="required_fields_message"><b>*</b><?php echo lang('common_fields_required_message'); ?></div>
                <?php echo form_hidden("baseURL", base_url()); ?>
                <div class="field_row clearfix">
                    <label for="company_name" class="col-sm-4 control-label required">
                        <?php echo form_label(lang('suppliers_company_name') . ':', 'company_name', array('class' => 'required')); ?>
                    </label>
                    <div class="form_field">
                        <?php
                        echo form_input(array(
                            'name' => 'company_name',
                            'id' => 'company_name_input',
                            'value' => $person_info->company_name)
                        );
                        ?>
                    </div>
                </div>
                <div class="field_row clearfix">
                    <label for="account_number" class="col-sm-4 control-label">
                        <?php echo form_label(lang('suppliers_account_number') . ':', 'account_number'); ?>
                    </label>
                    <div class="form_field">
                        <?php
                        echo form_input(array(
                            'name' => 'account_number',
                            'id' => 'account_number',
                            'value' => $person_info->account_number)
                        );
                        ?>
                    </div>
                </div>
                <?php $this->load->view("people/form_basic_info"); ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close'); ?></button>
                <?php
                echo form_input(array(
                    'name' => 'btnSubmitSupplier',
                    'id' => 'btnSubmit',
                    'value' => lang('common_submit'),
                    'class' => 'submit_button float_right btn btn-primary',
                    'size' => '5',
                    'role' => 'button'
                        )
                );
                ?>

                <?php
                echo form_close();
                ?>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal