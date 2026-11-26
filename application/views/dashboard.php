<?php $this->load->view('header'); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3><?php echo html_escape($title); ?></h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="btn-group" role="group" aria-label="Pilih ringkasan dashboard" style="margin-bottom: 20px;">
              <a class="btn <?php echo $dashboard_modul === 'penjualan' ? 'btn-primary' : 'btn-default'; ?>" href="<?php echo site_url('home/dashboard/penjualan'); ?>">Penjualan</a>
              <a class="btn <?php echo $dashboard_modul === 'cashin' ? 'btn-primary' : 'btn-default'; ?>" href="<?php echo site_url('home/dashboard/cashin'); ?>">Cash In</a>
            </div>
            <?php if (empty($list_proj)): ?>
              <div class="alert alert-info" role="status">Belum ada proyek aktif untuk ditampilkan.</div>
            <?php endif; ?>
<?php foreach($list_proj as $row) 
{
?>            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $row->namproj ?></h2>
                     <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  
 <?php if( $dashboard_modul == 'penjualan' )
				  {	 ?>                  
              <?php 
			   $query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status IN ('BLA') AND projid = '".$row->projid."'");
				foreach( $query->result()  as $row_count_bla);
  			?>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-warning"></i></div>
                  <div class="count"><font color="#FF0000"><?php echo number_format($row_count_bla->tot); ?></font></div>
                  <h3>Belum Laku</h3>
                  <p>Stok kavling belum laku</p>
                </div>
              </div>
               <?php 
			   $query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status IN ('BTL') AND projid = '".$row->projid."'");
				foreach( $query->result()  as $row_count_btl);
  			?>
                <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-ban"></i></div>
                  <div class="count"><font color="#FF0000"><?php echo number_format($row_count_btl->tot); ?></font></div>
                  <h3>Batal</h3>
                  <p>Stok kavling batal</p>
                </div>
              </div>
                <?php 
         //$query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status NOT IN ('BTL','AKD','BLA') AND projid = '".$row->projid."'");
         $query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status NOT IN ('BTL','AKD','BLA','AKS') AND projid = '".$row->projid."'");
				foreach( $query->result()  as $row_count_spr);
  			?>
               <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-check-circle"></i></div>
                  <div class="count"><font color="#009900"><?php echo number_format($row_count_spr->tot); ?></font></div>
                  <h3>SPR</h3>
                  <p>Stok kavling SPR</p>
                </div>
              </div>
              
               <?php 
         //$query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status = 'AKD' AND projid = '".$row->projid."'");
         $query = $this->db->query("SELECT count(status) as tot FROM mkav WHERE status IN ('AKD','AKS') AND projid = '".$row->projid."'");
				foreach( $query->result()  as $row_count_akd);
  			?>
               <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-thumbs-o-up"></i></div>
                  <div class="count"><font color="#009900"><?php echo number_format($row_count_akd->tot); ?></font></div>
                  <h3>AKAD</h3>
                  <p>Stok kavling Akad</p>
                </div>
              </div>
                <?php } ?>
                <?php 
				if( $dashboard_modul == 'cashin' )
				  {	 
			  $query = $this->db->query("SELECT sum(tsprp.total) as tot 
			  FROM tsprp 
			  INNER JOIN tsprh ON tsprp.projid = tsprh.projid AND tsprp.sprno = tsprh.sprno
			  WHERE tsprh.status <> 'BTL'
			  AND tsprp.projid = '".$row->projid." 'AND tsprp.status = 'BLN' AND tsprp.jtmp < '".date('Y-m-d')."'");
				foreach( $query->result()  as $row_count_jtmp);
//Total biaya lain2 / BUM				
				/* $query2 = $this->db->query("SELECT SUM(tsprh.byttp) as tot_byttp FROM tsprh
				WHERE tsprh.status <> 'BTL'
				AND sprno IN ( SELECT sprno FROM tsprp WHERE projid = '".$row->projid."' 
				AND status = 'BLN' AND jtmp < '".date('Y-m-d')."' GROUP BY sprno)
				AND projid = '".$row->projid."'");
				foreach( $query2->result()  as $row_count_bum); */
  			?>
               <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-money"></i></div>
                  <div class="count"><font color="#FF0000"><?php echo $this->lib->format_idr($row_count_jtmp->tot); ?></font></div>
                  <h3>Jatuh Tempo</h3>
                  <p>Pembayaran jatuh tempo</p>
                </div>
              </div>
              <?php
               $query = $this->db->query("SELECT sum(total) as tot FROM tkwt WHERE  projid = '".$row->projid." 'AND btl = ''");
				foreach( $query->result()  as $row_count_in);
  			?>
               <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-money"></i></div>
                  <div class="count"><font color="#009900"><?php echo $this->lib->format_idr($row_count_in->tot); ?></font></div>
                  <h3>Uang Masuk</h3>
                  <p>Kwitansi sudah dibayar</p>
                </div>
              </div>
                  <?php } ?>      
                  </div>
                </div>
              </div>
            </div>
          
             <?php } ?>
          </div>
        </div>
        <!-- /page content -->
<?php $this->load->view('footer'); ?>
 
