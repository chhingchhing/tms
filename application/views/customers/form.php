<!-- Modal -->
<div class="modal fade" id="customers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang("customers_basic_information"); ?></h4>
            </div>

            <div class="modal-body">
                <?php echo form_open('customers/save/' . $person_info->customer_id, array('id' => 'customer_frm', 'class' => 'add_update', 'role' => 'form', 'class' => 'form-horizontal')); ?>
                <div id="required_fields_message"><b>*</b><?php echo lang('common_fields_required_message'); ?></div>
                <div id="error"><div id="getSmsError"></div><span class="cross">X</span></div>
                <?php echo form_hidden("baseURL", base_url()); ?>
                <?php echo form_hidden("customer_id", $person_info->customer_id); ?>
                <?php $this->load->view("people/form_basic_info"); ?>

                <div class="field_row clearfix">
                    <label for="company_name" class="col-sm-4 control-label">
                        <?php echo form_label(lang('config_company') . ':', 'company_name'); ?>
                    </label>
                    <div class="form_field">
                        <?php
                        echo form_input(array(
                            'name' => 'company_name',
                            'id' => 'customer_company_name',
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
                <div class="field_row clearfix">
                    <label for="hotel_name" class="col-sm-4 control-label">
                        <?php echo form_label(lang('config_hotel') . ':', 'hotel_name'); ?>
                    </label>
                    <div class="form_field">
                        <?php
                        echo form_input(array(
                            'name' => 'hotel_name',
                            'id' => 'customer_hotel_name',
                            'value' => $person_info->hotel_name)
                        );
                        ?>
                    </div>
                </div>
                <div class="field_row clearfix">
                    <label for="room_number" class="col-sm-4 control-label">
                        <?php echo form_label(lang('config_room_number') . ':', 'room_number'); ?>
                    </label>
                    <div class="form_field">
                        <?php
                        echo form_input(array(
                            'name' => 'room_number',
                            'id' => 'customer_room_number',
                            'value' => $person_info->room_number)
                        );
                        ?>
                    </div>
                </div>

                <div class="field_row clearfix">
                    <label for="taxable" class="col-sm-4 control-label"> 
                        <?php echo form_label(lang('customers_taxable') . ':', 'taxable'); ?>
                    </label>
                    <div class="form_field">
                        <ul style="list-style: none;">
                            <li>
                                <?php echo form_checkbox('taxable', '1', $person_info->taxable == '' ? TRUE : (boolean) $person_info->taxable); ?>
                            </li>
                        </ul>
                    </div>
                    <div class="cleared"></div>
                </div>

                <?php if ($person_info->cc_token && $person_info->cc_preview) { ?>
                    <div class="field_row clearfix">
                        <label for="customer_delete_cc_info" class="col-sm-4 control-label"> 
                            <?php echo form_label(lang('customers_delete_cc_info') . ':', 'delete_cc_info'); ?>
                        </label>
                        <div class="form_field">
                            <?php echo form_checkbox('delete_cc_info', '1'); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="modal-footer">

              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close');?></button>
                <?php 
                $btnName = "btn".$this->uri->segment(1)."Customer";
                if ($controller_name == 'customers') {
                  $btnID = "btnSubmit";
                } else {
                  $btnID = "btnSubmitCustomer";
                }
                
                echo form_submit(array(
                  'name'=>$btnName,
                  'id'=>$btnID,
                  'value'=>lang('common_submit'),
                  'class'=>'submit_button float_right btn btn-primary',
                  'role'=>'button'
                  )
                );
                ?>
          		<?php 
          		echo form_close();
          		?>
            </div>
        
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->