<?php
// A partially uploaded module must not interrupt every page while rendering navigation.
$optionalMenuAccess=function($mid,$accessTable,$requiredTables,$moduleFile,$moid='PJ') {
    $ci =& get_instance();
    if (!is_file(APPPATH.'controllers/'.$moduleFile.'.php')) return FALSE;
    foreach (array_merge(array($accessTable),$requiredTables) as $table) if (!$ci->db->table_exists($table)) return FALSE;
    if ($ci->session->userdata('roleid')==='ADM') return TRUE;
    $user=$ci->db->get_where($accessTable,array('userid'=>$ci->session->userdata('userid')))->row();
    if ($user) return !empty($user->r);
    $role=$ci->db->get_where('mauth',array('roleid'=>$ci->session->userdata('roleid'),'moid'=>$moid,'mid'=>$mid))->row();
    return $role && !empty($role->r);
};
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?php echo $this->config->item('title'); ?> | <?php echo $title; ?></title>

  <!-- Bootstrap -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- bootstrap-wysiwyg -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
  <!-- Select2 -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- Switchery -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
  <!-- starrr -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/starrr/dist/starrr.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/build/css/custom.min.css" rel="stylesheet">
  <!-- Datatables -->
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $this->config->item('assets'); ?>/gentelella-master/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
  <script>try{document.documentElement.dataset.theme=localStorage.getItem('rumaji-theme')==='dark'?'dark':'light';}catch(e){document.documentElement.dataset.theme='light';}</script>
  <link rel="stylesheet" href="<?php echo $this->config->item('assets'); ?>/css/app-theme.css?v=4">
  <script defer src="<?php echo $this->config->item('assets'); ?>/js/app-theme.js?v=1"></script>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">

            <a href="<?=base_url();?>" class="site_title">
              <center><img alt="Bhagya Group" class="sidebar-brand" src="<?php echo $this->config->item('assets'); ?>/img/bhagya-white.svg" /></center>
            </a>
          </div>

          <div class="clearfix"></div>

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
              <h3><?php echo $this->session->userdata('nama_modul'); ?></h3>
              <ul class="nav side-menu">
                <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?php echo site_url('home'); ?>">Menu Utama</a></li>

                    <?php if ($this->session->userdata('modul') != '') {   ?>

                      <li><a href="<?php echo site_url('home/dashboard'); ?>">Dashboard </a></li>
                      <li><a href="<?php echo site_url('home/dashboard_live_view'); ?>">Dashboard Live</a></li>

                    <?php
                    } ?>
                  </ul>
                </li>
                <?php if ($this->session->userdata('modul')==='penjualan'): ?>
                <li><a href="<?php echo site_url('siteplan'); ?>"><i class="fa fa-map-o"></i> Siteplan</a></li>
                <?php endif; ?>
              </ul>
            </div>

            <?php if ($this->session->userdata('roleid') == 'ADM') {   ?>
              <div class="menu_section">
                <h3>Administrator</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-user"></i>Users <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('users/input_user'); ?>">Tambah User</a></li><strong></strong>
                      <li><a href="<?php echo site_url('users'); ?>">List Users</a></li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-th-list"></i>Role <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('role/input_role'); ?>">Tambah Role</a></li><strong></strong>
                      <li><a href="<?php echo site_url('role'); ?>">List Role</a></li>
                    </ul>
                  </li>

                </ul>
              </div>
            <?php
            }
            ?>


            <?php if ($this->session->userdata('modul') == 'cashin') {   ?>

              <div class="menu_section">
                <h3>Transaksi</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-money"></i> Pembayaran<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('byr'); ?>">Tambah Pembayaran</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              </ul>

              <div class="menu_section">
                <h3>Laporan</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-bar-chart"></i> Laporan <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('byr/lap_byr'); ?>">Laporan Cash In</a></li>
                      <li><a href="<?php echo site_url('byr/lap_rkp_kwt'); ?>">Laporan Rekap Kwitansi</a></li>
                      <li><a href="<?php echo site_url('byr/lap_jtmpo'); ?>">Laporan Jatuh Tempo</a></li>
                    </ul>
                  </li>
                </ul>
              </div>

            <?php } ?>

            <?php if ($this->session->userdata('modul') == 'cashout') {   ?>

<div class="menu_section">
                <h3>Transaksi</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-book"></i> Pemakaian Anggaran<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('spk/input_spk'); ?>">Tambah Pemakaian Anggaran</a></li>
                      <li><a href="<?php echo site_url('spk/list_spk'); ?>">List Pemakaian Anggaran</a></li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-money"></i> Permintaan Dana <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('dana/input_dana'); ?>">Tambah Permintaan Dana</a></li>
                      <li><a href="<?php echo site_url('dana'); ?>">List Permintaan Dana</a></li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-lock"></i> Approval <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('dana/list_dana_app'); ?>">List Approval Dana</a></li>
                      <li><a href="<?php echo site_url('spk/list_spk_app'); ?>">List Approval Pemakaian Anggaran</a></li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-area-chart"></i> RAB <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('rab/input_rab_init'); ?>">Tambah RAB</a></li>
                      <li><a href="<?php echo site_url('rab/list_rab_init'); ?>">List RAB</a></li>
                    </ul>
                  </li>

                </ul>
              </div>

<div class="menu_section">
                <h3>Laporan</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-bar-chart"></i> Laporan <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('ang/lap_ang'); ?>">Laporan Pemakaian Anggaran</a></li>
                      <li><a href="<?php echo site_url('ang/lap_ang_mg'); ?>">Laporan Permintaan Dana Perminggu</a></li>
                      <li><a href="<?php echo site_url('rab/lap_rab'); ?>">Laporan Master RAB</a></li>
                    </ul>
                  </li>
                </ul>
              </div>

              <div class="menu_section">
                <h3>Master Data</h3>
                <ul class="nav side-menu">
                  <?php $this->load->view('menu_retensi'); ?>
                  <li><a><i class="fa fa-puzzle-piece"></i> Pos Anggaran <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('ang/input_pa'); ?>">Tambah Pos Anggaran</a></li>
                      <li><a href="<?php echo site_url('ang/list_pa'); ?>">List Pos Anggaran</a></li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-calculator"></i> Anggaran <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('ang/pick_proj'); ?>">Atur Anggaran</a></li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-group"></i> Vendor <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('kon/input_kon'); ?>">Tambah Vendor</a></li>
                      <li><a href="<?php echo site_url('kon/list_kon'); ?>">List Vendor</a></li>
                    </ul>
                  </li>

                  <li><a><i class="fa fa-pie-chart"></i> RAB <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('rab/input_kat'); ?>">Tambah Kategori</a></li>
                      <li><a href="<?php echo site_url('rab/list_kat'); ?>">List Kategori</a></li>
                      <li><a href="<?php echo site_url('rab/pick_proj'); ?>">List Harga</a></li>
                    </ul>
                  </li>

                </ul>
              </div>
            <?php } ?>

            <?php if ($this->session->userdata('modul') == 'penjualan') {   ?>

<div class="menu_section">
                <h3>Transaksi</h3>
                <ul class="nav side-menu">
                  <li><a href="<?php echo site_url('booking/admin'); ?>"><i class="fa fa-calendar-check-o"></i> Booking</a></li>
                  <li><a><i class="fa fa-check-square-o"></i> AKAD <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('akad'); ?>">List Akad</a></li>
                      <li><a href="<?php echo site_url('pengajuan_akad'); ?>">Pengajuan Akad</a></li>
                      <li><a href="<?php echo site_url('akad/retensi'); ?>">List Retensi</a></li>
                      <?php if ($optionalMenuAccess('T003','mpengajuan_retensi_akses_user',array('tpengajuan_retensi','tpengajuan_retensi_detail'),'Pengajuan_retensi')): ?><li><a href="<?php echo site_url('pengajuan_retensi'); ?>">Pengajuan Pencairan Retensi</a></li><?php endif; ?>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-book"></i> SPR <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('spr/input_spr'); ?>">Tambah SPR</a></li>
                      <li><a href="<?php echo site_url('spr'); ?>">List SPR</a></li>
                    </ul>
                  </li>
                </ul>
              </div>

              <div class="menu_section">
                <h3>Laporan</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-bar-chart"></i> Laporan <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('spr/lap_ptn'); ?>">Laporan Potensi</a></li>
                      <li><a href="<?php echo site_url('kom/lap_kom'); ?>">Laporan Komisi Marketing</a></li>
                    </ul>
                  </li>
                </ul>
              </div>

              <div class="menu_section">
                <h3>Master Data</h3>
                <ul class="nav side-menu">
                  <?php $this->load->view('menu_retensi'); ?>
                  <li><a href="<?php echo site_url('notaris'); ?>"><i class="fa fa-briefcase"></i> Notaris</a></li>
                  <li><a href="<?php echo site_url('lot'); ?>"><i class="fa fa-th-large"></i> Pengaturan Lot</a></li>
                  <?php if ($optionalMenuAccess('M009','msiteplan_status_akses_user',array('msiteplan_status'),'Pengaturan_siteplan')): ?><li><a href="<?php echo site_url('pengaturan_siteplan'); ?>"><i class="fa fa-paint-brush"></i> Pengaturan Siteplan</a></li><?php endif; ?>
                  <li><a><i class="fa fa-sitemap"></i> Project <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('proj/input_proj'); ?>">Tambah Project</a></li>
                      <li><a href="<?php echo site_url('proj'); ?>">List Project</a></li>					  					  <li><a href="<?php echo site_url('proj/input_pt'); ?>">Tambah Perusahaan</a></li>					  <li><a href="<?php echo site_url('proj/list_pt'); ?>">List Perusahaan</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-bank"></i> Stok Kavling <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('kav/input_kav'); ?>">Tambah Stok Kavling</a></li>
                      <li><a href="<?php echo site_url('kav'); ?>">List Stok Kavling</a></li>
                      <li><a href="<?php echo site_url('kav/prog_bgn'); ?>">Update Progress Bangun</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-money"></i> Harga Standard<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('hrg/input_hrg'); ?>">Tambah Harga</a></li>
                      <li><a href="<?php echo site_url('hrg'); ?>">List Harga</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-money"></i> Harga Fitur<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('hrgf/input_hrgf'); ?>">Tambah Harga</a></li>
                      <li><a href="<?php echo site_url('hrgf'); ?>">List Harga</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-money"></i> Komisi<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('kom'); ?>">List Komisi</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-group"></i> Sales <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('sls/input_sls'); ?>">Tambah Sales</a></li>
                      <li><a href="<?php echo site_url('sls/list_sls'); ?>">List Sales</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-credit-card"></i> Bank Account <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('bank/input_bankacc'); ?>">Tambah Bank Account</a></li>
                      <li><a href="<?php echo site_url('bank/list_bankacc'); ?>">List Bank Account</a></li>
                    </ul>
                  </li>

                </ul>
              </div>
            <?php } ?>


            <?php if ($this->session->userdata('modul') == 'finance') {   ?>

              <div class="menu_section">
                <h3>Transaksi</h3>
                <ul class="nav side-menu">

                  <li><a><i class="fa fa-money"></i> Kas Besar <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">

                      <li><a>Penerimaan<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                          <li><a href="<?php echo site_url('kas/kb_input_in'); ?>">Tambah Penerimaan</a></li>
                          <li><a href="<?php echo site_url('kas/list_kb_in'); ?>">List Penerimaan</a></li>
                        </ul>
                      </li>



                    </ul>
                  </li>


                </ul>
              </div>

              <div class="menu_section">
                <h3>Laporan</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-bar-chart"></i> Laporan <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('saldo/lap_saldo_bank'); ?>">Laporan Saldo Bank</a></li>
                      <li><a href="<?php echo site_url('saldo/lap_trans_bank'); ?>">Laporan Transaksi Bank</a></li>
                    </ul>
                  </li>
                </ul>
              </div>

            <?php } ?>

            <?php if ($this->session->userdata('modul') == 'reportinv') {   ?>

              <div class="menu_section">
                <h3>Laporan</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-bar-chart"></i> Laporan <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo site_url('inv/lap_per_prfktr'); ?>">Laporan Perkiraan Profit Kotor
                        </a></li>
                      <?php if ($optionalMenuAccess('R002','minvestor_potensi_akses_user',array('tinvestor_cutoff','tinvestor_cutoff_item'),'Investor_potensi','IN')): ?>
                      <li><a href="<?php echo site_url('investor_potensi'); ?>">Laporan Kontrol Potensi</a></li>
                      <?php endif; ?>
                    </ul>
                  </li>
                </ul>
              </div>

            <?php } ?>

          </div>
          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->
          <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
              <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
              <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
              <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" data-confirm-logout href="<?php echo site_url('auth/logout'); ?>">
              <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
          </div>
          <!-- /menu footer buttons -->
        </div>
      </div>

      <!-- top navigation -->
      <div class="top_nav">
        <div class="nav_menu">
          <nav>
            <div class="nav toggle">
              <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
              <li class="theme-control"><button type="button" id="theme-toggle" aria-label="Aktifkan mode gelap" aria-pressed="false"><i class="fa fa-moon-o" aria-hidden="true"></i><span class="theme-label">Gelap</span></button></li>
              <li class="">
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <img class="user-avatar" src="<?php echo html_escape($this->lib->disp_foto()); ?>" data-fallback="<?php echo $this->config->item('assets'); ?>/img/avatar-default.svg" alt="Foto profil" /><?php echo $this->session->userdata('name'); ?>
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                  <li>
                    <a href="<?php echo site_url('users/chg_pass'); ?>">
                      <span>Ganti Password</span>
                    </a>
                  </li>
                  <li>
                    <a href="<?php echo site_url('users/chg_foto'); ?>">
                      <span>Ganti Foto</span>
                    </a>
                  </li>
                  <li><a data-confirm-logout href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <!-- /top navigation -->
