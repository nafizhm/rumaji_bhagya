<?php $this->load->view('header'); ?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>List Approval Pemakaian Anggaran</h3>
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
                  <li><a href="<?php echo site_url('spk/list_spk_app'); ?>"><i class="fa fa-search"></i> Pilih project</a>
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
                  <th>No. Dokumen</th>
                  <th>Pos Ang.</th>
                  <th>Biaya</th>
                  <th>Tipe</th>
                  <th>Tanggal</th>
                  <th>Total</th>
                  <th>Ket.</th>
                  <th>Pilihan</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($list_spk_app as $row) {
                ?>
                  <tr>

                    <td><?php echo $this->lib->format_spkno($row->projid, $row->spkno); ?></td>
                    <td><?php echo $row->nampa; ?></td>
                    <td><?php echo $row->namby; ?></td>
                    <td><?php echo $row->tipe; ?></td>
                    <td data-order="<?php echo $row->tgl; ?>"><?php echo  $this->lib->disp_date($row->tgl); ?></td>
                    <td><?php echo $this->lib->format_idr($row->total); ?></td>
                    <td><?php echo $row->ket; ?></td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <a href="<?php echo site_url('spk/edit_spk_app/' . $row->projid . '/' . $row->spkno); ?>" class="btn btn-primary"><i class="fa fa-search"></i> Detail</a>
                      </div>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
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