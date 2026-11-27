<?php $this->load->view('header'); ?>
<style>
.retensi-request-fields{max-width:850px;display:grid;grid-template-columns:180px minmax(0,1fr);gap:14px 20px;align-items:center;margin-bottom:20px}
.retensi-request-fields label{margin:0}.retensi-request-fields .form-control{width:100%}
.retensi-request-table .amount{text-align:right;white-space:nowrap}.retensi-request-table td{vertical-align:middle!important}
.retensi-request-table input[type=checkbox]{width:18px;height:18px}
.retensi-request-summary{background:#edf7f3;padding:14px;margin:15px 0}
@media(max-width:600px){.retensi-request-fields{grid-template-columns:1fr;gap:7px}.retensi-request-fields .form-control{margin-bottom:10px}}
</style>
<div class="right_col" role="main"><div class="x_panel">
<div class="x_title"><h2><?php echo html_escape($title); ?></h2><div class="clearfix"></div></div>
<div class="x_content">
<?php if ($error): ?><div class="alert alert-danger" role="alert"><?php echo html_escape($error); ?></div><?php endif; ?>
<?php echo form_open('pengajuan_retensi/save',array('id'=>'retensi-request-form')); ?>
<input type="hidden" name="id" value="<?php echo (int)$id; ?>"><input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('pengajuan_retensi_token')); ?>">
<div class="retensi-request-fields">
<label for="request-project">Nama Proyek</label><select class="form-control" id="request-project" name="projid" required><option value="">Pilih proyek</option><?php foreach ($projects as $project): ?><option value="<?php echo (int)$project->projid; ?>" <?php echo (string)$project->projid===(string)$data['projid'] ? 'selected' : ''; ?>><?php echo html_escape($project->namproj); ?></option><?php endforeach; ?></select>
<label for="request-date">Tanggal Surat</label><input type="date" class="form-control" id="request-date" name="tanggal_surat" required value="<?php echo html_escape($data['tanggal_surat']); ?>">
<label for="request-number">No. Surat</label><input type="text" class="form-control" id="request-number" name="no_surat" maxlength="100" required value="<?php echo html_escape($data['no_surat']); ?>">
<label for="request-destination">Tujuan Surat</label><input type="text" class="form-control" id="request-destination" name="tujuan_surat" list="request-destinations" maxlength="150" required value="<?php echo html_escape($data['tujuan_surat']); ?>" placeholder="Kantor cabang tujuan">
<label for="request-account">No Rekening</label><input type="text" class="form-control" id="request-account" name="no_rekening" list="request-accounts" maxlength="50" required value="<?php echo html_escape($data['no_rekening']); ?>">
<label for="request-category">Retensi</label><select class="form-control" id="request-category" name="kategori_id" required><option value="">Pilih jenis retensi</option><?php foreach ($categories as $key=>$label): ?><option value="<?php echo (int)$key; ?>" <?php echo (string)$key===(string)$data['kategori_id'] ? 'selected' : ''; ?>><?php echo html_escape($label); ?></option><?php endforeach; ?></select>
</div>
<details style="margin-bottom:20px" open><summary style="font-weight:bold;margin-bottom:15px;cursor:pointer">Data Surat untuk Cetak PDF</summary>
<div class="retensi-request-fields">
<?php foreach (array('kota_surat'=>'Kota Surat','nama_perusahaan'=>'Nama Perusahaan','bank_tujuan'=>'Bank Tujuan','atas_nama'=>'Rekening Atas Nama','bank_rekening'=>'Bank / Cabang Rekening') as $field=>$label): ?><label for="print-<?php echo $field; ?>"><?php echo $label; ?></label><input type="text" class="form-control" id="print-<?php echo $field; ?>" name="<?php echo $field; ?>" maxlength="<?php echo $field==='kota_surat' ? 100 : 150; ?>" value="<?php echo html_escape($data[$field]); ?>"><?php endforeach; ?>
<label for="print-alamat_proyek">Alamat Proyek</label><textarea class="form-control" id="print-alamat_proyek" name="alamat_proyek" maxlength="2000" rows="2"><?php echo html_escape($data['alamat_proyek']); ?></textarea>
<label for="print-alamat_tujuan">Alamat Tujuan Surat</label><textarea class="form-control" id="print-alamat_tujuan" name="alamat_tujuan" maxlength="2000" rows="3"><?php echo html_escape($data['alamat_tujuan']); ?></textarea>
</div></details>
<datalist id="request-destinations"><?php foreach ($destinations as $destination): ?><option value="<?php echo html_escape($destination->bank_cabang); ?>"></option><?php endforeach; ?></datalist>
<datalist id="request-accounts"><?php foreach ($accounts as $account): ?><option data-projid="<?php echo (int)$account->projid; ?>" value="<?php echo html_escape($account->accno); ?>" label="<?php echo html_escape($account->nambank.' - '.$account->namacc); ?>"></option><?php endforeach; ?></datalist>
<button class="btn btn-primary" type="submit" name="action" value="process">Proses</button>
<a class="btn btn-default" href="<?php echo site_url('pengajuan_retensi'); ?>">Kembali</a>
<p id="request-reprocess" class="text-info" hidden>Proyek atau jenis retensi berubah. Klik Proses untuk menampilkan saldo yang sesuai.</p>
<?php if ($processed): ?><section id="request-results" style="margin-top:20px">
<h4>Retensi <?php echo html_escape(isset($categories[$data['kategori_id']]) ? $categories[$data['kategori_id']] : ''); ?> yang Masih Terhutang</h4>
<div class="retensi-request-summary" aria-live="polite">Konsumen terpilih: <strong id="request-count">0</strong> &nbsp; Total: <strong id="request-total">Rp 0</strong></div>
<div class="table-responsive"><table class="table table-striped table-bordered retensi-request-table">
<thead><tr><th><input type="checkbox" id="request-select-all" aria-label="Pilih semua konsumen"></th><th>No</th><th>Konsumen</th><th>Lokasi Unit</th><th class="amount">Jumlah Retensi</th></tr></thead>
<tbody><?php foreach ($rows as $index=>$item): ?><tr>
<td><input type="checkbox" class="request-participant" name="participants[]" value="<?php echo (int)$item['projid'].':'.(int)$item['sprno']; ?>" data-amount="<?php echo (float)$item['jumlah']; ?>" aria-label="<?php echo html_escape('Pilih '.$item['nama']); ?>" <?php echo in_array($item['projid'].':'.$item['sprno'],$selected,TRUE) ? 'checked' : ''; ?>></td>
<td><?php echo $index+1; ?></td><td><?php echo html_escape($item['nama']); ?></td><td><?php echo html_escape($item['blok'].'/'.$item['no_unit']); ?></td><td class="amount">Rp <?php echo number_format($item['jumlah'],0,',','.'); ?></td>
</tr><?php endforeach; ?></tbody>
</table></div>
<?php if (!$rows): ?><p class="alert alert-info">Tidak ada saldo retensi terhutang untuk proyek dan jenis retensi yang dipilih.</p><?php else: ?><button type="submit" name="action" value="save" class="btn btn-success" id="request-save">Simpan Pengajuan</button><?php endif; ?>
</section><?php endif; ?>
<?php echo form_close(); ?></div></div></div>
<?php $this->load->view('footer'); ?>
<script src="<?php echo $this->config->item('assets'); ?>/js/pengajuan-retensi.js?v=1"></script>
