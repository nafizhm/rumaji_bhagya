<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mod_dashboard extends CI_Model
{
    public function metrics()
    {
        return array('total' => 'Total Unit', 'belum_laku' => 'Belum Laku', 'batal' => 'Batal', 'spr' => 'SPR', 'subsidi' => 'Akad Subsidi', 'komersial' => 'Akad Komersial', 'jatuh_tempo' => 'Jatuh Tempo', 'uang_masuk' => 'Uang Masuk');
    }

    public function summary()
    {
        return $this->db->query("SELECT p.projid, p.namproj,
            COALESCE(k.total,0) total, COALESCE(k.belum_laku,0) belum_laku,
            COALESCE(k.batal,0) batal, COALESCE(k.spr,0) spr,
            COALESCE(k.subsidi,0) subsidi, COALESCE(k.komersial,0) komersial,
            COALESCE(j.total,0) jatuh_tempo, COALESCE(u.total,0) uang_masuk
            FROM mproj p
            LEFT JOIN (SELECT projid, COUNT(*) total, SUM(status='BLA') belum_laku,
                SUM(status='BTL') batal, SUM(status NOT IN ('BTL','AKD','BLA','AKS')) spr,
                SUM(jenis <> 'Komersial' AND status IN ('AKD','AKS')) subsidi,
                SUM(jenis = 'Komersial' AND status IN ('AKD','AKS')) komersial
                FROM mkav GROUP BY projid) k ON k.projid=p.projid
            LEFT JOIN (SELECT d.projid, SUM(d.total) total FROM tsprp d
                INNER JOIN tsprh h ON h.projid=d.projid AND h.sprno=d.sprno
                WHERE h.status <> 'BTL' AND d.status='BLN' AND d.jtmp < ? GROUP BY d.projid) j ON j.projid=p.projid
            LEFT JOIN (SELECT projid, SUM(total) total FROM tkwt WHERE btl='' GROUP BY projid) u ON u.projid=p.projid
            WHERE p.status='AKT' ORDER BY p.projid ASC", array(date('Y-m-d')))->result();
    }

    private function detail_query($projid, $metric, $payment_status = '', $search = '')
    {
        if ($metric === 'jatuh_tempo') {
            $this->db->from('tsprp d')->join('tsprh h', 'h.projid=d.projid AND h.sprno=d.sprno');
            $this->db->where('d.projid', $projid)->where('h.status <>', 'BTL')->where('d.status', 'BLN')->where('d.jtmp <', date('Y-m-d'));
        } elseif ($metric === 'uang_masuk') {
            $this->db->from('tkwt d')->where('d.projid', $projid)->where('d.btl', '');
        } else {
            $this->db->from('mkav d')->where('d.projid', $projid);
            if ($metric === 'belum_laku') $this->db->where('d.status', 'BLA');
            if ($metric === 'batal') $this->db->where('d.status', 'BTL');
            if ($metric === 'spr') $this->db->where_not_in('d.status', array('BTL','AKD','BLA','AKS'));
            if (in_array($metric, array('subsidi','komersial','akad'), TRUE)) {
                $this->db->where_in('d.status', array('AKD','AKS'));
                if ($metric!=='akad') $this->db->where($metric === 'subsidi' ? 'd.jenis <>' : 'd.jenis', 'Komersial');
                $project_sql = $this->db->escape($projid);
                $this->db->join("(SELECT projid,blok,no,MAX(sprno) sprno FROM tsprh WHERE projid=".$project_sql." AND status <> 'BTL' GROUP BY projid,blok,no) latest", 'latest.projid=d.projid AND latest.blok=d.blok AND latest.no=d.no', 'left', FALSE);
                $this->db->join('tsprh h', 'h.projid=latest.projid AND h.blok=latest.blok AND h.no=latest.no AND h.sprno=latest.sprno', 'left');
                $this->db->join("(SELECT projid,sprno,MIN(id) id,MAX(plafon) plafon,SUM(potongan_kpp) potongan_kpp,SUM(jumlah) jumlah,GREATEST(0,MAX(plafon)-SUM(potongan_kpp)-SUM(jumlah)) total_retensi FROM tpencairan WHERE projid=".$project_sql." GROUP BY projid,sprno) cair",'cair.projid=h.projid AND cair.sprno=h.sprno','left',FALSE);
                $remaining = 'GREATEST(0, COALESCE(cair.plafon,h.kpr,0)-COALESCE(cair.potongan_kpp,0)-COALESCE(cair.jumlah,0))';
                if ($payment_status === 'lunas') $this->db->where('cair.id IS NOT NULL', NULL, FALSE)->where($remaining . ' = 0', NULL, FALSE);
                if ($payment_status === 'belum') $this->db->where('(cair.id IS NULL OR ' . $remaining . ' > 0)', NULL, FALSE);
                if ($search !== '') $this->db->group_start()->like('h.nama',$search)->or_like('h.sprno',$search)->or_like('d.lot',$search)->or_like('d.blok',$search)->or_like('d.no',$search)->group_end();
            }
        }
    }

    public function detail_count($projid, $metric, $payment_status = '', $search = '')
    {
        $this->detail_query($projid, $metric, $payment_status, $search);
        return $this->db->count_all_results();
    }

    public function details($projid, $metric, $offset, $payment_status = '', $limit = 25, $search = '', $order_column = 'd.blok', $direction = 'ASC')
    {
        $this->detail_query($projid, $metric, $payment_status, $search);
        if ($metric === 'jatuh_tempo') {
            $this->db->select('d.sprno, h.nama, d.tipe, d.ang, d.jtmp, d.total')->order_by('d.jtmp')->order_by('d.sprno')->order_by('d.tipe')->order_by('d.ang');
        } elseif ($metric === 'uang_masuk') {
            $this->db->select('d.kwtno, d.sprno, d.tgl, d.tipe, d.ang, d.total')->order_by('d.tgl', 'DESC')->order_by('d.kwtno', 'DESC');
        } else {
            if (in_array($metric, array('subsidi', 'komersial','akad'), TRUE)) {
                $this->db->select('h.sprno, h.nama, h.kpr, h.crby, h.crdat');
                $this->db->select('cair.plafon AS plafon_pencairan, cair.jumlah AS jumlah_pencairan, cair.total_retensi, cair.potongan_kpp');
            }
            $this->db->select('d.lot, d.blok, d.no, d.jenis, d.status')->order_by($order_column,$direction)->order_by('d.blok')->order_by('d.no');
        }
        return $this->db->limit($limit, $offset)->get()->result();
    }
}
