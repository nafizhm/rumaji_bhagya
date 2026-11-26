<?php $this->load->view('header'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Laporan Saldo Bank</h3>
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
                           <li><a href="<?php echo site_url('saldo'); ?>"><i class="fa fa-search"></i> Pilih project</a>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php $this->lib->disp_msg(); ?>
                    <h2><?php echo $bank_nama; ?> / <?php echo $bank_accno; ?></h2>
                    <table width="100%" border="1" class="table table-striped table-bordered">
                     <thead>
                      <tr>
                        <th width="20%">Keterangan</th>
                        <th width="30%">Bank Masuk</th>
                        <th width="25%">Bank Keluar</th>
                        <th width="25%">Saldo</th>
                      </tr>
                      </thead>
                      <tr>
                        <td>Saldo Awal</td>
                        <td align="right"><?php echo $this->lib->format_idr($sld_awl_bm); ?></td>
                        <td align="right"><?php echo $this->lib->format_idr($sld_awl_bk); ?></td>
                        <td align="right"><?php echo $this->lib->format_idr($sld_awl_bm - $sld_awl_bk); ?></td>
                      </tr>
                      <tr>
                        <td>Saldo Akhir</td>
                        <td align="right"><?php echo $this->lib->format_idr($sld_akh_bm); ?></td>
                        <td align="right"><?php echo $this->lib->format_idr($sld_akh_bk); ?></td>
                        <td align="right"><strong><?php echo $this->lib->format_idr( ($sld_akh_bm - $sld_akh_bk) + ($sld_awl_bm - $sld_awl_bk)); ?></strong></td>
                      </tr>
                    </table>
                    <br />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<?php $this->load->view('footer'); ?>

      