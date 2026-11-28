<?php $this->load->view('header'); ?>

          <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Home</h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Menu Utama</h2>
                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                
                      <div class="bs-glyphicons">
                     
                        <ul class="bs-glyphicons-list">
							 <a href="<?php echo site_url('home/main_menu/penjualan'); ?>">
                          <li>
                            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                            <span class="glyphicon-class">Modul Penjualan</span>     
                          </li>
                           </a>
                           <a href="<?php echo site_url('home/main_menu/cashin'); ?>">
                          <li>
                            <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
                            <span class="glyphicon-class">Modul Cash In</span>
                          </li>
                           </a>
                             <a href="<?php echo site_url('home/main_menu/cashout'); ?>">
                          <li>
                            <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                            <span class="glyphicon-class">Modul Cash Out</span>
                          </li>
                           </a>
                             <a href="<?php echo site_url('home/main_menu/finance'); ?>">
                          <li>
                            <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                            <span class="glyphicon-class">Modul Keuangan</span>
                          </li>
                           </a>
                             <a href="<?php echo site_url('home/main_menu/reportinv'); ?>">
                          <li>
                            <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
                            <span class="glyphicon-class">Laporan Investor</span>
                          </li>
                           </a>
                          </ul>
                          
                          
             </div>
                  </div>
                </div>
              </div>
            </div>
            <?php if (!empty($show_dashboard)) $this->load->view('dashboard_table'); ?>
          </div>
        </div>
        <!-- /page content -->

<?php $this->load->view('footer'); ?>
 
