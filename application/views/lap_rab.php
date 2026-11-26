<?php $this->load->view('header'); ?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Laporan Master Rencana Anggaran Biaya</h3>
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
                  <li><a href="<?php echo site_url('rab/lap_rab'); ?>"><i class="fa fa-search"></i> Pilih project</a>
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
            <table width="808" border="1" class="table table-bordered">
              <?php
              foreach ($kat as $row) {
              ?>
                <tr>
                  <th colspan="5">
                    <h4> <strong><?php echo $row->namkat; ?></strong></h4>
                  </th>
                </tr>
                <tr>
                  <th width="385">Item</th>
                  <th width="407">Satuan</th>
                  <th width="407">Harga</th>
                  <th width="407">Keterangan</th>
                </tr>
                <?php
                $query = $this->mod_rab->get_itmhrg_projid($this->input->post('cb_proj'), $row->katid)->result();
                foreach ($query as $row_itm) {
                ?>
                  <tr>
                    <td width="385"><?php echo $row_itm->namitm; ?></td>
                    <td width="407"><?php echo $row_itm->namsat; ?></td>
                    <td width="407" align="right"><?php echo $this->lib->format_idr($row_itm->hrg); ?></td>
                    <td width="407"><?php echo $row_itm->ket; ?></td>
                  </tr>
              <?php }
              } ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
<?php $this->load->view('footer'); ?>