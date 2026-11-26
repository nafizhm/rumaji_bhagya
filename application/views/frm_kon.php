<?php $this->load->view('header'); ?>
<link href="<?php echo $this->config->item('assets'); ?>/bootstrap-select-1.12.3/css/bootstrap-select.min.css" rel="stylesheet">
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Vendor</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo $title; ?><small></small></h2>

            <div class="clearfix"></div>
          </div>
          <?php $this->lib->disp_msg(); ?>
          <div class="x_content">
            <br />
            <form id="demo-form2" class="form-horizontal form-label-left" method="post" action="<?php echo $form_action; ?>">

              <input name="txt_konid" type="hidden" required="required" class="form-control col-md-7 col-xs-12" id="txt_konid" value="<?php echo set_value('txt_konid', isset($default['txt_konid']) ? $default['txt_konid'] : ''); ?>">

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_namkon">Nama Vendor<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_namkon" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_namkon" maxlength="100" value="<?php echo set_value('txt_namkon', isset($default['txt_namkon']) ? $default['txt_namkon'] : ''); ?>">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea name="txt_alm" id="txt_alm" cols="2" rows="5" class="form-control col-md-7 col-xs-12" required="required"><?php echo set_value('txt_alm', isset($default['txt_alm']) ? $default['txt_alm'] : ''); ?></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Telp
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_telp" id="txt_telp" class="form-control col-md-7 col-xs-12" required="required" maxlength="15" value="<?php echo set_value('txt_telp', isset($default['txt_telp']) ? $default['txt_telp'] : ''); ?>" />
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Bank
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="hidden" name="cb_bank_hid" value="<?php echo $this->uri->segment(3); ?>" />
                  <select name="cb_bank" <?php echo $disabled; ?> id="cb_bank" class="form-control selectpicker" data-live-search="true" required>
                    <?php echo $cb_bank; ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Account No
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_accno" id="txt_accno" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_accno', isset($default['txt_accno']) ? $default['txt_accno'] : ''); ?>" maxlength="20" <?php echo $readonly; ?>>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Account
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_namacc" id="txt_namacc" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_namacc', isset($default['txt_namacc']) ? $default['txt_namacc'] : ''); ?>" maxlength="100">
                </div>
              </div>

              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <a href="<?php echo $url_back; ?>" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>
                  <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
<?php $this->load->view('footer'); ?>
<script src="<?php echo $this->config->item('assets'); ?>/bootstrap-select-1.12.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.selectpicker').selectpicker();
  });
</script>