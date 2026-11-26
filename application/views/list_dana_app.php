<?php $this->load->view('header'); ?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>List Approval Permintaan Dana</h3>
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
                  <li><a href="<?php echo site_url('dana/list_dana_app'); ?>"><i class="fa fa-search"></i> Pilih project</a>
                </ul>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <form action="<?= site_url('dana/do_app_multi'); ?>" method="POST">
            <div class="x_content">
              <?php $this->lib->disp_msg(); ?>
              <br />
              <table id="datatable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th><input type="checkbox" class="check" id="cAll"></th>
                    <th>No. Req.</th>
                    <th>Pos Ang.</th>
                    <th>Biaya</th>
                    <th>No. SPK/WO/PAI</th>
                    <th>Nilai SPK/WO/PAI</th>
                    <th>Sdh Dibyr</th>
                    <th>Sisa</th>
                    <th>Permin. Dana</th>
                    <th>Ket.</th>
                    <th>Mg/Bln/Thn</th>
                    <th>Dibuat</th>
                    <th>Tgl Buat</th>
                    <th>Pilihan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($list_dana_app as $row) {
                    if ($row->spkno != 0) {
                      $sql_get_spk = $this->db->query("SELECT * FROM tspk WHERE projid = '" . $row->projid . "' AND spkno = '" . $row->spkno . "'");
                      foreach ($sql_get_spk->result()  as $row_spk);
                      $tot_dibyr = $this->lib->get_tot_dana($row->projid, $row->spkno);
                    } else {
                      $tot_dibyr = 0;
                    }
                  ?>
                    <tr>
                      <td><input type="checkbox" name="chk[]" value="<?= $row->projid . ';' . $row->reqno; ?>" class="c" /></td>
                      <td><?php echo $this->lib->no_req($row->reqno); ?></td>
                      <td><?php echo $row->nampa; ?></td>
                      <td><?php echo $row->namby; ?></td>
                      <td><?php if ($row->spkno != 0) {
                            echo $this->lib->format_spkno($row->projid, $row->spkno);
                          } ?></td>
                      <td><?php if ($row->spkno != 0) {
                            echo $this->lib->format_idr($row_spk->total);
                          } ?></td>
                      <td><?php echo $this->lib->format_idr($tot_dibyr); ?></td>
                      <td><?php if ($row->spkno != 0) {
                            echo  $this->lib->format_idr($row_spk->total - $tot_dibyr);
                          } ?></td>
                      <td><?php echo $this->lib->format_idr($row->total); ?></td>
                      <td><?php echo $row->ket; ?></td>
                      <td><?php echo $row->week . '/' . $row->mon . '/' . $row->year; ?></td>
                      <td><?php echo $row->crby; ?></td>
                      <td data-order="<?php echo $row->crdat; ?>"><?php echo  $this->lib->disp_date($row->crdat); ?></td>
                      <td>
                        <div class="btn-group btn-group-sm">
                          <a href="<?php echo site_url('dana/edit_dana_app/' . $row->projid . '/' . $row->reqno); ?>" class="btn btn-primary"><i class="fa fa-search"></i> Detail</a>
                        </div>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Approve</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
<?php $this->load->view('footer'); ?>
<script>
  table = $('#datatable').DataTable();

  table.destroy();

  $('#datatable').DataTable({

    fixedHeader: true,
    dom: 'Blfrtip',
    aLengthMenu: [
      [25, 50, 100, 200, -1],
      [25, 50, 100, 200, "All"]
    ],

    iDisplayLength: 25,
    buttons: [
      'copyHtml5',
      //'excelHtml5',
      {
        extend: 'excelHtml5',
        exportOptions: {
          columns: 'th:not(:last-child)',
          rows: ':visible',
          format: {
            body: function(data, row, column, node) {
              //data = $('<p>' + data + '</p>').text();
              return $.isNumeric(data.replace(/[.]/g, '')) ? data.replace(/[.]/g, '') : data;
            }
          }
        }
      },
      'csvHtml5',
      'pdfHtml5',
      'print',
    ]
  });
</script>
<script type="text/javascript">
  $("#cAll").click(function() {
    $(".c").prop('checked', $(this).prop('checked'));
  });
</script>