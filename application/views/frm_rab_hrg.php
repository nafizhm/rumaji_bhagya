<?php $this->load->view('header'); ?>
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
          <div class="x_content">
            <br />
            <form id="demo-form2" class="form-horizontal form-label-left" method="post" action="<?php echo $form_action; ?>">
              <input name="txt_projid" id="txt_projid" type="hidden" value="<?php echo $projid; ?>">
              <input name="txt_katid" id="txt_katid" type="hidden" value="<?php echo isset($katid) ? $katid : ''; ?>">
              <input name="txt_itmid" id="txt_itmid" type="hidden" value="<?php echo isset($itmid) ? $itmid : ''; ?>">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="cb_kat" id="cb_kat" required class="form-control" <?php echo isset($disabled) ? $disabled : ''; ?>>
                    <option value="">Silahkan Pilih</option>
                    <?php echo $cb_kat; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Item<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="cb_itm" id="cb_itm" required class="form-control" <?php echo isset($disabled) ? $disabled : ''; ?>>
                    <div id="cb_itm"> <?php echo $cb_itm; ?></div>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Harga<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_hrg" type="text" required="required" class="form-control col-md-7 col-xs-12" id="txt_wo_tot" value="<?php echo set_value('txt_hrg', isset($default['txt_hrg']) ? $default['txt_hrg'] : ''); ?>" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input name="txt_ket" id="txt_ket" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('txt_ket', isset($default['txt_ket']) ? $default['txt_ket'] : ''); ?>" maxlength="100" required="required">
                </div>
              </div>

              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <a href="javascript:history.back()" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>
                  <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                </div>
              </div>

            </form>
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
    $("#cb_kat").change(function() {
      var katid = $("#cb_kat option:selected").attr('value');
      var projid = $("#txt_projid").val();

      if (katid.length > 0) {
        //alert(paid);
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('rab/get_itm'); ?>",
          data: "projid=" + projid + "&katid=" + katid,
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
  });
</script>