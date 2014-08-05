<?php foreach (array_reverse($cart, true) as $line => $item) { ?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo $item['name']; ?></div>
    <div class="panel-body">
        
        <!-- <table class="table table-register">   -->
        <table>  
            <tr>
                <th class="col-1 col-sm-1 col-lg-1"><?php echo lang('common_unit_price'); ?></th>
                <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo to_currency($item['price']); ?></td>
            </tr>
            <tr>
                <th class="col-1 col-sm-1 col-lg-1"><?php echo lang("sales_duration"); ?></th>
                <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo to_quantity($item['quantity']); ?></td>
            </tr>
            <tr>
                <th class="col-1 col-sm-1 col-lg-1"><?php echo lang("sales_discount"); ?></th>
                <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $item['discount']; ?></td>
            </tr>  
            <tr>
                <th class="col-1 col-sm-1 col-lg-1"><?php echo lang("common_description"); ?></th>
                <td class="col-1 col-sm-1 col-lg-1" style='text-align:left;'><?php echo $item['description']; ?></td>
            </tr>  
        </table>

    </div>
</div>
<?php } ?>

<div class="panel panel-default">
    <div class="panel-body">

<table class="">
    <tr>
        <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo lang('sales_sub_total'); ?></td>
        <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;'><?php echo to_currency($subtotal); ?></td>
    </tr>
    <tr>
        <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right'><?php echo lang('sales_deposit'); ?></td>
        <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;'><?php echo to_currency($deposit_price); ?></td>
    </tr>
    <tr>
        <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right'><?php echo lang('sales_balance'); ?></td>
        <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;'><?php echo to_currency($total - $deposit_price); ?></td>
    </tr>
    <?php foreach ($taxes as $name => $value) { ?>
        <tr>
            <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo $name; ?>:</td>
            <td class="col-1 col-sm-1 col-lg-1" style='text-align:right;'><?php echo to_currency($value); ?></td> 
        </tr>
    <?php } ?>
    <tr>
        <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo lang('sales_total'); ?></td>
        <td class="col-1 col-sm-1 col-lg-1" style='text-align:right'><?php echo $this->config->item('round_cash_on_sales') ? to_currency(round_to_nearest_05($total)) : to_currency($total); ?></td>
    </tr>
<!-- Show total in Riels -->
    <tr>
        <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo lang('sales_total_in_riels'); ?></td>
        <?php $symbol = $this->currency_model->get_currency_rate_by_type_name($this->config->item('default_currency'))->symbol; ?>
        <td class="col-1 col-sm-1 col-lg-1" style='text-align:right'><?php echo $total_in_riels.' '.ucfirst($symbol); ?></td>
    </tr>

    <tr><td colspan="8">&nbsp;</td></tr>

    <?php
    foreach ($payments as $payment_id => $payment) {
        ?>
        <tr>
            <td  class="col-1 col-sm-1 col-lg-1" colspan="6" style="text-align:right;"><?php echo (isset($show_payment_times) && $show_payment_times) ? date('d/m/Y' . ' ' . get_time_format(), strtotime($payment['payment_date'])) : lang('sales_payment'); ?></td>
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
            <td class="col-1 col-sm-1 col-lg-1" colspan="7" style='text-align:right;'><?php echo lang('sales_amount_due'); ?></td>
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

</div>
</div>