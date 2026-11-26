<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo $title; ?></h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title2; ?><small></small></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                   <?php $this->lib->disp_msg(); ?>
                  <div class="x_content">
                    <br />
                    <form id="demo-form2" class="form-horizontal form-label-left" method="post" action="<?php echo $form_action; ?>">
                    
                    <input name="txt_paid" type="hidden" value="<?php echo $this->uri->segment(4); ?>" >
                     <input name="txt_projid" type="hidden" value="<?php echo $this->uri->segment(3); ?>" >
                      <input name="txt_total_old" type="hidden" value="<?php echo set_value('txt_total_old', isset($default['txt_total_old']) ? $default['txt_total_old'] : ''); ?>" >
 					<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_byid">Pilih Biaya<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_by" id="cb_by" required class="form-control">
                          <?php echo $cb_by; ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_total">Total Anggaran<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_total" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_total" value="<?php echo set_value('txt_total', isset($default['txt_total']) ? $default['txt_total'] : ''); ?>" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true" >
                        </div>
                      </div>
                      
 					<div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href ="<?php echo $url_back; ?>"  class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>
                          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                          
                           <?php if(isset($default['txt_total']))
                      {
					?>
                     <!-- Small modal -->
                     <button type="button" class="btn btn-info" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-clock-o"></i> History</button>
                      <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel2">History Total Anggaran</h4>
                        </div>
                        <div class="modal-body">
                         <table class="table table-striped table-bordered">
                     	 <thead>
                        <tr>
                          <th>Total</th>
                          <th>Dibuat Oleh</th>
                          <th>Tgl Buat</th>
                          <th>Jam Buat</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($list_anglog as $row)  
						{
						?>
                        <tr>
                          <td><?php echo $this->lib->format_idr($row->total); ?></td>
                          <td><?php echo $row->crby; ?></td>
                          <td><?php echo $this->lib->disp_date( $row->crdat ); ?></td>
                          <td><?php echo $row->crtim; ?></td>
                        </tr>
                        <?php
						}
						?>
                         </tbody>
                    	</table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        </div>

                      </div>
                    </div>
                  </div>
                  <!-- /modals -->
                    <?php
					  }
					  ?>
                      
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
      