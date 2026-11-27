<?php $this->load->view('header'); ?>
<div class="right_col" role="main"><h3><?php echo $archived ? 'Arsip Booking' : 'Booking'; ?></h3>
<?php if ($message=$this->session->flashdata('booking_message')): ?><div class="alert alert-success"><?php echo html_escape($message); ?></div><?php endif; ?><div class="x_panel"><div class="x_title"><h2><?php echo $archived ? 'Booking yang Dihapus' : 'Pengajuan Booking Masuk'; ?></h2><a class="btn btn-default pull-right" href="<?php echo site_url($archived?'booking/admin':'booking/arsip'); ?>"><?php echo $archived?'Kembali ke Booking':'Arsip'; ?></a><a class="btn btn-primary pull-right" target="_blank" rel="noopener" href="<?php echo site_url('booking'); ?>">Buka Form Booking</a><div class="clearfix"></div></div><div class="x_content"><div class="table-responsive"><table id="booking-table" class="table table-striped table-bordered"><thead><tr><th>Tanggal / No. Registrasi</th><th>Pemohon</th><th>No. Telp</th><th>Proyek</th><th>Blok/No</th><th>Marketing</th><th>Booking Fee</th><th>Status</th><th>Pilihan</th></tr></thead><tbody></tbody></table></div></div></div></div>
<?php if ($this->session->flashdata('booking_verified')): ?>
<div class="modal fade" id="booking-verified" tabindex="-1" role="dialog" aria-labelledby="booking-verified-title">
 <div class="modal-dialog modal-sm" role="document" style="margin-top:15vh"><div class="modal-content" style="border:0;border-radius:16px;overflow:hidden;box-shadow:0 16px 48px rgba(0,0,0,.2)">
  <div class="modal-body text-center" style="padding:32px 24px">
   <div aria-hidden="true" style="width:72px;height:72px;margin:0 auto 20px;border-radius:50%;background:#e8f7ef;color:#239b65;font-size:38px;line-height:72px">&#10003;</div>
   <h4 id="booking-verified-title" style="font-size:22px;color:#263b4d;margin-bottom:12px">Verifikasi Berhasil</h4>
   <p style="color:#657786;line-height:1.6;margin-bottom:24px">Verifikasi booking berhasil disimpan.</p>
   <button type="button" class="btn btn-success btn-block" data-dismiss="modal" style="border-radius:8px;padding:10px">Kembali ke Daftar Booking</button>
  </div>
 </div></div>
</div>
<?php endif; ?>
<?php $this->load->view('footer'); ?>
<script>
if (document.getElementById('booking-verified')) { jQuery('#booking-verified').modal('show'); }
(function($){$('#booking-table').on('submit','.booking-delete-form',function(event){
 var message=<?php echo json_encode($archived?'Hapus permanen booking ini? Data tidak dapat dikembalikan.':'Pindahkan booking ini ke arsip?'); ?>;
 if(!window.confirm(message)) event.preventDefault();
});var safe=$.fn.dataTable.render.text();$('#booking-table').DataTable({serverSide:true,processing:true,pageLength:10,ordering:false,searchDelay:350,ajax:<?php echo json_encode(site_url('booking/data').($archived?'?arsip=1':'')); ?>,columns:[{data:'no_registrasi',render:function(value,type,row){if(type!=='display')return value;var date=String(row.tanggal||'').split('-').reverse().join('/');return '<div>'+safe.display(date)+'</div><small class="text-muted">'+safe.display(value)+'</small>';}},{data:'nama_lengkap',render:safe},{data:'no_telp',render:safe},{data:'nama_proyek',render:safe},{data:null,render:function(data,type,row){return $('<div>').text(row.blok+'/'+row.no).html();}},{data:'marketing',render:safe},{data:'booking_fee',render:function(value){return 'Rp '+Number(value).toLocaleString('id-ID');}},{data:'status',render:function(value,type){if(type!=='display')return value;var colors={Pending:'#d99816',Disetujui:'#239b65',Ditolak:'#d9534f',SPR:'#337ab7'};return '<span class="badge" style="background:'+(colors[value]||'#777')+';padding:6px 10px">'+safe.display(value)+'</span>';}},{data:'id',render:function(id,type,row){
 if(type!=='display') return id;
 var archived=<?php echo $archived?'true':'false'; ?>;
 var buttons='<a class="btn btn-primary btn-sm" href="'+<?php echo json_encode(site_url('booking/detail')); ?>+'/'+Number(id)+'">'+(archived?'Detail':'Verifikasi')+'</a>';
 if(!archived && row.status!=='SPR') buttons+=' <a class="btn btn-success btn-sm" href="'+<?php echo json_encode(site_url('spr/input_spr')); ?>+'?booking_id='+Number(id)+'">Buat SPR</a>';
 <?php if ($can_delete): ?>
 buttons+=' <form method="post" class="booking-delete-form" style="display:inline" action="'+<?php echo json_encode(site_url($archived?'booking/purge':'booking/delete')); ?>+'/'+Number(id)+'"><input type="hidden" name="token" value="'+<?php echo json_encode($this->session->userdata('booking_delete_token')); ?>+'"><button type="submit" class="btn btn-danger btn-sm">'+(archived?'Hapus Permanen':'Hapus')+'</button></form>';
 <?php endif; ?>
 return buttons;
}}],language:{processing:'Memuat…',search:'Cari:',lengthMenu:'Tampilkan _MENU_ data',info:'Menampilkan _START_–_END_ dari _TOTAL_ booking',infoEmpty:'Belum ada booking',emptyTable:'Belum ada pengajuan booking.',zeroRecords:'Booking tidak ditemukan.',paginate:{previous:'Sebelumnya',next:'Berikutnya'}}});}(jQuery));
</script>
