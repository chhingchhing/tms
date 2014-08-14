
            <div class="panel-body" id="receipt_header">
                <?php 
                $office_id = $this->Office->get_office_id($this->session->userdata("office_number"));
                $office_info = $this->Office->get_info($office_id);
                ?>
                <?php if ($this->session->userdata("office_number")) { ?>
                    <div id="company_logo"><?php echo img(array('src' => $this->Appconfig->get_logo_image())); ?></div>
                <?php } ?>
                <div id="company_address"><?php echo nl2br($office_info->ofc_address); ?></div>
                <div id="company_phone"><?php echo $office_info->ofc_phone; ?></div>
                <?php if ($office_info->ofc_website) { ?>
                    <div id="website"><?php echo $office_info->ofc_website; ?></div>
                <?php } ?>
                <div id="sale_receipt"><?php echo $receipt_title; ?></div>
                <div id="sale_time"><?php echo date("d/m/Y h:i a", strtotime($transaction_time)) ?></div>
            <div id="receipt_general_info">
                <?php
                // if (isset($customer)) {
                    ?>
                    <!-- <div id="customer"><?php //echo lang('customers_customer') . ": " . $customer; ?></div> -->
                    <?php
                // }
                ?>
                <?php
                $pieces = explode(' ', $sale_id);
                ?>
                <div id="sale_id"><?php echo '<b>' . lang('sales_id') . ": " . '</b> W'.$office_id . '-'.str_pad($pieces[1], 6, '0', STR_PAD_LEFT); ?></div>
                <div id="employee"><?php echo '<b>' . lang('employees_employee') . ": " . '</b>' . $employee; ?></div>
                <?php if ($controller_name == "massages" || $controller_name == "bikes") {
                    ?>
                    <div id="time_departure"><?php echo lang('massages_time_in') . ": " . $time_in; ?></div>
                    <div id="time_departure"><?php echo lang('massages_time_out') . ": " . $time_out; ?></div>
                <?php }
                ?>
            </div>
            <?php
            foreach (array_reverse($cart, true) as $line => $item) {
                $discount_exists = false;
                if ($item['discount'] > 0) {
                    $discount_exists = true;
                }
            }
            ?>

            <?php 
            if ($controller_name == "tickets") { ?>
                <?php $this->load->view("sales/components/receipt_ticket"); ?>
            <?php } else if($controller_name == "massages") {
                $this->load->view("sales/components/receipt_massages");
            } else { ?>

            <table id="receipt_items">
                <tr>
                    <th class="col-1 col-sm-1 col-lg-1" style="width:<?php echo $discount_exists ? "33%" : "49%"; ?>;"><?php echo lang('items_item'); ?></th>
                    <th class="col-1 col-sm-1 col-lg-1" style="width:20%;"><?php echo lang('common_price'); ?></th>
                    <th class="col-1 col-sm-1 col-lg-1" style="width:15%;">
                        <?php
                        if ($controller_name == "massages") {
                            echo lang('sales_duration');
                        } else {
                            echo lang('sales_quantity');
                        }
                        ?>
                    </th>

                    <?php if ($controller_name == "bikes") { ?>
                        <th class="col-1 col-sm-1 col-lg-1" style="width:15%;"><?php echo lang('sales_num_day'); ?></th>
                    <?php } ?>

                    <?php
                    if ($discount_exists) {
                        ?>
                        <th class="col-1 col-sm-1 col-lg-1" style="width:16%;"><?php echo lang('sales_discount'); ?></th>
                        <?php
                    }
                    ?>
                    <th class="col-1 col-sm-1 col-lg-1" style="width:16%;"><?php echo lang('sales_total'); ?></th>
                    <?php if ($controller_name == "tickets") { ?>
                        <th class="col-1 col-sm-1 col-lg-1" style="width:16%;"><?php echo lang('sales_seat_number'); ?></th>
                        <th class="col-1 col-sm-1 col-lg-1" style="width:16%;"><?php echo lang('sales_vol'); ?></th>
                        <th class="col-1 col-sm-1 col-lg-1" style="width:16%;"><?php echo lang('sales_date_departure'); ?></th>
                        <th class="col-1 col-sm-1 col-lg-1" style="width:16%;"><?php echo lang('sales_time_departure'); ?></th>
                    <?php }
                    ?>

                </tr>
                <?php foreach (array_reverse($cart, true) as $line => $item) { ?>
                    <tr>
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'></span><span class='short_name'><?php echo $item['name']; ?></span></td>
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo to_currency($item['price']); ?></td>
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo to_quantity($item['quantity']); ?></td>
                        <?php if ($controller_name == "bikes") { ?>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo (round(abs(strtotime($return_dates[$item['line'] - 1]) - strtotime($rent_dates[$item['line'] - 1])) / 86400)) + 1; ?></td>
                        <?php } ?>
                        <?php
                        if ($discount_exists) {
                            ?>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $item['discount']; ?></td>
                            <?php
                        }
                        ?>
                            
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo to_currency($item['price'] * $item['quantity'] - $item['discount']); ?></td>
                        <?php if ($seat_no || $controller_name == "tickets") { ?>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $seat_no[$item['line'] - 1]; ?></td>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $item_vol[$item['line'] - 1]; ?></td>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $dates_departure[$item['line'] - 1]; ?></td>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $times_departure[$item['line'] - 1]; ?></td>
                        <?php }
                        ?>
                    </tr>
                    <tr>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="2" style='text-align:left;'><?php echo '<strong>Description:</strong> ' . $item['description']; ?></td>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="2" style='text-align:left;'><?php echo isset($item['serialnumber']) ? $item['serialnumber'] : ''; ?></td>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="2" style='text-align:left;'><?php echo '&nbsp;'; ?></td>
                    </tr>
                    <?php
                }
                ?> 
                <tr>
                    <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;border-top:2px solid #000000;'><?php echo lang('sales_sub_total'); ?></td>
                    <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;border-top:2px solid #000000;'><?php echo to_currency($subtotal); ?></td>

                </tr>
                <?php
                // if ($controller_name == "tickets") { ?> 
                <tr>
                    <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right'><?php echo lang('sales_deposit'); ?></td>
                    <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;'><?php echo to_currency($deposit_price); ?></td>
                </tr>
                <tr>
                    <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right'><?php echo lang('sales_balance'); ?></td>
                    <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;'><?php echo to_currency($total - $deposit_price); ?></td>
                </tr>
                <?php //}
                 ?>

                <?php foreach ($taxes as $name => $value) { ?>
                    <tr>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo $name; ?>:</td>
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;'><?php echo to_currency($value); ?></td> 
                    </tr>
                <?php } ?>

                <tr>
                    <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo lang('sales_total'); ?></td>
       
                    <td class="col-1 col-sm-1 col-lg-1" style='text-align:right'><?php echo $this->config->item('round_cash_on_sales') ? to_currency((round_to_nearest_05($total))) : to_currency($total); ?>
                    </td>
                   
                </tr>

                <!-- Show total in Riels -->
                <tr>
                    <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo lang('sales_total_in_riels'); ?></td>
                   <?php 
                   $symbol = $this->currency_model->get_currency_rate_by_type_name($this->config->item('default_currency'))->symbol;
                   ?>
                    <td class="col-1 col-sm-1 col-lg-1" style='text-align:right'><?php echo $total_in_riels . ' ' . ucfirst($symbol); ?></td>
                </tr>

                <tr><td colspan="8">&nbsp;</td></tr>

                <?php
                foreach ($payments as $payment_id => $payment) {
                    ?>
                    <tr>
                        <td  class="col-1 col-sm-1 col-lg-1" colspan="6" style="text-align:right;"><?php echo (isset($show_payment_times) && $show_payment_times) ? date(get_date_format() . ' ' . get_time_format(), strtotime($payment['payment_date'])) : lang('sales_payment'); ?></td>
                        <td  class="col-1 col-sm-1 col-lg-1" style="text-align:right;">
                            <?php
                                $splitpayment = explode(':', $payment['payment_type']);
                                echo $splitpayment[0];
                            ?> 
                        </td>
                            
                            <td class="col-1 col-sm-1 col-lg-1" style="text-align:right"><?php echo $this->config->item('round_cash_on_sales') && $payment['payment_type'] == lang('sales_cash') ? to_currency(round_to_nearest_05($payment['payment_amount'])) : to_currency($payment['payment_amount']); ?>  </td>
                         </tr>
                    <?php
                }
                ?>  
                <tr><td colspan="8">&nbsp;</td></tr>

                <?php foreach ($payments as $payment) { ?>
                    <?php if (strpos($payment['payment_type'], lang('sales_giftcard')) !== FALSE) { ?>
                        <tr>
                            <td class="col-1 col-sm-1 col-lg-1" colspan="5" style="text-align:right;"><?php echo lang('sales_giftcard_balance'); ?></td>
                            <td class="col-1 col-sm-1 col-lg-1" colspan="1" style="text-align:right;"><?php echo $payment['payment_type']; ?> </td>
                            <td class="col-1 col-sm-1 col-lg-1" colspan="2" style="text-align:right"><?php echo to_currency($this->Giftcard->get_giftcard_value(end(explode(':', $payment['payment_type'])))); ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>

                <?php if ($amount_change >= 0) { ?>
                    <tr>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo lang('sales_change_due'); ?></td>
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:right'>
                            <?php echo $this->config->item('round_cash_on_sales') ? to_currency(round_to_nearest_05($amount_change)) : to_currency($amount_change); ?> </td>
                    </tr>
                    <?php
                } else {
                    ?>
                    <tr>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="5" style='text-align:right;'><?php echo lang('sales_amount_due'); ?></td>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="3" style='text-align:right'><?php echo $this->config->item('round_cash_on_sales') ? to_currency(round_to_nearest_05($amount_change * -1)) : to_currency($amount_change * -1); ?>

                            <?php echo to_currency($amount_change * -1); ?></td>
                    </tr>   
                    <?php
                }
                if ($ref_no) {
                    ?>
                    <tr>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="5" style='text-align:right;'><?php echo lang('sales_ref_no'); ?></td>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="3" style='text-align:right'><?php echo $ref_no; ?></td>
                    </tr>   
                    <?php
                }
                ?>
                <tr>
                    <td colspan="8" align="right">
                        <?php
                        if ($show_comment_on_receipt == 1) {
                            echo $comment;
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <?php }
            ?>

            <div id="sale_return_policy">
                <?php echo nl2br($this->config->item('return_policy')); ?>
                <br />   

            </div>
            <div id='barcode'>
                <?php echo "<img src='" . site_url('barcode') . "?barcode=$sale_id&text=W$office_id-".str_pad($pieces[1], 6, '0', STR_PAD_LEFT)."' />"; ?>
            </div>
            <?php if (!$this->config->item('hide_signature')) { ?>

                <div id="signature">

                    <?php foreach ($payments as $payment) { ?>
                        <?php if (strpos($payment['payment_type'], lang('sales_credit')) !== FALSE and $this->config->item('enable_credit_card_processing')) { ?>
                            <?php echo lang('sales_signature'); ?>  
                            <?php
                            $is_integrated_credit_sale = $this->config->item('enable_credit_card_processing');
                            echo lang('sales_card_statement');
                            break;
                            ?>

                        <?php } ?>
                    <?php } ?>

                </div>
            <?php } ?>
        </div>