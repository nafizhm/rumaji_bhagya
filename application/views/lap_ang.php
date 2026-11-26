<?php if ($excel == '') {
?>
  <?php $this->load->view('header'); ?>

  <!-- page content -->
  <div class="right_col" role="main">
    <div class="">
      <div class="page-title">
        <div class="title_left">
          <h3>Laporan Pemakaian Anggaran</h3>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2><?php echo $title; ?></h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="<?php echo site_url('ang/lap_ang'); ?>"><i class="fa fa-search"></i> Pilih project</a>
                    <li><a href="<?php echo site_url('ang/do_lap_ang_disp/' . $projid . '/excel/' . $kav); ?>" target="_blank"><i class="fa fa-download"></i> Download excel</a>
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
            <?php } else {
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=lap_ang.xls");
          } ?>
            <table width="808" border="1" class="table table-bordered">
              <?php
              $total_all = 0;
              $total_sisa_all = 0;
              $total_klr_all = 0;
              $total_sisa = 0;
              $total = 0;
              $total_klr = 0;
              $total_hpp = 0;
              foreach ($list_pa as $row_pa) {
              ?>
                <tr>
                  <th colspan="5">
                    <h4> <?php echo $row_pa->paid; ?> - <?php echo $row_pa->nampa; ?></h4>
                  </th>
                </tr>
                <tr>
                  <th width="385">Biaya</th>
                  <th width="407">Total Anggaran</th>
                  <th width="407">Total Pengeluaran</th>				  				  <th width="100" ><center>%</center></th>
                  <th width="407">Sisa Anggaran</th>
                </tr>
                <?php
                $total_all = $total_all + $total;
                $total_klr_all =  $total_klr_all + $total_klr;
                $total_sisa_all =  $total_sisa_all + $total_sisa;
                $total = 0;
                $total_klr = 0;
                $total_sisa =  0;
                $query = $this->db->query("SELECT mby.namby, mang.byid, mang.total 
			   FROM mang 
			   INNER JOIN mby ON mby.byid = mang.byid AND mby.paid = mang.paid
			   WHERE mang.projid = '" . $projid . "' AND mang.paid = '" . $row_pa->paid . "'");
                foreach ($query->result()  as $row_ang) {
                  $klr = $this->lib->get_tot_ang($projid, $row_pa->paid, $row_ang->byid);
                  $sisa = $row_ang->total - $klr;
                  $total = $row_ang->total + $total;
                  $total_klr = $klr + $total_klr;
                  $total_sisa = $sisa + $total_sisa;				  				  if( $klr > 0){					   $persen = number_format(($klr / $row_ang->total)*100,0);				  }else{					  $persen  = 0;				  }				 				  				  if( $persen > 100 ){					  $color = "red";				  }				  else{					  $color = "green";				  }

                ?>
                  <tr>
                    <td width="385"><?php echo $row_ang->byid; ?> - <?php echo $row_ang->namby; ?></td>
                    <td width="407" align="right"><?php echo $excel == '' ? $this->lib->format_idr($row_ang->total) : $row_ang->total; ?></td>
                    <td width="407" align="right"><?php echo $excel == '' ? $this->lib->format_idr($klr) : $klr; ?></td>										<td width="100" align="center"><font color="<?=$color;?>"><?php echo $excel == '' ? $persen : $persen; ?></font></td>
                    <td width="407" align="right"><?php echo $excel == '' ? $this->lib->format_idr($sisa) : $sisa; ?></td>
                  </tr>

                <?php } ?>
                <tr>
                  <td width="385">Total</td>
                  <td width="407" align="right"><b><?php echo $excel == '' ? $this->lib->format_idr($total) : $total; ?></b></td>
                  <td width="407" align="right"><b><?php echo $excel == '' ? $this->lib->format_idr($total_klr) : $total_klr; ?></b></td>								  <td width="100" align="right"></td>				
                  <td width="407" align="right"><b><?php echo $excel == '' ? $this->lib->format_idr($total_sisa) : $total_sisa; ?></b></td>
                </tr>
              <?php

                if ($row_pa->paid < '12') {
                  $total_hpp =  $total_hpp + $total_klr;
                }
              } ?>
              <tr>
                <td width="385">Total Anggaran Project</td>
                <td width="407" align="right"><b><?php echo $excel == '' ? $this->lib->format_idr($total_all) : $total_all; ?></b></td>
                <td width="407" align="right"><b><?php echo $excel == '' ? $this->lib->format_idr($total_klr_all) : $total_klr_all; ?></b></td>								<td width="100" align="right"></td>				
                <td width="407" align="right"><b><?php echo $excel == '' ? $this->lib->format_idr($total_sisa_all) : $total_sisa_all; ?></b></td>
              </tr>
              <tr>
                <td colspan="4">HPP ( Total Pengeluaran 1 s/d 11 = <b><?php echo $excel == '' ? $this->lib->format_idr($total_hpp) : $total_hpp; ?></b> / Jumlah Unit = <b><?php echo $kav; ?> </b> )</td>
                <td width="407" align="right"><b><?php echo  $excel == '' ? $this->lib->format_idr($total_hpp / $kav) : $total_hpp / $kav; ?></b></td>
              </tr>
            </table>
            <?php if ($excel == '') {
            ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->
  <?php $this->load->view('footer'); ?>
<?php } ?>