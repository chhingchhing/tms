<div id="overall_sale">
    <?php $this->load->view("sales/components/suspend_sale"); ?>

    <div id="customer_info_shell">
        <?php
        if (isset($customer)) {
            echo "<div id='customer_info_filled' class='col-md-12'>";
            echo '<h5 id="customer_txt">' . lang('customers_customer') . '</h5>';
            echo '<div id="customer_name" class="col-md-8"><span>' . character_limiter($customer, 25) . '</span></div>';
            echo '<div id="customer_edit" class="col-md-1">' . anchor("#customers/view/$customer_id/$controller_name", ' ', array('id'=>'sale_edit_customer','class' => 'thickbox none glyphicon edit glyphicon-edit', 'title' => lang('customers_update'), 'modals' => 'customers' )) . '</div>';
            echo '<div id="customer_remove" class="col-md-1">' . anchor("$controller_name/delete_customer", ' ', array('id' => 'delete_customer', 'class' => 'glyphicon glyphicon-remove-circle')) . '</div>';
            echo '<div class="clearboth"></div>';
            echo "</div>";
        } else {
            ?>

            <div id='customer_info_empty'>
                <?php echo form_open("$controller_name/select_customer", array('id' => 'select_customer_form')); ?>
                <label id="customer_label" for="customer">
                    <?php echo lang('sales_select_customer'); ?>
                </label>
                <?php echo form_input(array('name' => 'customer', 'id' => 'customer', 'size' => '1', 'value' => '', 'placeholder' => lang('sales_start_typing_customer_name'), 'accesskey' => 'c', 'class' => 'form-control input-sm')); ?>
                </form>
                <div id="add_customer_info">
                    <div id="common_or">
                        <?php echo lang('common_or'); ?>
                    </div>					
                    <?php
                      echo anchor("#customers/view", "<div class='small_button' style='margin:0 auto;'> <span>" . lang('sales_new_customer') . "</span> </div>", array('class' => 'thickbox edit none btn btn-primary btn-sm', 'title' => lang('sales_new_customer'), 'role' => 'button', 'id' => 'add_customer', 'modals' => "customers"));
                    ?>
                </div>
            </div>
        <?php } ?>
        
    </div>
    
     <!--date for bike--> 
         <?php if ($controller_name == "bikes") { ?>
             <?php echo form_open("$controller_name/edit_item/" . $this->session->userdata("office_number") . "/$line", array('class' => 'line_item_form')); ?>
             <?php echo form_hidden("line", $line); ?>
                <div id="reg_item_date_departure">
                    <div class='form_field' id="rent_date">
                        <label>Rent Date: </label> <br/>
                        <?php
                        echo form_input(array('name' => 'rent_date',
                            'value' => $rent_dates[$line - 1] != "" ? $rent_dates[$line - 1] : "0000-00-00",
                            'id' => 'rent_date', 'class' => 'date-pick dp-applied'));
                        ?>
                    </div>
                </div>
                        
                <div id="reg_item_date_departure">
                    <div class='form_field' id="return_date">
                        <label>Return Date: </label> <br/>
                        <?php
                        echo form_input(array('name' => 'return_date',
                            'value' => $return_dates[$line - 1] != "" ? $return_dates[$line - 1] : "0000-00-00",
                            'id' => 'return_date', 'class' => 'date-pick dp-applied'));
                        ?>
                    </div>
                </div>
            <?php form_close();?>
        <?php } ?>
        <!--end date-->
    
        <div>
            <?php 
		/*if ($controller_name == "massages" || $controller_name == "bikes") {
			$this->load->view('sales/components/time_in');
		}*/
        if ($controller_name == "bikes") {
            $this->load->view('sales/components/time_in');
        }
		?>
        </div>
        <div>
            <?php 
		/*if ($controller_name == "massages" || $controller_name == "bikes") {
			$this->load->view('sales/components/time_out');
		}*/
        if ($controller_name == "bikes") {
            $this->load->view('sales/components/time_out');
        }
		?>
        </div>

<!-- Guides -->
	<?php if ($controller_name == "tours") { ?>
	<div id="guide_info_shell">
		<?php
		if(isset($guides))
		{
			echo "<div id='guide_info_filled' class='col-md-12'>";
				echo '<h5 id="sale_com">'.lang('sales_guide').'</h5>';
				echo '<div id="guide_name" class="col-md-8"><span>'.character_limiter($guides, 25).'</span></div>';
				echo '<div id="guide_edit" class="col-md-1">'.anchor("#guides/view/$guide_id/$controller_name", ' ',  array('class'=>'thickbox none glyphicon edit glyphicon-edit','title'=>lang('sales_guide_update'), 'modals'=>'guides')).'</div>';
				echo '<div id="guide_remove" class="col-md-1">'.anchor("$controller_name/delete_guide", ' ',array('id' => 'delete_guide', 'class' => 'glyphicon glyphicon-remove-circle')).'</div>';
			echo "</div>";
		}
		else
		{ ?>
			<div id='guide_info_empty'>
				<?php echo form_open("$controller_name/select_guide",array('id'=>'select_guide_form')); ?>
				<label id="guide_label" for="guide">
					<?php echo lang('sales_select_guide'); ?>
				</label>
				<?php echo form_input(array('name'=>'guide','id'=>'guide','size'=>'30','value'=>'','placeholder'=>lang('sales_start_typing_guide_name'),  'accesskey' => 'c', 'class'=>'form-control input-sm'));?>
				</form>
				<div id="add_guide_info">
					<div id="common_or"> 
						<?php echo lang('common_or'); ?>
					</div>					
					<?php 
					echo anchor("#guides/view", 
						"<div class='small_button' style='margin:0 auto;'> <span>".lang('sales_add_guide')."</span> </div>", 
						array('class'=>'thickbox edit none btn btn-primary btn-sm','title'=>lang('sales_add_guide'), 'role'=>'button', 'id' => 'add_guide', 'modals' => "guides"));
					?>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php } ?>             
<!-- End Guides -->


<!-- Massager --> 
<?php if ($controller_name == "massages") { ?>   
    <div id="massager_info_shell">
        <?php
        if (isset($massager)) {
            echo "<div id='massager_info_filled' class='col-md-12'>";
            echo '<h5 id="sale_com">' . lang('sales_massager') . '</h5>';
            echo '<div id="massager_name" class="col-md-8">' . character_limiter($massager, 25) . '</div>';
            echo '<div id="massager_edit" class="col-md-1">' . anchor("#massages/view_massager/$massager_id", ' ', array('class' => 'thickbox edit none glyphicon glyphicon-edit', 'title' => lang('commissioner_update'), 'id' => 'edit_commissioner', 'modals' => "add_massager")) . '</div>';
            echo '<div id="massager_remove" class="col-md-1">' . anchor("$controller_name/delete_massager", ' ', array('id' => 'delete_commissioner', 'class' => 'glyphicon glyphicon-remove-circle')) . '</div>';
            echo '<div class="clearboth"></div>';
            echo "</div>";
        } else {
            ?>
            <!-- <div id='guide_info_empty'>
                <?php echo form_open("$controller_name/select_massager",array('id'=>'select_massager_form')); ?>
                <label id="massager_label" for="massager">
                    <?php echo lang('sales_select_massager'); ?>
                </label>
                <?php echo form_input(array('name'=>'massager','id'=>'massager','size'=>'30','value'=>'','placeholder'=>lang('sales_start_typing_massager_name'),  'accesskey' => 'm', 'class'=>'form-control input-sm'));?>
                </form>
                <div id="add_massager_info">
                    <div id="common_or"> 
                        <?php echo lang('common_or'); ?>
                    </div>                  
                    <?php 
                    //echo anchor("#massages/view_massager", 
                        // "<div class='small_button' style='margin:0 auto;'> <span>".lang('sales_add_massagers')."</span> </div>", 
                        // array('class'=>'thickbox edit none btn btn-primary btn-sm','title'=>lang('sales_add_massagers'), 'modals' => "add_massager"));
                    ?>
                </div>
            </div> -->
        <?php } ?>
    </div>
    <!-- Tip money for massager -->
    <!-- <div id='info_empty_price_tip'>
        <?php $tip_price = count($cart) == 0 ? 0 : $tip_price;  ?>
        <?php echo form_open("$controller_name/set_commissioner_tip", array('id' => 'price_tip_form')); ?>
        <label id="price_commissioner_label" for="tip_price">
            <?php echo lang('sales_price_tip'); ?>
        </label>
        <?php echo form_input(array('name' => 'tip_price', 'id' => 'tip_price', 'size' => '30', 'value' => $tip_price, 'placeholder' => lang('sales_start_typing_tip_price'), 'accesskey' => 't', 'class' => 'form-control input-sm')); ?>
        </form>
    </div> -->
<?php } ?>
    <!-- End Massager -->

    <!-- Commissioners -->    
    <div id="commissioner_info_shell">
        <?php
        if (isset($commissioner)) {
            echo "<div id='commissioner_info_filled' class='col-md-12'>";
            echo '<h5 id="sale_com">' . lang('sales_commissioner') . '</h5>';
            echo '<div id="commissioner_name" class="col-md-8">' . character_limiter($commissioner, 25) . '</div>';
            echo '<div id="commissioner_edit" class="col-md-1">' . anchor("#commissioners/view/$commissioner_id", ' ', array('id'=>'sale_edit_commissioners','class' => 'thickbox edit none glyphicon glyphicon-edit', 'title' => lang('commissioner_update'), 'id' => 'edit_commissioner', 'modals' => "commissioners")) . '</div>';
            echo '<div id="commissioner_remove" class="col-md-1">' . anchor("$controller_name/delete_commissioner", ' ', array('id' => 'delete_commissioner', 'class' => 'glyphicon glyphicon-remove-circle')) . '</div>';
            echo '<div class="clearboth"></div>';
            echo "</div>";
        } else {
            ?>
            <div id='commissioner_info_empty'>
                <?php echo form_open("$controller_name/select_commissioner", array('id' => 'select_commissioner_form')); ?>
                <label id="commissioner_label" for="commissioner">
                    <?php echo lang('sales_select_commissioner'); ?>
                </label>
                <?php echo form_input(array('name' => 'commissioner', 'id' => 'commissioner', 'size' => '30', 'value' => '', 'placeholder' => lang('sales_start_typing_commissioner_name'), 'accesskey' => 'c', 'class' => 'form-control input-sm')); ?>
                </form>
                <div id="add_commissioner_info">
                    <div id="common_or">
                        <?php echo lang('common_or'); ?>
                    </div>					
                    <?php
                    echo anchor("#commissioners/view", "<div class='small_button' style='margin:0 auto;'> <span>" . lang('sales_add_commissioner') . "</span> </div>", array('class' => 'thickbox edit none btn btn-primary btn-sm', 'title' => lang('sales_add_commissioner'), 'role' => 'button', 'id' => 'add_commissioner', 'modals' => "commissioners"));
                    ?>
                </div>
            </div>
        <?php } ?>
        
        <div id='commissioner_info_empty_price'>
            <?php echo form_open("$controller_name/set_commissioner_price", array('id' => 'price_commissioner_form')); ?>
            <label id="price_commissioner_label" for="commissioner_price">
                <?php echo lang('sales_price_commissioner'); ?>
            </label>
            <?php echo form_input(array('name' => 'commissioner_price', 'id' => 'commissioner_price', 'size' => '30', 'value' => $commissioner_price, 'placeholder' => lang('sales_start_typing_commissioner_price'), 'accesskey' => 'p', 'class' => 'form-control input-sm')); ?>
            </form>
        </div>
        <div id='sale_deposit'>
            <?php 
            if($controller_name == "massages"){
                
            }  else {                
            echo form_open("$controller_name/set_deposit_price", array('id' => 'price_deposit_form')); ?>
            <label id="price_deposit_label" for="deposit_price">
                <?php echo lang('sales_deposit'); ?>
            </label>
            <?php echo form_input(array('name' => 'deposit_price', 'id' => 'deposit_price', 'size' => '30', 'value' => $deposit_price, 'placeholder' => lang('sales_start_typing_deposit_price'), 'accesskey' => 'd', 'class' => 'form-control input-sm'));}; ?>
            </form>
        </div>
    </div>
    <!-- End Commissioners -->
   
    <div id='sale_details'>
        <table id="deposit_items_total">
            <tr>
                <td class="left"><?php echo lang('sales_deposit'); ?>:</td>
                <td class="right"><?php echo to_currency($deposit_price); ?></td>
            </tr>
        </table>
        <table id="sales_items">
            <tr>
                <td class="left"><?php echo lang('sales_items_in_cart'); ?>:</td>
                <td class="right"><?php echo $items_in_cart; ?></td>
            </tr>
            <?php foreach ($payments as $payment) { ?>
                <?php if (strpos($payment['payment_type'], lang('sales_giftcard')) !== FALSE) { ?>
                    <tr>
                        <td class="left"><?php echo $payment['payment_type'] . ' ' . lang('sales_balance') ?>:</td>
                        <td class="right"><?php echo to_currency($this->Giftcard->get_giftcard_value(end(explode(':', $payment['payment_type']))) - $payment['payment_amount']); ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <tr>
                <td class="left"><?php echo lang('sales_sub_total'); ?>:</td>
                <td class="right"><?php echo to_currency($subtotal); ?></td>
            </tr>
            <?php foreach ($taxes as $name => $value) { ?>
                <tr>
                    <td class="left"><?php echo $name; ?>:</td>
                    <td class="right"><?php echo to_currency($value); ?></td>
                </tr>
            <?php }; ?>
        </table>
        <table id="sales_items_total">
            <tr>
                <td class="left"><?php echo lang('sales_total'); ?>:</td>
                <td class="right"><?php echo to_currency($total); ?></td>
            </tr>
        </table>
    </div>

    <?php
// Only show this part if there are Items already in the sale.
    if (count($cart) > 0) {
        ?>

        <div id="Payment_Types">

            <?php
            // Only show this part if there is at least one payment entered.
            if (count($payments) > 0) {
                ?>
                <table id="register">
                    <thead>
                        <tr>
                            <th id="pt_type" class="col-1 col-sm-1 col-lg-1"><?php echo lang('sales_type'); ?></th>
                            <th id="pt_amount" class="col-1 col-sm-1 col-lg-1"><?php echo lang('sales_amount'); ?></th>
                            <th id="pt_delete" class="col-1 col-sm-1 col-lg-1"></th>
                        </tr>
                    </thead>
                    <tbody id="payment_contents">
                        <?php
                        foreach ($payments as $payment_id => $payment) {
                            ?>
                            <tr>
                                <td id="pt_type" class="col-1 col-sm-1 col-lg-1"><?php echo $payment['payment_type']; ?> </td>
                                <td id="pt_amount" class="col-1 col-sm-1 col-lg-1"><?php echo to_currency($payment['payment_amount']); ?>  </td>
                                <td id="pt_delete" class="col-1 col-sm-1 col-lg-1"><?php echo anchor("$controller_name/delete_payment/$payment_id", ' ', array('class' => 'delete_payment glyphicon glyphicon-remove-circle')); ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php } ?>

            <table id="amount_due">
                <tr class="<?php
                if ($payments_cover_total) {
                    echo 'covered';
                }
                ?>">
                    <td>
                        <div class="float_left"><?php echo lang('sales_amount_due'); ?>:</div>
                    </td>
                    <td>
                        <div class="float_left col-1 col-sm-1 col-lg-1" style="font-weight:bold;"><?php echo to_currency($amount_due); ?></div>
                    </td>
                </tr>
            </table>

            <div id="make_payment">
                <?php echo form_open("$controller_name/add_payment", array('id' => 'add_payment_form')); ?>
                <table id="make_payment_table">
                    <tr id="mpt_top">
                        <td id="add_payment_text">
                            <?php echo lang('sales_add_payment'); ?>:
                        </td>
                        <td>
                            <?php echo form_dropdown('payment_type', $payment_options, $this->config->item('default_payment_type'), 'id="payment_types" class="form-control"'); ?>
                        </td>
                    </tr>
                    <tr id="mpt_bottom">
                        <td id="tender" colspan="2">
                            <?php echo form_input(array('name' => 'amount_tendered', 'id' => 'amount_tendered', 'value' => to_currency_no_money($amount_due), 'size' => '27', 'accesskey' => 'p', 'class' => 'form-control input-sm')); ?>
                        </td>
                    </tr>
                </table>
                <div id='add_payment_button' class='btn btn-primary btn-sm'>
                    <span><?php echo lang('sales_add_payment'); ?></span>
                </div>
                <?php echo form_close(); ?>
            </div>

        </div>


        <?php
        echo '<div id="comments">';
        echo '<label id="comment_label" for="comment">';
        echo lang('common_comments');
        echo ':</label><br />';
        echo form_textarea(array('class'=>'form-control', 'name' => 'comment', 'id' => 'comment', 'value' => $comment, 'rows' => '1', 'accesskey' => 'o', 'cols' => '27'));
        echo '<br />';
        echo '</div>';

	// Only show this part if there is at least one payment entered.
	if((count($payments) > 0 && !is_sale_integrated_cc_processing()) || ($this->sale_lib->get_change_sale_id() && count($payments) > 0)){
    ?>
        <?php
            if($this->sale_lib->get_change_sale_id()) { ?>
            
        <div id="sale_change_date">
                <?php
                echo '<label id="comment_label" for="change_sale_date_enable">';
                echo lang('sales_change_date');
                echo ':</label>  ';
                echo form_checkbox(array(
                    'name'=>'change_sale_date_enable',
                    'id'=>'change_sale_date_enable',
                    'value' => $change_sale_date_enable,
                    'checked'=>(boolean)$change_sale_date_enable)
                );
                ?>
                <div class="field_row clearfix" id="change_sale_input">
                    <div class='form_field' id="change_sale_date">
                        <?php echo form_input(array('name'=>'change_sale_date',
                        'value'=> (isset($change_sale_date) && $change_sale_date) ?  $change_sale_date : date(get_date_format()), 
                        'id'=>'change_sale_date', 'class'=>'date-pick dp-applied'));?>
                    </div>
                </div>
            <br/>
        </div>
        <?php 
            } 
            ?>

        <div id="finish_sale">
			<?php echo form_open("$controller_name/complete/".$this->session->userdata("office_number"),array('id'=>'finish_sale_form')); ?>
			<?php							 
			if ($payments_cover_total)
			{
				echo form_submit('finish_sale_button', lang('sales_complete_sale'), 'class="btn btn-primary" id="finish_sale_button"');
			}
			?>
            <?php echo form_close(); ?> 
		</div>
	

	<?php } elseif (count($payments) > 0) { ?>
        
		<div id="finish_sale">
			<?php echo form_open("$controller_name/start_cc_processing/".$this->session->userdata("office_number"),array('id'=>'finish_sale_form')); ?>
			<?php							 
			if ($payments_cover_total)
			{
				echo form_submit('finish_sale_button', lang('sales_complete_sale'), 'class="btn btn-primary" id="finish_sale_button"');
				if (is_sale_integrated_cc_processing())
				{
					if (isset($customer) && $customer_cc_token && $customer_cc_preview)
					{
						echo '<label id="sales_use_saved_cc_label" for="use_saved_cc_info">';
						echo lang('sales_use_saved_cc_info'). ' '.$customer_cc_preview;
						echo ':</label>  ';
						echo form_checkbox(array(
							'name'=>'use_saved_cc_info',
							'id'=>'use_saved_cc_info',
							'value'=>'1',
							'checked'=>(boolean)$use_saved_cc_info)
						);
					}
					elseif(isset($customer))
					{
						echo '<label id="sales_save_credit_card_label" for="save_credit_card_info">';
						echo lang('sales_save_credit_card_info');
						echo ':</label>  ';
						echo form_checkbox(array(
							'name'=>'save_credit_card_info',
							'id'=>'save_credit_card_info',
							'value'=>$save_credit_card_info,
							'checked'=>(boolean)$save_credit_card_info)
						);
					}
				}
			}
			?>
    </form>
		</div>
	<?php } 
 }	// End of if (count($cart)) 
 ?>

</div><!-- END OVERALL-->	
