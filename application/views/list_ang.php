<?php $this->load->view('header'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Atur Anggaran</h3>
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
                           <li><a href="<?php echo site_url('ang/pick_proj'); ?>"><i class="fa fa-search"></i> Pilih project</a>
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
                    <table width="808" border="1" class="table table-bordered">
 <?php 
 $total_all = 0;
 $total = 0;
 foreach( $list_pa as $row_pa)
								{
					  ?>
                      <tr>
                        <th colspan="3"><h4> <?php echo $row_pa->paid; ?> - <?php echo $row_pa->nampa; ?></h4><a href="<?php echo site_url('ang/input_ang/'.$projid.'/'.$row_pa->paid); ?>" class="btn btn-primary btn-xs"><i class="fa fa-plus-circle"></i> Tambah Anggaran</a></th>
                      </tr>
                      <tr>
                        <th width="385">Biaya</th>
                        <th width="407">Jumlah Anggaran</th>
                        <th width="407">Pilihan</th>
                      </tr>
                       <?php 
					   $total_all = $total_all + $total;
					   $total = 0;
			   $query = $this->db->query("SELECT mby.namby, mang.byid, mang.total 
			   FROM mang 
			   INNER JOIN mby ON mby.byid = mang.byid AND mby.paid = mang.paid
			   WHERE mang.projid = '".$projid."' AND mang.paid = '".$row_pa->paid."'");
				foreach( $query->result()  as $row_ang)
				{
					$total = $row_ang->total + $total;
  			?>
             		<tr>
                        <td width="385"><?php echo $row_ang->byid; ?> - <?php echo $row_ang->namby; ?></td>
                        <td width="407" align="right"><?php echo $this->lib->format_idr($row_ang->total); ?></td>
                        <td width="407"><a href="<?php echo site_url('ang/edit_ang/'.$projid.'/'.$row_pa->paid.'/'.$row_ang->byid); ?>" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Ubah</a></td>
                      </tr>
            
            <?php } ?>
              <tr>
                        <td width="385">Total</td>
                        <td width="407" align="right"><b><?php echo $this->lib->format_idr($total); ?></b></td>
                        <td width="407"></td>
                      </tr>
<?php } ?>
 <tr>
                        <td width="385">Total Anggaran Project</td>
                        <td width="407" align="right"><b><?php echo $this->lib->format_idr($total_all); ?></b></td>
                        <td width="407"></td>
                      </tr>
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
	
	bootbox.confirm("Hapus anggaran "+title+" ?", function(result) {
		if(result)
		{
			location.href=url;
		}
	}); 
 
});
    </script>
      