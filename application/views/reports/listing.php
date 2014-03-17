<?php $this->load->view("partial/header"); ?>
<div class="wrapper_report">
    <div  class="panel-heading" id="report">
        <table id="title_bar">
            <tr>
                <td id="title_icon">
                    <img src='<?php echo base_url() ?>images/menubar/reports.png' alt='<?php echo lang('reports_reports'); ?> - <?php echo lang('reports_welcome_message'); ?>' />
                </td>
                <td id="title"><?php echo lang('reports_reports'); ?></td>
            </tr>
        </table>
    </div>
    <div class="content_report">
        <ul id="report_list" class="block_report">
            <li class="full"> 
                <h4 class="col-md-3" ><?php echo lang('reports_sales_generator'); ?></h4>
                <ul>	
                    <li class="col-md-3">&nbsp;</li>
                    <li class="col-md-3">&nbsp;</li>
                    <li class="col-md-3"><a href="<?php echo site_url('reports/sales_generator'); ?>"><?php echo lang('reports_sales_search'); ?></a></li>		
                </ul>
            </li>
            <li class="full"> 
                <h4 class="col-md-3"><?php echo lang('reports_customers'); ?></h4>
                <ul>	
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_customers'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_customers'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>
                    <li class="detailed col-md-3"><a href="<?php echo site_url('reports/specific_customer'); ?>"><?php echo lang('reports_detailed_reports'); ?></a></li>		
                </ul>
            </li>
            <li class="full">
                <h4 class="col-md-3"><?php echo lang('reports_employees'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_employees'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_employees'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>
                    <li class="detailed col-md-3"><a href="<?php echo site_url('reports/specific_employee'); ?>"><?php echo lang('reports_detailed_reports'); ?></a></li>
                </ul>
            </li>
            <li class="full">
                <h4 class="col-md-3"><?php echo lang('reports_sales'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_sales'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_sales'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>
                    <li class="detailed col-md-3"><a href="<?php echo site_url('reports/detailed_sales'); ?>"><?php echo lang('reports_detailed_reports'); ?></a></li>			
                </ul>
            </li>
            <?php if ($this->config->item('track_cash')) { ?>
                <li class="third">
                    <h4 class="col-md-3"><?php echo lang('reports_register_log_title'); ?></h4>
                    <ul>
                        <li class="col-md-3">&nbsp;</li>
                        <li class="col-md-3">&nbsp;</li>
                        <li class="detailed col-md-3"><a href="<?php echo site_url('reports/detailed_register_log'); ?>"><?php echo lang('reports_detailed_reports'); ?></a></li>			
                    </ul>
                </li>
            <?php } ?>
            <li class="second">
                <h4 class="col-md-3"><?php echo lang('reports_categories'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_categories'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_categories'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>
                    <li class="col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="second">
                <h4 class="col-md-3"><?php echo lang('reports_discounts'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_discounts'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_discounts'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>			
                    <li class="col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="second">
                <h4 class="col-md-3"><?php echo lang('reports_items'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_items'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_items'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>			
                    <li class="col-md-3"> &nbsp;</li>
                </ul>
            </li>
            <li class="second">
                <h4 class="col-md-3"><?php echo lang('module_item_kits'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_item_kits'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_item_kits'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>			
                    <li class="col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="second">
                <h4 class="col-md-3"><?php echo lang('reports_payments'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_payments'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_payments'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>	
                    <li class="col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="second">
                <h4 class="col-md-3"><?php echo lang('reports_suppliers'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_suppliers'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_suppliers'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>
                    <li class="detailed col-md-3"><a href="<?php echo site_url('reports/specific_supplier'); ?>"><?php echo lang('reports_detailed_reports'); ?></a></li>					
                </ul>
            </li>
            <li class="second">
                <h4 class="col-md-3"><?php echo lang('reports_taxes'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_taxes'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_taxes'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>			
                    <li class="col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="third">
                <h4 class="col-md-3"><?php echo lang('reports_receivings'); ?></h4>
                <ul>
                    <li class="col-md-3">&nbsp;</li>
                    <li class="col-md-3">&nbsp;</li>
                    <li class="detailed col-md-3"><a href="<?php echo site_url('reports/detailed_receivings'); ?>"><?php echo lang('reports_detailed_reports'); ?></a></li>			
                </ul>
            </li>
            <li class="second">
                <h4 class="col-md-3"><?php echo lang('reports_inventory_reports'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/inventory_low'); ?>"><?php echo lang('reports_low_inventory'); ?></a></li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/inventory_summary'); ?>"><?php echo lang('reports_inventory_summary'); ?></a></li>	
                    <li class="col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="first">
                <h4 class="col-md-3"><?php echo lang('reports_deleted_sales'); ?></h4>
                <ul>
                    <li class="graphical col-md-3"><a href="<?php echo site_url('reports/deleted_sales'); ?>"><?php echo lang('reports_detailed_reports'); ?></a></li>
                    <li class="col-md-3">&nbsp;</li>
                    <li class="col-md-3">&nbsp;</li>

                </ul>
            </li>	
            <li class="full">
                <h4 class="col-md-3"><?php echo lang('reports_giftcards'); ?></h4>
                <ul> 
                    <li class="col-md-3">&nbsp;</li>
                    <li class="summary col-md-3"><a href="<?php echo site_url('reports/summary_giftcards'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>			
                    <li class="detailed col-md-3"><a href="<?php echo site_url('reports/detailed_giftcards'); ?>"><?php echo lang('reports_detailed_reports'); ?></a></li>			
                </ul>
            </li>
            <div style="clear: both;"></div>
        </ul>
    </div>
</div>
<?php $this->load->view("partial/footer"); ?>