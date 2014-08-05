<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $metas = array(
            array('name' => 'X-UA-Compatible', 'content' => 'IE=edge', 'type' => 'equiv'),
            array('name' => 'content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv'),
            array('name' => 'robots', 'content' => 'no-cache'),
            array('name' => 'robots', 'content' => 'no-cache'),
            array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1'),
            array('name' => 'author', 'content' => 'Tour Management System')
        );
        echo meta($metas);
        ?>

        <base href="<?php echo base_url(); ?>" />
        <title>
        <?php 
            if ($this->session->userdata("office_number")) {

            $office_id = $this->Office->get_office_id($this->session->userdata("office_number"));
            echo $this->Office->get_info($office_id)->ofc_company; 
        } else {
            echo $this->config->item('company');
        }
        ?>
        </title>
        <link rel="icon" href="<?php echo base_url(); ?>codingate.ico" type="image/x-icon"/>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <!-- Plugin for date picker -->
        <!--[if IE]><script type="text/javascript" src="scripts/jquery.bgiframe.js"></script><![endif]-->
        <!-- jQuery -->
        <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script> -->
        <?php
        echo link_tag("assets/dist/css/bootstrap.css");
        echo link_tag("assets/dist/css/bootstrap-theme.css");
        echo link_tag("css/jquery-ui-1.10.3.custom.min.css");
        echo link_tag("css/date_picker.css");

        echo link_tag("assets/timepicker/css/bootstrap-formhelpers.min.css");
        // echo link_tag("assets/tokenfield/css/bootstrap-tokenfield.min.css");
        echo link_tag("css/reset.css");
        ?>
        <link rel="stylesheet" href="<?php echo base_url()."assets/tokeninput/css/token-input.css"; ?>" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url()."assets/tokeninput/css/token-input-facebook.css"; ?>" type="text/css" />
        
        <style type="text/css">
            html {
                overflow: auto;
            }
        </style>

    </head>
    <body>
        <?php echo form_hidden("baseURL", base_url()); ?>
        <?php echo form_hidden("controller_name", $controller_name); ?>
        <div class="container">

            <?php if ($this->uri->segment(1) !== "dashboard" OR $this->uri->segment(2)) {
                ?>
                <div id="menubar" class="row content_home">
                    <div id="menubar_container">
                        <div id="logo" class="col-md-2">
                            <!-- Logo of TMS -->
                            <a href="<?php echo site_url("dashboard"); ?>"><?php
                                echo img(
                                        array(
                                            'src' => $this->Appconfig->get_logo_image()
                                ));
                                ?></a>
                        </div>
                        <?php
                        $controller = $this->uri->segment(1);
                        $page = $this->uri->segment(2);
                        ?>
                        <?php
                        foreach ($allowed_modules->result() as $module) {
                            ?>
                            <?php if ($module->module_id != 'sales') { ?>
                            <div id="moudle_icon" class="col-md-1 menu_item_<?php echo $module->module_id; ?>">
                                <a href="<?php echo site_url("$module->module_id/$module->module_id/$module->alias_name"); ?>">
                                    <p class="p_office" id="menubar">   
                                        <img src="<?php echo base_url() . 'images/menubar/' . $module->module_id . '.png'; ?>" border="0" alt="Menubar Image" /><br>
                                        <?php
                                        if ($module->module_id == 'transportations') {
                                            echo ucfirst("transport");
                                        } elseif ($module->module_id == 'commissioners') {
                                            echo ucfirst("commissioner");
                                        } else {
                                            echo ucfirst($module->module_id);
                                        } ?>
                                    </p>
                                </a>  

                            </div>
                            <?php } ?>
                    <?php } ?>
                    </div>

                </div>

<?php } ?>
            <div class="panel-body row" id="content_area_wrapper">
