<!-- Modal -->
<div class="modal fade" id="suspended" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo lang("sales_suspended_sales"); ?></h4>
      </div>

      <div class="modal-body">
      	<table  id="suspended_sales_table" class="table">
      		<tr>
  				<th><?php echo lang('sales_suspended_sale_id'); ?></th>
  				<th><?php echo lang('sales_date'); ?></th>
  				<th><?php echo lang('sales_customer'); ?></th>
  				<th><?php echo lang('sales_comments'); ?></th>
  				<th><?php echo lang('sales_unsuspend'); ?></th>
  				<th><?php echo lang('sales_receipt'); ?></th>
  				<th><?php echo lang('common_delete'); ?></th>
  			</tr>
  			<tr>
  				<td colspan="7">
  					<table class="table table-register" id="appendTable">
  						<!-- Append data from ajax call -->
  					</table>
  				</td>
  			</tr>
      	</table>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close');?></button>
  		<?php 
  		echo form_close();
  		?>
    </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
