<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hrg extends CI_Controller {

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
	 var $mid = 'M003';
	 
	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_hrg', '', TRUE);
		$this->load->model('mod_proj', '', TRUE);
	}
	
	public function index()
	{
		$this->pick_proj();
	}
	
	function delete_hrg()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'D');
		
		$this->db->trans_start();	
		$this->mod_hrg->delete_hrg($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5),$this->uri->segment(6));
		$this->mod_hrg->delete_hrglog($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5),$this->uri->segment(6));
		$this->db->trans_complete();
	
	if ($this->db->trans_status() == TRUE)
	{
		$this->session->set_flashdata('ok', 'Harga sudah dihapus');
	}
	else
	{
		$this->session->set_flashdata('error', 'Hapus harga gagal!');
	}
	    redirect('hrg/do_search_hrg/'.$this->uri->segment(3));
	}
	
	function pick_proj()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'C');
		
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action']= site_url('hrg/do_search_hrg');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'Harga Standard';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj',$data);
	}
	
	function do_search_hrg()
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
		
		
		$list_hrg = $this->mod_hrg->list_hrg($projid)->result();
		$data['list_hrg'] = $list_hrg;
		foreach($this->mod_proj->edit_proj($projid)->result() as $row_proj );
		
		$data['title'] = $row_proj->namproj;
		$this->load->view('list_hrg',$data);
		
	}
	
	function input_hrg()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'C');
		
		$data['form_action']= site_url('hrg/do_input_hrg');
		$data['url_back']= site_url('hrg');
		$data['title'] = 'Tambah Harga';
		$data['readonly'] = '';
		
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['tipe'] = $this->lib->generate_cb_tipe($this->input->post('cb_tipe'));
		
		$this->load->view('frm_hrg',$data);
	}
	
	function do_input_hrg()
	{
		//Check data already exist or not
		$sql_cekhrg = $this->mod_hrg->edit_hrg($this->input->post('cb_proj'),strtoupper($this->input->post('txt_lot')),$this->input->post('cb_tipe'), $this->lib->conv_date_to_int($this->input->post('txt_tgl')));
		
		if ( $sql_cekhrg->num_rows() > 0 )
		{
			  $this->session->set_flashdata('error', 'Harga sudah ada!');
	    	  redirect('hrg/input_hrg');
		}
		else
		{
		$hrg = array( 
						'projid' => $this->input->post('cb_proj'),
						'lot' => strtoupper(trim($this->input->post('txt_lot'))),
						'tipe' => $this->input->post('cb_tipe'),
						'hrgstd' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
						'byttp' => $this->lib->convert_idr_to_num($this->input->post('txt_byttp')),
						'tgl' =>  $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
	   $this->mod_hrg->input_hrg($hrg);
	   
	   $hrglog = array( 
						'projid' => $this->input->post('cb_proj'),
						'lot' => strtoupper(trim($this->input->post('txt_lot'))),
						'tipe' => $this->input->post('cb_tipe'),
						'hrgstd' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
						'byttp' => $this->lib->convert_idr_to_num($this->input->post('txt_byttp')),
						'tgl' =>  $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d'),
						'crtim' =>  $this->lib->get_time()
						);
	   $this->mod_hrg->input_hrglog($hrglog);
	   
	   $this->session->set_flashdata('ok', 'Tambah harga berhasil');
	    redirect('hrg/input_hrg');
		}
	}
	
	function edit_hrg()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'D');
		
		$data['form_action']= site_url('hrg/do_edit_hrg');
		$data['readonly'] = 'readonly="readonly"';
		$data['title'] = 'List Harga > Ubah Harga';
		$data['url_back']= site_url('hrg/do_search_hrg/'.$this->uri->segment(3));
		$sql_get_hrg = $this->mod_hrg->edit_hrg($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5),$this->uri->segment(6));
		foreach( $sql_get_hrg->result()  as $row );
		
		$data['proj'] = $this->lib->generate_cb_proj($row->projid);
		$data['tipe'] = $this->lib->generate_cb_tipe($row->tipe);
		$data['default']['txt_proj'] = $row->projid;
		$data['default']['txt_tipe'] = $row->tipe;
		$data['default']['txt_lot'] = $row->lot;
		$data['default']['txt_hrg'] = $row->hrgstd;
		$data['default']['txt_byttp'] = $row->byttp;
		
		$data['default']['txt_hrg_old'] = $row->hrgstd;
		$data['default']['txt_byttp_old'] = $row->byttp;
		$data['default']['txt_lot_old'] = $row->lot;
		$data['default']['txt_tipe_old'] = $row->tipe;
		$data['default']['txt_tgl_old'] = $row->tgl;
		
		$data['default']['txt_tgl'] = $this->lib->disp_date($row->tgl);
		
		$data['list_hrglog'] = $this->mod_hrg->list_hrglog($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5),$this->uri->segment(6))->result();
		
		$this->load->view('frm_hrg',$data);
		
	}
	
	function do_edit_hrg()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'U');
		
		//Check data already exist or not
			if( $this->input->post('cb_tipe') != $this->input->post('txt_tipe_old') ||  $this->input->post('txt_lot') != $this->input->post('txt_lot_old') || $this->lib->conv_date_to_int($this->input->post('txt_tgl')) != $this->input->post('txt_tgl_old'))
		    {
		    
		    
		    	$sql_cekhrg = $this->mod_hrg->edit_hrg($this->input->post('txt_proj'),strtoupper($this->input->post('txt_lot')),$this->input->post('cb_tipe'), $this->lib->conv_date_to_int($this->input->post('txt_tgl')));
		    print_r($sql_cekhrg);
		    if ( $sql_cekhrg->num_rows() > 0 )
		    {
			  $this->session->set_flashdata('error', 'Harga sudah ada untuk lot '.strtoupper($this->input->post('txt_lot')).' tipe '.$this->input->post('cb_tipe').' tanggal valid '.$this->input->post('txt_tgl'));
	    	  redirect('hrg/do_search_hrg/'.$this->input->post('txt_proj'));
		    }
		
		    
		    }
		
		$hrg = array( 
		    	        'lot' => strtoupper(trim($this->input->post('txt_lot'))),
						'tipe' => $this->input->post('cb_tipe'),
						 'tgl' =>  $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
						'hrgstd' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
						'byttp' => $this->lib->convert_idr_to_num($this->input->post('txt_byttp')),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
						
		$this->mod_hrg->update_hrg($this->input->post('txt_proj'),strtoupper($this->input->post('txt_lot_old')),$this->input->post('txt_tipe_old'),
		$this->input->post('txt_tgl_old'),$hrg);
		
		if( $this->lib->convert_idr_to_num($this->input->post('txt_hrg')) != $this->input->post('txt_hrg_old') || $this->lib->convert_idr_to_num($this->input->post('txt_byttp')) != $this->input->post('txt_byttp_old') ||  $this->input->post('cb_tipe') != $this->input->post('txt_tipe_old') ||  $this->input->post('txt_lot') != $this->input->post('txt_lot_old') || $this->lib->conv_date_to_int($this->input->post('txt_tgl')) != $this->input->post('txt_tgl_old'))
		{
			 $hrglog = array( 
						'projid' => $this->input->post('txt_proj'),
						'lot' => strtoupper($this->input->post('txt_lot')),
						'tipe' => $this->input->post('cb_tipe'),
						'hrgstd' => $this->lib->convert_idr_to_num($this->input->post('txt_hrg')),
						'byttp' => $this->lib->convert_idr_to_num($this->input->post('txt_byttp')),
						'tgl' =>  $this->lib->conv_date_to_int($this->input->post('txt_tgl')),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d'),
						'crtim' =>  $this->lib->get_time()
						);
	   		$this->mod_hrg->input_hrglog($hrglog);
			
		}
		$this->session->set_flashdata('ok', 'Data harga telah diubah');
	redirect('hrg/edit_hrg/'.$this->input->post('txt_proj').'/'.strtoupper($this->input->post('txt_lot')).'/'.$this->input->post('cb_tipe').'/'.$this->lib->conv_date_to_int($this->input->post('txt_tgl')));
	}


}