<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

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
		$this->load->model('mod_proj', '', TRUE);
		$this->load->model('mod_dashboard');
	}
	
	//var $title_dashboard = 'Dashboard';
	
	public function index()
	{
		//$this->dashboard();
		$this->main_menu();
	}
	
	function main_menu()
	{
		$data['title']= 'Menu Utama';
		
		
		switch ($this->uri->segment(3)) {
    	case 'penjualan':
       		$modul_sess = array('modul'  => 'penjualan',
							    'nama_modul'  => 'Modul Penjualan');
        break;
		case 'cashin':
       		$modul_sess = array('modul'  => 'cashin',
							    'nama_modul'  => 'Modul Cash In');
        break;
		case 'cashout':
       		$modul_sess = array('modul'  => 'cashout',
							    'nama_modul'  => 'Modul Cash Out');
        break;
		case 'reportinv':
       		$modul_sess = array('modul'  => 'reportinv',
							    'nama_modul'  => 'Modul Laporan Investor');
        break;
		  default:
		  $modul_sess = array('modul'  => $this->session->userdata('modul'),
		  'nama_modul'  =>  $this->session->userdata('nama_modul'));
		break;
		case 'finance':
       		$modul_sess = array('modul'  => 'finance',
							    'nama_modul'  => 'Modul Keuangan');
		}
		$this->session->set_userdata($modul_sess);
		$data['show_dashboard'] = $modul_sess['modul'] === 'penjualan';
		if ($data['show_dashboard']) {
			$data['dashboard_rows'] = $this->mod_dashboard->summary();
			$data['dashboard_metrics'] = $this->mod_dashboard->metrics();
		}
		$this->load->view('home',$data);
	}
	
	function dashboard()
	{
		$data['title']= 'Dashboard Penjualan';
		$dashboard_modul = $this->uri->segment(3) ?: $this->session->userdata('modul');
		$data['dashboard_modul'] = in_array($dashboard_modul, array('penjualan', 'cashin'), TRUE) ? $dashboard_modul : 'penjualan';
		$data['title'] = $data['dashboard_modul'] === 'cashin' ? 'Dashboard Cash In' : 'Dashboard Penjualan';
		
		$list_proj = $this->mod_proj->list_proj_akt()->result();
		$data['list_proj'] = $list_proj;
		
		$this->load->view('dashboard',$data);
	}

	function dashboard_live_view()
	{
		$this->load->view('dashboard_live_view');
	}
	
	function dashboard_live()
	{
		$data['dashboard_rows'] = $this->mod_dashboard->summary();
		$data['dashboard_metrics'] = $this->mod_dashboard->metrics();
		$this->load->view('dashboard_table', $data);
	}

	function dashboard_detail($projid = NULL, $metric = 'total')
	{
		$metrics = $this->mod_dashboard->metrics();
		$project = $this->mod_proj->edit_proj($projid)->row();
		if (!$project || !isset($metrics[$metric])) { show_404(); return; }
		if (in_array($metric, array('batal', 'spr'), TRUE)) {
			redirect('spr/do_search_spr/' . rawurlencode($projid) . '?status=' . ($metric === 'batal' ? 'batal' : 'proses'));
			return;
		}
		$cash = in_array($metric, array('jatuh_tempo', 'uang_masuk'), TRUE);
		$this->lib->check_auth($this->session->userdata('roleid'), $cash ? 'CI' : 'PJ', $cash ? 'T001' : 'M002', 'R');
		$payment_status = (string) $this->input->get('status_pencairan');
		$payment_status = in_array($payment_status, array('lunas', 'belum'), TRUE) ? $payment_status : '';
		if (in_array($metric, array('subsidi','komersial'), TRUE)) {
			$this->load->view('list_akad', array('title'=>$project->namproj.' — '.$metrics[$metric], 'projid'=>$projid, 'metric'=>$metric, 'payment_status'=>$payment_status));
			return;
		}
		$total = $this->mod_dashboard->detail_count($projid, $metric, $payment_status);
		$pages = max(1, (int) ceil($total / 25));
		$page = min($pages, max(1, (int) $this->input->get('page')));
		$data = array('title' => $project->namproj . ' — ' . $metrics[$metric], 'metric' => $metric, 'projid' => $projid,
			'total' => $total, 'pages' => $pages, 'page' => $page, 'offset' => ($page - 1) * 25);
		$data['payment_status'] = $payment_status;
		$data['rows'] = $this->mod_dashboard->details($projid, $metric, $data['offset'], $payment_status);
		$this->load->view(in_array($metric, array('subsidi', 'komersial'), TRUE) ? 'list_akad' : 'dashboard_detail', $data);
	}

	function akad_data($projid = NULL, $metric = 'subsidi')
	{
		$this->lib->check_auth($this->session->userdata('roleid'),'PJ','M002','R');
        $project=$this->mod_proj->edit_proj($projid)->row();
        if (!in_array($metric,array('subsidi','komersial','akad'),TRUE) || !$project) { show_404(); return; }
		$status=(string)$this->input->get('status_pencairan');
		$status=in_array($status,array('lunas','belum'),TRUE)?$status:'';
		$search=$this->input->get('search');
		$search=is_array($search)&&isset($search['value'])?trim((string)$search['value']):'';
		$start=max(0,(int)$this->input->get('start'));
		$length=(int)$this->input->get('length');
		$length=in_array($length,array(10,25,50,100),TRUE)?$length:10;
		$order=$this->input->get('order');
		$columns=array('h.sprno','d.lot','d.blok','h.nama');
		$column=isset($order[0]['column'],$columns[(int)$order[0]['column']])?$columns[(int)$order[0]['column']]:'d.blok';
		$direction=isset($order[0]['dir'])&&$order[0]['dir']==='desc'?'DESC':'ASC';
		$total=$this->mod_dashboard->detail_count($projid,$metric);
		$filtered=($status===''&&$search==='')?$total:$this->mod_dashboard->detail_count($projid,$metric,$status,$search);
		$rows=$this->mod_dashboard->details($projid,$metric,$start,$status,$length,$search,$column,$direction);
		foreach ($rows as $row) {
			$row->plafon=$row->plafon_pencairan!==NULL?$row->plafon_pencairan:$row->kpr;
			$row->potongan_kpp=(float)$project->kkp;
			$row->sisa=max(0,(float)$row->plafon-(float)$row->potongan_kpp-(float)$row->jumlah_pencairan);
			foreach (array('potongan_kpp','jumlah_pencairan','total_retensi') as $amount_field) $row->$amount_field=(float)$row->$amount_field;
			$row->detail_url=$row->sprno!==NULL?site_url('akad/detail/'.(int)$projid.'/'.(int)$row->sprno):site_url('kav/edit_kav/'.(int)$projid.'/'.rawurlencode($row->blok).'/'.rawurlencode($row->no));
		}
		$this->output->set_content_type('application/json')->set_output(json_encode(array('draw'=>(int)$this->input->get('draw'),'recordsTotal'=>$total,'recordsFiltered'=>$filtered,'data'=>$rows)));
	}

}
