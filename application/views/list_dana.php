<?php $this->load->view('header'); ?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>List Permintaan Dana</h3>
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
                  <li><a href="<?php echo site_url('dana/input_dana'); ?>"><i class="fa fa-plus-circle"></i> Tambah Permintaan Dana</a>
                  </li>
                  <li><a href="<?php echo site_url('dana/print_dana/'.$projid); ?>"><i class="fa fa-print"></i> Cetak Bukti Bank Keluar</a>
                  <li><a href="<?php echo site_url('dana/list_dana'); ?>"><i class="fa fa-search"></i> Pilih project</a>
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
                  <th>No. Request</th>
                  <th>SPK/WO/PAI</th>
                  <th>Total</th>
                  <th>Keterangan</th>
                  <th>Status</th>
                  <th>Dibuat</th>
                  <th>Tgl Buat</th>
                  <!--- <th>Diganti</th>
                          <th>Tgl Ganti</th> --->
                  <th>Pilihan</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($list_dana as $row) {
                ?>
                  <tr>
                    <td><?php echo $this->lib->no_req($row->reqno); ?></td>
                    <td><?php echo $this->lib->format_spkno($row->projid, $row->spkno); ?></td>
                    <td><?php echo  $this->lib->format_idr($row->total); ?></td>
                    <td><?php echo $row->ket; ?></td>
                    <td><?php echo $this->lib->get_status_text('APP', $row->status); ?></td>
                    <td><?php echo $row->crby; ?></td>
                    <td data-order="<?php echo $row->crdat; ?>"><?php echo  $this->lib->disp_date($row->crdat); ?></td>
                    <!---  <td><?php //echo $row->chby; 
                                ?></td>
                      <td><?php //echo  $this->lib->disp_date($row->chdat); 
                          ?></td> --->
                    <td>
                      <div class="btn-group btn-group-sm">
                        <a href="<?php echo site_url('dana/edit_dana/' . $row->projid . '/' . $row->reqno); ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Ubah</a>
                        <?php if (substr($row->status, 0, 1) == 'R') {
                        ?>
                          <a title="<?php echo $this->lib->no_req($row->reqno); ?>" href="<?php echo site_url('dana/delete_dana/' . $row->projid . '/' . $row->reqno); ?>" class="btn btn-danger" id="confirm"><i class="fa fa-trash"></i> Hapus</a>
                        <?php
                        }
                        ?>
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
<script src="<?php echo $this->config->item('assets'); ?>/bootbox.min.js"></script>
<script>
  $("a#confirm").click(function(e) {
    var url = $(this).attr('href');
    var title = $(this).attr('title');
    e.preventDefault();

    bootbox.confirm("Hapus No. Request " + title + " ?", function(result) {
      if (result) {
        location.href = url;
      }
    });

  });

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