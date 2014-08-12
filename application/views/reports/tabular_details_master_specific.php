<?php
if($export_excel == 1)
{
	$rows = array();
	$row = array();
	foreach ($headers['summary'] as $header) 
	{
		$row[] = strip_tags($header['data']);
	}
	$rows[] = $row;
	
	foreach ($summary_data as $key=>$datarow) 
	{
		$row = array();
		foreach($datarow as $cell)
		{
			$row[] = strip_tags($cell['data']);
		}
		
		$rows[] = $row;

		$row = array();
		foreach ($headers['details'] as $header) 
		{
			$row[] = strip_tags($header['data']);
		}
		
		$rows[] = $row;
		
		foreach($details_data[$key] as $datarow2)
		{
			$row = array();
			foreach($datarow2 as $cell)
			{
				$row[] = strip_tags($cell['data']);
			}
			$rows[] = $row;
		}
	}
	
	$content = array_to_csv($rows);
	force_download(strip_tags($title) . '.csv', $content);
	exit;
}

?>
<?php $this->load->view("partial/header"); ?>

<div class="panel panel-info">
    <div class="panel-heading">
        <div class="row" id="title_bar">
            <div id="page_title" class="col-10 col-sm-10 col-lg-10" style="margin-bottom:8px;">
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
            </div>
        </div>
    </div>
    <div class="panel-body">
        <!-- Panel content -->
        <div class="table-responsive">
	        <table id="contents" class="table table-register">
				<tr>
					<td id="item_table">
						<div id="table_holder" style="width: 100%;">
							<!-- <table class="innertable table table-register"> -->
							<table class="tablesorter report table table-bordered">
								<thead>
									<tr>
										<?php foreach ($headers['details'] as $header) { ?>
										<th align="<?php echo $header['align']; ?>"><?php echo $header['data']; ?></th>
										<?php } ?>
									</tr>
								</thead>
							
								<tbody>
									<?php foreach ($details_data[$key] as $row2) { ?>
									
										<tr>
											<?php foreach ($row2 as $cell) { ?>
											<td align="<?php echo $cell['align']; ?>"><?php echo $cell['data']; ?></td>
											<?php } ?>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>

					</td>
				</tr>
			</table>
		</div>
		<br />
<?php 
if ($this->Employee->has_owner_action_permission('Owner', $this->Employee->get_logged_in_employee_info()->employee_id)) { ?>
        <div id="report_summary" class="panel panel-default tablesorter report" style="margin-right: 10px;">
        <?php foreach($overall_summary_data as $name=>$value) { ?>
			<div class="summary_row"><?php echo "<strong>".lang('reports_'.$name). '</strong>: '.to_currency($value); ?></div>
		<?php }?>
        </div>
<?php }
?> 
        
    </div>
</div>

<?php $this->load->view("partial/footer"); ?>
