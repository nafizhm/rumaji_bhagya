<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Biaya</h3>
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
                     <form id="form_status" class="form-horizontal form-label-left" method="post" action="<?php echo $form_action; ?>" >
                                     <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Pos Anggaran
                                          </label>
                                           <div class="col-md-6 col-sm-6 col-xs-12">
                                           <input name="txt_paid" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_paid" maxlength="2" value="<?php echo set_value('txt_paid', isset($default['txt_paid']) ? $default['txt_paid'] : ''); ?>" readonly="readonly">
                                           </div>
                                         </div>
                                         
                                      <div class="form-group">
                                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Biaya<span class="required">*</span>
                                          </label>
                                           <div class="col-md-6 col-sm-6 col-xs-12">
                                           <input name="txt_byid" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_byid" maxlength="2" value="<?php echo set_value('txt_byid', isset($default['txt_byid']) ? $default['txt_byid'] : ''); ?>" <?php echo $readonly;?> >
                                           </div>
                                         </div>
                                      
                                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_namby">Deskripsi<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_namby" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_namby" maxlength="30" value="<?php echo set_value('txt_namby', isset($default['txt_namby']) ? $default['txt_namby'] : ''); ?>" >
                        </div>
                      </div>
                      
 					<div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href ="<?php echo $url_back; ?>"  class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>
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
      