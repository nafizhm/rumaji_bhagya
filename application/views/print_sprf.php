<?php 
if(empty($sprf))
{
	echo 'Tidak ada data!';
	exit;
}
foreach($sprh as $row_sprh); ?>
<style type="text/css">
hr {
    border: none;
    height: 1px;
    /* Set the hr color */
    color: #333; /* old IE */
    background-color: #333; /* Modern Browsers */
}
body,td,th {
	font-size: 12px;
}
</style>
<table width="100%" border="0">
  <tr>
    <td width="20%"><img src="<?php echo $this->config->item('assets');?>/img/logo.jpg" alt="" /></td>
    <td align="center" width="40%"><h2>SURAT PEMESANAN RUMAH</h2></td>
    <td width="20%"><strong>No SPR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </strong><?php echo $this->lib->no_spr($row_sprh->sprno); ?><br />
    <strong>Tgl Transaksi :</strong> <?php 
	$date = new DateTime($row_sprh->crdat);
	$date->modify('+30 days');
	echo $this->lib->disp_date($date->format('Y-m-d')); ?></td>
  </tr>
</table>
<h4>Konsumen</h4>
<hr />
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="8%"><strong>Nama </strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="29%"><?php echo $row_sprh->nama; ?></td>
    <td width="13%"><strong>Alamat</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="46%"><?php echo $row_sprh->alm; ?></td>
  </tr>
  <tr>
    <td><strong>No. KTP</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_sprh->ktp; ?></td>
    <td><strong>No. Telp / Telp2</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_sprh->telp; ?> / <?php echo $row_sprh->telp2; ?></td>
  </tr>
  <tr>
    <td><strong>NPWP</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_sprh->npwp; ?></td>
    <td><strong>Email</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_sprh->email; ?></td>
  </tr>
</table>
<h4>Kavling Dipesan</h4>
<hr />
<table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td width="10%"><strong>Project</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="28%"><?php echo $row_sprh->namproj; ?></td>
    <td width="6%"><strong>Lot</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="6%"><?php echo $row_sprh->lot; ?></td>
    <td width="12%"><strong>Luas Tanah</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="35%"><?php echo number_format($row_sprh->ltstd); ?> M<sup>2</sup></td>
  </tr>
  <tr>
    <td><strong>Blok / No </strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_sprh->blok; ?> / <?php echo $row_sprh->no; ?></td>
    <td><strong>Tipe</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_sprh->tipe; ?></td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<h4>Harga &amp; Rencana Pembayaran</h4>
<hr />
<table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td width="12%"><strong>Harga</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="86%" ><?php
    foreach($sprf_h as $row_sprf_h)
	{
		echo '<b>'.$row_sprf_h->namfit.': </b>';
		if($row_sprf_h->ftid=='TLE')
		{
			$hargaf = $row_sprf_h->hrg *  $row_sprf_h->ltnh;
				
			echo $this->lib->format_idr($hargaf).'('.$this->lib->format_idr($row_sprf_h->hrg).' x '.number_format($row_sprf_h->ltnh).' M<sup>2</sup>)';	
		}
		else
		{
			echo $this->lib->format_idr($row_sprf_h->hrg);
		}
	?>
    ,
    <?php
	}
	?></td>
  </tr>
  <?php
    foreach($sprf as $row_sprf);
	?>
  <tr>
    <td><strong>Harga Total</strong></td>
    <td  align="center"><strong>:</strong></td>
    <td><?php echo $this->lib->format_idr($row_sprf->hrgtotf); ?></td>
  </tr>
  <tr>
    <td><strong>Diskon</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php
	if($row_sprf->disftp == 'V')
	{
	 	echo $this->lib->format_idr($row_sprf->disf);
	}
	elseif($row_sprf->disftp == 'P')
	{
		$dis_val = ($row_sprf->disf / 100) * $row_sprf->hrgtotf;
		echo  $this->lib->format_idr($dis_val);	
		echo '&nbsp;('.$row_sprf->disf.'%)';
	}
	else
	{
		echo $row_sprf->disf;
	}?></td>
  </tr>
  <tr>
    <td><strong>Jumlah Bayar</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php
	if($row_sprf->fbyr == 'C')
	{
		$bayarf = '(Cicilan '.$row_sprf->fccl.'X)';
		
	}
	else
	{
		$bayarf = '(Tunai)';
	}
	 echo $this->lib->format_idr($row_sprf->hrgtotalf).'&nbsp;'.$bayarf; ?></td>
  </tr>
</table>
<h4>Jadwal Pembayaran</h4>
<hr />
<?php
if($row_sprf->fccl == 0 )
{
?>
<table width="100%" border="1" cellpadding="0" cellspacing="0" class="table table-striped table-bordered">
  <tr>
    <td colspan="3" align="center"><strong>Tabel Pembayaran Fitur</strong></td>
  </tr>
  <tr>
    <td width="21%" align="center"><strong>Untuk Pembayaran</strong></td>
    <td width="37%" align="center"><strong>Jumlah</strong></td>
    <td width="42%" align="center"><strong>Jatuh Tempo</strong></td>
  </tr>
  <?php
foreach($sprpf_t as $row_sprpf_t)
	{
?>
  <tr>
    <td align="center"><?php 
	if($row_sprpf_t->tipe = 'UFT')
	{
		echo 'Uang Fitur';
	}
	?></td>
    <td align="center"><?php echo $this->lib->format_idr($row_sprpf_t->total); ?></td>
    <td align="center"><?php echo $this->lib->disp_date($row_sprpf_t->jtmp); ?></td>
  </tr>
  <?php
}
?>
 </table>
 <?php
}
else
{
	$tot_cicil = 0;
?>
<br />
<table width="100%" border="1" cellpadding="0" cellspacing="0" class="table table-striped table-bordered">
  <tr>
    <td colspan="3" align="center"><strong>Tabel Pembayaran Cicilan Fitur</strong></td>
  </tr>
  <tr>
    <td width="9%" align="center"><strong>Cicilan Ke</strong></td>
    <td width="49%" align="center"><strong>Jumlah</strong></td>
    <td width="42%" align="center"><strong>Jatuh Tempo</strong></td>
  </tr>
<?php
foreach($sprp as $row_sprp)
	{
		$tot_cicil = $tot_cicil + $row_sprp->total;
?>
  <tr>
    <td align="center"><?php echo $row_sprp->ang; ?></td>
    <td align="center"><?php echo $this->lib->format_idr($row_sprp->total); ?></td>
    <td align="center"><?php echo $this->lib->disp_date($row_sprp->jtmp); ?></td>
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
?>
<table width="100%" border="0">
  <tr>
    <td width="100%" colspan="2">Demikian surat pesanan dan konfirmasi pembelian rumah ini dibuat. Dan seluruh isi ketentuan yang ada di surat ini (2 halaman) telah disetujui oleh saya selaku pemesan untuk dapat dipergunakan sebagaimana mestinya.</td>
  </tr>
<tr>
    <td width="60%"><table width="84%" height="86" border="1" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <th height="18" align="center">Marketing</th>
        <th align="center">Keuangan</th>
        <th align="center">Pimpinan</th>
      </tr>
      <tr>
        <td height="40"></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    <td width="40%" align="center"><p>Pemesan<br />
      <br />
      <br />
    </p>
      <p><em>Materai 6000</em><br />
        <br />
        <br />
        <br />
    ( <?php echo $row_sprh->nama; ?> )</p></td>
  </tr>
</table>

