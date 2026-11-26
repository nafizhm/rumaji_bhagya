<?php $this->load->view('header'); ?>
<link href="<?php echo $this->config->item('assets');?>/bootstrap-select-1.12.3/css/bootstrap-select.min.css" rel="stylesheet">
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Bank Account</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                   <?php $this->lib->disp_msg(); ?>
                  <div class="x_content">
                    <br />
                    <form id="demo-form2" class="form-horizontal form-label-left" method="post" action="<?php echo $form_action; ?>">
                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Project
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="hidden" name="cb_proj_hid" value="<?php echo $this->uri->segment(5);?>"/>
                           <select name="cb_proj" id="cb_proj" required class="form-control" <?php echo $disabled;?>>
                          <?php echo $cb_proj; ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bank
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="hidden" name="cb_bank_hid" value="<?php echo $this->uri->segment(3);?>"/>
                          <select name="cb_bank" <?php echo $disabled;?> id="cb_bank" class="form-control selectpicker" data-live-search="true" required >
                          <?php echo $cb_bank; ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kas
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_kas" id="cb_kas" class="form-control" required >
                         	<option value="KB" <?php echo set_select('cb_kas','KB', ( !empty($default['cb_kas']) && $default['cb_kas'] == "KB" ? TRUE : FALSE )); ?>>Kas Besar</option>
                            <option value="KP" <?php echo set_select('cb_kas','KP', ( !empty($default['cb_kas']) && $default['cb_kas'] == "KP" ? TRUE : FALSE )); ?>>Kas Proyek</option>
                            <option value="KK" <?php echo set_select('cb_kas','KK', ( !empty($default['cb_kas']) && $default['cb_kas'] == "KK" ? TRUE : FALSE )); ?>>Kas Konsumen</option>
                          </select>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Account No<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_accno" id="txt_accno" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_accno', isset($default['txt_accno']) ? $default['txt_accno'] : ''); ?>" maxlength="20" required="required" <?php echo $readonly;?>>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Account<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_namacc" id="txt_namacc" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_namacc', isset($default['txt_namacc']) ? $default['txt_namacc'] : ''); ?>" maxlength="100" required="required" >
                        </div>
                      </div>	
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         <a href ="javascript:history.back()"  class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>
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
<script src="<?php echo $this->config->item('assets');?>/bootstrap-select-1.12.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){	
$('.selectpicker').selectpicker();
});
</script>