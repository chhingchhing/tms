<?php $this->load->view("partial/header"); ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <div class="row" id="title_bar">
            <div class="col-md-9" id="title">
                <img src='<?php echo base_url() ?>images/menubar/<?php echo $controller_name; ?>.png' alt='title icon' />
                <?php echo lang('common_list_of') . ' ' . lang('module_' . $controller_name); ?>

            </div>
            <div class="col-md-3" id="title_search">
                <?php echo form_open($this->uri->segment(2)=='list_package' ? "$controller_name/search_package" : "$controller_name/search", array('id' => 'search_form')); ?>
                <input type="text" name ='search' id='<?php echo $this->uri->segment(2)=="list_package" ? "search_package" : "search" ?>' class="form-control"/>
                <span class="glyphicon glyphicon-search"></span>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- Panel content -->
        <div class="row" id="contents">
            <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">

                <div class="list-group">
                    <?php echo anchor($controller_name, lang('tours_list_view'), array('class' => 'list-group-item none glyphicon glyphicon-list-alt')); ?>
                    <?php
                    echo anchor("#$controller_name/viewJSON/", lang('common_tour'), array('class' => 'list-group-item edit glyphicon glyphicon-plus-sign', 'id' => 'add_tour', 'modals' => "tours"));
                    $this->load->view('tours/form.php');
                    
                    echo anchor("$controller_name/sales/".$this->uri->segment(3), lang('tour_sale'), array('class' => 'list-group-item none import glyphicon glyphicon-shopping-cart'));
                    ?>
                    <?php
                    echo anchor("$controller_name/excel_export", lang('common_excel_export'), array('class' => 'list-group-item none import glyphicon glyphicon-circle-arrow-left'));
                    ?>
                    <?php echo anchor("$controller_name/delete", $this->lang->line("common_delete"), array('id' => 'delete', 'class' => 'list-group-item delete_inactive glyphicon glyphicon-trash')); ?>
                </div>

                <div class="list-group">
                    <?php echo anchor($controller_name."/list_package", lang('tours_list_view_package'), array('class' => 'list-group-item none glyphicon glyphicon-list-alt')); ?>
                    <?php
                    echo anchor("tours/view_package", lang('tours_new_tour_package'),
                            array('class' => 'new list-group-item glyphicon glyphicon-plus-sign', 'title' => lang($controller_name . '_new'), 'modals' => 'tours_package'));

                    $this->load->view('tours/form_package.php');
                    
                    // echo anchor("$controller_name/sales/".$this->uri->segment(3), lang('tour_sale'), array('class' => 'list-group-item none import glyphicon glyphicon-shopping-cart'));
                    ?>
                    <?php
                    echo anchor("$controller_name/excel_export_package", lang('tours_package_excel_export'), array('class' => 'list-group-item none import glyphicon glyphicon-circle-arrow-left'));
                    ?>
                    <?php echo anchor("$controller_name/delete_package", $this->lang->line("common_delete"), array('id' => 'delete', 'class' => 'list-group-item delete_inactive glyphicon glyphicon-trash')); ?>
                </div>

            </div>
            <div class="col-xs-12 col-sm-9">
                <div id="item_table" class="row">
                    <form action="<?php echo $controller_name ?>/delete" method="post" id="manage_form">          
                        <?php echo $manage_table; ?>
                    </form> 
                </div>
                <div id="paggination"><?php echo $pagination; ?></div>
            </div>

        </div>
    </div>
</div>
<div id="feedback_bar"></div>


<?php $this->load->view("partial/footer"); ?>
