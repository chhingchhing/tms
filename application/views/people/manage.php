<?php $this->load->view("partial/header"); ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <div class="row" id="title_bar">
            <div class="col-md-9" id="title">
                <img src='<?php echo base_url() ?>images/menubar/<?php echo $controller_name; ?>.png' alt='title icon' />
                <?php echo lang('common_list_of') . ' ' . lang('module_' . $controller_name); ?>
            </div>
            <div class="col-md-3" id="title_search">
                <?php echo form_open("$controller_name/search", array('id' => 'search_form')); ?>
                <input type="text" name ='search' id='search' class="form-control "/>
                <span class="glyphicon glyphicon-search"></span>
            </div>
            </form>
        </div>
    </div>

    <div class="panel-body">
        <!-- Panel content -->
        <div class="row" id="contents">
            <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
                <div class="list-group">
                    <?php echo form_hidden("controller_name", $controller_name); ?>
                    <?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
                        <?php
                        echo anchor("#$controller_name", lang($controller_name . '_new'), array('class' => 'list-group-item add_new glyphicon glyphicon-plus-sign', 'title' => lang($controller_name . '_new'), 'data-toggle' => 'modal', 'data-target' => "#$controller_name"));
                        ?>

                        <?php
                        $this->load->view('customers/form.php');
                        if ($controller_name != "customers") {
                           $this->load->view('employees/form.php');
                        }
                    }
                    ?>

                    <!-- <a class="list-group-item email email_inactive glyphicon glyphicon-envelope" href="<?php //echo current_url() . '#'; ?>" id="email"><?php //echo lang("common_email"); ?></a> -->
                    <?php if ($controller_name == 'customers') { ?>
                        <?php
                        echo anchor("#excel_import", lang('common_excel_import'), array('class' => 'list-group-item thickbox none import glyphicon glyphicon-circle-arrow-right', 'title' => lang('customers_import_customers_from_excel'), 'data-toggle' => 'modal', 'data-target' => "#excel_import"));
                    }
                    ?>
                    <?php
                    if ($controller_name == 'customers' || $controller_name == 'employees') {
                        echo anchor("$controller_name/excel_export", lang('common_excel_export'), array('class' => 'list-group-item none import glyphicon glyphicon-circle-arrow-left'));
                    }

                    // if ($controller_name == 'customers') {
                    //     echo anchor("#excel_import_master", lang('common_excel_import_update'), array('class' => 'list-group-item thickbox none import glyphicon glyphicon-edit', 'title' => lang('customers_import_customers_from_excel'), 'data-toggle' => 'modal', 'data-target' => "#excel_import_master"));
                    // }
                    ?>
                    <?php if ($this->Employee->has_module_action_permission($controller_name, 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) { ?>
                        <?php echo anchor("$controller_name/delete", $this->lang->line("common_delete"), array('id' => 'delete', 'class' => 'list-group-item delete_inactive glyphicon glyphicon-trash')); ?>
                    <?php } ?>

                    <?php if ($controller_name == 'customers' or $controller_name == 'employees') { ?>
                        <?php
                        echo
                        anchor("$controller_name/cleanup", lang($controller_name . "_cleanup_old_customers"), array('id' => 'cleanup',
                            'class' => 'list-group-item cleanup glyphicon glyphicon-user'));
                        ?>
                    <?php } ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9">
                <div id="item_table" class="row">
                    <?php echo $manage_table; ?>
                </div>
                <div id="pagination" class="row">
                    <?php echo $pagination; ?>
                </div>
            </div>
        </div> <!-- End of div class content -->
    </div>
</div>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>

<?php $this->load->view("customers/excel_import"); ?>
<?php $this->load->view("customers/excel_import_update"); ?>


