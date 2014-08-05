<div class="form-group">	
    <?php echo form_label(lang('config_company_logo') . ':', 'company_logo', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_upload(array(
            'name' => 'company_logo',
            'id' => 'company_logo',
            'value' => $office_info->ofc_company_logo));
        ?>		
    </div>	
</div>

<div class="form-group">	
        <?php echo form_label(lang('config_delete_logo') . ':', 'delete_logo', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
<?php echo form_checkbox('delete_logo', '1'); ?>
    </div>	
</div>

<div class="form-group">	
        <?php echo form_label(lang('config_company') . ':', 'company', array('class' => 'col-sm-2 control-label required')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_input(array(
            'class' => 'form-control',
            'name' => 'company',
            'id' => 'company',
            'value' => $office_info->ofc_company));
        ?>
    </div>
</div>

<div class="form-group">
        <?php echo form_label(lang('config_address') . ':', 'address', array('class' => 'col-sm-2 control-label required')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_textarea(array(
            'class' => 'form-control',
            'name' => 'address',
            'id' => 'address',
            'rows' => 4,
            'cols' => 30,
            'value' => $office_info->ofc_address));
        ?>
    </div>
</div>

<div class="form-group">
        <?php echo form_label(lang('config_phone') . ':', 'phone', array('class' => 'col-sm-2 control-label required')); ?>
    <div class='col-sm-10'>
<?php
echo form_input(array(
    'class' => 'form-control',
    'name' => 'phone',
    'id' => 'phone',
    'value' => $office_info->ofc_phone));
?>
    </div>
</div>

<!-- This is for tax of item -->
<div class="form-group" style="display: none;">
        <?php echo form_label(lang('config_default_tax_rate_1') . ':', 'default_tax_1_rate', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_input(array(
            'name' => 'default_tax_1_name',
            'id' => 'default_tax_1_name',
            'size' => '10',
            'value' => $this->config->item('default_tax_1_name') !== FALSE ? $this->config->item('default_tax_1_name') : lang('items_sales_tax_1')));
        ?>

        <?php
        echo form_input(array(
            'name' => 'default_tax_1_rate',
            'id' => 'default_tax_1_rate',
            'size' => '4',
            'value' => $this->config->item('default_tax_1_rate')));
        ?>%
    </div>
</div>
<div class="form-group" style="display: none;">
        <?php echo form_label(lang('config_default_tax_rate_2') . ':', 'default_tax_1_rate', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_input(array(
            'name' => 'default_tax_2_name',
            'id' => 'default_tax_2_name',
            'size' => '10',
            'value' => $this->config->item('default_tax_2_name') !== FALSE ? $this->config->item('default_tax_2_name') : lang('items_sales_tax_2')));
        ?>
    <?php
    echo form_input(array(
        'name' => 'default_tax_2_rate',
        'id' => 'default_tax_2_rate',
        'size' => '4',
        'value' => $this->config->item('default_tax_2_rate')));
    ?>%
<?php echo form_checkbox('default_tax_2_cumulative', '1', $this->config->item('default_tax_2_cumulative') ? true : false); ?>
        <span class="cumulative_label">
    <?php echo lang('common_cumulative'); ?>
        </span>
    </div>
</div>
<!-- End of tax on item -->

<div class="form-group">
    <?php echo form_label(lang('config_currency_symbol') . ':', 'currency_symbol', array('class' => 'col-sm-2 control-label')); ?>
<div class='col-sm-10'>
<?php
echo form_input(array(
    'class' => 'form-control',
    'name' => 'currency_symbol',
    'id' => 'currency_symbol',
    'value' => $office_info->ofc_currency_symbol));
?>
    </div>
</div>

<div class="form-group">	
<?php echo form_label(lang('common_email') . ':', 'email', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
<?php
echo form_input(array(
    'class' => 'form-control',
    'name' => 'email',
    'id' => 'email',
    'value' => $office_info->ofc_email));
?>
    </div>
</div>

<div class="form-group">	
<?php echo form_label(lang('config_fax') . ':', 'fax', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
    <?php
    echo form_input(array(
        'class' => 'form-control',
        'name' => 'fax',
        'id' => 'fax',
        'value' => $office_info->ofc_fax));
    ?>
    </div>
</div>

<div class="form-group">	
<?php echo form_label(lang('config_website') . ':', 'website', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
    <?php
    echo form_input(array(
        'class' => 'form-control',
        'name' => 'website',
        'id' => 'website',
        'value' => $office_info->ofc_website));
    ?>
    </div>
</div>

<div class="form-group">
<?php echo form_label(lang('common_return_policy') . ':', 'return_policy', array('class' => 'col-sm-2 control-label required')); ?>
    <div class='col-sm-10'>
    <?php
    echo form_textarea(array(
        'class' => 'form-control',
        'name' => 'return_policy',
        'id' => 'return_policy',
        'rows' => '4',
        'cols' => '30',
        'value' => $office_info->ofc_return_policy));
    ?>
    </div>
</div>

<div class="form-group">
        <?php echo form_label(lang('config_language') . ':', 'language', array('class' => 'col-sm-2 control-label required')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_dropdown('language', array(
            'english' => 'English',
            //'indonesia' => 'Indonesia',
            //'spanish' => 'Spanish',
            //'french' => 'French',
            'khmer' => 'Khmer'), $office_info->ofc_language);
        ?>
    </div>
</div>

<div class="form-group">	
        <?php echo form_label(lang('config_timezone') . ':', 'timezone', array('class' => 'col-sm-2 control-label required')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_dropdown('timezone', array(
            'Pacific/Midway' => '(GMT-11:00) Midway Island, Samoa',
            'America/Adak' => '(GMT-10:00) Hawaii-Aleutian',
            'Etc/GMT+10' => '(GMT-10:00) Hawaii',
            'Pacific/Marquesas' => '(GMT-09:30) Marquesas Islands',
            'Pacific/Gambier' => '(GMT-09:00) Gambier Islands',
            'America/Anchorage' => '(GMT-09:00) Alaska',
            'America/Ensenada' => '(GMT-08:00) Tijuana, Baja California',
            'Etc/GMT+8' => '(GMT-08:00) Pitcairn Islands',
            'America/Los_Angeles' => '(GMT-08:00) Pacific Time (US & Canada)',
            'America/Denver' => '(GMT-07:00) Mountain Time (US & Canada)',
            'America/Chihuahua' => '(GMT-07:00) Chihuahua, La Paz, Mazatlan',
            'America/Dawson_Creek' => '(GMT-07:00) Arizona',
            'America/Belize' => '(GMT-06:00) Saskatchewan, Central America',
            'America/Cancun' => '(GMT-06:00) Guadalajara, Mexico City, Monterrey',
            'Chile/EasterIsland' => '(GMT-06:00) Easter Island',
            'America/Chicago' => '(GMT-06:00) Central Time (US & Canada)',
            'America/New_York' => '(GMT-05:00) Eastern Time (US & Canada)',
            'America/Havana' => '(GMT-05:00) Cuba',
            'America/Bogota' => '(GMT-05:00) Bogota, Lima, Quito, Rio Branco',
            'America/Caracas' => '(GMT-04:30) Caracas',
            'America/Santiago' => '(GMT-04:00) Santiago',
            'America/La_Paz' => '(GMT-04:00) La Paz',
            'Atlantic/Stanley' => '(GMT-04:00) Faukland Islands',
            'America/Campo_Grande' => '(GMT-04:00) Brazil',
            'America/Goose_Bay' => '(GMT-04:00) Atlantic Time (Goose Bay)',
            'America/Glace_Bay' => '(GMT-04:00) Atlantic Time (Canada)',
            'America/St_Johns' => '(GMT-03:30) Newfoundland',
            'America/Araguaina' => '(GMT-03:00) UTC-3',
            'America/Montevideo' => '(GMT-03:00) Montevideo',
            'America/Miquelon' => '(GMT-03:00) Miquelon, St. Pierre',
            'America/Godthab' => '(GMT-03:00) Greenland',
            'America/Argentina/Buenos_Aires' => '(GMT-03:00) Buenos Aires',
            'America/Sao_Paulo' => '(GMT-03:00) Brasilia',
            'America/Noronha' => '(GMT-02:00) Mid-Atlantic',
            'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde Is.',
            'Atlantic/Azores' => '(GMT-01:00) Azores',
            'Europe/Belfast' => '(GMT) Greenwich Mean Time : Belfast',
            'Europe/Dublin' => '(GMT) Greenwich Mean Time : Dublin',
            'Europe/Lisbon' => '(GMT) Greenwich Mean Time : Lisbon',
            'Europe/London' => '(GMT) Greenwich Mean Time : London',
            'Africa/Abidjan' => '(GMT) Monrovia, Reykjavik',
            'Europe/Amsterdam' => '(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
            'Europe/Belgrade' => '(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague',
            'Europe/Brussels' => '(GMT+01:00) Brussels, Copenhagen, Madrid, Paris',
            'Africa/Algiers' => '(GMT+01:00) West Central Africa',
            'Africa/Windhoek' => '(GMT+01:00) Windhoek',
            'Asia/Beirut' => '(GMT+02:00) Beirut',
            'Africa/Cairo' => '(GMT+02:00) Cairo',
            'Asia/Gaza' => '(GMT+02:00) Gaza',
            'Africa/Blantyre' => '(GMT+02:00) Harare, Pretoria',
            'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
            'Europe/Minsk' => '(GMT+02:00) Minsk',
            'Asia/Damascus' => '(GMT+02:00) Syria',
            'Europe/Moscow' => '(GMT+03:00) Moscow, St. Petersburg, Volgograd',
            'Africa/Addis_Ababa' => '(GMT+03:00) Nairobi',
            'Asia/Tehran' => '(GMT+03:30) Tehran',
            'Asia/Dubai' => '(GMT+04:00) Abu Dhabi, Muscat',
            'Asia/Yerevan' => '(GMT+04:00) Yerevan',
            'Asia/Kabul' => '(GMT+04:30) Kabul',
            'Asia/Yekaterinburg' => '(GMT+05:00) Ekaterinburg',
            'Asia/Tashkent' => '(GMT+05:00) Tashkent',
            'Asia/Calcutta' => '(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi',
            'Asia/Katmandu' => '(GMT+05:45) Kathmandu',
            'Asia/Dhaka' => '(GMT+06:00) Astana, Dhaka',
            'Asia/Novosibirsk' => '(GMT+06:00) Novosibirsk',
            'Asia/Rangoon' => '(GMT+06:30) Yangon (Rangoon)',
            'Asia/Bangkok' => '(GMT+07:00) Bangkok, Hanoi, Jakarta',
            'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
            'Asia/Hong_Kong' => '(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi',
            'Asia/Irkutsk' => '(GMT+08:00) Irkutsk, Ulaan Bataar',
            'Australia/Perth' => '(GMT+08:00) Perth',
            'Australia/Eucla' => '(GMT+08:45) Eucla',
            'Asia/Tokyo' => '(GMT+09:00) Osaka, Sapporo, Tokyo',
            'Asia/Seoul' => '(GMT+09:00) Seoul',
            'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
            'Australia/Adelaide' => '(GMT+09:30) Adelaide',
            'Australia/Darwin' => '(GMT+09:30) Darwin',
            'Australia/Brisbane' => '(GMT+10:00) Brisbane',
            'Australia/Hobart' => '(GMT+10:00) Hobart',
            'Asia/Vladivostok' => '(GMT+10:00) Vladivostok',
            'Australia/Lord_Howe' => '(GMT+10:30) Lord Howe Island',
            'Etc/GMT-11' => '(GMT+11:00) Solomon Is., New Caledonia',
            'Asia/Magadan' => '(GMT+11:00) Magadan',
            'Pacific/Norfolk' => '(GMT+11:30) Norfolk Island',
            'Asia/Anadyr' => '(GMT+12:00) Anadyr, Kamchatka',
            'Pacific/Auckland' => '(GMT+12:00) Auckland, Wellington',
            'Etc/GMT-12' => '(GMT+12:00) Fiji, Kamchatka, Marshall Is.',
            'Pacific/Chatham' => '(GMT+12:45) Chatham Islands',
            'Pacific/Tongatapu' => '(GMT+13:00) Nuku\'alofa',
            'Pacific/Kiritimati' => '(GMT+14:00) Kiritimati'
                ), $office_info->ofc_timezone ? $office_info->ofc_timezone : date_default_timezone_get(), 'class="form-control"');
        ?>
    </div>
</div>

<div class="form-group">	
        <?php echo form_label(lang('config_date_format') . ':', 'date_format', array('class' => 'col-sm-2 control-label required')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_dropdown('date_format', array(
            'middle_endian' => '12/30/2000',
            'little_endian' => '30-12-2000',
            'big_endian' => '2000-12-30'), $office_info->ofc_date_format, 'class="form-control"');
        ?>
    </div>
</div>

<div class="form-group">	
        <?php echo form_label(lang('config_time_format') . ':', 'time_format', array('class' => 'col-sm-2 control-label required')); ?>
    <div class='col-sm-10'>
<?php
echo form_dropdown('time_format', array(
    '12_hour' => '1:00 PM',
    '24_hour' => '13:00'
        ), $office_info->ofc_time_format, 'class="form-control"');
?>
    </div>
</div>

<div class="form-group">
        <?php echo form_label(lang('config_print_after_sale') . ':', 'print_after_sale', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
<?php
echo form_checkbox(array(
    'name' => 'print_after_sale',
    'id' => 'print_after_sale',
    'value' => 'print_after_sale',
    'checked' => $office_info->ofc_print_after_sale));
?>
    </div>
</div>

<div class="form-group">	
<?php echo form_label(lang('config_round_cash_on_sales') . ':', 'round_cash_on_sales', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
<?php
echo form_checkbox(array(
    'name' => 'round_cash_on_sales',
    'id' => 'round_cash_on_sales',
    'value' => 'round_cash_on_sales',
    'checked' => $office_info->ofc_round_cash_on_sales));
?>
    </div>
</div>

<div class="form-group">	
<?php echo form_label(lang('config_automatically_email_receipt') . ':', 'automatically_email_receipt', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
    <?php
    echo form_checkbox(array(
        'name' => 'automatically_email_receipt',
        'id' => 'automatically_email_receipt',
        'value' => 'automatically_email_receipt',
        'checked' => $office_info->ofc_automatically_email_receipt));
    ?>
    </div>
</div>

<div class="form-group">	
    <?php echo form_label(lang('config_barcode_price_include_tax') . ':', 'barcode_price_include_tax', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_checkbox(array(
            'name' => 'barcode_price_include_tax',
            'id' => 'barcode_price_include_tax',
            'value' => 'barcode_price_include_tax',
            'checked' => $office_info->ofc_barcode_price_include_tax));
        ?>
    </div>
</div>


<div class="form-group">	
        <?php echo form_label(lang('config_hide_signature') . ':', 'hide_signature', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_checkbox(array(
            'name' => 'hide_signature',
            'id' => 'hide_signature',
            'value' => 'hide_signature',
            'checked' => $office_info->ofc_hide_signature));
        ?>
    </div>
</div>

<div class="form-group">
        <?php echo form_label(lang('disable_confirmation_sale') . ':', 'disable_confirmation_sale', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_checkbox(array(
            'name' => 'disable_confirmation_sale',
            'id' => 'disable_confirmation_sale',
            'value' => 'disable_confirmation_sale',
            'checked' => $office_info->ofc_disable_confirmation_sale));
        ?>
    </div>
</div>

<div class="form-group">
        <?php echo form_label(lang('config_mailchimp_api_key') . ':', 'mailchimp_api_key', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_input(array(
            'class' => 'form-control',
            'name' => 'mailchimp_api_key',
            'id' => 'mailchimp_api_key',
            'value' => $office_info->ofc_mailchimp_api_key));
        ?>
    </div>
</div>

<div class="form-group">	
        <?php echo form_label(lang('config_number_of_items_per_page') . ':', 'number_of_items_per_page', array('class' => 'col-sm-2 control-label required')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_dropdown('number_of_items_per_page', array(
            '20' => '20',
            '50' => '50',
            '100' => '100',
            '200' => '200',
            '500' => '500'
                ), $office_info->ofc_number_of_items_per_page ? $office_info->ofc_number_of_items_per_page : '20');
        ?>
    </div>
</div>

<div class="form-group">	
<?php echo form_label(lang('config_track_cash') . ':', 'track_cash', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
    <?php
    echo form_checkbox(array(
        'name' => 'track_cash',
        'id' => 'track_cash',
        'value' => '1',
        'checked' => $office_info->ofc_track_cash));
    ?>
    </div>
</div>


<div class="form-group">	
    <?php echo form_label(lang('sales_hide_suspended_sales_in_reports') . ':', 'hide_suspended_sales_in_reports', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_checkbox(array(
            'name' => 'hide_suspended_sales_in_reports',
            'id' => 'hide_suspended_sales_in_reports',
            'value' => '1',
            'checked' => $office_info->ofc_hide_suspended_sales_in_reports));
        ?>
    </div>
</div>

<div class="form-group">
<?php echo form_label(lang('config_speed_up_search_queries') . ':', 'speed_up_search_queries', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
    <?php
    echo form_checkbox(array(
        'name' => 'speed_up_search_queries',
        'id' => 'speed_up_search_queries',
        'value' => '1',
        'checked' => $office_info->ofc_speed_up_search_queries));
    ?>
        <span id="speed_up_note" style="font-weight: normal;">(<?php echo lang('config_speed_up_note'); ?>)</span>
    </div>
</div>
<div class="form-group">	
        <?php echo form_label(lang('receive_stock_alert') . ':', 'receive_stock_alert', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
        <?php
        echo form_checkbox(array(
            'name' => 'receive_stock_alert',
            'id' => 'receive_stock_alert',
            'value' => '1',
            'checked' => $office_info->ofc_receive_stock_alert));
        ?>
    </div>
</div>

<div class="form-group">	
            <?php echo form_label(lang('stock_alert_email') . ':', 'stock_alert_email', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
    <?php
    echo form_input(array(
        'class' => 'form-control',
        'name' => 'stock_alert_email',
        'id' => 'stock_alert_email',
        'value' => $office_info->ofc_stock_alert_email));
    ?>
    </div>
</div>

<div class="form-group">	
<?php echo form_label(lang('config_payment_types') . ':', 'additional_payment_types', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
        <?php echo lang('sales_cash'); ?>, 
        <?php echo lang('sales_check'); ?>, 
        <?php echo lang('sales_giftcard'); ?>, 
        <?php echo lang('sales_debit'); ?>, 
        <?php echo lang('sales_credit'); ?>,
        <?php echo lang('sales_visa_card'); ?>,
        <?php echo lang('sales_master_card'); ?>,
        <?php echo lang('sales_western_union'); ?>,
        <?php
        echo form_input(array(
            'name' => 'additional_payment_types',
            'id' => 'additional_payment_types',
            'class' => 'form-control',
            'size' => 40,
            'value' => $office_info->ofc_additional_payment_types));
        ?>
    </div>
</div>

<div class="form-group">	
<?php echo form_label(lang('config_default_payment_type') . ':', 'default_payment_type', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
<?php echo form_dropdown('default_payment_type', $payment_options, $office_info->ofc_default_payment_type); ?>
    </div>
</div>

<div class="form-group">
<?php echo form_label("<a href='http://go.mercurypay.com/go/PHP_POS/' target='_blank'>" . $this->lang->line('config_enable_credit_card_processing') . '</a>:', 'enable_credit_card_processing', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
    <?php
    echo form_checkbox(array(
        'name' => 'enable_credit_card_processing',
        'id' => 'enable_credit_card_processing',
        'value' => '1',
        'checked' => $office_info->ofc_enable_credit_card_processing));
    ?>
    </div>
</div>
<div id="merchant_information">
    <div class="form-group">
<?php echo form_label($this->lang->line('config_merchant_id') . ':', 'merchant_id', array('class' => 'col-sm-2 control-label')); ?>
        <div class='col-sm-10'>
        <?php
        echo form_input(array(
            'class' => 'form-control',
            'name' => 'merchant_id',
            'id' => 'merchant_id',
            'value' => $office_info->ofc_merchant_id));
        ?>
        </div>
    </div>

    <div class="form-group">	
<?php echo form_label($this->lang->line('config_merchant_password') . ':', 'merchant_password', array('class' => 'col-sm-2 control-label')); ?>
        <div class='col-sm-10'>
        <?php
        echo form_password(array(
            'class' => 'form-control',
            'name' => 'merchant_password',
            'id' => 'merchant_password',
            'value' => $office_info->ofc_merchant_password));
        ?>
        </div>
        <div class='col-sm-10' id="merchant_password_note"><?php echo lang('sales_mercury_password_note'); ?></div>
    </div>
</div>

<!-- Currency -->
<div class="form-group">    
<?php echo form_label(lang('config_default_currency_type') . ':', 'default_currency', array('class' => 'col-sm-2 control-label')); ?>
    <div class='col-sm-10'>
<?php echo form_dropdown('default_currency', $currency_options, $office_info->ofc_default_currency, 'class="form-control"'); ?>
    </div>
</div>
<!-- Currency End -->