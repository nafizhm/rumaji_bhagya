<?php $this->load->view('header'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>List SPR</h3>
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
                          <li><a href="<?php echo site_url('spr/input_spr'); ?>"><i class="fa fa-plus-circle"></i> Tambah SPR</a>
                          <li><a href="<?php echo site_url('spr'); ?>"><i class="fa fa-search"></i> Pilih project</a>
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
                    <div class="row" style="margin-bottom:20px;">
                      <div class="col-md-7">
                        <form method="get" action="<?php echo html_escape($list_url); ?>" class="form-inline">
                          <label for="spr-status">Status SPR</label>
                          <select id="spr-status" name="status" class="form-control">
                            <option value="">Semua status</option>
                            <option value="proses" <?php echo $status_filter === 'proses' ? 'selected' : ''; ?>>Dalam Proses</option>
                            <option value="batal" <?php echo $status_filter === 'batal' ? 'selected' : ''; ?>>Batal</option>
                          </select>
                          <label for="spr-per-page">Per halaman</label>
                          <select id="spr-per-page" name="per_page" class="form-control">
                            <?php foreach (array(25, 50, 100) as $size): ?>
                              <option value="<?php echo $size; ?>" <?php echo $per_page === $size ? 'selected' : ''; ?>><?php echo $size; ?></option>
                            <?php endforeach; ?>
                          </select>
                          <input class="form-control" type="search" name="q" value="<?php echo html_escape($search); ?>" placeholder="No SPR, konsumen, blok, lot" aria-label="Cari SPR">
                          <button class="btn btn-primary" type="submit" style="margin-bottom:0">Tampilkan</button>
                        </form>
                      </div>
                      <div class="col-md-5" role="status" style="padding-top:8px;">
                        <strong>Total: <?php echo number_format($summary->total); ?></strong>
                        &nbsp; | &nbsp; Dalam Proses: <strong><?php echo number_format($summary->proses); ?></strong>
                        &nbsp; | &nbsp; Batal: <strong><?php echo number_format($summary->batal); ?></strong>
                        <?php if ($search !== ''): ?><small>(sesuai pencarian)</small><?php endif; ?>
                      </div>
                    </div>
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No SPR</th>
                          <th>Lot</th>
                          <th>Blok/No</th>
                          <th>Konsumen</th>
                          <th>Status SPR</th>
                          <th>UM</th>
                          <th>DOK</th>
                          <th>KAV</th>
                          <th>Dibuat</th>
                          <th>Tgl Buat</th>
                          <th>Pilihan</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php if (empty($list_spr)): ?>
                        <tr><td colspan="11" class="text-center">Tidak ada SPR yang sesuai dengan filter.</td></tr>
                      <?php endif; ?>
                      <?php foreach( $list_spr as $row)
								{
					  ?>
                      <tr>
                      <td><?php echo $this->lib->no_spr($row->sprno); ?></td>
                      <td><?php echo $row->lot; ?></td>
                      <td><?php echo $row->blok; ?><?php echo $row->no; ?></td>
                      <td><?php echo $row->nama; ?></td>
                       <td>
                       <b>
                       <?php
					  // $query_status = $this->mod_kav->get_kav_spr($row->projid,$row->blok,$row->no);
					//	foreach( $query_status->result()  as $row_status);
							
					//	echo $row_status->namstat;
					if($row->status == 'BTL')
					{
					echo '<font color="#FF0000">'.strtoupper($this->lib->get_status_text('SPR',$row->status)).'</font>';
					}
					else
					{
					echo '<font color="#009900">'.strtoupper($this->lib->get_status_text('SPR',$row->status)).'</font>';	
					}
						?>
                        </b>
                       </td>
                       <td><?php echo $this->lib->disp_prog_um($row->projid,$row->sprno); ?> %</td>
                       <td><?php echo $this->lib->disp_prog_dok($row->projid,$row->sprno); ?> %</td>
                       <td><?php echo $this->lib->disp_prog_kav($row->projid,$row->blok,$row->no); ?> %</td>
                      <td><?php echo $row->crby; ?></td>
                      <td data-order="<?php echo $row->crdat; ?>"><?php echo  $this->lib->disp_date($row->crdat); ?></td>
                    
                      <td>
                       <div class="btn-group btn-group-sm">
                       <a href="<?php echo site_url('spr/edit_spr/'.$row->sprno.'/'.$row->projid); ?>" class="btn btn-primary"><i class="fa fa-search-plus"></i> Detail</a>
                      </div>
                   </td>
                      </tr>
                      <?php
								}
					?>
                      </tbody>
                    </table>
                    </div>
                    <div class="clearfix">
                      <p class="pull-left">Menampilkan <?php echo $total ? $offset + 1 : 0; ?>–<?php echo min($offset + count($list_spr), $total); ?> dari <?php echo number_format($total); ?> data. Halaman <?php echo $page; ?> dari <?php echo $pages; ?>.</p>
                      <nav class="pull-right" aria-label="Halaman SPR"><ul class="pagination" style="margin:0">
                        <?php if ($page > 1): ?>
                          <li><a href="<?php echo html_escape($list_url . '?' . http_build_query(array_merge($page_query, array('page' => $page - 1)))); ?>">Sebelumnya</a></li>
                        <?php endif; ?>
                        <?php for ($p = max(1, $page - 2); $p <= min($pages, $page + 2); $p++): ?>
                          <li class="<?php echo $p === $page ? 'active' : ''; ?>"><a <?php echo $p === $page ? 'aria-current="page"' : ''; ?> href="<?php echo html_escape($list_url . '?' . http_build_query(array_merge($page_query, array('page' => $p)))); ?>"><?php echo $p; ?></a></li>
                        <?php endfor; ?>
                        <?php if ($page < $pages): ?>
                          <li><a href="<?php echo html_escape($list_url . '?' . http_build_query(array_merge($page_query, array('page' => $page + 1)))); ?>">Berikutnya</a></li>
                        <?php endif; ?>
                      </ul></nav>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<?php $this->load->view('footer'); ?>
      
