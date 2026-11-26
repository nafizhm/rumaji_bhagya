<?php $this->load->view('header'); ?>
<div class="right_col" role="main">
 <h3>Verifikasi Booking</h3><a class="btn btn-default" href="<?php echo site_url('booking/admin'); ?>">Kembali ke Booking</a>
 <div class="x_panel"><div class="x_title"><h2><?php echo html_escape($booking->no_registrasi); ?></h2><div class="clearfix"></div></div><div class="x_content">
 <div class="table-responsive"><table class="table table-striped table-bordered"><tbody>
 <?php foreach (array('Proyek'=>$booking->nama_proyek,'Blok / No'=>$booking->blok.'/'.$booking->no,'Marketing'=>$booking->marketing,'Jenis Perumahan'=>$booking->jenis_perumahan,'Jenis Pembelian'=>$booking->jenis_pembelian,'Total Harga'=>'Rp '.number_format($booking->total_harga,0,',','.'),'Booking Fee'=>'Rp '.number_format($booking->booking_fee,0,',','.'),'Status'=>$booking->status) as $label=>$value): ?><tr><th><?php echo html_escape($label); ?></th><td><?php echo html_escape($value); ?></td></tr><?php endforeach; ?>
 <?php foreach ($fields as $name=>$field): ?><tr><th><?php echo html_escape($field[0]); ?></th><td style="white-space:pre-wrap"><?php echo html_escape(isset($payload[$name])?$payload[$name]:''); ?></td></tr><?php endforeach; ?>
 <?php foreach (array('kontak_darurat_1'=>'No. Kontak Darurat','nama_pemilik_kontak_darurat_1'=>'Nama Pemilik Kontak Darurat','keterangan_kontak_darurat_1'=>'Hubungan dengan Pemohon') as $name=>$label): ?><tr><th><?php echo html_escape($label); ?></th><td><?php echo html_escape(isset($payload[$name])?$payload[$name]:''); ?></td></tr><?php endforeach; ?>
 </tbody></table></div>
 <h4>Lampiran Booking</h4><div class="table-responsive"><table class="table table-striped table-bordered"><thead><tr><th>No.</th><th>Lampiran</th><th>Pilihan</th></tr></thead><tbody>
 <?php $number=0; foreach ((array)$files as $key=>$file): $url=site_url('booking/file/'.$booking->id.'/'.rawurlencode($key)); ?>
 <tr><td><?php echo ++$number; ?></td><td><?php echo html_escape($file['nama']); ?></td><td><button type="button" class="btn btn-primary btn-sm booking-view" data-url="<?php echo html_escape($url.'?view=1'); ?>" data-title="<?php echo html_escape($file['nama']); ?>" data-mime="<?php echo html_escape($file['mime']); ?>">View</button> <a class="btn btn-default btn-sm" href="<?php echo html_escape($url); ?>">Download</a></td></tr>
 <?php endforeach; if (!$number): ?><tr><td colspan="3">Belum ada lampiran.</td></tr><?php endif; ?>
 </tbody></table></div>
 <?php if ($booking->status!=='SPR'): ?>
 <form action="<?php echo site_url('booking/verify/'.$booking->id); ?>" method="post" class="form-inline">
 <input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('booking_verify_token')); ?>">
 <label for="booking-status">Status Verifikasi</label> <select class="form-control" id="booking-status" name="status" required><?php foreach (array('Pending','Disetujui','Ditolak') as $status): ?><option value="<?php echo $status; ?>" <?php echo $booking->status===$status?'selected':''; ?>><?php echo $status; ?></option><?php endforeach; ?></select> <button class="btn btn-success" type="submit">Simpan Verifikasi</button>
 </form>
 <?php else: ?><div class="alert alert-info">Booking ini sudah dibuat SPR.</div><?php endif; ?>
 </div></div>
</div>
<div class="modal fade" id="booking-preview" tabindex="-1" role="dialog" aria-labelledby="booking-preview-title"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="booking-preview-title">Lampiran Booking</h4></div><div class="modal-body" id="booking-preview-content"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div></div></div></div>
<?php $this->load->view('footer'); ?>
<script>
(function($){$('.booking-view').on('click',function(){var button=$(this),body=$('#booking-preview-content').empty(),url=button.attr('data-url'),mime=button.attr('data-mime');$('#booking-preview-title').text(button.attr('data-title'));if(['image/jpeg','image/png','image/webp'].indexOf(mime)!==-1){$('<img>').attr({src:url,alt:button.attr('data-title')}).css({maxWidth:'100%',height:'auto',display:'block',margin:'auto'}).appendTo(body);}else if(mime==='application/pdf'){$('<iframe>').attr({src:url,title:button.attr('data-title')}).css({width:'100%',height:'70vh',border:0}).appendTo(body);}else{body.text('Pratinjau tidak tersedia untuk format ini. Gunakan tombol Download.');}$('#booking-preview').modal('show');});$('#booking-preview').on('hidden.bs.modal',function(){$('#booking-preview-content').empty();});}(jQuery));
</script>
