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
                <input type="text" id="search" name ='search' class="form-control"/>
                <span class="glyphicon glyphicon-search"></span>

                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- Panel content -->
        <div class="row" id="contents" >
            <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
                <div class="list-group">
                    <?php echo anchor("$controller_name", lang('offices_list_view'), array('class' => 'list-group-item none glyphicon glyphicon-list-alt')); ?>
                    <?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->employee_id)) { ?> 
                        <?php
                        echo anchor("offices/view", lang($controller_name . '_new'), array('class' => 'list-group-item glyphicon glyphicon-plus-sign', 'title' => lang($controller_name . '_new') ));
                        ?> 
                    <?php } ?>
                    <?php echo anchor("$controller_name/excel_export", lang('common_excel_export'), array('class' => 'list-group-item none import glyphicon glyphicon-circle-arrow-left')); ?>
                    <?php echo anchor("$controller_name/delete", $this->lang->line("common_delete"), array('id' => 'delete', 'name' => 'delete_ticket', 'class' => 'list-group-item delete_inactive glyphicon glyphicon-trash')); ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9">
                <?php 
                if ($this->uri->segment(2) == "view") {
                    $this->load->view('offices/form.php');
                } else { ?>
                    <div id="item_table" class="row">
                        <?php echo $manage_table; ?>
                    </div>
                    <div id="pagination" class="row">
                        <?php echo $pagination; ?>
                    </div>
                <?php }
                ?>
            </div>
            <!--</div>-->

            <div class="clearboth"></div>
        </div>
    </div> <!-- End of div class content -->
</div>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>