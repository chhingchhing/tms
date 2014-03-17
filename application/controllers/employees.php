<?php

require_once ("person_controller.php");

class Employees extends Person_controller {

    function __construct() {
        parent::__construct('employees');
    }

    function index() {
        $this->employees();
    }

    function employees() {
        $logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
        $office = substr($this->uri->segment(3), -1);
        $data['allowed_modules'] = $this->Module->get_allowed_modules($office, $logged_in_employee_info->person_id); //get officle allowed

        $data['person_info'] = $this->Employee->get_info($employee_id);
        $data['all_modules'] = $this->Module->get_all_modules();
        $data['all_offices'] = $this->Module->get_all_offices();
        $data['positions'] = array();
        foreach ($this->Module->get_all_positions()->result() as $position)
        {
            $data['positions'][$position->position_id] = $position->position_name;
        }

        $this->check_action_permission('search');
        $config['base_url'] = site_url('employees/sorting');
        $config['total_rows'] = $this->Employee->count_all();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['controller_name'] = strtolower(get_class());
        $data['form_width'] = $this->get_form_width();
        $data['per_page'] = $config['per_page'];
        // $data['positions'] = $this->Employee->get_position();
        $data['manage_table'] = get_people_manage_table($this->Employee->get_all($data['per_page']), $this);
        $this->load->view('people/manage', $data);
    }

    function sorting() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        if ($search) {
            $config['total_rows'] = $this->Employee->search_count_all($search);
            $table_data = $this->Employee->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        } else {
            $config['total_rows'] = $this->Employee->count_all();
            $table_data = $this->Employee->get_all($per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        }
        $config['base_url'] = site_url('employees/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_people_manage_table_data_rows($table_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    function check_duplicate() {
        $term = $this->input->post("first_name")." ".$this->input->post("last_name");
        echo json_encode(array('duplicate' => $this->Employee->check_duplicate($term)));
    }

    /* added for excel expert */

    function excel_export() {
        $data = $this->Employee->get_all()->result_object();
        $this->load->helper('report');
        $rows = array();
        $row = array(lang('employees_username'), lang('common_first_name'), lang('common_last_name'), lang('common_email'), lang('common_phone_number'), lang('common_address_1'), lang('common_address_2'), lang('common_city'), lang('common_state'), lang('common_zip'), lang('common_country'), lang('common_comments'));
        $rows[] = $row;
        foreach ($data as $r) {
            $row = array(
                $r->username,
                $r->first_name,
                $r->last_name,
                $r->email,
                $r->phone_number,
                $r->address_1,
                $r->address_2,
                $r->city,
                $r->state,
                $r->zip,
                $r->country,
                $r->comments
            );
            $rows[] = $row;
        }

        $content = array_to_csv($rows);
        force_download('employees_export' . '.csv', $content);
        exit;
    }

    /*
      Returns employee table data rows. This will be called with AJAX.
     */

    function search() {
        $this->check_action_permission('search');
        $search = $this->input->post('search');
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->Employee->search($search, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'last_name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        $config['base_url'] = site_url('employees/search');
        $config['total_rows'] = $this->Employee->search_count_all($search);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_people_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    /*
      Gives search suggestions based on what is being searched for
     */

    function suggest() {
        $suggestions = $this->Employee->get_search_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }

    /*
      Loads the employee edit form
     */

    function view($employee_id = -1) {
        $this->check_action_permission('add_update');
        $data['person_info'] = $this->Employee->get_info($employee_id);
        $data['all_modules'] = $this->Module->get_all_modules();
        $data['all_offices'] = $this->Module->get_all_offices();
        $data['positions'] = array();
        foreach ($this->Module->get_all_positions()->result() as $position)
        {
            $data['positions'][$position->position_id] = $position->position_name;
        }
        $this->load->view("employees/_form", $data);
    }

    function employee_exists() {
        if ($this->Employee->employee_username_exists($this->input->post('username')))
            echo 'false';
        else
            echo 'true';
    }

    /*
      Inserts/updates an employee
     */
    function save($employee_id=-1)
    {
        $this->check_action_permission('add_update');
        $person_data = array(
            'first_name'=>$this->input->post('first_name'),
            'last_name'=>$this->input->post('last_name'),
            'email'=>$this->input->post('email'),
            'phone_number'=>$this->input->post('phone_number'),
            'address_1'=>$this->input->post('address_1'),
            'address_2'=>$this->input->post('address_2'),
            'city'=>$this->input->post('city'),
            'state'=>$this->input->post('state'),
            'zip'=>$this->input->post('zip'),
            'country'=>$this->input->post('country'),
            'comments'=>$this->input->post('comments')
        );
        $office_data = $this->input->post("offices")!=false ? $this->input->post("offices"):array();
        $permission_data = $this->input->post("permissions")!=false ? $this->input->post("permissions"):array();
        $permission_action_data = $this->input->post("permissions_actions")!=false ? $this->input->post("permissions_actions"):array();
        //Password has been changed OR first time password set
        if($this->input->post('password')!='')
        {
            $employee_data=array(
            'username'=>$this->input->post('username'),
            'password'=>md5($this->input->post('password')),
            'position_id' => $this->input->post("position")
            );
        }
        else //Password not changed
        {
            $employee_data=array(
                'username'=>$this->input->post('username'),
                'position_id' => $this->input->post("position")
                );
        }

        if ( ($_SERVER['HTTP_HOST'] == 'demo.phppointofsale.com' || $_SERVER['HTTP_HOST'] == 'demo.phppointofsalestaging.com') && $employee_id == 1)
        {
            //failure
            echo json_encode(array('success'=>false,'message'=>lang('employees_error_updating_demo_admin').' '.
            $person_data['first_name'].' '.$person_data['last_name'],'person_id'=>-1));
        }
        elseif($this->Employee->save($person_data,$employee_data,$office_data,$permission_data, $permission_action_data, $employee_id))
        {
            if ($this->config->item('mailchimp_api_key'))
            {
                $this->Person->update_mailchimp_subscriptions($this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('mailing_lists'));
            }
            
            //New employee
            if($employee_id==-1)
            {
                echo json_encode(array('success'=>true,'message'=>lang('employees_successful_adding').' '.
                $person_data['first_name'].' '.$person_data['last_name'],'person_id'=>$employee_data['person_id']));
            }
            else //previous employee
            {
                echo json_encode(array('success'=>true,'message'=>lang('employees_successful_updating').' '.
                $person_data['first_name'].' '.$person_data['last_name'],'person_id'=>$employee_id));
            }
        }
        else//failure
        {   
            echo json_encode(array('success'=>false,'message'=>lang('employees_error_adding_updating').' '.
            $person_data['first_name'].' '.$person_data['last_name'],'person_id'=>-1));
        }
    }

//     function save($employee_id = -1) {
//         $this->check_action_permission('add_update');
//         $person_data = array(
//             'first_name' => $this->input->post('first_name'),
//             'last_name' => $this->input->post('last_name'),
//             'email' => $this->input->post('email'),
//             'phone_number' => $this->input->post('phone_number'),
//             'address_1' => $this->input->post('address_1'),
//             'address_2' => $this->input->post('address_2'),
//             'city' => $this->input->post('city'),
//             'state' => $this->input->post('state'),
//             'zip' => $this->input->post('zip'),
//             'country' => $this->input->post('country'),
//             'comments' => $this->input->post('comments'),
//         );
//         // I would like to test insert data to poeple 
// //        $people = $this->db->insert('people',$person_data);
// //        if($people){
// //            $this->
// //        }


//         $permission_data = $this->input->post("permissions") != false ? $this->input->post("permissions") : array();
// //        $permission_data = $this->input->post("permissions");
//         echo "permission ";
//         var_dump($permission_data);
//         $resultTest = $this->Employee->testPermission($permission_data);
//         echo "resultTest";
//         var_dump($resultTest);
//        // var_dump($permission_data);

//         //return $this->Employee->save($permission_data);
//         $permission_action_data = $this->input->post("permissions_actions") != false ? $this->input->post("permissions_actions") : array();

//         // var_dump($permission_action_data);
//         //return $this->Employee->save($permission_action_data);
//         //Password has been changed OR first time password set
//         if ($this->input->post('password') != '') {
//             $employee_data = array(
//                 'username' => $this->input->post('username'),
//                 'password' => md5($this->input->post('password')),
//                 'position_id' => $this->input->post('position')
//             );
// //           //return $this->Employee->save($employee_data);
//         } else { //Password not changedi
//             $employee_data = array('username' => $this->input->post('username'));
//         }
//         echo 'employee_data';
//         var_dump($employee_data);

//         // $this->Employee->save($person_data, $permission_data, $permission_action_data);

//         if (($_SERVER['HTTP_HOST'] == 'demo.phppointofsale.com' || $_SERVER['HTTP_HOST'] == 'demo.phppointofsalestaging.com') && $employee_id == 1) {
//             //failure
//             echo json_encode(array('success' => false, 'message' => lang('employees_error_updating_demo_admin') . ' ' .
//                 $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => -1));
// //        } elseif ($this->Employee->save($person_data, $employee_data, $permission_data, $permission_action_data, $employee_id)) {
//         } elseif ($this->Employee->saved($person_data, $employee_data, $permission_data, $permission_action_data, $employee_id)) {
//             if ($this->config->item('mailchimp_api_key')) {
//                 $this->Person->update_mailchimp_subscriptions($this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('mailing_lists'));
//             }

//             //New employee
//             if ($employee_id == -1) {
//                 echo json_encode(array('success' => true, 'message' => lang('employees_successful_adding') . ' ' .
//                     $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => $employee_data['person_id']));
//             } else { //previous employee
//                 echo $employee_id;
//                 echo json_encode(array('success' => true, 'message' => lang('employees_successful_updating') . ' ' .
//                     $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => $employee_id));
//             }
//         } else {//failure
//             echo json_encode(array('success' => false, 'message' => lang('employees_error_adding_updating') . ' ' .
//                 $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => -1));
//         }
//     }
    
//     function saved($employee_id = -1) {
//         $this->check_action_permission('add_update');
//         $person_data = array(
//             'first_name' => $this->input->post('first_name'),
//             'last_name' => $this->input->post('last_name'),
//             'email' => $this->input->post('email'),
//             'phone_number' => $this->input->post('phone_number'),
//             'address_1' => $this->input->post('address_1'),
//             'address_2' => $this->input->post('address_2'),
//             'city' => $this->input->post('city'),
//             'state' => $this->input->post('state'),
//             'zip' => $this->input->post('zip'),
//             'country' => $this->input->post('country'),
//             'comments' => $this->input->post('comments'),
//         );
//         // I would like to test insert data to poeple 
// //        $people = $this->db->insert('people',$person_data);
// //        if($people){
// //            $this->
// //        }


//         $permission_data = $this->input->post("permissions") != false ? $this->input->post("permissions") : array();
// //        $permission_data = $this->input->post("permissions");
//         echo "permission ";
//         var_dump($permission_data);
//         $resultTest = $this->Employee->testPermission($permission_data);
//         echo "resultTest";
//         var_dump($resultTest);
        
//         //return $this->Employee->save($permission_data);
//         $permission_action_data = $this->input->post("permissions_actions") != false ? $this->input->post("permissions_actions") : array();

//        // var_dump($permission_action_data);
//         //return $this->Employee->save($permission_action_data);
//         //Password has been changed OR first time password set
//         if ($this->input->post('password') != '') {
//             $employee_data = array(
//                 'username' => $this->input->post('username'),
//                 'password' => md5($this->input->post('password')),
//                 'position_id' => $this->input->post('position')
//             );
// //           //return $this->Employee->save($employee_data);
//         } else { //Password not changedi
//             $employee_data = array('username' => $this->input->post('username'));
//         }
//         echo 'employee_data';
//         var_dump($employee_data);
        
//         // $this->Employee->save($person_data, $permission_data, $permission_action_data);
         
//         if (($_SERVER['HTTP_HOST'] == 'demo.phppointofsale.com' || $_SERVER['HTTP_HOST'] == 'demo.phppointofsalestaging.com') && $employee_id == 1) {
//             //failure
//             echo json_encode(array('success' => false, 'message' => lang('employees_error_updating_demo_admin') . ' ' .
//                 $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => -1));
// //        } elseif ($this->Employee->save($person_data, $employee_data, $permission_data, $permission_action_data, $employee_id)) {
//         } elseif ($this->Employee->saved($person_data, $employee_data, $permission_data, $permission_action_data, $employee_id)) {
//             if ($this->config->item('mailchimp_api_key')) {
//                 $this->Person->update_mailchimp_subscriptions($this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('mailing_lists'));
//             }

//             //New employee
//             if ($employee_id == -1) {
//                 echo json_encode(array('success' => true, 'message' => lang('employees_successful_adding') . ' ' .
//                     $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => $employee_data['person_id']));
                
//             } else { //previous employee
//                 echo $employee_id;
//                 echo json_encode(array('success' => true, 'message' => lang('employees_successful_updating') . ' ' .
//                     $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => $employee_id));
//             }
//         } else {//failure
//             echo json_encode(array('success' => false, 'message' => lang('employees_error_adding_updating') . ' ' .
//                 $person_data['first_name'] . ' ' . $person_data['last_name'], 'person_id' => -1));
//         }
//     }

    /*
      This deletes employees from the employees table
     */

    function delete() {
        $this->check_action_permission('delete');
        $employees_to_delete = $this->input->post('checkedID');

        if (($_SERVER['HTTP_HOST'] == 'demo.phppointofsale.com' || $_SERVER['HTTP_HOST'] == 'demo.phppointofsalestaging.com') && in_array(1, $employees_to_delete)) {
            //failure
            echo json_encode(array('success' => false, 'message' => lang('employees_error_deleting_demo_admin')));
        } elseif ($this->Employee->delete_list($employees_to_delete)) {
            echo json_encode(array('success' => true, 'message' => lang('employees_successful_deleted') . ' ' .
                count($employees_to_delete) . ' ' . lang('employees_one_or_multiple')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('employees_cannot_be_deleted')));
        }
    }

    function cleanup() {
        $this->Employee->cleanup();
        echo json_encode(array('success' => true, 'message' => lang('employees_cleanup_sucessful')));
    }

    /*
      get the width for the add/edit form
     */

    function get_form_width() {
        return 880;
    }

}
?>