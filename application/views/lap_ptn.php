<?php $this->load->view('header'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Laporan Potensi</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?><small></small></h2>
                      <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="<?php echo site_url('spr/lap_ptn'); ?>"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
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
                          <th>No SPR</th>
                          <th>Blok/No</th>
                          <th>Lot</th>
                          <th>Konsumen</th>
                          <th>Prog. UM</th>
                          <th>Prog. DOK</th>
                          <th>Prog. KAV</th>
                          <th>Status</th>
			  <th>No HP</th>
			  <th>Tgl SPR</th>							
			  <th>LT</th>							
			  <th>Tipe</th>							
			  <th>Jenis Fitur</th>							
			  <th>Harga Jual</th>							
			  <th>Uang Muka</th>							
			  <th>Fitur</th>							
			  <th>Biaya Titipan</th>				
			  <th>Piutang JT</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
					  $pb = 0;
					  $ppa = 0;
					  $pa = 0;
					  $sa = 0;
					  $bap = 0;
					  $pbg = 0;
					  $prb = 0;
					  $sp3k = 0;
					  foreach( $list_spr as $row)
								{
						$um = $this->lib->disp_prog_um($row->projid,$row->sprno);
						$dok = $this->lib->disp_prog_dok($row->projid,$row->sprno);
						$kav = $this->lib->disp_prog_kav($row->projid,$row->blok,$row->no);
					  ?>
                      <tr>
            
                      <td><?php echo $this->lib->no_spr($row->sprno); ?></td>
                      <td><?php echo $row->blok; ?><?php echo $row->no; ?></td>
                      <td><?php echo $row->lot; ?></td>
                      <td><?php echo $row->nama; ?></td>
                       <td><?php echo $um; ?> %</td>
                       <td><?php echo $dok; ?> %</td>
                       <td><?php echo $kav; ?> %</td>
                      <td><?php
					  //if( $um >= 50 && $dok >= 20 && $kav < 75)
					  /*if( $um >= 50 && $dok >= 25 && $kav == 0)
					  {
						  $pb = $pb + 1;
						  echo 'POTENSI BANGUN';
					  }
					   elseif( $um <= 75 && $dok <= 75 && $kav >= 75 )
					   //um dibawah 75, berkas dibawah 75, bangunan >= 75
					  {
						  $ppa = $ppa + 1;
						  echo 'PRIORITAS BERKAS&UM';
					  }
					  //elseif( $um >= 75 && $dok >= 75 && $kav <= 50 ) //Prioritas Bangun
					  elseif( $um >= 75 && $dok >= 75 && $kav <= 50 )
					  {
						  $prb = $prb + 1;
						  echo 'PRIORITAS BANGUN';
					  }
					   //elseif( $um >= 50 &&  $um < 75 && $dok >= 25 && $kav > 0 && $kav < 75 ) //Pembangunan
					  elseif( $um >= 50 && $dok >= 30 && $kav > 0 && $kav < 100)
					  {
						  $pbg = $pbg + 1;
						  echo 'PEMBANGUNAN';
					  }
					   elseif( $um >= 75 && $dok >= 75 && $dok < 100 && $kav >= 75 )
					   //um diatas 75, berkas diatas 75, bangunan >= 75
					  {
						  $pa = $pa + 1;
						  echo 'POTENSI AKAD';
					  }
					  /*elseif( $um == 100 && $dok == 100 && $kav < 100 )
					  {
						  $pa = $pa + 1;
						  echo 'POTENSI AKAD';
					  }
					  
					  elseif( $um == 100 && $dok == 100 && $kav == 100 )
					  {
						  $sa = $sa + 1;
						  echo 'SUDAH AKAD';
					  }
					  */
					  $tot = $um + $dok + $dok;
					  if($um >= 50 && $dok >= 25 && $kav == 0)
					  {
						  $pb = $pb + 1;
						  echo 'POTENSI BANGUN';
					  }
					  elseif($um >= 50 && $um < 75 && $dok >= 25 && $dok < 90 && $kav > 0)
					  {
						  $pbg = $pbg + 1;
						  echo 'PEMBANGUNAN';
					  }
					  elseif(($um >= 75 && $um <= 100 || $dok >= 90 && $dok <= 100 || $kav >= 75 && $kav <= 100) && $tot < 300)
					  {
						   $pa = $pa + 1;
						   echo 'POTENSI AKAD';
					  }
					 
					   elseif( $tot == 300)
					  {
						  //Cek status sudah akad
						  $querykav = $this->db->query("SELECT status FROM `mkav` 
						  WHERE projid = '".$row->projid."' AND blok = '".$row->blok."' 
						  AND no = '".$row->no."'");
						  foreach( $querykav->result()  as $rowkav);
						  
						  if($rowkav->status == 'AKD')
						  {
							  $sa = $sa + 1;
						  	  echo 'SUDAH AKAD';
						  }
						  else
						  {
						  	$sp3k = $sp3k + 1;
						  	echo 'SP3K';
						  }
					  }
					  else
					  {
						  $bap = $bap + 1;
						  echo 'BELUM ADA POTENSI';
					  }
					   ?></td>
					   <td><?php echo $row->telp; ?></td>					
<td><?php echo $this->lib->disp_date($row->crdat); ?></td>					
<td><?php echo $row->ltstd; ?></td>					
<td><?php echo $row->tipe; ?></td>					
<td><?php echo $row->namfit; ?></td>					
<td><?php echo $this->lib->format_idr($row->hrgtot); ?></td>					
<td><?php echo $this->lib->format_idr($row->umtot); ?></td>					
<td><?php echo $this->lib->format_idr($row->hrgtotalf); ?></td>					
<td><?php echo $this->lib->format_idr($row->byttp); ?></td>					
<td><?php $queryjt = $this->db->query("SELECT sum(total) as totals FROM `tsprp` WHERE `sprno` = '".$row->sprno."' AND projid = '".$row->projid."' 
AND status = 'BLN' AND jtmp <= '".date('Y-m-d')."'");					
$rowjt = $queryjt->row();					
echo $this->lib->format_idr($rowjt->totals);?></td>
                      </tr>
                      <?php
					  $tot = 0;
								}
					?>
                      </tbody>
                      <tfoot>
                      <tr>
                          <td>Total POTENSI BANGUN :</td>
                          <td colspan="17"><b><?php echo $pb; ?></b></td>
                      </tr>
                       <tr>
                          <td>Total PEMBANGUNAN :</td>
                          <td colspan="15"><b><?php echo $pbg; ?></b></td>
                      </tr>
                       <!--<tr>
                          <td>Total PRIORITAS BANGUN :</td>
                          <td colspan="17"><b><?php //echo $prb; ?></b></td>
                      </tr>
                       <tr>
                          <td>Total PRIORITAS BERKAS&UM :</td>
                          <td colspan="17"><b><?php //echo $ppa; ?></b></td>
                      </tr>-->
                      <tr>
                          <td>Total POTENSI AKAD :</td>
                          <td colspan="17"><b><?php echo $pa; ?></b></td>
                      </tr>
                       <tr>
                          <td>Total SP3K :</td>
                          <td colspan="17"><b><?php echo $sp3k; ?></b></td>
                      </tr>
                      <!--
                        <tr>
                          <td>Total POTENSI AKAD :</td>
                          <td colspan="17"><b><?php //echo $pa; ?></b></td>
                      </tr>
                         -->
                        <tr>
                          <td>Total SUDAH AKAD :</td>
                          <td colspan="17"><b><?php echo $sa; ?></b></td>
                      </tr>
                   
                       <tr>
                          <td>Total BELUM ADA POTENSI :</td>
                          <td colspan="17"><b><?php echo $bap; ?></b></td>
                      </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<?php $this->load->view('footer'); ?>
 <script type="text/javascript">

$(document).ready(function() {
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
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            'print',
			{

          extend: 'excelHtml5',

          exportOptions: {

            //columns: 'th:not(:last-child)',

            rows: ':visible',

            format: {

              body: function(data, row, column, node) {
                return $.isNumeric(data.replace(/[.]/g, '')) ? data.replace(/[.]/g, '') : data;
              }

            }

          }

        },
        ]
    } );
    
} );
</script>
      

      