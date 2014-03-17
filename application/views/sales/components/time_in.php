<div id='time_departure_shell'>
    <?php echo form_open("$controller_name/set_time_in",array('id'=>'set_time_in_form')); ?>  
      <label id="time_departure_label" for="time_in">
        <?php echo lang('massages_time_in'); ?> 
      </label>
      <div class="bfh-timepicker" data-time="<?php echo $time_in ?>">
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
      <?php echo form_input(array('name'=>'btn_time_in','value'=>lang('massages_add_time_in'),'class'=>'none btn btn-primary btn-sm', 'role'=>'button')); ?>
    <?php echo form_close(); ?>
</div>