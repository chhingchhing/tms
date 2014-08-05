<div class="form-group">
    <?php echo form_label(lang('common_first_name') . ':', 'first_name', array('class' => 'col-sm-4 control-label required')); ?>
    <div class='col-sm-8'>
        <?php
        echo form_input(array(
            'name' => 'first_name',
            'id' => 'first_name',
            'value' => $person_info->first_name,
            'class'=>"form-control")
        );
        ?>
    </div>
</div>

<div class="form-group">
    <?php echo form_label(lang('common_last_name') . ':', 'last_name', array('class' => 'col-sm-4 control-label required')); ?>
    <div class='col-sm-8'>
        <?php
        echo form_input(array(
            'name' => 'last_name',
            'id' => 'last_name',
            'value' => $person_info->last_name,
            'class'=>"form-control")
        );
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_email') . ':', 'email', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'email',
            'id' => 'email',
            'value' => $person_info->email,
            'class'=>"form-control")
        );
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_phone_number') . ':', 'phone_number', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'phone_number',
            'id' => 'phone_number',
            'value' => $person_info->phone_number,
            'class'=>"form-control")
        );
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_address_1') . ':', 'address_1', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'address_1',
            'id' => 'address_1',
            'value' => $person_info->address_1,
            'class'=>"form-control")
        );
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_address_2') . ':', 'address_2', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'address_2',
            'id' => 'address_2',
            'value' => $person_info->address_2,
            'class'=>"form-control")
        );
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_city') . ':', 'city', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'city',
            'id' => 'city',
            'value' => $person_info->city,
            'class'=>"form-control")
        );
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_state') . ':', 'state', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'state',
            'id' => 'state',
            'value' => $person_info->state,
            'class'=>"form-control"));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_zip') . ':', 'zip', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'zip',
            'id' => 'zip',
            'value' => $person_info->zip,
            'class'=>"form-control"));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_country') . ':', 'country', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php
        echo form_input(array(
            'name' => 'country',
            'id' => 'country',
            'value' => $person_info->country,
            'class'=>"form-control"));
        ?>
    </div>
</div>
<div class="form-group">
    <?php echo form_label(lang('common_comments') . ':', 'comments', array('class'=>'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?php
        echo form_textarea(array(
            'name' => 'comments',
            'id' => 'comments',
            'value' => $person_info->comments,
            'rows' => '5',
            'cols' => '17',
            'class'=>"form-control")
        );
        ?>
    </div>
</div>

<?php
if ($controller_name != "massages") {
    if ($this->config->item('mailchimp_api_key')) {
        ?>
        <div class="form-group">  
            <?php echo form_label(lang('common_mailing_lists') . ':', 'mailchimp_mailing_lists', array('class'=>'col-sm-4 control-label')); ?>
            <div class="col-sm-8">
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
}

?>