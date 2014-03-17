<table id="register" class="table">
	<thead>
		<tr>
			<th id="reg_item_del" class="col-1 col-sm-1 col-lg-1"> </th>
			<th id="reg_item_name" class="col-1 col-sm-1 col-lg-1"><?php echo lang('sales_item_name'); ?></th>
			<?php 
			if( $controller_name == "tickets") { ?>
			<th id="reg_item_record_item" class="col-1 col-sm-1 col-lg-1"><?php echo lang('tickets_code_ticket'); ?></th>
			<?php } else { ?>
			<th id="reg_item_number" class="col-1 col-sm-1 col-lg-1"><?php echo lang('sales_item_number'); ?></th>
			<?php }
			?>
			<th id="reg_item_price" class="col-1 col-sm-1 col-lg-1"><?php echo lang('sales_price'); ?></th>
			<th id="reg_item_qty" class="col-1 col-sm-1 col-lg-1">
            <!--Create condition if controller massage display duration(minute) label else Qty label-->
            <?php
                if($controller_name == "massages"){
                    echo lang('sales_duration');
                }else{
                    echo lang('sales_quantity'); 
                }
                ?>         
            </th>
			<th id="reg_item_discount" class="col-1 col-sm-1 col-lg-1"><?php echo lang('sales_discount'); ?></th>
			<th id="reg_item_total" class="col-1 col-sm-1 col-lg-1"><?php echo lang('sales_total'); ?></th>
			<?php 
			if($controller_name == "tickets") { ?>
			<th id="reg_item_seat_number" class="col-1 col-sm-1 col-lg-1"><?php echo lang('sales_seat_number'); ?></th>
			<th id="reg_item_vol" class="col-1 col-sm-1 col-lg-1"><?php echo lang('sales_vol'); ?></th>
			<?php }
			?>
		</tr>
	</thead>
	<tbody id="cart_contents">
		<?php if(count($cart)==0)	{ ?>
		<tr>
			<td colspan='9' style="height:60px;border:none;">
					<div class='warning_message' style='padding:7px;'><?php echo lang('sales_no_items_in_cart'); ?></div>
			</td>
		</tr>
		<?php	}
		else	{
		foreach(array_reverse($cart, true) as $line=>$item)		{
			if ($controller_name == "tickets") {
				$cur_item_info = isset($item['ticket_id']) ? $this->ticket->get_info($item['ticket_id']) : $this->ticket_kit->get_info($item['item_kit_id']);
			}
			
			?>
				<tr>
					<td colspan='9'>
					<?php
						echo form_open("$controller_name/edit_item/".$this->session->userdata("office_number")."/$line", array('class' => 'line_item_form')); ?>
						<?php echo form_hidden("line", $line); ?>
						<table class="table table-register">
								<tr id="reg_item_top">
									<td id="reg_item_del" class="col-1 col-sm-1 col-lg-1"><?php echo anchor("$controller_name/delete_item/".$this->session->userdata("office_number")."/$line",lang('common_delete'), array('class' => 'delete_item'));?></td>

									<td id="reg_item_name" class="col-1 col-sm-1 col-lg-1"><?php echo $item['name']; //echo character_limiter($item['name'], 20); ?></td>
									<!-- <td id="reg_item_number" class="col-1 col-sm-1 col-lg-1"> -->

                                    <?php if($controller_name == "massages"){}else{?>
                                    <td id="reg_item_number" class="col-1 col-sm-1 col-lg-1">

										<?php echo form_input(array('name'=>'number','value'=>$item_number[$line-1],'size'=>'6', 'class'=>'number', 'id' => 'number_'.$line));?>
									</td>
                                    <?php };?>
									<?php if ($this->Employee->has_module_action_permission('sales', 'edit_sale_price', $this->Employee->get_logged_in_employee_info()->person_id)){ ?>
									<td id="reg_item_price" class="col-1 col-sm-1 col-lg-1"><?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6', 'id' => 'price_'.$line));?></td>
									<?php }else{ ?>
									<td id="reg_item_price" class="col-1 col-sm-1 col-lg-1"><?php echo $item['price']; ?></td>
									<?php echo form_hidden('price',$item['price']); ?>
									<?php }	?>
									
									<td id="reg_item_qty" class="col-1 col-sm-1 col-lg-1">
									<?php if(isset($item['is_serialized']) && $item['is_serialized']==1){
										echo to_quantity($item['quantity']);
										echo form_hidden('quantity',to_quantity($item['quantity']));
										}else if($controller_name == 'massages' || $controller_name == 'bikes'){
                                            echo form_input(array('name'=>'quantity','value'=>to_quantity($item['quantity']),'size'=>'2', 'id' => 'quantity_'.$line));
                                        }else if($controller_name == 'tickets'){
                                            echo form_input(array('name'=>'quantity','value'=>to_quantity($item['quantity']),'size'=>'2', 'id' => 'quantity_'.$line));
                                        }else{
											echo to_quantity($item['quantity']);
											echo form_hidden('quantity',to_quantity($item['quantity']));
										}?>
									</td>
									
										<?php if ($this->Employee->has_module_action_permission('sales', 'give_discount', $this->Employee->get_logged_in_employee_info()->person_id)){ ?>
									<td class="col-1 col-sm-1 col-lg-1" id="reg_item_discount"><?php echo form_input(array('name'=>'discount','value'=>$item['discount'],'size'=>'6', 'id' => 'discount_'.$line));?></td>
									<?php }else{ ?>
									<td class="col-1 col-sm-1 col-lg-1" id="reg_item_discount"><?php echo $item['discount']; ?></td>
									<?php echo form_hidden('discount',$item['discount']); ?>
									<?php }	?>
									
					
									<td id="reg_item_total" class="col-1 col-sm-1 col-lg-1"><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
									<?php 
										if($controller_name == "tickets"){?>
                                        <td id="reg_seat_no" class="col-1 col-sm-1 col-lg-1">
                                        	<?php echo form_input(array('name'=>'seat_no','value'=>$seat_no[$line-1],'size'=>'6', 'class'=>'seat_no', 'id' => 'seat_no_'.$line));?>
                                        </td>
                                        <td id="reg_vol" class="col-1 col-sm-1 col-lg-1">
                                        	<?php echo form_input(array('name'=>'vol','value'=>$item_vol[$line-1],'size'=>'6', 'class'=>'vol', 'id' => 'vol_'.$line));?>
                                        </td>
                                    <?php }
									?>
									
								</tr>
			
								<tr id="reg_item_bottom">
                                                                    <?php if($controller_name == "tickets" || $controller_name == "massages" ){ ?>
									<td id="reg_item_descrip_label"><?php echo lang('sales_description_abbrv').':';?></td>
									<td id="reg_item_descrip" colspan="2" class="col-1 col-sm-1 col-lg-1">
										<?php if(isset($item['allow_alt_description']) && $item['allow_alt_description']==1){
											echo form_input(array('name'=>'description','value'=>$item['description'],'size'=>'20', 'id' => 'description_'.$line));
                                                                                }
                                                                                else{
											if ($item['description']!=''){
												echo $item['description'];
												echo form_hidden('description',$item['description']);
											}
                                                                                        else{
												echo 'None';
												echo form_hidden('description','');
											}
										}?>
									</td>
                                                                        
                                                                    <?php } ?>
                                                                      
									<td id="reg_item_serial_label" class="col-1 col-sm-1 col-lg-1">
										<?php if(isset($item['is_serialized']) && $item['is_serialized']==1  && $item['name']!=lang('sales_giftcard')){
											echo lang('sales_serial').':';
										}?>
									</td>
									<?php 
									if($controller_name == "tickets"){ ?>
									<td id="reg_item_date_departure" class="col-1 col-sm-1 col-lg-1" colspan="2">										
										<div class="bfh-datepicker" data-format="y-m-d" data-date="<?php echo $dates_departure[$line-1]!='0000-00-00' ? $dates_departure[$line-1] : 'today'; ?>" id="date_departure">
										</div>
									</td>
									<td id="reg_item_time_departure" class="col-1 col-sm-1 col-lg-1" colspan="2">
										<div id="times" class="bfh-timepicker" data-time="<?php echo $times_departure[$line-1] == '00:00:00' ? '00:00' : $times_departure[$line-1]; ?>">
										  <div class="input-group bfh-timepicker-toggle" data-toggle="bfh-timepicker">
										    <span class="input-group-addon">
										    <i class="glyphicon glyphicon-time"></i>
										    </span>
										    <input class="form-control" type="text" readonly="" placeholder="" name="times">
										  </div>
										</div>
									</td>
									<?php }
									?>
                                                                        
                                                                        <?php 
									if($controller_name == "bikes"){ ?>
									<td id="reg_item_date_departure" class="col-1 col-sm-1 col-lg-1" colspan="2">
										<?php //echo form_hidden("date_departure"); ?>
										
										<div class="bfh-datepicker" data-format="y-m-d" data-date="<?php echo $dates_departure[$line-1]!='0000-00-00' ? $dates_departure[$line-1] : 'today'; ?>" id="date_departure">
                                                                                </div>
									</td>
                                                                        <td id="reg_item_date_departure" class="col-1 col-sm-1 col-lg-1" colspan="2">
										<?php //echo form_hidden("date_departure"); ?>
										
										<div class="bfh-datepicker" data-format="y-m-d" data-date="<?php echo $dates_departure[$line-1]!='0000-00-00' ? $dates_departure[$line-1] : 'today'; ?>" id="date_departure">
										</div>
									</td>
									<?php }
									?>
                                                                        
									<td id="reg_item_serial" class="col-1 col-sm-1 col-lg-1">
										<?php if(isset($item['is_serialized']) && $item['is_serialized']==1  && $item['name']!=lang('sales_giftcard'))	{
											echo form_input(array('name'=>'serialnumber','value'=>$item['serialnumber'],'size'=>'20', 'id' => 'serialnumber_'.$line));
										}else{
											echo form_hidden('serialnumber', '');
										}?>
									</td>
								</tr>
						</table>
					</form>
				  </td>
				</tr>
			<?php
			}
		}?>
	</tbody>
</table>