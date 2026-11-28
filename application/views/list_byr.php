<?php $this->load->view('header'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="pull-right text-right" style="padding:8px 15px"><div>Saldo Deposit</div><strong style="font-size:30px;color:#169f85">Rp <?php echo number_format((float)$deposit,2,',','.'); ?></strong></div>
              <div class="title_left">
                <h3>Tambah Pembayaran > List Bayar</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?><small><?php echo $subtitle; ?></small></h2>
                      <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="<?php echo site_url('byr'); ?>"><i class="fa fa-search"></i> Pilih SPR</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                 <?php if($status == 'BTL') { echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">
                         <span aria-hidden="true">SPR ini telah dibatalkan, anda tidak bisa melakukan perubahan data.</span></div>';
}
					 ?>
                    <?php $this->lib->disp_msg(); ?>
                    <br />
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Jenis Pembayaran</th>
                          <th>Angsuran Ke</th>
                          <th>Total</th>
                          <th>Jatuh Tempo</th>
                          <th>Status</th>
                          <th>No Kwitansi</th>
                          <th>Tgl Terima</th>
                          <th>Verifikasi</th>
                          <th>Pilihan</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach( $list_sprp as $row)
								{
					  ?>
                      <tr>
            
                      <td><?php echo  $this->lib->tipe_byr_txt($row->tipe); ?></td>
                      <td><?php echo $row->ang; ?></td>
                      <td><?php echo  $this->lib->format_idr($row->total); ?></td>
                       <td><?php echo $this->lib->disp_date($row->jtmp); ?></td>
                      <td><?php echo $this->lib->stat_byr_txt($row->status); ?></td>
                      <td> <a target="_blank" href="<?php echo site_url('byr/cetak_kwt/'.$row->projid.'/'.$row->kwtno.'/'.$row->sprno); ?>"><?php echo  $this->lib->no_kwt($row->kwtno); ?></a></td>
                      <td><?php 
                       if( $row->kwtno > 0 )
                       {
                        $query_tgl = $this->db->query("SELECT tgl FROM `tkwt`
                        WHERE projid = '".$row->projid."' AND kwtno = '".$row->kwtno."'");
                        foreach( $query_tgl->result()  as $row_tgl);
                        echo $this->lib->disp_date($row_tgl->tgl);
                       }
                      ?></td>
                      <td align="center"><?php
                      if( $row->kwtno > 0 )
                      {
                      $query = $this->db->query("SELECT * FROM `tkwt`
                      WHERE projid = '".$row->projid."' AND kwtno = '".$row->kwtno."'");
                     foreach( $query->result()  as $rows);
                      if( $rows->crdat >= $_SERVER['TGL_VER'] )
                      {
                        if($rows->verby <> '' ){
                          echo '<i class="fa fa-check"></i> Sudah diverifikasi';
                        }
                        else{
                          echo '<i class="fa fa-close"></i> Belum diverifikasi';
                        }
                      }
                    }
                      ?></td>
                      <td>
                       <div class="btn-group btn-group-sm">
                       <?php foreach ($deposit_only_receipts as $depositReceipt): ?>
                       <?php if ($depositReceipt->tipe===$row->tipe && (int)$depositReceipt->ang===(int)$row->ang): $depositNo='DEP-'.(int)$depositReceipt->projid.'-'.str_pad((string)(int)$depositReceipt->kwtno,6,'0',STR_PAD_LEFT); ?>
                       <a target="_blank" rel="noopener" href="<?php echo site_url('byr/cetak_deposit/'.$depositReceipt->projid.'/'.$depositReceipt->kwtno.'/'.$depositReceipt->sprno); ?>" class="btn btn-warning" title="<?php echo html_escape($depositNo); ?>"><i class="fa fa-print"></i> Cetak Deposit</a>
                       <?php endif; ?>
                       <?php endforeach; ?>
                       <?php if (isset($deposit_receipts[$row->kwtno])): ?>
                       <a target="_blank" rel="noopener" href="<?php echo site_url('byr/cetak_deposit/'.$row->projid.'/'.$row->kwtno.'/'.$row->sprno); ?>" class="btn btn-warning" title="Cetak kwitansi deposit"><i class="fa fa-print"></i> Cetak Deposit</a>
                       <?php endif; ?>
                      
                       <?php if ($row->status == 'BLN' ) 
					   { 
					   if ($status != 'BTL' ) 
					   {
					   ?>
                       <a href="<?php echo site_url('byr/input_byr/'.$row->sprno.'/'.$row->projid.'/'.$row->tipe.'/'.$row->ang); ?>" class="ByrDialog btn btn-primary" data-sprno="<?php echo $row->sprno; ?>" data-projid="<?php echo $row->projid; ?>" data-tipe="<?php echo $row->tipe; ?>"  data-ang="<?php echo $row->ang; ?>" data-tot="<?php echo $row->total; ?>" data-tipe_txt="<?php echo $this->lib->tipe_byr_txt($row->tipe); ?>" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-check-circle"></i> Bayar</a>
                         <?php
					   }
					   }
						  else
						 { ?>
                         <a target="_blank" href="<?php echo site_url('byr/cetak_kwt/'.$row->projid.'/'.$row->kwtno.'/'.$row->sprno); ?>" class="btn btn-primary"><i class="fa fa-print"></i>Cetak</a>
                       <a href="<?php echo site_url('byr/input_byr/'.$row->sprno.'/'.$row->projid.'/'.$row->tipe.'/'.$row->ang); ?>" class="BtlDialog btn btn-danger" data-sprno="<?php echo $row->sprno; ?>" data-projid="<?php echo $row->projid; ?>" data-tipe="<?php echo $row->tipe; ?>"  data-ang="<?php echo $row->ang; ?>" data-tot="<?php echo $row->total; ?>" data-tipe_txt="<?php echo $this->lib->tipe_byr_txt($row->tipe); ?>" data-kwtno="<?php echo $row->kwtno; ?>"  data-kwtnodis="<?php echo $this->lib->no_kwt($row->kwtno); ?>" data-toggle="modal" data-target=".bs-example-modal-lg2"><i class="fa fa-remove"></i> Batal</a>
                         <?php
						 } ?>
                      </div>
                   </td>
                      </tr>
                      <?php
								}
					?>
                      </tbody>
                    </table>
                    <?php if (!empty($deposit_only_receipts)): ?>
                    <h4>Setoran Deposit — Belum Dialokasikan ke Cicilan</h4>
                    <div class="table-responsive"><table class="table table-bordered table-striped">
                    <thead><tr><th>No. Kwitansi Deposit</th><th>Tanggal</th><th>Jumlah Deposit</th><th>Verifikasi</th><th>Action</th></tr></thead><tbody>
                    <?php foreach ($deposit_only_receipts as $receipt): $depNumber='DEP-'.(int)$receipt->projid.'-'.str_pad((string)(int)$receipt->kwtno,6,'0',STR_PAD_LEFT); ?>
                    <tr><td><?php echo html_escape($depNumber); ?></td><td><?php echo $this->lib->disp_date($receipt->tgl); ?></td><td>Rp <?php echo number_format($receipt->deposit,2,',','.'); ?></td><td><?php echo $receipt->verby!==''?'Sudah diverifikasi':'Belum diverifikasi'; ?></td><td>
                    <a class="btn btn-warning btn-sm" target="_blank" rel="noopener" href="<?php echo site_url('byr/cetak_deposit/'.$receipt->projid.'/'.$receipt->kwtno.'/'.$receipt->sprno); ?>"><i class="fa fa-print"></i> Cetak Deposit</a>
                    <button type="button" class="BtlDialog btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg2" data-sprno="<?php echo (int)$receipt->sprno; ?>" data-projid="<?php echo (int)$receipt->projid; ?>" data-tipe="<?php echo html_escape($receipt->tipe); ?>" data-ang="<?php echo (int)$receipt->ang; ?>" data-tot="0" data-kwtno="<?php echo (int)$receipt->kwtno; ?>" data-kwtnodis="<?php echo html_escape($depNumber); ?>">Batal Deposit</button>
                    </td></tr>
                    <?php endforeach; ?>
                    </tbody></table></div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
         <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel2"></h4>
                        </div>
                        <div class="modal-body">
                        <form action="<?php echo $form_action; ?>" method="post" name="form1" id="form1" class="form-horizontal form-label-left">
                        <input type="hidden" name="byr_token" value="<?php echo html_escape($this->session->userdata('byr_token')); ?>">
                        <?php if ((float)$deposit>0): ?>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Deposit Sebelumnya</label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <p class="form-control-static" style="font-size:24px;font-weight:bold;color:#169f85">Rp <?php echo number_format((float)$deposit,2,',','.'); ?></p>
                            <small>Saldo deposit dari pembayaran sebelumnya pada SPR ini.</small>
                          </div>
                        </div>
                        <?php endif; ?>
                        <input type="hidden" id="txt_sprno" name="txt_sprno" />
                        <input type="hidden"  id="txt_projid" name="txt_projid" />
                        <input type="hidden"  id="txt_ang" name="txt_ang" />
                         <input type="hidden"  id="txt_tipe" name="txt_tipe" />
                        
                         <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_jumlah_bayar">Jumlah Bayar</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_jumlah_bayar" id="txt_jumlah_bayar" class="form-control" required inputmode="decimal" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true">
                          <small>Jika pembayaran dan deposit cukup, cicilan dilunasi. Jika belum cukup, seluruh dana baru masuk deposit dan cicilan tetap belum lunas.</small>
                          <div id="byr-deposit-preview" class="text-success" aria-live="polite"></div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total Bayar
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                           <input name="txt_total" id="txt_total" class="form-control col-md-7 col-xs-12" required="required" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true" readonly="readonly"  >
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Bayar
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_tgl" id="txt_tgl" class="form-control col-md-7 col-xs-12" required >
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Pembayaran
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        	<select name="cb_byr" class="form-control col-md-7 col-xs-12" required>
                            <option value="T">Transfer</option>
                            <option value="C">Cash</option>
                            </select>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_ket" id="txt_ket" maxlength="100" class="form-control col-md-7 col-xs-12" required >
                        </div>
                      </div>
                      
                        </div>
                        
                        <div class="modal-footer">
                         <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Bayar</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                          </form>
                        </div>

                      </div>
                    </div>
                  </div>
                  <!-- /modals -->
                  
                    <div class="modal fade bs-example-modal-lg2" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel3"></h4>
                        </div>
                        <div class="modal-body">
                        <form action="<?php echo $form_action2; ?>" method="post" name="form2" id="form2" class="form-horizontal form-label-left">
                        <input type="hidden" name="byr_token" value="<?php echo html_escape($this->session->userdata('byr_token')); ?>">
                        <input type="hidden" id="txt_sprno2" name="txt_sprno2" />
                        <input type="hidden"  id="txt_projid2" name="txt_projid2" />
                        <input type="hidden"  id="txt_ang2" name="txt_ang2" />
                         <input type="hidden"  id="txt_tipe2" name="txt_tipe2" />
                         <input type="hidden"  id="txt_kwtno" name="txt_kwtno" />
                          <input type="hidden"  id="txt_total2" name="txt_total2" />
                        
                      
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan batal
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_ket2" id="txt_ket2" maxlength="100" class="form-control col-md-7 col-xs-12" required >
                        </div>
                      </div>
                      
                        </div>
                        
                        <div class="modal-footer">
                         <button type="submit" class="btn btn-danger"><i class="fa fa-remove"></i> Batal</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                          </form>
                        </div>

                      </div>
                    </div>
                  </div>
                  <!-- /modals -->
                  
                  
        <!-- /page content -->
<?php $this->load->view('footer'); ?>
<script type="text/javascript">
$(document).ready( function() {
	
	table = $('#datatable').DataTable();
 
table.destroy();

  $('#datatable').dataTable( {
    "aaSorting": [[1,'asc']],
	 fixedHeader: true
  } );
} );
$(document).on("click", ".BtlDialog", function () {
	 $("#txt_sprno2").val($(this).data('sprno'));
	  $("#txt_kwtno").val($(this).data('kwtno'));
	  $("#txt_projid2").val($(this).data('projid'));
	  $("#txt_total2").val($(this).data('tot'));
	  $("#txt_ang2").val($(this).data('ang'));
	   $("#txt_tipe2").val($(this).data('tipe'));
	  $( "#myModalLabel3" ).html( "Batal Pembayaran Pembayaran Kwitansi No " + $(this).data('kwtnodis'));
    
     
});
	
$(document).on("click", ".ByrDialog", function () {
	 $("#txt_sprno").val($(this).data('sprno'));
	  $("#txt_projid").val($(this).data('projid'));
	  $("#txt_total").val($(this).data('tot'));
	  $('#txt_jumlah_bayar').data('tagihan',Number($(this).data('tot')));
	  $('#txt_jumlah_bayar').val(Number($(this).data('tot')).toFixed(2).replace('.',','));
	  $('#txt_jumlah_bayar').trigger('input');
	  $("#txt_ang").val($(this).data('ang'));
	   $("#txt_tipe").val($(this).data('tipe'));
	  $( "#myModalLabel2" ).html( "Input Pembayaran " + $(this).data('tipe_txt') +" ( Angsuran Ke "+ $(this).data('ang')+" )");
    
     
});

$(function() {
    $('#txt_jumlah_bayar').on('input change',function () {
        var amount=Number(this.value.replace(/\./g,'').replace(',','.'));
        var due=Number($(this).data('tagihan'));
        var saldo=<?php echo json_encode((float)$deposit); ?>;
        var used=Math.max(0,Math.round((due-amount)*100)/100);
        var extra=Math.max(0,Math.round((amount-due)*100)/100);
        function rp(value) { return value.toLocaleString('id-ID',{style:'currency',currency:'IDR'}); }
        this.setCustomValidity(amount===0 && saldo<due?'Isi jumlah bayar lebih dari nol.':'');
        if (used>saldo) {
            $('#byr-deposit-preview').text('Seluruh dana '+rp(amount)+' masuk deposit. Saldo deposit menjadi '+rp(saldo+amount)+'. Cicilan tetap BELUM LUNAS.');
        } else {
            $('#byr-deposit-preview').text('Deposit digunakan: '+rp(used)+' | Tambahan deposit: '+rp(extra)+' | Sisa deposit: '+rp(saldo-used+extra));
        }
    });
    $('input[name="txt_tgl"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
		autoUpdateInput: false/*,
		 locale: {
            format: 'DD/MM/YYYY'
        }*/
    });
	
	$('input[name="txt_tgl"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
	
});

</script>
