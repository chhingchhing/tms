<div class="panel panel-default" >
<div class="col-xs-12 col-sm-12">
        <?php
        echo form_open_multipart('offices/save/', array('id' => 'config_form', 'class' => 'form-horizontal', 'role' => 'form'));
        ?>
        <legend><?php echo lang("config_info"); ?></legend>

        <div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>

        <div class="col-xs-12 col-sm-11">
            <?php $this->load->view("partial/show_sms"); ?>
        </div>

        <div style="display: none;">
                <?php
                echo form_input(array(
                    'name' => 'office_id',
                    'id' => 'office_id',
                    'disabple' => 'disabple',
                    'value' => $office_info->office_id)
                );
                ?>
            </div>
            <div class="form-group">
		<?php echo form_label($this->lang->line('offices_name') . ':', 'office_name', array('class' => 'col-sm-2 control-label required')); ?>
		        <div class='col-sm-10'>
		        <?php
		        echo form_input(array(
                        'name' => 'office_name',
                        'id' => 'office_name',
                        'value' => $office_info->office_name)
                    );
		        ?>
		        </div>
		    </div>


        <?php 
        /**
        *Config on details of each office
        */
        $this->load->view("offices/_form");
        ?>


        <div class="form-group">	
            <div class='col-sm-2'></div>
            <div class='col-sm-10'>
            <?php
            echo form_submit(array(
                'name' => 'submitf',
                'id' => 'btnSubmit',
                'value' => lang('common_submit'),
                'class' => 'submit_button float_right btn btn-primary',
                'role' => 'button'
                    )
            );
            ?>
            </div>
        </div>
<?php
echo form_close();
?>
    </div>
    <!-- <div id="feedback_bar" style="top: 1550px;"></div> -->
</div>