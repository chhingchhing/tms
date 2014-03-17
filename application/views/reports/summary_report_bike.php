<?php $this->load->view("partial/header"); ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <div class="row" id="title_bar">
            <div id="page_title" class="col-10 col-sm-10 col-lg-10" style="margin-bottom:8px;"><?php echo lang('summary_reports_bike'); ?></div>
    
        </div>
    </div>
    <div class="panel-body">
        <!-- Panel content -->
        <div class="re_ticket">
                <table class="table table-bordered">   
                    <tr>
                        <th><?php echo lang('summary_reports_ticket_id'); ?></th>
                        <th><?php echo lang('summary_reports_issue_date'); ?></th>
                        <th><?php echo lang('summary_reports_date_out'); ?></th>
                        <th><?php echo lang('summary_reports_date_in'); ?></th>
                        <th><?php echo lang('summary_reports_time_out'); ?></th>
                        <th><?php echo lang('summary_reports_time_in'); ?></th>
                        <th><?php echo lang('summary_reports_quantity'); ?></th>
                        <th><?php echo lang('summary_reports_num_bike'); ?></th>
                        <th><?php echo lang('summary_reports_num_day'); ?></th>
                        <th><?php echo lang('summary_reports_price_amount'); ?></th>
                        <th><?php echo lang('summary_reports_total'); ?></th>
                        <th><?php echo lang('summary_reports_deposit'); ?></th>
                        <th><?php echo lang('summary_reports_code_invoice'); ?></th>
                        <th><?php echo lang('summary_reports_balance'); ?></th>
                        <th><?php echo lang('summary_reports_commissioner'); ?></th>
                        <th><?php echo lang('summary_reports_contact'); ?></th>
                        <th><?php echo lang('summary_reports_hotel_name'); ?></th>
                        <th><?php echo lang('summary_reports_room'); ?></th>
                        <th><?php echo lang('summary_reports_seller'); ?></th>
                        <th><?php echo lang('summary_reports_receive'); ?></th>
                        <th><?php echo lang('summary_reports_over_date'); ?></th>
                        <th><?php echo lang('summary_reports_extra_pay'); ?></th>
                        <th><?php echo lang('summary_reports_time_receive'); ?></th>
                        <th><?php echo lang('summary_reports_receiver'); ?></th>
                    </tr>
                    <tr>
                        <?php // for($i = 0; $i <23; $i++) { ?>
                        <!--<td>This is some text.</td>--> 
                        <?php // } ?>
                        <td>1</td>
                        <td>07/02/2014</td>
                        <td>15/03/2014</td>
                        <td>17/03/2014</td>
                        <td>7 AM</td>
                        <td>10 PM</td>
                        <td>2</td>
                        <td>001</td>
                        <td>2</td>
                        <td>$50 </td>
                        <td>$50</td>
                        <td>$20</td>
                        <td>0001</td>
                        <td>$30</td>
                        <td>sok sreychen</td>
                        <td>097 95 86 953</td>
                        <td>Royal </td>
                        <td>R 25</td>
                        <td>youlay hong</td>
                        <td>Sokry Sat</td>
                        <td>1</td>
                        <td>$5</td>
                        <td>9 PM</td>
                        <td>Youlay Hong</td>
                        
                    </tr> 
                </table>
        </div>
    </div>
</div>
<?php $this->load->view("partial/footer");?>
<script type="text/javascript" language="javascript">
    $(document).ready(function()
    {
        $("#generate_report").click(function()
        {
            var sale_type = $("#sale_type").val();
            var export_excel = 0;
            if ($("#export_excel_yes").attr('checked'))
            {
                export_excel = 1;
            }

            if ($("#simple_radio").attr('checked'))
            {
                window.location = window.location + '/' + $("#report_date_range_simple option:selected").val() + '/' + sale_type + '/' + export_excel;
            }
            else
            {
                var start_date = $("#start_year").val() + '-' + $("#start_month").val() + '-' + $('#start_day').val() + ' ' + $('#start_hour').val() + ':' + $('#start_minute').val() + ':00';
                var end_date = $("#end_year").val() + '-' + $("#end_month").val() + '-' + $('#end_day').val() + ' ' + $('#end_hour').val() + ':' + $('#end_minute').val() + ':00';

                window.location = window.location + '/' + start_date + '/' + end_date + '/' + sale_type + '/' + export_excel;
            }
        });

        $("#start_month, #start_day, #start_year, #end_month, #end_day, #end_year").change(function()
        {
            $("#complex_radio").attr('checked', 'checked');
        });

        $("#report_date_range_simple").change(function()
        {
            $("#simple_radio").attr('checked', 'checked');
        });

    });
</script>

