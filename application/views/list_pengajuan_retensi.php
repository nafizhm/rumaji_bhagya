<?php $this->load->view('header'); ?>
<div class="right_col" role="main"><div class="x_panel">
<div class="x_title"><h2>Pengajuan Pencairan Retensi</h2><div class="clearfix"></div></div>
<div class="x_content"><?php $this->lib->disp_msg(); ?>
<?php if ($this->mod_pengajuan_retensi->permission('c')): ?><p><a class="btn btn-primary" href="<?php echo site_url('pengajuan_retensi/create'); ?>"><i class="fa fa-plus"></i> Pengajuan Baru</a></p><?php endif; ?>
<div class="table-responsive"><table id="datatable" class="table table-bordered table-striped">
<thead><tr><th>No</th><th>Nomor / Tanggal Surat</th><th>Nama Proyek</th><th>Tujuan Surat / Rekening</th><th>Retensi</th><th>Jumlah Konsumen</th><th>Pengajuan</th><th>Status</th><th>Action</th></tr></thead>
<tbody><?php foreach ($rows as $index=>$item): ?><tr>
<td><?php echo $index+1; ?></td><td><strong><?php echo html_escape($item->no_surat); ?></strong><br><?php echo date('d-m-Y',strtotime($item->tanggal_surat)); ?></td>
<td><?php echo html_escape($item->namproj); ?></td><td><?php echo html_escape($item->tujuan_surat); ?><br><?php echo html_escape($item->no_rekening); ?></td>
<td><?php echo html_escape($item->nama_retensi); ?></td><td class="text-center"><?php echo (int)$item->jumlah_konsumen; ?></td><td class="text-right" style="white-space:nowrap" data-order="<?php echo (float)$item->total; ?>">Rp <?php echo number_format($item->total,0,',','.'); ?></td>
<?php $paid=(int)$item->jumlah_cair;$count=(int)$item->jumlah_konsumen;$status=$paid===0 ? 'Belum Cair' : ($paid>=$count ? 'Cair Semua' : 'Cair Sebagian');$statusClass=$paid===0 ? 'label-default' : ($paid>=$count ? 'label-success' : 'label-warning'); ?>
<td style="white-space:nowrap"><span class="label <?php echo $statusClass; ?>"><?php echo $status; ?></span><br><small><?php echo $paid.' / '.$count; ?> konsumen</small></td>
<td style="white-space:nowrap"><a class="btn btn-info btn-xs" href="<?php echo site_url('pengajuan_retensi/detail/'.$item->id); ?>">Detail</a>
<a class="btn btn-success btn-xs" href="<?php echo site_url('pengajuan_retensi/pencairan/'.$item->id); ?>">Pencairan</a>
<a class="btn btn-default btn-xs" target="_blank" rel="noopener" href="<?php echo site_url('pengajuan_retensi/cetak/'.$item->id); ?>"><i class="fa fa-print"></i> Cetak</a>
<?php if ($this->mod_pengajuan_retensi->permission('u')): ?><a class="btn btn-primary btn-xs" href="<?php echo site_url('pengajuan_retensi/edit/'.$item->id); ?>">Edit</a><?php endif; ?>
<?php if ($this->mod_pengajuan_retensi->permission('d')): ?><?php echo form_open('pengajuan_retensi/delete',array('style'=>'display:inline','onsubmit'=>"return confirm('Hapus pengajuan pencairan retensi ini?');")); ?><input type="hidden" name="id" value="<?php echo (int)$item->id; ?>"><input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('pengajuan_retensi_token')); ?>"><button class="btn btn-danger btn-xs" type="submit">Hapus</button><?php echo form_close();endif; ?></td>
</tr><?php endforeach; ?></tbody>
</table></div></div></div></div>
<?php $this->load->view('footer'); ?>
