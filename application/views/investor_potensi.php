<?php $this->load->view('header');
$money=function($v) { return $v===NULL?'Belum ada harga':'Rp '.number_format($v,0,',','.'); };
$totals=array();foreach(array('penerimaan','pengeluaran') as $kind) $totals[$kind]=array('cutoff'=>0,'real'=>0,'missing_cutoff'=>0,'missing_real'=>0);
foreach($rows as $r) foreach(array('cutoff','real') as $col) { if($r[$col]===NULL) $totals[$r['jenis']]['missing_'.$col]++;else $totals[$r['jenis']][$col]+=$r[$col]; }
$delta=function($old,$new) use($money) { return $old===NULL || $new===NULL?'—':($new-$old>0?'+':'').$money($new-$old); };
?>
<div class="right_col" role="main">
 <h3>Laporan Kontrol Potensi</h3>
 <?php if($message=$this->session->flashdata('investor_message')): ?><div class="alert alert-success"><?php echo html_escape($message); ?></div><?php endif; ?>
 <div class="x_panel"><div class="x_content">
  <form method="get" action="<?php echo site_url('investor_potensi'); ?>" class="form-inline"><label for="investor-project">Proyek</label> <select id="investor-project" name="projid" class="form-control"><?php foreach($projects as $p): ?><option value="<?php echo (int)$p->projid; ?>" <?php echo $p->projid==$project->projid?'selected':''; ?>><?php echo html_escape($p->namproj); ?></option><?php endforeach; ?></select> <button class="btn btn-primary" type="submit">Tampilkan</button></form>
  <p style="margin-top:15px">Cut off otomatis: <strong><?php echo html_escape(date('d/m/Y H:i:s',strtotime($baseline->captured_at))); ?> WIB</strong> oleh <?php echo html_escape($baseline->captured_by); ?>. Nilai acuan tetap; kolom Real mengikuti kondisi terbaru.</p>
  <p class="text-muted">Real adalah potensi penjualan dan rencana pengeluaran terbaru, bukan realisasi uang masuk atau pembayaran. Penerimaan mencakup harga jual bersih, fitur/tambahan bangunan dan biaya lain/titipan.</p>
  <?php if($totals['penerimaan']['missing_cutoff'] || $totals['penerimaan']['missing_real']): ?><div class="alert alert-warning">Total penerimaan masih parsial. Ada <?php echo $totals['penerimaan']['missing_cutoff']; ?> unit tanpa harga saat cut off dan <?php echo $totals['penerimaan']['missing_real']; ?> unit tanpa harga saat ini. Nilai yang belum ada tidak dianggap nol; selisih total penerimaan belum dapat dipastikan.</div><?php endif; ?>
  <?php if ($can_update && $can_delete && $this->mod_investor_potensi->permission('c')): ?>
  <div style="margin-bottom:15px">
   <?php if ($totals['penerimaan']['missing_cutoff'] && !$totals['penerimaan']['missing_real']): ?><p>Harga terbaru sudah lengkap. Ambil cut off baru untuk menggunakan data lengkap sebagai acuan perbandingan.</p><?php endif; ?>
   <?php echo form_open('investor_potensi/manage/'.(int)$baseline->id,array('onsubmit'=>"return confirm('Arsipkan cut off lama dan gunakan kondisi terbaru sebagai acuan? Selisih akan dihitung mulai dari cut off baru.');")); ?>
   <input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('investor_token')); ?>">
   <input type="hidden" name="action" value="reset">
   <button type="submit" class="btn btn-primary">Ambil Cut Off Baru</button>
   <span class="text-muted">Acuan lama diarsipkan; acuan baru mengikuti kondisi saat tombol diklik.</span>
   <?php echo form_close(); ?>
  </div>
  <?php endif; ?>
  <div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Komponen</th><th class="text-right">Potensi Cut Off</th><th class="text-right">Real / Potensi Terbaru</th><th class="text-right">Selisih terhadap Cut Off</th></tr></thead><tbody>
  <?php foreach(array('penerimaan'=>'Potensi Penerimaan Penjualan','pengeluaran'=>'Potensi Pengeluaran / Rencana Anggaran') as $kind=>$label): $t=$totals[$kind];$missing=$t['missing_cutoff']||$t['missing_real']; ?>
  <tr><th><?php echo $label; ?></th><td class="text-right"><?php echo $money($t['cutoff']).($t['missing_cutoff']?' (parsial)':''); ?></td><td class="text-right"><?php echo $money($t['real']).($t['missing_real']?' (parsial)':''); ?></td><td class="text-right"><?php echo $missing?'Belum lengkap':$delta($t['cutoff'],$t['real']); ?></td></tr>
  <?php endforeach; $in=$totals['penerimaan'];$out=$totals['pengeluaran'];$missing=$in['missing_cutoff']||$in['missing_real']; ?>
  <tr class="info"><th>Perkiraan Selisih Penerimaan − Pengeluaran</th><td class="text-right"><?php echo $in['missing_cutoff']?'Belum lengkap':$money($in['cutoff']-$out['cutoff']); ?></td><td class="text-right"><?php echo $in['missing_real']?'Belum lengkap':$money($in['real']-$out['real']); ?></td><td class="text-right"><?php echo $missing?'Belum lengkap':$delta($in['cutoff']-$out['cutoff'],$in['real']-$out['real']); ?></td></tr>
  </tbody></table></div>
 </div></div>
</div>
<?php $this->load->view('footer'); ?>
