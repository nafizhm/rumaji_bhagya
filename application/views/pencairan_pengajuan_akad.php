<?php
$this->load->view('header');
$timezone=new DateTimeZone('Asia/Jakarta');
$akadDate=new DateTimeImmutable($row->tanggal_akad,$timezone);
$today=new DateTimeImmutable('today',$timezone);
$days=(int)$akadDate->diff($today)->format('%r%a');
$amountFields=array('plafon','kkp','jumlah_pencairan','total_retensi','sisa');
$totals=array_fill_keys($amountFields,0);
foreach ($rows as $item) foreach ($amountFields as $field) $totals[$field]+=(float)$item->$field;
?>
<style>
.akad-disbursement th,.akad-disbursement td{vertical-align:middle!important}
.akad-disbursement .amount{text-align:right;white-space:nowrap}
.akad-disbursement .consumer{min-width:200px}
.akad-disbursement .choices{white-space:nowrap}
.akad-disbursement tfoot th{background:#f0f6f8}
.akad-age{display:inline-block;border-radius:12px;padding:5px 10px;background:#198754;color:#fff;font-weight:600}
.akad-age.overdue{background:#d9534f;color:#fff}
.akad-disbursement-heading{display:flex;align-items:center;justify-content:space-between;gap:12px}
.akad-disbursement-heading h2{float:none;margin:0;min-width:0;white-space:normal}
.akad-disbursement-heading .akad-age{flex-shrink:0}
@media(max-width:480px){.akad-disbursement-heading h2{font-size:17px}}
</style>
<div class="right_col" role="main"><div class="x_panel">
<div class="x_title akad-disbursement-heading"><h2>Pencairan Pengajuan Akad</h2><?php if ($days>=0): ?><span class="akad-age<?php echo $days>=90 ? ' overdue' : ''; ?>"><?php echo $days.' Hari'; ?></span><?php endif; ?></div>
<div class="x_content">
<?php $this->lib->disp_msg(); ?>
<dl class="dl-horizontal">
<dt>Nama Proyek</dt><dd><?php echo $project ? html_escape($project->namproj) : 'Belum dipilih'; ?></dd>
<dt>Kode Akad</dt><dd><?php echo html_escape($row->kode_akad); ?></dd>
<dt>Tanggal Akad</dt><dd><?php echo date('d-m-Y',strtotime($row->tanggal_akad)); ?></dd>
</dl>
<div class="table-responsive"><table id="pengajuan-pencairan-table" class="table table-striped table-bordered table-hover akad-disbursement">
<thead><tr><th>No</th><th>Konsumen / Blok</th><th>Jumlah Plafon</th><th>KMK</th><th>Jumlah Pencairan</th><th>Jumlah Retensi</th><th>Sisa Pencairan</th><th>Pilihan</th></tr></thead>
<tbody><?php foreach ($rows as $index=>$item): ?><tr>
<td><?php echo $index+1; ?></td>
<td class="consumer"><strong><?php echo html_escape($item->nama ?: 'Belum tersedia'); ?></strong><br><?php echo html_escape($item->blok.'/'.$item->no); ?></td>
<?php foreach ($amountFields as $field): ?><td class="amount" data-order="<?php echo (float)$item->$field; ?>"><?php if ($field==='sisa'): ?><strong class="<?php echo $item->sisa>0 ? 'text-danger' : ''; ?>"><?php endif; ?><?php echo $item->$field===NULL ? 'Belum tersedia' : 'Rp '.number_format((float)$item->$field,0,',','.'); ?><?php if ($field==='sisa'): ?></strong><?php endif; ?></td><?php endforeach; ?>
<td class="choices"><a class="btn btn-primary btn-sm" href="<?php echo site_url('akad/detail/'.(int)$item->projid.'/'.(int)$item->sprno); ?>">Detail</a>
<?php if ($item->spr_status!==NULL && $item->spr_status!=='BTL'): ?><button type="button" class="btn btn-success btn-sm open-pencairan" data-projid="<?php echo (int)$item->projid; ?>" data-sprno="<?php echo (int)$item->sprno; ?>">Pencairan</button><?php endif; ?></td>
</tr><?php endforeach; ?></tbody>
<tfoot><tr><th colspan="2">TOTAL</th><?php foreach ($amountFields as $field): ?><th class="amount">Rp <?php echo number_format($totals[$field],0,',','.'); ?></th><?php endforeach; ?><th></th></tr></tfoot>
</table></div>
<a class="btn btn-default" href="<?php echo site_url('pengajuan_akad'.($row->projid ? '?projid='.(int)$row->projid : '')); ?>">Kembali</a>
</div></div></div>
<?php $this->load->view('modal_pencairan',array('pengajuan_id'=>(int)$row->id));$this->load->view('footer'); ?>
<script src="<?php echo $this->config->item('assets'); ?>/js/pencairan.js?v=12"></script>
