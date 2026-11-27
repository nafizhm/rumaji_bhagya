<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_booking extends CI_Model
{
    public function allowed($action)
    {
        $column=strtolower($action);
        if (!in_array($column,array('c','r','u','d'),TRUE)) return FALSE;
        $permission=$this->db->get_where('mbooking_akses_user',array('userid'=>$this->session->userdata('userid')))->row();
        if ($permission) return (bool)$permission->$column;
        if ($this->session->userdata('roleid')==='ADM') return TRUE;
        $permission=$this->db->get_where('mauth',array('roleid'=>$this->session->userdata('roleid'),'moid'=>'PJ','mid'=>'T001'))->row();
        return $permission && (bool)$permission->$column;
    }

    public function remove($id,$permanent,$userid)
    {
        $this->db->trans_begin();
        $row=$this->db->query('SELECT * FROM tbooking WHERE id=? FOR UPDATE',array($id))->row();
        if (!$row || ($permanent ? !$row->deleted_at : $row->deleted_at)) {
            $this->db->trans_rollback();return FALSE;
        }
        if ($permanent) $this->db->where('id',$id)->delete('tbooking');
        else $this->db->where('id',$id)->update('tbooking',array('deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>$userid));
        if (!$this->db->trans_status()) { $this->db->trans_rollback();return FALSE; }
        $this->db->trans_commit();
        // Uploaded files can also be used by the original database and SPR documents.
        // Keep those shared assets; permanently deleting removes the booking record.
        return TRUE;
    }

    public function fields()
    {
        return array(
            'tanggal'=>array('Tanggal','date',TRUE), 'nama_lengkap'=>array('Nama Lengkap','text',TRUE),
            'nik'=>array('NIK','text',TRUE), 'tempat_lahir'=>array('Tempat Lahir','text',TRUE),
            'tgl_lahir'=>array('Tanggal Lahir','date',TRUE), 'no_telp'=>array('No. Telp / WA','tel',TRUE),
            'jenis_kelamin'=>array('Jenis Kelamin',array('Laki-laki','Perempuan'),TRUE),
            'email'=>array('Email','email',FALSE), 'npwp'=>array('NPWP','text',FALSE),
            'pekerjaan'=>array('Pekerjaan',array('Wiraswasta','Pegawai Swasta','ASN','TNI atau Polri','Karyawan BUMN','Karyawan','Buruh','Petani','Pedagang','Sopir','Guru/Dosen','Dokter','Ibu Rumah Tangga','Pensiunan','Pelajar/Mahasiswa','Freelancer','Lain-lain'),FALSE),
            'pekerjaan_lain'=>array('Pekerjaan Lainnya','text',FALSE), 'no_bpjs_kes'=>array('No. BPJS Kes','text',FALSE),
            'alamat_ktp'=>array('Alamat KTP','textarea',TRUE), 'alamat_domisili'=>array('Alamat Domisili','textarea',TRUE),
            'status_pernikahan'=>array('Status Pernikahan',array('Belum Menikah','Menikah','Cerai Hidup','Cerai Mati'),TRUE),
            'nama_p'=>array('Nama Pasangan','text',FALSE), 'nik_p'=>array('NIK Pasangan','text',FALSE)
        );
    }
    public function documents_for_spr($projid,$sprno)
    {
        $booking=$this->db->query("SELECT b.id,b.berkas,b.created_at,b.marketing FROM tbooking b
            INNER JOIN tsprh h ON h.projid=b.projid AND h.blok=b.blok AND h.no=b.no AND TRIM(h.ktp)=TRIM(b.nik)
            WHERE h.projid=? AND h.sprno=? AND h.status <> 'BTL' AND b.status='SPR' ORDER BY b.id DESC LIMIT 1",array($projid,$sprno))->row();
        $documents=array();
        if (!$booking) return $documents;
        $document_types=array('foto_ktp'=>'D01','foto_npwp'=>'D02','foto_kk'=>'D05');
        foreach ((array)json_decode($booking->berkas,TRUE) as $key=>$file) {
            if (!is_array($file) || empty($file['file']) || empty($file['nama'])) continue;
            $path=APPPATH.'uploads/booking/'.basename($file['file']);
            $documents[$key]=(object)array('key'=>$key,'nama'=>$file['nama'],'file'=>$file['file'],
                'mime'=>isset($file['mime'])?$file['mime']:'application/octet-stream','path'=>$path,
                'exists'=>is_file($path),'size'=>is_file($path)?round(filesize($path)/1024,2):0,
                'created_at'=>$booking->created_at,'marketing'=>$booking->marketing,
                'document_status'=>isset($document_types[$key])?$document_types[$key]:NULL);
        }
        return $documents;
    }

    public function attachments()
    {
        return array('foto_pemohon'=>'Foto Pemohon','foto_ktp'=>'Foto KTP','foto_npwp'=>'Foto NPWP','foto_kk'=>'Foto KK','foto_bpjs'=>'Foto BPJS','foto_ktp_p'=>'Foto KTP Pasangan','file_bukti'=>'Bukti Transfer Booking','file_sppr'=>'File SPPR');
    }
    public function lots($projid)
    {
        $this->load->model('mod_release_inventory');
        return $this->mod_release_inventory->lots($projid);
    }
    public function units($projid,$lotid=0)
    {
        $this->load->model('mod_release_inventory');
        return $this->mod_release_inventory->units($projid,$lotid);
    }
    public function unit($projid,$blok,$no,$lotid=0)
    {
        $this->load->model('mod_release_inventory');
        return $this->mod_release_inventory->unit($projid,$blok,$no,$lotid);
    }
    public function price($unit)
    {
        return (object)array('hrgstd'=>$unit->release_harga_jual,'byttp'=>$unit->release_biaya_lain);
    }
}
