<?php $this->load->view('header');$isClosed=!empty($row->biaya_closed_at);$canUpdate=$this->mod_pengajuan_akad->permission('u') && !$isClosed;$text_fields=array('bank_cabang'=>'Bank Cabang','nama_notaris'=>'Nama Notaris');$return_url=site_url('pengajuan_akad').($row->projid ? '?projid='.(int)$row->projid : ''); ?>
<link rel="stylesheet" href="<?php echo $this->config->item('assets'); ?>/css/akad-costs.css?v=9">
<div class="right_col" role="main"><div class="x_panel">
<div class="x_title"><h2>Biaya Akad</h2><div class="clearfix"></div></div>
<div class="x_content">
<?php $this->lib->disp_msg(); ?>
<?php if ($isClosed): ?><p><a class="btn btn-info" target="_blank" rel="noopener" href="<?php echo site_url('pengajuan_akad/cetak_biaya/'.(int)$row->id); ?>"><i class="fa fa-print"></i> Cetak PDF</a></p><?php endif; ?>
<dl class="dl-horizontal"><dt>Nama Proyek</dt><dd><?php echo $project ? html_escape($project->namproj) : 'Belum dipilih'; ?></dd><dt>Kode Akad</dt><dd><?php echo html_escape($row->kode_akad); ?></dd><dt>Tanggal Akad</dt><dd><?php echo date('d-m-Y',strtotime($row->tanggal_akad)); ?></dd><dt>Status</dt><dd><span id="akad-cost-status" class="label <?php echo $isClosed ? 'label-default' : 'label-success'; ?>"><?php echo $isClosed ? 'Closed' : 'Open'; ?></span></dd></dl>
<div id="akad-cost-error" class="alert alert-danger" role="alert" hidden></div>
<?php echo form_open('pengajuan_akad/save_costs',array('id'=>'akad-cost-form','data-editable'=>$canUpdate ? '1' : '0','data-return-url'=>$return_url,'data-closed'=>$isClosed ? '1' : '0')); ?>
<input type="hidden" name="id" value="<?php echo (int)$row->id; ?>"><input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('pengajuan_akad_token')); ?>">
<div class="akad-cost-scroll" tabindex="0" role="region" aria-label="Rincian biaya akad, geser ke samping untuk melihat semua kolom">
<table class="akad-cost-table" id="akad-cost-table">
<colgroup><col class="cost-no"><col class="cost-customer"><col class="cost-unit"><?php foreach ($cost_fields as $field=>$label): ?><col class="cost-amount-col"><?php endforeach; ?><col class="cost-bank-col"><col class="cost-notary-col"></colgroup>
<thead><tr><th rowspan="3" class="cost-no" scope="col">No</th><th rowspan="3" class="cost-customer" scope="col">Nama Konsumen</th><th rowspan="3" class="cost-unit" scope="col">Unit</th><th colspan="<?php echo count($cost_fields)+count($text_fields); ?>" scope="colgroup" class="cost-section-title"><span>Biaya Akad</span></th></tr>
<tr><th colspan="3" scope="colgroup">Biaya Notaris</th><?php foreach (array_slice($cost_fields,3,NULL,TRUE)+$text_fields as $field=>$label): ?><th rowspan="2" scope="col" class="cost-fill-header" data-fill-field="<?php echo $field; ?>" data-fill-label="<?php echo html_escape($label); ?>"><?php echo html_escape($label); ?></th><?php endforeach; ?></tr>
<tr><?php foreach (array_slice($cost_fields,0,3,TRUE) as $field=>$label): ?><th scope="col" class="cost-fill-header" data-fill-field="<?php echo $field; ?>" data-fill-label="<?php echo html_escape($label); ?>"><?php echo html_escape($label); ?></th><?php endforeach; ?></tr></thead>
<tbody>
<?php foreach ($rows as $index=>$item): ?><tr data-projid="<?php echo (int)$item->projid; ?>" data-sprno="<?php echo (int)$item->sprno; ?>" data-pph-rate="<?php echo (int)$this->mod_pengajuan_akad->pph_rate($item->jenis); ?>">
<td class="cost-no"><?php echo $index+1; ?></td><td class="cost-customer"><strong><?php echo html_escape($item->nama ?: '-'); ?></strong><br><small>SPR <?php echo html_escape($this->lib->no_spr($item->sprno)); ?></small>
<?php $jenis=$item->jenis==='Subsidi' ? 'Subsidi KPR' : $item->jenis; $jenisClasses=array('Subsidi KPR'=>'cost-jenis-kpr','Subsidi Cash'=>'cost-jenis-cash','Komersial'=>'cost-jenis-komersial'); ?>
<?php if (isset($jenisClasses[$jenis])): ?><br><span class="cost-jenis-badge <?php echo $jenisClasses[$jenis]; ?>"><?php echo html_escape($jenis); ?></span><?php endif; ?>
</td><td class="cost-unit"><?php echo html_escape(($item->blok ?: '-').'/'.($item->no ?: '-')); ?></td>
<?php foreach ($cost_fields as $field=>$label): $value=$item->$field; $formatted=$value===NULL ? '' : number_format((float)$value,2,',','.'); if ($formatted!=='' && substr($formatted,-3)===',00') $formatted=substr($formatted,0,-3); ?>
<td><input type="text" class="cost-input cost-money" data-field="<?php echo $field; ?>" inputmode="decimal" autocomplete="off" value="<?php echo html_escape($formatted); ?>" aria-label="<?php echo html_escape($label.' - '.($item->nama ?: 'SPR '.$item->sprno)); ?>" <?php echo in_array($field,array('pph','biaya_akad','total_pph'),TRUE) ? 'readonly' : ''; ?> <?php echo $canUpdate ? '' : 'disabled'; ?>></td>
<?php endforeach; ?>
<?php foreach ($text_fields as $field=>$label): ?><td><input type="text" class="cost-input cost-text" data-field="<?php echo $field; ?>" maxlength="150" value="<?php echo html_escape($item->$field); ?>" aria-label="<?php echo html_escape($label.' - '.($item->nama ?: 'SPR '.$item->sprno)); ?>" <?php echo $canUpdate ? '' : 'disabled'; ?>></td><?php endforeach; ?>
</tr><?php endforeach; ?>
</tbody>
<tfoot><tr><th colspan="3" class="cost-total-label" scope="row">TOTAL</th><?php foreach ($cost_fields as $field=>$label): ?><td data-total-field="<?php echo $field; ?>">0</td><?php endforeach; ?><td></td><td></td></tr></tfoot>
</table></div>
<p class="text-muted cost-scroll-hint">Geser tabel ke samping untuk mengisi seluruh rincian.</p>
<p id="cost-copy-status" class="text-success" role="status" aria-live="polite"></p>
<?php if (!$rows): ?><p>Belum ada konsumen yang dipilih. Centang peserta di halaman detail, kemudian simpan.</p><?php endif; ?>
<div class="cost-actions">
<?php if ($canUpdate && $rows): ?><button type="submit" class="btn btn-success" id="akad-cost-save">Simpan Biaya Akad</button> <button type="submit" class="btn btn-primary" id="akad-cost-finish">Selesai</button><?php endif; ?>
<a class="btn btn-default" href="<?php echo html_escape($return_url); ?>">Kembali ke Daftar</a>
</div>
<?php echo form_close(); ?>
</div></div></div>
<?php $this->load->view('footer'); ?>
<script src="<?php echo $this->config->item('assets'); ?>/js/akad-costs.js?v=9"></script>
