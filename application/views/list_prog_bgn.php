<?php $this->load->view('header'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Update Progress Bangun</h3>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $title; ?><small></small></h2>
                      <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?php echo site_url('kav/prog_bgn'); ?>"><i class="fa fa-search"></i> Pilih project</a>
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
                    <form id="demo-form2" class="form-horizontal form-label-left" method="post" action="<?php echo $form_action; ?>">
                    <input type="hidden" name="txt_count" id="txt_count" value="<?php echo $list_kav->num_rows(); ?>" />
                      <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Lot</th>
                          <th>Blok/No</th>
                          <th>LT</th>
                          <th>Tipe</th>
                          <th>Fitur</th>
                          <th>Progress Bangun</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach( $list_kav->result() as $row)
								{
					  ?>
                      <tr>
            
                      <td><?php echo $row->lot; ?></td>
                      <td><?php echo $row->blok; ?><?php echo $row->no; ?></td>
                      <td><?php echo $row->ltstd; ?></td>
                       <td><?php echo $row->tipe; ?></td>
                       <td>
                       <?php
					   $query_fit = $this->mod_kav->list_kavf($row->projid,$row->blok,$row->no);
						foreach( $query_fit->result()  as $row_fit)
						{
							if($row_fit->ftid=='TLE')
							{
					   ?>
                       
                       <span class="label label-success "><?php echo $row_fit->namfit;  ?>:<?php echo $row_fit->ltnh;  ?></span>
                       <br />
                       <?php
							}
						
						else
						{
							 ?>
						 <span class="label label-success "><?php echo $row_fit->namfit;  ?></span>
                          <br />
						 <?php
                        }
						}
						?>
                       </td>
                      <td>
                      <input name="txt_projid[]" id="txt_projid[]" type="hidden" value="<?php echo $row->projid; ?>" />
                      <input name="txt_blok[]" id="txt_blok[]" type="hidden" value="<?php echo $row->blok; ?>" />
                       <input name="txt_no[]" id="txt_no[]" type="hidden" value="<?php echo $row->no; ?>" />
                      <input name="txt_pkav_old[]" id="txt_pkav_old[]" type="hidden" value="<?php echo $row->pkav; ?>" />
                      <input name="txt_pkav[]" id="txt_pkav[]" type="text" value="<?php echo $row->pkav; ?>" /> %</td>
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