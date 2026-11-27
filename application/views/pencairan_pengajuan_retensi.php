<?php $this->load->view('header');$canUpdate=$this->mod_pengajuan_retensi->permission('u');$pending=0;$requested=0;$paidTotal=0; ?>
<style>
.retensi-pay-table th,.retensi-pay-table td{vertical-align:middle!important}
.retensi-pay-table .amount{text-align:right;white-space:nowrap}.retensi-pay-table .center{text-align:center;white-space:nowrap}
.retensi-pay-table input[type=checkbox]{width:18px;height:18px}.retensi-pay-table tfoot{background:#edf7f3}
.retensi-pay-summary{padding:14px;background:#edf7f3;margin:15px 0}
.retensi-payment-date{max-width:300px;margin:20px 0}
</style>
<div class="right_col" role="main"><div class="x_panel"><div class="x_title"><h2>Pencairan Pengajuan Retensi</h2><div class="clearfix"></div></div><div class="x_content">
<?php $this->lib->disp_msg();if($error): ?><div class="alert alert-danger" role="alert"><?php echo html_escape($error); ?></div><?php endif; ?>
<dl class="dl-horizontal"><dt>Nama Proyek</dt><dd><?php echo $project ? html_escape($project->namproj) : ''; ?></dd><dt>No. Surat</dt><dd><?php echo html_escape($row->no_surat); ?></dd><dt>Tanggal Surat</dt><dd><?php echo date('d-m-Y',strtotime($row->tanggal_surat)); ?></dd><dt>Retensi</dt><dd><?php echo html_escape($row->nama_retensi); ?></dd></dl>
<?php echo form_open('pengajuan_retensi/save_payment',array('id'=>'retensi-payment-form')); ?>
<input type="hidden" name="id" value="<?php echo (int)$row->id; ?>"><input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('pengajuan_retensi_token')); ?>">
<?php if($canUpdate): ?><div class="form-group retensi-payment-date"><label for="retensi-payment-date">Tanggal Pembayaran</label><input type="date" name="tanggal_pembayaran" id="retensi-payment-date" class="form-control" required value="<?php echo html_escape($payment_date); ?>"></div><?php endif; ?>
<p>Centang hanya konsumen yang sudah menerima pembayaran. Konsumen yang belum dicentang tetap memiliki saldo retensi.</p>
<div class="retensi-pay-summary" aria-live="polite">Dipilih untuk dibayar: <strong id="retensi-payment-count">0</strong> konsumen &nbsp; | &nbsp; Jumlah: <strong id="retensi-payment-total">Rp 0</strong></div>
<div class="table-responsive"><table class="table table-striped table-bordered retensi-pay-table">
<thead><tr><th class="center"><?php if($canUpdate): ?><input type="checkbox" id="retensi-payment-all" aria-label="Pilih semua konsumen yang belum dibayar"><?php endif; ?> Cair</th><th>No</th><th>No Akad</th><th>Tgl Akad</th><th>Nama Konsumen</th><th>Blok</th><th>Type</th><th class="amount"><?php echo html_escape($row->nama_retensi); ?></th><th>Status / Tanggal Pembayaran</th></tr></thead>
<tbody><?php foreach($rows as $index=>$item): $isPaid=$item->pencairan_bayar_id!==NULL;$requested+=(float)$item->jumlah;$paidTotal+=(float)$item->jumlah_cair;if(!$isPaid)$pending++;$key=$item->projid.':'.$item->sprno;$type=(string)$item->tipe;if($type!=='' && strpos($type,'/')===FALSE && !empty($item->ltstd))$type.='/'.rtrim(rtrim(number_format($item->ltstd,2,'.',''),'0'),'.'); ?><tr>
<td class="center"><input type="checkbox" class="retensi-payment-check" name="participants[]" value="<?php echo html_escape($key); ?>" data-amount="<?php echo (float)$item->jumlah; ?>" aria-label="<?php echo html_escape('Pembayaran '.$item->nama); ?>" <?php echo $isPaid || in_array($key,$selected,TRUE) ? 'checked' : ''; ?> <?php echo !$canUpdate || $isPaid ? 'disabled' : ''; ?>></td>
<td><?php echo $index+1; ?></td><td><?php echo html_escape($item->kode_akad); ?></td><td><?php echo $item->tanggal_akad ? date('d/m/Y',strtotime($item->tanggal_akad)) : ''; ?></td><td><?php echo html_escape($item->nama); ?></td><td class="center"><?php echo html_escape($item->blok.'-'.$item->no_unit); ?></td><td class="center"><?php echo html_escape($type); ?></td><td class="amount">Rp <?php echo number_format($item->jumlah,0,',','.'); ?></td>
<td><?php if($isPaid): ?><span class="label label-success">Sudah Cair</span><br><?php echo date('d-m-Y',strtotime($item->tanggal_cair)); ?><?php else: ?><span class="label label-default">Belum Cair</span><?php endif; ?></td>
</tr><?php endforeach; ?></tbody>
<tfoot><tr><th colspan="7">Total Diajukan</th><th class="amount">Rp <?php echo number_format($requested,0,',','.'); ?></th><th></th></tr><tr><th colspan="7">Total Sudah Cair</th><th class="amount">Rp <?php echo number_format($paidTotal,0,',','.'); ?></th><th></th></tr></tfoot>
</table></div>
<?php if($canUpdate && $pending): ?><button class="btn btn-success" type="submit" id="retensi-payment-save">Simpan Pencairan</button><?php elseif(!$pending): ?><p class="alert alert-success">Seluruh konsumen dalam pengajuan ini sudah dibayar.</p><?php endif; ?>
<a class="btn btn-default" href="<?php echo site_url('pengajuan_retensi'); ?>">Kembali</a>
<?php echo form_close(); ?></div></div></div>
<?php $this->load->view('footer'); ?>
<script src="<?php echo $this->config->item('assets'); ?>/js/pengajuan-retensi-payment.js?v=1"></script>
