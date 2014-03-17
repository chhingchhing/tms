<?php
class Login extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('encrypt');
	}
	
	function index()
	{
		$data = array();
		
		if(APPLICATION_VERSION==$this->config->item('version'))
		{
			$data['application_mismatch']=false;
		}
		else
		{
			$data['application_mismatch']=lang('login_application_mismatch');
		}
		
		if($this->Employee->is_logged_in())		//Model employee
		{
			redirect('dashboard');
			// redirect('home');
		}
		else
		{
			$this->form_validation->set_rules('username', 'lang:login_undername', 'callback_login_check');
    	    $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
			if($this->form_validation->run() == FALSE)
			{
				include APPPATH.'config/database.php';
				
				//If we have a site configuration check to make sure the user has not cancelled
				if (isset($db['site']))
				{
					$site_db = $this->load->database('site', TRUE);
					
					if ($this->_is_subscription_cancelled($site_db))
					{
						if ($this->_is_subscription_cancelled_within_30_days($site_db))
						{
							$this->load->view('login/login', array('subscription_cancelled_within_30_days' => true));
						}
						else
						{
							$this->load->view('login/subscription_cancelled');
						}
					}
					else
					{
						$this->load->view('login/login', $data);
					}
				}
				else
				{
					$this->load->view('login/login',$data);
				}
			}
			else
			{
				redirect('dashboard');
				// redirect('home');
			}
		}
	}
	
	function _is_subscription_cancelled($site_db)
	{
		$phppos_client_name = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));
		$site_db->select('subscr_status');	
		$site_db->from('subscriptions');	
		$site_db->where('username',$phppos_client_name);
		$site_db->where('subscr_status','cancelled');
		$query = $site_db->get();
		return ($query->num_rows() >= 1);
	}
	
	function _is_subscription_cancelled_within_30_days($site_db)
	{
		$phppos_client_name = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));
		$thirty_days_ago = date('Y-m-d H:i:s', strtotime("now -30 days"));
		$site_db->select('subscr_status');	
		$site_db->from('subscriptions');	
		$site_db->where('username',$phppos_client_name);
		$site_db->where('subscr_status','cancelled');
		$site_db->where('cancel_date >', $thirty_days_ago);
		$query = $site_db->get();
		return ($query->num_rows() >= 1);
	}
	
	function login_check($username)
	{
		$password = $this->input->post("password");	
		
		if(!$this->Employee->login($username,$password))
		{
			$this->form_validation->set_message('login_check', lang('login_invalid_username_and_password'));
			return false;
		}
		return true;		
	}
	
	function reset_password()
	{
		$this->load->view('login/reset_password');
	}
	
	function do_reset_password_notify()
	{
		$employee = $this->Employee->get_employee_by_username_or_email($this->input->post('username_or_email'));
		if ($employee)
		{
			$data = array();
			$data['employee'] = $employee;
		    $data['reset_key'] = base64url_encode($this->encrypt->encode($employee->person_id.'|'.(time() + (2 * 24 * 60 * 60))));
			
			$this->load->library('email');
			$config['mailtype'] = 'html';				
			$this->email->initialize($config);
			$this->email->from($this->config->item('email'), $this->config->item('company'));
			$this->email->to($employee->email); 

			$this->email->subject(lang('login_reset_password'));
			$this->email->message($this->load->view("login/reset_password_email",$data, true));	
			$this->email->send();
		}
		
		$this->load->view('login/do_reset_password_notify');	
	}
	
	function reset_password_enter_password($key=false)
	{
		if ($key)
		{
			$data = array();
		    list($employee_id, $expire) = explode('|', $this->encrypt->decode(base64url_decode($key)));			
			if ($employee_id && $expire && $expire > time())
			{
				$employee = $this->Employee->get_info($employee_id);
				$data['username'] = $employee->username;
				$data['key'] = $key;
				$this->load->view('login/reset_password_enter_password', $data);			
			}
		}
	}
	
	function do_reset_password($key=false)
	{
		if ($key)
		{
	    	list($employee_id, $expire) = explode('|', $this->encrypt->decode(base64url_decode($key)));
			
			if ($employee_id && $expire && $expire > time())
			{
				$password = $this->input->post('password');
				$confirm_password = $this->input->post('confirm_password');
				
				if (($password == $confirm_password) && strlen($password) >=8)
				{
					if ($this->Employee->update_employee_password($employee_id, md5($password)))
					{
						$this->load->view('login/do_reset_password');	
					}
				}
				else
				{
					$data = array();
					$employee = $this->Employee->get_info($employee_id);
					$data['username'] = $employee->username;
					$data['key'] = $key;
					$data['error_message'] = lang('login_passwords_must_match_and_be_at_least_8_characters');
					$this->load->view('login/reset_password_enter_password', $data);
				}
			}
		}
	}
}
?>