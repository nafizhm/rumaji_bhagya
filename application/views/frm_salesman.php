<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Salesman</h3>
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
                    <form id="demo-form2" class="form-horizontal form-label-left" method="post" action="<?php echo $form_action; ?>" novalidate>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Salesman
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_kode" id="txt_kode" class="form-control col-md-7 col-xs-12" 
                          value=""  required="required" maxlength="4" style="width:100px" >
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Salesman
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_nama" id="txt_nama" class="form-control col-md-7 col-xs-12" 
                          value=""  required="required" maxlength="50"  >
                        </div>
                      </div>
                      
                      <div class="ln_solid"></div>
                      <div class="form-group" style="margin-bottom: 50px;">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                         <!-- <a href ="?php echo $url_back; ?>"  class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a> -->
                          <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                        </div>
                      </div>

                      <?php
                      $query = $this->db->query("SELECT kodeslsman,namaslsman FROM msalesman order by kodeslsman");
                      
                      echo '<table  class="table table-bordered table-striped table-hover">';
                      echo '<thead>';
                      echo '<tr>';
                      echo '<th>Kode Salesman</th>';
                      echo '<th>Nama Salesman</th>';
                      echo '</tr>';
                      echo '</thead>';
                      echo '<tbody>';
                      
                      foreach ($query->result() as $row) {
                          echo '<tr>';
                          echo '<td>' . htmlspecialchars($row->kodeslsman) . '</td>';
                          echo '<td>' . htmlspecialchars($row->namaslsman) . '</td>';
                          echo '</tr>';
                      }
                      
                      echo '</tbody>';
                      echo '</table>';
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

<script type="text/javascript">

$('#txt_kode').on('input', function () {
    this.value = this.value.toUpperCase();
});

// $(function() {
//     $('input[name="txt_tgl"]').daterangepicker({
//         singleDatePicker: true,
//         showDropdowns: true,
// 		autoUpdateInput: false/*,
// 		 locale: {
//             format: 'DD/MM/YYYY'
//         }*/
//     });
	
// 	$('input[name="txt_tgl"]').on('apply.daterangepicker', function(ev, picker) {
//       $(this).val(picker.startDate.format('DD/MM/YYYY'));
//   });
	
// });
</script>
      