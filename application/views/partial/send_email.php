<!-- Modal -->
<div class="modal fade" id="sendingEmail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("suppliers_basic_information"); ?></h4>
      </div>

      <div class="modal-body">

        <?php
		echo form_open('suppliers/saved',array('id'=>'supplier_frm', 'class'=>'add_update', 'role'=>'form','class'=>'form-horizontal'));
		?>
		<div id="error"><div id="getSmsError"></div><span class="cross">X</span></div>
		<?php echo form_hidden("baseURL", base_url()); ?>
		

		
		<div class="form-group">
			<label for="company_name" class="col-sm-4 control-label required">
				<?php echo form_label(lang('suppliers_company_name').':', 'company_name', array('class'=>'required')); ?>
			</label>
			<div class="col-sm-8">
				<?php echo form_input(array(
					'name'=>'company_name',
					'id'=>'company_name_input',
					'value'=>$person_info->company_name)
				);?>
			</div>
		</div>
		<div class="form-group">
			<label for="account_number" class="col-sm-4 control-label">
				<?php echo form_label(lang('suppliers_account_number').':', 'account_number'); ?>
			</label>
			<div class="col-sm-8">
			<?php echo form_input(array(
				'name'=>'account_number',
				'id'=>'account_number',
				'value'=>$person_info->account_number)
			);?>
			</div>
		</div>

		<?php $this->load->view("people/form_basic_info"); ?>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php 
		echo form_input(array(
			'name'=>'btnSubmitSupplier',
			'id'=>'btnSubmit',
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
</div><!-- /.modal