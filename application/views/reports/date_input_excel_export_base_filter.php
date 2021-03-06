<?php $this->load->view("partial/header"); ?>
<div class="panel panel-info">
    <div class="panel-heading">
        <div id="page_title" style="margin-bottom:8px;"><?php echo lang('reports_report_input'); ?></div>
    </div>
    <div class="panel-body">
        <?php
        if (isset($error)) {
            echo "<div class='error_message'>" . $error . "</div>";
        }
        ?>
        <?php echo form_label(lang('reports_date_range'), 'report_date_range_label', array('class' => 'required')); ?>
        <div id='report_date_range_simple'>
            <input type="radio" name="report_type" id="simple_radio" value='simple' checked='checked'/>
            <?php echo form_dropdown('report_date_range_simple', $get_report['report_date_range_simple'], '', 'id="report_date_range_simple"'); ?>
        </div>
        <div id='report_date_range_complex'>
            <input type="radio" name="report_type" id="complex_radio" value='complex' />
            <?php echo form_dropdown('start_month', $get_report['months'], $get_report['selected_month'], 'id="start_month"'); ?>
            <?php echo form_dropdown('start_day', $get_report['days'], $get_report['selected_day'], 'id="start_day"'); ?>
            <?php echo form_dropdown('start_year', $get_report['years'], $get_report['selected_year'], 'id="start_year"'); ?>
            <?php echo form_dropdown('start_hour', $get_report['hours'], 0, 'id="start_hour"'); ?>
            :
            <?php echo form_dropdown('start_minute', $get_report['minutes'], '0', 'id="start_minute"'); ?>
            -
            <?php echo form_dropdown('end_month', $get_report['months'], $get_report['selected_month'], 'id="end_month"'); ?>
            <?php echo form_dropdown('end_day', $get_report['days'], $get_report['selected_day'], 'id="end_day"'); ?>
            <?php echo form_dropdown('end_year', $get_report['years'], $get_report['selected_year'], 'id="end_year"'); ?>
            <?php echo form_dropdown('end_hour', $get_report['hours'], 23, 'id="end_hour"'); ?>
            :
            <?php echo form_dropdown('end_minute', $get_report['minutes'], '59', 'id="end_minute"'); ?>
        </div>

        <?php echo form_label(lang('reports_sale_type'), 'reports_sale_type_label', array('class' => 'required')); ?>
        <div id='report_sale_type'>
            <?php echo form_dropdown('sale_type', array('all' => lang('reports_all'), 'sales' => lang('reports_sales'), 'returns' => lang('reports_returns')), 'all', 'id="sale_type"'); ?>
        </div>

        <?php echo form_label(lang('reports_codition_office_reports'), 'reports_codition_office_reports'); ?>
        <div id='report_sale_type'>
            <?php echo form_dropdown('condition_office_filter', $allowed_offices, 'all', 'id="office"'); ?>
        </div>

        <?php echo form_label(lang('reports_codition_massager_reports'), 'reports_codition_massager_reports'); ?>
        <div id='report_sale_type'>
            <?php echo form_dropdown('condition_massager_filter', $massagers, 'all', 'id="massager"'); ?>
        </div>

        <div>
            <?php echo lang('reports_export_to_excel'); ?>: <input type="radio" name="export_excel" id="export_excel_yes" value='1' /> <?php echo lang('common_yes'); ?>
            <input type="radio" name="export_excel" id="export_excel_no" value='0' checked='checked' /> <?php echo lang('common_no'); ?>
        </div>

        <?php
        echo form_button(array(
            'name' => 'generate_report',
            'id' => 'generate_report',
            'content' => lang('common_submit'),
            'class' => 'submit_button btn btn-info')
        );
        ?>
    </div>
</div>

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
    $(document).ready(function()
    {

        $("#generate_report").click(function()
        {
            var sale_type = $("#sale_type").val();
            var office = $("#office").val();
            var massager = $("#massager").val();
            var export_excel = 0;
            if ($('#export_excel_yes').is(':checked')) {
                export_excel = 1;
            };


            if ($("#simple_radio").attr('checked'))
            {
                window.location = window.location + '/' + $("#report_date_range_simple option:selected").val() + '/' + sale_type + '/' + export_excel + '/' + office + '/' + massager;
            }
            else
            {
                var start_date = $("#start_year").val() + '-' + $("#start_month").val() + '-' + $('#start_day').val() + ' ' + $('#start_hour').val() + ':' + $('#start_minute').val() + ':00';
                var end_date = $("#end_year").val() + '-' + $("#end_month").val() + '-' + $('#end_day').val() + ' ' + $('#end_hour').val() + ':' + $('#end_minute').val() + ':00';

                window.location = window.location + '/' + start_date + '/' + end_date + '/' + sale_type + '/' + export_excel + '/' + office + '/' + massager;
            }
        });

        $("#start_month, #start_day, #start_year, #end_month, #end_day, #end_year").change(function()
        {
            $("#complex_radio").attr('checked', 'checked');
            $("#simple_radio").removeAttr("checked");
        });

        $("#report_date_range_simple").change(function()
        {
            $("#simple_radio").attr('checked', 'checked');
        });
        

    });
</script>
