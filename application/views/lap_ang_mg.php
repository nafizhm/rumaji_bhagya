<?php $this->load->view('header'); 
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Laporan Permintaan Dana Perminggu</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?><small> <?php echo $subtitle; ?></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                           <li><a href="<?php echo site_url('ang/lap_ang_mg'); ?>"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php $this->lib->disp_msg(); ?>
                    <br />
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No. Req.</th>
                          <th>Pos Ang.</th>
                          <th>Biaya</th>
                          <th>No. SPK/WO/PAI</th>
                          <th>Vendor</th>
                          <th>Bank Vendor</th>
                          <th>No. Rek Vendor</th>
                          <th>A/N Bank Vendor</th>
                          <th>Total</th>
                          <th>Sdh Dibyr</th>
                          <th>Sisa</th>
                          <th>Permin. Dana</th>
                          <th>Realisasi</th>
                          <th>Ket.</th>
                          <th>Nomor Cek</th>
                          <?php if($edit != 0) { ?>
                          <th>Pilihan</th>
                            <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach( $list_ang_mg as $row)
								{
			if($row->spkno != 0)
					{	      
					  $sql_get_spk = $this->db->query("SELECT * FROM tspk WHERE projid = '".$row->projid."' AND spkno = '".$row->spkno."'");
					  foreach( $sql_get_spk->result()  as $row_spk );
					  $tot_dibyr = $this->lib->get_tot_dana($row->projid,$row->spkno);
					}
					else
					{
						$tot_dibyr = 0;
					}
					  ?>
                      <tr>
            
                      <td><?php echo $this->lib->no_req($row->reqno); ?></td>
                      <td><?php echo $row->nampa; ?></td>
                      <td><?php echo $row->namby; ?></td>
                      <td><?php if($row->spkno != 0)
					{ echo $this->lib->format_spkno($row->projid,$row->spkno); } ?></td>
					<td><?php echo $this->lib->get_namkon($row->konid); ?></td>
          <?php $sql_get_acc = $this->db->query("SELECT mkon.accno, mkon.namacc,mbank.nambank FROM `mkon` 
          LEFT JOIN mbank ON mkon.bankid = mbank.bankid 
          WHERE mkon.konid ='".$row->konid."'");
					foreach( $sql_get_acc->result()  as $row_acc ); ?>
          <td><?=$row_acc->nambank; ?></td>
          <td><?=$row_acc->accno; ?></td>
          <td><?=$row_acc->namacc; ?></td>
                      <td><?php  if($row->spkno != 0)
					{ echo $this->lib->format_idr($row_spk->total); } ?></td>
                      <td><?php echo $this->lib->format_idr($tot_dibyr); ?></td>
                      <td><?php if($row->spkno != 0)
					{  echo  $this->lib->format_idr($row_spk->total - $tot_dibyr); } ?></td>
                      <td><?php echo $this->lib->format_idr($row->total); ?></td>
                      <td><?php echo $this->lib->format_idr($row->totalbyr);?></td>
                      <td><?php echo $row->ket; ?></td>
                      <td><?php echo $row->nocek; ?></td>
                      <?php if($edit != 0) { ?>
                      <td> <div class="btn-group btn-group-sm">
                       <a href="#" data-ket="<?php echo $row->ket; ?>" data-bankven= "<?=$row_acc->nambank.' , '.$row_acc->accno.' A/N '.$row_acc->namacc; ?>" data-real="<?php echo $row->totalbyr; ?>" data-dana="<?php echo $this->lib->format_idr($row->total); ?>" data-spkbyr="<?php echo $this->lib->format_idr($tot_dibyr); ?>" data-spksisa="<?php echo $this->lib->format_idr($row_spk->total - $tot_dibyr); ?>"  data-spktot="<?php echo $this->lib->format_idr($row_spk->total); ?>" data-vendor="<?php echo $this->lib->get_namkon($row->konid); ?>" data-spkno="<?php echo $this->lib->format_spkno($row->projid,$row->spkno); ?>" data-nampa="<?php echo $row->nampa; ?>" data-namby="<?php echo $row->namby; ?>"  data-noreq="<?php echo $this->lib->no_req($row->reqno); ?>" data-noreq2="<?php echo $row->reqno; ?>" data-bankid="<?php echo $row->bankid; ?>"  data-accno="<?php echo $row->accno; ?>"  data-nocek="<?php echo $row->nocek; ?>" data-projid="<?php echo $row->projid; ?>"  data-toggle="modal" data-target=".bs-example-modal-lg" class="ReaDialog btn btn-primary"><i class="fa fa-edit"></i> Realisasi</a>
                        </div></td><?php } ?>
                      </tr>
                      <?php
								}
					?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
     <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel2"></h4>
                        </div>
                        <div class="modal-body">
                        <form action="<?php echo site_url('ang/do_edit_real/'.$row->projid.'/'.$row->mon.'/'.$row->week.'/'.$row->year); ?>" method="post" name="form1" id="form1" class="form-horizontal form-label-left">
                         <input type="hidden"  id="txt_noreq" name="txt_noreq" />
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Pos Anggaran
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"> <label id ="lbl-nampa"></label></p>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Biaya
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><label id ="lbl-namby"></label></p>
                        </div>
                      </div>
                      
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">No. SPK/WO/PAI
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><label id ="lbl-spkno"></label></p>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Vendor
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><label id ="lbl-vendor"></label></p>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bank Vendor
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><label id ="lbl-bankven"></label></p>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><label id ="lbl-spktot"></label></p>
                        </div>
                      </div>
                      
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sdh Dibyr
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><label id ="lbl-spkbyr"></label></p>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sisa
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><label id ="lbl-spksisa"></label></p>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Permin. Dana
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><label id ="lbl-dana"></label></p>
                        </div>
                      </div>
                      
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Realisasi
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"> <input name="txt_real" id="txt_real" class="form-control col-md-7 col-xs-12" required="required" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true" ></p>
                        </div>
                      </div>
                      
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ket.
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><input name="txt_ket" id="txt_ket" class="form-control col-md-7 col-xs-12" ></p>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Bank
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <select name="cb_bank" id="cb_bank"  class="form-control col-md-7 col-xs-12">
                           <p class="form-control-static"><?php echo $this->lib->generate_cb_bank_acc($proj);?></p>
                        </select>
                        </div>
                      </div>
                      
                         <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Cek
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <p class="form-control-static"><input name="txt_nocek" id="txt_nocek" class="form-control col-md-7 col-xs-12" ></p>
                        </div>
                      </div>
                        
                        <div class="modal-footer">
                         <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Ubah Realisasi</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                          </form>
                        </div>

                      </div>
                    </div>
                  </div>
                  <!-- /modals -->                      
<?php $this->load->view('footer'); ?>
      <script type="text/javascript">

$(document).ready(function() {

 $(document).on("click", ".ReaDialog", function () {
	
	  $( "#myModalLabel2" ).html( "Ubah Realisasi Permintaan Dana No. Request " + $(this).data('noreq'));
      $('#lbl-nampa').text($(this).data('nampa'));
	  $('#lbl-namby').text($(this).data('namby'));
	  $('#lbl-spkno').text($(this).data('spkno'));
	  $('#lbl-vendor').text($(this).data('vendor'));
	  $('#lbl-spktot').text($(this).data('spktot'));
	  $('#lbl-spkbyr').text($(this).data('spkbyr'));
	  $('#lbl-spksisa').text($(this).data('spksisa'));
	  $('#lbl-dana').text($(this).data('dana'));
	  $("#txt_real").val($(this).data('real'));
	  $('#txt_ket').val($(this).data('ket'));
	  $("#txt_noreq").val($(this).data('noreq2'));
    $("#txt_nocek").val($(this).data('nocek'));
    $('#lbl-bankven').text($(this).data('bankven'));
	  
	  var selected = $(this).data('bankid') + ";" + $(this).data('accno');
	  $("select").val(selected);
     
});
	table = $('#datatable').DataTable();
 
table.destroy();

   $('#datatable').DataTable( {
	   
	    fixedHeader: true,
        dom: 'Blfrtip',
          aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
    ],
	
    iDisplayLength: -1,
        buttons: [
            'copyHtml5',
           //'excelHtml5',
		   { extend: 'excelHtml5',
      exportOptions: {
          //columns: ':visible',
          columns: 'th:not(:last-child)',
          format: {
              body: function(data, row, column, node) {
                  //data = $('<p>' + data + '</p>').text();
                  return $.isNumeric(data.replace( /[.]/g, '' )) ? data.replace( /[.]/g, '' ) : data;
              }
          }
      }
			},
            'csvHtml5',
            'pdfHtml5',
            'print',
        ]
    } );
    
} );
</script>
      