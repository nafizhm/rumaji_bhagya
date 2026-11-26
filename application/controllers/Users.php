<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

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
		$this->lib->check_login();
		$this->load->model('mod_users', '', TRUE);
		$this->load->model('mod_admin', '', TRUE);
	}
	
	public function index()
	{
		$this->list_users();
	}
	
	function chg_foto()
	{
		$data['form_action']= site_url('users/do_chg_foto');
		$data['title']= 'Ganti Foto';
		$this->load->view('frm_users_chg_foto',$data);
	}
	
	function do_chg_foto()
	{
		$field_name = "userfile";
		$path = 'assets/uploads_foto/';
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'jpg';
		$config['file_name'] = $this->session->userdata('userid').'.jpg';
		$config['remove_spaces'] = TRUE;
		$config['overwrite']     = TRUE;
		
		$this->load->library('upload', $config);
		
		if (!$this->upload->do_upload())
		{
			$this->session->set_flashdata('error', $this->upload->display_errors());
			redirect('users/chg_foto');
		}
   		else
		{
			$users = array( 
						'foto' => $config['file_name'],
						'chby'=> $this->session->userdata('userid'),
						'chdat'=>date('Y-m-d')
						);
						
		$this->mod_users->update_users($this->session->userdata('userid'),$users);
		
			$this->session->set_flashdata('ok', 'Foto sudah diganti');
			redirect('users/chg_foto');
		}
	}
	
	function chg_pass()
	{
		$data['form_action']= site_url('users/do_chg_pass');
		$data['title']= 'Ganti password';
		$this->load->view('frm_users_chg_pass',$data);
	}
	
	function do_chg_pass()
	{
		$users = array( 
						'pass' => $this->lib->enkripsi($this->input->post('txt_pass')),
						'chby'=> $this->session->userdata('userid'),
						'chdat'=>date('Y-m-d')
						);
						
		$this->mod_users->update_users($this->session->userdata('userid'),$users);
			
		$this->session->set_flashdata('ok', 'Password sudah diganti');
	    redirect('users/chg_pass');
		
	}
	
	function input_user()
	{
		$data['form_action']= site_url('users/do_input_user');
		$data['title'] = 'Tambah User';
		$data['readonly'] = '';
		$data['default']['cb_status'] = 1;
		$data['url_back']= site_url('users');
		
		/* Generate combo box */
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['role'] = $this->lib->generate_cb_role($this->input->post('cb_role'));
		$data['stat'] = $this->lib->generate_cb_stat($this->input->post('cb_stat'),'USR');
		
			
		$this->load->view('frm_user',$data);
	}
	
	function do_input_user()
	{
		$config = array(
				array(
                     'field'   => 'txt_userid',
                     'label'   => 'User ID',
                     'rules'   => 'is_unique[muser.userid]'
                  )
            );
		$this->form_validation->set_rules($config); 
		if ($this->form_validation->run() == TRUE)
		{
			$user = array( 
						'userid' => $this->input->post('txt_userid'),
						'namusr' => $this->input->post('txt_nama'),
						'email' => $this->input->post('txt_email'),
						'pass' => $this->lib->enkripsi($this->input->post('txt_pass')),
						'roleid' => $this->input->post('cb_role'),
						'status' => $this->input->post('cb_status'),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
						
			foreach($this->input->post('cb_proj') as $projid)
			{
				$proj[] = array( 
						'projid' => $projid,
						'userid' => $this->input->post('txt_userid'),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
			}
						
			$this->mod_users->input_users($user);
			$this->mod_users->input_prjusr($proj);
				
			$this->session->set_flashdata('ok', 'Tambah user berhasil');
			redirect('users/input_user');
		}
		else
		{
			$this->input_user();
		}
	}
	
	function list_users()
	{
		$list_users = $this->mod_users->list_users()->result();
		$data['list_users'] = $list_users;
		$data['title'] = 'List Users';
		$this->load->view('list_user',$data);
	}
	
	function edit_user()
	{
		$data['form_action']= site_url('users/do_edit_user');
		$data['readonly'] = 'readonly="readonly"';
		$data['title'] = 'List Users > Ubah User';
		$data['url_back']= site_url('users');
		$sql_get_usr = $this->mod_users->edit_users($this->uri->segment(3));
		foreach( $sql_get_usr->result()  as $row);
		
		$data['default']['txt_userid'] = $row->userid;
		$data['default']['txt_nama'] = $row->namusr;
		$data['default']['txt_email'] = $row->email;
		$data['default']['txt_pass'] = $this->lib->dekripsi($row->pass);
		$data['default']['txt_pass_conf'] = $this->lib->dekripsi($row->pass);
		
		/* Generate combo box */
		$data['role'] = $this->lib->generate_cb_role($row->roleid);
		$data['stat'] = $this->lib->generate_cb_stat($row->status,'USR');
		
		/* Generate combo box project */
			$query_proj = $this->mod_proj->list_proj();
			$data_proj = $query_proj->result();
			
			
			
			$data['proj']='';
			foreach($data_proj as $row_proj)
			{
				
				$query_projusr = $this->mod_users->get_prjusr($row->userid,$row_proj->projid);
			
			 	if($query_projusr->num_rows() == 1)
				{
					$selected = 'selected="selected"';
				}
				else
				{
					$selected = '';
				}
			
			$data['proj'] .= '<option value="'.$row_proj->projid.'" '.$selected.'>';
			$data['proj'] .= $row_proj->namproj.'</option>';
			
			}	
		
		$this->load->view('frm_user',$data);
	}
	
	function do_edit_user()
	{
		$user = array( 
						'userid' => $this->input->post('txt_userid'),
						'namusr' => $this->input->post('txt_nama'),
						'email' => $this->input->post('txt_email'),
						'pass' => $this->lib->enkripsi($this->input->post('txt_pass')),
						'status' => $this->input->post('cb_status'),
						'roleid' => $this->input->post('cb_role'),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
			$this->mod_users->update_users($this->input->post('txt_userid'),$user);
			$this->mod_users->delete_prjusr($this->input->post('txt_userid'));
			
			
			foreach($this->input->post('cb_proj') as $projid)
			{
				$proj[] = array( 
						'projid' => $projid,
						'userid' => $this->input->post('txt_userid'),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
			}
						
			$this->mod_users->input_prjusr($proj);
						
			$this->session->set_flashdata('ok', 'Data user telah diubah');
			redirect('users/edit_user/'.$this->input->post('txt_userid'));
	}
	
	function delete_user()
	{
		$this->mod_users->delete_users($this->uri->segment(3));
		
		$this->session->set_flashdata('ok', 'Data user sudah di hapus');
		redirect('users');
	}
	

}