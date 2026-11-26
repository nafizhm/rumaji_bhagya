<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Pemakaian Anggaran</h3>
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
                     <input name="txt_spkno" type="hidden" required="required" class="form-control col-md-7 col-xs-12" id="txt_spkno" value="<?php echo set_value('txt_spkno', isset($default['txt_spkno']) ? $default['txt_spkno'] : ''); ?>" >
                      <input name="txt_projid" type="hidden" required="required" class="form-control col-md-7 col-xs-12" id="txt_projid" value="<?php echo set_value('txt_projid', isset($default['txt_projid']) ? $default['txt_projid'] : ''); ?>" >
                      <input name="txt_tipe" type="hidden" required="required" class="form-control col-md-7 col-xs-12" id="txt_tipe" value="<?php echo set_value('txt_tipe', isset($default['txt_tipe']) ? $default['txt_tipe'] : ''); ?>" >
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Project
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_proj" id="cb_proj" required class="form-control" >
                          <?php echo $cb_proj; ?>
                          </select>
                          <input type="hidden" name="txt_proj" value="<?php echo set_value('txt_proj', isset($default['txt_proj']) ? $default['txt_proj'] : ''); ?>" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pos Anggaran
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_pa" id="cb_pa" required class="form-control" >
                          <option value="">Silahkan Pilih</option>
                          <?php echo $cb_pa; ?>
                          </select>
                        </div>
                      </div>
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_by" id="cb_by" required class="form-control" >
                          <div id="cb_by"> <?php echo $cb_by; ?></div>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipe
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_tipe" id="cb_tipe" required class="form-control" <?php echo $disabled; ?>>
                          <option value="">Silahkan Pilih</option>
                         	<option value="SPK" <?php echo set_select('cb_tipe','SPK', ( !empty($default['txt_tipe']) && $default['txt_tipe'] == "SPK" ? TRUE : FALSE )); ?>>Surat Perjanjian Kerja</option>
                          	<option value="WO" <?php echo set_select('cb_tipe','WO', ( !empty($default['txt_tipe']) && $default['txt_tipe'] == "WO" ? TRUE : FALSE )); ?>>Work Order</option>
                            <option value="PAI" <?php echo set_select('cb_tipe','PAI', ( !empty($default['txt_tipe']) && $default['txt_tipe'] == "PAI" ? TRUE : FALSE )); ?>>Persetujuan Anggaran Internal</option>
                             <option value="PO" <?php echo set_select('cb_tipe','PO', ( !empty($default['txt_tipe']) && $default['txt_tipe'] == "PO" ? TRUE : FALSE )); ?>>Purchase Order</option>
                          </select>
                           <input type="text" name="txt_konid" id="txt_konid" class="form-control col-md-7 col-xs-12 typeahead"  data-provide="typeahead" value="<?php echo set_value('txt_konid', isset($default['txt_konid']) ? $default['txt_konid'] : ''); ?>" required="required" readonly="readonly" style="visibility:hidden">
                        </div>
                      </div>
                       <fieldset id="wo">
  <legend></legend>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_wo_tgl">Tanggal<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_wo_tgl" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_wo_tgl"value="<?php echo set_value('txt_wo_tgl', isset($default['txt_wo_tgl']) ? $default['txt_wo_tgl'] : ''); ?>" >
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_wo_mandor">Mandor<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_wo_mandor" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_wo_mandor" value="<?php echo set_value('txt_wo_mandor', isset($default['txt_wo_mandor']) ? $default['txt_wo_mandor'] : ''); ?>" maxlength="80" autocomplete="off" >
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_wo_krj">Pekerjaan<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_wo_krj" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_wo_krj"value="<?php echo set_value('txt_wo_krj', isset($default['txt_wo_krj']) ? $default['txt_wo_krj'] : ''); ?>" >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_wo_vol">Volume<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_wo_vol" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_wo_vol"value="<?php echo set_value('txt_wo_vol', isset($default['txt_wo_vol']) ? $default['txt_wo_vol'] : ''); ?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cb_wo_sat">Satuan<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_wo_sat" class="form-control"> 
                          <option value="M1" <?php echo set_select('cb_wo_sat','M1', ( !empty($default['cb_wo_sat']) && $default['cb_wo_sat'] == "M1" ? TRUE : FALSE )); ?>>M¹</option>
                          <option value="M2" <?php echo set_select('cb_wo_sat','M2', ( !empty($default['cb_wo_sat']) && $default['cb_wo_sat'] == "M2" ? TRUE : FALSE )); ?>>M²</option>
                           <option value="M3" <?php echo set_select('cb_wo_sat','M3', ( !empty($default['cb_wo_sat']) && $default['cb_wo_sat'] == "M3" ? TRUE : FALSE )); ?>>M³</option>
                          <option value="PCS" <?php echo set_select('cb_wo_sat','PCS', ( !empty($default['cb_wo_sat']) && $default['cb_wo_sat'] == "PCS" ? TRUE : FALSE )); ?>>PCS (PIECES/BUAH)</option>
                        
                          <option value="TTK" <?php echo set_select('cb_wo_sat','TTK', ( !empty($default['cb_wo_sat']) && $default['cb_wo_sat'] == "TTK" ? TRUE : FALSE )); ?>>TTK</option>
                          
                           <option value="LS" <?php echo set_select('cb_wo_sat','LS', ( !empty($default['cb_wo_sat']) && $default['cb_wo_sat'] == "LS" ? TRUE : FALSE )); ?>>LS</option>
                            <option value="UNIT" <?php echo set_select('cb_wo_sat','UNIT', ( !empty($default['cb_wo_sat']) && $default['cb_wo_sat'] == "UNIT" ? TRUE : FALSE )); ?>>UNIT</option>
                             <option value="RIT" <?php echo set_select('cb_wo_sat','RIT', ( !empty($default['cb_wo_sat']) && $default['cb_wo_sat'] == "RIT" ? TRUE : FALSE )); ?>>RIT</option>
                          </select>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_wo_hrg">Harga Satuan<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                         <input name="txt_wo_hrg" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_wo_hrg"value="<?php echo set_value('txt_wo_hrg', isset($default['txt_wo_hrg']) ? $default['txt_wo_hrg'] : ''); ?>" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_wo_tot">Total<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_wo_tot" type="text" readonly="readonly" required="required" class="form-control col-md-7 col-xs-12" id="txt_wo_tot" value="<?php echo set_value('txt_wo_tot', isset($default['txt_wo_tot']) ? $default['txt_wo_tot'] : ''); ?>" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true" >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_wo_spek">Syarat Dan Spek
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea name="txt_wo_spek" class="form-control col-md-7 col-xs-12"><?php echo set_value('txt_wo_spek', isset($default['txt_wo_spek']) ? $default['txt_wo_spek'] : ''); ?></textarea>
                        </div>
                      </div>
                      </fieldset>
                       <fieldset id="spk">
  					   <legend>Surat Perjanjian Kerja</legend>
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cb_spk_jp">Jenis Pekerjaan<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_spk_jp" class="form-control"> 
                          <option value="RMH" <?php echo set_select('cb_spk_jp','RMH', ( !empty($default['cb_spk_jp']) && $default['cb_spk_jp'] == "RMH" ? TRUE : FALSE )); ?>>RUMAH</option>
                        <option value="INFRA" <?php echo set_select('cb_spk_jp','INFRA', ( !empty($default['cb_spk_jp']) && $default['cb_spk_jp'] == "INFRA" ? TRUE : FALSE )); ?>>INFRASTRUKTUR</option>
                        <option value="KIOS" <?php echo set_select('cb_spk_jp','KIOS', ( !empty($default['cb_spk_jp']) && $default['cb_spk_jp'] == "KIOS" ? TRUE : FALSE )); ?>>KIOS</option>
                          </select>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_spk_vendor">Penerima Pekerjaan<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_spk_vendor" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_spk_vendor" value="<?php echo set_value('txt_spk_vendor', isset($default['txt_spk_vendor']) ? $default['txt_spk_vendor'] : ''); ?>" maxlength="80" autocomplete="off" >
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_spk_tgl">Tanggal Kontrak<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_spk_tgl" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_spk_tgl" value="<?php echo set_value('txt_spk_tgl', isset($default['txt_spk_tgl']) ? $default['txt_spk_tgl'] : ''); ?>" >
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_spk_tot">Total Kontrak<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_spk_tot" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_spk_tot" value="<?php echo set_value('txt_spk_tot', isset($default['txt_spk_tot']) ? $default['txt_spk_tot'] : ''); ?>" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true" >
                        </div>
                      </div>
                    
                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_spk_ket">Keterangan
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_spk_ket" id="txt_spk_ket" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_spk_ket', isset($default['txt_spk_ket']) ? $default['txt_spk_ket'] : ''); ?>" maxlength="100" >
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_spk_isi">Isi Perjanjian
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <textarea id="txt_spk_isi" name="txt_spk_isi" class="form-control col-md-7 col-xs-12" cols="50" rows="20"><?php echo set_value('txt_spk_isi', isset($default['txt_spk_isi']) ? $default['txt_spk_isi'] : ''); ?></textarea>
                         
                        </div>
                      </div>
                        
  					   </fieldset>
                      
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


$(document).ready(function(){

if($("#cb_tipe").val() == 'SPK')
			{
				$('#txt_spk_tot').attr("required","required");
				$('#txt_spk_tgl').attr("required","required");
				$('#txt_spk_vendor').attr("required","required");
				
				$('#txt_wo_tgl').removeAttr("required");
				$('#txt_wo_mandor').removeAttr("required");
				$('#txt_wo_krj').removeAttr("required");
				$('#txt_wo_vol').removeAttr("required");
				$('#txt_wo_hrg').removeAttr("required");
				$('#txt_wo_tot').removeAttr("required");
				
				 $("#wo").hide();
				 $("#spk").show();
				 
				  $('input[name="txt_spk_tgl"]').daterangepicker({
					singleDatePicker: true,
					showDropdowns: true,
					autoUpdateInput: false/*,
					 locale: {
						format: 'DD/MM/YYYY'
					}*/
    			});
	
	$('input[name="txt_spk_tgl"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
  
				 //alert($("#cb_tipe").val());
			}
			else if($("#cb_tipe").val() == 'WO' || $("#cb_tipe").val() == 'PO')
			{
				$('#txt_spk_tot').removeAttr("required");
				$('#txt_spk_tgl').removeAttr("required");
				$('#txt_spk_vendor').removeAttr("required");
				
				$('#txt_wo_tgl').attr("required","required");
				$('#txt_wo_mandor').attr("required","required");
				$('#txt_wo_krj').attr("required","required");
				$('#txt_wo_vol').attr("required","required");
				$('#txt_wo_hrg').attr("required","required");
				$('#txt_wo_tot').attr("required","required");
				
				//alert($("#cb_tipe").val());
				if($("#cb_tipe").val() == 'WO')
				{
					$("fieldset#wo legend").html("Work Order");
				}
				else
				{
					$("fieldset#wo legend").html("Purchase Order");	
				}
				
				$("label[for='txt_wo_mandor']").text("Penerima Pekerjaan*");
				$("label[for='txt_wo_spek']").text("Syarat Dan Spek");
				$("label[for='txt_wo_krj']").text("Pekerjaan*");
				
				$('input[name="txt_wo_tgl"]').daterangepicker({
					singleDatePicker: true,
					showDropdowns: true,
					autoUpdateInput: false,
					 locale: {
						format: 'DD/MM/YYYY'
					}
    			});
	
	$('input[name="txt_wo_tgl"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
  
				 $("#wo").show();
				 $("#spk").hide();
			}
			else if($("#cb_tipe").val() == 'PAI')
			{
				$('#txt_spk_tot').removeAttr("required");
				$('#txt_spk_tgl').removeAttr("required");
				$('#txt_spk_vendor').removeAttr("required");
				
				$('#txt_wo_tgl').attr("required","required");
				$('#txt_wo_mandor').attr("required","required");
				$('#txt_wo_krj').attr("required","required");
				$('#txt_wo_vol').attr("required","required");
				$('#txt_wo_hrg').attr("required","required");
				$('#txt_wo_tot').attr("required","required");
				
				$("label[for='txt_wo_mandor']").text("Pemohon*");
				$("label[for='txt_wo_spek']").text("Keterangan");
				$("label[for='txt_wo_krj']").text("Keperluan*");
				//alert($("#cb_tipe").val());
				$("fieldset#wo legend").html("Persetujuan Anggaran Internal");
				$('input[name="txt_wo_tgl"]').daterangepicker({
					singleDatePicker: true,
					showDropdowns: true,
					autoUpdateInput: false,
					 locale: {
						format: 'DD/MM/YYYY'
					}
    			});
	
	$('input[name="txt_wo_tgl"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
				 $("#wo").show();
				 $("#spk").hide();
			}
			else
			{
				$("#wo").hide();
				$("#spk").hide();
			}
			
$("#cb_tipe").change(function(){
			//alert($("#cb_tipe").val());
			if($("#cb_tipe").val() == 'SPK')
			{
				$('#txt_konid').val("");
				
				$('#txt_spk_tot').attr("required","required");
				$('#txt_spk_tgl').attr("required","required");
				$('#txt_spk_vendor').attr("required","required");
				
				$('#txt_wo_tgl').removeAttr("required");
				$('#txt_wo_mandor').removeAttr("required");
				$('#txt_wo_krj').removeAttr("required");
				$('#txt_wo_vol').removeAttr("required");
				$('#txt_wo_hrg').removeAttr("required");
				$('#txt_wo_tot').removeAttr("required");
				
				
				 $("#wo").hide();
				 $("#spk").show();
				 
				 $('input[name="txt_spk_tgl"]').daterangepicker({
					singleDatePicker: true,
					showDropdowns: true,
					autoUpdateInput: false/*,
					 locale: {
						format: 'DD/MM/YYYY'
					}*/
    			});
	
	$('input[name="txt_spk_tgl"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
  
				 //alert($("#cb_tipe").val());
			}
			else if($("#cb_tipe").val() == 'WO' || $("#cb_tipe").val() == 'PO')
			{
				$('#txt_konid').val("");
				
				$('#txt_spk_tot').removeAttr("required");
				$('#txt_spk_tgl').removeAttr("required");
				$('#txt_spk_vendor').removeAttr("required");
				
				$('#txt_wo_tgl').attr("required","required");
				$('#txt_wo_mandor').attr("required","required");
				$('#txt_wo_krj').attr("required","required");
				$('#txt_wo_vol').attr("required","required");
				$('#txt_wo_hrg').attr("required","required");
				$('#txt_wo_tot').attr("required","required");
				
				//alert($("#cb_tipe").val());
				if($("#cb_tipe").val() == 'WO')
				{
					$("fieldset#wo legend").html("Work Order");
				}
				else
				{
					$("fieldset#wo legend").html("Purchase Order");	
				}
				
				$("label[for='txt_wo_mandor']").text("Penerima Pekerjaan*");
				$("label[for='txt_wo_spek']").text("Syarat Dan Spek");
				$("label[for='txt_wo_krj']").text("Pekerjaan*");
				
				$('input[name="txt_wo_tgl"]').daterangepicker({
					singleDatePicker: true,
					showDropdowns: true,
					autoUpdateInput: false/*,
					 locale: {
						format: 'DD/MM/YYYY'
					}*/
    			});
	
	$('input[name="txt_wo_tgl"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
  
				 $("#wo").show();
				 $("#spk").hide();
			}
			else if($("#cb_tipe").val() == 'PAI')
			{
				$('#txt_konid').val("");
				
				$('#txt_spk_tot').removeAttr("required");
				$('#txt_spk_tgl').removeAttr("required");
				$('#txt_spk_vendor').removeAttr("required");
				
				$('#txt_wo_tgl').attr("required","required");
				$('#txt_wo_mandor').attr("required","required");
				$('#txt_wo_krj').attr("required","required");
				$('#txt_wo_vol').attr("required","required");
				$('#txt_wo_hrg').attr("required","required");
				$('#txt_wo_tot').attr("required","required");
				
				$("fieldset#wo legend").html("Persetujuan Anggaran Internal");
				$("label[for='txt_wo_mandor']").text("Pemohon*");
				$("label[for='txt_wo_spek']").text("Keterangan");
				$("label[for='txt_wo_krj']").text("Keperluan*");
				
				$('input[name="txt_wo_tgl"]').daterangepicker({
					singleDatePicker: true,
					showDropdowns: true,
					autoUpdateInput: false/*,
					 locale: {
						format: 'DD/MM/YYYY'
					}*/
    			});
	
	$('input[name="txt_wo_tgl"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
  
				 $("#wo").show();
				 $("#spk").hide();
			}
			else
			{
				$("#wo").hide();
				$("#spk").hide();
			}
	});
	
	function hitung()
	{
		var hrg = $("#txt_wo_hrg").val();
		var vol = $("#txt_wo_vol").val();
		
		
		while(hrg.indexOf('.')!=-1)hrg= hrg.replace('.','');
		while(vol.indexOf(',')!=-1)vol= vol.replace(',','.');
		
		var total =  parseFloat(vol) * parseFloat(hrg);
		//alert(parseFloat(hrg.replace('.', '')));
		$("#txt_wo_tot").val(total);
	}
	$( "#txt_wo_vol" ).keyup(function() {
		
		hitung();
	});
	
	$( "#txt_wo_hrg" ).keyup(function() {
		hitung();
		
	});
	
$("#cb_proj").change(function(){
			$("#cb_by").html( "" );
			$("#cb_spk").html( "" );
	});
	
	$("#cb_pa").change(function(){
		//$('#txt_konid').val("");
		var paid = $("#cb_pa option:selected").attr('value');	
		var projid = $("#cb_proj option:selected").attr('value');	
		if (paid.length > 0 ) { 
			//alert(paid);
			  $.ajax({
				type: "POST",
				url: "<?php echo site_url('spk/get_by'); ?>",
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
    	}
		
	});
	
});
</script>
<script src="<?php echo $this->config->item('assets');?>/Bootstrap-3-Typeahead-4.0.2/bootstrap3-typeahead.js"></script>
 <script>
 $(document).ready(function () {
        $('#txt_wo_mandor').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "<?php echo site_url('kon/get_kon'); ?>",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							// $('#txt_konid').val(item.id);
							return item;
                        }));
                    }
                });
            } ,updater: function(item){
			$('#txt_konid').val(item.id);
			return item;
        //alert("You selected: " + item.id)
    } 
        });
		
		$('#txt_spk_vendor').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "<?php echo site_url('kon/get_kon'); ?>",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							/* $('#txt_konid').val(item.id);*/
							return item;
                        }));
                    }
                });
            }
        ,updater: function(item){
			$('#txt_konid').val(item.id);
			return item;
        //alert("You selected: " + item.id)
    } });
		
    });
    </script>
      <!-----------Load Tiny MCE Editor---------------->
<script src="<?php echo $this->config->item('assets');?>/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script type="text/javascript">
        tinymce.init({
            selector: "#txt_spk_isi",
			 plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
         "save table contextmenu directionality emoticons template paste textcolor"
   ],toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons"
        });
    </script>