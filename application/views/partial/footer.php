<?php
if ($this->uri->segment(1) == "dashboard" OR $this->uri->segment(2)) { ?>

<div class="panel panel-info">
    <div class="panel-heading" id="footer_heading">
    <table id="footer_info" class="table table-register">
		<tr>
			<td id="menubar_footer" class="col-md-9">
			<?php echo lang('common_welcome')." <b> $user_info->first_name $user_info->last_name! | </b>"; ?>
			<?php
			if ($this->config->item('track_cash') && $this->Sale->is_register_log_open()) {
				echo anchor("sales/closeregister?continue=logout",lang("common_logout"));
			} else {
				echo anchor("home/logout",lang("common_logout"));
			}
			?>
			</td>
	
			<td id="menubar_date_time" class="menu_date col-md-3">
				
                                <?php echo date('D') ?>	
				<?php echo date('d') ?>
				<?php echo date('F') ?>
				<?php echo date('Y').' ' ?>
                                <?php
				if($this->config->item('time_format') == '24_hour')
				{
					echo date('H:i');					
				}
				else
				{
					echo date('h:i');
				}
				?>
				
				<?php
				if($this->config->item('time_format') != '24_hour')
				{
					echo date('a');
				}
				?>
			</td>
		</tr>
	</table>
  </div>
  
  <div class="panel-footer">
	<table id="footer">
		<tr>
			<td id="footer_cred">
			<?php echo lang('common_powered_by'); ?> 
					<a href="http://www.codingate.com" target="_blank">
						<img src="<?php echo base_url() . 'images/logo.png'; ?>" border="0" alt="logo Image" />
					</a> 
			
			</td>
			<!-- <td id="footer_version">
                          <?php// echo lang('common_you_are_using_tms')?> <?php //echo APPLICATION_VERSION; ?>
			</td> -->
		</tr>
	</table>
  </div>

</div>

<?php } ?>

</div> <!-- End of container class-->

<script src="<?php echo site_url('js/jquery.googleapi.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/jquery-1.10.2.js'); ?>"></script>
<script src="<?php echo site_url('js/excanvas.min.js'); ?>"></script>
<script src="<?php echo site_url('js/jquery.flot.min.js'); ?>"></script>
<script src="<?php echo site_url('js/jquery.flot.pie.min.js'); ?>"></script>
<script src="<?php echo site_url('js/jquery.PrintArea.js'); ?>"></script>
<script src="<?php echo base_url()."assets/tokeninput/js/jquery.tokeninput.js"; ?>"></script>

<?php
	echo include_js(array('assets/js/jquery-ui-1.10.3.custom.min.js',
	'assets/dist/js/bootstrap.js','assets/js/offcanvas.js', 'assets/js/employee.js',
	'assets/js/jquery_form.js', 'assets/js/massage.js', 'assets/js/bike.js','assets/js/tour.js'
	,'assets/timepicker/js/bootstrap-formhelpers.js'
	,'assets/timepicker/js/bootstrap-formhelpers-timepicker.js'
	,'js/jquery.datePicker-2.1.2.js'
	,'js/datepicker.js','js/date.js'
	,'assets/js/generic.js','assets/js/ticket.js','assets/js/transport.js','assets/js/commis.js'
	)); 
?>
  </body>
</html>