<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akad extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lib->check_login();
        $this->load->model('mod_akad');
    }

    public function index()
    {
        $this->lib->check_auth($this->session->userdata('roleid'),'PJ','M002','R');
        $projects=$this->db->order_by('namproj')->get('mproj')->result();
        $projid=(int)$this->input->get('projid');
        if (!$projid && $projects) $projid=(int)$projects[0]->projid;
        $project=NULL;
        foreach ($projects as $item) if ((int)$item->projid===$projid) $project=$item;
        if ($projid && !$project) { show_404();return; }
        $metric=(string)$this->input->get('jenis');
        if (!in_array($metric,array('subsidi','komersial'),TRUE)) $metric='akad';
        $status=(string)$this->input->get('status_pencairan');
        if (!in_array($status,array('lunas','belum'),TRUE)) $status='';
        $this->load->view('list_akad',array('title'=>$project?$project->namproj.' — Customer Sudah Akad':'Customer Sudah Akad',
            'projid'=>$projid,'metric'=>$metric,'payment_status'=>$status,'akad_menu'=>TRUE,'projects'=>$projects));
    }

    public function retensi()
    {
        $this->lib->check_auth($this->session->userdata('roleid'),'PJ','M002','R');
        $this->lib->check_auth($this->session->userdata('roleid'),'CI','T001','R');
        $projects=$this->db->order_by('projid','ASC')->get('mproj')->result();
        $projid=(int)$this->input->get('projid');$project=NULL;
        foreach ($projects as $item) if ((int)$item->projid===$projid) $project=$item;
        if ($projid && !$project) { show_404();return; }
        $report=$projid?$this->mod_akad->retensi_report($projid):array('categories'=>array(),'rows'=>array());
        $status=(string)$this->input->get('status_retensi');
        if (!in_array($status,array('lunas','belum','belum_diisi'),TRUE)) $status='';
        if ($status!=='') $report['rows']=array_values(array_filter($report['rows'],function($row) use ($status) {
            if ($status==='belum_diisi') return $row->pencairan_id===NULL;
            if ($status==='lunas') return $row->retensi_lunas;
            return $row->pencairan_id!==NULL && !$row->retensi_lunas;
        }));
        $request_categories=array();
        foreach ($this->db->order_by('nama_retensi')->get('mretensi_kategori')->result() as $category) $request_categories[$category->id]=$category->nama_retensi;
        foreach ($this->db->select('kategori_id,nama_retensi')->distinct()->get('tpencairan_retensi')->result() as $category) if (!isset($request_categories[$category->kategori_id])) $request_categories[$category->kategori_id]=$category->nama_retensi;
        $this->load->view('list_retensi',array('title'=>$project?$project->namproj.' — List Retensi':'List Retensi',
            'projects'=>$projects,'projid'=>$projid,'categories'=>$report['categories'],'rows'=>$report['rows'],'status_retensi'=>$status,'request_categories'=>$request_categories));
    }

    public function ajukan_pencairan()
    {
        $this->lib->check_auth($this->session->userdata('roleid'),'PJ','M002','R');
        $this->lib->check_auth($this->session->userdata('roleid'),'CI','T001','R');
        $projid=(int)$this->input->get('projid');
        $project=$this->db->get_where('mproj',array('projid'=>$projid))->row();
        $selected=$this->input->get('kategori');
        if (!$project || !is_array($selected) || !$selected) { show_error('Pilih proyek dan minimal satu kategori retensi.',422);return; }
        foreach ($selected as $id) if (!is_scalar($id) || !ctype_digit((string)$id)) { show_error('Kategori retensi tidak valid.',422);return; }
        $report=$this->mod_akad->retensi_report($projid);
        $categories=array();
        foreach (array_unique($selected) as $id) {
            if (isset($report['categories'][$id])) $categories[$id]=$report['categories'][$id];
            else {
                $category=$this->db->get_where('tpencairan_retensi',array('kategori_id'=>(int)$id),1)->row();
                if (!$category) { show_error('Kategori retensi tidak valid.',422);return; }
                $categories[$id]=$category->nama_retensi;
            }
        }
        $rows=array_values(array_filter($report['rows'],function($row) use ($categories) {
            $total=0;
            foreach ($categories as $id=>$label) $total+=isset($row->balances[$id])?(float)$row->balances[$id]:0;
            return $total>0;
        }));
        $this->load->view('ajukan_pencairan',array('title'=>'Ajukan Pencairan Retensi','project'=>$project,'categories'=>$categories,'rows'=>$rows));
    }

    public function detail($projid=NULL, $sprno=NULL)
    {
        $this->lib->check_auth($this->session->userdata('roleid'),'PJ','M002','R');
        $this->lib->check_auth($this->session->userdata('roleid'),'CI','T001','R');
        $akad=$this->mod_akad->get((int)$projid,(int)$sprno);
        if (!$akad) { show_404(); return; }
        $history=$this->mod_akad->disbursements($akad->projid,$akad->sprno);
        $plafon=$history ? (float)$history[0]->plafon : (float)$akad->kpr;
        $project=$this->db->select('kkp')->get_where('mproj',array('projid'=>(int)$akad->projid))->row();
        $kpp=$project?(float)$project->kkp:0;$cair=0;$retensi=0;
        foreach ($history as $item) $cair+=(float)$item->jumlah;
        $retensi=max(0,$plafon-$kpp-$cair);
        $payments=$this->mod_akad->payments($akad->projid,$akad->sprno);
        $dibayar=0;
        foreach ($payments as $payment) $dibayar+=(float)$payment->total;
        $metric=$akad->jenis==='Komersial'?'komersial':'subsidi';
        $data=array('title'=>'Detail Akad — '.$akad->nama,'akad'=>$akad,'history'=>$history,
            'payments'=>$payments,'schedule'=>$this->mod_akad->schedule($akad->projid,$akad->sprno),
            'plafon'=>$plafon,'kpp'=>$kpp,'cair'=>$cair,'retensi'=>$retensi,'sisa'=>max(0,$plafon-$kpp-$cair),
            'dibayar'=>$dibayar,'back_url'=>site_url('home/dashboard_detail/'.$akad->projid.'/'.$metric));
        $this->load->view('detail_akad',$data);
    }
}
