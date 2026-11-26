<?php $this->load->view('header'); ?>
 <link href="<?php echo $this->config->item('assets');?>/bootstrap-select-1.12.3/css/bootstrap-select.min.css" rel="stylesheet">
 <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>List Perjanjian Kerja</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                         <li><a href="#"><i class="fa fa-plus-circle"></i> Tambah SPK</a>
                          </li>
                           <li><a href="#"><i class="fa fa-search"></i> Pilih project</a>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                   <form>
            <h1>Try it yourself</h1>
            <select class="selectpicker" data-live-search="true">
  <option>Mustard</option>
  <option>Ketchup</option>
  <option>Relish</option>
</select>
            <input type="text" name="txt_kon" id="txt_kon" size="30" class="form-control col-md-7 col-xs-12 typeahead" placeholder="Nama Kontraktor" data-provide="typeahead" autocomplete="off">
             <input type="hidden" name="txt_konid" id="txt_konid" size="30" class="form-control col-md-7 col-xs-12 typeahead"  data-provide="typeahead">
             <input type="submit" value="submit" />
        </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

<?php $this->load->view('footer'); ?>
 <script src="<?php echo $this->config->item('assets');?>/bootstrap-select-1.12.3/js/bootstrap-select.min.js"></script>
 <script src="<?php echo $this->config->item('assets');?>/Bootstrap-3-Typeahead-4.0.2/bootstrap3-typeahead.js"></script>
 <script>
 $(document).ready(function () {
        $('#txt_kon').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "<?php echo site_url('test/get_kon'); ?>",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							 $('#txt_konid').val(item.id);
							return item;
                        }));
                    }
                });
            }
        });
    });
    </script>