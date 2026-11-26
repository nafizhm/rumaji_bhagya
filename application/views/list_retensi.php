<?php $this->load->view('header'); ?>
<style>
.retensi-heading{display:flex;align-items:center;justify-content:space-between;gap:20px}.retensi-heading h2{float:none;margin:0;white-space:normal;flex:1}.retensi-count{white-space:nowrap;color:#2a3f54}.retensi-count strong{font-size:30px;margin-right:8px}.retensi-filter{display:flex;align-items:center;gap:14px;margin:12px 0 24px}.retensi-filter label{margin:0}.retensi-filter select{max-width:460px;flex:1;border-radius:5px;height:38px}#retensi-table th{white-space:nowrap}#retensi-table tfoot{background:#edf4f8;font-weight:bold}#retensi-table .retensi-amount{white-space:nowrap;text-align:right}@media(max-width:767px){.retensi-filter{align-items:stretch;flex-direction:column}.retensi-filter select{max-width:none;flex:auto}.retensi-heading h2{font-size:17px}}
</style>
<div class="right_col" role="main">
 <div class="page-title"><div class="title_left"><h3>List Retensi</h3></div></div><div class="clearfix"></div>
 <div class="x_panel">
  <div class="x_title retensi-heading"><h2><?php echo html_escape($title); ?></h2><div class="retensi-count" role="status"><strong><?php echo count($rows); ?></strong>unit</div></div>
  <div class="x_content">
   <form method="get" action="<?php echo site_url('akad/retensi'); ?>" class="retensi-filter">
    <label for="retensi-project">Lokasi Proyek</label>
    <select id="retensi-project" name="projid" class="form-control" onchange="this.form.submit()">
     <option value="">Pilih lokasi proyek</option>
     <?php foreach ($projects as $project): ?><option value="<?php echo (int)$project->projid; ?>" <?php echo (int)$project->projid===(int)$projid?'selected':''; ?>><?php echo html_escape($project->namproj); ?></option><?php endforeach; ?>
    </select>
    <label for="retensi-status">Status Retensi</label>
    <select id="retensi-status" name="status_retensi" class="form-control" onchange="this.form.submit()">
     <option value="">Semua</option><option value="lunas" <?php echo $status_retensi==='lunas'?'selected':''; ?>>Sudah Lunas</option><option value="belum" <?php echo $status_retensi==='belum'?'selected':''; ?>>Belum Lunas</option><option value="belum_diisi" <?php echo $status_retensi==='belum_diisi'?'selected':''; ?>>Belum Diisi</option>
    </select>
    <button type="button" class="btn btn-success" style="margin:0;white-space:nowrap" data-toggle="modal" data-target="#ajukan-pencairan-modal"><i class="fa fa-money"></i> Ajukan Pencairan</button>
   </form>
   <?php if (!$projid): ?>
   <div class="alert alert-info">Pilih lokasi proyek untuk menampilkan data retensi.</div>
   <?php else: ?>
   <div class="table-responsive"><table id="retensi-table" class="table table-striped table-bordered table-hover">
    <thead><tr><th>No SPR</th><th>Lot</th><th>Blok/No</th><th>Konsumen</th><?php foreach ($categories as $label): ?><th class="text-right"><?php echo html_escape($label); ?></th><?php endforeach; ?><th class="text-right">Jumlah</th></tr></thead>
    <tbody>
     <?php $totals=array_fill_keys(array_keys($categories),0); foreach ($rows as $row): ?>
     <tr><td><?php echo html_escape($row->sprno===NULL?'Belum tersedia':str_pad($row->sprno,4,'0',STR_PAD_LEFT)); ?></td><td><?php echo html_escape($row->lot); ?></td><td><?php echo html_escape($row->blok.'/'.$row->no); ?></td><td><?php echo html_escape($row->nama===NULL?'Belum tersedia':$row->nama); ?></td>
      <?php foreach ($categories as $id=>$label): $value=isset($row->balances[$id])?$row->balances[$id]:0;$totals[$id]+=$value; ?><td class="retensi-amount" data-order="<?php echo $value; ?>" data-amount="<?php echo $value; ?>">Rp <?php echo number_format($value,0,',','.'); ?></td><?php endforeach; ?>
      <td class="retensi-amount" data-order="<?php echo $row->jumlah; ?>" data-amount="<?php echo $row->jumlah; ?>"><strong>Rp <?php echo number_format($row->jumlah,0,',','.'); ?></strong></td>
     </tr>
     <?php endforeach; ?>
    </tbody>
    <tfoot><tr><th colspan="4">Total</th><?php foreach ($categories as $id=>$label): ?><th class="retensi-amount">Rp <?php echo number_format($totals[$id],0,',','.'); ?></th><?php endforeach; ?><th class="retensi-amount">Rp <?php echo number_format(array_sum($totals),0,',','.'); ?></th></tr></tfoot>
   </table></div>
   <?php endif; ?>
  </div>
 </div>
</div>
<div class="modal fade" id="ajukan-pencairan-modal" tabindex="-1" role="dialog" aria-labelledby="ajukan-pencairan-title"><div class="modal-dialog" role="document"><div class="modal-content">
 <form method="get" action="<?php echo site_url('akad/ajukan_pencairan'); ?>" id="ajukan-pencairan-filter">
  <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Tutup">&times;</button><h4 class="modal-title" id="ajukan-pencairan-title">Ajukan Pencairan Retensi</h4></div>
  <div class="modal-body">
   <div class="form-group"><label for="ajukan-project">Nama Proyek</label><select class="form-control" id="ajukan-project" name="projid" required><option value="">Pilih proyek</option><?php foreach ($projects as $project): ?><option value="<?php echo (int)$project->projid; ?>" <?php echo (int)$project->projid===(int)$projid?'selected':''; ?>><?php echo html_escape($project->namproj); ?></option><?php endforeach; ?></select></div>
   <fieldset><legend style="font-size:14px;font-weight:bold">Kategori Retensi</legend>
    <?php foreach ($request_categories as $id=>$label): ?><div class="checkbox"><label><input type="checkbox" name="kategori[]" value="<?php echo (int)$id; ?>"> <?php echo html_escape($label); ?></label></div><?php endforeach; ?>
    <?php if (!$request_categories): ?><p class="text-muted">Belum ada kategori retensi.</p><?php endif; ?>
   </fieldset>
   <p id="ajukan-category-error" class="text-danger" role="alert" hidden>Pilih minimal satu kategori retensi.</p>
  </div>
  <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary" <?php echo !$request_categories?'disabled':''; ?>>Proses</button></div>
 </form>
</div></div></div>
<?php $this->load->view('footer'); ?>
<script>
jQuery(function($){$('#ajukan-pencairan-filter').on('submit',function(event){var valid=$(this).find('input[name="kategori[]"]:checked').length>0;$('#ajukan-category-error').prop('hidden',valid);if(!valid)event.preventDefault();});});
</script>
<?php if ($projid): ?>
<script>
jQuery(function($){$('#retensi-table').DataTable({paging:false,order:[[2,'asc']],language:{search:'Cari:',info:'Menampilkan _TOTAL_ data',infoEmpty:'Belum ada data retensi',infoFiltered:'(dari _MAX_ data)',emptyTable:'Belum ada customer yang sudah akad pada proyek ini.',zeroRecords:'Data retensi tidak ditemukan.'},footerCallback:function(){var api=this.api();for(var column=4;column<api.columns().count();column++){var total=0;api.column(column,{search:'applied'}).nodes().each(function(cell){total+=Number(cell.getAttribute('data-amount'))||0;});$(api.column(column).footer()).text('Rp '+total.toLocaleString('id-ID'));}}});});
</script>
<?php endif; ?>
