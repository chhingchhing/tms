<?php $this->load->view("partial/header"); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<div class="row" id="title_bar">
			<div class="col-md-9" id="title">
                <img src='<?php echo base_url()?>images/menubar/<?php echo $controller_name; ?>.png' alt='title icon' />
				<?php echo lang('common_list_of').' '.lang('module_'.$controller_name); ?>
			</div>
			<div class="col-md-3" id="title_search">
				<?php echo form_open("$controller_name/search",array('id'=>'search_form')); ?>
					<input type="text" name ='search' id='search' class="form-control"/>
                    <span class="glyphicon glyphicon-search"></span>
				<?php echo form_close();?>
			</div>

		</div>
	</div>
	<div class="panel-body">
		<div class="row" id="contents">
			<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
				<div class="list-group">
					<?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				
					
					<?php echo anchor("#$controller_name", lang($controller_name.'_new'),
					array('class'=>'list-group-item glyphicon glyphicon-plus-sign', 'title'=>lang($controller_name.'_new'), 'data-toggle'=>'modal', 'data-target'=>"#$controller_name"));
					?>
					<?php echo anchor("$controller_name/excel_export", lang('common_excel_export'), array('class'=>'list-group-item none import glyphicon glyphicon-circle-arrow-left')); ?>
					<?php } ?>
					
					<?php if ($this->Employee->has_module_action_permission($controller_name, 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) {?>				
					
					<?php //echo anchor("#sendingEmail", $this->lang->line("common_email"), array('id'=>'mail_to', 'class'=>'list-group-item email email_inactive glyphicon glyphicon-envelope', 'data-toggle'=>'modal', 'data-target'=>"#sendingEmail")); ?>
					<?php echo anchor("$controller_name/delete",$this->lang->line("common_delete"),array('id'=>'delete', 'class'=>'list-group-item delete_inactive glyphicon glyphicon-trash')); ?>
					<?php } ?>
				</div>
			</div>
			<!-- <div id="success"><div id="getSmsSuccess"></div><span class="cross">X</span></div> -->
			<div class="col-xs-12 col-sm-9">
				<div id="item_table" class="row">
					<form action="<?php echo $controller_name ?>/delete" method="post" id="manage_form">
						<?php echo $manage_table; ?>
					</form>
				</div>
				<div id="pagination" class="row">
					<?php echo $pagination;?>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="feedback_bar"><span id="getSmsSuccess"></span><span class="crossing">X</span></div>
<div id="feedback_bar_error"><span id="getSmsError"></span><span class="crossing">X</span></div>
<?php $this->load->view("partial/footer"); ?>

<?php $this->load->view('suppliers/form.php'); ?>
<?php $this->load->view('partial/send_email.php'); ?>
