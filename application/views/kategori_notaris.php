<?php $this->load->view('header');$assets=$this->config->item('assets'); ?>
<link rel="stylesheet" href="<?php echo $assets; ?>/css/notaris.css?v=1">
<div class="right_col" role="main" id="notaris-page" data-url="<?php echo site_url('notaris'); ?>" data-token="<?php echo html_escape($this->session->userdata('notaris_token')); ?>" data-update="<?php echo $can_update?'1':'0'; ?>" data-delete="<?php echo $can_delete?'1':'0'; ?>">
 <h3>Notaris</h3>
 <div class="x_panel"><div class="x_title"><h2>Master Notaris</h2>
  <?php if ($can_create): ?><button type="button" id="add-notaris" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Tambah Notaris</button><?php endif; ?>
  <div class="clearfix"></div></div>
  <div class="x_content">
   <div id="notaris-load-error" class="alert alert-danger" role="alert" hidden></div>
   <div class="table-responsive"><table id="notaris-table" class="table table-striped table-bordered"><thead><tr><th>No</th><th>Nama Notaris</th><th>Keterangan</th><th>Pilihan</th></tr></thead><tbody></tbody></table></div>
  </div>
 </div>
</div>
<div class="modal fade" id="notaris-editor" tabindex="-1" role="dialog" aria-labelledby="notaris-editor-title"><div class="modal-dialog" role="document"><div class="modal-content">
 <form id="notaris-form">
  <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Tutup">&times;</button><h4 class="modal-title" id="notaris-editor-title">Tambah Notaris</h4></div>
  <div class="modal-body">
   <input type="hidden" id="notaris-id" name="id">
   <div id="notaris-form-error" class="alert alert-danger" role="alert" hidden></div>
   <div class="form-group"><label for="notaris-name">Nama Notaris *</label><input type="text" class="form-control" id="notaris-name" name="nama_notaris" maxlength="150" required></div>
   <div class="form-group"><label for="notaris-description">Keterangan</label><textarea class="form-control" id="notaris-description" name="keterangan" rows="4" maxlength="5000"></textarea></div>
  </div>
  <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button type="submit" id="notaris-save" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button></div>
 </form>
</div></div></div>
<div class="modal fade notaris-popup" id="notaris-feedback" tabindex="-1" role="dialog" aria-labelledby="notaris-feedback-title"><div class="modal-dialog modal-sm" role="document"><div class="modal-content">
 <div class="modal-body text-center"><div class="feedback-icon" aria-hidden="true"><i class="fa fa-check"></i></div><h3 id="notaris-feedback-title">Berhasil!</h3><p id="notaris-feedback-message"></p></div>
 <div class="modal-footer"><button type="button" id="notaris-cancel-delete" class="btn btn-default" data-dismiss="modal" hidden>Batal</button><button type="button" id="notaris-feedback-action" class="btn btn-success">Oke, mengerti</button></div>
</div></div></div>
<?php $this->load->view('footer'); ?>
<script src="<?php echo $assets; ?>/js/notaris.js?v=1"></script>
