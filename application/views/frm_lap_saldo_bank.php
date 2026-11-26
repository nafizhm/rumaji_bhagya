<?php $this->load->view('header'); ?>
 <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Project<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_proj" id="cb_proj" required class="form-control">
                          <option value="">Silahkan Pilih</option>
                          <?php echo $proj; ?>
                          </select>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bank<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_bank" id="cb_bank" required class="form-control">
                          <option value="">Silahkan Pilih</option>
                          <div id="cb_bank"></div>
                          </select>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_mg">Pilih Minggu<span class="required">*</span>
                        </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_mg" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_mg" >
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
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="<?php echo $this->config->item('assets');?>/weekPicker.js"></script>
<script type="text/javascript">
convertToWeekPicker($("#txt_mg"));
$(document).ready(function(){
	
	$("#cb_proj").change(function(){
		
		var projid = $("#cb_proj option:selected").attr('value');
		//alert(projid.length);
		if (projid.length > 0 ) { 
			
			 $.ajax({
            type: "POST",
            url: "<?php echo site_url('saldo/get_bank'); ?>",
            data: "projid="+projid,
            cache: false,
            beforeSend: function () { 
                $('#cb_bank').html('<option>Loading....</option>');
            },
            success: function(html) {    
                $("#cb_bank").html( html );
            }
				});
				
		}
		 else {
				$("#cb_bank").html( "" );
			}
	
		});

});
</script>
      