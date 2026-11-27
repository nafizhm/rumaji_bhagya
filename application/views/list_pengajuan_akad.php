<?php $this->load->view('header');$akadTimezone=new DateTimeZone('Asia/Jakarta');$akadToday=new DateTimeImmutable('today',$akadTimezone); ?>
<style>
.pengajuan-list-table .akad-no{width:28px!important;max-width:35px;padding:8px 5px!important;text-align:center;vertical-align:middle}
.pengajuan-list-table .akad-days{text-align:center;white-space:nowrap;vertical-align:middle}
.pengajuan-list-table .akad-day-badge{display:inline-block;border-radius:12px;padding:5px 10px;background:#198754;color:#fff;font-weight:600}
.pengajuan-list-table .akad-day-badge.overdue{background:#d9534f}

.pengajuan-list-table .akad-count{width:65px!important;max-width:70px;text-align:center!important;vertical-align:middle}
.pengajuan-list-table .akad-link,.pengajuan-list-table .akad-actions{text-align:center;white-space:nowrap;vertical-align:middle}
.pengajuan-list-table .akad-amount{text-align:right;white-space:nowrap;vertical-align:middle}
.pengajuan-list-table .attendance-mismatch{background:#d9534f;color:#fff;border-radius:12px;padding:4px 9px;display:inline-block;min-width:25px}
.pengajuan-list-table tfoot th{background:#f0f6f8}
</style>
<div class="right_col" role="main"><div class="x_panel">
<div class="x_title"><h2>Pengajuan Akad</h2><div class="clearfix"></div></div>
<div class="x_content">
<?php $this->lib->disp_msg(); ?>
<form method="get" action="<?php echo site_url('pengajuan_akad'); ?>" class="form-inline" style="margin-bottom:15px">
<div class="form-group"><label for="pengajuan-filter-project">Nama Proyek</label> <select id="pengajuan-filter-project" name="projid" class="form-control" onchange="this.form.submit()"><option value="0">Semua Proyek</option><?php foreach ($projects as $project): ?><option value="<?php echo (int)$project->projid; ?>" <?php echo (int)$project->projid===$projid ? 'selected' : ''; ?>><?php echo html_escape($project->namproj); ?></option><?php endforeach; ?></select></div>
<noscript><button type="submit" class="btn btn-default">Tampilkan</button></noscript>
</form>
<?php if ($this->mod_pengajuan_akad->permission('c')): ?><p><button type="button" id="pengajuan-akad-add" class="btn btn-primary" data-toggle="modal" data-target="#pengajuan-akad-modal"><i class="fa fa-plus"></i> Tambah Pengajuan Akad</button></p><?php endif; ?>
<div class="table-responsive"><table id="pengajuan-akad-list-table" class="table table-striped table-bordered pengajuan-list-table">
<thead><tr><th class="akad-no">No</th><th>Kode Akad / Tanggal Akad</th><th class="akad-days">Hari</th><th class="akad-count">Jumlah<br>Akad</th><th class="akad-count">Jumlah<br>Hadir</th><th>Total Biaya Akad</th><th>Sisa Pencairan</th><th class="akad-link">Pilihan</th><th class="akad-actions">Action</th></tr></thead>
<tbody><?php foreach ($rows as $index=>$item): ?><tr>
<td class="akad-no"><?php echo $index+1; ?></td><td><strong><?php echo html_escape($item->kode_akad); ?></strong><br><?php echo date('d-m-Y',strtotime($item->tanggal_akad)); ?><br><small><?php echo $item->namproj ? html_escape($item->namproj) : 'Pilih proyek melalui Edit'; ?></small></td>
<?php $akadDays=(int)(new DateTimeImmutable($item->tanggal_akad,$akadTimezone))->diff($akadToday)->format('%r%a'); ?>
<td class="akad-days" data-order="<?php echo $akadDays; ?>"><?php if ($akadDays>=0): ?><span class="akad-day-badge<?php echo $akadDays>=90 ? ' overdue' : ''; ?>"><?php echo $akadDays.' Hari'; ?></span><?php endif; ?></td>
<td class="akad-count"><?php echo (int)$item->jumlah_akad; ?></td><td class="akad-count"><?php if ((int)$item->jumlah_hadir!==(int)$item->jumlah_akad): ?><span class="attendance-mismatch" title="Jumlah hadir belum sama dengan jumlah akad"><?php echo (int)$item->jumlah_hadir; ?></span><?php else: ?><?php echo (int)$item->jumlah_hadir; ?><?php endif; ?></td><td class="akad-amount">Rp <?php echo number_format((float)$item->total_biaya,2,',','.'); ?></td><td class="akad-amount" data-order="<?php echo (float)$item->sisa_pencairan; ?>">Rp <?php echo number_format((float)$item->sisa_pencairan,0,',','.'); ?></td>
<td class="akad-link"><a class="btn btn-info btn-xs" href="<?php echo site_url('pengajuan_akad/detail/'.$item->id); ?>">Detail</a>
<a class="btn btn-warning btn-xs" href="<?php echo site_url('pengajuan_akad/biaya/'.$item->id); ?>"><i class="fa fa-money"></i> Biaya Akad</a>
<a class="btn btn-success btn-xs" href="<?php echo site_url('pengajuan_akad/pencairan/'.$item->id); ?>">Pencairan</a></td>
<td class="akad-actions">
<?php if ($this->mod_pengajuan_akad->permission('u')): ?><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#pengajuan-akad-modal" data-akad-id="<?php echo (int)$item->id; ?>" data-akad="<?php echo html_escape(json_encode($item)); ?>">Edit</button><?php endif; ?>
<?php if ($this->mod_pengajuan_akad->permission('d')): ?>
<?php echo form_open('pengajuan_akad/delete',array('style'=>'display:inline','onsubmit'=>"return confirm('Hapus pengajuan akad beserta daftar pesertanya?');")); ?>
<input type="hidden" name="id" value="<?php echo (int)$item->id; ?>"><input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('pengajuan_akad_token')); ?>"><button type="submit" class="btn btn-danger btn-xs">Hapus</button>
<?php echo form_close(); endif; ?></td>
</tr><?php endforeach; ?></tbody>
<tfoot><tr><th colspan="5">Total Biaya sesuai filter proyek</th><th class="akad-amount">Rp <?php $grandTotal=0;foreach ($rows as $item) $grandTotal+=(float)$item->total_biaya;echo number_format($grandTotal,2,',','.'); ?></th><th class="akad-amount">Rp <?php $remainingTotal=0;foreach ($rows as $item) $remainingTotal+=(float)$item->sisa_pencairan;echo number_format($remainingTotal,0,',','.'); ?></th><th colspan="2"></th></tr></tfoot>
</table></div>
</div></div></div>
<?php if ($this->mod_pengajuan_akad->permission('c') || $this->mod_pengajuan_akad->permission('u')) $this->load->view('frm_pengajuan_akad'); ?>
<?php $this->load->view('footer'); ?>
<script src="<?php echo $this->config->item('assets'); ?>/js/pengajuan-akad-form.js?v=2"></script>

<script>
jQuery(function($){
    $('#pengajuan-akad-list-table').DataTable({
        fixedHeader:true,order:[],
        columnDefs:[{targets:0,orderable:false,width:'28px'}],
        aLengthMenu:[[50,100,200,-1],[50,100,200,'All']]
    });
});
</script>
