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

    public function cetak()
    {
        $this->index(TRUE);
    }

    public function index($print=FALSE)
    {
        $projects=$this->mod_siteplan->projects();
        $requested=$this->input->get('projid');$projid=(int)$requested;$project=NULL;
        if ($requested===NULL) foreach ($projects as $item) if ($item->has_siteplan!==NULL) { $projid=(int)$item->projid;break; }
        foreach ($projects as $item) if ((int)$item->projid===$projid) $project=$item;
        if ($projid && !$project) { show_404();return; }
        $template=$project?$this->mod_siteplan->template($projid):NULL;
        $this->load->model('mod_release_inventory');
        $lots=$this->mod_release_inventory->lots($projid);
        $lotid=(int)$this->input->get('lot_release_id');
        $selectedLot=NULL;foreach ($lots as $lot) if ((int)$lot->id===$lotid) $selectedLot=$lot;
        $units=$template?$this->mod_siteplan->units($projid,$lotid):array();
        $this->load->model('mod_siteplan_status');
        $legend=$this->mod_siteplan_status->legend();
        foreach ($legend as $state=>&$item) {
            if ($state==='tersedia' || !preg_match('/^#[0-9a-f]{6}$/i',$item[1])) continue;
            $rgb=sscanf($item[1],'#%02x%02x%02x');
            $item[1]=sprintf('#%02x%02x%02x',(int)round($rgb[0]*0.65),(int)round($rgb[1]*0.65),(int)round($rgb[2]*0.65));
        }
        unset($item);
        $counts=array_fill_keys(array_keys($legend),0);
        foreach ($units as $unit) {
            $counts[$unit->state]++;
            $unit->stock_url=site_url('kav/edit_kav/'.$projid.'/'.rawurlencode($unit->blok).'/'.rawurlencode($unit->no));
            $unit->spr_url=$unit->sprno!==NULL?site_url('spr/edit_spr/'.$unit->sprno.'/'.$projid):NULL;
        }
        if ($print) {
            if (!$template) { show_error('Siteplan untuk proyek ini belum tersedia.',404);return; }
            $svg=$template->header_svg;
            foreach ($units as $unit) {
                $label=$unit->state==='tersedia'?$unit->label_svg:preg_replace('/<text\b/','<text style="fill:#fff !important"',$unit->label_svg);
                $svg.=str_replace('[[warna]]',$legend[$unit->state][1],$unit->shape_svg).$label;
            }
            $svg.=$template->footer_svg;
            $this->load->view('cetak_siteplan',array('project'=>$project,'legend'=>$legend,'counts'=>$counts,'lot'=>$selectedLot,'svg'=>$svg));
            return;
        }
        $this->load->view('siteplan',array('title'=>'Siteplan','projects'=>$projects,'project'=>$project,'projid'=>$projid,
            'template'=>$template,'units'=>$units,'legend'=>$legend,'counts'=>$counts,'lots'=>$lots,'lotid'=>$lotid,'selectedLot'=>$selectedLot,'publicView'=>FALSE,'formAction'=>site_url('siteplan')));
    }
}
