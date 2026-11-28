<?php $this->load->view('header'); ?>
<div class="right_col" role="main"><div class="x_panel">
<div class="x_title"><h2>Edit Sales</h2><div class="clearfix"></div></div>
<div class="x_content">
<?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
<?php echo form_open('sls/update',array('class'=>'form-horizontal')); ?>
<input type="hidden" name="kode" value="<?php echo html_escape($row->kodeslsman); ?>">
<input type="hidden" name="sales_token" value="<?php echo html_escape($this->session->userdata('sales_token')); ?>">
<div class="form-group"><label class="control-label col-sm-3" for="sales-kode">Kode Sales</label><div class="col-sm-6"><input id="sales-kode" class="form-control" value="<?php echo html_escape($row->kodeslsman); ?>" readonly></div></div>
<div class="form-group"><label class="control-label col-sm-3" for="sales-nama">Nama Sales</label><div class="col-sm-6"><input id="sales-nama" class="form-control" name="nama" maxlength="50" required value="<?php echo set_value('nama',$row->namaslsman); ?>"></div></div>
<div class="form-group"><div class="col-sm-6 col-sm-offset-3"><button class="btn btn-success" type="submit">Simpan</button><a class="btn btn-default" href="<?php echo site_url('sls/list_sls'); ?>">Kembali</a></div></div>
<?php echo form_close(); ?>
</div></div></div>
<?php $this->load->view('footer'); ?>
