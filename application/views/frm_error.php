<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Error</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Otorisasi Error<?php //echo $title; ?></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                   <?php //$this->lib->disp_msg(); ?>
                  <div class="x_content">
                   <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button><?php echo $this->session->userdata("auth_error"); ?></div>
                    <a href ="javascript:history.back()"  class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<?php $this->load->view('footer'); ?>
