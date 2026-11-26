<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siteplan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->lib->check_login();
        $this->lib->check_auth($this->session->userdata('roleid'),'PJ','M002','R');
        $this->load->model('mod_siteplan');
    }

    public function index()
    {
        $projects=$this->mod_siteplan->projects();
        $requested=$this->input->get('projid');$projid=(int)$requested;$project=NULL;
        if ($requested===NULL) foreach ($projects as $item) if ($item->has_siteplan!==NULL) { $projid=(int)$item->projid;break; }
        foreach ($projects as $item) if ((int)$item->projid===$projid) $project=$item;
        if ($projid && !$project) { show_404();return; }
        $template=$project?$this->mod_siteplan->template($projid):NULL;
        $units=$template?$this->mod_siteplan->units($projid):array();
        $legend=array('tersedia'=>array('Tersedia','#ffffff'),'booking'=>array('Booking','#f6cc5c'),
            'spr'=>array('Proses SPR','#6db4ee'),'akad'=>array('Sudah Akad','#76cb9a'),'batal'=>array('Batal','#ef9a9a'));
        $counts=array_fill_keys(array_keys($legend),0);
        foreach ($units as $unit) {
            $counts[$unit->state]++;
            $unit->stock_url=site_url('kav/edit_kav/'.$projid.'/'.rawurlencode($unit->blok).'/'.rawurlencode($unit->no));
            $unit->spr_url=$unit->sprno!==NULL?site_url('spr/edit_spr/'.$unit->sprno.'/'.$projid):NULL;
        }
        $this->load->view('siteplan',array('title'=>'Siteplan','projects'=>$projects,'project'=>$project,'projid'=>$projid,
            'template'=>$template,'units'=>$units,'legend'=>$legend,'counts'=>$counts));
    }
}
