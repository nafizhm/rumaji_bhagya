<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mod_booking extends CI_Model
{
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
    public function attachments()
    {
        return array('foto_pemohon'=>'Foto Pemohon','foto_ktp'=>'Foto KTP','foto_npwp'=>'Foto NPWP','foto_kk'=>'Foto KK','foto_bpjs'=>'Foto BPJS','foto_ktp_p'=>'Foto KTP Pasangan','file_bukti'=>'Bukti Transfer Booking','file_sppr'=>'File SPPR');
    }
    public function units($projid)
    {
        return $this->db->query("SELECT k.blok,k.no,k.lot,k.jenis FROM mkav k JOIN mproj p ON p.projid=k.projid AND p.status='AKT' LEFT JOIN tbooking b ON b.projid=k.projid AND b.blok=k.blok AND b.no=k.no WHERE k.projid=? AND k.status='BLA' AND b.id IS NULL ORDER BY k.blok,k.no",array($projid))->result();
    }
    public function unit($projid,$blok,$no)
    {
        return $this->db->select('k.*,p.namproj')->from('mkav k')->join('mproj p',"p.projid=k.projid AND p.status='AKT'")->where(array('k.projid'=>$projid,'k.blok'=>$blok,'k.no'=>$no,'k.status'=>'BLA'))->get()->row();
    }
    public function price($unit)
    {
        return $this->db->where(array('projid'=>$unit->projid,'lot'=>$unit->lot,'tipe'=>$unit->tipe))->where('tgl <=',date('Y-m-d'))->order_by('tgl','DESC')->get('mhrg',1)->row();
    }
}
