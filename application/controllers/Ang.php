<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ang extends CI_Controller {

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
	 var $moid = 'CO';
	 var $mid = 'M001'; //Pos Anggaran
	 var $mid2 = 'M002'; //Anggaran
	 var $mid3 = 'R001'; //Laporan Pemakaian Anggaran
	 var $mid4 = 'R002'; //Laporan Pemakaian Anggaran Perminggu
	 
	
	function __construct()
	{
		parent::__construct();
		$this->lib->check_login();
		$this->load->model('mod_ang', '', TRUE);
		$this->load->model('mod_proj', '', TRUE);
		$this->load->model('mod_dana', '', TRUE);

	}
	
	public function index()
	{
		$this->list_pa();
	}
/* ---------------------------------------- Anggaran ----------------------------------- */	
function do_edit_real()
{
	$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid4,'U');
	$bank = $this->input->post('cb_bank');
	$bank_arr = array();
	$bank_arr = explode(';',$bank);
	$dana = array( 
				   'bankid' => $bank_arr[0],
				   'accno' => $bank_arr[1],
				   'nocek' => strtoupper($this->input->post('txt_nocek')),
				   'totalbyr' => $this->lib->convert_idr_to_num($this->input->post('txt_real')),
				   'ket' => $this->input->post('txt_ket'),
				   'chby' => $this->session->userdata('userid'),
				   'chdat' => date('Y-m-d')
					);
					
   $this->mod_dana->update_dana($this->uri->segment(3),$this->input->post('txt_noreq'),$dana);
   
   $this->session->set_flashdata('ok', 'Realisasi permintaan dana no.req '.$this->lib->no_req($this->input->post('txt_noreq')).' telah di ubah');
   redirect('ang/do_lap_angmg_disp/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6));	
}

function lap_ang_mg()
{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid4,'R');
	
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action']= site_url('ang/do_lap_angmg_disp');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['mon'] = $this->lib->generate_cb_mon($this->input->post('cb_mon'));
		$data['main_title'] = 'Laporan Permintaan Dana Perminggu';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_lap_ang_mg',$data);
}

function do_lap_angmg_disp()
{
	if($this->input->post('cb_proj')=='')
	{
		$data['proj'] = $this->uri->segment(3);
	}
	else
	{
		$data['proj'] = $this->input->post('cb_proj');
	}
	
	if($this->input->post('cb_mon')=='')
	{
		$cb_mon = $this->uri->segment(4);
	}
	else
	{
		$cb_mon = $this->input->post('cb_mon');
	}
	
	if($this->input->post('cb_mg')=='')
	{
		$cb_mg = $this->uri->segment(5);
	}
	else
	{
		$cb_mg = $this->input->post('cb_mg');
	}
	
	if($this->input->post('txt_tahun')=='')
	{
		$txt_tahun = $this->uri->segment(6);
	}
	else
	{
		$txt_tahun = $this->input->post('txt_tahun');
	}
		
		$list_ang_mg = $this->mod_ang->ang_mg($data['proj'],$cb_mon,$cb_mg,$txt_tahun)->result();
		$data['list_ang_mg'] = $list_ang_mg;
		
		foreach($this->mod_proj->edit_proj($data['proj'])->result() as $row_proj );
		$data['title'] = 'Project '.$row_proj->namproj;
		if($cb_mg != 0)
		{
		$data['subtitle'] = 'Bulan '.$this->lib->disp_mon($cb_mon).' Minggu Ke '.$cb_mg.' Tahun '.$txt_tahun;
		}
		else
		{
		$data['subtitle'] = 'Bulan '.$this->lib->disp_mon($cb_mon).' Tahun '.$txt_tahun;	
		}
		
//Cek otorisasi edit
$query = $this->db->query("SELECT * FROM mauth WHERE roleid = '".$this->session->userdata('roleid')."' AND moid = '".$this->moid."'  AND mid = '".$this->mid4."'");
if($query->num_rows() > 0)
{
foreach( $query->result()  as $row);
$data['edit'] = $row->u;
}
else
{
	$data['edit'] = 1; //Admin
}
		
		$this->load->view('lap_ang_mg',$data);
	
}

function lap_ang()
{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid3,'R');
	
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action']= site_url('ang/do_lap_ang_disp');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'Laporan Pemakaian Anggaran';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_projang',$data);
}

function do_lap_ang_disp()
{
		$data['projid'] = '';
		$data['kav'] = '';
		$data['excel'] = $this->uri->segment(4);

		if($this->input->post('cb_proj') == '')
		{
			$data['projid'] = $this->uri->segment(3);
			$data['kav'] = $this->uri->segment(5);
		}
		else
		{
			$data['projid'] = $this->input->post('cb_proj');
			$data['kav'] = $this->input->post('txt_kav');
		}
		
		$list_pa = $this->mod_ang->list_mpa()->result();
		$data['list_pa'] = $list_pa;
		
		foreach($this->mod_proj->edit_proj($data['projid'])->result() as $row_proj );
		$data['title'] = 'Laporan Pemakaian Anggaran Project '.$row_proj->namproj;
		
		
		$this->load->view('lap_ang',$data);
}

function pick_proj()
	{
		$list_proj = $this->mod_proj->list_proj()->result();
		$data['form_action']= site_url('ang/do_search_ang');
		$data['proj'] = $this->lib->generate_cb_proj($this->input->post('cb_proj'));
		$data['main_title'] = 'Atur Anggaran';
		$data['title'] = 'Pilih Project';
		$this->load->view('frm_pick_proj',$data);
	}
	
function do_search_ang()
{
	$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'R');
	
		$data['projid'] = '';
		if($this->input->post('cb_proj') == '')
		{
			$data['projid'] = $this->uri->segment(3);
		}
		else
		{
			$data['projid'] = $this->input->post('cb_proj');
		}
		
		$list_pa = $this->mod_ang->list_mpa()->result();
		$data['list_pa'] = $list_pa;
		
		foreach($this->mod_proj->edit_proj($data['projid'])->result() as $row_proj );
		$data['title'] = 'Anggaran Project '.$row_proj->namproj;
		$this->load->view('list_ang',$data);
}

function input_ang()
{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'C');
	
		foreach($this->mod_proj->edit_proj($this->uri->segment(3))->result() as $row_proj );
		$data['title'] = 'Tambah Anggaran Project '.$row_proj->namproj;
		
		foreach($this->mod_ang->edit_mpa($this->uri->segment(4))->result() as $row_pa );
		$data['title2'] = 'Pos Anggaran '.$row_pa->nampa;
		
		$data['url_back']= site_url('ang/do_search_ang/'.$this->uri->segment(3));
		
		$data['cb_by']='';
	
	$query_cb = $this->db->query("SELECT *
	FROM mby WHERE paid = '".$this->uri->segment(4)."'
	AND byid NOT IN 
	( SELECT byid 
	 FROM mang 
	 WHERE projid = '".$this->uri->segment(3)."' AND paid = '".$this->uri->segment(4)."')");
	foreach( $query_cb->result()  as $row_cb)
	{
			$data['cb_by'].= '<option value="'.$row_cb->byid.'">'.$row_cb->byid.' - '.$row_cb->namby.'</option>';
	}
		$data['form_action']= site_url('ang/do_input_ang');
		$this->load->view('frm_ang',$data);
	
}

function do_input_ang()
{
	$ang = array( 
						'projid' => $this->input->post('txt_projid'),
						'paid' => $this->input->post('txt_paid'),
						'byid' => $this->input->post('cb_by'),
						'total' => $this->lib->convert_idr_to_num($this->input->post('txt_total')),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
	
	$anglog = array( 
						'projid' => $this->input->post('txt_projid'),
						'paid' => $this->input->post('txt_paid'),
						'byid' => $this->input->post('cb_by'),
						'total' => $this->lib->convert_idr_to_num($this->input->post('txt_total')),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d'),
						'crtim' => $this->lib->get_time()
						);
						
			$this->mod_ang->input_mang($ang);
			$this->mod_ang->input_tanglog($anglog);

			$this->session->set_flashdata('ok', 'Tambah anggaran berhasil');
			redirect('ang/do_search_ang/'.$this->input->post('txt_projid'));
}

function edit_ang()
{
	$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'R');
	
	foreach($this->mod_proj->edit_proj($this->uri->segment(3))->result() as $row_proj );
		$data['title'] = 'Ubah Anggaran Project '.$row_proj->namproj;
		
		foreach($this->mod_ang->edit_mpa($this->uri->segment(4))->result() as $row_pa );
		$data['title2'] = 'Pos Anggaran '.$row_pa->nampa;
		
		$data['url_back']= site_url('ang/do_search_ang/'.$this->uri->segment(3));
		
		$data['cb_by']='';
	
	$query_cb = $this->db->query("SELECT *
	FROM mby WHERE paid = '".$this->uri->segment(4)."'
	AND byid = '".$this->uri->segment(5)."'");
	foreach( $query_cb->result()  as $row_cb)
	{
			$data['cb_by'].= '<option value="'.$row_cb->byid.'">'.$row_cb->byid.' - '.$row_cb->namby.'</option>';
	}
	
	$sql_get_ang = $this->mod_ang->edit_ang($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5));
	foreach( $sql_get_ang->result()  as $row);
		
		$data['default']['txt_total'] = $row->total;
		$data['default']['txt_total_old'] = $row->total;
		
	$data['list_anglog'] = $this->mod_ang->list_tanglog($this->uri->segment(3),$this->uri->segment(4),$this->uri->segment(5))->result();
	
		$data['form_action']= site_url('ang/do_edit_ang/');
		$this->load->view('frm_ang',$data);
}

function do_edit_ang()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'U');
		
		$ang = array( 
						'total' => $this->lib->convert_idr_to_num($this->input->post('txt_total')),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
			$this->mod_ang->update_mang($this->input->post('txt_projid'),$this->input->post('txt_paid'),$this->input->post('cb_by'),$ang);
			
			if($this->lib->convert_idr_to_num($this->input->post('txt_total')) <> $this->input->post('txt_total_old'))
			{
				$anglog = array( 
							'projid' => $this->input->post('txt_projid'),
							'paid' => $this->input->post('txt_paid'),
							'byid' => $this->input->post('cb_by'),
							'total' => $this->lib->convert_idr_to_num($this->input->post('txt_total')),
							'crby' => $this->session->userdata('userid'),
							'crdat' => date('Y-m-d'),
							'crtim' => $this->lib->get_time()
							);
				$this->mod_ang->input_tanglog($anglog);
			}
				
			$this->session->set_flashdata('ok', 'Data biaya telah diubah');
			redirect('ang/do_search_ang/'.$this->input->post('txt_projid'));	
		
	}
	
/* ---------------------------------------- Biaya ----------------------------------- */	
	function input_by()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'C');
		
		$data['form_action']= site_url('ang/do_input_by');
		$data['url_back']= site_url('ang/list_pa');
		
		$list_by = $this->mod_ang->list_mby($this->uri->segment(3))->result();
		$data['list_by'] = $list_by;
		
		$sql_get_pa = $this->mod_ang->edit_mpa($this->uri->segment(3));
		foreach( $sql_get_pa->result()  as $row);
		$data['nampa'] = $row->nampa;
		
		$data['title'] = 'Pos Anggaran '.$row->nampa.' > Tambah Biaya';
		
		$this->load->view('list_ang_by',$data);
	}
	
	function input_by_frm()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'C');
		
		$data['form_action']= site_url('ang/do_input_by');
		$data['readonly'] = '';
		$data['url_back']= site_url('ang/input_by/'.$this->uri->segment(3));
		
		$sql_get_pa = $this->mod_ang->edit_mpa($this->uri->segment(3));
		foreach( $sql_get_pa->result()  as $row);
		$data['nampa'] = $row->nampa;
		
		$data['title'] = 'Pos Anggaran '.$row->nampa.' > Tambah Biaya';
		
		$data['default']['txt_paid'] = $this->uri->segment(3);
		
		$this->load->view('frm_by',$data);
		
	}
	
	function do_input_by()
	{	
		$by = array( 
						'paid' => $this->input->post('txt_paid'),
						'byid' => $this->input->post('txt_byid'),
						'namby' => $this->input->post('txt_namby'),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
			$this->mod_ang->input_mby($by);

			$this->session->set_flashdata('ok', 'Tambah biaya berhasil');
			redirect('ang/input_by/'.$this->input->post('txt_paid'));
		
	}
	
	function delete_by()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'D');
		
		$this->mod_ang->delete_mby($this->uri->segment(3),$this->uri->segment(4));
		
		$this->session->set_flashdata('ok', 'Data biaya sudah di hapus');
		redirect('ang/input_by/'.$this->uri->segment(3));
	}
	
	function edit_by()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'R');
		
		$data['form_action']= site_url('ang/do_edit_by');
		$data['readonly'] = 'readonly="readonly"';
		
		$data['url_back']= site_url('ang/input_by/'.$this->uri->segment(3));
		$sql_get_pa = $this->mod_ang->edit_mpa($this->uri->segment(3));
		foreach( $sql_get_pa->result()  as $row_pa);
		
		$sql_get_by = $this->mod_ang->edit_mby($this->uri->segment(3),$this->uri->segment(4));
		foreach( $sql_get_by->result()  as $row);
		
		$data['default']['txt_paid'] = $row->paid;
		$data['default']['txt_byid'] = $row->byid;
		$data['default']['txt_namby'] = $row->namby;
		
		$data['title'] = 'Pos Anggaran '.$row_pa->nampa.' > Ubah Biaya '.$row->namby;
		
		$this->load->view('frm_by',$data);
	}
	
	function do_edit_by()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid2,'U');
		
		$by = array( 
						'namby' => $this->input->post('txt_namby'),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
			$this->mod_ang->update_mby($this->input->post('txt_paid'),$this->input->post('txt_byid'),$by);
				
			$this->session->set_flashdata('ok', 'Data biaya telah diubah');
			redirect('ang/edit_by/'.$this->input->post('txt_paid').'/'.$this->input->post('txt_byid'));	
		
	}

/* ---------------------------------------- Pos anggaran ----------------------------------- */	
	
	function input_pa()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'C');
		
		$data['form_action']= site_url('ang/do_input_pa');
		$data['url_back']= site_url('ang/list_pa');
		$data['title'] = 'Tambah Pos Anggaran';
		$data['readonly'] = '';
		
		$this->load->view('frm_pa',$data);
	}
	
	function do_input_pa()
	{
		$config = array(
				array(
                     'field'   => 'txt_paid',
                     'label'   => 'Kode Pos Anggaran',
                     'rules'   => 'is_unique[mpa.paid]'
                  )
            );
		$this->form_validation->set_rules($config); 
		if ($this->form_validation->run() == TRUE)
		{
			
		$pa = array( 
						'paid' => $this->input->post('txt_paid'),
						'nampa' => $this->input->post('txt_nampa'),
						'crby' => $this->session->userdata('userid'),
						'crdat' => date('Y-m-d')
						);
			$this->mod_ang->input_mpa($pa);

			$this->session->set_flashdata('ok', 'Tambah pos anggaran berhasil');
			redirect('ang/input_pa');
		}
		else
		{
			$this->input_pa();
		}
		
	}
	
	function edit_pa()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$data['form_action']= site_url('ang/do_edit_pa');
		$data['readonly'] = 'readonly="readonly"';
		$data['title'] = 'List Pos Anggaran > Ubah Pos Anggaran';
		$data['url_back']= site_url('ang/list_pa');
		$sql_get_pa = $this->mod_ang->edit_mpa($this->uri->segment(3));
		foreach( $sql_get_pa->result()  as $row);
		
		$data['default']['txt_paid'] = $row->paid;
		$data['default']['txt_nampa'] = $row->nampa;
		
		$this->load->view('frm_pa',$data);
	}
	
	function do_edit_pa()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'U');
		
		$pa = array( 
						'nampa' => $this->input->post('txt_nampa'),
						'chby' => $this->session->userdata('userid'),
						'chdat' => date('Y-m-d')
						);
			$this->mod_ang->update_mpa($this->input->post('txt_paid'),$pa);
				
			$this->session->set_flashdata('ok', 'Data pos anggaran telah diubah');
			redirect('ang/edit_pa/'.$this->input->post('txt_paid'));	
		
	}
	
	function list_pa()
	{
		$this->lib->check_auth($this->session->userdata('roleid'),$this->moid,$this->mid,'R');
		
		$list_pa = $this->mod_ang->list_mpa()->result();
		$data['list_pa'] = $list_pa;
		$data['title'] = 'List Pos Anggaran';
		$this->load->view('list_ang_pos',$data);
	}
	
	

}