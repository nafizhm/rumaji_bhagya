<?php $this->load->view('header'); ?>
<div class="right_col" role="main"><div class="x_panel"><div class="x_title"><h2>Detail Pengajuan Pencairan Retensi</h2><div class="clearfix"></div></div><div class="x_content">
<dl class="dl-horizontal"><dt>Nama Proyek</dt><dd><?php echo $project ? html_escape($project->namproj) : ''; ?></dd>
<dt>Tanggal Surat</dt><dd><?php echo date('d-m-Y',strtotime($row->tanggal_surat)); ?></dd><dt>No. Surat</dt><dd><?php echo html_escape($row->no_surat); ?></dd>
<dt>Tujuan Surat</dt><dd><?php echo html_escape($row->tujuan_surat); ?></dd><dt>No Rekening</dt><dd><?php echo html_escape($row->no_rekening); ?></dd><dt>Retensi</dt><dd><?php echo html_escape($row->nama_retensi); ?></dd></dl>
<div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>No</th><th>Konsumen</th><th>Lokasi Unit</th><th class="text-right">Jumlah Retensi</th></tr></thead>
<tbody><?php $total=0;foreach ($rows as $index=>$item): $total+=(float)$item->jumlah; ?><tr><td><?php echo $index+1; ?></td><td><?php echo html_escape($item->nama); ?></td><td><?php echo html_escape($item->blok.'/'.$item->no_unit); ?></td><td class="text-right" style="white-space:nowrap">Rp <?php echo number_format($item->jumlah,0,',','.'); ?></td></tr><?php endforeach; ?></tbody>
<tfoot><tr><th colspan="3">TOTAL</th><th class="text-right" style="white-space:nowrap">Rp <?php echo number_format($total,0,',','.'); ?></th></tr></tfoot></table></div>
<a class="btn btn-default" href="<?php echo site_url('pengajuan_retensi'); ?>">Kembali</a>
<?php if ($this->mod_pengajuan_retensi->permission('u')): ?><a class="btn btn-primary" href="<?php echo site_url('pengajuan_retensi/edit/'.$row->id); ?>">Edit</a><?php endif; ?>
</div></div></div><?php $this->load->view('footer'); ?>
