<?php $this->load->view('header');
?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Laporan Cash In</h3>
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
<!-- awal penambahan bambang 2021-10-27 -->
<li><a href="<?php echo site_url('byr/print_byr/'.$projid.'/'.$mtgl1.'/'.$mtgl2.'/'.$mbtl); ?>"><i class="fa fa-print"></i> Cetak Bukti Bank Masuk</a>
<!-- akhir penambahan bambang 2021-10-27 -->
                  <li><a href="<?php echo site_url('byr/lap_byr'); ?>"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
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
                  <th>Tgl. Kwt</th>
                  <th>No. SPR</th>
                  <th>Blok</th>
                  <th>No. Kwt</th>
                  <th>Konsumen</th>
                  <th>Utk Pembayaran</th>
                  <th>Ang. Ke</th>
                  <th>Jumlah</th>
                  <th>Total UM</th>
                  <th>Hrg Jual</th>
                  <th>Hrg Fitur</th>
                  <th>Bayar</th>
                  <?php if ($this->input->post('chk_btl') == '1') {
                  ?>
                    <th>Ket. Batal</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php

                $total = 0;
                foreach ($list_byr as $row) {
                  $total = $total + $row->total;
                ?>
                  <tr>

                    <td data-order="<?php echo $row->tgl; ?>"><?php echo $this->lib->disp_date($row->tgl); ?></td>
                    <td><?php echo $this->lib->no_spr($row->sprno); ?></td>
                    <td><?php echo $row->blok; ?> - <?php echo $row->no; ?></td>
                    <td><?php echo $this->lib->no_kwt($row->kwtno); ?></td>
                    <td><?php echo $row->nama; ?></td>
                    <td><?php echo $this->lib->tipe_byr_txt($row->tipe); ?></td>
                    <td><?php echo $row->ang; ?></td>
                    <td align="right"><?php echo $this->lib->format_idr($row->total); ?></td>
                    <td align="right"><?php echo $this->lib->format_idr($row->umk); ?></td>
                    <td align="right"><?php echo $this->lib->format_idr($row->hrgstd); ?></td>
                    <td align="right"><?php echo $this->lib->format_idr($row->hrgtotf); ?></td>
                    <td><?php if ($row->byr == 'T') {
                          echo 'TRANSFER';
                        } else {
                          echo 'CASH';
                        }
                        ?></td>
                    <?php if ($this->input->post('chk_btl') == '1') {
                    ?>
                      <td><?php echo $row->ketbtl; ?></td>
                    <?php } ?>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><b>Total :</b></td>
                  <td align="right"><b><?php echo $this->lib->format_idr($total); ?></b></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <?php if ($this->input->post('chk_btl') == '1') {
                  ?>
                    <td></td>
                  <?php } ?>
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

    $('#datatable').DataTable({
      fixedHeader: true,
      dom: 'Blfrtip',
      aLengthMenu: [
        [25, 50, 100, 200, -1],
        [25, 50, 100, 200, "All"]
      ],
      iDisplayLength: -1,
      buttons: [
        'copyHtml5',
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
                //data = $('<p>' + data + '</p>').text();
                return $.isNumeric(data.replace(/[.]/g, '')) ? data.replace(/[.]/g, '') : data;
              }
            }
          }
        },

      ],
    });
  });
</script>