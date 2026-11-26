<?php $this->load->view('header');$assets=$this->config->item('assets'); ?>
<link rel="stylesheet" href="<?php echo $assets; ?>/css/retensi.css?v=1">
<div class="right_col" role="main" id="retensi-page" data-url="<?php echo site_url('retensi'); ?>" data-token="<?php echo html_escape($this->session->userdata('retensi_token')); ?>" data-update="<?php echo $can_update?'1':'0'; ?>" data-delete="<?php echo $can_delete?'1':'0'; ?>">
 <h3>Kategori Retensi</h3>
 <div class="x_panel"><div class="x_title"><h2>Master Kategori Retensi</h2>
  <?php if ($can_create): ?><button type="button" id="add-retensi" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Tambah Kategori</button><?php endif; ?>
  <div class="clearfix"></div></div>
  <div class="x_content">
   <div id="retensi-load-error" class="alert alert-danger" role="alert" hidden></div>
   <div class="table-responsive"><table id="retensi-table" class="table table-striped table-bordered"><thead><tr><th>No</th><th>Nama Kategori Retensi</th><th>Keterangan</th><th>Pilihan</th></tr></thead><tbody></tbody></table></div>
  </div>
 </div>
</div>
<div class="modal fade" id="retensi-editor" tabindex="-1" role="dialog" aria-labelledby="retensi-editor-title"><div class="modal-dialog" role="document"><div class="modal-content">
 <form id="retensi-form">
  <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Tutup">&times;</button><h4 class="modal-title" id="retensi-editor-title">Tambah Kategori Retensi</h4></div>
  <div class="modal-body">
   <input type="hidden" id="retensi-id" name="id">
   <div id="retensi-form-error" class="alert alert-danger" role="alert" hidden></div>
   <div class="form-group"><label for="retensi-name">Nama Kategori Retensi *</label><input type="text" class="form-control" id="retensi-name" name="nama_retensi" maxlength="150" required></div>
   <div class="form-group"><label for="retensi-description">Keterangan</label><textarea class="form-control" id="retensi-description" name="keterangan" rows="4" maxlength="5000"></textarea></div>
  </div>
  <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button type="submit" id="retensi-save" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button></div>
 </form>
</div></div></div>
<div class="modal fade retensi-popup" id="retensi-feedback" tabindex="-1" role="dialog" aria-labelledby="retensi-feedback-title"><div class="modal-dialog modal-sm" role="document"><div class="modal-content">
 <div class="modal-body text-center"><div class="feedback-icon" aria-hidden="true"><i class="fa fa-check"></i></div><h3 id="retensi-feedback-title">Berhasil!</h3><p id="retensi-feedback-message"></p></div>
 <div class="modal-footer"><button type="button" id="retensi-cancel-delete" class="btn btn-default" data-dismiss="modal" hidden>Batal</button><button type="button" id="retensi-feedback-action" class="btn btn-success">Oke, mengerti</button></div>
</div></div></div>
<?php $this->load->view('footer'); ?>
<script src="<?php echo $assets; ?>/js/retensi.js?v=1"></script>
