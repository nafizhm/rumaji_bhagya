<?php $this->load->view('header'); ?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>List Pemakaian Anggaran</h3>
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
                  <li><a href="<?php echo site_url('spk/input_spk'); ?>"><i class="fa fa-plus-circle"></i> Tambah SPK</a>
                  </li>
                  <li><a href="<?php echo site_url('spk/list_spk'); ?>"><i class="fa fa-search"></i> Pilih project</a>
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
                  <th>Tipe</th>
                  <th>Tanggal</th>
                  <th>Total</th>
                  <th>Keterangan</th>
                  <th>Status</th>
                  <th>Pilihan</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($list_spk as $row) {
                ?>
                  <tr>

                    <td data-sort="<?=$row->spkno;?>"><?php echo $this->lib->format_spkno($row->projid, $row->spkno); ?></td>
                    <td><?php echo $row->tipe; ?></td>
                    <td data-order="<?php echo $row->tgl; ?>"><?php echo  $this->lib->disp_date($row->tgl); ?></td>
                    <td><?php echo  $this->lib->format_idr($row->total); ?></td>
                    <td><?php echo $row->ket; ?></td>
                    <td><?php echo $this->lib->get_status_text('APP', $row->status); ?></td>
                    <td>
                      <div class="btn-group btn-group-sm">
                        <a href="<?php echo site_url('spk/edit_spk/' . $row->spkno . '/' . $row->projid); ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Ubah</a>
                        <?php /*  if($row->status == 'A02')
								{ */
                        ?>
                        <a target="_blank" href="<?php echo site_url('spk/print_spk/' . $row->spkno . '/' . $row->projid); ?>" class="btn btn-success"><i class="fa fa-print"></i> Cetak</a>
                        <?php
                        /*} */
                        ?>
                        <?php if ($row->status == 'R02') {

                        ?>
                          <a title="<?php echo $this->lib->format_spkno($row->projid, $row->spkno); ?>" href="<?php echo site_url('spk/delete_spk/' . $row->spkno . '/' . $row->projid); ?>" class="btn btn-danger" id="confirm"><i class="fa fa-trash"></i> Hapus</a>
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

    bootbox.confirm("Hapus No. SPK " + title + " ?", function(result) {
      if (result) {
        location.href = url;
      }
    });

  });
  table = $('#datatable').DataTable();

  table.destroy();

  $('#datatable').DataTable({
	order: [[0, 'asc']],
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