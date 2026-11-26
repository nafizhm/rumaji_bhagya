<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas extends CI_Controller {

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
	
	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_kas', '', TRUE);
		$this->load->model('mod_bank', '', TRUE);
	}
	
	public function index()
	{
		$this->list_kb_in();
	}
	
	function edit_kb()
	{
		$data['form_action']= site_url('kas/do_edit_kb');
		$data['title'] = 'List Penerimaan Kas Besar > Ubah Data Kas No '.$this->lib->no_kas($this->uri->segment(3));
		$data['url_back']= site_url('kas/do_search_kb/'.$this->uri->segment(4));
		$sql_get_kb = $this->mod_kas->edit_kas($this->uri->segment(4),$this->uri->segment(3),'KB');
		foreach( $sql_get_kb->result()  as $row );
		
		$data['default']['txt_tgl_trans'] = $this->lib->disp_date($row->tgl);
		$data['default']['txt_total'] = $this->lib->format_idr($row->total);
		$data['default']['txt_ket'] = $row->ket;
		
		$data['default']['txt_projid'] = $this->uri->segment(4);
		$data['default']['txt_nokas'] = $this->uri->segment(3);
		
		
		$data['cb_proj'] = $this->lib->generate_cb_proj($row->projid);
		$data['cb_tkas'] = $this->lib->generate_cb_tkas('KB','D',$row->tipe);
		
		$this->load->view('frm_kb',$data);
	}
	
	function do_edit_kb()
	{
		
		$kas = array( 
						'projid' => $this->input->post('cb_proj'),
						'tipe' => $this->input->post('cb_tkas'),
						'total' => $this->lib->convert_idr_to_num($this->input->post('txt_total')),
						'ket' => $this->input->post('txt_ket'),
						'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
			
						
	    $this->mod_kas->update_kas($this->input->post('txt_projid'),$this->input->post('txt_nokas'),$kas);
		$this->session->set_flashdata('ok', 'Data penerimaan kas besar sudah di ubah');
		redirect('kas/do_search_kb/'.$this->input->post('txt_projid'));
	}
	
	function do_kb_btl()
	{
		
		$kas = array( 
						'btl' => 1,
						'ketbtl' => $this->input->post('txt_ket'),
						'btlby' => $this->session->userdata('userid'),
						'btldat' => date('Y-m-d')
						);
			
						
	    $this->mod_kas->update_kas($this->input->post('txt_projid'),$this->input->post('txt_nokas'),$kas);
		$this->session->set_flashdata('ok', 'Data penerimaan kas besar sudah di batalkan');
		redirect('kas/do_search_kb/'.$this->input->post('txt_projid'));
	}
	
	function delete_kb()
	{
		$this->mod_kas->delete_kas($this->uri->segment(4),$this->uri->segment(3));
			
	    $this->session->set_flashdata('ok', 'Data penerimaan kas besar sudah di hapus');
		redirect('kas/do_search_kb/'.$this->uri->segment(4));
	}
	
	function print_kb()
	{
		
		$sql_print_kb = $this->mod_kas->cetak_kas($this->uri->segment(4),$this->uri->segment(3),'KB');
		foreach( $sql_print_kb->result()  as $row );
		
		$data['nokas'] = $this->lib->no_kas($row->nokas);
		$data['namproj'] = $row->namproj;
		$data['total'] = $this->lib->format_idr($row->total);
		$data['total2'] = $row->total;
		$data['tipetrans'] = $row->namtipe;
		$data['ket'] = $row->ket;
		$data['tgltrans'] = $this->lib->disp_date($row->tgl);
		$data['namuser'] = $this->lib->disp_namusr($row->crby);
		
		$filename = 'kb_'.$row->projid.'_'.$row->nokas;
		
		$data['bank'] = $this->mod_bank->get_bankacc_kas($this->uri->segment(4),'KB')->result();
		
		$this->load->library('pdf');
		$this->pdf->load_view('print_kb',$data,$filename);
	}
	
	function pick_proj($form,$title)
	{
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action']= site_url('kas/'.$form);
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'List '.$title;
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj',$data);
	}
	
	function list_kb_in()
	{
		$this->pick_proj('do_search_kb','Penerimaan Kas Besar');
	}
	
	function do_search_kb()
	{
		$data['projid'] = '';
		if($this->input->post('cb_proj') == '')
		{
			$data['projid'] = $this->uri->segment(3);
		}
		else
		{
			$data['projid'] = $this->input->post('cb_proj');
		}
		
		$list_kb = $this->mod_kas->list_kas($data['projid'],'KB')->result();
		$data['list_kb'] = $list_kb;
		
		foreach($this->mod_proj->edit_proj($data['projid'])->result() as $row_proj );
		$data['title'] = 'Penerimaan Kas Besar Untuk Project '.$row_proj->namproj;
		
		$data['form_action']= site_url('kas/do_kb_btl');
		$this->load->view('list_kb',$data);
	}
	
	function kb_input_in()
	{
		
		$data['form_action']= site_url('kas/do_kb_input_in');
		
		
		$data['url_back']= site_url('kas/list_kb_in');
		$data['title'] = 'Tambah Penerimaan Kas Besar';
		
		$data['cb_proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['cb_tkas'] = $this->lib->generate_cb_tkas('KB','D',$this->input->post('cb_tkas'));
		
		$this->load->view('frm_kb',$data);
	}
	
	function do_kb_input_in()
	{
		$kasno = $this->lib->get_no($this->input->post('cb_proj'),'KAS');
		
		if($this->input->post('cb_tkas') == 'CIN')
		{
			$kas[] = array( 
						'projid' => $this->input->post('cb_proj'),
						'nokas' => $kasno,
						'kas' => 'KB',
						'tipe' => $this->input->post('cb_tkas'),
						'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')),
						'total' => 	$this->lib->convert_idr_to_num($this->input->post('txt_total')),
						'ket' => $this->input->post('txt_ket'),
						'crdat' => date('Y-m-d'),
						'crby' => $this->session->userdata('userid')
					);
					
			$kas[] = array( 
						'projid' => $this->input->post('cb_proj'),
						'nokas' => $kasno,
						'kas' => 'KK',
						'tipe' => 'TKB',
						'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')),
						'total' => 	$this->lib->convert_idr_to_num($this->input->post('txt_total')) * -1,
						'ket' => $this->input->post('txt_ket'),
						'crdat' => date('Y-m-d'),
						'crby' => $this->session->userdata('userid')
					);
			
			
			$this->mod_kas->input_kas_batch($kas);
		}
		else
		{
		$kas = array( 
						'projid' => $this->input->post('cb_proj'),
						'nokas' => $kasno,
						'kas' => 'KB',
						'tipe' => $this->input->post('cb_tkas'),
						'tgl' => $this->lib->conv_date_to_int($this->input->post('txt_tgl_trans')),
						'total' => 	$this->lib->convert_idr_to_num($this->input->post('txt_total')),
						'ket' => $this->input->post('txt_ket'),
						'crdat' => date('Y-m-d'),
						'crby' => $this->session->userdata('userid')
					);
		
		$this->mod_kas->input_kas($kas);
		}
		$this->lib->commit_no($this->input->post('cb_proj'),$kasno,'KAS');
		
		$this->session->set_flashdata('ok', 'Tambah penerimaan kas besar berhasil');
		redirect('kas/kb_input_in');
		
	}

}