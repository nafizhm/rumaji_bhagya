<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Harga Standard</h3>
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
                           <input type="hidden" name="txt_lot_old" value="<?php echo set_value('txt_lot_old', isset($default['txt_lot_old']) ? $default['txt_lot_old'] : ''); ?>" />
                           <input type="hidden" name="txt_tipe_old" value="<?php echo set_value('txt_tipe_old', isset($default['txt_tipe_old']) ? $default['txt_tipe_old'] : ''); ?>" />
                            <input type="hidden" name="txt_tgl_old" value="<?php echo set_value('txt_tgl_old', isset($default['txt_tgl_old']) ? $default['txt_tgl_old'] : ''); ?>" />
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Project
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="hidden" name="txt_proj" value="<?php echo set_value('txt_proj', isset($default['txt_proj']) ? $default['txt_proj'] : ''); ?>" />
                          <select name="cb_proj" id="cb_proj" required class="form-control" <?php if($readonly != '') { echo'disabled="disabled"'; } ?> >
                          <?php echo $proj; ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Lot
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_lot" id="txt_lot" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_lot', isset($default['txt_lot']) ? $default['txt_lot'] : ''); ?>"  required="required" <?php //echo $readonly; ?>  >
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipe Rumah
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="hidden" name="txt_tipe" value="<?php echo set_value('txt_tipe', isset($default['txt_tipe']) ? $default['txt_tipe'] : ''); ?>" />
                          <select name="cb_tipe" id="cb_tipe" required class="form-control" <?php //if($readonly != '') { echo'disabled="disabled"'; } ?>>
                          <?php echo $tipe; ?>
                          </select>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Harga
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="hidden" name="txt_hrg_old" value="<?php echo set_value('txt_hrg_old', isset($default['txt_hrg_old']) ? $default['txt_hrg_old'] : ''); ?>" />
                           <input name="txt_hrg" id="txt_hrg" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_hrg', isset($default['txt_hrg']) ? $default['txt_hrg'] : ''); ?>"  required="required" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true"  >
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya Titipan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <input type="hidden" name="txt_byttp_old" value="<?php echo set_value('txt_byttp_old', isset($default['txt_byttp_old']) ? $default['txt_byttp_old'] : ''); ?>" />
                           <input name="txt_byttp" id="txt_byttp" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_byttp', isset($default['txt_byttp']) ? $default['txt_byttp'] : ''); ?>"  required="required" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true"  >
                        </div>
                      </div>
                      
                         <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Valid
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_tgl" id="txt_tgl" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_tgl', isset($default['txt_tgl']) ? $default['txt_tgl'] : ''); ?>"  required="required" <?php //echo $readonly; ?> >
                        </div>
                      </div>
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         <a href ="<?php echo $url_back; ?>"  class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>
                          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                          <?php if($readonly != '')
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
                          <h4 class="modal-title" id="myModalLabel2">History Harga Standard</h4>
                        </div>
                        <div class="modal-body">
                         <table class="table table-striped table-bordered">
                     	 <thead>
                        <tr>
                          <th>Harga Standard</th>
                          <th>Biaya Titipan</th>
                          <th>Dibuat Oleh</th>
                          <th>Tgl Buat</th>
                          <th>Jam Buat</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($list_hrglog as $row)  
						{
						?>
                        <tr>
                          <td><?php echo $this->lib->format_idr($row->hrgstd); ?></td>
                          <td><?php echo $this->lib->format_idr($row->byttp); ?></td>
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
<?php //if($readonly == '')
//{
?>
<script type="text/javascript">
$(function() {
    $('input[name="txt_tgl"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
		autoUpdateInput: false/*,
		 locale: {
            format: 'DD/MM/YYYY'
        }*/
    });
	
	$('input[name="txt_tgl"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
	
});
</script>
<?php
//}
?>
      