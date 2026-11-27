<div class="modal fade" id="pengajuan-akad-modal" tabindex="-1" role="dialog" aria-labelledby="pengajuan-akad-modal-title">
<div class="modal-dialog" role="document"><div class="modal-content">
<?php echo form_open('pengajuan_akad/save',array('id'=>'pengajuan-akad-form','class'=>'form-horizontal')); ?>
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Tutup">&times;</button><h4 class="modal-title" id="pengajuan-akad-modal-title">Tambah Pengajuan Akad</h4></div>
<div class="modal-body">
<div id="pengajuan-akad-error" class="alert alert-danger" role="alert" style="display:none"></div>
<input type="hidden" name="id" id="pengajuan-akad-id" value="0"><input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('pengajuan_akad_token')); ?>">
<div class="form-group"><label for="akad-projid" class="control-label col-sm-4">Nama Proyek *</label><div class="col-sm-8"><select class="form-control" name="projid" id="akad-projid" required><option value="">Pilih nama proyek</option><?php foreach ($projects as $project): ?><option value="<?php echo (int)$project->projid; ?>"><?php echo html_escape($project->namproj); ?></option><?php endforeach; ?></select></div></div>
<div class="form-group"><label for="akad-kode" class="control-label col-sm-4">Kode Akad *</label><div class="col-sm-8"><input class="form-control" name="kode_akad" id="akad-kode" maxlength="50" required></div></div>
<div class="form-group"><label for="akad-tanggal" class="control-label col-sm-4">Tanggal Akad *</label><div class="col-sm-8"><input type="date" class="form-control" name="tanggal_akad" id="akad-tanggal" required></div></div>
<div class="form-group"><label for="akad-keterangan" class="control-label col-sm-4">Keterangan</label><div class="col-sm-8"><textarea class="form-control" name="keterangan" id="akad-keterangan" rows="4" maxlength="5000"></textarea></div></div>
</div>
<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-success" id="pengajuan-akad-save">Simpan</button></div>
<?php echo form_close(); ?>
</div></div></div>
