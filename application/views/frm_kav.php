<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Stok Kavling</h3>
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
                        <input type="hidden" name="txt_proj" value="<?php echo set_value('txt_proj', isset($default['txt_proj']) ? $default['txt_proj'] : ''); ?>" />
                         <input type="hidden" name="txt_status" value="<?php echo set_value('txt_status', isset($default['txt_status']) ? $default['txt_status'] : ''); ?>" />
                          <select name="cb_proj" id="cb_proj" required class="form-control" <?php if($readonly != '') { echo'disabled="disabled"'; } ?> >
                          <?php echo $proj; ?>
                          </select>
                        </div>
                      </div>
                      
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Blok
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_blok" id="txt_blok" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_blok', isset($default['txt_blok']) ? $default['txt_blok'] : ''); ?>" maxlength="3" required="required" <?php echo $readonly; ?>>
                           <input type="hidden" name="txt_blok_old" id="txt_blok_old" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_blok_old', isset($default['txt_blok']) ? $default['txt_blok'] : ''); ?>" >

            
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_no" id="txt_no" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_no', isset($default['txt_no']) ? $default['txt_no'] : ''); ?>" maxlength="4" required="required" <?php echo $readonly; ?>>
                            <input type="hidden" name="txt_no_old" id="txt_no_old" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_no_old', isset($default['txt_no']) ? $default['txt_no'] : ''); ?>" >
                            <input type="hidden" name="txt_pkav_old" id="txt_pkav_old" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_pkav_old', isset($default['txt_pkav']) ? $default['txt_pkav'] : ''); ?>" >
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Lot
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_lot" id="txt_lot" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_lot', isset($default['txt_lot']) ? $default['txt_lot'] : ''); ?>"  required="required" >
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Luas Tanah Standard
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_lt_std" id="txt_lt_std" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_lt_std', isset($default['txt_lt_std']) ? $default['txt_lt_std'] : ''); ?>" required="required" data-parsley-type="number">
                        </div>
                      </div>
        
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipe Rumah
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_tipe" id="cb_tipe" required class="form-control">
                          <?php echo $tipe; ?>
                          </select>
                        </div>
                      </div>
                      
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Fitur
                        </label>
                         <div class="col-md-6 col-sm-6 col-xs-12">
                         
                       <input type="checkbox" name="ch_ft[]" id="blv" value="BLV" <?php echo  set_checkbox('blv', 'BLV', isset($default['blv']) ? true : ''); ?> /> Boulevard
                       <br />
                       <input type="checkbox" name="ch_ft[]" id="hok" value="HOK" <?php echo  set_checkbox('hok', 'HOK', isset($default['hok']) ? true : ''); ?>/> Hook
                       <br />
                       <input type="checkbox" name="ch_ft[]" id="tle" value="TLE" <?php echo  set_checkbox('tle', 'TLE', isset($default['tle']) ? true : ''); ?> /> Tanah lebih
                        <input name="txt_lt_lbh" id="txt_lt_lbh" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_lt_lbh', isset($default['txt_lt_lbh']) ? $default['txt_lt_lbh'] : ''); ?>" required="required" data-parsley-type="number" placeholder="Input luas tanah lebih">
                       </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Progress Bangun (%)
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_pkav" id="txt_pkav" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_pkav', isset($default['txt_pkav']) ? $default['txt_pkav'] : ''); ?>" data-parsley-type="number">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tahun Bangun
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_tahun" id="txt_tahun" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_tahun', isset($default['txt_tahun']) ? $default['txt_tahun'] : ''); ?>" data-parsley-type="number">
                        </div>
                      </div>

                      
<!-- BAMS 11-03-2025 START -->
<!-- <div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="radio">
            <label>
                <input type="radio" name="rd_jenis" value="Subsidi" <?php echo set_radio('rd_jenis', 'Subsidi', isset($default['rd_jenis']) ? ($default['rd_jenis'] == 'Subsidi') : TRUE); ?> /> Subsidi
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="rd_jenis" value="Komersial" <?php echo set_radio('rd_jenis', 'Komersial', isset($default['rd_jenis']) ? ($default['rd_jenis'] == 'Komersial') : FALSE); ?> /> Komersial
            </label>
        </div>
    </div>
</div> -->
<!-- BAMS 11-03-2025 END -->



                     <?php if($readonly != '')
                      {
					?>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Status
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        
                         <b> <font color="#FF0000"><?php echo $status; ?> </b> </font>
                          <!-- Small modal -->
                         <button type="button" class="btn btn-info" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-clock-o"></i> History</button>
                         <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel2">History Status</h4>
                        </div>
                        <div class="modal-body">
                         <table class="table table-striped table-bordered">
                     	 <thead>
                        <tr>
                          <th>Status</th>
                          <th>Keterangan</th>
                          <th>Dibuat Oleh</th>
                          <th>Tgl Buat</th>
                          <th>Jam Buat</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($list_kavlog as $row)  
						{
						?>
                        <tr>
                          <td><?php echo $this->lib->get_status_text('KAV',$row->status); ?></td>
                           <td><?php echo $row->ket; ?></td>
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
                  
                        </div>
                      </div>
                      <?php
					  }
					  ?>
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
<script type="text/javascript">
$(document).ready(function() {
   
   if ($('#txt_lt_lbh').val().length == 0) {
	   $( "#txt_lt_lbh" ).prop( "readonly", 'readonly' );
	   $( "#txt_lt_lbh" ).prop( "required", "" );
   }
   
    $('#tle').change(function() {
        if(this.checked) {
         	$( "#txt_lt_lbh" ).prop( "readonly", '' );
			$( "#txt_lt_lbh" ).prop( "required", "required" );
        }
		else
		{
			$( "#txt_lt_lbh" ).prop( "required", "" );
			$('#txt_lt_lbh').val('');
			 $( "#txt_lt_lbh" ).prop( "readonly", 'readonly' );
		}
    });
});
</script>
      