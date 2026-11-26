<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Project</h3>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">ID Project
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_projid" id="txt_projid" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_projid', isset($default['txt_projid']) ? $default['txt_projid'] : ''); ?>" readonly="readonly" >
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Project<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_namproj" id="txt_namproj" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_namproj', isset($default['txt_namproj']) ? $default['txt_namproj'] : ''); ?>" maxlength="100" required="required" >
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">ID External<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_idext" id="txt_namproj" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_idext', isset($default['txt_idext']) ? $default['txt_idext'] : ''); ?>" maxlength="4" required="required" >
                        </div>
                      </div>
                      
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Perusahaan<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <select name="cb_pt" class="form-control" id="cb_pt" required>
                            <?php echo $pt; ?>
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
      