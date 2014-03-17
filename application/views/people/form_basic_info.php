<div class="field_row clearfix">
    <label for="first_name" class="col-sm-4 control-label required">
        <?php echo form_label(lang('common_first_name') . ':', 'first_name', array('class' => 'required')); ?>
    </label>
    <div class='form_field'>
        <?php
        echo form_input(array(
            'name' => 'first_name',
            'id' => 'first_name',
            'value' => $person_info->first_name)
        );
        ?>
    </div>
</div>
<div class="field_row clearfix">
    <label for="last_name" class="col-sm-4 control-label required">
        <?php echo form_label(lang('common_last_name') . ':', 'last_name', array('class' => 'required')); ?>
    </label>
    <div class='form_field'>
        <?php
        echo form_input(array(
            'name' => 'last_name',
            'id' => 'last_name',
            'value' => $person_info->last_name)
        );
        ?>
    </div>
</div>
<div class="field_row clearfix">
    <label for="email" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_email') . ':', 'email'); ?>
    </label>
    <div class="form_field">
        <?php
        echo form_input(array(
            'name' => 'email',
            'id' => 'email',
            'value' => $person_info->email)
        );
        ?>
    </div>
</div>
<div class="field_row clearfix">
    <label for="phone_number" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_phone_number') . ':', 'phone_number'); ?>
    </label>
    <div class="form_field">
        <?php
        echo form_input(array(
            'name' => 'phone_number',
            'id' => 'phone_number',
            'value' => $person_info->phone_number)
        );
        ?>
    </div>
</div>
<div class="field_row clearfix">
    <label for="address_1" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_address_1') . ':', 'address_1'); ?>
    </label>
    <div class="form_field">
        <?php
        echo form_input(array(
            'name' => 'address_1',
            'id' => 'address_1',
            'value' => $person_info->address_1)
        );
        ?>
    </div>
</div>
<div class="field_row clearfix">
    <label for="address_2" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_address_2') . ':', 'address_2'); ?>
    </label>
    <div class="form_field">
        <?php
        echo form_input(array(
            'name' => 'address_2',
            'id' => 'address_2',
            'value' => $person_info->address_2)
        );
        ?>
    </div>
</div>
<div class="field_row clearfix">
    <label for="city" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_city') . ':', 'city'); ?>
    </label>
    <div class="form_field">
        <?php
        echo form_input(array(
            'name' => 'city',
            'id' => 'city',
            'value' => $person_info->city)
        );
        ?>
    </div>
</div>
<div class="field_row clearfix">
    <label for="state" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_state') . ':', 'state'); ?>
    </label>
    <div class="form_field">
        <?php
        echo form_input(array(
            'name' => 'state',
            'id' => 'state',
            'value' => $person_info->state));
        ?>
    </div>
</div>
<div class="field_row clearfix">
    <label for="zip" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_zip') . ':', 'zip'); ?>
    </label>
    <div class="form_field">
        <?php
        echo form_input(array(
            'name' => 'zip',
            'id' => 'zip',
            'value' => $person_info->zip));
        ?>
    </div>
</div>
<div class="field_row clearfix">
    <label for="country" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_country') . ':', 'country'); ?>
    </label>
    <div class="form_field">
        <?php
        echo form_input(array(
            'name' => 'country',
            'id' => 'country',
            'value' => $person_info->country));
        ?>
    </div>
</div>
<div class="field_row clearfix">
    <label for="comments" class="col-sm-4 control-label">
        <?php echo form_label(lang('common_comments') . ':', 'comments'); ?>
    </label>
    <div class="form_field">
        <?php
        echo form_textarea(array(
            'name' => 'comments',
            'id' => 'comments',
            'value' => $person_info->comments,
            'rows' => '5',
            'cols' => '17')
        );
        ?>
    </div>
</div>

<?php
if ($this->config->item('mailchimp_api_key')) {
    ?>
    <div class="field_row clearfix">
        <label for="mailchimp_mailing_lists" class="col-sm-4 control-label">	
            <?php echo form_label(lang('common_mailing_lists') . ':', 'mailchimp_mailing_lists'); ?>
        </label>
        <div class="form_field">
            <ul style="list-style: none;">
                <?php
                foreach (get_all_mailchimps_lists() as $list) {
                    echo '<li>';
                    echo form_checkbox(array('name' => 'mailing_lists[]',
                        'id' => $list['id'],
                        'value' => $list['id'],
                        'checked' => email_subscribed_to_list($person_info->email, $list['id']),
                        'label' => $list['id']));
                    echo form_label($list['name'], $list['id'], array('style' => 'float: none;'));
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
        <div class="cleared"></div>
    </div>
    <?php
}
?>