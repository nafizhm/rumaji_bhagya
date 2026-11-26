<?php $this->load->view('header'); ?>
    <style type="text/css">
					  /*
* Callouts
*
* Not quite alerts, but custom and helpful notes for folks reading the docs.
* Requires a base and modifier class.
*/
/* Common styles for all types */
.bs-callout {
  margin: 20px 0;
  padding: 20px;
  border-left: 3px solid #eee;
}
.bs-callout h4 {
  margin-top: 0;
  margin-bottom: 5px;
}
.bs-callout p:last-child {
  margin-bottom: 0;
}
.bs-callout code {
  background-color: #fff;
  border-radius: 3px;
}
/* Variations */
.bs-callout-danger {
  background-color: #fdf7f7;
  border-color: #d9534f;
}
.bs-callout-danger h4 {
  color: #d9534f;
}
.bs-callout-warning {
  background-color: #fcf8f2;
  border-color: #f0ad4e;
}
.bs-callout-warning h4 {
  color: #f0ad4e;
}
.bs-callout-info {
  background-color: #f4f8fa;
  border-color: #5bc0de;
}
.bs-callout-info h4 {
  color: #5bc0de;
}
 </style>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>SPR</h3>
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
                    <form id="form1" class="form-horizontal form-label-left" method="post" action="<?php echo $form_action; ?>">
                      <?php
					  foreach($stok_kav as $row_stok);
					  ?>
                      	 <div class="form-group">
                        <table width="100%" border="0" class="table table-striped table-bordered">
                        <tr>
                            <td colspan="6" align="center"><strong>Stok Kavling Yang Dipesan</strong></td>
                          </tr>
                          <tr>
                            <td width="83"><strong>Project / Blok / No </strong></td>
                            <td width="30" align="center"><strong>:</strong></td>
                            <td width="194"><?php echo $row_stok->namproj; ?> / <?php echo $row_stok->blok; ?> / <?php echo $row_stok->no; ?>
                              <input type="hidden" name="txt_projid" value="<?php echo $row_stok->projid; ?>" />
 <input type="hidden" name="txt_blok" value="<?php echo $row_stok->blok; ?>" /><input type="hidden" name="txt_no" value="<?php echo $row_stok->no; ?>" /></td>
                            <td width="105"><strong>Harga Unit </strong></td>
                            <td width="27" align="center"><strong>:</strong></td>
                            <td width="194"><?php 
                                $global_harga_unit = $this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'H');
                                echo $this->lib->format_idr($this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'H'));
                          	 ?>
                            <input type="hidden" id="global_harga_unit" name="global_harga_unit" value="<?php echo $global_harga_unit; ?>" /> 
                            <input type="hidden" name="txt_hrgstd" value="<?php echo $this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'H'); ?>" />
                            <input type="hidden" name="txt_byttp" value="<?php echo $this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'B'); ?>" /></td>
                          </tr>
                          <tr>
                            <td><strong>Lot</strong></td>
                            <td align="center"><strong>:</strong></td>
                            <td><?php echo $row_stok->lot; ?></td>
                            <td><strong>Fitur</strong></td>
                            <td align="center"><strong>:</strong></td>
                            <td><?php
					  			foreach($stok_kavf as $row_fit)
								{
					  				if($row_fit->ftid=='TLE')
									{
					   			?>
                              <span class="label label-success "><?php echo $row_fit->namfit;  ?>:<?php echo $row_fit->ltnh;  ?></span>
                              <?php
                                     }
                                    else
                                    {
                                    ?>
                              <span class="label label-success "><?php echo $row_fit->namfit;  ?></span>
                            <?php
                                }
                                }
                                ?></td>
                          </tr>
                          <tr>
                            <td><strong>Tipe</strong></td>
                            <td align="center"><strong>:</strong></td>
                            <td><?php echo $row_stok->tipe; ?></td>
                            <td width="105"><strong>Harga Fitur </strong></td>
                            <td width="27" align="center"><strong>:</strong></td>
                            <td width="194"><?php 
							$tot_hrgf = 0;
							foreach($stok_kavf as $row_fit)
								{
								?>
                              <?php echo $row_fit->namfit;  ?> :
                              <?php 
								if($row_fit->ftid=='TLE')
									{
										$tot_hrgf = $tot_hrgf + ($this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'H')*$row_fit->ltnh);
								echo $this->lib->format_idr($this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'H')*$row_fit->ltnh);
									}
								else
									{
										$tot_hrgf = $tot_hrgf + $this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'H');
								echo $this->lib->format_idr($this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'H'));
								?>
                              <br />
                            <?php
									}
								}
							  ?>
                
                <input type="hidden" id="global_harga_fitur" name="global_harga_fitur" value="<?php echo $tot_hrgf; ?>" /> 
              
              </td>
                          </tr>
                          <tr>
                            <td><strong>Luas Tanah</strong></td>
                            <td align="center"><strong>:</strong></td>
                            <td><?php echo number_format($row_stok->ltstd); ?> M<sup>2</sup></td>
                            <td><strong>Harga Total</strong></td>
                            <td width="27" align="center"><strong>:</strong></td>
                            <td><?php echo $this->lib->format_idr($this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'H')+$tot_hrgf );
	 ?></td>
                          </tr>
                          <tr>
   <td></td>
   <td></td>
   <td></td>
   <td><strong>Biaya Biaya Titipan</strong></td>
   <td width="27" align="center"><strong>:</strong></td>
   <td><?php 
   $global_biaya_titipan = $this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'B');
   
   echo $this->lib->format_idr($global_biaya_titipan); ?></td>
   <input type="hidden" id="global_biaya_titipan" name="global_biaya_titipan" value="<?php echo $global_biaya_titipan; ?>" /> 

</tr>
                        </table>
                   	  </div>
                      

                       <div class="form-group">
                       <table width="100%" border="0" class="table table-striped table-bordered">
                        <tr>
                          <td width="130"><strong>Salesman</strong></td>
                          <td width="27" align="center"><strong>:</strong></td>
                          <td>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                           <select id="cb_slsman" name="cb_slsman"  class="form-control col-md-7 col-xs-12" required="required">
                           <option value="">Pilih....</option>
                           <?php if (isset($booking_source) && $booking_source->marketing==='Non Marketing'): ?><option value="0">Non Marketing</option><?php endif; ?>
                            <?php
                              $query = $this->db->query("SELECT kodeslsman,namaslsman FROM msalesman order by namaslsman");
                              foreach ($query->result() as $row) {
                                echo '<option value="' . htmlspecialchars($row->kodeslsman) . '">';
                                echo htmlspecialchars($row->kodeslsman) . ' - ' . htmlspecialchars($row->namaslsman) . '</option>';
                              }
      
                           ?>

                           </select>
                           </div>
                          </td>
                        </tr>
                        </table>
                       </div>
                  
                      <div class="bs-callout bs-callout-danger hidden">
                      <h4>Data Error!</h4>
                      <p>Silahkan lengkapi/perbaiki form</p>
                    </div>
                    
                      <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Konsumen</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">SPR Standard</a>
                        </li>
                        <?php
						if(!empty($stok_kavf))
						{
						?>
                        <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">SPR Fitur</a>
                        </li>
                        <?php
						}
						?>
                          <?php
						if(!empty($this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'B')))
						{
						?>
                        <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Biaya Biaya Titipan</a>
                        </li>
                        <?php
						}
						?>
                      </ul>
                      <div id="myTabContent" class="tab-content">
                      	<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                         <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_nama" id="txt_nama" class="form-control col-md-7 col-xs-12" required="required" maxlength="100" />
                           </div>
                         </div>
                         
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">No. KTP
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_ktp" id="txt_ktp" class="form-control col-md-7 col-xs-12" required="required" maxlength="20" />
                           </div>
                         </div>
                         
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">No. NPWP
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_npwp" id="txt_npwp" class="form-control col-md-7 col-xs-12" maxlength="20" />
                           </div>
                         </div>
                         
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea name="txt_alm" id="txt_alm" cols="2" rows="5" class="form-control col-md-7 col-xs-12" required="required"></textarea>
                           </div>
                         </div>
                         
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Telp
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_telp" id="txt_telp" class="form-control col-md-7 col-xs-12" required="required" maxlength="15" />
                           </div>
                         </div>
                         
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Telp 2
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_telp2" id="txt_telp2" class="form-control col-md-7 col-xs-12" maxlength="15" />
                           </div>
                         </div>
                         
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Email
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_email" type="email" id="txt_email" class="form-control col-md-7 col-xs-12" data-parsley-type="email" maxlength="100" />
                           </div>
                         </div>
                         
                         
<!-- BAMS 11-03-2025 START -->
<div class="form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis</label>
    <div class="col-md-6 col-sm-6 col-xs-12">
        
        <div class="radio">
            <label>
                <input type="radio" name="rd_jenis" value="Subsidi" <?php echo set_radio('rd_jenis', 'Subsidi', isset($row_stok->jenis) ? ($row_stok->jenis == 'Subsidi') : TRUE); ?> /> Subsidi
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="rd_jenis" value="Komersial" <?php echo set_radio('rd_jenis', 'Komersial', isset($row_stok->jenis) ? ($row_stok->jenis == 'Komersial') : FALSE); ?> /> Komersial
            </label>
        </div>
    </div>
</div>
<!-- BAMS 11-03-2025 END -->


                         
                        </div>
                        
                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                        
                        <div class="form-group" style="display:none;">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ada PPN ?
                        </label>
                        <div class="checkbox">
                            <label>
                              <input type="checkbox" name="chk_ppn" id="chk_ppn" class="js-switch" value="X">
                            </label>
                          </div>
                      </div>
                      
                           <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tgl Transaksi
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_tgl_trans" id="txt_tgl_trans" class="form-control col-md-7 col-xs-12" required="required">
                        </div>
                      </div>
                      
                            <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Uang Tanda Jadi
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_bkf" id="txt_bkf" class="form-control col-md-7 col-xs-12" required="required" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true"  />
                           </div>
                         </div>
                         
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Cara bayar Uang Muka
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                           <select id="cb_byr" name="cb_byr"  class="form-control col-md-7 col-xs-12" required="required">
                           <option value="">Pilih....</option>
                           <option value="T">TUNAI</option>
                           <option value="C">CICILAN</option>
                           </select>
                           </div>
                         </div>
                         
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Uang Muka
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_dp" id="txt_dp" 
                            value="
                            <?php 
                               if ( $row_stok->jenis == 'Komersial' ) {
                                    $global_harga_unit_ppn = $global_harga_unit * 111/100;
                                } else {
                                    $global_harga_unit_ppn = $global_harga_unit * 5/100;
                                }
                               echo $this->lib->format_idr($global_harga_unit_ppn);                                                         
                            ?>"
                            class="form-control col-md-7 col-xs-12" required="required" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true"  />
                            <input type="hidden" id="global_harga_unit_ppn" name="global_harga_unit_ppn" value="<?php echo $global_harga_unit_ppn; ?>" /> 
                           </div>
                         </div>
                         
                            <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Lama Cicilan UM (X)
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_dp_ccl" id="txt_dp_ccl" class="form-control col-md-7 col-xs-12" required="required" type="number" data-parsley-maxccl= "" />
                           </div>
                         </div>
 
  <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Pelunasan Cicilan
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_dp_cclns" required="required" class="form-control col-md-7 col-xs-12" id="txt_dp_cclns" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true"  />
                           </div>
                         </div>
                                                 
                         <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tgl Jatuh Tempo UM
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_tgl_jtdp" id="txt_tgl_jtdp" class="form-control col-md-7 col-xs-12" required="required" data-parsley-jtempo = "">
                        </div>
                      </div>
                      
                           <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Lama Cicilan KPR (Thn)
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_kpr_ccl" id="txt_kpr_ccl" class="form-control col-md-7 col-xs-12" required="required" type="number" />
                           </div>
                         </div>
                         
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Diskon</label>
                          
                             <div class="col-md-6 col-sm-6 col-xs-12">
                           <select id="cb_dis" name="cb_dis"  class="form-control col-md-7 col-xs-12">
                           <option value="">Pilih....</option>
                           <option value="V">Dalam Rp</option>
                           <option value="P">Dalam Persen (%)</option>
                           </select>
                            <input name="txt_dis" id="txt_dis" class="form-control col-md-7 col-xs-12"  />
                            </div>
                           </div>
                           
                           <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Disc UM ( BUM pemerintah )</label>
                          
                             <div class="col-md-6 col-sm-6 col-xs-12">
                           <select id="cb_disum" name="cb_disum"  class="form-control col-md-7 col-xs-12">
                           <option value="">Pilih....</option>
                           <option value="V">Dalam Rp</option>
                           <option value="P">Dalam Persen (%)</option>
                           </select>
                            <input name="txt_disum" id="txt_disum" class="form-control col-md-7 col-xs-12"  />
                            </div>
                           </div>
                          
                           
                        
                         
                        </div>
                       
                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                          <?php
						if(!empty($stok_kavf))
						{
						?>
                           <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Cara bayar Uang Fitur
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                           <select id="cb_byrf" name="cb_byrf"  class="form-control col-md-7 col-xs-12" required="required">
                           <option value="">Pilih....</option>
                           <option value="T">TUNAI</option>
                           <option value="C">CICILAN</option>
                           </select>
                           </div>
                         </div>
                         
                           <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Lama Cicilan (X)
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_cclf" id="txt_cclf" class="form-control col-md-7 col-xs-12" required="required" type="number" data-parsley-maxcclf= "" />
                           </div>
                         </div>
                         
                           <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Pelunasan Cicilan
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="txt_dp_cclnf" name="txt_dp_cclnf" required="required" class="form-control col-md-7 col-xs-12" id="txt_dp_cclnf" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true"  />
                           </div>
                         </div>
                         
                          <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tgl Jatuh Tempo
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_tgl_jtf" id="txt_tgl_jtf" class="form-control col-md-7 col-xs-12" required="required" data-parsley-jtempof = "">
                        </div>
                      </div>
                      
                       <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Diskon</label>
                             <div class="col-md-6 col-sm-6 col-xs-12">
                           <select id="cb_disf" name="cb_disf"  class="form-control col-md-7 col-xs-12">
                           <option value="">Pilih....</option>
                           <option value="V">Dalam Rp</option>
                           <option value="P">Dalam Persen (%)</option>
                           </select>
                            <input name="txt_disf" id="txt_disf" class="form-control col-md-7 col-xs-12"  />
                            </div>
                           </div>
                             <?php
							}
						?>
                        </div>
                        <?php if (!empty($this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'B')))
						{
						?>
                         <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Cara bayar Biaya Biaya Titipan
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                           <select id="cb_byrb" name="cb_byrb"  class="form-control col-md-7 col-xs-12" required="required">
                           <option value="">Pilih....</option>
                           <option value="T">TUNAI</option>
                           <option value="C">CICILAN</option>
                           </select>
                           </div>
                         </div>
                         
                           <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Lama Cicilan (X)
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input name="txt_cclb" id="txt_cclb" class="form-control col-md-7 col-xs-12" required="required" type="number" data-parsley-maxcclb= "" />
                           </div>
                         </div>
                         
                           <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Pelunasan Cicilan
                       	  </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="txt_dp_cclnb" name="txt_dp_cclnb" required="required" class="form-control col-md-7 col-xs-12" id="txt_dp_cclnb" data-inputmask="'alias': 'currency', 'prefix' : '', 'suffix': '','radixPoint': ',', 'groupSeparator': '.', 'autoGroup': true"  />
                           </div>
                         </div>
                         
                          <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tgl Jatuh Tempo
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input name="txt_tgl_jtb" id="txt_tgl_jtb" class="form-control col-md-7 col-xs-12" required="required" data-parsley-jtempob = "">
                        </div>
                      </div>
                      
                       <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Diskon</label>
                             <div class="col-md-6 col-sm-6 col-xs-12">
                           <select id="cb_disb" name="cb_disb"  class="form-control col-md-7 col-xs-12">
                           <option value="">Pilih....</option>
                           <option value="V">Dalam Rp</option>
                           <option value="P">Dalam Persen (%)</option>
                           </select>
                            <input name="txt_disb" id="txt_disb" class="form-control col-md-7 col-xs-12"  />
                            </div>
                           </div>
                         </div>
                        <?php } ?>
                      </div>
                     
                      
                      </div>
                      
                   
                     <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <a href ="<?php echo $url_back; ?>"  class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i> kembali</a>
                           <button type="button" id="btn_periksa" class="btn btn-info"><i class="fa fa-check"></i> Verifikasi SPR</button>
                          
                        </div>
                      </div>
  
    <!-- Modal -->
  <div class="modal fade bs-example-modal-lg" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Verifikasi Surat Pesanan Rumah</h4>
        </div>
        <div class="modal-body">
         <div id="prev"></div>
        </div>
        <div class="modal-footer">
         <button type="submit" id="btn_simpan" class="btn btn-success"><i class="fa fa-save"></i> Buat SPR</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
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
$(function () {
  /*$('#form1').parsley().on('field:validated', function() {
    var ok = $('.parsley-error').length === 0;
   // $('.bs-callout-info').toggleClass('hidden', !ok);
    $('.bs-callout-danger').toggleClass('hidden', ok);
  })*/
  
  if($( "#cb_dis" ).val()=='')
	   {
		   $( "#txt_dis" ).prop( "readonly", 'readonly' );
		   $( "#txt_dis" ).prop( "required", "" );
		   
	   }
	   
 if($( "#cb_disum" ).val()=='')
	   {
		   $( "#txt_disum" ).prop( "readonly", 'readonly' );
		   $( "#txt_disum" ).prop( "required", "" );
		   
	   }

 window.Parsley.addValidator('maxccl', {
  validateString: function(value) {
	  //alert(value.substring(0, 2));
   return value <= 10;
  },
  messages: {
    en: 'Cicilan tidak boleh lebih dari 10 kali'
  }
});
	   
 window.Parsley.addValidator('jtempo', {
  validateString: function(value) {
	  //alert(value.substring(0, 2));
    return value.substring(0, 2) === '14' || value.substring(0, 2) === '28';
  },
  messages: {
    en: 'Jatuh tempo harus  tanggal 14 atau 28'
  }
});
	   
  var $form = $('#form1');
  $('#btn_periksa').click (function () {
	  
	 

	  $form.parsley().validate();
	  
	   if ($form.parsley().isValid()) {
		   	
			  $('.bs-callout-danger').toggleClass('hidden', true);
			  
			$.ajax({
            type: "POST",
            url: "<?php echo site_url('spr/check_spr'); ?>",
            data: $('#form1').serialize()+"&moredata=" + 'xxx',
            cache: false,
            beforeSend: function () { 
                $('#prev').html('Loading....');
            },
            success: function(html) {    
                $("#prev").html( html );
            },
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
			 $('#prev').html(errorThrown);
    			}       
        });
		
	   		$("#myModal").modal();
	   
	   		}
			else
			{
				  $('.bs-callout-danger').toggleClass('hidden', false);
			}
	   });
 /*.on('form:submit', function() {
	 
	
    return true; // Don't submit form for this demo
  });*/
  
  $( "#cb_byr" ).change(function() {
	  if($( "#cb_byr" ).val()=='T')
	  {
		  /* $( "#txt_dp" ).prop( "readonly", 'readonly' );
		   $( "#txt_dp" ).prop( "required", "" );*/
			 
		   $( "#txt_dp_ccl" ).prop( "readonly", 'readonly' );
		   $( "#txt_dp_ccl" ).prop( "required", "" );
		   $( "#txt_dp_ccl" ).val('');
		   
		   $( "#txt_dp_cclns" ).prop( "readonly", 'readonly' );
		   $( "#txt_dp_cclns" ).prop( "required", "" );
		   $( "#txt_dp_cclns" ).val('');
		   //$( "#txt_tgl_jtdp" ).prop("disabled", 'disabled');
		   //$( "#txt_tgl_jtdp" ).val('');
		   //$( "#txt_tgl_jtdp" ).prop( "required", "" );
		  
		 
	  }
	  else
	  {
		/*  $( "#txt_dp" ).prop( "readonly", '' );
		  $( "#txt_dp" ).prop( "required", "required" );
		   */
		  $( "#txt_dp_ccl" ).prop( "readonly", '' );
		  $( "#txt_dp_ccl" ).prop( "required", "required" );
		  
		  $( "#txt_dp_cclns" ).prop( "readonly", '' );
		  $( "#txt_dp_cclns" ).prop( "required", "required" );
		  
		 /* $( "#txt_tgl_jtdp" ).prop( "disabled", '' );
		  $( "#txt_tgl_jtdp" ).prop( "required", "required" );*/
	  }
  });
  
   $( "#cb_dis" ).change(function() {
	   
	   if($( "#cb_dis" ).val()=='')
	   {
		   $( "#txt_dis" ).prop( "readonly", 'readonly' );
		   $( "#txt_dis" ).prop( "required", "" );
		   $( "#txt_dis" ).val('');
		   
	   }
	    else if($( "#cb_dis" ).val()=='V')
	  	{
		   $( "#txt_dis" ).prop( "readonly", '' );
		   $( "#txt_dis" ).prop( "required", "required" );
		   
		    $("#txt_dis").inputmask({
			'alias': 'currency',
			rightAlign: true,
			'prefix' : '',
			'suffix': '',
			'radixPoint': ',',
			'groupSeparator': '.',
			'autoGroup': true
		  	});
	  	}
		else
		{
			$( "#txt_dis" ).prop( "readonly", '' );
		   $( "#txt_dis" ).prop( "required", "required" );
		   
			 $("#txt_dis").inputmask({
			'alias': 'decimal',
			rightAlign: true,
			'groupSeparator': '.',
			'autoGroup': true
		  	});
		}
	 });   
	 
	 
	  $( "#cb_disum" ).change(function() {
	   
	   if($( "#cb_disum" ).val()=='')
	   {
		   $( "#txt_disum" ).prop( "readonly", 'readonly' );
		   $( "#txt_disum" ).prop( "required", "" );
		   $( "#txt_disum" ).val('');
		   
	   }
	    else if($( "#cb_disum" ).val()=='V')
	  	{
		   $( "#txt_disum" ).prop( "readonly", '' );
		   $( "#txt_disum" ).prop( "required", "required" );
		   
		    $("#txt_disum").inputmask({
			'alias': 'currency',
			rightAlign: true,
			'prefix' : '',
			'suffix': '',
			'radixPoint': ',',
			'groupSeparator': '.',
			'autoGroup': true
		  	});
	  	}
		else
		{
			$( "#txt_disum" ).prop( "readonly", '' );
		   $( "#txt_disum" ).prop( "required", "required" );
		   
			 $("#txt_disum").inputmask({
			'alias': 'decimal',
			rightAlign: true,
			'groupSeparator': '.',
			'autoGroup': true
		  	});
		}
	 });   
	 
	 
	 
	   
  
});
</script>
<script type="text/javascript">
$(function() {
	
	 $('input[name="txt_tgl_trans"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
		autoUpdateInput: false/*,
		 locale: {
            format: 'DD/MM/YYYY'
        }*/
    });
	
	$('input[name="txt_tgl_trans"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
  
    $('input[name="txt_tgl_jtdp"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
		autoUpdateInput: false/*,
		 locale: {
            format: 'DD/MM/YYYY'
        }*/
    });
	
	$('input[name="txt_tgl_jtdp"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
  
  window.Parsley.addValidator('maxcclf', {
  validateString: function(value) {
	  //alert(value.substring(0, 2));
   return value <= 10;
  },
  messages: {
    en: 'Cicilan tidak boleh lebih dari 10 kali'
  }
});

  $('input[name="txt_tgl_jtf"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
		autoUpdateInput: false/*,
		 locale: {
            format: 'DD/MM/YYYY'
        }*/
    });
	
	$('input[name="txt_tgl_jtf"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
  
   window.Parsley.addValidator('maxcclb', {
  validateString: function(value) {
	  //alert(value.substring(0, 2));
   return value <= 10;
  },
  messages: {
    en: 'Cicilan tidak boleh lebih dari 10 kali'
  }
});

   $('input[name="txt_tgl_jtb"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
		autoUpdateInput: false/*,
		 locale: {
            format: 'DD/MM/YYYY'
        }*/
    });
	
	$('input[name="txt_tgl_jtb"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('DD/MM/YYYY'));
  });
  
	
});
</script>
 <?php
if(!empty($stok_kavf))
{
?>
<script type="text/javascript">

window.Parsley.addValidator('jtempof', {
  validateString: function(value) {
	  //alert(value.substring(0, 2));
    return value.substring(0, 2) === '14' || value.substring(0, 2) === '28';
  },
  messages: {
    en: 'Jatuh tempo harus  tanggal 14 atau 28'
  }
});

if($( "#cb_disf" ).val()=='')
	   {
		   $( "#txt_disf" ).prop( "readonly", 'readonly' );
		   $( "#txt_disf" ).prop( "required", "" );
		   
	   }
	   
$( "#cb_byrf" ).change(function() {
	  if($( "#cb_byrf" ).val()=='T')
	  {
		  $( "#txt_cclf" ).prop( "readonly", 'readonly' );
		  $( "#txt_cclf" ).prop( "required", "" );
		  $( "#txt_cclf" ).val('');
		  
		   $( "#txt_dp_cclnf" ).prop( "readonly", 'readonly' );
		   $( "#txt_dp_cclnf" ).prop( "required", "" );
		   $( "#txt_dp_cclnf" ).val('');
	  }
	  else
	  {
		  $( "#txt_dp_cclnf" ).prop( "readonly", '' );
		  $( "#txt_dp_cclnf" ).prop( "required", "required" );
		   
		  $( "#txt_cclf" ).prop( "readonly", '' );
		  $( "#txt_cclf" ).prop( "required", "required" );
	  }
});

 $( "#cb_disf" ).change(function() {
	   
	   if($( "#cb_disf" ).val()=='')
	   {
		   $( "#txt_disf" ).prop( "readonly", 'readonly' );
		   $( "#txt_disf" ).prop( "required", "" );
		   $( "#txt_disf" ).val('');
		   
	   }
	    else if($( "#cb_disf" ).val()=='V')
	  	{
		   $( "#txt_disf" ).prop( "readonly", '' );
		   $( "#txt_disf" ).prop( "required", "required" );
		   
		    $("#txt_disf").inputmask({
			'alias': 'currency',
			rightAlign: true,
			'prefix' : '',
			'suffix': '',
			'radixPoint': ',',
			'groupSeparator': '.',
			'autoGroup': true
		  	});
	  	}
		else
		{
			$( "#txt_disf" ).prop( "readonly", '' );
		   $( "#txt_disf" ).prop( "required", "required" );
		   
			 $("#txt_disf").inputmask({
			'alias': 'decimal',
			rightAlign: true,
			'groupSeparator': '.',
			'autoGroup': true
		  	});
		}
	 });   
	 
</script>
<?php
}
?>
 <?php
if (!empty($this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'B')))
{
?>
<script type="text/javascript">

window.Parsley.addValidator('jtempob', {
  validateString: function(value) {
	  //alert(value.substring(0, 2));
    return value.substring(0, 2) === '14' || value.substring(0, 2) === '28';
  },
  messages: {
    en: 'Jatuh tempo harus  tanggal 14 atau 28'
  }
});

if($( "#cb_disb" ).val()=='')
	   {
		   $( "#txt_disb" ).prop( "readonly", 'readonly' );
		   $( "#txt_disb" ).prop( "required", "" );
		   
	   }
	   
$( "#cb_byrb" ).change(function() {
	  if($( "#cb_byrb" ).val()=='T')
	  {
		  $( "#txt_cclb" ).prop( "readonly", 'readonly' );
		  $( "#txt_cclb" ).prop( "required", "" );
		  $( "#txt_cclb" ).val('');
		  
		   $( "#txt_dp_cclnb" ).prop( "readonly", 'readonly' );
		   $( "#txt_dp_cclnb" ).prop( "required", "" );
		   $( "#txt_dp_cclnb" ).val('');
	  }
	  else
	  {
		  $( "#txt_dp_cclnb" ).prop( "readonly", '' );
		  $( "#txt_dp_cclnb" ).prop( "required", "required" );
		   
		  $( "#txt_cclb" ).prop( "readonly", '' );
		  $( "#txt_cclb" ).prop( "required", "required" );
	  }
});

 $( "#cb_disb" ).change(function() {
	   
	   if($( "#cb_disb" ).val()=='')
	   {
		   $( "#txt_disb" ).prop( "readonly", 'readonly' );
		   $( "#txt_disb" ).prop( "required", "" );
		   $( "#txt_disb" ).val('');
		   
	   }
	    else if($( "#cb_disb" ).val()=='V')
	  	{
		   $( "#txt_disb" ).prop( "readonly", '' );
		   $( "#txt_disb" ).prop( "required", "required" );
		   
		    $("#txt_disb").inputmask({
			'alias': 'currency',
			rightAlign: true,
			'prefix' : '',
			'suffix': '',
			'radixPoint': ',',
			'groupSeparator': '.',
			'autoGroup': true
		  	});
	  	}
		else
		{
			$( "#txt_disb" ).prop( "readonly", '' );
		   $( "#txt_disb" ).prop( "required", "required" );
		   
			 $("#txt_disb").inputmask({
			'alias': 'decimal',
			rightAlign: true,
			'groupSeparator': '.',
			'autoGroup': true
		  	});
		}
	 });   
	 
</script>



<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
function formatRibuan(nilai) {
    return nilai.toLocaleString('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function parseRibuanToDecimal(nilaiStr) {
    if (typeof nilaiStr !== 'string') return '0';

    const str = nilaiStr.trim();
    const adaKoma = str.includes(',');
    const adaTitik = str.includes('.');

    let cleanStr = str;
    let format = 'US'; // Default: titik desimal

    if (adaKoma && adaTitik) {
        // Contoh: 1.234,56 → gaya Indonesia
        cleanStr = str.replace(/\./g, '').replace(',', '.');
        format = 'ID';
    } else if (adaKoma && !adaTitik) {
        // Contoh: 1234,56 → gaya Indonesia
        cleanStr = str.replace(',', '.');
        format = 'ID';
    } else if (!adaKoma && adaTitik) {
        const parts = str.split('.');
        if (parts.length === 2 && parts[1].length > 0) {
            // Contoh: 1234.56 → gaya US
            format = 'US';
            cleanStr = str;
        } else {
            // Contoh: 1.234.567 → hanya ribuan
            cleanStr = str.replace(/\./g, '');
        }
    } else {
        // Tidak ada pemisah apa pun
        cleanStr = str;
    }

    const parsed = parseFloat(cleanStr);
    if (isNaN(parsed)) return '0';

    // Cek apakah ada desimal non-nol
    const isDesimal = !Number.isInteger(parsed);

    if (format === 'ID') {
        return isDesimal ? parsed.toString().replace('.', ',') : parsed.toString().split('.')[0];
    } else {
        return isDesimal ? parsed.toString() : parsed.toString().split('.')[0];
    }
}

function bulatkanKeRibuanAtas(angka) {
  return Math.ceil(angka / 1000) * 1000;
}

function hitungDanTampilkan() {
    var global_harga_unit = parseFloat($('#global_harga_unit').val()) || 0;
    var jenis = $('input[name="rd_jenis"]:checked').val();
    var hasil = (jenis === 'Komersial') ? global_harga_unit * 111/100 : global_harga_unit * 5/100;
    var tandajadi = parseFloat(parseRibuanToDecimal($('#txt_bkf').val())) || 0 ;
    var disc = parseFloat(parseRibuanToDecimal($('#txt_disum').val())) || 0 ;
    var hasilakhir = hasil - tandajadi - disc  ;
    // $('#txt_dp').val(formatRibuan(hasil));
    $('#txt_dp').val(hasil);

    var cicilan = parseFloat($('#txt_dp_ccl').val()) || 0;
    var nilaicicilan = cicilan <= 0 ? 0 : hasilakhir / cicilan;
    $('#txt_dp_cclns').val(formatRibuan(bulatkanKeRibuanAtas(nilaicicilan)));
}

$(document).ready(function() {
    $('input[name="rd_jenis"]').on('change', hitungDanTampilkan);
    // $('#txt_dp').on('input', hitungDanTampilkan);
});

$('#txt_bkf').on('blur change input', function () {
    hitungDanTampilkan();
});

function hitungDanTampilkan2() {
    var hasil = parseFloat(parseRibuanToDecimal($('#txt_dp').val())) || 0 ;
    var tandajadi = parseFloat(parseRibuanToDecimal($('#txt_bkf').val())) || 0 ;
    var disc = parseFloat(parseRibuanToDecimal($('#txt_disum').val())) || 0 ;
    var hasilakhir = hasil - tandajadi - disc  ;
    var cicilan = parseFloat($('#txt_dp_ccl').val()) || 0;
    var nilaicicilan = cicilan <= 0 ? 0 : hasilakhir / cicilan;
    $('#txt_dp_cclns').val(formatRibuan(bulatkanKeRibuanAtas(nilaicicilan)));
}

$('#txt_dp').on('blur change input', function () {
    hitungDanTampilkan2();
});

$('#txt_dp_ccl').on('blur change input', function () {
    hitungDanTampilkan2();
});

$('#txt_disum').on('blur change input', function () {
    hitungDanTampilkan2();
});

function hitungfitur() {
    var hasil = parseFloat($('#global_harga_fitur').val()) || 0 ;
    var disc = parseFloat(parseRibuanToDecimal($('#txt_disf').val())) || 0 ;
    var hasilakhir = hasil - disc  ;
    var cicilan = parseFloat($('#txt_cclf').val()) || 0;
    var nilaicicilan = cicilan <= 0 ? 0 : hasilakhir / cicilan;
    $('#txt_dp_cclnf').val(formatRibuan(bulatkanKeRibuanAtas(nilaicicilan)));
}
function hitungtitipan() {
    var hasil = parseFloat($('#global_biaya_titipan').val()) || 0 ;
    var disc = parseFloat(parseRibuanToDecimal($('#txt_disb').val())) || 0 ;
    var hasilakhir = hasil - disc  ;   
    var cicilan = parseFloat($('#txt_cclb').val()) || 0;
    var nilaicicilan = cicilan <= 0 ? 0 : hasilakhir / cicilan;
    $('#txt_dp_cclnb').val(formatRibuan(bulatkanKeRibuanAtas(nilaicicilan)));
}

$('#txt_cclf').on('blur change input', function () {
    hitungfitur();
});
$('#txt_disf').on('blur change input', function () {
    hitungfitur();
});

$('#txt_cclb').on('blur change input', function () {
    hitungtitipan();
});
$('#txt_disb').on('blur change input', function () {
    hitungtitipan();
});


</script>



<?php
}
?>
<?php if (isset($booking_source)):
$booking_payload=json_decode($booking_source->data_pemohon,TRUE);
$booking_values=array('txt_nama'=>$booking_source->nama_lengkap,'txt_ktp'=>$booking_source->nik,'txt_telp'=>$booking_source->no_telp,'txt_bkf'=>number_format($booking_source->booking_fee,0,',','.'),'txt_tgl_trans'=>date('d/m/Y',strtotime($booking_source->tanggal)));
foreach (array('txt_npwp'=>'npwp','txt_alm'=>'alamat_ktp','txt_email'=>'email','txt_telp2'=>'kontak_darurat_1') as $target=>$source) $booking_values[$target]=isset($booking_payload[$source])?$booking_payload[$source]:'';
?>
<script>
jQuery(function($){
 var values=<?php echo json_encode($booking_values,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>;
 Object.keys(values).forEach(function(name){$('#'+name).val(values[name]).trigger('input');});
 $('<input>').attr({type:'hidden',name:'booking_id',value:<?php echo (int)$booking_source->id; ?>}).appendTo('#form1');
 var marketing=<?php echo json_encode($booking_source->marketing,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>;
 $('#cb_slsman option').each(function(){var label=$(this).text().trim();if(label===marketing || label.slice(-(marketing.length+3))===' - '+marketing){$('#cb_slsman').val(this.value).trigger('change');}});
 $('input[name="rd_jenis"]').val([<?php echo json_encode($booking_source->jenis_perumahan==='Komersil'?'Komersial':'Subsidi'); ?>]).trigger('change');
});
</script>
<?php endif; ?>
