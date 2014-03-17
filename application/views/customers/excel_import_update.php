<!-- Chhingchhing -->
<!-- Modal -->
<div class="modal fade" id="excel_import_master" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("customers_mass_import_from_excel"); ?></h4>
      </div>
<h4 class="modal-title"><?php echo anchor("customers/excel_export/1", lang('customers_download_excel_mass_update_template')); ?></h4>
<?php echo form_open_multipart('customers/do_excel_import_update/',array('id'=>'mass_update', 'class'=>'add_update', 'role'=>'form','class'=>'form-horizontal')); ?>
      <div class="modal-body">
        <div id="error"><div id="getSmsError"></div><span class="cross">X</span></div>
        <?php echo form_hidden("baseURL", base_url()); ?>

        <div class="form-group">
            <label for="company_name" class="col-sm-4 control-label">
                <?php echo form_label(lang('common_file_path').':', 'name',array('class'=>'wide')); ?>
            </label>
            <div class="col-sm-8">
            <?php echo form_upload(array(
				'name'=>'file_path',
				'id'=>'file_path',
				'value'=>'')
			);?>
            </div>
        </div>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php 
          echo form_submit(array(
            'name'=>'submitMassUpdate',
            'id'=>'btnSubmitMU',
            'value'=>lang('common_submit'),
            'class'=>'submit_button float_right btn btn-primary',
            'role'=>'button'
            )
          );
        ?>
    </div>
	<?php 
	echo form_close();
	?>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->