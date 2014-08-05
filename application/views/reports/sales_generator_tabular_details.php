<div class="panel panel-default">
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
                                    <div id="table_holder">
                                            <table class="tablesorter report table" id="sortable_table">
                                                    <thead>
                                                            <tr>
                                                                    <th><a href="#" class="expand_all" style="font-weight: bold; color:#000000">+</a></th>
                                                                    <?php foreach ($headers['summary'] as $header) { ?>
                                                                    <th align="<?php echo $header['align']; ?>"><?php echo $header['data']; ?></th>
                                                                    <?php } ?>
                                                            </tr>
                                                    </thead>
                                                    <tbody>
                                                            <?php foreach ($summary_data as $key=>$row) { ?>
                                                            <tr>
                                                                    <td><a href="#" class="expand" style="font-weight: bold;">+</a></td>
                                                                    <?php foreach ($row as $cell) { ?>
                                                                    <td align="<?php echo $cell['align']; ?>"><?php echo $cell['data']; ?></td>
                                                                    <?php } ?>
                                                            </tr>
                                                            <tr>
                                                                    <td colspan="12" class="innertable">
                                                                            <table class="innertable">
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
                                                                    </td>
                                                            </tr>
                                                            <?php } ?>
                                                    </tbody>
                                            </table>
                                    </div>

                                    <?php 
                                    if ($this->Employee->has_owner_action_permission('Owner', $this->Employee->get_logged_in_employee_info()->employee_id)) { ?>
                                        <div id="report_summary" class="tablesorter report" style="margin-right: 10px;">
                                        <?php foreach($overall_summary_data as $name=>$value) { ?>
                                                <div class="summary_row"><?php echo "<strong>".lang('reports_'.$name). '</strong>: '.to_currency($value); ?></div>
                                        <?php }?>
                                        </div>
                                    <?php }
                                    ?>
                                    
                            </td>
                    </tr>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" language="javascript">
$("body").on("click", ".tablesorter a.expand", function(event)
{
        $(event.target).parent().parent().next().find('td.innertable').toggle();

        if ($(event.target).text() == '+')
        {
                $(event.target).text('-');
        }
        else
        {
                $(event.target).text('+');
        }
        return false;
});

$("body").on("click", ".tablesorter a.expand_all", function(event) 
{
        $('td.innertable').toggle();

        if ($(event.target).text() == '+')
        {
                $(event.target).text('-');
        }
        else
        {
                $(event.target).text('+');
        }
        return false;
});
</script>