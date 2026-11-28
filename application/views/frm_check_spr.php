<fieldset>
<legend>
Konsumen
</legend>
<table width="873" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="122"><strong>Nama </strong></td>
    <td width="24" align="center"><strong>:</strong></td>
    <td width="184"><?php echo $nama; ?></td>
    <td width="115"><strong>Alamat</strong></td>
    <td width="18" align="center"><strong>:</strong></td>
    <td width="410"><?php echo $alm; ?></td>
    </tr>
  <tr>
    <td><strong>No. KTP</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $ktp; ?></td>
    <td><strong>No. Telp / Telp2</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $telp; ?> / <?php echo $telp2; ?></td>
    </tr>
  <tr>
    <td><strong>NPWP</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $npwp; ?></td>
    <td><strong>Email</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $email; ?></td>
    </tr>
</table>
</fieldset>
<br />
<fieldset>
<legend>
Kavling Yang Dipesan
</legend>
  <?php
foreach($stok_kav as $row_stok);
	?>
<table width="873" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="74"><strong>Project</strong></td>
    <td width="19" align="center"><strong>:</strong></td>
    <td width="174"><?php echo $row_stok->namproj; ?></td>
    <td width="53"><strong>Lot</strong></td>
    <td width="18" align="center"><strong>:</strong></td>
    <td width="39"><?php echo $row_stok->lot; ?></td>
    <td width="93"><strong>Luas Tanah</strong></td>
    <td width="22" align="center"><strong>:</strong></td>
    <td width="381"><?php echo number_format($row_stok->ltstd); ?> M<sup>2</sup> </td>
    </tr>
  <tr>
    <td><strong>Blok / No </strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_stok->blok; ?> / <?php echo $row_stok->no; ?></td>
    <td><strong>Tipe</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_stok->tipe; ?></td>
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
</table>
</fieldset>
<br />
<fieldset>
<table width="873" border="0" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 120px;">
  <strong>Salesman</strong>
</td>
<td align="center"><strong>:</strong></td>
<td>
<?php echo $salesman;  ?>
</td>
</tr>
</table>
</fieldset>

<br />
<div class="" role="tabpanel" data-example-id="togglable-tabs">
 <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_sprs" id="sprs-tab" role="tab" data-toggle="tab" aria-expanded="true">Data SPR Standard</a>
                        </li>
                         <?php
						if(!empty($stok_kavf))
						{
						?>
                        <li role="presentation" class=""><a href="#tab_sprf" role="tab" id="sprf-tab" data-toggle="tab" aria-expanded="false">Data SPR Fitur</a>
                        </li>
                        <?php } ?>
                         <?php
						  $tot_hrgb = $release_biaya_titipan;
						if(!empty($tot_hrgb))
						{
						?>
                        <li role="presentation" class=""><a href="#tab_sprb" role="tab" id="sprb-tab" data-toggle="tab" aria-expanded="false">Data SPR Biaya Biaya Titipan</a>
                        </li>
                        <?php } ?>
                        </ul>
	<div id="myTabContent" class="tab-content">
      <div role="tabpanel" class="tab-pane fade active in" id="tab_sprs" aria-labelledby="home-tab">      
<fieldset>
<legend>
Harga & Rencana Pembayaran</legend>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="141"><strong>Harga Unit </strong></td>
    <td width="9" align="center"><strong>:</strong></td>
    <td width="293"><?php echo $this->lib->format_idr($this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'H'));?>      <input type="hidden" name="txt_hrg_tgl" value="<?php echo $this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'T'); ?>" /></td>
    <td width="234"><strong>Uang Tanda Jadi</strong></td>
    <td width="8" align="center"><strong>:</strong></td>
    <td width="375"><?php echo $this->lib->format_idr($bkf); ?>
      <input type="hidden" name="txt_utj" value="<?php echo $bkf; ?>" /></td>
    </tr>
  <tr>
    <td><strong>Diskon</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php
	if($cb_dis == 'V')
	{
		$dis_val = $dis;
	 	echo $this->lib->format_idr($dis_val);
	}
	elseif($cb_dis == 'P')
	{
		$dis_val = ($dis / 100) * $this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'H');
		echo  $this->lib->format_idr($dis_val);	
		echo '&nbsp;('.$dis.'%)';
	}
	else
	{
		$dis_val = 0;
		echo $dis_val;
	}?></td>
    <td><strong>Uang Muka</strong></td>
    <td align="center"><strong>:</strong></td>
    <td> <?php
	 echo $this->lib->format_idr($um);?></tr>
  <tr>
    <td><strong>Harga Total</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php 
	$total = $this->lib->get_harga($row_stok->projid,$row_stok->lot,$row_stok->tipe,'H') - $dis_val;

  $total2 = $total;
	// if($chk_ppn == 'X')
  if($jenis == 'Komersial')
	{
		//$ppn = round($total * 0.1);		
    $dppppn = $total * 11/12 ;
		$ppn = round($dppppn * 12/100);	
		$total = $total + $ppn;	
	}
	else
	{
    $dppppn = $total;
		$ppn = 0;
	}
	
	$kpr = $total - $um;
	  ?>
      <?php
	
	 echo $this->lib->format_idr($total2);?>
      <input type="hidden" name="txt_hrgtot" value="<?php echo $total; ?>" />
      <input type="hidden" name="txt_ppn" value="<?php echo $ppn; ?>" />
      </td>
    <td><strong>Disc UM ( BUM pemerintah )</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php
	if($cb_disum == 'V')
	{
		$disum_val = $disum;
	 	echo $this->lib->format_idr($disum_val);
	}
	elseif($cb_disum == 'P')
	{
		$disum_val = ($disum / 100) * $dp;
		echo  $this->lib->format_idr($disum_val);	
		echo '&nbsp;('.$disum.'%)';
	}
	else
	{
		$disum_val = 0;
		echo $disum_val;
	}?></td>
  </tr>
  <tr>
  <td><strong>Harga Jual Unit</strong></td>
    <td width="9" align="center"><strong>:</strong></td>
    <td><?php
	
	 echo $this->lib->format_idr($dppppn);?></td>
    <td><strong>Total Uang Muka</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php
	if($cb_byr == 'C')
	{
		$bayar = '(Cicilan '.$dp_ccl.'X)';
		
	}
	else
	{
		$bayar = '(Tunai)';

	}
	 echo $this->lib->format_idr($dp).'&nbsp;'.$bayar; ?>
      <input type="hidden" name="txt_umk" value="<?php echo $um; ?>" />
      <input type="hidden" name="txt_umbyr" value="<?php echo $cb_byr; ?>" />
      <input type="hidden" name="txt_umccl" value="<?php echo $dp_ccl; ?>" />
      <input type="hidden" name="txt_cclns" value="<?php echo $cclns; ?>" /></td>
    </tr>
  <tr>
  <td><strong>PPN</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $this->lib->format_idr($ppn); ?></td>
    <td><strong>Biaya Biaya Titipan</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $this->lib->format_idr($release_biaya_titipan);?></td>
    </tr>
  <tr>
  <td><strong>Harga Total</strong> <strong>+ PPN</strong></td>
    <td width="9" align="center"><strong>:</strong></td>
    <td><?php	
	    echo $this->lib->format_idr($total);?></td>
    <td><strong>KPR</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $this->lib->format_idr($kpr); ?> (<?php echo $kpr_ccl; ?> Tahun)
<input type="hidden" name="txt_kpr" value="<?php echo $kpr; ?>" />
<input type="hidden" name="txt_kpr_ccl" value="<?php echo $kpr_ccl;  ?>" /></td>
    </tr>
</table>
</fieldset>

<?php if($dp_ccl > 0)
{
?>
<br />
</legend>
<table width="462" border="1" class="table table-striped table-bordered">
  <tr>
    <td colspan="3" align="center"><strong>Tabel Rencana Cicilan Uang Muka</strong><br />
    <i>( Total Uang Muka - Uang Tanda Jadi )</i></td>
    </tr>
  <tr>
    <td width="73" align="center"><strong>Cicilan Ke</strong></td>
    <td width="238" align="center"><strong>Jumlah</strong></td>
    <td width="129" align="center"><strong>Jatuh Tempo</strong></td>
  </tr>
<?php
if($cclns == 0)
{
//$cicilan = (($dp - $bkf) + $release_biaya_titipan) / $dp_ccl;
$cicilan = ($dp - $bkf) / $dp_ccl;
}
else
{
	//$cicilan = ((($dp - $bkf) + $release_biaya_titipan) - $cclns) / ($dp_ccl-1);
	$cicilan = (($dp - $bkf) - $cclns) / ($dp_ccl-1);
}
$tot_cicil = 0;
for ($i=1;$i<=$dp_ccl;$i++) { 

if($cclns > 0)
	{
		if($i==$dp_ccl)
		{
			$cicilan = $cclns;
		}
	}
	
$tot_cicil = $tot_cicil + $cicilan;

if($i == 1)
{
	$date=date_create($this->lib->conv_date_to_int($tgl_jtdp));
}
else
{	
	
	$date_tmp=date_create(date("Y-m-01",strtotime($this->lib->conv_date_to_int($tgl_jtdp2))));
	date_add($date_tmp,date_interval_create_from_date_string("+1 months"));	
	
	if(date_format($date_tmp,"t") < date_format(date_create($this->lib->conv_date_to_int($tgl_jtdp)),"d"))
	{ 
	
		$tgl_jtdp2 = date_format($date_tmp,"Y-m-t");
		$date = date_create($this->lib->conv_date_to_int($tgl_jtdp2));
	}
	else
	{
		$date =date_create($this->lib->conv_date_to_int($tgl_jtdp2));
		date_add($date,date_interval_create_from_date_string("+1 months"));
	}
}


?>
  <tr>
    <td align="center"><?php echo $i; ?><input type="hidden" name="txt_ang[]"  value="<?php echo $i; ?>"/></td>
    <td align="center"><?php echo $this->lib->format_idr($cicilan); ?><input type="hidden" name="txt_ccl[]"  value="<?php echo $cicilan; ?>"/></td>
    <td align="center"><?php echo date_format($date,"d/m/Y"); $tgl_jtdp2 = date_format($date,"d/m/Y"); ?><input type="hidden" name="txt_tgl_ccl[]"  value="<?php echo date_format($date,"Y-m-d"); ?>"/></td>
  </tr>
  <?php
}
?>
  <tr>
    <td align="center"><strong>Total</strong></td>
    <td align="center"><strong><?php echo $this->lib->format_idr($tot_cicil); ?></strong></td>
    <td></td>
    </tr>

</table>
<?php
}
else
{
?>
<table width="462" border="1" class="table table-striped table-bordered">
  <tr>
    <td colspan="2" align="center"><strong>Tabel Pembayaran Uang Muka Tunai</strong><br />
    <i>( Uang Muka - Uang Tanda Jadi )</i></td>
    </tr>
  <tr>
    <td width="238" align="center"><strong>Jumlah</strong></td>
    <td width="129" align="center"><strong>Jatuh Tempo</strong></td>
  </tr>
  <tr>
    <td width="238" align="center"><?php // echo $this->lib->format_idr(($dp - $bkf) + $release_biaya_titipan); 
	echo $this->lib->format_idr($dp - $bkf);?></td>
    <td width="129" align="center"><?php echo $tgl_jtdp; ?></td>
  </tr>
  </table>
<?php
}
?>
</fieldset>
</div>
<?php
	if(!empty($stok_kavf))
	{
?>
      <div role="tabpanel" class="tab-pane fade in" id="tab_sprf" aria-labelledby="home-tab">
<fieldset>
<legend>
Data Harga & Rencana Pembayaran</legend>
</fieldset>
<table width="784" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="122"><strong>Harga</strong></td>
    <td width="21" align="center"><strong>:</strong></td>
    <td width="619"><?php 
							$tot_hrgf = 0;
							$totall_hrgf = 0;
							foreach($stok_kavf as $row_fit)
								{
								?>
                              <input type="hidden" name="txt_ftid[]" value="<?php echo $row_fit->ftid; ?>" />
                              <input type="hidden" name="txt_ltnh[]" value="<?php echo $row_fit->ltnh; ?>" />
                              <input type="hidden" name="txt_hrgf[]" value="<?php echo $this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'H'); ?>" />
                              <input type="hidden" name="txt_tgl_hrgf[]" value="<?php echo $this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'T'); ?>" />
                              <?php echo $row_fit->namfit;  ?> :
                              <?php 
								if($row_fit->ftid=='TLE')
									{
										$tot_hrgf = $tot_hrgf + ($this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'H')*$row_fit->ltnh);
								echo $this->lib->format_idr($this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'H')*$row_fit->ltnh).' ('.$this->lib->format_idr($this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'H')).' x '.number_format($row_fit->ltnh).' M<sup>2</sup>)';
									}
								else
									{
										$tot_hrgf = $tot_hrgf + $this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'H');
								echo $this->lib->format_idr($this->lib->get_hargaf($row_stok->projid,$row_fit->ftid,'H'));
								?>
                             ,
                            <?php
									}
								}
							  ?><input type="hidden" name="txt_hrgtotf" value="<?php echo $tot_hrgf; ?>" />
                              </td>
    </tr>
  <tr>
    <td><strong>Harga Total</strong></td>
    <td width="21" align="center"><strong>:</strong></td>
    <td><?php
	echo $this->lib->format_idr($tot_hrgf);
	?></td>
    </tr>
  <tr>
    <td><strong>Diskon</strong></td>
    <td width="21" align="center"><strong>:</strong></td>
    <td><?php
	if($cb_disf == 'V')
	{
		$dis_valf = $disf;
	 	echo $this->lib->format_idr($dis_valf);
	}
	elseif($cb_disf == 'P')
	{
		$dis_valf = ($disf / 100) * $tot_hrgf;
		echo  $this->lib->format_idr($dis_valf);	
		echo '&nbsp;('.$disf.'%)';
	}
	else
	{
		$dis_valf = 0;
		echo $dis_valf;
	}?></td>
    </tr>
  <tr>
    <td><strong>Jumlah Bayar</strong></td>
    <td width="21" align="center"><strong>:</strong></td>
    <td><?php
	$totall_hrgf = $tot_hrgf - $dis_valf;
	if($cb_byrf == 'C')
	{
		$bayarf = '(Cicilan '.$cclf.'X)';
		
	}
	else
	{
		$bayarf = '(Tunai)';
	}
	 echo $this->lib->format_idr($totall_hrgf).'&nbsp;'.$bayarf; ?>
     <input type="hidden" name="txt_fbyr" value="<?php echo $cb_byrf; ?>" />
     <input type="hidden" name="txt_totall_hrgf" value="<?php echo $totall_hrgf; ?>" />
      <input type="hidden" name="txt_cclnf" value="<?php echo $cclnf; ?>" /></td>
    </tr>
</table>
<br />
<?php if($cclf > 0)
{
	?>
  <table width="462" border="1" class="table table-striped table-bordered">
  <tr>
    <td colspan="3" align="center"><strong>Tabel Rencana Cicilan Fitur</strong></td>
  </tr>
  <tr>
    <td width="73" align="center"><strong>Cicilan Ke</strong></td>
    <td width="238" align="center"><strong>Jumlah</strong></td>
    <td width="129" align="center"><strong>Jatuh Tempo</strong></td>
  </tr>
  <?php
  if($cclnf > 0)
{
	$cicilanf = ($totall_hrgf - $cclnf) / ($cclf-1);
}
else
{
	$cicilanf = $totall_hrgf / $cclf;
}
$tot_cicilf = 0;
for ($if=1;$if<=$cclf;$if++) { 


if($cclnf > 0)
	{
		if($if==$cclf)
		{
			$cicilanf = $cclnf;
		}
	}

$tot_cicilf = $tot_cicilf + $cicilanf;
	
if($if == 1)
{
	$datef=date_create($this->lib->conv_date_to_int($tgl_jtf));
}
else
{	
	$date_tmpf=date_create(date("Y-m-01",strtotime($this->lib->conv_date_to_int($tgl_jtf2))));
	date_add($date_tmpf,date_interval_create_from_date_string("+1 months"));	
	
	if(date_format($date_tmpf,"t") < date_format(date_create($this->lib->conv_date_to_int($tgl_jtf)),"d"))
	{ 
	
		$tgl_jtf2 = date_format($date_tmpf,"Y-m-t");
		$datef = date_create($this->lib->conv_date_to_int($tgl_jtf2));
	}
	else
	{
		$datef =date_create($this->lib->conv_date_to_int($tgl_jtf2));
		date_add($datef,date_interval_create_from_date_string("+1 months"));
	}
}


?>
  <tr>
    <td align="center"><?php echo $if; ?>
      <input type="hidden" name="txt_angf[]"  value="<?php echo $if; ?>"/></td>
    <td align="center"><?php echo $this->lib->format_idr($cicilanf); ?>
      <input type="hidden" name="txt_cclf2[]"  value="<?php echo $cicilanf; ?>"/></td>
    <td align="center"><?php echo date_format($datef,"d/m/Y"); $tgl_jtf2 = date_format($datef,"d/m/Y"); ?>
      <input type="hidden" name="txt_tgl_cclf[]"  value="<?php echo date_format($datef,"Y-m-d"); ?>"/></td>
  </tr>
  <?php
}
?>
  <tr>
    <td align="center"><strong>Total</strong></td>
    <td align="center"><strong><?php echo $this->lib->format_idr($tot_cicilf); ?></strong></td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php
}
?>
</div>
<?php
}
?>

  <?php
 
	if(!empty($tot_hrgb))
	{
?>
      <div role="tabpanel" class="tab-pane fade in" id="tab_sprb" aria-labelledby="home-tab">
     <fieldset>
<legend>
Biaya Biaya Titipan & Rencana Pembayaran</legend>
</fieldset>
<table width="784" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="157"><strong>Biaya Biaya Titipan</strong></td>
    <td width="11" align="center"><strong>:</strong></td>
    <td width="616"><?php 
	echo $this->lib->format_idr($tot_hrgb);?></td>
    </tr>
  <tr>
    <td><strong>Diskon</strong></td>
    <td width="11" align="center"><strong>:</strong></td>
    <td><?php
	if($cb_disb == 'V')
	{
		$dis_valb = $disb;
	 	echo $this->lib->format_idr($dis_valb);
	}
	elseif($cb_disb == 'P')
	{
		$dis_valb = ($disb / 100) * $release_biaya_titipan;
		echo  $this->lib->format_idr($dis_valb);	
		echo '&nbsp;('.$disb.'%)';
	}
	else
	{
		$dis_valb = 0;
		echo $dis_valb;
	}?></td>
  </tr>
  <tr>
    <td><strong>Jumlah Bayar</strong></td>
    <td width="11" align="center"><strong>:</strong></td>
    <td><?php
	$totall_hrgb = $tot_hrgb - $dis_valb;
	if($cb_byrb == 'C')
	{
		$bayarb = '(Cicilan '.$cclb.'X)';
		
	}
	else
	{
		$bayarb = '(Tunai)';
	}
	 echo $this->lib->format_idr($totall_hrgb).'&nbsp;'.$bayarb; ?>
      <input type="hidden" name="txt_tot_hrgb" value="<?php echo $tot_hrgb; ?>" />
     <input type="hidden" name="txt_byrb" value="<?php echo $cb_byrb; ?>" />
     <input type="hidden" name="txt_totall_hrgb" value="<?php echo $totall_hrgb; ?>" />
      <input type="hidden" name="txt_cclnb" value="<?php echo $cclnb; ?>" /></td>
    </tr>
</table>
<br />
<?php if($cclb > 0)
{
	?>
  <table width="462" border="1" class="table table-striped table-bordered">
  <tr>
    <td colspan="3" align="center"><strong>Tabel Rencana Cicilan Biaya Biaya Titipan</strong></td>
  </tr>
  <tr>
    <td width="73" align="center"><strong>Cicilan Ke</strong></td>
    <td width="238" align="center"><strong>Jumlah</strong></td>
    <td width="129" align="center"><strong>Jatuh Tempo</strong></td>
  </tr>
  <?php
  if($cclnb > 0)
{
	$cicilanb = ($totall_hrgb - $cclnb) / ($cclb-1);
}
else
{
	$cicilanb = $totall_hrgb / $cclb;
}
$tot_cicilb = 0;
for ($ib=1;$ib<=$cclb;$ib++) { 


if($cclnb > 0)
	{
		if($ib==$cclb)
		{
			$cicilanb = $cclnb;
		}
	}

$tot_cicilb = $tot_cicilb + $cicilanb;
	
if($ib == 1)
{
	$dateb=date_create($this->lib->conv_date_to_int($tgl_jtb));
}
else
{	
	$date_tmpb=date_create(date("Y-m-01",strtotime($this->lib->conv_date_to_int($tgl_jtb2))));
	date_add($date_tmpb,date_interval_create_from_date_string("+1 months"));	
	
	if(date_format($date_tmpb,"t") < date_format(date_create($this->lib->conv_date_to_int($tgl_jtb)),"d"))
	{ 
	
		$tgl_jtb2 = date_format($date_tmpb,"Y-m-t");
		$dateb = date_create($this->lib->conv_date_to_int($tgl_jtb2));
	}
	else
	{
		$dateb =date_create($this->lib->conv_date_to_int($tgl_jtb2));
		date_add($dateb,date_interval_create_from_date_string("+1 months"));
	}
}


?>
  <tr>
    <td align="center"><?php echo $ib; ?>
      <input type="hidden" name="txt_angb[]"  value="<?php echo $ib; ?>"/></td>
    <td align="center"><?php echo $this->lib->format_idr($cicilanb); ?>
      <input type="hidden" name="txt_cclb2[]"  value="<?php echo $cicilanb; ?>"/></td>
    <td align="center"><?php echo date_format($dateb,"d/m/Y"); $tgl_jtb2 = date_format($dateb,"d/m/Y"); ?>
      <input type="hidden" name="txt_tgl_cclb[]"  value="<?php echo date_format($dateb,"Y-m-d"); ?>"/></td>
  </tr>
  <?php
}
?>
  <tr>
    <td align="center"><strong>Total</strong></td>
    <td align="center"><strong><?php echo $this->lib->format_idr($tot_cicilb); ?></strong></td>
    <td>&nbsp;</td>
  </tr>
</table>

<?php } ?>
      </div>
 <?php
	}
 ?>
 
        </div>
      
                        </div>
                       <!-- </div>-->