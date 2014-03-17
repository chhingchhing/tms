<?php var_dump($times_departure); ?>
<div id="times" class="bfh-timepicker" data-time="<?php echo $times_departure[$line-1] == '00:00:00' ? '00:00' : $times_departure[$line-1]; ?>">
  <div class="input-group bfh-timepicker-toggle" data-toggle="bfh-timepicker">
    <span class="input-group-addon">
    <i class="glyphicon glyphicon-time"></i>
    </span>
    <input class="form-control" type="text" readonly="" placeholder="" name="times">
  </div>
</div>