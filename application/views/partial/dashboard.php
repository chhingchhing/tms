<div class="panel panel-info" id="dashboard">
    <div class="panel-heading">
        <h3 id="dash_text" class="panel-title"><?php echo lang('dashboard_welcome'); ?></h3>
    </div> 
    <div class="panel-body">
        <div class="row wrap-office">
            <?php
            $controller = "dashboard";
            foreach ($allowed_offices->result() as $office) {
                ?>
                <div id="dash_img" class="col-2 col-sm-2 col-lg-2 office-img">
                    <a href="<?php echo site_url("$controller/world/$office->alias_name/$office->office_id"); ?>">
                        <!-- <img src="<?php //echo base_url() . 'assets/images/offices/office_' . $office->office_id . '.png'; ?>" border="0" alt="Menubar Image" class="img-rounded" /> -->
                        <img src="<?php echo base_url() . 'images/menubar/' .'home_offices.png'; ?>" border="0" alt="Menubar Image" class="img-rounded" />
                    </a>
                    <div class="row" id="office"><?php echo '<p>' . $office->office_name . '</p>'; ?></div></a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>