<?php $this->load->view('header'); ?>



        <!-- page content -->

        <div class="right_col" role="main">

          <div class="">

            <div class="page-title">

              <div class="title_left">

                <h3>Project</h3>

              </div>

            </div>

            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">

                <div class="x_panel">

                  <div class="x_title">

                    <h2>List Perusahaan<small></small></h2>

                     <ul class="nav navbar-right panel_toolbox">

                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>

                      </li>

                      <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>

                        <ul class="dropdown-menu" role="menu">

                          <li><a href="<?php echo site_url('proj/input_pt'); ?>"><i class="fa fa-plus-circle"></i> Tambah perusahaan</a>

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
                          <th>ID Perusahaan</th>
                          <th>Nama</th>
                          <th>Pilihan</th>
                        </tr>

                      </thead>

                      <tbody>

                      <?php foreach( $list_pt as $row)

								{

					  ?>

                      <tr>

                      <td><?php echo $row->ptid; ?></td>
                      <td><?php echo $row->nampt; ?></td>

                      <td>

                       <a href="<?php echo site_url('proj/edit_pt/'.$row->ptid); ?>" class="btn btn-primary"><i class="fa fa-edit"></i> Ubah</a>

                      

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