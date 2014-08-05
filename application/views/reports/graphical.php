<?php
$this->load->view("partial/header");
?>
<div class="panel panel-info">
    <div class="panel-heading">
<table id="title_bar">
	<tr>
		<td id="title_icon">
			<img src='<?php echo base_url()?>images/menubar/reports.png' alt='<?php echo lang('reports_reports'); ?> - <?php echo lang('reports_welcome_message'); ?>' />
		</td>
		<td id="title"><?php echo lang('reports_reports'); ?> - <?php echo $title ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><small><?php echo $subtitle ?></small></td>
	</tr>
</table>
<br />
</div>
    <div class="panel-body">

<div id="chart_wrapper">
	<div id="chart"></div>
</div>

<?php 
if ($this->Employee->has_owner_action_permission('Owner', $this->Employee->get_logged_in_employee_info()->employee_id)) { ?>
    <div id="report_summary">
	<?php foreach($summary_data as $name=>$value) { ?>
		<div class="summary_row"><?php echo lang('reports_'.$name). ': '.to_currency($value); ?></div>
	<?php }?>
	</div>
<?php }
?>
 </div>
</div>        


<?php
$this->load->view("partial/footer"); 
?>

<script type="text/javascript">
	$.getScript('<?php echo $graph_file; ?>');
</script>



<?php
/*$result = mysql_query("SHOW COLUMNS FROM cgate_sales_tickets_temp");
if (!$result) {
   echo 'Impossible d\'exécuter la requête : ' . mysql_error();
   exit;
}
if (mysql_num_rows($result) > 0) {
   while ($row = mysql_fetch_assoc($result)) {
      print_r($row);
   }
}*/
?>
