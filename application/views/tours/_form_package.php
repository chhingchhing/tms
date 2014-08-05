<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title" id="myModalLabel"><?php echo lang("item_kits_info"); ?></h4>
    </div>
      
    <div class="modal-body">
      <?php
      echo form_open('tours/save_package/'.$item_kit_info->item_kit_id,array('id'=>'item_kit_form','post', 'class'=>'add_update', 'role'=>'form','class'=>'form-horizontal'));
      ?>
      <!--Ms error show-->
        <div id="error" style="display: none"><div id="getSmsError"></div><span class="cross">X</span>
            <?php echo form_hidden("baseURL", base_url()); ?>
        </div>
        <div id="success" style="display: none"><div id="getSmsSuccess"></div><span class="cross">X</span></div>
        <div id="required_fields_message"><b>*</b><?php echo lang('common_fields_required_message'); ?></div>

         <ul id="error_message_box"> </ul>
         <?php 
         echo form_hidden("controller_name", $controller_name);
         ?>
      
          <?php $this->load->view("tours/form_basic_info_package"); ?>
      </div>
      

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('common_close');?></button>
         <?php
              /*if ($this->uri->segment(2) == "sales") {
                  $btnName = "submitTour";
              } else {
                  $btnName = "btn_submit_tours";
              }*/
              echo form_submit(array(
                  'name' => 'btn_tours_package',
                  'id' => 'btn_tours_package', 
                  'value' => lang('common_submit'),
                  'class' => 'submit_button float_right btn btn-primary')
              );
              ?>
    </div>
    <?php 
          echo form_close();
        ?>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->