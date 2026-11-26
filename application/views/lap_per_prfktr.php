<?php $this->load->view('header'); 
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Laporan Perkiraan Profit Kotor</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Project <?php echo $title; ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                           <li><a href="<?php echo site_url('inv/lap_per_prfktr'); ?>"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table width="40%" border="1" align="center" class="table table-striped table-bordered">
                      <tr>
                        <td colspan="2" align="center"><strong>PENERIMAAN</strong></td>
                      </tr>
                      <tr>
                        <td width="52%">TOTAL SPR</td>
                        <td width="48%" align="right"><b><?php 
						$sql_tot_spr = $this->db->query("SELECT SUM(hrgstd-dis) as total FROM `tsprh` WHERE projid = '".$projid."' AND status = 'PRO'");
						foreach($sql_tot_spr->result() as $row_tot_spr); 
						$tot_spr = $row_tot_spr->total;
						echo $this->lib->format_idr($tot_spr);?></b></td>
                      </tr>
                      <tr>
                        <td>HARGA RATA-RATA</td>
                        <td align="right"><b><?php 
						/*$sql_tot_kav = $this->db->query("SELECT SUM(kav.hrgstd) as total FROM 
						( SELECT mhrg.hrgstd FROM `mkav` INNER JOIN mhrg ON mhrg.projid = mkav.projid 
						AND mhrg.lot = mkav.lot AND mhrg.tipe = mkav.tipe 
						WHERE mkav.projid = '".$projid."' AND mkav.status IN ('BLA','BTL') 
						GROUP BY mkav.blok, mkav.no ORDER BY `mhrg`.`tgl` ASC ) as kav");
						foreach($sql_tot_kav->result() as $row_tot_kav); 
						$tot_kav = $row_tot_kav->total;
						$tot_trm = $tot_kav+$tot_spr;
						echo $this->lib->format_idr($tot_trm);*/
						 $sql_hit_spr = $this->db->query("SELECT COUNT(*) as total FROM `tsprh` 
						WHERE projid = '".$projid."' AND status = 'PRO'");
						foreach($sql_hit_spr->result() as $row_hit_spr); 
						$hit_spr = $row_hit_spr->total;
						$rata2 = $tot_spr / $hit_spr;
						echo $this->lib->format_idr($rata2);
						?></b></td>
                      </tr>
                      <tr>
                        <td>SISA PENERIMAAN</td>
                        <td align="right"><b><?php
                       
						
						$sql_hit_kav = $this->db->query("SELECT COUNT(*) as total FROM 
						( SELECT mhrg.hrgstd FROM `mkav` INNER JOIN mhrg ON mhrg.projid = mkav.projid 
						AND mhrg.lot = mkav.lot AND mhrg.tipe = mkav.tipe 
						WHERE mkav.projid = '".$projid."' AND mkav.status IN ('BLA','BTL') 
						GROUP BY mkav.blok, mkav.no ORDER BY `mhrg`.`tgl` ASC ) as kav");
						foreach($sql_hit_kav->result() as $row_hit_kav); 
						$hit_kav = $row_hit_kav->total;
						
						$tot_est = $rata2 * $hit_kav;
						echo $this->lib->format_idr($tot_est);
						?></b></td>
                      </tr>
                      <tr>
                        <td>TOTAL PENERIMAAN</td>
                        <td align="right"><b><?php $tot_in = $tot_est + $tot_spr;
						echo $this->lib->format_idr($tot_in);?></b></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center"><strong>PENGELUARAN</strong></td>
                      </tr>
                      <tr>
                        <td>TOTAL ANGGARAN PENGELUARAN</td>
                        <td align="right"><b><?php $sql_tot_out = $this->db->query("SELECT sum(mang.total) as total FROM `mang` 
										INNER JOIN mpa ON mpa.paid = mang.paid
										INNER JOIN mby ON mby.byid = mang.byid AND mby.paid = mang.paid
										WHERE projid = '".$projid."'");
						foreach($sql_tot_out->result() as $row_tot_out); 
						$tot_out = $row_tot_out->total;
						echo $this->lib->format_idr($tot_out);?></b></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center"><strong>PROFIT</strong></td>
                      </tr>
                      <tr>
                        <td>PROFIT KOTOR</td>
                        <td align="right"><b><font size="+1"><?php echo $this->lib->format_idr($tot_in - $tot_out);?></font></b></td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
                         
<?php $this->load->view('footer'); ?>
