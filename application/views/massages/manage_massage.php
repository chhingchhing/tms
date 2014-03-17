<?php $this->load->view("partial/header"); ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <div class="row" id="title_bar">
            <!--			<div class="col-md-2" id="title_icon">
                                            <img src='<?php echo base_url() ?>images/menubar/<?php echo $controller_name; ?>.png' alt='title icon' />
                                    </div>-->
            <div class="col-md-9" id="title">
                <img src='<?php echo base_url() ?>images/menubar/<?php echo $controller_name; ?>.png' alt='title icon' />
                <?php echo lang('common_list_of') . ' ' . lang('module_' . $controller_name); ?>
            </div>
            <div class="col-md-3" id="title_search">
                <?php echo form_open("$controller_name/search", array('id' => 'search_form')); ?>
                <input type="text" name ='search' id='search' class="form-control"/>
                <span class="glyphicon glyphicon-search"></span>
                <!--<img src='<?php echo base_url() ?>images/spinner_small.gif' alt='spinner' id='spinner' />-->
                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- Panel content -->
        <div class="row" id="contents">
            <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
                <div class="list-group">
                    <?php
                    echo anchor("#massages", lang('common_massage'),
                            // array('class'=>'list-group-item thickbox none new', 'title'=>lang($controller_name.'_new')));
                            array('class' => 'list-group-item glyphicon glyphicon-plus-sign', 'title' => lang($controller_name . '_new'), 'data-toggle' => 'modal', 'data-target' => '#massages'));

                    $this->load->view('massages/form.php');
                    ?>
		    <?php echo anchor("$controller_name/sales/".$this->uri->segment(3),
			lang('massage_sale'),
			array('class' => 'list-group-item glyphicon glyphicon-shopping-cart'));    ?>
                    <?php
                    echo anchor("$controller_name/excel_export", lang('common_excel_export'), array('class' => 'list-group-item none import glyphicon glyphicon-circle-arrow-left'));
                    ?>
                    <?php echo anchor("$controller_name/delete", $this->lang->line("common_delete"), array('id' => 'delete', 'class' => 'list-group-item delete_inactive glyphicon glyphicon-trash')); ?>

                </div>
            </div>
            <div class="col-xs-12 col-sm-9">
                <div id="item_table" class="row">
                    <div id="feedback_bar"><span id="getSmsSuccess"></span><span class="crossing">X</span></div>	
                    <form action="<?php echo $controller_name ?>/delete" method="post" id="manage_form">          
                        <?php echo $manage_table; ?>
                    </form>
                </div>
                <div id="paggination"><?php echo $pagination; ?></div>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view("partial/footer");?>

