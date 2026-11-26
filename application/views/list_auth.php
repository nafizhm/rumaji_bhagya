<?php $this->load->view('header'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Role</h3>
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
                          <li><a href="<?php echo site_url('role/input_role'); ?>"><i class="fa fa-plus-circle"></i> Tambah Role</a>
                          </li>
                           <li><a href="<?php echo site_url('role/list_role'); ?>"><i class="fa fa-plus-circle"></i> List Role</a>
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
                    <form action="<?php echo $form_action; ?>" method="post">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                    <table class="table table-striped table-bordered" id="datatable">
                      <thead>
                        <tr>
                          <th>Modul</th>
                          <th>Menu</th>
                          <th><input type="checkbox" class="check" id="cAll"> Create</th>
                          <th><input type="checkbox" class="check" id="rAll"> Read</th>
                          <th><input type="checkbox" class="check" id="uAll"> Update</th>
                          <th><input type="checkbox" class="check" id="dAll"> Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php 
					  $i = 0;
					  foreach( $list_auth as $row)
								{
									 $i++;
					  ?>
                      <tr>
                      <td><?php echo $row->namod; ?><input type="hidden" name="chk_roleid[]" value="<?php echo $row->roleid; ?>" /><input type="hidden" name="chk_moid[]" value="<?php echo $row->moid; ?>" /><input type="hidden" name="chk_mid[]" value="<?php echo $row->mid; ?>" /></td>
                      <td><?php echo $row->mdesc; ?></td>
                      <td><input type="checkbox" name="chk_c<?php echo $i; ?>" value="1" <?php echo set_checkbox('chk_c'.$i, '1',($row->c == 1)?true:false); ?> class="c" /> Create</td>
                      <td><input type="checkbox" name="chk_r<?php echo $i; ?>" value="1" <?php echo set_checkbox('chk_r'.$i, '1',($row->r == 1)?true:false); ?> class="r"/> Read</td>
                      <td><input type="checkbox" name="chk_u<?php echo $i; ?>" value="1" <?php echo set_checkbox('chk_u'.$i, '1',($row->u == 1)?true:false); ?> class="u"/> Update</td>
                      <td><input type="checkbox" name="chk_d<?php echo $i; ?>" value="1" <?php echo set_checkbox('chk_d'.$i, '1',($row->d == 1)?true:false); ?> class="d"/> Delete</td>
                      </tr>
                      <?php
								}
					?>
                      </tbody>
                    </table>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
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
	
	table = $('#datatable').DataTable();
 
table.destroy();

   $('#datatable').DataTable( {
	    fixedHeader: true,
          aLengthMenu: [
        [-1],
        ["All"]
    ]
    } );
    
} );
</script>
<script type="text/javascript">

$("#cAll").click(function () {
    $(".c").prop('checked', $(this).prop('checked'));
});
$("#rAll").click(function () {
    $(".r").prop('checked', $(this).prop('checked'));
});
$("#uAll").click(function () {
    $(".u").prop('checked', $(this).prop('checked'));
});
$("#dAll").click(function () {
    $(".d").prop('checked', $(this).prop('checked'));
});
</script>