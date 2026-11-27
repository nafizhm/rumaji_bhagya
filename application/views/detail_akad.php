<?php $this->load->view('header'); $idr=function($value){return 'Rp '.number_format((float)$value,0,',','.');}; ?>
<link rel="stylesheet" href="<?php echo $this->config->item('assets'); ?>/css/akad-detail.css?v=1">
<div class="right_col" role="main">
 <div class="akad-detail-heading"><div><p class="text-muted">AKAD / <?php echo html_escape($akad->namproj); ?></p><h3>Detail Akad</h3><p><?php echo html_escape($akad->nama); ?> · Blok <?php echo html_escape($akad->blok.'/'.$akad->no); ?> · SPR <?php echo $this->lib->no_spr($akad->sprno); ?></p></div><div><a class="btn btn-default" href="<?php echo $back_url; ?>">Kembali ke daftar akad</a><button type="button" class="btn btn-success open-pencairan" data-projid="<?php echo (int)$akad->projid; ?>" data-sprno="<?php echo (int)$akad->sprno; ?>"><i class="fa fa-money" aria-hidden="true"></i> Pencairan</button></div></div>
 <div class="akad-summary-grid">
  <?php foreach (array('Plafon'=>$plafon,'Potongan KMK'=>$kpp,'Sudah Dicairkan'=>$cair,'Retensi'=>$retensi,'Sisa Pencairan'=>$sisa) as $label=>$amount): ?>
  <div class="x_panel akad-summary-card"><span class="text-muted"><?php echo $label; ?></span><strong class="<?php echo $label==='Sisa Pencairan'&&$sisa>0?'text-danger':''; ?>"><?php echo $idr($amount); ?></strong></div>
  <?php endforeach; ?>
 </div>
 <div class="row">
  <div class="col-md-5"><section class="x_panel"><div class="x_title"><h2>Informasi Akad</h2><div class="clearfix"></div></div><div class="x_content">
   <dl class="akad-info">
    <?php foreach (array('Proyek'=>$akad->namproj,'Jenis Hunian'=>$akad->jenis,'Lot'=>$akad->lot,'Blok / No'=>$akad->blok.' / '.$akad->no,'Konsumen'=>$akad->nama,'Telepon'=>$akad->telp,'Alamat'=>$akad->alm,'Bank'=>$akad->nambank,'No. Rekening'=>$akad->accno,'Atas Nama'=>$akad->namacc) as $label=>$value): ?>
    <dt><?php echo html_escape($label); ?></dt><dd><?php echo html_escape($value!==NULL&&$value!==''?$value:'—'); ?></dd>
    <?php endforeach; ?>
   </dl>
  </div></section></div>
  <div class="col-md-7"><section class="x_panel"><div class="x_title"><h2>Grafik Pencairan</h2><div class="clearfix"></div></div><div class="x_content">
   <p class="text-muted">Komposisi plafon: pencairan bersih, potongan KMK, dan sisa yang belum dicairkan.</p>
   <?php $segments=array(array('label'=>'Sudah Dicairkan','value'=>$cair,'class'=>'cair'),array('label'=>'Potongan KMK','value'=>$kpp,'class'=>'kpp'),array('label'=>$history?'Retensi / Sisa Pencairan':'Belum Dicairkan','value'=>$sisa,'class'=>'sisa')); ?>
   <div class="akad-chart" role="img" aria-label="<?php echo html_escape('Plafon '.$idr($plafon).', dicairkan '.$idr($cair).', KMK '.$idr($kpp).', sisa '.$idr($sisa)); ?>">
    <?php foreach ($segments as $segment): $percentage=$plafon>0?max(0,min(100,$segment['value']/$plafon*100)):0; ?>
     <div class="akad-chart-segment <?php echo $segment['class']; ?>" style="width:<?php echo $percentage; ?>%" title="<?php echo html_escape($segment['label'].': '.$idr($segment['value'])); ?>"></div>
    <?php endforeach; ?>
   </div>
   <table class="table"><thead><tr><th>Komponen</th><th class="text-right">Jumlah</th><th class="text-right">% Plafon</th></tr></thead><tbody>
    <?php foreach ($segments as $segment): ?><tr><td><span class="akad-chart-key <?php echo $segment['class']; ?>" aria-hidden="true"></span><?php echo $segment['label']; ?></td><td class="text-right"><?php echo $idr($segment['value']); ?></td><td class="text-right"><?php echo $plafon>0?number_format($segment['value']/$plafon*100,1,',','.'):0; ?>%</td></tr><?php endforeach; ?>
   </tbody></table>
   <?php if (!$history): ?><div class="alert alert-info">Belum ada pencairan yang dicatat untuk akad ini.</div><?php endif; ?>
  </div></section></div>
 </div>
 <section class="x_panel"><div class="x_title"><h2>Histori Pencairan Bank</h2><div class="clearfix"></div></div><div class="x_content"><div class="table-responsive">
  <table class="table table-bordered table-striped"><thead><tr><th>Tanggal</th><th>Plafon</th><th>KMK</th><th>Rincian Retensi</th><th>Pencairan Bersih</th><th>Dicatat Oleh</th></tr></thead><tbody>
   <?php foreach ($history as $item): ?><tr><td><?php echo html_escape(!empty($item->tanggal_pembayaran) ? $item->tanggal_pembayaran : $item->created_at); ?></td><td><?php echo $idr($item->plafon); ?></td><td><?php echo $idr($item->potongan_kpp); ?></td><td><?php foreach ($item->retensi as $line): ?><div><?php echo html_escape($line->nama_retensi); ?>: <?php echo $idr($line->nilai); ?></div><?php endforeach; ?><strong>Total: <?php echo $idr($item->total_retensi); ?></strong></td><td><?php echo $idr($item->jumlah); ?></td><td><?php echo html_escape($item->created_by); ?></td></tr><?php endforeach; ?>
   <?php if (!$history): ?><tr><td colspan="6" class="text-center">Belum ada histori pencairan.</td></tr><?php endif; ?>
  </tbody></table>
 </div></div></section>
 <section class="x_panel"><div class="x_title"><h2>Rincian Pembayaran Konsumen <small>Total dibayar: <?php echo $idr($dibayar); ?></small></h2><div class="clearfix"></div></div><div class="x_content">
  <p class="text-muted">Pembayaran konsumen mengikuti kwitansi aktif pada SPR. Angka ini terpisah dari pencairan bank.</p>
  <ul class="nav nav-tabs" role="tablist"><li class="active" role="presentation"><a href="#akad-payments" role="tab" data-toggle="tab">Histori Pembayaran</a></li><li role="presentation"><a href="#akad-schedule" role="tab" data-toggle="tab">Jadwal Pembayaran</a></li></ul>
  <div class="tab-content" style="padding-top:20px;">
   <div id="akad-payments" class="tab-pane active" role="tabpanel"><div class="table-responsive"><table id="akad-payments-table" class="table table-bordered table-striped"><thead><tr><th>No. Kwitansi</th><th>Tanggal</th><th>Jenis Pembayaran</th><th>Angsuran</th><th>Jumlah</th><th>Keterangan</th><th>Pilihan</th></tr></thead><tbody>
    <?php foreach ($payments as $payment): ?><tr><td><?php echo $this->lib->no_kwt($payment->kwtno); ?></td><td><?php echo html_escape($payment->tgl); ?></td><td><?php echo html_escape($this->lib->tipe_byr_txt($payment->tipe)); ?></td><td><?php echo (int)$payment->ang; ?></td><td class="text-right" data-order="<?php echo (float)$payment->total; ?>"><?php echo $idr($payment->total); ?></td><td><?php echo html_escape($payment->ket); ?></td><td><a class="btn btn-default btn-sm" target="_blank" rel="noopener" href="<?php echo site_url('byr/cetak_kwt/'.$akad->projid.'/'.$payment->kwtno.'/'.$akad->sprno); ?>">Cetak Kwitansi</a></td></tr><?php endforeach; ?>
   </tbody></table></div></div>
   <div id="akad-schedule" class="tab-pane" role="tabpanel"><div class="table-responsive"><table id="akad-schedule-table" class="table table-bordered table-striped"><thead><tr><th>Jatuh Tempo</th><th>Jenis Pembayaran</th><th>Angsuran</th><th>Tagihan</th><th>Dibayar</th><th>Sisa</th><th>Status</th></tr></thead><tbody>
    <?php foreach ($schedule as $line): $outstanding=max(0,(float)$line->total-(float)$line->dibayar); ?><tr><td><?php echo html_escape($line->jtmp); ?></td><td><?php echo html_escape($this->lib->tipe_byr_txt($line->tipe)); ?></td><td><?php echo (int)$line->ang; ?></td><td class="text-right"><?php echo $idr($line->total); ?></td><td class="text-right"><?php echo $idr($line->dibayar); ?></td><td class="text-right <?php echo $outstanding>0?'text-danger':''; ?>"><?php echo $idr($outstanding); ?></td><td><?php echo $outstanding>0?'Belum Lunas':'Lunas'; ?></td></tr><?php endforeach; ?>
   </tbody></table></div></div>
  </div>
 </div></section>
</div>
<?php $this->load->view('modal_pencairan'); $this->load->view('footer'); ?>
<script src="<?php echo $this->config->item('assets'); ?>/js/pencairan.js?v=12"></script>
<script>
 (function($){var options={pageLength:25,order:[],language:{search:'Cari:',emptyTable:'Belum ada data pembayaran.',info:'Menampilkan _START_–_END_ dari _TOTAL_ data',infoEmpty:'Belum ada data',lengthMenu:'Tampilkan _MENU_ data',paginate:{next:'Berikutnya',previous:'Sebelumnya'}}};$('#akad-payments-table,#akad-schedule-table').DataTable(options);$('a[data-toggle="tab"]').on('shown.bs.tab',function(){$.fn.dataTable.tables({visible:true,api:true}).columns.adjust();});}(jQuery));
</script>
