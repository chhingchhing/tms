<?php

/**
*Gets the html table to manage offices
*/
function get_offices_table($offices, $controller) {
    $CI = & get_instance();

    $table = '<table class="tablesorter table table-hover" id="data_table" >';//id="sortable_table"

    $headers = array('<input type="checkbox" id="select_all" />',
        $CI->lang->line('offices_name'),
        $CI->lang->line('offices_address'),
        $CI->lang->line('common_email'),
        $CI->lang->line('common_phone_number'),
        $CI->lang->line('common_website'),
        $CI->lang->line('tickets_ticket_action'),
    );

    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_offices_manage_table_data_rows($offices, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the offices.
 */
function get_offices_manage_table_data_rows($offices, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';
    foreach ($offices->result() as $item) {
        $table_data_rows.=get_offices_data_row($item, $controller);
    }

    if ($offices->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . lang('offices_no_offices_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_offices_data_row($item, $controller) {
    $CI = & get_instance();
    $controller_name = strtolower(get_class($CI));
    // $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td><input type='checkbox' class='check_value' name='ids[]' id='item_$item->office_id' value='" . $item->office_id . "'/></td>";
    $table_data_row.='<td>' . $item->office_name . '</td>';
    $table_data_row.='<td>' . $item->ofc_address . '</td>';
    $table_data_row.='<td>' . $item->ofc_email . '</td>';
    $table_data_row.='<td>' . $item->ofc_phone . '</td>';
    $table_data_row.='<td>' . $item->ofc_website . '</td>';
    
    $table_data_row.='<td width="4%" class="rightmost">' . anchor("$controller_name/view/$item->office_id", lang('common_edit'), array('class' => 'thickbox glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'id' => 'edit_offices')) . '</td>';
    $table_data_row.='</tr>';
    return $table_data_row;
}

/**
*Gets the html table to manage currency
*/
function get_currency_table($currency, $controller) {
    $CI = & get_instance();
    // $has_cost_price_permission = $CI->Employee->has_module_action_permission('currency', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->employee_id);

    $table = '<table class="tablesorter table table-hover" id="data_table" >';//id="sortable_table"

    $headers = array('<input type="checkbox" id="select_all" />',
        // $CI->lang->line('currency_id'),
        $CI->lang->line('currency_type_name'),
        $CI->lang->line('currency_value'),
        $CI->lang->line('tickets_ticket_action'),
    );

    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_currency_manage_table_data_rows($currency, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the currency.
 */
function get_currency_manage_table_data_rows($currency, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($currency->result() as $item) {
        $table_data_rows.=get_currency_data_row($item, $controller);
    }

    if ($currency->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . lang('currency_no_currency_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_currency_data_row($item, $controller) {
    $CI = & get_instance();
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td><input type='checkbox' class='check_value' name='ids[]' id='item_$item->currency_id' value='" . $item->currency_id . "'/></td>";
    // $table_data_row.='<td width="10%">' . $item->currency_id . '</td>';
    $table_data_row.='<td>' . $item->currency_type_name . '</td>';
    $table_data_row.='<td>' . $item->currency_value . '</td>';
    
    $table_data_row.='<td class="rightmost">' . anchor("#$controller_name/view/$item->currency_id", lang('common_edit'), array('class' => 'thickbox edit glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'id' => 'edit_currency', 'modals' => 'currency')) . '</td>';
    $table_data_row.='</tr>';
    return $table_data_row;
}

/**
 * Gets the html table to manage ticket.
 */
function get_tickets_table($ticket, $controller) {
    $CI = & get_instance();
    $has_cost_price_permission = $CI->Employee->has_module_action_permission('tickets', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);

    $table = '<table class="tablesorter table table-hover" id="data_table" >';//id="sortable_table"

    if ($has_cost_price_permission) {
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('tickets_ticket_id'),
            $CI->lang->line('tickets_ticket_code'),
            $CI->lang->line('tickets_ticket_destination'),
            $CI->lang->line('tickets_ticket_type_id'),
            $CI->lang->line('tickets_ticket_actual_price'),
            $CI->lang->line('tickets_ticket_sale_price'),
            $CI->lang->line('tickets_ticket_action'),
        );
    } else {
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('tickets_ticket_id'),
            $CI->lang->line('tickets_code_name'),
            $CI->lang->line('tickets_ticket_destination'),
            $CI->lang->line('tickets_ticket_type_id'),
            //$CI->lang->line('tickets_ticket_actual_price'),
            $CI->lang->line('tickets_ticket_sale_price'),
            $CI->lang->line('tickets_ticket_action'),
        );
        //var_dump($CI->lang->line('items_item_id'));
    }

    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_ticket_manage_table_data_rows($ticket, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the items.
 */

function get_ticket_manage_table_data_rows($ticket, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($ticket->result() as $item) {
        $table_data_rows.=get_ticket_data_row($item, $controller);
    }

    if ($ticket->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . lang('tickets_no_ticket_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_ticket_data_row($item, $controller) {
    $CI = & get_instance();
    $has_cost_price_permission = $CI->Employee->has_module_action_permission('tickets', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
    $item_tax_info = $CI->Item_taxes->get_info($item->ticket_id);
    $tax_percents = '';
    foreach ($item_tax_info as $tax_info) {
        $tax_percents.=$tax_info['percent'] . '%, ';
    }
    $tax_percents = substr($tax_percents, 0, -2);
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='3%'><input type='checkbox' class='check_value' name='ids[]' id='item_$item->ticket_id' value='" . $item->ticket_id . "'/></td>";
    $table_data_row.='<td width="10%">' . $item->ticket_id . '</td>';
    $table_data_row.='<td width="10%">' . $item->ticket_name . '</td>';
    $table_data_row.='<td width="20%">' . $item->destination_name . '</td>';
    $table_data_row.='<td width="11%">' . $item->ticket_type_name . '</td>';
    if ($has_cost_price_permission) {
        $table_data_row.='<td width="10%" style="padding-right:20px; text-align:right;">' . to_currency($item->actual_price) . '</td>';
    }
    $table_data_row.='<td width="10%" style="padding-right:20px; text-align:right;">' . to_currency($item->sale_price) . '</td>';
    $table_data_row.='<td width="4%" class="rightmost">' . anchor("#$controller_name/viewJSON/$item->ticket_id", lang('common_edit'), array('class' => 'thickbox edit glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'id' => 'edit_ticket', 'modals' => 'tickets')) . '</td>';
    // $table_data_row.= "<td>Edit Edit</td>";
    $table_data_row.='</tr>';
    return $table_data_row;
}

/****get table for guides*****/    
    function get_guides_table($guide, $controller) {
    $CI = & get_instance();
    $has_cost_price_permission = $CI->Employee->has_module_action_permission('guides', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);

    $table = '<table class="tablesorter table table-hover" id="data_table" >';//id="sortable_table"

    if ($has_cost_price_permission){
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('guides_guide_id'),
            $CI->lang->line('guides_guide_name'),
            $CI->lang->line('guides_gender'),
            $CI->lang->line('guides_tel'),
            $CI->lang->line('guides_email'),
            $CI->lang->line('guides_guide_type'),
            $CI->lang->line('guides_guide_action'),
        );
    } else {
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('guides_guide_id'),
            $CI->lang->line('guides_guide_name'),
            $CI->lang->line('guides_gender'),
            $CI->lang->line('guides_tel'),
            $CI->lang->line('guides_email'),
            $CI->lang->line('guides_guide_type'),
            $CI->lang->line('guides_guide_action'),
        );
    }

    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_guide_manage_table_data_rows($guide, $controller);
    $table.='</tbody></table>';
    return $table;
}

function get_guide_manage_table_data_rows($guide, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';
    foreach ($guide->result() as $item) {
        $table_data_rows.=get_guide_data_row($item, $controller);
    }

    if ($guide->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . lang('guides_no_guide_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_guide_data_row($item, $controller) {
    $CI = & get_instance();
//    $has_cost_price_permission = $CI->Employee->has_module_action_permission('guides', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
    $item_tax_info = $CI->Item_taxes->get_info($item->guide_id);
    $tax_percents = '';
    foreach ($item_tax_info as $tax_info) {
        $tax_percents.=$tax_info['percent'] . '%, ';
    }
    $tax_percents = substr($tax_percents, 0, -2);
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='3%'><input type='checkbox' class='check_value' name='ids[]' id='item_$item->guide_id' value='" . $item->guide_id . "'/></td>";
    $table_data_row.='<td width="10%">' . $item->guide_id . '</td>';
    $table_data_row.='<td width="10%">' . $item->guide_fname.' '.$item->guide_lname . '</td>';
    $table_data_row.='<td width="20%">' . $item->gender . '</td>';
    $table_data_row.='<td width="11%">' . $item->tel . '</td>';
    $table_data_row.='<td width="11%">' . $item->email . '</td>';
    $table_data_row.='<td width="11%">' . $item->guide_type . '</td>';
    $table_data_row.='<td width="4%" class="rightmost">' . anchor("#$controller_name/view/$item->guide_id/$controller_name", lang('common_edit'), array('class' => 'thickbox edit glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'id' => 'edit_guide', 'modals'=>'guides')) . '</td>';
    $table_data_row.='</tr>';
    return $table_data_row;
}
   
/**end guide***/

/*table for transport*/

     function get_transportations_table($transport, $controller) {
    $CI = & get_instance();
    $has_cost_price_permission = $CI->Employee->has_module_action_permission('transports', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);

    $table = '<table class="tablesorter table table-hover" id="data_table" >';//id="sortable_table"

    if ($has_cost_price_permission){
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('transports_transport_id'),
            $CI->lang->line('transports_company_name'),
            $CI->lang->line('transports_taxi_fname'),
            $CI->lang->line('transports_taxi_lname'),
            $CI->lang->line('transports_phone'),
            $CI->lang->line('transports_vehicle'),
            $CI->lang->line('transports_mark'),
            $CI->lang->line('transports_action'),
        );
    } else {
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('transports_transport_id'),
            $CI->lang->line('transports_company_name'),
            $CI->lang->line('transports_taxi_fname'),
            $CI->lang->line('transports_taxi_lname'),
            $CI->lang->line('transports_phone'),
            $CI->lang->line('transports_vehicle'),
            $CI->lang->line('transports_mark'),
            $CI->lang->line('transports_action'),
        );
    }

    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_transportation_manage_table_data_rows($transport, $controller);
    $table.='</tbody></table>';
    return $table;
}

function get_transportation_manage_table_data_rows($transport, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';
    foreach ($transport->result() as $item) {
        $table_data_rows.=get_transportation_data_row($item, $controller);
    }

    if ($transport->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . lang('transports_no_guide_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_transportation_data_row($item, $controller) {
    $CI = & get_instance();
//    $has_cost_price_permission = $CI->Employee->has_module_action_permission('guides', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
    $item_tax_info = $CI->Item_taxes->get_info($item->transport_id);
    $tax_percents = '';
    foreach ($item_tax_info as $tax_info) {
        $tax_percents.=$tax_info['percent'] . '%, ';
    }
    $tax_percents = substr($tax_percents, 0, -2);
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='3%'><input type='checkbox' class='check_value' name='ids[]' id='item_$item->transport_id' value='" . $item->transport_id . "'/></td>";
    $table_data_row.='<td width="10%">' . $item->transport_id . '</td>';
    $table_data_row.='<td width="10%">' . $item->company_name. '</td>';
    $table_data_row.='<td width="20%">' . $item->taxi_fname . '</td>';
    $table_data_row.='<td width="20%">' . $item->taxi_lname . '</td>';
    $table_data_row.='<td width="11%">' . $item->phone . '</td>';
    $table_data_row.='<td width="20%">' . $item->vehicle . '</td>';
    $table_data_row.='<td width="11%">' . $item->mark . '</td>';
    $table_data_row.='<td width="4%" class="rightmost">' . anchor("#$controller_name/viewJSON/$item->transport_id", lang('common_edit'), array('class' => 'thickbox glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'id' => 'edit_transport', 'data-toggle' => 'modal', 'data-target' => "#$controller_name")) . '</td>';
    $table_data_row.='</tr>';
    return $table_data_row;
}

/*end table for transports*/

/*table for commissioners*/
     function get_commissioners_table($commis, $controller) {
    $CI = & get_instance();
    $has_cost_price_permission = $CI->Employee->has_module_action_permission('commissioners', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);

    $table = '<table class="tablesorter table table-hover" id="data_table" >';//id="sortable_table"

    if ($has_cost_price_permission){
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('commissioners_id'),
            $CI->lang->line('commissioners_first_name'),
            $CI->lang->line('commissioners_last_name'),
            $CI->lang->line('commissioners_tel'),
            $CI->lang->line('commissioners_action'),
        );
    } else {
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('commissioners_id'),
            $CI->lang->line('commissioners_first_name'),
            $CI->lang->line('commissioners_last_name'),
            $CI->lang->line('commissioners_tel'),
            $CI->lang->line('commissioners_action'),
        );
    }

    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_commissioner_manage_table_data_rows($commis, $controller);
    $table.='</tbody></table>';
    return $table;
}

function get_commissioner_manage_table_data_rows($commis, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';
    foreach ($commis->result() as $item) {
        $table_data_rows.=get_commissioner_data_row($item, $controller);
    }

    if ($commis->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . lang('commis_no_guide_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_commissioner_data_row($item, $controller) {
    $CI = & get_instance();
    $item_tax_info = $CI->Item_taxes->get_info($item->commisioner_id);
    $tax_percents = '';
    foreach ($item_tax_info as $tax_info) {
        $tax_percents.=$tax_info['percent'] . '%, ';
    }
    $tax_percents = substr($tax_percents, 0, -2);
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='3%'><input type='checkbox' class='check_value' name='ids[]' id='item_$item->commisioner_id' value='" . $item->commisioner_id . "'/></td>";
    $table_data_row.='<td width="10%">' . $item->commisioner_id . '</td>';
    $table_data_row.='<td width="10%">' . $item->first_name. '</td>';
    $table_data_row.='<td width="20%">' . $item->last_name . '</td>';
    $table_data_row.='<td width="11%">' . $item->tel . '</td>';
    $table_data_row.='<td width="4%" class="rightmost">' . anchor("#$controller_name/viewJSON/$item->commisioner_id", lang('common_edit'), array('class' => 'thickbox glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'id' => 'edit_commis', 'data-toggle' => 'modal', 'data-target' => "#$controller_name")) . '</td>';
    $table_data_row.='</tr>';
    return $table_data_row;
}

/*end table for commissionters*/

function get_people_manage_table($people, $controller) {
    $CI = & get_instance();
    $table = '<table class="table" id="data_table">';

    $controller_name = strtolower(get_class($CI));
    $headers = array('<input type="checkbox" id="select_all" />',
        lang('common_last_name'),
        lang('common_first_name'),
        lang('common_email'),
        lang('common_phone_number'),
        lang('common_state'),
        '&nbsp');
    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;
        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_people_manage_table_data_rows($people, $controller);
    $table.='</tbody></table>';
    return $table;
}

/* START WITH NAME */

function get_people_manage_table_massage($people, $controller) {
    $CI = & get_instance();
    // $table='<table class="tablesorter" id="sortable_table">';
    $table = '<table class="table" id="data_table">';

    $controller_name = strtolower(get_class($CI));

    $headers = array('<input type="checkbox" id="select_all" />',
        lang('common_massage_id'),
        lang('common_massage_name'),
        lang('common_massage_desc'),
        lang('common_price_haft'),
        lang('common_price_one'),
        lang('common_price_one_haft'),
        lang('common_price_two'),
        '&nbsp');
    $table.='<thead><tr>';

    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_people_manage_table_data_rows($people, $controller);
    $table.='</tbody></table>';
    return $table;
}

/* end func for massage */

/*
  Gets the html data rows for the people.
 */

function get_people_manage_table_data_rows($people, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($people->result() as $person) {
        $table_data_rows.=get_person_data_row($person, $controller);
    }

    if ($people->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='7'><div class='warning_message' style='padding:7px;'>" . lang('common_no_persons_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_person_data_row($person, $controller) {
    $CI = & get_instance();
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $start_of_time = date('Y-m-d', 0);
    $today = date('Y-m-d') . ' 23:59:59';
    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' class='check_value' name='ids[]' id='person_$person->person_id' value='" . $person->person_id . "'/></td>";
    $table_data_row.='<td width="20%">' . $person->last_name . '</td>';
    $table_data_row.='<td width="20%">' . $person->first_name . '</td>';
    $table_data_row.='<td width="30%">' . mailto($person->email, $person->email, array('class' => 'underline')) . '</td>';
    $table_data_row.='<td width="15%">' . $person->phone_number . '</td>';
    $table_data_row.='<td width="15%">' . $person->state . '</td>';
    if ($controller_name == "employees") {
        $table_data_row.='<td width="5%" class="rightmost">' . anchor("#$controller_name/view/$person->person_id/$controller_name", lang('common_edit'), array('class' => 'thickbox edit_emp glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'modals' => $controller_name )) . '</td>';
    } else {
        $table_data_row.='<td width="5%" class="rightmost">' . anchor("#$controller_name/view/$person->customer_id/$controller_name", lang('common_edit'), array('class' => 'thickbox edit glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'modals' => $controller_name )) . '</td>';
    }
    $table_data_row.='</tr>';

    return $table_data_row;
}

/*
  Gets the html table to manage suppliers.
 */

function get_supplier_manage_table($suppliers, $controller) {
    $CI = & get_instance();
    $table = '<table class="table" id="data_table">';
    $headers = array('<input type="checkbox" id="select_all" />',
        lang('suppliers_company_name'),
        lang('common_last_name'),
        lang('common_first_name'),
        lang('common_email'),
        lang('common_phone_number'),
        '&nbsp');
    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }

    $table.='</tr></thead><tbody>';
    $table.=get_supplier_manage_table_data_rows($suppliers, $controller);
    $table.='</tbody></table>';
    return $table;
}

/* Get record of supplier with pagination 
 * Chhingchhing
 */

function get_supplier_manage_table_pagination($suppliers, $controller) {
    $CI = & get_instance();
    $table = '<table class="table" id="data_table">';
    $headers = array('<input type="checkbox" id="select_all" />',
        lang('suppliers_company_name'),
        lang('common_last_name'),
        lang('common_first_name'),
        lang('common_email'),
        lang('common_phone_number'),
        '&nbsp');
    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }

    $table.='</tr></thead><tbody>';
    $table.=get_supplier_manage_table_data_rows($suppliers, $controller);
    $table.='</tbody></table>';
    return $table;
}

/* Get record of people(customer) with pagination */

function get_people_manage_table_pagination($people, $controller) {
    $CI = & get_instance();
    $table = '<table class="table" id="data_table">';

    $controller_name = strtolower(get_class($CI));

    $headers = array('<input type="checkbox" id="select_all" />',
        lang('common_last_name'),
        lang('common_first_name'),
        lang('common_email'),
        lang('common_phone_number'),
        lang('common_state'),
        '&nbsp');
    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_people_manage_table_data_rows($people, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
 * End of chhingchhing
 */

/*
  Gets the html data rows for the supplier.
 */

function get_supplier_manage_table_data_rows($suppliers, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($suppliers->result() as $supplier) {
        $table_data_rows.=get_supplier_data_row($supplier, $controller);
    }

    if ($suppliers->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='8'><div class='warning_message' style='padding:7px;'>" . lang('common_no_persons_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_supplier_data_row($supplier, $controller) {
    $CI = & get_instance();
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' name='ids[]' class='check_value' id='person_$supplier->person_id' value='" . $supplier->person_id . "'/></td>";
    $table_data_row.='<td width="17%">' . $supplier->company_name . '</td>';
    $table_data_row.='<td width="17%">' . $supplier->last_name . '</td>';
    $table_data_row.='<td width="17%">' . $supplier->first_name . '</td>';
    $table_data_row.='<td width="22%">' . mailto($supplier->email, $supplier->email) . '</td>';
    $table_data_row.='<td width="17%">' . $supplier->phone_number . '</td>';
    $table_data_row.='<td width="5%" class="rightmost">' . anchor("#$controller_name/viewJSON/$supplier->person_id", lang('common_edit'), array('class' => 'thickbox edit_supplier glyphicon glyphicon-edit', 'id' => 'edit', 'title' => lang($controller_name . '_update') )) . '</td>';
    $table_data_row.='</tr>';
    return $table_data_row;
}

/*
  Gets the html table to manage items.
 */

function get_items_manage_table($items, $controller) {
    $CI = & get_instance();
    $has_cost_price_permission = $CI->Employee->has_module_action_permission('items', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
    $table = '<table class="tablesorter" id="sortable_table">';

    if ($has_cost_price_permission) {
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('items_item_id'),
            $CI->lang->line('items_item_number'),
            $CI->lang->line('items_name'),
            $CI->lang->line('items_category'),
            $CI->lang->line('items_cost_price'),
            $CI->lang->line('items_unit_price'),
            $CI->lang->line('items_quantity'),
            $CI->lang->line('items_inventory'),
            '&nbsp;'
        );
    } else {
        $headers = array('<input type="checkbox" id="select_all" />',
            $CI->lang->line('items_item_id'),
            $CI->lang->line('items_item_number'),
            $CI->lang->line('items_name'),
            $CI->lang->line('items_category'),
            $CI->lang->line('items_unit_price'),
            $CI->lang->line('items_quantity'),
            $CI->lang->line('items_inventory'),
            '&nbsp;'
        );
    }

    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_items_manage_table_data_rows($items, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the items.
 */

function get_items_manage_table_data_rows($items, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($items->result() as $item) {
        $table_data_rows.=get_item_data_row($item, $controller);
    }

    if ($items->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . lang('items_no_items_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_item_data_row($item, $controller) {
    $CI = & get_instance();
    $has_cost_price_permission = $CI->Employee->has_module_action_permission('items', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);
    $item_tax_info = $CI->Item_taxes->get_info($item->item_id);
    $tax_percents = '';
    foreach ($item_tax_info as $tax_info) {
        $tax_percents.=$tax_info['percent'] . '%, ';
    }
    $tax_percents = substr($tax_percents, 0, -2);
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td width='3%'><input type='checkbox' id='item_$item->item_id' value='" . $item->item_id . "'/></td>";
    $table_data_row.='<td width="10%">' . $item->item_id . '</td>';
    $table_data_row.='<td width="15%">' . $item->item_number . '</td>';
    $table_data_row.='<td width="15%">' . $item->name . '</td>';
    $table_data_row.='<td width="11%">' . $item->category . '</td>';
    if ($has_cost_price_permission) {
        $table_data_row.='<td width="11%" align="right">' . to_currency($item->cost_price) . '</td>';
    }
    $table_data_row.='<td width="11%" align="right">' . to_currency($item->unit_price) . '</td>';
    $table_data_row.='<td width="11%">' . to_quantity($item->quantity) . '</td>';
    $table_data_row.='<td width="12%">' . anchor($controller_name . "/inventory/$item->item_id/width~$width", lang('common_inv'), array('class' => 'thickbox', 'title' => lang($controller_name . '_count'))) . '&nbsp;&nbsp;&nbsp;&nbsp;' . anchor($controller_name . "/count_details/$item->item_id/width~$width", lang('common_det'), array('class' => 'thickbox', 'title' => lang($controller_name . '_details_count'))) . '</td>'; //inventory details	
    $table_data_row.='<td width="4%" class="rightmost">' . anchor($controller_name . "/view/$item->item_id/width~$width", lang('common_edit'), array('class' => 'thickbox edit glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'))) . '</td>';

    $table_data_row.='</tr>';
    return $table_data_row;
}

/*
  Gets the html table to manage giftcards.
 */

function get_giftcards_manage_table($giftcards, $controller) {
    $CI = & get_instance();

    $table = '<table class="tablesorter" id="sortable_table">';

    $headers = array('<input type="checkbox" id="select_all" />',
        lang('giftcards_giftcard_number'),
        lang('giftcards_card_value'),
        lang('giftcards_customer_name'),
        '&nbsp',
    );

    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_giftcards_manage_table_data_rows($giftcards, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the giftcard.
 */

function get_giftcards_manage_table_data_rows($giftcards, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($giftcards->result() as $giftcard) {
        $table_data_rows.=get_giftcard_data_row($giftcard, $controller);
    }

    if ($giftcards->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . lang('giftcards_no_giftcards_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_giftcard_data_row($giftcard, $controller) {
    $CI = & get_instance();
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();
    $link = site_url('reports/detailed_' . $controller_name . '/' . $giftcard->customer_id . '/0');
    $cust_info = $CI->Customer->get_info($giftcard->customer_id);

    $table_data_row = '<tr>';
    $table_data_row.="<td width='3%'><input type='checkbox' id='giftcard_$giftcard->giftcard_id' value='" . $giftcard->giftcard_id . "'/></td>";
    $table_data_row.='<td width="15%">' . $giftcard->giftcard_number . '</td>';
    $table_data_row.='<td width="20%">' . to_currency($giftcard->value) . '</td>';
    $table_data_row.='<td width="15%"><a class="underline" href="' . $link . '">' . $cust_info->first_name . ' ' . $cust_info->last_name . '</a></td>';
    $table_data_row.='<td width="5%" class="rightmost">' . anchor($controller_name . "/view/$giftcard->giftcard_id/width~$width", lang('common_edit'), array('class' => 'thickbox edit glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'))) . '</td>';

    $table_data_row.='</tr>';
    return $table_data_row;
}

/*
  Gets the html table to manage item kits.
 */

function get_item_kits_manage_table($item_kits, $controller) {
    $CI = & get_instance();

    $table = '<table class="tablesorter table" id="data_table">';

    $headers = array('<input type="checkbox" id="select_all" />',
        lang('items_item_number'),
        lang('item_kits_name'),
        lang('item_kits_description'),
        lang('items_unit_price'),
        // lang('items_tax_percents'),
        '&nbsp',
    );

    $table.='<thead><tr>';
    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_item_kits_manage_table_data_rows($item_kits, $controller);
    $table.='</tbody></table>';
    return $table;
}

/*
  Gets the html data rows for the item kits.
 */

function get_item_kits_manage_table_data_rows($item_kits, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($item_kits->result() as $item_kit) {
        $table_data_rows.=get_item_kit_data_row($item_kit, $controller);
    }

    if ($item_kits->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='11'><div class='warning_message' style='padding:7px;'>" . lang('item_kits_no_item_kits_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_item_kit_data_row($item_kit, $controller) {
    $CI = & get_instance();
    /*$item_kit_tax_info = $CI->Item_kit_taxes->get_info($item_kit->item_kit_id);
    $tax_percents = '';
    foreach ($item_kit_tax_info as $tax_info) {
        $tax_percents.=$tax_info['percent'] . '%, ';
    }
    $tax_percents = substr($tax_percents, 0, -2);*/

    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $table_data_row = '<tr>';
    $table_data_row.="<td><input type='checkbox' name='ids[]' class='check_value' id='item_kit_$item_kit->item_kit_id' value='" . $item_kit->item_kit_id . "'/></td>";
    $table_data_row.='<td>' . $item_kit->item_kit_number . '</td>';
    $table_data_row.='<td>' . $item_kit->name . '</td>';
    $table_data_row.='<td>' . $item_kit->description . '</td>';
    $table_data_row.='<td>' . (!is_null($item_kit->unit_price) ? to_currency($item_kit->unit_price) : 'j') . '</td>';
    // $table_data_row.='<td width="20%">' . $tax_percents . '</td>';
    $table_data_row.='<td class="rightmost">' . anchor($controller_name . "/view_package/$item_kit->item_kit_id/$item_kit->category", lang('common_edit'), array('class' => 'thickbox edit glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'id' => 'edit_tour_package', 'modals' => 'tours_package')) . '</td>';
    $table_data_row.='</tr>';
    return $table_data_row;
}

// Chen's coding
/* START WITH NAME */
function get_bike_manage_table($people, $controller) {
    $CI = & get_instance();
    $has_cost_price_permission = $CI->Employee->has_module_action_permission('bikes', 'see_cost_price', $CI->Employee->get_logged_in_employee_info()->person_id);

$table = '<table class="tablesorter table table-hover" id="data_table" >';//id="sortable_table"

    if ($has_cost_price_permission) {    
    $controller_name = strtolower(get_class($CI));
    $headers = array('<input type="checkbox" id="select_all" />',
        lang('bikes_bike_code'),
        lang('bikes_available'),
        lang('bikes_unit_price'),
        lang('bikes_actual_price'),
        lang('bikes_bike_types'),
        lang('bikes_action'),
       );
    }else{
         $controller_name = strtolower(get_class($CI));
        $headers = array('<input type="checkbox" id="select_all" />',
        lang('bikes_bike_code'),
        lang('bikes_available'),
        lang('bikes_unit_price'),
        lang('bikes_actual_price'),
        lang('bikes_bike_types'),
        lang('bikes_action'),
       );
    }
    $table.='<thead><tr>';

    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_bike_manage_table_data_rows($people, $controller);
    $table.='</tbody></table>';
    return '<center>' . $table . '</center>';
}

//start with new bike
function get_bike_manage_table_data_rows($people, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($people->result() as $bike) {
        $table_data_rows.=get_bike_data_row($bike, $controller);
    }

    if ($people->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='7'><div class='warning_message' style='padding:7px;'>" . lang('common_no_persons_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

/* end func for bike */

//start show data with manage tbl
function get_bike_data_row($bike, $controller) {
    $CI = & get_instance();
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();
    if($bike->available == 1){
        $avail = 'Available';
    }
    else
    {  
        $avail = 'Unavailable';
    }
    
    $start_of_time = date('Y-m-d', 0);
    $today = date('Y-m-d') . ' 23:59:59';
//  $link = site_url('reports/specific_'.($controller_name == 'customers' ? 'customer' : 'employee').'/'.$start_of_time.'/'.$today.'/'.$person->person_id.'/all/0');
    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' class='check_value' name='ids[]' id='massage_$bike->item_bike_id ' value='" . $bike->item_bike_id . "'/></td>";
    $table_data_row.='<td width="10%">' . $bike->bike_code . '</td>';
    $table_data_row.='<td width="10%">' . $avail . '</td>';
    $table_data_row.='<td width="10%">' . $bike->unit_price . '</td>';
    $table_data_row.='<td width="10%">' . $bike->actual_price . '</td>';
    $table_data_row.='<td width="10%">' . $bike->bike_types . '</td>';
    $table_data_row.='<td width="5%" class="rightmost">' . anchor("#$controller_name/viewJSON/$bike->item_bike_id", lang('common_edit'), array('class' => 'thickbox edit glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'modals' => 'bikes')) . '</td>';

    $table_data_row.='</tr>';

    return $table_data_row;
}

//end show data with manage bike tbl

/* START WITH NAME */
function get_tour_manage_table($people, $controller) {
    $CI = & get_instance();
    $table = '<table class="table table-hover" id="data_table">';

    $table = '<table class="table table-hover" id="data_table" style="width:850px;">';

    $controller_name = strtolower(get_class($CI));
    $headers = array('<input type="checkbox" id="select_all" />',
        lang('tours_tour_name'),
        lang('tours_by'),
        lang('tours_price'),
        lang('tours_destination_name'),
        lang('tours_supplier'),
        lang('tours_desc'),
        '&nbsp');
    $table.='<thead><tr>';

    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_tour_manage_table_data_rows($people, $controller);
    $table.='</tbody></table>';
    return '<center>' . $table . '</center>';
}

//start with new massage
function get_tour_manage_table_data_rows($people, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($people->result() as $tour) {
        $table_data_rows.=get_tour_data_row($tour, $controller);
    }

    if ($people->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='7'><div class='warning_message' style='padding:7px;'>" . lang('common_no_persons_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

//start show data with manage tbl
function get_tour_data_row($tour, $controller) {
    $CI = & get_instance();
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $start_of_time = date('Y-m-d', 0);
    $today = date('Y-m-d') . ' 23:59:59';
    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' class='check_value' name='ids[]' id='tour_$tour->tour_id ' value='" . $tour->tour_id . "'/></td>";
    $table_data_row.='<td width="10%">' . $tour->tour_name . '</td>';
    $table_data_row.='<td width="10%">' . $tour->by . '</td>';
    $table_data_row.='<td width="10%">' . $tour->sale_price . '</td>';
    $table_data_row.='<td width="10%">' . $tour->destination_name . '</td>';
    $table_data_row.='<td width="10%">' . $tour->company_name . '</td>';
    $table_data_row.='<td width="10%">' . $tour->description . '</td>';
    $table_data_row.='<td width="5%" class="rightmost">' . anchor("#$controller_name/viewJSON/$tour->tour_id", lang('common_edit'), array('class' => 'thickbox edit glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'id' => 'edit_tour', 'modals' => "tours")) . '</td>';
    
    $table_data_row.='</tr>';

    return $table_data_row;
}

/* START WITH NAME */

function get_massage_manage_table($people, $controller) {
    $CI = & get_instance();
    $table = '<table class="table table-hover" id="data_table">';

    $controller_name = strtolower(get_class($CI));
    $headers = array('<input type="checkbox" id="select_all" />',
        lang('common_massage_name'),
        lang('common_massage_desc'),
        lang('common_price_one'),
        lang('common_price_actual'),
        '&nbsp');
    $table.='<thead><tr>';

    $count = 0;
    foreach ($headers as $header) {
        $count++;

        if ($count == 1) {
            $table.="<th class='leftmost'>$header</th>";
        } elseif ($count == count($headers)) {
            $table.="<th class='rightmost'>$header</th>";
        } else {
            $table.="<th>$header</th>";
        }
    }
    $table.='</tr></thead><tbody>';
    $table.=get_massage_manage_table_data_rows($people, $controller);
    $table.='</tbody></table>';
    return '<center>' . $table . '</center>';
}

//start with new massage
function get_massage_manage_table_data_rows($people, $controller) {
    $CI = & get_instance();
    $table_data_rows = '';

    foreach ($people->result() as $massage) {
        $table_data_rows.=get_massage_data_row($massage, $controller);
    }

    if ($people->num_rows() == 0) {
        $table_data_rows.="<tr><td colspan='7'><div class='warning_message' style='padding:7px;'>" . lang('common_no_persons_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

//end with new massage
//start show data with manage tbl
function get_massage_data_row($massage, $controller) {
    $CI = & get_instance();
    $controller_name = strtolower(get_class($CI));
    $width = $controller->get_form_width();

    $start_of_time = date('Y-m-d', 0);
    $today = date('Y-m-d') . ' 23:59:59';
//  $link = site_url('reports/specific_'.($controller_name == 'customers' ? 'customer' : 'employee').'/'.$start_of_time.'/'.$today.'/'.$person->person_id.'/all/0');
    $table_data_row = '<tr>';
    $table_data_row.="<td width='5%'><input type='checkbox' class='check_value' name='ids[]' id='massage_$massage->item_massage_id ' value='" . $massage->item_massage_id . "'/></td>";
    $table_data_row.='<td width="10%">' . $massage->massage_name . '</td>';
    $table_data_row.='<td width="10%">' . $massage->massage_desc . '</td>';
    $table_data_row.='<td width="10%">' . $massage->price_one . '</td>';
    $table_data_row.='<td width="10%">' . $massage->actual_price. '</td>';
    $table_data_row.='<td width="5%" class="rightmost">' . anchor("#$controller_name/viewJSON/$massage->item_massage_id", lang('common_edit'), array('class' => 'thickbox edit glyphicon glyphicon-edit', 'title' => lang($controller_name . '_update'), 'modals' => 'massages')) . '</td>';
//    $table_data_row.='<td width="5%" class="rightmost">'.$pagination.'</td>';
    $table_data_row.='</tr>';

    return $table_data_row;
}

// End Chen's coding
?>