<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('mod_users', '', TRUE);
	}
	
	var $title_login = 'Login';
	
	public function index()
	{
		$this->login();
	}
	
	function login()
	{
		$data['form_action']= site_url('auth/do_login');
		$data['title']= $this->title_login;
		$this->load->view('frm_login',$data);
	}
	
	function do_login()
	{
		$config = array(
               array(
                     'field'   => 'txt_usnam',
                     'label'   => 'User name',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'txt_pass',
                     'label'   => 'Password',
                     'rules'   => 'required'
                  )
            );
			
		$this->form_validation->set_rules($config); 
		if ($this->form_validation->run() == TRUE)
			{
				$sql_login = $this->mod_users->check_login($this->input->post('txt_usnam'));
				if ($sql_login->num_rows() > 0)
				{	
					foreach( $sql_login->result()  as $row_user);
					
					if($this->input->post('txt_pass') != $this->lib->dekripsi($row_user->pass))
					{
						$this->session->set_flashdata('error', 'Password salah!');
						redirect('auth/login/');	
					}
					elseif($row_user->status=='NON')
					{
						$this->session->set_flashdata('error', 'User tidak aktif!');
						redirect('auth/login/');
					}
					else
					{
						
						
								$usr_sess = array(
											   'userid'  => $this->input->post('txt_usnam'),
											   'roleid'  => $row_user->roleid,
											   'logged_in' => TRUE ,
											   'name' => $row_user->namusr
										   );
								$this->session->set_userdata($usr_sess);
								redirect('home');
					}
								
				}
				else
				{
					$this->session->set_flashdata('error', 'Username tidak terdaftar!');
					redirect('auth/login/');
				}
			}
		else
			{
				$this->login();
			}
	}
	
	function logout()
	{	
			/*$sess_items = array('userid' => '',
			 			'roleid'  => '', 
						'logged_in' => '',
						'name' => '',
						'modul' => '',
						'nama_modul' => '');*/
		
		$sess_items = array('userid', 'roleid','logged_in','name','modul','nama_modul');
		
			$this->session->unset_userdata($sess_items);
	
	


		$this->session->set_flashdata('ok', 'Logout sukses');
		redirect('auth/login/');
	}


}