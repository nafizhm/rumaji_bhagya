<?php $this->load->view('header'); ?>
<style>
.pengajuan-summary{display:flex;align-items:center;justify-content:space-between;gap:20px;background:#edf7f3;border:1px solid #cde7db;border-radius:8px;padding:18px 22px;margin:18px 0}.pengajuan-summary strong{font-size:25px;color:#239b65}.pengajuan-summary .pengajuan-label{display:block;color:#657786;margin-bottom:5px}#pengajuan-table th{white-space:nowrap}#pengajuan-table .amount{white-space:nowrap;text-align:right}#pengajuan-table tfoot{background:#edf7f3}#pengajuan-table input[type=checkbox]{width:18px;height:18px;cursor:pointer}#pengajuan-table tr.is-selected{background:#edf7f3}@media(max-width:767px){.pengajuan-summary{align-items:flex-start;flex-direction:column}.pengajuan-summary strong{font-size:22px}}
</style>
<div class="right_col" role="main">
 <h3>Ajukan Pencairan Retensi</h3>
 <a class="btn btn-default" href="<?php echo site_url('akad/retensi').'?projid='.(int)$project->projid; ?>"><i class="fa fa-arrow-left"></i> Kembali ke List Retensi</a>
 <div class="x_panel"><div class="x_title"><h2><?php echo html_escape($project->namproj); ?></h2><div class="clearfix"></div></div>
  <div class="x_content">
   <p>Pilih customer yang akan diajukan pencairan retensinya.</p>
   <div class="pengajuan-summary" role="status" aria-live="polite"><div><span class="pengajuan-label">Customer terpilih</span><strong id="pengajuan-count">0</strong> dari <?php echo count($rows); ?> customer</div><div><span class="pengajuan-label">Jumlah retensi terpilih</span><strong id="pengajuan-total">Rp 0</strong></div></div>
   <div class="table-responsive"><table id="pengajuan-table" class="table table-striped table-bordered table-hover">
    <thead><tr><th><input type="checkbox" id="pengajuan-all" aria-label="Pilih semua customer yang tampil"></th><th>No SPR</th><th>Lot</th><th>Blok/No</th><th>Konsumen</th><?php foreach ($categories as $label): ?><th class="text-right"><?php echo html_escape($label); ?></th><?php endforeach; ?><th class="text-right">Jumlah</th></tr></thead>
    <tbody>
     <?php foreach ($rows as $index=>$row): $jumlah=0; ?>
     <tr><td><input type="checkbox" class="pengajuan-check" aria-label="<?php echo html_escape('Pilih '.($row->nama===NULL?$row->blok.'/'.$row->no:$row->nama)); ?>" value="<?php echo $index; ?>"></td><td><?php echo html_escape($row->sprno===NULL?'Belum tersedia':str_pad($row->sprno,4,'0',STR_PAD_LEFT)); ?></td><td><?php echo html_escape($row->lot); ?></td><td><?php echo html_escape($row->blok.'/'.$row->no); ?></td><td><?php echo html_escape($row->nama===NULL?'Belum tersedia':$row->nama); ?></td>
      <?php foreach ($categories as $id=>$label): $value=isset($row->balances[$id])?$row->balances[$id]:0;$jumlah+=$value; ?><td class="amount" data-amount="<?php echo $value; ?>" data-order="<?php echo $value; ?>">Rp <?php echo number_format($value,0,',','.'); ?></td><?php endforeach; ?>
      <td class="amount" data-amount="<?php echo $jumlah; ?>" data-order="<?php echo $jumlah; ?>"><strong>Rp <?php echo number_format($jumlah,0,',','.'); ?></strong></td>
     </tr>
     <?php endforeach; ?>
    </tbody>
    <tfoot><tr><th colspan="5">Total Terpilih</th><?php foreach ($categories as $label): ?><th class="amount">Rp 0</th><?php endforeach; ?><th class="amount">Rp 0</th></tr></tfoot>
   </table></div>
  </div>
 </div>
</div>
<?php $this->load->view('footer'); ?>
<script>
jQuery(function($){
 var table=$('#pengajuan-table').DataTable({paging:false,order:[[3,'asc']],columnDefs:[{targets:0,orderable:false,searchable:false}],language:{search:'Cari:',info:'Menampilkan _TOTAL_ customer',infoEmpty:'Belum ada customer',infoFiltered:'(dari _MAX_ customer)',emptyTable:'Tidak ada customer dengan sisa retensi pada kategori yang dipilih.',zeroRecords:'Customer tidak ditemukan.'}});
 function money(value){return 'Rp '+value.toLocaleString('id-ID');}
 function update(){
  var totals=[],count=0;
  table.rows().nodes().each(function(row){var checked=$(row).find('.pengajuan-check').prop('checked');$(row).toggleClass('is-selected',checked);if(!checked)return;count++;$(row).find('[data-amount]').each(function(index){totals[index]=(totals[index]||0)+Number(this.getAttribute('data-amount'));});});
  for(var index=5;index<table.columns().count();index++)$(table.column(index).footer()).text(money(totals[index-5]||0));
  $('#pengajuan-count').text(count);$('#pengajuan-total').text(money(totals[table.columns().count()-6]||0));
  var visible=$(table.rows({search:'applied'}).nodes()).find('.pengajuan-check'),checked=visible.filter(':checked').length;
  $('#pengajuan-all').prop('checked',visible.length>0&&checked===visible.length).prop('indeterminate',checked>0&&checked<visible.length).prop('disabled',!visible.length);
 }
 $('#pengajuan-table').on('change','.pengajuan-check',update);
 $('#pengajuan-all').on('change',function(){$(table.rows({search:'applied'}).nodes()).find('.pengajuan-check').prop('checked',this.checked);update();});
 table.on('draw',update);update();
});
</script>
