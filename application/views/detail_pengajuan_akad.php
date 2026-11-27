<?php $this->load->view('header');$isClosed=!empty($row->peserta_closed_at);$canAttend=$this->mod_pengajuan_akad->permission('u') && $isClosed;$canUpdate=$this->mod_pengajuan_akad->permission('u') && !$isClosed;$selectionLocked=$isClosed || !empty($row->biaya_closed_at); ?>
<div class="right_col" role="main"><div class="x_panel">
<div class="x_title"><h2>Detail Pengajuan Akad</h2><div class="clearfix"></div></div>
<div class="x_content">
<?php $this->lib->disp_msg(); ?>
<dl class="dl-horizontal"><dt>Nama Proyek</dt><dd><?php echo $project ? html_escape($project->namproj) : 'Belum dipilih'; ?></dd><dt>Kode Akad</dt><dd><?php echo html_escape($row->kode_akad); ?></dd><dt>Tanggal Akad</dt><dd><?php echo date('d-m-Y',strtotime($row->tanggal_akad)); ?></dd><dt>Status</dt><dd><span class="label <?php echo $isClosed ? 'label-default' : 'label-success'; ?>"><?php echo $isClosed ? 'Closed' : 'Open'; ?></span></dd><dt>Keterangan</dt><dd><?php echo nl2br(html_escape($row->keterangan)); ?></dd></dl>
<?php if (!$project): ?><div class="alert alert-warning">Pilih proyek melalui Edit Pengajuan Akad. Hanya peserta lama yang ditampilkan; keluarkan peserta dari proyek lain sebelum menetapkan proyek.</div><?php endif; ?>
<?php if ($isClosed): ?><p class="alert alert-info">Detail pengajuan akad sudah Closed. Hanya peserta terpilih yang ditampilkan.</p><?php elseif ($selectionLocked): ?><p class="alert alert-info">Biaya akad sudah Closed. Daftar peserta dikunci; kehadiran dapat diperbarui setelah detail selesai.</p><?php endif; ?>
<?php if (!$selectionLocked): ?><p>Centang konsumen SP3K yang diikutkan dalam kloter ini, lalu simpan. Peserta yang sudah tersimpan tetap ditampilkan saat statusnya berubah.</p><?php endif; ?>
<?php echo form_open('pengajuan_akad/save_participants',array('id'=>'pengajuan-peserta-form','data-editable'=>$canUpdate ? '1' : '0','data-attendance-editable'=>$canAttend ? '1' : '0','data-attendance-url'=>site_url('pengajuan_akad/save_attendance'))); ?>
<input type="hidden" name="id" value="<?php echo (int)$row->id; ?>"><input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('pengajuan_akad_token')); ?>"><input type="hidden" name="participants_json" id="pengajuan-peserta-json">
<input type="hidden" id="pengajuan-project" value="<?php echo $row->projid ? (int)$row->projid : ''; ?>">
<div class="form-group"><label for="pengajuan-search">Cari konsumen / unit</label><input type="search" id="pengajuan-search" class="form-control" placeholder="Nama, blok atau nomor unit"></div>
<div class="well well-sm" aria-live="polite">Jumlah Akad: <strong id="pengajuan-jumlah">0</strong><?php if ($isClosed): ?> &nbsp; | &nbsp; Jumlah Hadir: <strong id="pengajuan-hadir">0</strong><?php endif; ?></div>
<div class="table-responsive"><table class="table table-striped table-bordered" id="pengajuan-peserta-table">
<thead><tr><th>No</th><th>Nama Konsumen</th><th>Unit</th><th>Pembayaran (%)</th><th>Progress Berkas (%)</th><th>Pembangunan (%)</th><th><?php if ($canUpdate && !$selectionLocked): ?><input type="checkbox" id="pengajuan-select-all" aria-label="Pilih semua konsumen yang ditampilkan"><?php endif; ?> Pilih</th><?php if ($isClosed): ?><th>Hadir</th><?php endif; ?></tr></thead>
<tbody><?php foreach ($rows as $index=>$item): $selected=$item->pengajuan_id!==NULL; ?><tr data-projid="<?php echo (int)$item->projid; ?>" data-sprno="<?php echo (int)$item->sprno; ?>">
<td><?php echo $index+1; ?></td><td><a target="_blank" rel="noopener" href="<?php echo site_url('spr/edit_spr/'.$item->sprno.'/'.$item->projid); ?>"><?php echo html_escape($item->nama); ?></a><br><small>SPR <?php echo html_escape($this->lib->no_spr($item->sprno)); ?></small></td>
<td><?php echo html_escape($item->blok.'/'.$item->no); ?><br><small><?php echo html_escape($item->namproj); ?></small><?php if ($item->spr_status==='BTL'): ?><br><span class="label label-warning">SPR dibatalkan</span><?php endif; ?></td>
<?php foreach (array($item->persen_bayar,$item->persen_berkas,$item->persen_bangun) as $percent): ?><td><strong><?php echo html_escape($percent); ?>%</strong><div class="progress" style="height:8px;margin:6px 0"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo min(100,max(0,$percent)); ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo min(100,max(0,$percent)); ?>%"></div></div></td><?php endforeach; ?>
<td><input type="checkbox" class="peserta-select" aria-label="<?php echo html_escape('Pilih '.$item->nama); ?>" <?php echo $selected ? 'checked' : ''; ?> <?php echo $canUpdate && !$selectionLocked ? '' : 'disabled'; ?>></td>
<?php if ($isClosed): ?><td><input type="checkbox" class="peserta-hadir" aria-label="<?php echo html_escape('Hadir '.$item->nama); ?>" <?php echo $item->hadir ? 'checked' : ''; ?> <?php echo $canAttend && $selected ? '' : 'disabled'; ?>></td><?php endif; ?>
</tr><?php endforeach; ?></tbody></table></div>
<p id="pengajuan-attendance-status" role="status" aria-live="polite"></p>
<?php if (!$rows): ?><p>Belum ada konsumen SP3K yang dapat dipilih.</p><?php endif; ?>
<?php if ($canUpdate): ?><button type="submit" name="finish" value="0" class="btn btn-success">Simpan Peserta</button>
<button type="submit" name="finish" value="1" class="btn btn-primary">Selesai</button><?php endif; ?>
<a class="btn btn-default" href="<?php echo site_url('pengajuan_akad'); ?>">Kembali</a>
<?php echo form_close(); ?>
</div></div></div>
<?php $this->load->view('footer'); ?>
<script src="<?php echo $this->config->item('assets'); ?>/js/pengajuan-akad.js?v=4"></script>
