<?php $this->load->view('header'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>List Harga</h3>
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
                          <li><a href="<?php echo site_url('hrg/input_hrg'); ?>"><i class="fa fa-plus-circle"></i> Tambah harga</a>
                           <li><a href="<?php echo site_url('hrg'); ?>"><i class="fa fa-search"></i> Pilih project</a>
                          </li>
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
                          <th>Lot</th>
                          <th>Tipe</th>
                          <th>Hrg Standard</th>
                          <th>Biaya Titipan</th>
                          <th>Tgl Valid</th>
                          <th>Dibuat</th>
                          <th>Tgl Buat</th>
                          <th>Diganti</th>
                          <th>Tgl Ganti</th>
                          <th>Pilihan</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach( $list_hrg as $row)
								{
					  ?>
                      <tr>
            
                      <td><?php echo $row->lot; ?></td>
                      <td><?php echo $row->tipe; ?></td>
                      <td><?php echo  $this->lib->format_idr($row->hrgstd); ?></td>
                      <td><?php echo  $this->lib->format_idr($row->byttp); ?></td>
                       <td><?php echo $this->lib->disp_date($row->tgl); ?></td>
                      <td><?php echo $row->crby; ?></td>
                      <td><?php echo  $this->lib->disp_date($row->crdat); ?></td>
                      <td><?php echo $row->chby; ?></td>
                      <td><?php echo  $this->lib->disp_date($row->chdat); ?></td>
                      <td>
                          <div class="btn-group btn-group-sm">
                              <a title="Lot  <?php echo $row->lot;?> Tipe <?php echo $row->tipe;?> Tgl Valid <?php echo $this->lib->disp_date($row->tgl); ?>" href="<?php echo site_url('hrg/delete_hrg/'.$row->projid.'/'. $row->lot.'/'. $row->tipe.'/'. $row->tgl); ?>" class="btn btn-danger" id="confirm"><i class="fa fa-trash"></i> Hapus</a>
                       <a href="<?php echo site_url('hrg/edit_hrg/'.$row->projid.'/'. $row->lot.'/'. $row->tipe.'/'. $row->tgl); ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Ubah</a>
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
<script src="<?php echo $this->config->item('assets');?>/bootbox.min.js"></script>
 <script>
$("a#confirm").click(function(e) {
	var url = $(this).attr('href');
	var title = $(this).attr('title');
	e.preventDefault();
	
	bootbox.confirm("Hapus harga "+title+" ?", function(result) {
		if(result)
		{
			location.href=url;
		}
	}); 
 
});
    </script>
      