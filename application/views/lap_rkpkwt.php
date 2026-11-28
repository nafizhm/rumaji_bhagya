<?php $this->load->view('header');
?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Laporan Rekap Kwitansi</h3>
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
                  <li><a href="<?php echo site_url('byr/lap_rkp_kwt'); ?>"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
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

            <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">

              <?php
              $no = 0;
              foreach ($sum_kwt as $row) {
                $id = ++$no; ?>
                <div class="panel">
                  <a class="panel-heading" role="tab" id="heading<?php echo $id; ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $id; ?>" aria-expanded="true" aria-controls="collapse<?php echo $id; ?>">
                    <h4 class="panel-title"><?php echo $this->lib->disp_date($row->tgl); ?> = <?php echo $this->lib->format_idr($row->total); ?><?php if ($row->deposit>0): ?> | Deposit: Rp <?php echo $this->lib->format_idr($row->deposit); ?><?php endif; ?></h4>
                  </a>
                  <div id="collapse<?php echo $id; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?php echo $id; ?>">
                    <div class="panel-body">
                      <table class="table table-striped table-bordered">
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
                            <th>Deposit</th>
                            <th>Deposit Digunakan</th>
                            <th>Jumlah Bayar</th>
                            <th>Total UM</th>
                            <th>Hrg Jual</th>
                            <th>Hrg Fitur</th>
                            <th>Bayar</th>
                            <th>Ket. Batal</th>
                            <th>Verifikasi</th>
                            <?php if ($verify == 1) {
                            ?>
                              <th>Pilihan</th>
                            <?php
                            }
                            ?>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $list_rkp_kwt = $this->mod_kwt->list_rkp_kwt($projid, $row->tgl)->result();
                          foreach ($list_rkp_kwt as $rows) {
                            $bg = "";
                            if (($rows->total <= 0 && $rows->deposit <= 0) || $rows->btl == 1) {
                              $bg = "yellow";
                            }
                          ?>
                            <tr style="background-color:<?php echo  $bg; ?>">
                              <td data-order="<?php echo $rows->tgl; ?>"><?php echo $this->lib->disp_date($rows->tgl); ?></td>
                              <td><?php echo $this->lib->no_spr($rows->sprno); ?></td>
                              <td><?php echo $rows->blok; ?> - <?php echo $rows->no; ?></td>
                              <td><?php echo $rows->total==0 && $rows->deposit>0?'DEP-'.(int)$rows->projid.'-'.str_pad((string)(int)$rows->kwtno,6,'0',STR_PAD_LEFT):$this->lib->no_kwt($rows->kwtno); ?></td>
                              <td><?php echo $rows->nama; ?></td>
                              <td><?php echo $this->lib->tipe_byr_txt($rows->tipe); ?></td>
                              <td><?php echo $rows->ang; ?></td>
                              <td align="right"><?php echo $this->lib->format_idr($rows->total); ?></td>
                              <td align="right"><?php echo $this->lib->format_idr($rows->deposit); ?></td>
                              <td align="right"><?php echo $this->lib->format_idr($rows->deposit_digunakan); ?></td>
                              <td align="right"><?php echo $this->lib->format_idr($rows->jumlah_bayar); ?></td>
                              <td align="right"><?php echo $this->lib->format_idr($rows->umk); ?></td>
                              <td align="right"><?php echo $this->lib->format_idr($rows->hrgstd); ?></td>
                              <td align="right"><?php echo $this->lib->format_idr($rows->hrgtotf); ?></td>
                              <td><?php if ($rows->byr == 'T') {
                                    echo 'TRANSFER';
                                  } else {
                                    echo 'CASH';
                                  }
                                  ?></td>
                              <td><?php echo $rows->ketbtl; ?></td>
                              <td align="center">
                                <?php if ($rows->verby == '' && $bg == '' && $rows->crdat >= $_SERVER['TGL_VER']) {
                                ?>
                                  <i class="fa fa-close"></i> Belum diverifikasi</td>
                            <?php } else if ($rows->verby != '' && $bg == '' && $rows->crdat >= $_SERVER['TGL_VER']) { ?>
                              <i class="fa fa-check"></i> Sudah diverifikasi</td>
                            <?php } ?>
                            <?php if ($verify == 1 &&  $bg == "" && $rows->verby == '' && $rows->crdat >= $_SERVER['TGL_VER']) {
                            ?>
                              <td><a href="#" data-deposit="<?php echo $this->lib->format_idr($rows->deposit); ?>" data-jumlah-bayar="<?php echo $this->lib->format_idr($rows->jumlah_bayar); ?>" data-total="<?php echo $this->lib->format_idr($rows->total); ?>" data-projid="<?php echo $rows->projid; ?>" data-kwtno="<?php echo $this->lib->no_kwt($rows->kwtno); ?>" data-toggle="modal" data-target=".bs-example-modal-lg" class="VerDialog btn btn-primary"><i class="fa fa-check"></i> Verifikasi</a></td>
                            <?php
                            } else {
                            ?>
                              <td></td>
                            <?php
                            }
                            ?>
                            </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
  <form id="demo-form2" class="form-horizontal form-label-left" method="post" action="<?php echo site_url('byr/do_verify_kwt/'); ?>">
    <input type="hidden" id="txt_projid" name="txt_projid" />
    <input type="hidden" id="txt_kwtno" name="txt_kwtno" />
    <input type="hidden" id="txt_tgl_from" name="txt_tgl_from" value="<?php echo $tgl_from; ?>" />
    <input type="hidden" id="txt_tgl_to" name="txt_tgl_to" value="<?php echo $tgl_to; ?>"/>
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"></h4>
        </div>

        <div class="modal-body">

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Setor Bank
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input name="txt_tgl" id="txt_tgl" class="form-control col-md-7 col-xs-12" required>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah (Rp)
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <p class="form-control-static"><label id="lbl-total"></label></p>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-3">Deposit (Rp)</label>
            <div class="col-sm-6"><p class="form-control-static"><strong id="lbl-deposit"></strong></p><small>Deposit dari pembayaran kwitansi ini.</small></div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-3">Jumlah Bayar (Rp)</label>
            <div class="col-sm-6"><p class="form-control-static"><strong id="lbl-jumlah-bayar"></strong></p></div>
          </div>
          <div class="form-group">
            <label class="control-label col-sm-3">Deposit Digunakan (Rp)</label>
            <div class="col-sm-6"><p class="form-control-static"><strong id="lbl-deposit-digunakan"></strong></p></div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        </div>

      </div>
    </div>
  </form>
</div>
<!-- /modals -->
<?php $this->load->view('footer'); ?>
<script type="text/javascript">
  $('input[name="txt_tgl"]').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    autoUpdateInput: false
    /*,
    					 locale: {
    						format: 'DD/MM/YYYY'
    					}*/
  });

  $('input[name="txt_tgl"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });

  $(document).ready(function() {

    $(document).on("click", ".VerDialog", function() {
      $('#lbl-total').text($(this).data('total'));
      $('#lbl-deposit').text($(this).attr('data-deposit'));
      $('#lbl-jumlah-bayar').text($(this).attr('data-jumlah-bayar'));
      $('#lbl-deposit-digunakan').text($(this).closest('tr').children('td').eq(9).text());
      $("#myModalLabel").html("Verifikasi Kwitansi No " + $(this).data('kwtno'));
      $("#txt_projid").val($(this).data('projid'));
      $("#txt_kwtno").val($(this).data('kwtno'));

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
      iDisplayLength: -1,
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5',
        {
          extend: 'print',
          footer: true
        }
      ]
    });



  });
</script>
