<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo $main_title; ?></h3>
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
                          <select name="cb_proj" id="cb_proj" required class="form-control">
                          <?php echo $proj; ?>
                          </select>
                        </div>
                      </div>
                      
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bulan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_mon" id="cb_mon" required class="form-control">
                          <?php echo $mon; ?>
                          </select>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Minggu
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_mg" id="cb_mg" required class="form-control">
                          <option value="0">Tampilkan Perbulan</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          </select>
                        </div>
                      </div>
                      
                         <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_tahun">Tahun
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_tahun" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_tahun" maxlength="4" value="<?php echo date('Y'); ?>" >
                        </div>
                      </div>
                      
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tampilkan</button>
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
      