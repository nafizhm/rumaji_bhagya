<?php $this->load->view('header'); ?>
 <link href="<?php echo $this->config->item('assets');?>/bootstrap-select-1.12.3/css/bootstrap-select.min.css" rel="stylesheet">
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Detail Permintaan Dana</h3>
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
                    <form id="form1" class="form-horizontal form-label-left" method="post" action="<?php echo $form_action; ?>">
                     
                      <div class="form-group">
                      <input type="hidden" name="txt_projid" id="txt_projid" value="<?php echo $txt_projid; ?>" />
                       <input type="hidden" name="txt_reqno" id="txt_reqno" value="<?php echo $txt_reqno; ?>" />
                        <input type="hidden" name="txt_spkno" id="txt_spkno" value="<?php echo $txt_spkno; ?>" />
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Project
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"> <?php echo $txt_namproj; ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pos Anggaran
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_nampa; ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"> <?php echo $txt_namby; ?></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">No. SPK/WO/PAI
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><a href="#" id="btn_spk"><?php if($txt_spkno != 0) {echo $this->lib->format_spkno($txt_projid,$txt_spkno);  }?></a></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah Permintaan Dana
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"> <?php echo $txt_total; ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"> <?php echo $txt_ket; ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Status
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_status" id="cb_status" class="form-control" required >
                          <option value="">Silahkan Pilih</option>
                          <?php if($this->session->userdata('roleid') == 'FIN')
						  {
						?>
                         	<option value="A01">Approve</option>
                            <option value="R01">Reject</option>
                              <?php } 
							  elseif($this->session->userdata('roleid') == 'AUD')
						  {
						?>
                       		<option value="A03">Approve</option>
                            <option value="R03">Reject</option>
                            <?php } else { ?>
                            <option value="A02">Approve</option>
                            <option value="R02">Reject</option>
                            <?php }  ?>
                          </select>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan
                        </label> 
                        <div class="col-md-6 col-sm-6 col-xs-12">
                             <input type="text" name="txt_ket" id="txt_ket" maxlength="100" class="form-control col-md-7 col-xs-12" readonly="readonly" />
                        </div>
                      </div>
 					<div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href ="<?php echo $url_back; ?>"  class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>
                          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                          <!-- Small modal -->
                     <button type="button" class="btn btn-info" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-clock-o"></i> History</button>
                      <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel2">History Approval</h4>
                        </div>
                        <div class="modal-body">
                         <table class="table table-striped table-bordered">
                     	 <thead>
                        <tr>
                          <th>Status</th>
                          <th>Keterangan</th>
                          <th>Di Buat Oleh</th>
                          <th>Tgl Buat</th>
                          <th>Jam Buat</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($list_dnlog as $row)  
						{
						?>
                        <tr>
                          <td><?php echo $this->lib->get_status_text('APP',$row->status); ?></td>
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
                   <!-- SPK Small modal -->
                     
                      <div class="modal fade bs-spk-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="myModal">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                           <h4 class="modal-title" id="myModalLabel2">Detail Pemakaian Anggaran</h4>
                        </div>
                        <div class="modal-body">
                        <div id="spk"></div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        </div>

                      </div>
                    </div>
                  </div>
                  <!-- /SPK modals -->
                  
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
$(document).ready(function(){
	  $('#btn_spk').click (function () {
		  $("#myModal").modal();
		  
		  $.ajax({
            type: "POST",
            url: "<?php echo site_url('spk/detail_spk'); ?>",
            data: $('#form1').serialize(),
            cache: false,
            beforeSend: function () { 
                $('#spk').html('Loading....');
            },
            success: function(html) {    
                $("#spk").html( html );
            },
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
			 $('#spk').html(errorThrown);
    			}       
        });
		
	  });
	  
	$("#cb_status").change(function(){
		var status = $("#cb_status option:selected").attr('value');	
		
		if (status.length > 0 ) { 
			if(status == "R01" || status == "R02" )
			{
				$( "#txt_ket" ).prop( "readonly", '' );
				$( "#txt_ket" ).prop( "required", "required" );
			}
			else
			{
				$( "#txt_ket" ).prop( "readonly", 'readonly' );
				$( "#txt_ket" ).prop( "required", "" );
			}
		}
		else
		{
			$( "#txt_ket" ).prop( "readonly", 'readonly' );
			$( "#txt_ket" ).prop( "required", "" );
		}
	});
	
});
</script>