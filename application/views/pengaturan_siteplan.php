<?php $this->load->view('header'); ?>
<style>
.siteplan-settings{max-width:950px}.siteplan-settings td,.siteplan-settings th{vertical-align:middle!important}
.siteplan-settings .color-editor{display:flex;align-items:center;gap:10px;min-width:180px}
.siteplan-settings input[type=color]{width:48px;height:38px;padding:3px;cursor:pointer}
.siteplan-settings .hex-input{width:110px;font-family:monospace}
.siteplan-settings .preview{display:inline-flex;align-items:center;gap:9px;min-width:130px}
.siteplan-settings .swatch{display:inline-block;width:23px;height:23px;flex-shrink:0;border:1px solid #94a3b8;border-radius:5px}
</style>
<div class="right_col" role="main"><div class="x_panel"><div class="x_title"><h2>Pengaturan Siteplan</h2><div class="clearfix"></div></div><div class="x_content">
<?php $this->lib->disp_msg();if ($error): ?><div class="alert alert-danger" role="alert"><?php echo html_escape($error); ?></div><?php endif; ?>
<?php echo form_open('pengaturan_siteplan/save',array('id'=>'siteplan-status-form')); ?>
<input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('siteplan_status_token')); ?>">
<div class="table-responsive"><table class="table table-striped table-bordered siteplan-settings">
<thead><tr><th style="width:40px">No</th><th>Nama Status</th><th>Warna</th><th>Pratinjau</th></tr></thead>
<tbody><?php foreach ($rows as $index=>$item): ?><tr class="status-setting">
<td><?php echo $index+1; ?></td>
<td><input type="text" class="form-control status-name" name="statuses[<?php echo html_escape($item->kode); ?>][nama]" maxlength="80" required value="<?php echo html_escape($item->nama); ?>" aria-label="<?php echo html_escape('Nama status '.$item->nama); ?>" <?php echo $canUpdate ? '' : 'disabled'; ?>></td>
<td><div class="color-editor"><input type="color" class="status-color" value="<?php echo html_escape($item->warna); ?>" aria-label="<?php echo html_escape('Pilih warna '.$item->nama); ?>" <?php echo $canUpdate ? '' : 'disabled'; ?>>
<input type="text" class="form-control hex-input" name="statuses[<?php echo html_escape($item->kode); ?>][warna]" value="<?php echo html_escape($item->warna); ?>" maxlength="7" pattern="#[0-9a-fA-F]{6}" required aria-label="<?php echo html_escape('Kode warna '.$item->nama); ?>" <?php echo $canUpdate ? '' : 'disabled'; ?>></div></td>
<td><span class="preview"><i class="swatch" style="background:<?php echo html_escape($item->warna); ?>"></i><span class="preview-name"><?php echo html_escape($item->nama); ?></span></span></td>
</tr><?php endforeach; ?></tbody></table></div>
<?php if ($canUpdate): ?><button class="btn btn-success" type="submit">Simpan Pengaturan</button><?php endif; ?>
<a class="btn btn-default" href="<?php echo site_url('siteplan'); ?>">Lihat Siteplan</a>
<?php echo form_close(); ?></div></div></div>
<?php $this->load->view('footer'); ?>
<script>
(function(){document.querySelectorAll('.status-setting').forEach(function(row){
var name=row.querySelector('.status-name'),color=row.querySelector('.status-color'),hex=row.querySelector('.hex-input'),swatch=row.querySelector('.swatch');
name.addEventListener('input',function(){row.querySelector('.preview-name').textContent=name.value;});
color.addEventListener('input',function(){hex.value=color.value;swatch.style.backgroundColor=color.value;});
hex.addEventListener('input',function(){if(/^#[0-9a-fA-F]{6}$/.test(hex.value)){color.value=hex.value;swatch.style.backgroundColor=hex.value;}});
});}());
</script>
