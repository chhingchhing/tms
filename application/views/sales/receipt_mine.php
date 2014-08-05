<?php $this->load->view("partial/header"); ?>
<?php
$is_integrated_credit_sale = false;
if (isset($error_message)) {
    echo '<h1 style="text-align: center;">' . $error_message . '</h1>';
    exit;
}
?>
<!--<div id="receipt_wrapper">-->
    
    <div  class="panel panel-info" id="print_receipt_mine">
        <div class="panel-heading" id="company_name"><p><?php echo $this->config->item('company'); ?></p></div>
        <div id="print_receipt">
            <div class="panel-body" id="receipt_header">
                <?php if ($this->config->item('company_logo')) { ?>
                    <div id="company_logo"><?php echo img(array('src' => $this->Appconfig->get_logo_image())); ?></div>
                <?php } ?>
                <div id="company_address"><?php echo nl2br($this->config->item('address')); ?></div>
                <div id="company_phone"><?php echo $this->config->item('phone'); ?></div>
                <?php if ($this->config->item('website')) { ?>
                    <div id="website"><?php echo $this->config->item('website'); ?></div>
                <?php } ?>
                <div id="sale_receipt"><?php echo $receipt_title; ?></div>
                <div id="sale_time"><?php echo $transaction_time ?></div>
           
            <div id="receipt_general_info">
                <?php
                if (isset($customer)) {
                    ?>
                    <div id="customer"><?php echo lang('customers_customer') . ": " . $customer; ?></div>
                    <?php
                }
                ?>
                <div id="sale_id"><?php echo '<b>'.lang('sales_id') . ": " . '</b>'.$sale_id; ?></div>
                <div id="employee"><?php echo '<b>'.lang('employees_employee') . ": ".'</b>'. $employee; ?></div>
                    <?php if ($controller_name == "massages") {
                        ?>
                        <div id="time_departure"><?php echo lang('massages_time_in') . ": " . $time_in; ?></div>
                        <div id="time_departure"><?php echo lang('massages_time_out') . ": " . $time_out; ?></div>
                    <?php }
                    ?>
            </div>
            <?php
            var_dump($cart);
            foreach (array_reverse($cart, true) as $line => $item) {
                $discount_exists = false;
                if ($item['discount'] > 0) {
                    $discount_exists = true;
                }
            }
            ?>
            <table id="receipt_items">
                <tr>
                    <th class="col-1 col-sm-1 col-lg-1" style="width:<?php echo $discount_exists ? "33%" : "49%"; ?>;"><?php echo lang('items_item'); ?></th>
                    <th class="col-1 col-sm-1 col-lg-1" style="width:20%;"><?php echo lang('common_price'); ?></th>
                    <th class="col-1 col-sm-1 col-lg-1" style="width:15%;">
                    <?php 
                        if($controller_name == "massages"){
                             echo lang('sales_duration');
                        }  else {
                            echo lang('sales_quantity');
                        }
                    ;?>
                    </th>
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
                <?php
                foreach (array_reverse($cart, true) as $line => $item) {
                    ?>
                    <tr>
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'></span><span class='short_name'><?php echo $item['name']; ?></span></td>
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo to_currency($item['price']); ?></td>
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo to_quantity($item['quantity']); ?></td>
                        <?php
                        if ($discount_exists) {
                            ?>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $item['discount']; ?></td>
        		        <?php
        		    }
        		    ?>
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo to_currency($item['price'] * $item['quantity'] - $item['price'] * $item['quantity'] * $item['discount'] / 100); ?></td>
                        <?php if ($seat_no || $controller_name == "tickets") { ?>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $seat_no[$item['line'] - 1]; ?></td>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $item_vol[$item['line'] - 1]; ?></td>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $dates_departure[$item['line'] - 1]; ?></td>
                            <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $times_departure[$item['line'] - 1]; ?></td>
                        <?php }
                        ?>
                    </tr>

                    <tr>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="2" style='text-align:left;'><?php echo 'Description: ' . $item['description']; ?></td>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="2" style='text-align:left;'><?php echo isset($item['serialnumber']) ? $item['serialnumber'] : ''; ?></td>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="2" style='text-align:left;'><?php echo '&nbsp;'; ?></td>
                    </tr>
    <!--here-->
                    <?php
                }
                ?>
                <tr>
                    <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;border-top:2px solid #000000;'><?php echo lang('sales_sub_total'); ?></td>
                    <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;border-top:2px solid #000000;'><?php echo to_currency($subtotal); ?></td>
                </tr>
                <?php
                if ($controller_name == "tickets") { ?> 
                <tr>
                    <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right'><?php echo lang('sales_deposit'); ?></td>
                    <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;'><?php echo to_currency($deposit_price); ?></td>
                </tr>
                <?php }
                 ?>

                <?php foreach ($taxes as $name => $value) { ?>
                    <tr>
                        <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo $name; ?>:</td>
                        <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;'><?php echo to_currency($value); ?></td> 
                    </tr>
                <?php }; ?>

                <tr>
                    <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo lang('sales_total'); ?></td>
                    <td class="col-1 col-sm-1 col-lg-1" style='text-align:right'><?php echo $this->config->item('round_cash_on_sales') ? to_currency(round_to_nearest_05($total)) : to_currency($total); ?></td>
                </tr>

        <!-- Show total in Riels -->
                <tr>
                    <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo lang('sales_total_in_riels'); ?></td>
                    <td class="col-1 col-sm-1 col-lg-1" style='text-align:right'><?php echo $total_in_riels.' '.lang('sales_currency_type'); ?></td>

                </tr>

                <tr><td colspan="8">&nbsp;</td></tr>

                <?php
                foreach ($payments as $payment_id => $payment) {
                    ?>
                    <tr>
                        <td  class="col-1 col-sm-1 col-lg-1" colspan="6" style="text-align:right;"><?php echo (isset($show_payment_times) && $show_payment_times) ? date(get_date_format() . ' ' . get_time_format(), strtotime($payment['payment_date'])) : lang('sales_payment'); ?></td>
                        <td  class="col-1 col-sm-1 col-lg-1" style="text-align:right;"><?php $splitpayment = explode(':', $payment['payment_type']);
                echo $splitpayment[0];
                    ?> </td>
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

            <div id="sale_return_policy">
        <?php echo nl2br($this->config->item('return_policy')); ?>
                <br />   

            </div>
            <div id='barcode'>
            <?php echo "<img src='" . site_url('barcode') . "?barcode=$sale_id&text=$sale_id' />"; ?>
            </div>
        	<?php if (!$this->config->item('hide_signature')) { ?>

                <div id="signature">

                    <?php foreach ($payments as $payment) { ?>
                        <?php if (strpos($payment['payment_type'], lang('sales_credit')) !== FALSE and $this->config->item('enable_credit_card_processing')) { ?>
                            <?php echo lang('sales_signature'); ?> --------------------------------- <br />	
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
    </div>
<!--end print_receipt-->
    <br>
    <div id="btn_print" class="col-12 col-sm-12 col-lg-12">
        <a href="<?php echo site_url($controller_name.'/sales/world_1'); ?>" role='button' class='btn btn-info'>New sale</a>
        <?php
    if ($this->Employee->has_module_action_permission('sales', 'edit_sale', $this->Employee->get_logged_in_employee_info()->employee_id)) {

    $pieces = explode(' ', $sale_id);
    echo form_open("$controller_name/change_sale/" . $this->session->userdata("office_number") . "/" . $pieces[1], array('id' => 'sales_change_form'));
    ?> 
    <button class="submit_button btn btn-info" id="edit_sale" onclick="submit()" > <?php echo lang('sales_edit'); ?> </button>
        <?php } 
            echo form_close();
        ?>
        <!-- <button class="submit_button btn btn-info" id="print_button" onclick="print_receipt()" > <?php echo lang('sales_print'); ?> </button> -->
        <button class="submit_button btn btn-info" id="print" > <?php echo lang('sales_print'); ?> </button>
    </div>
    <br/>
 </div>

</div>
<?php $this->load->view("partial/footer"); ?>
<!--<br>-->
<div id="feedback_bar"></div>

<?php 
if ($this->config->item('print_after_sale')) {
    ?>
    <script type="text/javascript">
        $(window).bind("load", function() {
            window.print();
        });
    </script>
<?php } ?>
<!--<button class="submit_button" id="print_button" onclick="print_receipt()" > <?php // echo lang('sales_print');  ?> </button>-->

<script type="text/javascript">
    function print_receipt()
    {
        window.print();
    }
</script>

<?php if ($is_integrated_credit_sale && $is_sale) { ?>
    <script type="text/javascript">
        set_feedback(<?php echo json_encode(lang('sales_credit_card_processing_success')) ?>, 'success_message', false);
    </script>
<?php } ?>
