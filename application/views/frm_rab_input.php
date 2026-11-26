<?php $this->load->view('header'); ?>
<link href="<?php echo $this->config->item('assets'); ?>/bootstrap-select-1.12.3/css/bootstrap-select.min.css" rel="stylesheet">
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>RAB</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo $title; ?></h2>

            <div class="clearfix"></div>
          </div>
          <?php $this->lib->disp_msg(); ?>
          <div id="alert" class="" role="alert">
            <span id="message"></span>
          </div>
          <div class="x_content">
            <br />
            <form id="demo-form2" class="form-horizontal form-label-left" method="post" action="<?php echo $form_action; ?>">
              <input name="txt_projid" id="txt_projid" type="hidden" value="<?php echo $projid; ?>">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Pekerjaan
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_kerja" id="txt_kerja" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_kerja', isset($default['txt_kerja']) ? $default['txt_kerja'] : ''); ?>" required>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_tgl" id="txt_tgl" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_tgl', isset($default['txt_tgl']) ? $default['txt_tgl'] : ''); ?>" required>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-plus-circle"></i> Tambah Item</button>
                </div>
              </div>

              <table id="itemTable" class="table table-bordered
                     table-condensed table-striped">
                <thead>
                  <tr>
                    <th>Kontraktor</th>
                    <th>Uraian Pekerjaan</th>
                    <th>Volume</th>
                    <th>Harga Satuan ( Rp )</th>
                    <th>Total ( Rp )</th>
                    <th>Keterangan</th>
                    <th>Pilihan</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr style="background-color:yellow">
                    <th colspan="3"></th>
                    <th style="text-align:right">Total Semua ( Rp )</th>
                    <th style="text-align:right"><input type="hidden" id="txt_total"><span id="total"></span></th>
                    <th colspan="2"></th>
                  </tr>
                </tfoot>
              </table>

              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <a href="javascript:history.back()" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>
                  <button type="button" id="submit" onclick="submitForm();" class="btn btn-success"><i class="fa fa-save"></i> <span id="loading"></span></button>
                </div>
              </div>
            </form>

            <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
              <form data-parsley-validate id="demo-form3" class="form-horizontal form-label-left">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">

                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                      </button>
                      <h4 class="modal-title" id="myModalLabel3"> Tambah Item</h4>
                    </div>

                    <div class="modal-body">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kontraktor
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_kon" id="cb_kon" class="form-control selectpicker" data-live-search="true" required>
                            <?php echo $cb_kon; ?>
                          </select>
                          <input name="txt_namkon" id="txt_namkon" class="form-control col-md-7 col-xs-12" type="hidden">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_kat" id="cb_kat" required class="form-control">
                            <option value="">Silahkan Pilih</option>
                            <?php echo $cb_kat; ?>
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Item/Pekerjaan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="cb_itm" id="cb_itm" required class="form-control">
                            <div id="cb_itm"> <?php echo $cb_itm; ?></div>
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Satuan ( Rp )
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_hrg" id="txt_hrg" type="text" class="form-control col-md-7 col-xs-12" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true" readonly="readonly">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_wo">Volume
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_vol" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_vol">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total ( Rp )
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_tot" id="txt_tot" type="text" class="form-control col-md-7 col-xs-12" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true" readonly="readonly">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="txt_ket">Keterangan
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_ket" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_ket">
                        </div>
                      </div>

                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-success" onclick="itemAdd();"><i class="fa fa-plus-circle"></i> Tambah</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    </div>

                  </div>
                </div>
              </form>
            </div>
            <!-- /modals -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /page content -->
<?php $this->load->view('footer'); ?>
<script src="<?php echo $this->config->item('assets'); ?>/bootstrap-select-1.12.3/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
  function submitForm() {
    var form = $('#demo-form2');
    var result = confirm("Simpan data?");
    if (result) {
      if ($("#itemTable tr").length == 2) {
        alert('Item harus diisi!');
        //event.preventDefault();
      } else if (!form.parsley().validate()) {
        alert('Form harus diisi!');
        //event.preventDefault();
      } else {


        $.ajax({
          type: 'post',
          url: '<?php echo site_url('rab/do_input_rab'); ?>',
          dataType: "json",
          data: $('#demo-form2').serialize(),
          beforeSend: function() {
            $("#submit").prop("disabled", true);
            $("#loading").html("Sedang proses, harap tunggu....");
          },
          success: function(data) {
            $("#loading").html("Simpan");
            $("#submit").prop("disabled", false);
            $("#message").html(data.message);
            $("#alert").fadeIn();
            if (data.error == 0) {
              $('#alert').attr('class', 'alert alert-success alert-dismissible');
              $("#alert").fadeOut(5000);
              $("#itemTable tbody").remove();
              $("#txt_kerja").val("");
              $("#total").text("0");
              $("#txt_total").val("0");
              $("#txt_tgl").val("");
              $("#txt_kerja").focus();

            } else {
              $('#alert').attr('class', 'alert alert-error alert-dismissible');
            }

          },
          error: function(xhr, Status, err) {
            $("#loading").html("Simpan");
            $("#submit").prop("disabled", false);
          },
        });

      }
    }
  }

  $(document).ready(function() {
    $("#loading").html("Simpan");
    $("#total").html('0');
    $("#txt_kerja").focus();

    $('input[name="txt_tgl"]').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      autoUpdateInput: false
    });

    $('input[name="txt_tgl"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
    });

  });

  $("#cb_kon").change(function() {
    //alert($("#cb_kon :selected").text());
    $("#txt_namkon").val($("#cb_kon :selected").text());
  });

  $("#cb_itm").change(function() {
    var itmid = $("#cb_itm option:selected").attr('value');
    var projid = $("#txt_projid").val();
    var katid = $("#cb_kat").val();
    $("#txt_vol").val('0');
    $("#txt_tot").val('0');
    $("#txt_hrg").val('0');
    if (itmid.length > 0) {
      $.ajax({
        type: "POST",
        url: "<?php echo site_url('rab/get_itm_hrg'); ?>",
        data: "projid=" + projid + "&katid=" + katid + "&itmid=" + itmid,
        cache: false,
        beforeSend: function() {
          $("#txt_hrg").val('Loading.....');
        },
        success: function(data) {
          $("#txt_hrg").val(data);
        }
      });
    } else {
      $("#cb_itm").html("");
    }
  });

  $("#cb_kat").change(function() {
    var katid = $("#cb_kat option:selected").attr('value');
    var projid = $("#txt_projid").val();

    $("#txt_hrg").val('0');
    $("#txt_tot").val('0');

    if (katid.length > 0) {
      //alert(paid);
      $.ajax({
        type: "POST",
        url: "<?php echo site_url('rab/get_itm2'); ?>",
        data: "katid=" + katid,
        cache: false,
        beforeSend: function() {
          $('#cb_itm').html('<option>Loading....</option>');
        },
        success: function(html) {
          $("#cb_itm").html(html);
        }
      });
    } else {
      $("#cb_itm").html("");
    }
  });

  function convMoney(money) {
    var conv;
    while (money.indexOf('.') != -1) money = money.replace('.', '');
    var final = money.split(",");
    return final[0];
  }

  function delCommas(money) {
    while (money.indexOf(',') != -1) money = money.replace(',', '');
    return money;
  }

  function hitung() {
    var hrg = convMoney($("#txt_hrg").val());
    var vol = convQty($("#txt_vol").val());

    var total = parseFloat(vol) * parseFloat(hrg);
    //alert(parseFloat(hrg.replace('.', '')));
    $("#txt_tot").val(total);
  }

  $("#txt_vol").keyup(function() {
    hitung();
  });

  function itemAdd() {
    var form = $('#demo-form3');

    if ($("#txt_namkon").val() == '') {
      alert('Nama Kontraktor Harus Diisi');
    } else {

      if (form.parsley().validate()) {

        var konid = $('#cb_kon').val();
        var namkon = $('#txt_namkon').val();
        var tot = convMoney($('#txt_tot').val());
        var tot_txt = delCommas($('#txt_tot').val());
        var hrg = convMoney($('#txt_hrg').val());
        var hrg_txt = delCommas($('#txt_hrg').val());
        var itmid = $('#cb_itm').val();
        var itmnam = $("#cb_itm :selected").text();
        var ket = $('#txt_ket').val();
        var vol = convQty($('#txt_vol').val());
        var vol_txt = $('#txt_vol').val();

        if ($("#itemTable tbody").length == 0) {
          $("#itemTable").append("<tbody></tbody>");
        }

        if ($("#txt_total").val() == '') {
          $("#txt_total").val(parseFloat(0) + parseFloat(tot));
        } else {
          $("#txt_total").val(parseFloat(tot) + parseFloat($("#txt_total").val()));
        }

        $("#total").html(formatMoney($("#txt_total").val()));

        $("#itemTable tbody").append(
          "<tr data-total='" + tot + "'>" +
          "<td><input type='hidden' name='konid[]' id='konid[]' value='" + konid + "'>" + namkon + "</td>" +
          "<td><input type='hidden' name='itmid[]' id='itmid[]' value='" + itmid + "'>" + itmnam + "</td>" +
          "<td><input type='hidden' name='vol[]' id='vol[]' value='" + vol + "'>" + vol_txt + "</td>" +
          "<td align='right'><input type='hidden' name='hrg[]' id='hrg[]' value='" + hrg + "'>" + hrg_txt + "</td>" +
          "<td align='right'><input type='hidden' name='tot[]' id='tot[]' value='" + tot + "'>" + tot_txt + "</td>" +
          "<td><input type='hidden' name='ket[]' id='ket[]' value='" + ket + "'>" + ket + "</td>" +
          "<td><button type='button' " +
          "onclick='itemDelete(this);' " +
          "class='btn btn-default'>" +
          "<span class='glyphicon glyphicon-trash' /></button></td></tr>"
        );

        $('#myModal').modal('hide');
      }

    }

  }

  function convQty(val) {
    return val.replace(',', '.')
  }

  function formatMoney(amount, decimalCount = 0, decimal = ",", thousands = ".") {
    try {
      decimalCount = Math.abs(decimalCount);
      decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

      const negativeSign = amount < 0 ? "-" : "";

      let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
      let j = (i.length > 3) ? i.length % 3 : 0;

      return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
      console.log(e)
    }
  };

  function itemDelete(ctl) {
    var result = confirm("Hapus item?");

    if (result) {
      total = parseFloat($("#txt_total").val()) - parseFloat($(ctl).parents("tr").attr('data-total'));
      $("#total").html(formatMoney(total));
      $("#txt_total").val(total);
      $(ctl).parents("tr").remove();
    }

  }

  function clearForm() {
    $('#cb_kon').prop('selectedIndex', 0);
    $(".selectpicker").selectpicker('refresh');
    $("#txt_tot").val('0');
    $("#cb_itm").html("");
    $('#cb_kat').prop('selectedIndex', 0);
    $("#txt_ket").val("");
    $("#txt_vol").val("");
  }

  $('#myModal').on('hidden.bs.modal', function(e) {
    // do something...
    clearForm();
  })
</script>