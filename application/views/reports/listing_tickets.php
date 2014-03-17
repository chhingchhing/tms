<?php $this->load->view("partial/header"); ?>
<div class="wrapper_report">
    <div  class="panel-heading" id="report">
        <table id="title_bar">
            <tr>
                <td id="title_icon">
                    <img src='<?php echo base_url() ?>images/menubar/reports.png' alt='<?php echo lang('reports_reports'); ?> - <?php echo lang('reports_welcome_message'); ?>' />
                </td>
                <td id="title"><?php echo lang('reports_reports_tickets'); ?></td>
            </tr>
        </table>
    </div>
    <div class="content_report">
        <ul id="report_list" class="block_report">            
            <li class="full">
                <h5 class="col-md-3"><?php echo lang('reports_categories'); ?></h5>
                <ul>
                    <li class="reports graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_categories'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="reports summary col-md-3"><a href="<?php echo site_url('reports/summary_categories'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>
                    <li class="reports col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="full">
                <h5 class="col-md-3"><?php echo lang('reports_discounts'); ?></h5>
                <ul>
                    <li class="reports graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_discounts'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="reports summary col-md-3"><a href="<?php echo site_url('reports/summary_discounts'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>			
                    <li class="reports col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="full">
                <h5 class="col-md-3"><?php echo lang('reports_tickets'); ?></h5>
                <ul>
                    <li class="reports graphical col-md-3"><a href="<?php echo site_url('reports/graphical_tickets_summary/'.$this->uri->segment(3)); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="reports summary col-md-3"><a href="<?php echo site_url('reports/summary_tickets/' . $this->uri->segment(3)); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>		
                    <li class="reports col-md-3"><a href="<?php echo site_url('reports/detailed_sales_ticket/'.$this->uri->segment(3)); ?>"><?php echo lang('reports_detailed_reports');  ?></a></li><?php // echo lang('reports_summary_reports');  ?>
                </ul>
            </li>
            
            <li class="full">
                <h5 class="col-md-3"><?php echo lang('reports_massages'); ?></h5>
                <ul>
                    <li class="reports graphical col-md-3"><a href="<?php echo site_url('reports/graphical_massages_summary/'.$this->uri->segment(3)); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="reports summary col-md-3"><a href="<?php echo site_url('reports/summary_massages/' . $this->uri->segment(3)); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>		
                    <li class="reports col-md-3"><a href="<?php echo site_url('reports/detailed_sales_massage/'.$this->uri->segment(3)); ?>"><?php echo lang('reports_detailed_reports');  ?></a></li><?php // echo lang('reports_summary_reports');  ?>
                </ul>
            </li>
            
            <li class="full">
                <h5 class="col-md-3"><?php echo lang('module_item_kits'); ?></h5>
                <ul>
                    <li class="reports graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_item_kits'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="reports summary col-md-3"><a href="<?php echo site_url('reports/summary_item_kits'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>			
                    <li class="reports col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="full">
                <h5 class="col-md-3"><?php echo lang('reports_payments'); ?></h5>
                <ul>
                    <li class="reports graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_payments'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="reports summary col-md-3"><a href="<?php echo site_url('reports/summary_payments'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>	
                    <li class="reports col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="full">
                <h5 class="col-md-3"><?php echo lang('reports_suppliers'); ?></h5>
                <ul> 
                    <li class="reports graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_suppliers'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="reports summary col-md-3"><a href="<?php echo site_url('reports/summary_suppliers'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>
                    <li class="reports detailed col-md-3"><a href="<?php echo site_url('reports/specific_supplier'); ?>"><?php echo lang('reports_detailed_reports'); ?></a></li>					
                </ul>
            </li>
            <li class="full">
                <h5 class="col-md-3"><?php echo lang('reports_taxes'); ?></h5>
                <ul>
                    <li class="reports graphical col-md-3"><a href="<?php echo site_url('reports/graphical_summary_taxes'); ?>"><?php echo lang('reports_graphical_reports'); ?></a></li>
                    <li class="reports summary col-md-3"><a href="<?php echo site_url('reports/summary_taxes'); ?>"><?php echo lang('reports_summary_reports'); ?></a></li>			
                    <li class="reports col-md-3">&nbsp;</li>
                </ul>
            </li>
            <li class="full">
                <h5 class="col-md-3"><?php echo lang('reports_receivings'); ?></h5>
                <ul>
                    <li class="reports col-md-3">&nbsp;</li>
                    <li class="reports col-md-3">&nbsp;</li>
                    <li class="reports detailed col-md-3"><a href="<?php echo site_url('reports/detailed_receivings'); ?>"><?php echo lang('reports_detailed_reports'); ?></a></li>			
                </ul>
            </li>
            <li class="full">
                <h5 class="col-md-3"><?php echo lang('reports_inventory_reports'); ?></h5>
                <ul>
                    <li class="reports graphical col-md-3"><a href="<?php echo site_url('reports/inventory_low'); ?>"><?php echo lang('reports_low_inventory'); ?></a></li>
                    <li class="reports summary col-md-3"><a href="<?php echo site_url('reports/inventory_summary'); ?>"><?php echo lang('reports_inventory_summary'); ?></a></li>	
                    <li class="reports col-md-3">&nbsp;</li>
                </ul>
            </li>
            <div style="clear: both;"></div>
        </ul>
    </div>
</div>
<?php $this->load->view("partial/footer"); ?>
