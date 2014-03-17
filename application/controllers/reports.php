<?php

require_once ("secure_area.php");

class Reports extends Secure_area {

    function __construct() {
        parent::__construct('reports');
        $this->load->helper('report');
        $this->load->model(array("Sale_ticket"));
        $this->load->model(array("Sale_massage"));
        $this->load->model(array("Massage"));
        $this->load->model(array('reports/Summary_tickets'));
        $this->load->model(array('reports/Summary_massages'));
        $this->load->model(array('reports/Sales_generator'));
    }

    function index() {
        $this->reports_tickets();
    }

    //Initial report listing screen
    function reports_tickets() {

        $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        $office = substr($this->uri->segment(3), -1);

        $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed          
        $data['allowed_modules'] = $this->check_module_accessable();
        $this->load->view("reports/listing_tickets", $data);
    }

    function reports_bikes() {
        $data['allowed_modules'] = $this->check_module_accessable();
        $this->load->view("reports/listing_bikes", $data);
    }

    function reports_massages() {
        $data['allowed_modules'] = $this->check_module_accessable();
        $this->load->view("reports/listing_massages", $data);
    }

    function reports_tours() {
        $data['allowed_modules'] = $this->check_module_accessable();
        $this->load->view("reports/listing_tours", $data);
    }

//    function summary_report_ticket() {
//          
//            $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
//            $office = substr($this->uri->segment(3), -1);
//            $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed          
//            $this->load->view("reports/summary_report_ticket", $data);
//        
//    }

    // Sales Generator Reports 
   function sales_generator_tickets($office, $controller) {
    $data['allowed_modules'] = $this->check_module_accessable();
    $data['controller_name'] = strtolower(get_class());
    $newController = explode("_", $this->uri->segment(2));

       if ($this->input->get('act') == 'autocomplete') { // Must return a json string
           if ($this->input->get('w') != '') { // From where should we return data
               if ($this->input->get('term') != '') { // What exactly are we searchin
                   switch ($this->input->get('w')) {
                       case 'customers':
                           $t = $this->Customer->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v->customer_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                           }
                           die(json_encode($tmp));
                           break;
                       case 'employees':
                           $t = $this->Employee->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v->employee_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                           }
                           die(json_encode($tmp));
                           break;
                       case 'itemsCategory':
                           $t = $this->Item->get_category_suggestions($this->input->get('term'));
                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v['label'], 'name' => $v['label']);
                           }
                           die(json_encode($tmp));
                           break;
                       case 'suppliers':
                           $t = $this->Supplier->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v->supplier_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->company_name . " - " . $v->email);
                           }
                           die(json_encode($tmp));
                           break;
                       case 'itemsKitName':
                           $t = $this->Item_kit->search($this->input->get('term'), 100, 0, 'name', 'asc')->result_object();
                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v->item_kit_id, 'name' => $v->name . " / #" . $v->item_kit_number);
                           }
                           die(json_encode($tmp));
                           break;
                       case 'itemsName':
                           $t = $this->Item->search($this->input->get('term'), 100, 0, 'name', 'asc')->result_object();
                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v->item_id, 'name' => $v->name);
                           }
                           die(json_encode($tmp));
                           break;
                       case 'paymentType':
                           $t = array(lang('sales_cash'), lang('sales_check'), lang('sales_giftcard'), lang('sales_debit'), lang('sales_credit'));

                           foreach ($this->Appconfig->get_additional_payment_types() as $additional_payment_type) {
                               $t[] = $additional_payment_type;
                           }

                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v, 'name' => $v);
                           }
                           die(json_encode($tmp));
                           break;
                   }
               } else {
                   die;
               }
           } else {
               die(json_encode(array('value' => 'No such data found!')));
           }
       }

       $postData = array();
       $data = $this->_get_common_report_data();
       $data["title"] = lang('reports_sales_generator');
       $data["subtitle"] = lang('reports_sales_report_generator');

       $setValues = array('report_type' => '', 'sreport_date_range_simple' => '',
           'start_month' => date("m"), 'start_day' => date('d'), 'start_year' => date("Y"),
           'end_month' => date("m"), 'end_day' => date('d'), 'end_year' => date("Y"),
           'matchType' => '',
           'matched_items_only' => FALSE
       );
       foreach ($setValues as $k => $v) {
           if (empty($v) && !isset($data[$k])) {
               $data[$k] = '';
           } else {
               $data[$k] = $v;
           }
       }

       if ($this->input->post('generate_report')) { // Generate Custom Raport
           $data['report_type'] = $this->input->post('report_type');
           $data['sreport_date_range_simple'] = $this->input->post('report_date_range_simple');

           $data['start_month'] = $this->input->post('start_month');
           $data['start_day'] = $this->input->post('start_day');
           $data['start_year'] = $this->input->post('start_year');
           $data['end_month'] = $this->input->post('end_month');
           $data['end_day'] = $this->input->post('end_day');
           $data['end_year'] = $this->input->post('end_year');
           if ($data['report_type'] == 'simple') {
               $q = explode("/", $data['sreport_date_range_simple']);
               list($data['start_year'], $data['start_month'], $data['start_day']) = explode("-", $q[0]);
               list($data['end_year'], $data['end_month'], $data['end_day']) = explode("-", $q[1]);
           }
           $data['matchType'] = $this->input->post('matchType');
           $data['matched_items_only'] = $this->input->post('matched_items_only') ? TRUE : FALSE;

           $data['field'] = $this->input->post('field');
           $data['condition'] = $this->input->post('condition');
           $data['value'] = $this->input->post('value');

           $data['prepopulate'] = array();

           $field = $this->input->post('field');
           $condition = $this->input->post('condition');
           $value = $this->input->post('value');

           $tmpData = array();
           foreach ($field as $a => $b) {
               $uData = explode(",", $value[$a]);
               $tmp = $tmpID = array();
               switch ($b) {
                   case '1': // Customer
                       $t = $this->Customer->get_multiple_info($uData)->result_object();
                       foreach ($t as $k => $v) {
                           $tmpID[] = $v->customer_id;
                           $tmp[$k] = array('id' => $v->customer_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                       }
                       break;
                   case '2': // Ticket Number
                       $tmpID[] = $value[$a];
                       break;
                   case '3': // Employees
                       $t = $this->Employee->get_multiple_info($uData)->result_object();
                       foreach ($t as $k => $v) {
                           $tmpID[] = $v->employee_id;
                           $tmp[$k] = array('id' => $v->employee_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                       }
                       break;
                   case '4': // Items Category
                       foreach ($uData as $k => $v) {
                           $tmpID[] = $v;
                           $tmp[$k] = array('id' => $v, 'name' => $v);
                       }
                       break;
                   case '5': // Suppliers 
                       $t = $this->Supplier->get_multiple_info($uData)->result_object();
                       foreach ($t as $k => $v) {
                           $tmpID[] = $v->supplier_id;
                           $tmp[$k] = array('id' => $v->supplier_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->company_name . " - " . $v->email);
                       }
                       break;
                   case '6': // Sale Type
                       $tmpID[] = $condition[$a];
                       break;
                   case '7': // Sale Amount
                       $tmpID[] = $value[$a];
                       break;
                   // case '8': // Item Kits
                   //     $t = $this->Item_kit->get_multiple_info($uData)->result_object();
                   //     foreach ($t as $k => $v) {
                   //         $tmpID[] = $v->item_kit_id;
                   //         $tmp[$k] = array('id' => $v->item_kit_id, 'name' => $v->name . " / #" . $v->item_kit_number);
                   //     }
                   //     break;
                   // case '9': // Items Name
                   //     $t = $this->Item->get_multiple_info($uData)->result_object();
                   //     foreach ($t as $k => $v) {
                   //         $tmpID[] = $v->item_id;
                   //         $tmp[$k] = array('id' => $v->item_id, 'name' => $v->name);
                   //     }
                   //     break;
                   case '10': // SaleID
                       if (strpos(strtoupper($value[$a]), 'CGATE') !== FALSE) {
                           $pieces = explode(' ', $value[$a]);
                           $value[$a] = (int) $pieces[1];
                       }
                       $tmpID[] = $value[$a];
                       break;
                   case '11': // Payment type
                       foreach ($uData as $k => $v) {
                           $tmpID[] = $v;
                           $tmp[$k] = array('id' => $v, 'name' => $v);
                       }
                       break;
               }
               $data['prepopulate']['field'][$a][$b] = $tmp;

               // Data for sql
               $tmpData[] = array('f' => $b, 'o' => $condition[$a], 'i' => $tmpID);
           }

           $params['matchType'] = $data['matchType'];
           $params['matched_items_only'] = $data['matched_items_only'];
           $params['ops'] = array(
               1 => " = 'xx'",
               2 => " != 'xx'",
               5 => " IN ('xx')",
               6 => " NOT IN ('xx')",
               7 => " > xx",
               8 => " < xx",
               9 => " = xx",
               10 => '', // Sales
               11 => '', // Returns
           );

           $params['tables'] = array(
               1 => 'sales_tickets_temp.customer_id', // Customers
               3 => 'sales_tickets_temp.employee_id', // Employees
               // 4 => 'sales_tickets_temp.category', // Item Category
               5 => 'sales_tickets_temp.supplier_id', // Suppliers
               6 => '', // Sale Type
               7 => '', // Sale Amount
               8 => 'sales_tickets_temp.item_kit_id', // Item Kit Name
               9 => 'sales_tickets_temp.ticket_id', // Item Name
               10 => 'sales_tickets_temp.ID', // Sale ID
               11 => 'sales_tickets_temp.payment_type' // Payment Type
           );
           $params['values'] = $tmpData;

           $this->load->model('reports/Sales_generator');
           $model = $this->Sales_generator;
           $model->setParams($params);

           // Sales Interval Reports
           $interval = array(
               'start_date' => $data['start_year'] . '-' . $data['start_month'] . '-' . $data['start_day'],
               'end_date' => $data['end_year'] . '-' . $data['end_month'] . '-' . $data['end_day'] . ' 23:59:59',
           );
           $this->Sale_ticket->create_sales_tickets_temp_table($interval);
           $tabular_data = array();
           $report_data = $model->getData();
           $summary_data = array();
           $details_data = array();
// $newController[2].
           foreach ($report_data['summary'] as $key => $row) {
               $summary_data[] = array(
                array('data' => anchor('tickets/edit/' . $office . '/' . $row['ID'], lang('common_edit'), array('class' => 'glyphicon glyphicon-edit', 'target' => '_blank')) . ' ' .
                    anchor('tickets/receipt/' . $office . '/' . $row['ID'], lang('common_print_receipt_blank'), array('class' => 'glyphicon glyphicon-print', 'target' => '_blank')) . ' ' .
                    anchor('tickets/edit/' . $office . '/' . $row['ID'], lang('common_edit_text') . ' ' . $row['ID'], array('target' => '_blank')), 'align' => 'left'),
                   array('data' => date('d/m/Y' . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left'),
                   array('data' => to_quantity($row['items_purchased']), 'align' => 'left'),
                   array('data' => $row['employee_name'], 'align' => 'left'),
                   array('data' => $row['customer_name'], 'align' => 'left'),
                   array('data' => to_currency($row['subtotal']), 'align' => 'right'),
                   array('data' => to_currency($row['total']), 'align' => 'right'),
                   array('data' => to_currency($row['profit']), 'align' => 'right'),
                   array('data' => $row['payment_type'], 'align' => 'right'),
                   array('data' => $row['comment'], 'align' => 'right'));

               foreach ($report_data['details'][$key] as $drow) {
                   $details_data[$key][] = array(
                    array('data' => isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align' => 'left'), 
                    array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left'), 
                    array('data' => $drow['descriptions'], 'align' => 'left'), 
                    array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'), 
                    array('data' => to_currency($drow['subtotal']), 'align' => 'right'), 
                    array('data' => to_currency($drow['total']), 'align' => 'right'),
                    array('data' => to_currency($drow['profit']), 'align' => 'right'), 
                    array('data' => $drow['discount_percent'] . '%', 'align' => 'left')
                    );
               }
           }

           $reportdata = array(
               "title" => lang('reports_sales_generator'),
               "subtitle" => lang('reports_sales_report_generator') . " - " . date('d/m/Y', strtotime($interval['start_date'])) . ' - ' . date('d/m/Y', strtotime($interval['end_date'])) . " - " . count($report_data['summary']) . ' ' . lang('reports_sales_report_generator_results_found'),
               "headers" => $model->getDataColumns(),
               "summary_data" => $summary_data,
               "details_data" => $details_data,
               "overall_summary_data" => $model->getSummaryData(),
           );

           // Fetch & Output Data 
           $data['results'] = $this->load->view("reports/sales_generator_tabular_details", $reportdata, true);
       }
        $data['controller_name'] = strtolower($controller);
       $this->load->view("reports/sales_generator", $data);
   }

    function _get_common_report_data($time = false) {

        $data = array();
        $data['report_date_range_simple'] = get_simple_date_ranges($time);
        $data['months'] = get_months();
        $data['days'] = get_days();
        $data['years'] = get_years();
        $data['hours'] = get_hours($this->config->item('time_format'));
        $data['minutes'] = get_minutes();
        $data['selected_month'] = date('m');
        $data['selected_day'] = date('d');
        $data['selected_year'] = date('Y');
        $data['allowed_modules'] = $this->check_module_accessable();

        return $data;
    }

    //Input for reports that require only a date range and an export to excel. (see routes.php to see that all summary reports route here)
    function date_input_excel_export() {
        $data['allowed_modules'] = $this->check_module_accessable();
        $data['get_report'] = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/date_input_excel_export", $data);
    }

    /** added for register log */
//    function date_input_excel_export_register_log() {
//        $data = $this->_get_common_report_data();
//        $this->load->view("reports/date_input_excel_register_log.php", $data);
//    }

    /** also added for register log */
//    function detailed_register_log($start_date, $end_date, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Detailed_register_log');
//        $model = $this->Detailed_register_log;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date));
//
//        $headers = $model->getDataColumns();
//        $report_data = $model->getData();
//
//        $summary_data = array();
//        $details_data = array();
//
//        $overallSummaryData = array(
//            'total_cash_sales' => 0,
//            'total_shortages' => 0,
//            'total_overages' => 0,
//            'total_difference' => 0
//        );
//
//        foreach ($report_data['summary'] as $row) {
//            if ($row['shift_end'] == '0000-00-00 00:00:00') {
//                $shift_end = lang('reports_register_log_open');
//            } else {
//                $shift_end = date(get_date_format(), strtotime($row['shift_end'])) . ' ' . date(get_time_format(), strtotime($row['shift_end']));
//            }
//            $summary_data[] = array(
//                array('data' => $row['first_name'] . ' ' . $row['last_name'], 'align' => 'left'),
//                array('data' => date(get_date_format(), strtotime($row['shift_start'])) . ' ' . date(get_time_format(), strtotime($row['shift_start'])), 'align' => 'left'),
//                array('data' => $shift_end, 'align' => 'left'),
//                array('data' => to_currency($row['open_amount']), 'align' => 'right'),
//                array('data' => to_currency($row['close_amount']), 'align' => 'right'),
//                array('data' => to_currency($row['cash_sales_amount']), 'align' => 'right'),
//                array('data' => to_currency($row['difference']), 'align' => 'right')
//            );
//
//            $overallSummaryData['total_cash_sales'] += $row['cash_sales_amount'];
//            if ($row['difference'] > 0) {
//                $overallSummaryData['total_overages'] += $row['difference'];
//            } else {
//                $overallSummaryData['total_shortages'] += $row['difference'];
//            }
//
//            $overallSummaryData['total_difference'] += $row['difference'];
//        }
//
//        $data = array(
//            "title" => lang('reports_register_log_title'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "data" => $summary_data,
//            "details_data" => array(),
//            "summary_data" => $overallSummaryData,
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
    //Summary sales report
//    function summary_sales($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Summary_sales');
//        $model = $this->Summary_sales;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//        $tabular_data = array();
//        $report_data = $model->getData();
//
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(
//                array('data' => date(get_date_format(), strtotime($row['sale_date'])), 'align' => 'left'),
//                array('data' => to_currency($row['subtotal']), 'align' => 'right'),
//                array('data' => to_currency($row['total']), 'align' => 'right'),
//                array('data' => to_currency($row['tax']), 'align' => 'right'),
//                array('data' => to_currency($row['profit']), 'align' => 'right'));
//        }
//
//        $data = array(
//            "title" => lang('reports_sales_summary_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
    //Summary categories report
//    function summary_categories($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Summary_categories');
//        $model = $this->Summary_categories;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//        $tabular_data = array();
//        $report_data = $model->getData();
//
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['category'], 'align' => 'left'), array('data' => to_currency($row['subtotal']), 'align' => 'right'), array('data' => to_currency($row['total']), 'align' => 'right'), array('data' => to_currency($row['tax']), 'align' => 'right'), array('data' => to_currency($row['profit']), 'align' => 'right'));
//        }
//
//        $data = array(
//            "title" => lang('reports_categories_summary_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
//
//    //Summary customers report
//    function summary_customers($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Summary_customers');
//        $model = $this->Summary_customers;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $tabular_data = array();
//        $report_data = $model->getData();
//        $no_customer = $model->getNoCustomerData();
//        $report_data = array_merge($no_customer, $report_data);
//
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['customer'], 'align' => 'left'), array('data' => to_currency($row['subtotal']), 'align' => 'right'), array('data' => to_currency($row['total']), 'align' => 'right'), array('data' => to_currency($row['tax']), 'align' => 'right'), array('data' => to_currency($row['profit']), 'align' => 'right'));
//        }
//
//        $data = array(
//            "title" => lang('reports_customers_summary_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
//
//    //Summary suppliers report
//    function summary_suppliers($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Summary_suppliers');
//        $model = $this->Summary_suppliers;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//        $tabular_data = array();
//        $report_data = $model->getData();
//
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['supplier'], 'align' => 'left'), array('data' => to_currency($row['subtotal']), 'align' => 'right'), array('data' => to_currency($row['total']), 'align' => 'right'), array('data' => to_currency($row['tax']), 'align' => 'right'), array('data' => to_currency($row['profit']), 'align' => 'right'));
//        }
//
//        $data = array(
//            "title" => lang('reports_suppliers_summary_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }






    function summary_tickets($office, $start_date, $end_date, $sale_type, $export_excel = 0) {

        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model(array('reports/Summary_tickets', 'Ticket', 'Commissioner'));
        $model = $this->Summary_tickets;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $this->Sale_ticket->create_sales_tickets_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $destination = $this->Ticket->report_destination_name($row['destinationID']);
            $by = $this->Ticket->report_transport_by($row['ticket_typeID']);

            $comm = $this->Commissioner->get_information($row['commisioner_id']);
            $commissioner = ucwords($comm->first_name . ' ' . $comm->last_name);

            $emp = $this->Employee->get_info($row['employee_id']);
            $employee = ucwords($emp->first_name . ' ' . $emp->last_name);

            if ($row['deposit'] != 0) {
                $balance = $row['total'] - $row['deposit'];
            } else {
                $balance = $row['deposit'];
            }

            $tabular_data[] = array(
                array('data' => $row['ID'], 'align' => 'left'),
                array('data' => date('d/m/Y H:i:s', strtotime(rawurldecode($row['issue_date']))), 'align' => 'left'),
                array('data' => $row['date_departure'] != '0000-00-00' ? date('d/m/Y', strtotime(rawurlencode($row['date_departure']))) : '', 'align' => 'left'),
                array('data' => $by, 'align' => 'left'),
                array('data' => $destination, 'align' => 'left'),
                array('data' => $row['seat_number'], 'align' => 'left'),
                array('data' => $row['time_departure'] != '00:00:00' ? $row['time_departure'] : '', 'align' => 'left'),
                array('data' => $row['hotel_name'], 'align' => 'left'),
                array('data' => $row['room_number'], 'align' => 'left'),
                array('data' => $row['company_name'], 'align' => 'left'),
                array('data' => to_quantity($row['quantity']), 'align' => 'left'),
                array('data' => $row['item_number'], 'align' => 'left'),
                array('data' => to_currency($row['item_unit_price']), 'align' => 'left'),
                array('data' => to_currency($row['total']), 'align' => 'right'),
                array('data' => to_currency($row['deposit']), 'align' => 'right'),
                array('data' => to_currency($balance), 'align' => 'left'),
                array('data' => $commissioner, 'align' => 'left'),
                // array('data'=>'Sale', 'align' => 'left'),
                array('data' => $employee, 'align' => 'left'),
                    // array('data'=>to_quantity($row['quantity_purchased']), 'align' => 'left'), 
                    // array('data'=>to_currency($row['profit']), 'align' => 'right'),
            );
        }

        $data = array(
            "title" => lang('reports_tickets_summary_report'),
            "subtitle" => date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel
        );
        $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        $office = substr($this->uri->segment(3), -1);
        $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed          

        $data['allowed_modules'] = $this->check_module_accessable();

        $this->load->view('reports/tabular', $data);
    }

    function graphical_tickets_summary($office, $start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model(array('reports/Summary_tickets', 'Ticket', 'Commissioner'));
        $model = $this->Summary_tickets;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale_ticket->create_sales_tickets_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $data = array(
            "title" => lang('reports_items_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_tickets_graph/$office/$start_date/$end_date/$sale_type"),
            "subtitle" => date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );
//       var_dump($data);

        $data['allowed_modules'] = $this->check_module_accessable();

        $this->load->view("reports/graphical", $data);
    }

//  function test($office, $start_date, $end_date, $sale_type) {
//       $start_date = rawurldecode($start_date);
//       $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//       $this->load->model('reports/Summary_tickets');
//       $model = $this->Summary_tickets;
//       $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//       $this->Sale_ticket->create_sales_tickets_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//       $report_data = $model->getData();
//
//       $graph_data = array();
//       foreach ($report_data as $row) {
//           $graph_data[$row['ticket_name']] = $row['total'];
//       }
//
//       $data = array(
//           "title" => lang('reports_items_summary_report'),
//           "data" => $graph_data
//       );
//
//       $this->load->view("reports/graphs/pie", $data);
//   } 
    //The actual graph data
    function graphical_summary_tickets_graph($office, $start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_tickets');
        $model = $this->Summary_tickets;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

//       if(exist('cgate_sales_tickets_temp')){
//       drop table if exists temp2
//           $query = "DROP TABLE IF EXISTS cgate_sales_tickets_temp";
//           $this->db->query($query);
//////       }
//       
        $this->Sale_ticket->create_sales_tickets_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//$result = mysql_query("SHOW COLUMNS FROM cgate_sales_tickets_temp");
//if (!$result) {
//   echo 'Impossible d\'exécuter la requête : ' . mysql_error();
//   exit;
//}
//if (mysql_num_rows($result) > 0) {
//   while ($row = mysql_fetch_assoc($result)) {
//      print_r($row);
//   }
//}


        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['ticket_name']] = $row['total'];
        }

        $data = array(
            "title" => lang('reports_items_summary_report'),
            "data" => $graph_data
        );
//       var_dump($data);

        $this->load->view("reports/graphs/pie", $data);
    }

    function detailed_sales_ticket($office, $start_date, $end_date, $sale_type, $export_excel = 0) {
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Detailed_sales');
        $model = $this->Detailed_sales;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale_ticket->create_sales_tickets_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {
            $link = site_url('reports/specific_customer_for_ticket/' . $office . '/' . $start_date . '/' . $end_date . '/' . $row['customer_id'] . '/all/0');
            $summary_data[] = array(
                array('data' => anchor('tickets/edit/' . $office . '/' . $row['ID'], lang('common_edit'), array('class' => 'glyphicon glyphicon-edit', 'target' => '_blank')) . ' ' .
                    anchor('tickets/receipt/' . $office . '/' . $row['ID'], lang('common_print_receipt_blank'), array('class' => 'glyphicon glyphicon-print', 'target' => '_blank')) . ' ' .
                    anchor('tickets/edit/' . $office . '/' . $row['ID'], lang('common_edit_text') . ' ' . $row['ID'], array('target' => '_blank')), 'align' => 'left'),
                array('data' => date('d/m/Y' . '-' . get_time_format(), strtotime($row['issue_date'])), 'align' => 'left'),
                array('data' => to_quantity($row['items_purchased']), 'align' => 'left'),
                array('data' => $row['employee_name'], 'align' => 'left'),
                array('data' => '<a href="' . $link . '" target="_blank">' . $row['customer_name'] . '</a>', 'align' => 'left'),
                array('data' => $row['commissioner_name'], 'align' => 'left'),
                array('data' => $row['commision_price'], 'align' => 'left'),
                array('data' => to_currency($row['subtotal']), 'align' => 'right'),
                array('data' => to_currency($row['total']), 'align' => 'right'),
                array('data' => to_currency($row['profit']), 'align' => 'right'),
                array('data' => $row['payment_type'], 'align' => 'right'),
                array('data' => $row['comment'], 'align' => 'right'));

            foreach ($report_data['details'][$key] as $drow) {
                $details_data[$key][] = array(
                    array('data' => isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align' => 'left'),
                    array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left'),
                    array('data' => $drow['description'], 'align' => 'left'),
                    array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'),
                    array('data' => to_currency($drow['subtotal']), 'align' => 'right'),
                    array('data' => to_currency($drow['total']), 'align' => 'right'),
                    array('data' => to_currency($drow['profit']), 'align' => 'right'),
                    array('data' => $drow['discount_percent'] . '%', 'align' => 'left')
                );
            }
        }

        $data = array(
            "title" => lang('reports_detailed_sales_report'),
            "subtitle" => date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel
        );

        $data['allowed_modules'] = $this->check_module_accessable();

        $this->load->view("reports/tabular_details", $data);
    }

    function specific_customer_for_ticket($office, $start_date, $end_date, $customer_id, $sale_type, $export_excel = 0) {
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Specific_customer');
        $model = $this->Specific_customer;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'customer_id' => $customer_id, 'sale_type' => $sale_type));

        $this->Sale_ticket->create_sales_tickets_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'customer_id' => $customer_id, 'sale_type' => $sale_type));

        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {
            $summary_data[] = array(
                array('data' => anchor('tickets/edit/' . $office . '/' . $row['ID'], lang('common_edit') . ' ' . $row['ID'], array('target' => '_blank')), 'align' => 'left'),
                array('data' => date('d/m/Y' . '-' . get_time_format(), strtotime($row['issue_date'])), 'align' => 'left'),
                array('data' => to_quantity($row['items_purchased']), 'align' => 'left'),
                array('data' => $row['employee_name'], 'align' => 'left'),
                array('data' => $row['commissioner_name'], 'align' => 'left'),
                array('data' => $row['commision_price'], 'align' => 'left'),
                array('data' => to_currency($row['subtotal']), 'align' => 'right'),
                array('data' => to_currency($row['total']), 'align' => 'right'),
                array('data' => to_currency($row['profit']), 'align' => 'right'),
                array('data' => $row['payment_type'], 'align' => 'left'),
                array('data' => $row['comment'], 'align' => 'left')
            );

            foreach ($report_data['details'][$key] as $drow) {
                $details_data[$key][] = array(
                    array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left'),
                    array('data' => $drow['category'], 'align' => 'left'),
                    array('data' => $drow['description'], 'align' => 'left'),
                    array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'),
                    array('data' => to_currency($drow['subtotal']), 'align' => 'right'),
                    array('data' => to_currency($drow['total']), 'align' => 'right'),
                    array('data' => to_currency($drow['profit']), 'align' => 'right'),
                    array('data' => $drow['discount_percent'] . '%', 'align' => 'left')
                );
            }
        }

        $customer_info = $this->Customer->get_info($customer_id);
        $data = array(
            "title" => $customer_info->first_name . ' ' . $customer_info->last_name . ' ' . lang('reports_report'),
            "subtitle" => date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel
        );
        $data['allowed_modules'] = $this->check_module_accessable();

        $this->load->view("reports/tabular_details", $data);
    }

    /* function specific_customer_input() {
      $data = $this->_get_common_report_data(TRUE);
      $data['specific_input_name'] = lang('reports_customer');

      $customers = array();
      foreach ($this->Customer->get_all()->result() as $customer) {
      $customers[$customer->person_id] = $customer->first_name . ' ' . $customer->last_name;
      }
      $data['specific_input_data'] = $customers;
      $this->load->view("reports/specific_input", $data);
      } */

//-------------------------- Start Summary Massage Report-----------------------------
    //Summary tickets report
    function summary_massages($office, $start_date, $end_date, $sale_type, $export_excel = 0) {
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model(array('reports/Summary_massages', 'Massage', 'Commissioner'));
        $model = $this->Summary_massages;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale_massage->create_sales_massages_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $tabular_data = array();
        $report_data = $model->getData();
        foreach ($report_data as $row) {
            $comm = $this->Commissioner->get_information($row['commisioner_id']);
            $commissioner = ucwords($comm->first_name . ' ' . $comm->last_name);

            $emp = $this->Employee->get_info($row['employee_id']);
            $employee = ucwords($emp->first_name . ' ' . $emp->last_name);


            $tabular_data[] = array(
                array('data' => $row['ID'], 'align' => 'left'),
                array('data' => date('d/m/Y', strtotime(rawurldecode($row['issue_date']))), 'align' => 'left'),
                array('data' => $row['time_in'], 'align' => 'left'),
                array('data' => $row['time_out'], 'align' => 'left'),
                array('data' => $row['name_of_massage'], 'align' => 'left'),
                array('data' => to_currency($row['unit_price']), 'align' => 'left'),
                array('data' => to_currency($row['sale_price']), 'align' => 'right'),
                array('data' => to_quantity($row['quantity_purchased']), 'align' => 'right'),
                array('data' => $row['discount_percent'], 'align' => 'right'),
                array('data' => $row['commision_price'], 'align' => 'right'),
                array('data' => to_currency($row['total']), 'align' => 'right'),
                array('data' => to_currency($row['profit']), 'align' => 'right'),
//                array('data'=>$commissioner, 'align' => 'left'),
                array('data' => $row['comment'], 'align' => 'left'),
                // array('data'=>'Sale', 'align' => 'left'),
                array('data' => $employee, 'align' => 'left'),
                    // array('data'=>to_currency($row['profit']), 'align' => 'right'),
            );
        }

        $data = array(
            "title" => lang('reports_tickets_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel
        );
        $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        $office = substr($this->uri->segment(3), -1);
        $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed          

        $this->load->view('reports/tabular', $data);
    }

//    -------------------End Summary Massage report------------------
//    --------------------Graphic massages summary ------------------
    function graphical_massages_summary($office, $start_date, $end_date, $sale_type) {


        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model(array('reports/Summary_massages', 'Massage', 'Commissioner'));
        $model = $this->Summary_massages;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale_massage->create_sales_massages_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $data = array(
            "title" => lang('reports_items_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_massages_graph/$office/$start_date/$end_date/$sale_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );
//       var_dump($data);

        $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        $office = substr($this->uri->segment(3), -1);
        $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed          

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_massages_graph($office, $start_date, $end_date, $sale_type) {

        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_massages');
        $model = $this->Summary_massages;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $this->Sale_massage->create_sales_massages_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['name_of_massage']] = $row['total'];
        }

        $data = array(
            "title" => lang('reports_items_summary_report'),
            "data" => $graph_data
        );
//       var_dump($data);

        $this->load->view("reports/graphs/pie", $data);
    }

//    --------------------End Graphic massages summary ------------------
//    --------------------Start Graphic massages summary detail ------------------
    function detailed_sales_massage($office, $start_date, $end_date, $sale_type, $export_excel = 0) {
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->load->model('reports/Detailed_sales_sms');
        $model = $this->Detailed_sales_sms;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $testw = $this->Sale_massage->create_sales_massages_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();
        foreach ($report_data['summary'] as $key => $row) {
            $link = site_url('reports/specific_customer_for_massage/' . $office . '/' . $start_date . '/' . $end_date . '/' . $row['customer_id'] . '/all/0');
            $summary_data[] = array(
                array('data' => anchor('massages/edit/' . $office . '/' . $row['ID'], lang('common_edit'), array('class' => 'glyphicon glyphicon-edit', 'target' => '_blank')) . ' ' .
                    anchor('massages/receipt/' . $office . '/' . $row['ID'], lang('common_print_receipt_blank'), array('class' => 'glyphicon glyphicon-print', 'target' => '_blank')) . ' ' .
                    anchor('massages/edit/' . $office . '/' . $row['ID'], lang('common_edit_text') . ' ' . $row['ID'], array('target' => '_blank')), 'align' => 'left'),
                array('data' => date('d-m-Y', strtotime($row['issue_date'])), 'align' => 'left'),
                array('data' => to_quantity($row['items_purchased']), 'align' => 'center'),
                array('data' => $row['employee_name'], 'align' => 'left'),
                array('data' => '<a href="' . $link . '" target="_blank">' . $row['customer_name'] . '</a>', 'align' => 'left'),
                array('data' => $row['commissioner_name'], 'align' => 'left'),
                array('data' => '$' . $row['commision_price'], 'align' => 'center'),
                array('data' => to_currency($row['subtotal']), 'align' => 'right'),
                array('data' => to_currency($row['total']), 'align' => 'right'),
                array('data' => to_currency($row['profit']), 'align' => 'right'),
                array('data' => $row['payment_type'], 'align' => 'right'),
                array('data' => $row['comment'], 'align' => 'right'));

            foreach ($report_data['details'][$key] as $drow) {
                $details_data[$key][] = array(
                    array('data' => isset($drow['massage_name']) ? $drow['massage_name'] : "", 'align' => 'left'),
//                array('data' => $drow['description'], 'align' => 'left'), 
                    array('data' => date('d-m-Y', strtotime($row['issue_date'])), 'align' => 'left'),
                    array('data' => $row['time_in'], 'align' => 'left'),
                    array('data' => $row['time_out'], 'align' => 'left'),
                    array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'center'),
                    array('data' => to_currency($drow['subtotal']), 'align' => 'right'),
                    array('data' => to_currency($drow['total']), 'align' => 'right'),
                    array('data' => to_currency($drow['profit']), 'align' => 'right'),
                    array('data' => $drow['discount_percent'] . '%', 'align' => 'right'),
//                    array('data' => '$' . $row['commision_price'], 'align' => 'center'),
                );
            }
        }

        $data = array(
            "title" => lang('reports_detailed_sales_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel
        );
        $data['allowed_modules'] = $this->check_module_accessable();
        $this->load->view("reports/tabular_details", $data);
    }

    function specific_customer_for_massage($office, $start_date, $end_date, $customer_id, $sale_type, $export_excel = 0) {
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Specific_customer');
        $model = $this->Specific_customer;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'customer_id' => $customer_id, 'sale_type' => $sale_type));

        $this->Sale_massage->create_sales_massages_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'customer_id' => $customer_id, 'sale_type' => $sale_type));

        $headers = $model->getDataColumns();
        $report_data = $model->getDataSms();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {
            $summary_data[] = array(
                array('data' => anchor('massages/edit/' . $office . '/' . $row['ID'], lang('common_edit') . ' ' . $row['ID'], array('target' => '_blank')), 'align' => 'left'),
                array('data' => date('d-m-Y', strtotime($row['issue_date'])), 'align' => 'left'),
                array('data' => to_quantity($row['items_purchased']), 'align' => 'center'),
                array('data' => $row['employee_name'], 'align' => 'left'),
                array('data' => $row['commissioner_name'], 'align' => 'left'),
                array('data' => $row['commision_price'], 'align' => 'center'),
                array('data' => to_currency($row['subtotal']), 'align' => 'right'),
                array('data' => to_currency($row['total']), 'align' => 'right'),
                array('data' => to_currency($row['profit']), 'align' => 'right'),
                array('data' => $row['payment_type'], 'align' => 'left'),
                array('data' => $row['comment'], 'align' => 'left')
            );

            foreach ($report_data['details'][$key] as $drow) {
                $details_data[$key][] = array(
                    array('data' => isset($drow['massage_name']) ? $drow['massage_name'] : "", 'align' => 'left'),
//                  array('data' => $drow['category'], 'align' => 'left'),
//                  array('data' => $drow['description'], 'align' => 'left'), 
                    array('data' => $row['time_in'], 'align' => 'left'),
                    array('data' => $row['time_out'], 'align' => 'left'),
                    array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'center'),
                    array('data' => $row['commision_price'], 'align' => 'center'),
                    array('data' => to_currency($drow['subtotal']), 'align' => 'right'),
                    array('data' => to_currency($drow['total']), 'align' => 'right'),
                    array('data' => to_currency($drow['profit']), 'align' => 'right'),
                    array('data' => $drow['discount_percent'] . '%', 'align' => 'left')
                );
            }
        }

        $customer_info = $this->Customer->get_info($customer_id);
        $data = array(
            "title" => $customer_info->first_name . ' ' . $customer_info->last_name . ' ' . lang('reports_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumnsSms(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryDataSms(),
            "export_excel" => $export_excel
        );
        $data['allowed_modules'] = $this->check_module_accessable();

        $this->load->view("reports/tabular_details", $data);
    }

//    --------------------End Graphic massages summary detail ------------------
//    ------------------Search sale massage---------------
     // Sales Generator Reports 
   function sales_generator_massages($office, $controller) {
    $data['allowed_modules'] = $this->check_module_accessable();
       if ($this->input->get('act') == 'autocomplete') { // Must return a json string
           if ($this->input->get('w') != '') { // From where should we return data
               if ($this->input->get('term') != '') { // What exactly are we searchin
                   switch ($this->input->get('w')) {
                       case 'customers':
                           $t = $this->Customer->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v->person_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                           }
                           die(json_encode($tmp));
                           break;
                       case 'employees':
                           $t = $this->Employee->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v->person_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                           }
                           die(json_encode($tmp));
                           break;
//                       case 'itemsCategory':
//                           $t = $this->Item->get_category_suggestions($this->input->get('term'));
//                           $tmp = array();
//                           foreach ($t as $k => $v) {
//                               $tmp[$k] = array('id' => $v['label'], 'name' => $v['label']);
//                           }
//                           die(json_encode($tmp));
//                           break;
//                       case 'suppliers':
//                           $t = $this->Supplier->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
//                           $tmp = array();
//                           foreach ($t as $k => $v) {
//                               $tmp[$k] = array('id' => $v->person_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->company_name . " - " . $v->email);
//                           }
//                           die(json_encode($tmp));
//                           break;
//                       case 'itemsKitName':
//                           $t = $this->Item_kit->search($this->input->get('term'), 100, 0, 'name', 'asc')->result_object();
//                           $tmp = array();
//                           foreach ($t as $k => $v) {
//                               $tmp[$k] = array('id' => $v->item_kit_id, 'name' => $v->name . " / #" . $v->item_kit_number);
//                           }
//                           die(json_encode($tmp));
//                           break;
                       case 'itemsName':
                           $t = $this->Massage->search($this->input->get('term'), 100, 0, 'massage_name', 'asc')->result_object();
                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v->item_massage_id, 'name' => $v->massage_name);
                           }
                           die(json_encode($tmp));
                           break;
                       case 'paymentType':
                           $t = array(lang('sales_cash'), lang('sales_check'), lang('sales_giftcard'), lang('sales_debit'), lang('sales_credit'));

                           foreach ($this->Appconfig->get_additional_payment_types() as $additional_payment_type) {
                               $t[] = $additional_payment_type;
                           }

                           $tmp = array();
                           foreach ($t as $k => $v) {
                               $tmp[$k] = array('id' => $v, 'name' => $v);
                           }
                           die(json_encode($tmp));
                           break;
                   }
               } else {
                   die;
               }
           } else {
               die(json_encode(array('value' => 'No such data found!')));
           }
       }

       $postData = array();
       $data = $this->_get_common_report_data();
       $data["title"] = lang('reports_sales_generator');
       $data["subtitle"] = lang('reports_sales_report_generator');
       $setValues = array('report_type' => '', 'sreport_date_range_simple' => '',
           'start_month' => date("m"), 'start_day' => date('d'), 'start_year' => date("Y"),
           'end_month' => date("m"), 'end_day' => date('d'), 'end_year' => date("Y"),
           'matchType' => '',
           'matched_items_only' => FALSE
       );
       foreach ($setValues as $k => $v) {
           if (empty($v) && !isset($data[$k])) {
               $data[$k] = '';
           } else {
               $data[$k] = $v;
           }
       }
       if ($this->input->post('generate_report')) { // Generate Custom Raport
           $data['report_type'] = $this->input->post('report_type');
           $data['sreport_date_range_simple'] = $this->input->post('report_date_range_simple');
           $data['start_month'] = $this->input->post('start_month');
           $data['start_day'] = $this->input->post('start_day');
           $data['start_year'] = $this->input->post('start_year');
           $data['end_month'] = $this->input->post('end_month');
           $data['end_day'] = $this->input->post('end_day');
           $data['end_year'] = $this->input->post('end_year');
           if ($data['report_type'] == 'simple') {
               $q = explode("/", $data['sreport_date_range_simple']);
               list($data['start_year'], $data['start_month'], $data['start_day']) = explode("-", $q[0]);
               list($data['end_year'], $data['end_month'], $data['end_day']) = explode("-", $q[1]);
           }
           $data['matchType'] = $this->input->post('matchType');
           $data['matched_items_only'] = $this->input->post('matched_items_only') ? TRUE : FALSE;
           $data['field'] = $this->input->post('field');
           $data['condition'] = $this->input->post('condition');
           $data['value'] = $this->input->post('value');
           $data['prepopulate'] = array();
          
           $field = $this->input->post('field');
           $condition = $this->input->post('condition');
           $value = $this->input->post('value');
           $tmpData = array();
           foreach ($field as $a => $b) {
               $uData = explode(",", $value[$a]);
               $tmp = $tmpID = array();
               switch ($b) {
                   case '1': // Customer
                       $t = $this->Customer->get_multiple_info($uData)->result_object();
                       foreach ($t as $k => $v) {
                           $tmpID[] = $v->customer_id;
                           $tmp[$k] = array('id' => $v->customer_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                       }
                       break;
                   case '2': // Item Serial Number
                       $tmpID[] = $value[$a];
                       break;
                   case '3': // Employees
                       $t = $this->Employee->get_multiple_info($uData)->result_object();
                       foreach ($t as $k => $v) {
                           $tmpID[] = $v->employee_id;
                           $tmp[$k] = array('id' => $v->employee_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                       }
                       break;
                   case '4': // Items Category
                       foreach ($uData as $k => $v) {
                           $tmpID[] = $v;
                           $tmp[$k] = array('id' => $v, 'name' => $v);
                       }
                       break;
                   case '5': // Suppliers 
                       $t = $this->Supplier->get_multiple_info($uData)->result_object();
                       foreach ($t as $k => $v) {
                           $tmpID[] = $v->supplier_id;
                           $tmp[$k] = array('id' => $v->supplier_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->company_name . " - " . $v->email);
                       }
                       break;
                   case '6': // Sale Type
                       $tmpID[] = $condition[$a];
                       break;
                   case '7': // Sale Amount
                       $tmpID[] = $value[$a];
                       break;
                   // case '8': // Item Kits
                   //     $t = $this->Item_kit->get_multiple_info($uData)->result_object();
                   //     foreach ($t as $k => $v) {
                   //         $tmpID[] = $v->item_kit_id;
                   //         $tmp[$k] = array('id' => $v->item_kit_id, 'name' => $v->name . " / #" . $v->item_kit_number);
                   //     }
                   //     break;
                    case '9': // Items Name
                        $t = $this->Massage->get_multiple_info($uData)->result_object();
                        foreach ($t as $k => $v) {
                            $tmpID[] = $v->item_massage_id;
                            $tmp[$k] = array('id' => $v->item_massage_id, 'name' => $v->massage_name);
                        }
                        break;
                   case '10': // SaleID
                       if (strpos(strtoupper($value[$a]), 'CGATE') !== FALSE) {
                           $pieces = explode(' ', $value[$a]);
                           $value[$a] = (int) $pieces[1];
                       }
                       $tmpID[] = $value[$a];
                       break;
                   case '11': // Payment type
                       foreach ($uData as $k => $v) {
                           $tmpID[] = $v;
                           $tmp[$k] = array('id' => $v, 'name' => $v);
                       }
                       break;
               }
               $data['prepopulate']['field'][$a][$b] = $tmp;

               // Data for sql
               $tmpData[] = array('f' => $b, 'o' => $condition[$a], 'i' => $tmpID);
           }

           $params['matchType'] = $data['matchType'];
           $params['matched_items_only'] = $data['matched_items_only'];
           $params['ops'] = array(
               1 => " = 'xx'",
               2 => " != 'xx'",
               5 => " IN ('xx')",
               6 => " NOT IN ('xx')",
               7 => " > xx",
               8 => " < xx",
               9 => " = xx",
               10 => '', // Sales
               11 => '', // Returns
           );

           $params['tables'] = array(
               1 => 'sales_massages_temp.customer_id', // Customers
               3 => 'sales_massages_temp.employee_id', // Employees
               // 4 => 'sales_tickets_temp.category', // Item Category
               5 => 'sales_massages_temp.supplierID', // Suppliers
               6 => '', // Sale Type
               7 => '', // Sale Amount
//               8 => 'sales_tickets_temp.item_kit_id', // Item Kit Name
               9 => 'sales_massages_temp.item_massage_id', // Item Name
               10 => 'sales_massages_temp.ID', // Sale ID
               11 => 'sales_massages_temp.payment_type' // Payment Type
           );
           $params['values'] = $tmpData;
           $this->load->model('reports/Sales_generator');
           $model = $this->Sales_generator;
           $model->setParams($params);
           // Sales Interval Reports
           $interval = array(
               'start_date' => $data['start_year'] . '-' . $data['start_month'] . '-' . $data['start_day'],
               'end_date' => $data['end_year'] . '-' . $data['end_month'] . '-' . $data['end_day'] . ' 23:59:59',
           );
           $this->Sale_massage->create_sales_massages_temp_table($interval);
           $tabular_data = array();
           $report_data = $model->getDataSms();

           $summary_data = array();
           $details_data = array();

           foreach ($report_data['summary'] as $key => $row) {
               $summary_data[] = array(
                   array('data' => anchor('massages/edit/' . $office . '/' . $row['ID'], lang('common_edit') ,
                        array('target' => '_blank', 'class' => 'glyphicon glyphicon-edit',)) . ' ' . anchor('massages/receipt/' . $office . '/' . $row['ID'], ' ', 
                        array('class' => 'glyphicon glyphicon-print', 'target' => '_blank')) . ' ' . anchor('massages/edit/' . $office . '/'  . $row['ID'], lang('common_edit') . ' ' .$row['ID'],
                        array('target' => '_blank')), 'align' => 'left'),
                   array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left'),
                   array('data' => to_quantity($row['items_purchased']), 'align' => 'left'),
                   array('data' => $row['employee_name'], 'align' => 'left'),
                   array('data' => $row['customer_name'], 'align' => 'left'),
                   array('data' => to_currency($row['subtotal']), 'align' => 'right'),
                   array('data' => to_currency($row['total']), 'align' => 'right'),
                   // array('data' => to_currency($row['tax']), 'align' => 'right'),
                   array('data' => to_currency($row['profit']), 'align' => 'right'),
                   array('data' => $row['payment_type'], 'align' => 'right'),
                   array('data' => $row['comment'], 'align' => 'right'));
               foreach ($report_data['details'][$key] as $drow) {
                   $details_data[$key][] = array(array('data' => $drow['ID'],'align'=>'left'),
                       array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_name'], 'align' => 'left'),
//                     array('data' => $drow['category'], 'align' => 'left'),
//                     array('data' => $drow['description'], 'align' => 'left'),
                       array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'),
                       array('data' => $row['commision_price'], 'align' => 'center'),
                       array('data' => to_currency($drow['subtotal']), 'align' => 'left'),
                       array('data' => to_currency($drow['total']), 'align' => 'left'),
//                     array('data' => to_currency($drow['tax']), 'align' => 'right'), 
                       array('data' => to_currency($drow['profit']), 'align' => 'lfet'), 
                       array('data' => $drow['discount_percent'] . '%', 'align' => 'left'));
//                      array('data' => $row['commissioner_name'], 'align' => 'left'));
               }
           }

           $reportdata = array(
               "title" => lang('reports_sales_generator'),
               "subtitle" => lang('reports_sales_report_generator') . " - " . date(get_date_format(), strtotime($interval['start_date'])) . '-' . date(get_date_format(), strtotime($interval['end_date'])) . " - " . count($report_data['summary']) . ' ' . lang('reports_sales_report_generator_results_found'),
               "headers" => $model->getDataColumnsSms(),
               "summary_data" => $summary_data,
               "details_data" => $details_data,
               "overall_summary_data" => $model->getSummaryDataSms(),
           );

           // Fetch & Output Data 
           $data['results'] = $this->load->view("reports/sales_generator_tabular_details", $reportdata, true);
       }

       $data['controller_name'] = strtolower($controller);
       $this->load->view("reports/sales_generator", $data);
   }

    function summary_bikes($office, $start_date, $end_date, $sale_type, $export_excel = 0) {
        // $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        // $office = substr($this->uri->segment(3), -1);
        // $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed          
        $data['allowed_modules'] = $this->check_module_accessable();

        $this->load->view('reports/summary_report_bike', $data);
    }

    function summary_tours($office, $start_date, $end_date, $sale_type, $export_excel = 0) {
        // $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        // $office = substr($this->uri->segment(3), -1);
        // $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed          
        $data['allowed_modules'] = $this->check_module_accessable();

        $this->load->view('reports/summary_report_tour', $data);
    }

    //Summary item kits report
//    function summary_item_kits($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Summary_item_kits');
//        $model = $this->Summary_item_kits;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $tabular_data = array();
//        $report_data = $model->getData();
//
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['name'], 'align' => 'left'), array('data' => to_quantity($row['quantity_purchased']), 'align' => 'left'), array('data' => to_currency($row['subtotal']), 'align' => 'right'), array('data' => to_currency($row['total']), 'align' => 'right'), array('data' => to_currency($row['tax']), 'align' => 'right'), array('data' => to_currency($row['profit']), 'align' => 'right'));
//        }
//
//        $data = array(
//            "title" => lang('reports_item_kits_summary_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
    //Summary employees report
//    function summary_employees($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Summary_employees');
//        $model = $this->Summary_employees;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $tabular_data = array();
//        $report_data = $model->getData();
//
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['employee'], 'align' => 'left'), array('data' => to_currency($row['subtotal']), 'align' => 'right'), array('data' => to_currency($row['total']), 'align' => 'right'), array('data' => to_currency($row['tax']), 'align' => 'right'), array('data' => to_currency($row['profit']), 'align' => 'right'));
//        }
//
//        $data = array(
//            "title" => lang('reports_employees_summary_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
    //Summary taxes report
//    function summary_taxes($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Summary_taxes');
//        $model = $this->Summary_taxes;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $tabular_data = array();
//        $report_data = $model->getData();
//
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['name'], 'align' => 'left'), array('data' => to_currency($row['tax']), 'align' => 'right'));
//        }
//
//        $data = array(
//            "title" => lang('reports_taxes_summary_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
    //Summary discounts report
//    function summary_discounts($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Summary_discounts');
//        $model = $this->Summary_discounts;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $tabular_data = array();
//        $report_data = $model->getData();
//
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['discount_percent'], 'align' => 'left'), array('data' => $row['count'], 'align' => 'left'));
//        }
//
//        $data = array(
//            "title" => lang('reports_discounts_summary_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
//
//    function summary_payments($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Summary_payments');
//        $model = $this->Summary_payments;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $tabular_data = array();
//        $report_data = $model->getData();
//
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['payment_type'], 'align' => 'left'), array('data' => to_currency($row['payment_amount']), 'align' => 'right'));
//        }
//
//        $data = array(
//            "title" => lang('reports_payments_summary_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
    //Input for reports that require only a date range. (see routes.php to see that all graphical summary reports route here)
    function date_input() {
        $data = $this->_get_common_report_data();
        $this->load->view("reports/date_input", $data);
    }

    //Graphical summary sales report
//    function graphical_summary_sales($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_sales');
//        $model = $this->Summary_sales;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $data = array(
//            "title" => lang('reports_sales_summary_report'),
//            "graph_file" => site_url("reports/graphical_summary_sales_graph/$start_date/$end_date/$sale_type"),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "summary_data" => $model->getSummaryData()
//        );
//
//        $this->load->view("reports/graphical", $data);
//    }
//
//    //The actual graph data
//    function graphical_summary_sales_graph($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_sales');
//        $model = $this->Summary_sales;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//        $report_data = $model->getData();
//
//        $graph_data = array();
//        foreach ($report_data as $row) {
//            $graph_data[strtotime($row['sale_date'])] = $row['total'];
//        }
//
//        $data = array(
//            "title" => lang('reports_sales_summary_report'),
//            "data" => $graph_data
//        );
//
//        $this->load->view("reports/graphs/line", $data);
//    }
//
    //Graphical summary tickets report
    /* function graphical_summary_tickets($office, $start_date, $end_date, $sale_type) {
      $start_date = rawurldecode($start_date);
      $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

      $this->load->model(array('reports/Summary_tickets','Ticket','Commissioner'));
      $model = $this->Summary_tickets;
      $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

      $this->Sale_ticket->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

      $data = array(
      "title" => lang('reports_items_summary_report'),
      "graph_file" => site_url("reports/graphical_summary_items_graph/$start_date/$end_date/$sale_type"),
      "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
      "summary_data" => $model->getSummaryData()
      );

      $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
      $office = substr($this->uri->segment(3), -1);
      $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed

      $this->load->view("reports/graphical", $data);
      } */

//
//
//    //Graphical summary item kits report
//    function graphical_summary_item_kits($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_item_kits');
//        $model = $this->Summary_item_kits;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $data = array(
//            "title" => lang('reports_item_kits_summary_report'),
//            "graph_file" => site_url("reports/graphical_summary_item_kits_graph/$start_date/$end_date/$sale_type"),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "summary_data" => $model->getSummaryData()
//        );
//
//        $this->load->view("reports/graphical", $data);
//    }
//
//    //The actual graph data
//    function graphical_summary_item_kits_graph($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_item_kits');
//        $model = $this->Summary_item_kits;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//        $report_data = $model->getData();
//
//        $graph_data = array();
//        foreach ($report_data as $row) {
//            $graph_data[$row['name']] = $row['total'];
//        }
//
//        $data = array(
//            "title" => lang('reports_item_kits_summary_report'),
//            "data" => $graph_data
//        );
//
//        $this->load->view("reports/graphs/pie", $data);
//    }
//
//    //Graphical summary customers report
//    function graphical_summary_categories($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_categories');
//        $model = $this->Summary_categories;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $data = array(
//            "title" => lang('reports_categories_summary_report'),
//            "graph_file" => site_url("reports/graphical_summary_categories_graph/$start_date/$end_date/$sale_type"),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "summary_data" => $model->getSummaryData()
//        );
//
//        $this->load->view("reports/graphical", $data);
//    }
//
//    //The actual graph data
//    function graphical_summary_categories_graph($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_categories');
//        $model = $this->Summary_categories;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $report_data = $model->getData();
//
//        $graph_data = array();
//        foreach ($report_data as $row) {
//            $graph_data[$row['category']] = $row['total'];
//        }
//
//        $data = array(
//            "title" => lang('reports_categories_summary_report'),
//            "data" => $graph_data
//        );
//
//        $this->load->view("reports/graphs/pie", $data);
//    }
//
//    function graphical_summary_suppliers($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_suppliers');
//        $model = $this->Summary_suppliers;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $data = array(
//            "title" => lang('reports_suppliers_summary_report'),
//            "graph_file" => site_url("reports/graphical_summary_suppliers_graph/$start_date/$end_date/$sale_type"),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "summary_data" => $model->getSummaryData()
//        );
//
//        $this->load->view("reports/graphical", $data);
//    }
//
//    //The actual graph data
//    function graphical_summary_suppliers_graph($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_suppliers');
//        $model = $this->Summary_suppliers;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $report_data = $model->getData();
//
//        $graph_data = array();
//        foreach ($report_data as $row) {
//            $graph_data[$row['supplier']] = $row['total'];
//        }
//
//        $data = array(
//            "title" => lang('reports_suppliers_summary_report'),
//            "data" => $graph_data
//        );
//
//        $this->load->view("reports/graphs/pie", $data);
//    }
//
//    function graphical_summary_employees($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_employees');
//        $model = $this->Summary_employees;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $data = array(
//            "title" => lang('reports_employees_summary_report'),
//            "graph_file" => site_url("reports/graphical_summary_employees_graph/$start_date/$end_date/$sale_type"),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "summary_data" => $model->getSummaryData()
//        );
//
//        $this->load->view("reports/graphical", $data);
//    }
//
//    //The actual graph data
//    function graphical_summary_employees_graph($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_employees');
//        $model = $this->Summary_employees;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//        $report_data = $model->getData();
//
//        $graph_data = array();
//        foreach ($report_data as $row) {
//            $graph_data[$row['employee']] = $row['total'];
//        }
//
//        $data = array(
//            "title" => lang('reports_employees_summary_report'),
//            "data" => $graph_data
//        );
//
//        $this->load->view("reports/graphs/bar", $data);
//    }
//
//    function graphical_summary_taxes($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_taxes');
//        $model = $this->Summary_taxes;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $data = array(
//            "title" => lang('reports_taxes_summary_report'),
//            "graph_file" => site_url("reports/graphical_summary_taxes_graph/$start_date/$end_date/$sale_type"),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "summary_data" => $model->getSummaryData()
//        );
//
//        $this->load->view("reports/graphical", $data);
//    }
//
//    //The actual graph data
//    function graphical_summary_taxes_graph($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_taxes');
//        $model = $this->Summary_taxes;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//        $report_data = $model->getData();
//
//        $graph_data = array();
//        foreach ($report_data as $row) {
//            $graph_data[$row['name']] = $row['tax'];
//        }
//
//        $data = array(
//            "title" => lang('reports_taxes_summary_report'),
//            "data" => $graph_data
//        );
//
//        $this->load->view("reports/graphs/bar", $data);
//    }
//
//    //Graphical summary customers report
//    function graphical_summary_customers($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_customers');
//        $model = $this->Summary_customers;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $data = array(
//            "title" => lang('reports_customers_summary_report'),
//            "graph_file" => site_url("reports/graphical_summary_customers_graph/$start_date/$end_date/$sale_type"),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "summary_data" => $model->getSummaryData()
//        );
//
//        $this->load->view("reports/graphical", $data);
//    }
//
//    //The actual graph data
//    function graphical_summary_customers_graph($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//        $this->load->model('reports/Summary_customers');
//        $model = $this->Summary_customers;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $report_data = $model->getData();
//
//        $graph_data = array();
//        foreach ($report_data as $row) {
//            $graph_data[$row['customer']] = $row['total'];
//        }
//
//        $data = array(
//            "title" => lang('reports_customers_summary_report'),
//            "data" => $graph_data
//        );
//
//        $this->load->view("reports/graphs/pie", $data);
//    }
//
//    //Graphical summary discounts report
//    function graphical_summary_discounts($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_discounts');
//        $model = $this->Summary_discounts;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $data = array(
//            "title" => lang('reports_discounts_summary_report'),
//            "graph_file" => site_url("reports/graphical_summary_discounts_graph/$start_date/$end_date/$sale_type"),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "summary_data" => $model->getSummaryData()
//        );
//
//        $this->load->view("reports/graphical", $data);
//    }
//
//    //The actual graph data
//    function graphical_summary_discounts_graph($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_discounts');
//        $model = $this->Summary_discounts;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//        $report_data = $model->getData();
//
//        $graph_data = array();
//        foreach ($report_data as $row) {
//            $graph_data[$row['discount_percent']] = $row['count'];
//        }
//
//        $data = array(
//            "title" => lang('reports_discounts_summary_report'),
//            "data" => $graph_data
//        );
//
//        $this->load->view("reports/graphs/bar", $data);
//    }
//
//    function graphical_summary_payments($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_payments');
//        $model = $this->Summary_payments;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $data = array(
//            "title" => lang('reports_payments_summary_report'),
//            "graph_file" => site_url("reports/graphical_summary_payments_graph/$start_date/$end_date/$sale_type"),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "summary_data" => $model->getSummaryData()
//        );
//
//        $this->load->view("reports/graphical", $data);
//    }
//
//    //The actual graph data
//    function graphical_summary_payments_graph($start_date, $end_date, $sale_type) {
//        $start_date = rawurldecode($start_date);
//        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
//
//        $this->load->model('reports/Summary_payments');
//        $model = $this->Summary_payments;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//        $report_data = $model->getData();
//
//        $graph_data = array();
//        foreach ($report_data as $row) {
//            $graph_data[$row['payment_type']] = $row['payment_amount'];
//        }
//
//        $data = array(
//            "title" => lang('reports_payments_summary_report'),
//            "data" => $graph_data
//        );
//
//        $this->load->view("reports/graphs/bar", $data);
//    }                      
//
//    function specific_employee_input() {
//        $data = $this->_get_common_report_data(TRUE);
//        $data['specific_input_name'] = lang('reports_employee');
//
//        $employees = array();
//        foreach ($this->Employee->get_all()->result() as $employee) {
//            $employees[$employee->person_id] = $employee->first_name . ' ' . $employee->last_name;
//        }
//        $data['specific_input_data'] = $employees;
//        $this->load->view("reports/specific_input", $data);
//    }
//
//    function specific_employee($start_date, $end_date, $employee_id, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Specific_employee');
//        $model = $this->Specific_employee;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'employee_id' => $employee_id, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'employee_id' => $employee_id, 'sale_type' => $sale_type));
//        $headers = $model->getDataColumns();
//        $report_data = $model->getData();
//
//        $summary_data = array();
//        $details_data = array();
//
//        foreach ($report_data['summary'] as $key => $row) {
//            $summary_data[] = array(array('data' => anchor('sales/edit/' . $row['sale_id'], lang('common_edit') . ' ' . $row['sale_id'], array('target' => '_blank')), 'align' => 'left'), array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left'), array('data' => to_quantity($row['items_purchased']), 'align' => 'left'), array('data' => $row['customer_name'], 'align' => 'left'), array('data' => to_currency($row['subtotal']), 'align' => 'right'), array('data' => to_currency($row['total']), 'align' => 'right'), array('data' => to_currency($row['tax']), 'align' => 'right'), array('data' => to_currency($row['profit']), 'align' => 'right'), array('data' => $row['payment_type'], 'align' => 'left'), array('data' => $row['comment'], 'align' => 'left'));
//
//            foreach ($report_data['details'][$key] as $drow) {
//                $details_data[$key][] = array(array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left'), array('data' => $drow['category'], 'align' => 'left'), array('data' => $drow['serialnumber'], 'align' => 'left'), array('data' => $drow['description'], 'align' => 'left'), array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'), array('data' => to_currency($drow['subtotal']), 'align' => 'right'), array('data' => to_currency($drow['total']), 'align' => 'right'), array('data' => to_currency($drow['tax']), 'align' => 'right'), array('data' => to_currency($drow['profit']), 'align' => 'right'), array('data' => $drow['discount_percent'] . '%', 'align' => 'left'));
//            }
//        }
//
//        $employee_info = $this->Employee->get_info($employee_id);
//        $data = array(
//            "title" => $employee_info->first_name . ' ' . $employee_info->last_name . ' ' . lang('reports_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "summary_data" => $summary_data,
//            "details_data" => $details_data,
//            "overall_summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular_details", $data);
//    }
//
//
//    function specific_supplier_input() {
//        $data = $this->_get_common_report_data(TRUE);
//        $data['specific_input_name'] = lang('reports_supplier');
//
//        $suppliers = array();
//        foreach ($this->Supplier->get_all()->result() as $supplier) {
//            $suppliers[$supplier->person_id] = $supplier->first_name . ' ' . $supplier->last_name;
//        }
//        $data['specific_input_data'] = $suppliers;
//        $this->load->view("reports/specific_input", $data);
//    }
//
//    function specific_supplier($start_date, $end_date, $supplier_id, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Specific_supplier');
//        $model = $this->Specific_supplier;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'supplier_id' => $supplier_id, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'supplier_id' => $supplier_id, 'sale_type' => $sale_type));
//        $headers = $model->getDataColumns();
//        $report_data = $model->getData();
//
//        $summary_data = array();
//        $details_data = array();
//
//        foreach ($report_data['summary'] as $key => $row) {
//            $summary_data[] = array(array('data' => anchor('sales/edit/' . $row['sale_id'], lang('common_edit') . ' ' . $row['sale_id'], array('target' => '_blank')), 'align' => 'left'), array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left'), array('data' => to_quantity($row['items_purchased']), 'align' => 'left'), array('data' => $row['customer_name'], 'align' => 'left'), array('data' => to_currency($row['subtotal']), 'align' => 'right'), array('data' => to_currency($row['total']), 'align' => 'right'), array('data' => to_currency($row['tax']), 'align' => 'right'), array('data' => to_currency($row['profit']), 'align' => 'right'), array('data' => $row['payment_type'], 'align' => 'left'), array('data' => $row['comment'], 'align' => 'left'));
//
//            foreach ($report_data['details'][$key] as $drow) {
//                $details_data[$key][] = array(array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left'), array('data' => $drow['category'], 'align' => 'left'), array('data' => $drow['serialnumber'], 'align' => 'left'), array('data' => $drow['description'], 'align' => 'left'), array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'), array('data' => to_currency($drow['subtotal']), 'align' => 'right'), array('data' => to_currency($drow['total']), 'align' => 'right'), array('data' => to_currency($drow['tax']), 'align' => 'right'), array('data' => to_currency($drow['profit']), 'align' => 'right'), array('data' => $drow['discount_percent'] . '%', 'align' => 'left'));
//            }
//        }
//
//        $supplier_info = $this->Supplier->get_info($supplier_id);
//        $data = array(
//            "title" => $supplier_info->first_name . ' ' . $supplier_info->last_name . ' ' . lang('reports_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "summary_data" => $summary_data,
//            "details_data" => $details_data,
//            "overall_summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular_details", $data);
//    }
//
//    function deleted_sales($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Deleted_sales');
//        $model = $this->Deleted_sales;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $headers = $model->getDataColumns();
//        $report_data = $model->getData();
//
//        $summary_data = array();
//        $details_data = array();
//
//        foreach ($report_data['summary'] as $key => $row) {
//            $summary_data[] = array(array('data' => anchor('sales/edit/' . $row['sale_id'], lang('common_edit') . ' ' . $row['sale_id'], array('target' => '_blank')), 'align' => 'left'), array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left'), array('data' => to_quantity($row['items_purchased']), 'align' => 'left'), array('data' => $row['employee_name'], 'align' => 'left'), array('data' => $row['customer_name'], 'align' => 'left'), array('data' => to_currency($row['subtotal']), 'align' => 'right'), array('data' => to_currency($row['total']), 'align' => 'right'), array('data' => to_currency($row['tax']), 'align' => 'right'), array('data' => to_currency($row['profit']), 'align' => 'right'), array('data' => $row['payment_type'], 'align' => 'left'), array('data' => $row['comment'], 'align' => 'left'));
//
//            foreach ($report_data['details'][$key] as $drow) {
//                $details_data[$key][] = array(array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left'), array('data' => $drow['category'], 'align' => 'left'), array('data' => $drow['serialnumber'], 'align' => 'left'), array('data' => $drow['description'], 'align' => 'left'), array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'), array('data' => to_currency($drow['subtotal']), 'align' => 'right'), array('data' => to_currency($drow['total']), 'align' => 'right'), array('data' => to_currency($drow['tax']), 'align' => 'right'), array('data' => to_currency($drow['profit']), 'align' => 'right'), array('data' => $drow['discount_percent'] . '%', 'align' => 'left'));
//            }
//        }
//
//        $data = array(
//            "title" => lang('reports_deleted_sales_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "summary_data" => $summary_data,
//            "details_data" => $details_data,
//            "overall_summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular_details", $data);
//    }
//
//    function detailed_receivings($start_date, $end_date, $sale_type, $export_excel = 0) {
//        $start_date = rawurldecode($start_date);
//        $end_date = rawurldecode($end_date);
//
//        $this->load->model('reports/Detailed_receivings');
//        $model = $this->Detailed_receivings;
//        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $this->Receiving->create_receivings_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
//
//        $headers = $model->getDataColumns();
//        $report_data = $model->getData();
//
//        $summary_data = array();
//        $details_data = array();
//
//        foreach ($report_data['summary'] as $key => $row) {
//            $summary_data[] = array(array('data' => anchor('receivings/edit/' . $row['receiving_id'], 'RECV ' . $row['receiving_id'], array('target' => '_blank')), 'align' => 'left'), array('data' => date(get_date_format(), strtotime($row['receiving_date'])), 'align' => 'left'), array('data' => to_quantity($row['items_purchased']), 'align' => 'left'), array('data' => $row['employee_name'], 'align' => 'left'), array('data' => $row['supplier_name'], 'align' => 'left'), array('data' => to_currency($row['total']), 'align' => 'right'), array('data' => $row['payment_type'], 'align' => 'left'), array('data' => $row['comment'], 'align' => 'left'));
//
//            foreach ($report_data['details'][$key] as $drow) {
//                $details_data[$key][] = array(array('data' => $drow['name'], 'align' => 'left'), array('data' => $drow['category'], 'align' => 'left'), array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'), array('data' => to_currency($drow['total']), 'align' => 'right'), array('data' => $drow['discount_percent'] . '%', 'align' => 'left'));
//            }
//        }
//
//        $data = array(
//            "title" => lang('reports_detailed_receivings_report'),
//            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
//            "headers" => $model->getDataColumns(),
//            "summary_data" => $summary_data,
//            "details_data" => $details_data,
//            "overall_summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular_details", $data);
//    }
//
//    function excel_export() {
//        $this->load->view("reports/excel_export", array());
//    }
//
//    function inventory_low($export_excel = 0) {
//        $this->load->model('reports/Inventory_low');
//        $model = $this->Inventory_low;
//        $model->setParams(array());
//        $tabular_data = array();
//        $report_data = $model->getData(array());
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['name'], 'align' => 'left'), array('data' => $row['company_name'], 'align' => 'left'), array('data' => $row['item_number'], 'align' => 'left'), array('data' => $row['description'], 'align' => 'left'), array('data' => to_currency($row['cost_price']), 'align' => 'right'), array('data' => to_currency($row['unit_price']), 'align' => 'right'), array('data' => to_quantity($row['quantity']), 'align' => 'left'), array('data' => $row['reorder_level'], 'align' => 'left'));
//        }
//
//        $data = array(
//            "title" => lang('reports_low_inventory_report'),
//            "subtitle" => '',
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(array()),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
//
//    function inventory_summary($export_excel = 0) {
//        $this->load->model('reports/Inventory_summary');
//        $model = $this->Inventory_summary;
//        $tabular_data = array();
//        $report_data = $model->getData(array());
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['name'], 'align' => 'left'), array('data' => $row['company_name'], 'align' => 'left'), array('data' => $row['item_number'], 'align' => 'left'), array('data' => $row['description'], 'align' => 'left'), array('data' => to_currency($row['cost_price']), 'align' => 'right'), array('data' => to_currency($row['unit_price']), 'align' => 'right'), array('data' => to_quantity($row['quantity']), 'align' => 'left'), array('data' => $row['reorder_level'], 'align' => 'left'));
//        }
//
//        $data = array(
//            "title" => lang('reports_inventory_summary_report'),
//            "subtitle" => '',
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(array()),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
//
//    function summary_giftcards($export_excel = 0) {
//        $this->load->model('reports/Summary_giftcards');
//        $model = $this->Summary_giftcards;
//        $tabular_data = array();
//        $report_data = $model->getData(array());
//        foreach ($report_data as $row) {
//            $tabular_data[] = array(array('data' => $row['giftcard_number'], 'align' => 'left'), array('data' => to_currency($row['value']), 'align' => 'left'), array('data' => $row['customer_name'], 'align' => 'left'));
//        }
//
//        $data = array(
//            "title" => lang('reports_giftcard_summary_report'),
//            "subtitle" => '',
//            "headers" => $model->getDataColumns(),
//            "data" => $tabular_data,
//            "summary_data" => $model->getSummaryData(array()),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular", $data);
//    }
//
//    function detailed_giftcards_input() {
//        $data['specific_input_name'] = lang('reports_customer');
//
//        $customers = array();
//        foreach ($this->Customer->get_all()->result() as $customer) {
//            $customers[$customer->person_id] = $customer->first_name . ' ' . $customer->last_name;
//        }
//        $data['specific_input_data'] = $customers;
//        $this->load->view("reports/detailed_giftcards_input", $data);
//    }
//
//    function detailed_giftcards($customer_id, $export_excel = 0) {
//        $this->load->model('reports/Detailed_giftcards');
//        $model = $this->Detailed_giftcards;
//        $model->setParams(array('customer_id' => $customer_id));
//
//        $this->Sale->create_sales_items_temp_table(array('customer_id' => $customer_id));
//
//        $headers = $model->getDataColumns();
//        $report_data = $model->getData();
//
//        $summary_data = array();
//        $details_data = array();
//
//        foreach ($report_data['summary'] as $key => $row) {
//            $summary_data[] = array(array('data' => anchor('sales/edit/' . $row['sale_id'], lang('common_edit') . ' ' . $row['sale_id'], array('target' => '_blank')), 'align' => 'left'), array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left'), array('data' => to_quantity($row['items_purchased']), 'align' => 'left'), array('data' => $row['employee_name'], 'align' => 'left'), array('data' => to_currency($row['subtotal']), 'align' => 'right'), array('data' => to_currency($row['total']), 'align' => 'right'), array('data' => to_currency($row['tax']), 'align' => 'right'), array('data' => to_currency($row['profit']), 'align' => 'right'), array('data' => $row['payment_type'], 'align' => 'left'), array('data' => $row['comment'], 'align' => 'left'));
//
//            foreach ($report_data['details'][$key] as $drow) {
//                $details_data[$key][] = array(array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left'), array('data' => $drow['category'], 'align' => 'left'), array('data' => $drow['serialnumber'], 'align' => 'left'), array('data' => $drow['description'], 'align' => 'left'), array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'), array('data' => to_currency($drow['subtotal']), 'align' => 'right'), array('data' => to_currency($drow['total']), 'align' => 'right'), array('data' => to_currency($drow['tax']), 'align' => 'right'), array('data' => to_currency($drow['profit']), 'align' => 'right'), array('data' => $drow['discount_percent'] . '%', 'align' => 'left'));
//            }
//        }
//
//        $customer_info = $this->Customer->get_info($customer_id);
//        $data = array(
//            "title" => $customer_info->first_name . ' ' . $customer_info->last_name . ' ' . lang('giftcards_giftcard') . ' ' . lang('reports_report'),
//            "subtitle" => '',
//            "headers" => $model->getDataColumns(),
//            "summary_data" => $summary_data,
//            "details_data" => $details_data,
//            "overall_summary_data" => $model->getSummaryData(),
//            "export_excel" => $export_excel
//        );
//
//        $this->load->view("reports/tabular_details", $data);
//    }
}

?>