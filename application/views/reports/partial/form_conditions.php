<table id="contents" class="table table-bordered">
	<tr>
		<td class="item_table">
			<?php echo form_label(lang('reports_date_range'), 'report_date_range_label', array('class'=>'required')); ?>
			<div id='report_date_range_simple'>
				<input type="radio" name="report_type" id="simple_radio" value='simple'<?php if ($report_type != 'complex') { echo " checked='checked'"; }?>/>
				<?php echo form_dropdown('report_date_range_simple',$report_date_range_simple, $sreport_date_range_simple, 'id="report_date_range_simple"'); ?>
			</div>
			
			<div id='report_date_range_complex'>
				<input type="radio" name="report_type" id="complex_radio" value='complex'<?php if ($report_type == 'complex') { echo " checked='checked'"; }?>/>
				<?php echo form_dropdown('start_month',$months, $start_month, 'id="start_month"'); ?>
				<?php echo form_dropdown('start_day',$days, $start_day, 'id="start_day"'); ?>
				<?php echo form_dropdown('start_year',$years, $start_year, 'id="start_year"'); ?>
				-
				<?php echo form_dropdown('end_month',$months, $end_month, 'id="end_month"'); ?>
				<?php echo form_dropdown('end_day',$days, $end_day, 'id="end_day"'); ?>
				<?php echo form_dropdown('end_year',$years, $end_year, 'id="end_year"'); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="item_table">&nbsp;</td>
	</tr>
	<tr>
		<td class="item_table">
			<?php echo form_label(lang('reports_sales_generator_matchType'), 'matchType', array('class'=>'required')); ?><br />		
			<select name="matchType" id="matchType">
				<option value="matchType_All"<?php if ($matchType != 'matchType_All') { echo " selected='selected'"; }?>><?php echo lang('reports_sales_generator_matchType_All')?></option>
				<option value="matchType_Or"<?php if ($matchType == 'matchType_Or') { echo " selected='selected'"; }?>><?php echo lang('reports_sales_generator_matchType_Or')?></option>
			</select>
			<br />
			<em>
				<?php echo lang('reports_sales_generator_matchType_Help')?>
			</em>
		</td>
	</tr>
		<tr>
		<td class="item_table">&nbsp;</td>
	</tr>

<!--	<tr>
		<td class="item_table">
			//<?php // echo form_label(lang('reports_sales_generator_show_only_matched_items'), 'matched_items_only'); ?>
			//<?php
//				$matched_items_checkbox =	array(
//			    'name'        => 'matched_items_only',
//			    'id'          => 'matched_items_only',
//			    'value'       => '1',
//			    'checked'     => $matched_items_only,
//		    	);
				
//				if ($matchType == 'matchType_Or')
//				{
//					$matched_items_checkbox['disabled'] = 'disabled';
//				}
			?>
			&nbsp;&nbsp;<?php // echo form_checkbox($matched_items_checkbox); 
//			?>
		</td>
	</tr>-->

	<tr>
		<td class="item_table">&nbsp;</td>
	</tr>
	<tr>
		<td class="item_table">
			<table class="conditions">
				<?php
					if (isset($field) and $field[0] > 0) {
						foreach ($field as $k => $v) {
				?>
				<tr class="duplicate">
					<td class="field">
						<select name="field[]" class="selectField">
							<option value="0"><?php echo lang("reports_sales_generator_selectField_0") ?></option>						
							<option value="1" rel="customers"<?php if($field[$k] == 1) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_1") ?></option>
							<!--<option value="2" rel="itemsSN"<?php // if($field[$k] == 2) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_2") ?></option>-->
							<option value="3" rel="employees"<?php if($field[$k] == 3) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_3") ?></option>
							<!--<option value="4" rel="itemsCategory"<?php // if($field[$k] == 4) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_4") ?></option>-->
							<option value="5" rel="suppliers"<?php if($field[$k] == 5) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_5") ?></option>
							<!-- <option value="6" rel="saleType"<?php if($field[$k] == 6) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_6") ?></option> -->
							<option value="7" rel="saleAmount"<?php if($field[$k] == 7) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_7") ?></option>
							<!--<option value="8" rel="itemsKitName"<?php // if($field[$k] == 8) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_8") ?></option>-->
							<option value="9" rel="itemsName"<?php if($field[$k] == 9) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_9") ?></option>
							<option value="10" rel="saleID"<?php if($field[$k] == 10) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_10") ?></option>
							<option value="11" rel="paymentType"<?php if($field[$k] == 11) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_11") ?></option>
							<option value="12" rel="massager"<?php if($field[$k] == 12) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectField_12") ?></option>
						</select>
					</td>
					<td class="condition">
						<select name="condition[]" class="selectCondition">
							<option value="1"<?php if($condition[$k] == 1) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_1")?></option>
							<option value="2"<?php if($condition[$k] == 2) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_2")?></option>
							<option value="7"<?php if($condition[$k] == 7) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_7")?></option>
							<option value="8"<?php if($condition[$k] == 8) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_8")?></option>
							<option value="9"<?php if($condition[$k] == 9) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_9")?></option>
							<option value="10"<?php if($condition[$k] == 10) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_10")?></option>
							<option value="11"<?php if($condition[$k] == 11) echo " selected='selected'";?>><?php echo lang("reports_sales_generator_selectCondition_11")?></option>
						</select>
					</td>
					<td class="value">
						<input type="text" class="w" name="value[]" w="" value="<?php echo $value[$k]; ?>"/>
					</td>
					<td class="actions">
						<span class="actionCondition">
						<?php 
							if ($matchType == 'matchType_Or') {
								echo lang("reports_sales_generator_matchType_Or_TEXT");
							} else {
								echo lang("reports_sales_generator_matchType_All_TEXT");					
							}
						?>
						</span>
						<a class="AddCondition" href="#" title="<?php echo lang("reports_sales_generator_addCondition")?>"><?php echo lang("reports_sales_generator_addCondition")?></a>
						<a class="DelCondition" href="#" title="<?php echo lang("reports_sales_generator_delCondition")?>"><?php echo lang("reports_sales_generator_delCondition")?></a>
					</td>
				</tr>				
				<?php
						}
					} else {
				?>
				<tr class="duplicate">
					<td class="field">
						<select name="field[]" class="selectField">
							<option value="0"><?php echo lang("reports_sales_generator_selectField_0") ?></option>						
							<option value="1" rel="customers"><?php echo lang("reports_sales_generator_selectField_1") ?></option>
							<!--<option value="2" rel="itemsSN"><?php // echo lang("reports_sales_generator_selectField_2") ?></option>-->
							<option value="3" rel="employees"><?php echo lang("reports_sales_generator_selectField_3") ?></option>
							<!--<option value="4" rel="itemsCategory"><?php // echo lang("reports_sales_generator_selectField_4") ?></option>-->
							<option value="5" rel="suppliers"><?php echo lang("reports_sales_generator_selectField_5") ?></option>
							<!-- <option value="6" rel="saleType"><?php echo lang("reports_sales_generator_selectField_6") ?></option> -->
							<option value="7" rel="saleAmount"><?php echo lang("reports_sales_generator_selectField_7") ?></option>
							<!--<option value="8" rel="itemsKitName"><?php // echo lang("reports_sales_generator_selectField_8") ?></option>-->
							<option value="9" rel="itemsName"><?php echo lang("reports_sales_generator_selectField_9") ?></option>
							<option value="10" rel="saleID"><?php echo lang("reports_sales_generator_selectField_10") ?></option>
							<option value="11" rel="paymentType"><?php echo lang("reports_sales_generator_selectField_11") ?></option>
							<option value="12" rel="massager"><?php echo lang("reports_sales_generator_selectField_12") ?></option>
						</select>
					</td>
					<td class="condition">
						<select name="condition[]" class="selectCondition">
							<option value="1"><?php echo lang("reports_sales_generator_selectCondition_1")?></option>
							<option value="2"><?php echo lang("reports_sales_generator_selectCondition_2")?></option>
							<option value="7"><?php echo lang("reports_sales_generator_selectCondition_7")?></option>
							<option value="8"><?php echo lang("reports_sales_generator_selectCondition_8")?></option>
							<option value="9"><?php echo lang("reports_sales_generator_selectCondition_9")?></option>
							<option value="10"><?php echo lang("reports_sales_generator_selectCondition_10")?></option>
							<option value="11"><?php echo lang("reports_sales_generator_selectCondition_11")?></option>
						</select>
					</td>
					<td class="value">
						<input type="text" class="w" name="value[]" w="" value=""/>
					</td>
					<td class="actions">
						<span class="actionCondition">
						<?php 
							if ($matchType == 'matchType_Or') {
								echo lang("reports_sales_generator_matchType_Or_TEXT");
							} else {
								echo lang("reports_sales_generator_matchType_All_TEXT");					
							}
						?>
						</span>
						<a class="AddCondition" href="#" title="<?php echo lang("reports_sales_generator_addCondition")?>"><?php echo lang("reports_sales_generator_addCondition")?></a>
						<a class="DelCondition" href="#" title="<?php echo lang("reports_sales_generator_delCondition")?>"><?php echo lang("reports_sales_generator_delCondition")?></a>
					</td>
				</tr>
			
				<?php
					}
				?>
			</table>
		</td>
	</tr>	
	<tr>
		<td class="item_table" style="padding-top: 15px;">
			<button name="generate_report" type="submit" value="1" id="generate_report" class="submit_button btn btn-info"><?php echo lang('common_submit')?></button>
		</td>
	</tr>		
</table>