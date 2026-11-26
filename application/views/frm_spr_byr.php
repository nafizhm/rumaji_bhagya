<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Pembayaran</h3>
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
                       <table id="spr" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No SPR</th>
                          <th>Blok/No</th>
                          <th>Konsumen</th>
                          <th>Status Kavling</th>
                          <th>Dibuat</th>
                          <th>Tgl Buat</th>
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
	
	 $('#spr').DataTable();
	 $("#cb_proj").change(function(){
		 
		 var table = $('#spr').DataTable();
		table
		.clear()
		.draw();
		
		var projid = $("#cb_proj option:selected").attr('value');
		if (projid.length > 0 ) { 
			$('#spr').DataTable( {
				 fixedHeader: true,
			  "processing": true,
        	  "serverSide": false,
			  "destroy": true,
        	   "ajax": {
				"url": '<?php echo site_url('byr/get_spr'); ?>',
				"type": 'POST',
				"data": {
				 "projid" : projid
					}
        		},
					"columns": [
						{ "data": "sprno" },
						{ "data": "blokno" },
						{ "data": "nama" },
						{ "data": "namstat" },
						{ "data": "crby" },
						{ "data": "crdat" },
						{ "data": "pilihan" },
					]
				
			 } );
		}
		
	 });


});
</script>
      