<?php $this->load->view('header'); ?>
 <link href="<?php echo $this->config->item('assets');?>/bootstrap-select-1.12.3/css/bootstrap-select.min.css" rel="stylesheet">
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Permintaan Dana</h3>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Project
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="hidden" name="txt_reqno" id="txt_reqno" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_reqno', isset($default['txt_reqno']) ? $default['txt_reqno'] : ''); ?>">
                        <input type="hidden" name="txt_status" id="txt_status" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_status', isset($default['txt_status']) ? $default['txt_status'] : ''); ?>">
                          <select name="cb_proj" id="cb_proj" required class="form-control">
                          <?php echo $cb_proj; ?>
                          </select>
                          <input type="hidden" name="txt_proj" value="<?php echo set_value('txt_proj', isset($default['txt_proj']) ? $default['txt_proj'] : ''); ?>" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pos Anggaran
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_pa" id="cb_pa" required class="form-control">
                          <option value="">Silahkan Pilih</option>
                          <?php echo $cb_pa; ?>
                          </select>
                        </div>
                      </div>
              
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_by" id="cb_by" required class="form-control">
                          <div id="cb_by"> <?php echo $cb_by; ?></div>
                          </select>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tgl Transaksi
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_tgl_trans" id="txt_tgl_trans" class="form-control col-md-7 col-xs-12" required="required" value="<?php echo set_value('txt_tgl_trans', isset($default['txt_tgl_trans']) ? $default['txt_tgl_trans'] : ''); ?>">
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">SPK/WO/PAI</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_spk" id="cb_spk" class="form-control selectpicker" data-live-search="true" required >
                          <?php echo $cb_spk; ?>
                          </select>
                        </div>
                      </div>
                      <!---
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kontraktor/Penerima
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input required type="text" name="txt_namkon" id="txt_namkon" maxlength="100" class="form-control col-md-7 col-xs-12 typeahead" placeholder="Nama Kontraktor/Penerima" data-provide="typeahead" autocomplete="off" value="<?php //echo set_value('txt_namkon', isset($default['txt_namkon']) ? $default['txt_namkon'] : ''); ?>">
             <input type="hidden" name="txt_konid" id="txt_konid" class="form-control col-md-7 col-xs-12 typeahead"  data-provide="typeahead" value="<?php //echo set_value('txt_konid', isset($default['txt_konid']) ? $default['txt_konid'] : ''); ?>">
                        </div>
                      </div>
                      ----->
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_total">Total <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_total" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_total" value="<?php echo set_value('txt_total', isset($default['txt_total']) ? $default['txt_total'] : ''); ?>" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true" >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_ket">Keterangan<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                         <!-- <input name="txt_ket" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_ket" maxlength="100" value="<?php //echo set_value('txt_ket', isset($default['txt_ket']) ? $default['txt_ket'] : ''); ?>" > -->
                         <textarea rows="4" cols="50" required="required"  class="form-control col-md-7 col-xs-12" name="txt_ket" id="txt_ket"><?php echo set_value('txt_ket', isset($default['txt_ket']) ? $default['txt_ket'] : ''); ?>
                             </textarea>
                        </div>
                      </div>
 					<div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a href ="<?php echo $url_back; ?>"  class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>
                          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                           <?php if(isset($default['txt_reqno']))
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
<script src="<?php echo $this->config->item('assets');?>/bootstrap-select-1.12.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript">


$(document).ready(function(){

 $('input[name="txt_tgl_trans"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
		autoUpdateInput: false/*,
		 locale: {
            format: 'DD/MM/YYYY'
        }*/
    });
	
	$('input[name="txt_tgl_trans"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
  
$("#cb_proj").change(function(){
			$("#cb_by").html( "" );
			$('.selectpicker').selectpicker();
				$('.selectpicker').html('');
				$(".selectpicker").selectpicker('refresh');
	});
	
	$("#cb_pa").change(function(){
		var paid = $("#cb_pa option:selected").attr('value');	
		var projid = $("#cb_proj option:selected").attr('value');	
		$('.selectpicker').selectpicker();
				$('.selectpicker').html('');
				$(".selectpicker").selectpicker('refresh');
		if (paid.length > 0 ) { 
			//alert(paid);
			  $.ajax({
				type: "POST",
				url: "<?php echo site_url('dana/get_by'); ?>",
				data: "paid="+paid+"&projid="+projid,
				cache: false,
				beforeSend: function () { 
					$('#cb_by').html('<option>Loading....</option>');
				},
				success: function(html) {    
					$("#cb_by").html( html );
				}
			});
		}
		else {
        	$("#cb_by").html( "" );
			$('.selectpicker').selectpicker();
				$('.selectpicker').html('');
				$(".selectpicker").selectpicker('refresh');
    	}
		
	});
	
	$("#cb_by").change(function(){
		var paid = $("#cb_pa option:selected").attr('value');	
		var projid = $("#cb_proj option:selected").attr('value');	
		var byid = $("#cb_by option:selected").attr('value');	
		if (byid.length > 0 ) { 
			//alert(paid);
			  $.ajax({
				type: "POST",
				url: "<?php echo site_url('spk/get_spk'); ?>",
				data: "paid="+paid+"&projid="+projid+"&byid="+byid,
				cache: false,
				dataType: 'json',
				beforeSend: function () { 
					//$('#cb_spk').html('<option>Loading....</option>');
				},
				success: function(data) {   
				
				$('.selectpicker').selectpicker();
				$('.selectpicker').html('');
				var toAppend = '<option value="">Silahkan Pilih</option>';
				 $.each(data, function(index, element) {
					 toAppend += '<option value='+element.id+'>'+element.name+'</option>';
					  console.log(toAppend);
				 });
				 
				  $('.selectpicker').append(toAppend);
				  $(".selectpicker").selectpicker('refresh');
					//$("#cb_spk").html( html );
				}
			});
		}
		else {
        	$('.selectpicker').selectpicker();
			$('.selectpicker').html('');
			 $(".selectpicker").selectpicker('refresh');
    	}
		
	});
	
});
</script>
<!--
<script src="<?php //echo $this->config->item('assets');?>/Bootstrap-3-Typeahead-4.0.2/bootstrap3-typeahead.js"></script>
 <script>
 $(document).ready(function () {
        $('#txt_namkon').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "<?php //echo site_url('kon/get_kon'); ?>",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							 $('#txt_konid').val(item.id);
							return item;
                        }));
                    }
                });
            }
        });
    });
    </script>
--->