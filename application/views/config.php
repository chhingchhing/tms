<?php $this->load->view("partial/header"); ?>

<div class="panel panel-info">
    <div class="panel-heading">
        <div class="row" id="title_bar">
            <div class="col-md-8" id="title_icon">
                <img src='<?php echo base_url() ?>images/menubar/<?php echo $controller_name; ?>.png' alt='title icon' />
                <?php echo lang('module_' . $controller_name); ?>
            </div>
            <div class="col-md-4" id="dbBackup">
                <?php echo anchor('config/backup', lang('config_backup_database'), array('class' => 'glyphicon glyphicon-hdd dbBackup')); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo anchor('config/optimize', lang('config_optimize_database'), array('class' => 'glyphicon glyphicon-check dbOptimize')); ?>
                 <!--<img src="images/loading.gif" alt="loading..." id="optimize_loading" />--> 
            </div>	
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <?php
                echo form_open_multipart('config/save/', array('id' => 'config_form', 'class' => 'form-horizontal', 'role' => 'form'));
                ?>
                <legend><?php echo lang("config_info"); ?></legend>

                <div id="required_fields_message"><?php echo lang('common_fields_required_message'); ?></div>

                <div class="col-xs-12 col-sm-11">
                    <?php $this->load->view("partial/show_sms"); ?>
                </div>

                <?php 
                /**
               * Config on details of each office
                */
                $this->load->view("partial/config_path");
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
                <div>
                </div>
            </div>
            <!-- <div id="feedback_bar" style="top: 1550px;"></div> -->
        </div>
    </div>
</div>

<?php $this->load->view("partial/footer"); ?>


