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
                    <h2>List Role</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="<?php echo site_url('role/input_role'); ?>"><i class="fa fa-plus-circle"></i> Tambah Role</a>
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
                          <th>Role ID</th>
                          <th>Nama Role</th>
                          <th>Pilihan</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach( $list_role as $row)
								{
					  ?>
                      <tr>
            
                      <td><?php echo $row->roleid; ?></td>
                      <td><?php echo $row->namrol; ?></td>
                      <td>
                       <div class="btn-group btn-group-sm">
                        <a href="<?php echo site_url('role/edit_role/'.$row->roleid); ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Ubah Role</a>
                       <a href="<?php echo site_url('role/edit_auth/'.$row->roleid); ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Ubah Otorisasi</a>
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