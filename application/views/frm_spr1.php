<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>SPR</h3>
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
                    <form id="demo-form2" class="form-horizontal form-label-left" method="post">
                     
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Project
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_proj" id="cb_proj" required class="form-control">
                          <option value="">Silahkan pilih</option>
                          <?php echo $proj; ?>
                          </select>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Lot
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_lot" id="cb_lot" required class="form-control">
                          <div id="cb_lot"></div>
                          </select>
                        </div>
                      </div>
                      
                       <div class="form-group">
                       <table id="stokkav" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Blok/No</th>
                          <th>LT</th>
                          <th>Tipe</th>
                          <th>Fitur</th>
                          <th>Hrg Standard</th>
                          <th>Hrg Fitur (Total)</th>
                          <th>Hrg Total</th>
                          <th>Pilihan</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
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
	
	 $('#stokkav').DataTable();
$("#cb_proj").change(function(){
	var table = $('#stokkav').DataTable();
	table
    .clear()
    .draw();
	
	var projid = $("#cb_proj option:selected").attr('value');
	
	//alert(projid);
	
		if (projid.length > 0 ) { 
     $.ajax({
            type: "POST",
            url: "<?php echo site_url('spr/get_lot'); ?>",
            data: "projid="+projid,
            cache: false,
            beforeSend: function () { 
                $('#cb_lot').html('<option>Loading....</option>');
            },
            success: function(html) {    
                $("#cb_lot").html( html );
            }
        });
    } else {
        $("#cb_lot").html( "" );
    }
	
	$("#cb_lot").change(function(){
		var projid = $("#cb_proj option:selected").attr('value');
		var lot = $("#cb_lot option:selected").attr('value');
	
		if (lot.length > 0 ) { 
		
		//$("#stokkav").dataTable().fnDestroy();
		 $('#stokkav').DataTable( {
			  "processing": true,
        	  "serverSide": false,
			  "destroy": true,
        	   "ajax": {
				"url": '<?php echo site_url('spr/get_kav'); ?>',
				"type": 'POST',
				"data": {
				 "projid" : projid,
				 "lot" : lot
					}
        		},
					"columns": [
						{ "data": "blok" },
						{ "data": "ltstd" },
						{ "data": "tipe" },
						{ "data": "fitur" },
						{ "data": "hargastd" },
						{ "data": "hargaft" },
						{ "data": "hargatot" },
						{ "data": "pilihan" },
					]
				
			 } );
		
			
		}
	});
	
	
	
	
});



});
</script>
      