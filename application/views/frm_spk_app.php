<?php $this->load->view('header'); ?>
 <link href="<?php echo $this->config->item('assets');?>/bootstrap-select-1.12.3/css/bootstrap-select.min.css" rel="stylesheet">
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Detail Pemakaian Anggaran</h3>
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
                        <input type="hidden" name="txt_spkno" id="txt_spkno" value="<?php echo $txt_spkno; ?>" />
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Project
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><?php echo $txt_namproj; ?></p>
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
                           <p class="form-control-static"><?php echo $txt_namby; ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipe
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><?php echo $txt_tipe; ?></p>
                        </div>
                      </div>
                      <?php if($txt_tipe == 'WO' || $txt_tipe == 'PAI' || $txt_tipe == 'PO')
					  { ?>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_tgl; ?></p>
                        </div>
                      </div>
                      <div class="form-group">
                       <?php if($txt_tipe == 'WO')
					  { ?>
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Penerima Pekerjaan
                        </label>
                       <?php
					  }
					  else
					  {
					   ?>
                         <label class="control-label col-md-3 col-sm-3 col-xs-12">Pemohon
                        </label>
					 <?php } ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_wo_mandor; ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <?php if($txt_tipe == 'WO')
					  { ?>
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pekerjaan
                        </label>
                         <?php
					  }
					  else
					  {
					   ?>
                         <label class="control-label col-md-3 col-sm-3 col-xs-12">Keperluan
                        </label>
					 <?php } ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_ket; ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Vol
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_wo_vol; ?></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Satuan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_wo_sat; ?></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Satuan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_wo_hrg; ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><b><?php echo $txt_total; ?></b></p>
                        </div>
                      </div>
                       <div class="form-group">
                       <?php if($txt_tipe == 'WO')
					  { ?>
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Syarat dan Spek
                        </label>
                       <?php
						  }
						  else
						  {
					   ?>
                         <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan
                        </label>
					 <?php } ?>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_wo_spek; ?></p>
                        </div>
                      </div>
                      <?php 
					  }
					  elseif($txt_tipe == 'SPK') 
					  { 
					  ?>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pekerjaan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static">
						  <?php if($txt_spk_jp == 'RMH')
						  {
						  	echo 'RUMAH';
						  }
						  elseif($txt_spk_jp == 'INFRA')
						  {
							 echo 'INFRASTRUKTUR';
						  }
						  ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Penerima Pekerjaan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_spk_vendor; ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Kontrak
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_tgl; ?></p>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Kontrak
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><b><?php echo $txt_total; ?></b></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <p class="form-control-static"><?php echo $txt_ket; ?></p>
                        </div>
                      </div>
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_spk_isi">Isi Perjanjian
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea readonly="readonly" id="txt_spk_isi" name="txt_spk_isi" class="form-control col-md-7 col-xs-12" cols="50" rows="20"><?php echo $txt_spk_isi; ?></textarea>
                         
                        </div>
                      </div>
                      <?php } ?>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Status
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_status" id="cb_status" class="form-control" required >
                          <option value="">Silahkan Pilih</option>
                          <?php if($this->lib->cek_tipe_ang($txt_paid,'T') == TRUE) //Cek pos anggaran teknik
						  { 
						  
						  if ($this->session->userdata('roleid')=='AUD')
						  {?>
                         	<option value="A03">Approve</option>
                            <option value="R03">Reject</option>
                          <?php
						  }
						  else
						  {
						  ?>
                          	<option value="A02">Approve</option>
                            <option value="R02">Reject</option>
                         <?php 
						  }
						 }
						 else
						 {
						?>
                        <option value="A02">Approve</option>
                        <option value="R02">Reject</option>
                        <?php
							}
					     ?>
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
                        <?php foreach($list_spklog as $row)  
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
                           <h4 class="modal-title" id="myModalLabel2">Detail SPK</h4>
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
	 
	  
	$("#cb_status").change(function(){
		var status = $("#cb_status option:selected").attr('value');	
		
		if (status.length > 0 ) { 
			if(status == "R02" )
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
  <!-----------Load Tiny MCE Editor---------------->
<script src="<?php echo $this->config->item('assets');?>/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script type="text/javascript">
        tinymce.init({
			readonly : 0, //1 = disabled
            selector: "#txt_spk_isi",
			 plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor"
   ],toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons"
        });
    </script>