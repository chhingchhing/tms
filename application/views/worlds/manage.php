<?php $this->load->view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function() 
{ 
	var table_columns = ['','last_name','first_name','email','phone_number'];
	enable_sorting("<?php echo site_url("$controller_name/sorting"); ?>",table_columns, <?php echo $per_page; ?>);
    enable_select_all();
    enable_checkboxes();
    enable_row_selection();
    enable_search('<?php echo site_url("$controller_name/suggest");?>',<?php echo json_encode(lang("common_confirm_search"));?>);
    enable_email('<?php echo site_url("$controller_name/mailto")?>');
    enable_delete(<?php echo json_encode(lang($controller_name."_confirm_delete"));?>,<?php echo json_encode(lang($controller_name."_none_selected"));?>);
	enable_cleanup(<?php echo json_encode(lang($controller_name."_confirm_cleanup"));?>);
}); 


function post_person_form_submit(response)
{
	if(!response.success)
	{
		set_feedback(response.message,'error_message',true);	
	}
	else
	{
		//This is an update, just update one row
		if(jQuery.inArray(response.person_id,get_visible_checkbox_ids()) != -1)
		{
			update_row(response.person_id,'<?php echo site_url("$controller_name/get_row")?>');
			set_feedback(response.message,'success_message',false);	
			
		}
		else //refresh entire table
		{
			do_search(true,function()
			{
				//highlight new row
				highlight_row(response.person_id);
				set_feedback(response.message,'success_message',false);		
			});
		}
	}
}
</script>

<table id="title_bar">
	<tr>
		<td id="title_icon">
			<img src='<?php echo base_url()?>images/menubar/<?php echo $controller_name; ?>.png' alt='title icon' />
		</td>
		<td id="title">
			<?php echo lang('common_list_of').' '.lang('module_'.$controller_name); ?>
		</td>
		<td id="title_search">
			<?php echo form_open("$controller_name/search",array('id'=>'search_form')); ?>
				<input type="text" name ='search' id='search'/>
				<img src='<?php echo base_url()?>images/spinner_small.gif' alt='spinner' id='spinner' />
			</form>
		</td>
	</tr>
</table>
<table id="contents">
	<tr>
		<td id="commands">
			<div id="new_button">
				<?php if ($this->Employee->has_module_action_permission($controller_name, 'add_update', $this->Employee->get_logged_in_employee_info()->person_id)) {?>
					<?php echo anchor("$controller_name/view/-1/width~$form_width",
					$this->lang->line($controller_name.'_new'),
						array('class'=>'thickbox none new', 'title'=>$this->lang->line($controller_name.'_new')));
				}	
				?>
				<a class="email email_inactive" href="<?php echo current_url(). '#'; ?>" id="email"><?php echo lang("common_email");?></a>
				<?php if ($controller_name =='customers') {?>
				<?php echo anchor("$controller_name/excel_import/width~$form_width",
				lang('common_excel_import'),
				array('class'=>'thickbox none import','title'=>lang('customers_import_customers_from_excel')));
				} ?>
				<?php
				if ($controller_name == 'customers' || $controller_name == 'employees') {	
					echo anchor("$controller_name/excel_export",
					lang('common_excel_export'),
					array('class'=>'none import'));
				}
					
				if ($controller_name == 'customers' ) {
			     echo anchor("$controller_name/excel_import_update/width~$form_width",
					lang('common_excel_import_update'),
					array('class'=>'thickbox none import',
						'title'=>lang('customers_import_customers_from_excel')));
			
				}
				
				?>
				<?php if ($this->Employee->has_module_action_permission($controller_name, 'delete', $this->Employee->get_logged_in_employee_info()->person_id)) {?>
					<?php echo anchor("$controller_name/delete",$this->lang->line("common_delete"),array('id'=>'delete', 'class'=>'delete_inactive')); ?>
				<?php } ?>
				
				<?php if ($controller_name =='customers' or $controller_name =='employees') {?>
					<?php echo 
						anchor("$controller_name/cleanup",
						lang($controller_name."_cleanup_old_customers"),
						array('id'=>'cleanup', 
							'class'=>'cleanup')); 
					?>
				<?php } ?>

			</div>
		</td>
		<td style="width:10px;"></td>
<td>
 <div id="item_table">
			<div id="table_holder">
			<?php echo $manage_table; ?>
			</div>
			</div>
			<div id="pagination">
				<?php echo $pagination;?>
			</div>
		</td>
	</tr>
</table>
<div id="feedback_bar"></div>
<?php $this->load->view("partial/footer"); ?>