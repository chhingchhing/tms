<table class="table"> 
    
        <?php if (count($cart) == 0) { ?>   
       <tr>
            <?php if ($controller_name == "tours") {?>
                <th style="border: 0; width: 35%;"><?php echo lang('sales_item_name'); ?></th>
                <th style="border: 0;"><?php echo lang('sales_price'); ?></th>
                <th style="border: 0; text-align: center;"><?php echo lang('sales_quantity'); ?></th>
                <th style="border: 0;"><?php echo lang('sales_discount'); ?></th>
                <th style="border: 0;"><?php echo lang('sales_total'); ?></th>
                <th style="border: 0;">&nbsp;</th>
                <th style="border: 0;">&nbsp;</th>
            <?php } elseif ($controller_name == "tickets") { ?>
                <th style="border: 0;"> <?php echo '<span>' . lang('tickets_code_name') . '<span/>'; ?> </th>
                <th style="border: 0;"><?php echo '<span>' . lang('tickets_code_ticket') .'</span>'; ?></th>
                <th style="border: 0; text-align: center;"><?php echo lang('sales_price'); ?></th>
                <th style="border: 0; text-align: center;"><?php echo lang('sales_quantity'); ?></th>
                <th style="border: 0; text-align: center;"> <?php echo '<span>' . lang('sales_discount') . '</span>'; ?></th>
                <th style="border: 0;"> <?php echo '<span>' . lang('sales_total') . '</span>'; ?></th> 
            <?php } elseif ($controller_name == "bikes") { ?>
                <th style="border: 0;"> <?php echo '<span>' . lang('tickets_code_name') . '<span/>'; ?> </th>
                <th style="border: 0;"><?php echo '<span>' . lang('tickets_code_ticket') .'</span>'; ?></th>
                <th style="border: 0; text-align: center;"><?php echo lang('sales_price'); ?></th>
                <th style="border: 0; text-align: center;"><?php echo lang('sales_quantity'); ?></th>
                <th style="border: 0; text-align: center;"> <?php echo '<span>' . lang('sales_discount') . '</span>'; ?></th>
                <th style="border: 0;"> <?php echo '<span>' . lang('sales_total') . '</span>'; ?></th> 
            <?php } elseif ($controller_name == "massages") { ?>
                <th style="border: 0;" width="30%"><?php echo lang('sales_item_name'); ?></th> 
                <th style="border: 0; text-align: center; width: 15%"><?php echo lang('sales_price'); ?></th>             
                <th style="border: 0; text-align: center; width: 15%"> <?php echo lang('sales_duration'); ?></th>
                <th style="border: 0; text-align: left;"><?php echo lang('sales_discount'); ?></th>
                <th style="border: 0;"><?php echo lang('sales_total'); ?></th>      

                <th style="border: 0; text-align: center; width: 15%"><?php echo 'Massager'; ?></th>    

            <?php } else { ?>
                <th style="border: 0;"><?php echo lang('sales_item_name'); ?></th> 
                <th style="border: 0; text-align: center;"><?php echo lang('sales_price'); ?></th> 
                <th style="border: 0; text-align: center;"><?php echo lang('sales_quantity'); ?></th>
                <th style="border: 0; text-align: center;"><?php echo lang('sales_discount'); ?></th>
                <th style="border: 0;"><?php echo lang('sales_total'); ?></th> 
            <?php } ?>

        <?php } else { ?>
            <?php if ($controller_name == "tours") { ?>
                    <th width="280" style="border: 0; width: 35%;"><?php echo lang('sales_item_name'); ?></th>
                    <th width="130" style="border: 0;"><?php echo lang('sales_price'); ?></th>
                    <th width="100" align="center" style="border: 0;"><?php echo lang('sales_quantity'); ?></th>
                    <th align="center" style="border: 0; "><?php echo lang('sales_discount'); ?></th>
                    <th style="border: 0;"><?php echo lang('sales_total'); ?></th>
                    <th style="border: 0;">&nbsp;</th>
                    <th style="border: 0;">&nbsp;</th>
            <?php } else { ?>
                <?php if ($controller_name == "tickets") { ?>
                    <th style="border: 0;"> <?php echo '<span>' . lang('tickets_code_name') . '<span/>'; ?> </th>
                    <th style="border: 0;"><?php echo '<span>' . lang('tickets_code_ticket') . '</span>'; ?></th>
                    <th style="border: 0; text-align: center;"><?php echo lang('sales_price'); ?></th>
                    <th style="border: 0; text-align: center;"><?php echo lang('sales_quantity'); ?></th>
                    <th style="border: 0; text-align: center;"> <?php echo '<span>' . lang('sales_discount') . '</span>'; ?></th>
                    <th style="border: 0;"> <?php echo '<span>' . lang('sales_total') . '</span>'; ?></th>
                <?php } elseif ($controller_name == "bikes") { ?>
                    <th style="border: 0;"><?php echo lang('sales_bike_type'); ?></th>
                    <th style="border: 0;"><?php echo lang('sales_bike_name'); ?></th>
                    <th style="border: 0;"> <?php echo lang('sales_price'); ?></th>
                    <th style="border: 0;"> <?php echo lang('sales_quantity'); ?></th>
                    <th style="border: 0;"><?php echo lang('sales_total'); ?></th>
                <?php } elseif ($controller_name == "massages") { ?>
                    <th style="border: 0;" width="27%"><?php echo lang('sales_item_name'); ?></th> 
                    <th style="border: 0; text-align: center; width: 15%" width="15%"><?php echo lang('sales_price'); ?></th>                                  
                    <th style="border: 0; text-align: center; width: 15%"> <?php echo lang('sales_duration'); ?></th>
                    <th style="border: 0; text-align: left; width: 13% "><?php echo lang('sales_discount'); ?></th>
                    <th style="border: 0;"><?php echo lang('sales_total'); ?></th>

                    <th style="border: 0; text-align: center; width: 15%" width="15%"><?php echo 'Massager'; ?></th>

                <?php } else { ?>
                    <th style="border: 0;"><?php echo lang('sales_item_name'); ?></th>
                    <th style="border: 0;"><?php echo lang('sales_price'); ?></th>
                    <th style="border: 0;"> <?php echo lang('sales_quantity'); ?></th>
                    <th style="border: 0;"><?php echo lang('sales_discount'); ?></th>
                    <th style="border: 0;"><?php echo lang('sales_total'); ?></th> 
                <?php } ?>
                <th style="border: 0;">&nbsp;</th>

            <?php } ?>
            </tr>
        <?php } ?>
    <!-- </tr> -->

    <?php if (count($cart) == 0) { ?>
        <tr>
            <?php if ($controller_name == "tours" || $controller_name == "massages") { ?>
                <td colspan="5"><div class='warning_message' style='padding:7px;'><?php echo lang('sales_no_items_in_cart'); ?></div></td>
            <?php } else { ?>
                <td colspan="6"> <div class='warning_message' style='padding:7px;'><?php echo lang('sales_no_items_in_cart'); ?></div></td>
            <?php } ?>
        </tr>  
     <?php } else { ?>
        <?php foreach (array_reverse($cart, true) as $line => $item) { ?>
            <tr>
                <td colspan="9">
                <?php
                if ($controller_name == "tickets") {
                    $cur_item_info = isset($item['ticket_id']) ? $this->ticket->get_info($item['ticket_id']) : $this->ticket_kit->get_info($item['item_kit_id']);
                }
                ?>
                <?php echo form_open("$controller_name/edit_item/" . $this->session->userdata("office_number") . "/$line", array('class' => 'line_item_form')); ?>
            <table class="table table-register">
                <tr>
                <?php echo form_hidden("line", $line); ?>
                <?php if ($controller_name == "tickets") { ?>
                    <td width="50%"> <?php echo $item['name']; ?></td>
                    <td> <?php echo form_input(array('name' => 'number', 'value' => $item_number[$line - 1], 'size' => '10', 'class' => 'number', 'id' => 'number_' . $line)); ?></td>
                <?php } elseif($controller_name == "massages") { ?>
                    <td width="30%"><?php echo $item['name']; ?></td> 
                <?php } else { ?>
                    <td><?php echo $item['name']; ?></td> 
                <?php } ?>
                <?php if ($controller_name == "bikes") { ?>
                    <td> <?php echo form_input(array('name' => 'number', 'value' => $item['bike_code'], 'size' => '13', 'class' => 'number', 'id' => 'number_' . $line)); ?></td> 
                <?php } ?>
                <?php if ($this->Employee->has_module_action_permission($controller_name, 'edit_sale_price', $this->Employee->get_logged_in_employee_info()->employee_id)) { ?>
                    <?php if ($controller_name == 'bikes') { ?>
                        <td> <?php echo form_input(array('name' => 'price', 'value' => $item['price'], 'size' => '13', 'id' => 'price_' . $line)); ?></td>
                    <?php } else { ?>
                        <td> <?php echo form_input(array('name' => 'price', 'value' => $item['price'], 'size' => '10', 'id' => 'price_' . $line)); ?></td>
                    <?php } ?>
                <?php } else { ?>
                    <td>
                    <span style="padding: 0 35px"><?php echo $item['price']; ?></span> 
                        <?php echo form_hidden('price', $item['price']); ?>
                    </td> 
                <?php } ?>


                <?php if (isset($item['is_serialized']) && $item['is_serialized'] == 1) { ?>
                    <td>
                        <span style="padding: 0 35px"><?php echo to_quantity($item['quantity']); ?></span>
                        <?php
                        // echo to_quantity($item['quantity']);
                        echo form_hidden('quantity', to_quantity($item['quantity']));
                        ?>
                    </td>
                <?php } else if ($controller_name == 'massages') { ?>
                    <td> <?php echo form_input(array('name' => 'quantity', 'value' => to_quantity($item['quantity']), 'size' => '5', 'id' => 'quantity_' . $line)); ?></td>
                <?php } else if ($controller_name == 'tickets') { ?>
                    <td> <?php echo form_input(array('name' => 'quantity', 'value' => to_quantity($item['quantity']), 'size' => '7', 'id' => 'quantity_' . $line)); ?></td>
                <?php } else { ?>
                    <td> <?php echo form_input(array('name' => 'quantity', 'value' => to_quantity($item['quantity']), 'size' => '7', 'id' => 'quantity_' . $line)); ?></td>
                <?php }
                ?>

                <?php if ($this->Employee->has_module_action_permission($controller_name, 'give_discount', $this->Employee->get_logged_in_employee_info()->employee_id)) { ?>
                    <?php if ($controller_name == "massages") { ?>
                        <td> <?php echo form_input(array('name' => 'discount', 'value' => $item['discount'], 'size' => '5', 'id' => 'discount_' . $line)); ?></td>
                    <?php } else { ?>
                          <td><?php echo form_input(array('name' => 'discount', 'value' => $item['discount'], 'size' => '10', 'id' => 'discount_' . $line)); ?></td>
                    <?php } ?>
                <?php } else { ?>
                    <td>
                        <span style="padding: 0 35px"><?php echo $item['discount']; ?></span>
                        <?php echo form_hidden('discount', $item['discount']); ?>
                    </td> 
                <?php } ?>  
                <?php if($controller_name == "massages"){?>
                    <td style="text-align: center;"> <?php echo '<input type="text" value="' . to_currency($item['price'] * $item['quantity'] - $item['discount']) . '" readonly="readonly" size="4" style="text-align: center;"/>'; ?></td>
                    <td><?php 
                    $info_massager = $this->Employee->get_info($item['massager']);
                    $massager_name = $info_massager->first_name . ' ' . $info_massager->last_name;

                    echo '<input type="text" value="' . $massager_name . '" size="9" id="each_massager" name="each_massager_id"/>'; 
                    echo form_hidden("each_massager", $item['massager']);
                    ?></td>
                <?php } else{?>
                    <td> <?php echo '<input type="text" value="' . to_currency($item['price'] * $item['quantity'] - $item['discount']) . '" readonly="readonly" size="7" />'; ?></td>   
                 <?php } ?>
               <td> <?php echo anchor("$controller_name/delete_item/" . $this->session->userdata("office_number") . "/$line", ' ', array('class' => 'delete_item glyphicon glyphicon-remove-circle')); ?></td>
                <?php // if ($controller_name == "tickets" || $controller_name == "massages") {  ?>
                <?php if ($controller_name == "massages") { ?>
                <tr>
                    <td colspan="5">
                        <?php echo lang('sales_description_abbrv') . ':'; ?>

                        <?php
                        if (isset($item['allow_alt_description']) && $item['allow_alt_description'] == 1) {
                            echo form_input(array('name' => 'description', 'value' => $item['description'], 'size' => '20', 'id' => 'description_' . $line));
                        } else {
                            if ($item['description'] != '') {
                                echo $item['description'];
                                echo form_hidden('description', $item['description']);
                            } else {
                                echo 'None';
                                echo form_hidden('description', '');
                            }
                        }
                        ?>

                    </td>
                </tr>
                <?php } ?>
                <?php if ($controller_name != "tickets") { ?>
                    <td>
                        <?php
                        if (isset($item['is_serialized']) && $item['is_serialized'] == 1 && $item['name'] != lang('sales_giftcard')) {
                            echo lang('sales_serial') . ':';
                        }
                        ?>
                    </td> 
                <?php } ?> 
            </tr>
            <tr>                              
                <?php if ($controller_name == "tickets" OR $controller_name == "tours") { ?>
                    <td style="border: 0;"> 
                        <div id="date_departure" class="bfh-datepicker" data-format="y-m-d" data-date="<?php echo $dates_departure[$line - 1] != '0000-00-00' ? $dates_departure[$line - 1] : 'today'; ?>">
                        </div>
                    </td>                               
                    <td style="border: 0;">
                        <select class="form-control" name="times" id="times" style="width: 119px;">
                            <?php
                            echo '<option value="">Select</option>';
                            for ($hours = 0; $hours < 24; $hours++) { // the interval for hours is '1'
                                for ($mins = 0; $mins < 60; $mins+=10) { // the interval for mins is '15'
                                    $time = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($mins, 2, '0', STR_PAD_LEFT);
                                    // $t = date("h:i A", strtotime($time));
                                    $t = date(get_time_format(), strtotime($time));
                                    if ($times_departure[$line - 1] == $time . ':00') {
                                        echo '<option selected="selected" value="' . $time . ':00">' . $t . '</option>';
                                    } else {
                                        echo '<option value="' . $time . ':00">' . $t . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </td>
                <?php }
                if ($controller_name == "tickets") { ?>
                    <td style="border: 0;"> <?php echo form_input("company_name", $hotel_names[$line - 1], "size='10' placeholder='Hotel name' class='txt_hotel'"); ?></td>
                    <td style="border: 0;"> <?php echo form_input("room_number", $room_numbers[$line - 1], "size='12' placeholder='Room number' class='room_num'"); ?></td>  
                    <td style="border: 0;"> <?php echo form_input(array('name' => 'seat_no', 'value' => $seat_no[$line - 1], 'size' => '8', 'placeholder' => 'Seat No', 'class' => 'seat_no', 'id' => 'seat_no_' . $line)); ?></td>
                <?php }
                ?>
                <?php if ($controller_name != "tickets") { ?>

                    <td style="border: 0;">  
                        <?php
                        if (isset($item['is_serialized']) && $item['is_serialized'] == 1 && $item['name'] != lang('sales_giftcard')) {
                            echo form_input(array('name' => 'serialnumber', 'value' => $item['serialnumber'], 'size' => '20', 'id' => 'serialnumber_' . $line));
                        } else {
                            echo form_hidden('serialnumber', '');
                        }
                        ?>
                    </td>

                <?php } ?>   
                <td style="border: 0;">&nbsp;</td>
                <td style="border: 0;">&nbsp;</td>
            </tr>
        </table>
                <?php echo form_close(); ?>   

            </td>
            </tr>                  
        <?php } ?>

    <?php } ?>

</table>