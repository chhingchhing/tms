<div id='time_departure_shell'>
    <?php echo form_open("$controller_name/set_time_out",array('id'=>'set_time_out_form')); ?>  
      <label id="time_departure_label" for="time_out">
        <?php echo lang('massages_time_out'); ?> 
      </label>
      <div class="bfh-timepicker" data-time="<?php echo $time_out ?>">
        <div class="input-prepend bfh-timepicker-toggle" data-toggle="bfh-timepicker">
          <span class="add-on"><i class="icon-time"></i></span>
          <input type="text" class="input-medium" readonly>
        </div>
        <div class="bfh-timepicker-popover">
          <table class="table">
            <tbody>
              <tr>
                <td class="hour">
                  <a class="next" href="#"><i class="icon-chevron-up"></i></a><br>
                  <input type="text" class="input-mini" readonly><br>
                  <a class="previous" href="#"><i class="icon-chevron-down"></i></a>
                </td>
                <td class="separator">:</td>
                <td class="minute">
                  <a class="next" href="#"><i class="icon-chevron-up"></i></a><br>
                  <input type="text" class="input-mini" readonly><br>
                  <a class="previous" href="#"><i class="icon-chevron-down"></i></a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <?php echo form_input(array('name'=>'btn_time_out','value'=>lang('massages_add_time_out'),'class'=>'none btn btn-primary btn-sm', 'role'=>'button')); ?>
    <?php echo form_close(); ?>
</div>