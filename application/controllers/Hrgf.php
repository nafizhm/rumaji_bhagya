<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hrgf extends CI_Controller {

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
	 
	   /* Insialiasi otorisasi */
	 var $moid = 'PJ';
	 var $mid = 'M004';
	 
	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_hrgf', '', TRUE);
		$this->load->model('mod_proj', '', TRUE);
	}
	
	public function index()
	{
		$this->pick_proj();
	}
	
	function pick_proj()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action']= site_url('hrgf/do_search_hrgf');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'Harga Fitur';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj',$data);
	}
	
	function do_search_hrgf()
	{
		$projid = '';
		if($this->input->post('cb_proj') == '')
		{
			$projid = $this->uri->segment(3);
		}
		else
		{
			$projid = $this->input->post('cb_proj');
		}
		
		
		$list_hrgf = $this->mod_hrgf->list_hrgf($projid)->result();
		$data['list_hrgf'] = $list_hrgf;
		foreach($this->mod_proj->edit_proj($projid)->result() as $row_proj );
		
		$data['title'] = $row_proj->namproj;
		$this->load->view('list_hrgf',$data);
		
	}
	
	function input_hrgf()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'C');
		
		$data['form_action']= site_url('hrgf/do_input_hrgf');
		$data['url_back']= site_url('hrgf');
		$data['title'] = 'Tambah Harga Fitur';
		$data['readonly'] = '';
		
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['fit'] = $this->lib->generate_cb_fit($this->input->post('cb_fit'));
		$this->load->view('frm_hrgf',$data);
	}

	
	function list_sales()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		$this->sales_token();
		$this->load->view('list_sales',array('title'=>'List Sales','sales'=>$this->db->order_by('kodeslsman')->get('msalesman')->result()));
	}

	private function sales_token()
	{
		if (!$this->session->userdata('sales_token')) $this->session->set_userdata('sales_token',bin2hex(random_bytes(32)));
	}

	private function sales_mutation()
	{
		if ($this->input->method() !== 'post') show_error('Metode tidak diizinkan.',405);
		if (!hash_equals((string)$this->session->userdata('sales_token'),(string)$this->input->post('sales_token')) || !$this->session->userdata('sales_token')) show_error('Sesi formulir tidak valid. Muat ulang halaman.',403);
	}

	function edit_sales()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'U');
		$this->sales_token();
		$row=$this->db->get_where('msalesman',array('kodeslsman'=>(string)$this->input->get('kode')))->row();
		if (!$row) show_404();
		$this->load->view('edit_sales',array('title'=>'Edit Sales','row'=>$row));
	}

	function update_sales()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'U');
		$this->sales_mutation();
		$code=(string)$this->input->post('kode');
		$row=$this->db->get_where('msalesman',array('kodeslsman'=>$code))->row();
		if (!$row) show_404();
		$this->form_validation->set_rules('nama','Nama Sales','trim|required|max_length[50]');
		if (!$this->form_validation->run()) { $this->load->view('edit_sales',array('title'=>'Edit Sales','row'=>$row)); return; }
		$ok=$this->db->where('kodeslsman',$code)->update('msalesman',array('namaslsman'=>trim($this->input->post('nama'))));
		$this->session->set_flashdata($ok?'ok':'error',$ok?'Sales berhasil diperbarui.':'Sales gagal diperbarui.');
		redirect('sls/list_sls');
	}

	function delete_sales()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'D');
		$this->sales_mutation();
		$code=(string)$this->input->post('kode');
		if (!$this->db->get_where('msalesman',array('kodeslsman'=>$code))->row()) show_404();
		if ($this->db->where('kodeslsman',$code)->count_all_results('tsprh')) {
			$this->session->set_flashdata('error','Sales sudah digunakan pada SPR dan tidak dapat dihapus.');
		} else {
			$ok=$this->db->delete('msalesman',array('kodeslsman'=>$code));
			$this->session->set_flashdata($ok?'ok':'error',$ok?'Sales berhasil dihapus.':'Sales gagal dihapus.');
		}
		redirect('sls/list_sls');
	}

	function input_salesman()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'C');
		
		$data['form_action']= site_url('sls/save');
		// $data['url_back']= site_url('hrgf');
		$data['title'] = 'Tambah Sales';
		$data['readonly'] = '';
		
		// $data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		// $data['fit'] = $this->lib->generate_cb_fit($this->input->post('cb_fit'));
		$this->load->view('frm_salesman',$data);
	}



	function do_input_salesman()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'C');
		if ($this->input->method() !== 'post') show_error('Metode tidak diizinkan.',405);
		$this->form_validation->set_rules('txt_kode','Kode Sales','trim|required|max_length[4]|is_unique[msalesman.kodeslsman]');
		$this->form_validation->set_rules('txt_nama','Nama Sales','trim|required|max_length[50]');
		if (!$this->form_validation->run()) { $this->input_salesman(); return; }
		$slsman = array( 
						'kodeslsman' => $this->input->post('txt_kode'),
						'namaslsman' => $this->input->post('txt_nama')
						);
	   $this->mod_hrgf->input_slsman($slsman);
	   
	   $this->session->set_flashdata('ok', 'Sales berhasil ditambahkan');
	   redirect('sls/list_sls');
	}



	function do_input_hrgf()
	{
		//Check data already exist or not
		$sql_cekhrg = $this->mod_hrgf->edit_hrgf($this->input->post('cb_proj'),$this->input->post('cb_fit'), $this->lib->conv_date_to_int($this->input->post('txt_tgl')));
		
		if ( $sql_cekhrg->num_rows() > 0 )
		{
			  $this->session->set_flashdata('error', 'Harga sudah ada!');
	    	  redirect('hrgf/input_hrgf');
		}
		else
		{
		$hrgf = array( 
						'projid' => $this->input->post('cb_proj'),
						'ftid' => $this->input->post('cb_fit'),
						'hrg' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
						'tgl' =>  $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
	   $this->mod_hrgf->input_hrgf($hrgf);
	   
	   $hrgflog = array( 
						'projid' => $this->input->post('cb_proj'),
						'ftid' => $this->input->post('cb_fit'),
						'hrg' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
						'tgl' =>  $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d'),
						'crtim' =>  $this->lib->get_time()
						);
	   $this->mod_hrgf->input_hrgflog($hrgflog);
	   
	   $this->session->set_flashdata('ok', 'Tambah harga berhasil');
	    redirect('hrgf/input_hrgf');
		}
	}
	
	function edit_hrgf()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$data['form_action']= site_url('hrgf/do_edit_hrgf');
		$data['readonly'] = 'readonly="readonly"';
		$data['title'] = 'List Harga Fitur > Ubah Harga Fitur';
		$data['url_back']= site_url('hrgf/do_search_hrgf/'.$this->uri->segment(3));
		$sql_get_hrgf = $this->mod_hrgf->edit_hrgf($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5));
		foreach( $sql_get_hrgf->result()  as $row );
		
		$data['proj'] = $this->lib->generate_cb_proj($row->projid);
		$data['fit'] = $this->lib->generate_cb_fit($row->ftid);
		$data['default']['txt_proj'] = $row->projid;
		$data['default']['txt_fit'] = $row->ftid;
		
		$data['default']['txt_hrg'] = $row->hrg;
		$data['default']['txt_hrg_old'] = $row->hrg;
		
		$data['default']['txt_tgl'] = $this->lib->disp_date($row->tgl);
		
		$data['list_hrgflog'] = $this->mod_hrgf->list_hrgflog($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5))->result();
		
		$this->load->view('frm_hrgf',$data);
		
	}
	
	function do_edit_hrgf()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'U');
		
		$hrgf = array( 
						'hrg' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
						
		$this->mod_hrgf->update_hrgf($this->input->post('txt_proj'),$this->input->post('txt_fit'),
		$this->lib->conv_date_to_int($this->input->post('txt_tgl')),$hrgf);
		
		if( $this->lib->convert_idr_to_num($this->input->post('txt_hrg')) != $this->input->post('txt_hrg_old'))
		{
			  $hrgflog = array( 
						'projid' => $this->input->post('txt_proj'),
						'ftid' => $this->input->post('txt_fit'),
						'hrg' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
						'tgl' =>  $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d'),
						'crtim' =>  $this->lib->get_time()
						);
	   		$this->mod_hrgf->input_hrgflog($hrgflog);
			
		}
		$this->session->set_flashdata('ok', 'Data harga telah diubah');
		redirect('hrgf/edit_hrgf/'.$this->input->post('txt_proj').'/'.$this->input->post('txt_fit').'/'.$this->lib->conv_date_to_int($this->input->post('txt_tgl')));
	}


}
