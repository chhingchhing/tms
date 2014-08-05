<?php
if($export_excel == 1)
{
    $rows = array();
    $row = array();
    foreach ($headers as $header) 
    {
        $row[] = strip_tags($header['data']);
    }
    
    $rows[] = $row;
    
    foreach($data as $datarow)
    {
        $row = array();
        foreach($datarow as $cell)
        {
            $row[] = strip_tags($cell['data']);
        }
        $rows[] = $row;
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
        <div class="re_ticket">
            <table class="table table-bordered">   
                <thead>
                    <tr>
                        <?php foreach ($headers as $header) { ?>
                        <th align="<?php echo $header['align'];?>"><?php echo $header['data']; ?></th>
                        <?php } ?>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($data as $row) { ?>
                    <tr>
                        <?php foreach ($row as $cell) { ?>
                        <td align="<?php echo $cell['align'];?>"><?php echo $cell['data']; ?></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
                 
            </table>
        </div>
        <br />

<?php 
if ($this->Employee->has_owner_action_permission('Owner', $this->Employee->get_logged_in_employee_info()->employee_id)) { ?>
        <div id="report_summary" class="panel panel-default tablesorter report" style="margin-right: 10px;">
        <?php foreach($summary_data as $name=>$value) { ?>
            <div class="summary_row"><?php echo "<strong>".lang('reports_'.$name). '</strong>: '.to_currency($value); ?></div>
        <?php }?>
        </div>
<?php }
?>  
    </div>
</div>

<?php $this->load->view("partial/footer");?>

<?php 
//$result = mysql_query("SHOW COLUMNS FROM cgate_sales_massages_temp");
//if (!$result) {
//   echo 'Impossible d\'exécuter la requête : ' . mysql_error();
//   exit;
//}
//if (mysql_num_rows($result) > 0) {
//   while ($row = mysql_fetch_assoc($result)) {
//      print_r($row);
//   }
//}
?>
