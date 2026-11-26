<?php $this->load->view('header'); ?>
<link href="<?php echo $this->config->item('assets'); ?>/bootstrap-select-1.12.3/css/bootstrap-select.min.css" rel="stylesheet">
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Users</h3>
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
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_userid">User<span class="required"> ID*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_userid" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_userid" maxlength="20" value="<?php echo set_value('txt_userid', isset($default['txt_userid']) ? $default['txt_userid'] : ''); ?>" <?php echo $readonly; ?>>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_nama">Nama<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_nama" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_nama" maxlength="100" value="<?php echo set_value('txt_nama', isset($default['txt_nama']) ? $default['txt_nama'] : ''); ?>">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_email">Email<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_email" type="email" required="required" class="form-control col-md-7 col-xs-12" id="txt_email" maxlength="80" value="<?php echo set_value('txt_email', isset($default['txt_email']) ? $default['txt_email'] : ''); ?>">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Password<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_pass" data-parsley-minlength="6" id="txt_pass" type="password" required="required" class="date-picker form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_pass', isset($default['txt_pass']) ? $default['txt_pass'] : ''); ?>">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Konfirmasi Password<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_pass_conf" data-parsley-minlength="6" data-parsley-equalto="#txt_pass" type="password" required="required" class="date-picker form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_pass_conf', isset($default['txt_pass_conf']) ? $default['txt_pass_conf'] : ''); ?>">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cb_role">Role<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="cb_role" class="form-control" id="cb_role" required>
                    <?php echo $role; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Status<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="cb_status" class="form-control" id="cb_status" required>
                    <?php echo $stat; ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Project
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="cb_proj[]" id="cb_proj[]" class="form-control selectpicker" multiple>
                    <?php echo $proj; ?>
                  </select>
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