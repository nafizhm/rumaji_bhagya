<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Pos Anggaran</h3>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_paid">Kode Pos Anggaran<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_paid" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_paid" maxlength="2" value="<?php echo set_value('txt_paid', isset($default['txt_paid']) ? $default['txt_paid'] : ''); ?>" <?php echo $readonly;?> >
                        </div>
                      </div>
 					<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_nampa">Deskripsi<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_nampa" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_nampa" maxlength="30" value="<?php echo set_value('txt_nampa', isset($default['txt_nampa']) ? $default['txt_nampa'] : ''); ?>" >
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
      