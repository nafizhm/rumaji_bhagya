<?php foreach($sprh as $row_sprh);
foreach($kwt as $row_kwt); ?>
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
    <td align="center" width="40%"><h1><u>KWITANSI BATAL</u></h1></td>
    <td width="20%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="19%"><strong>NO</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="44%"><?php echo $this->lib->no_kwt($row_kwt->kwtno); ?></td>
    <td width="9%"><strong>Blok / No.</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="26%"><?php echo $row_sprh->blok; ?> / <?php echo $row_sprh->no; ?></td>
  </tr>
  <tr>
    <td><strong>Proyek</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_sprh->namproj; ?></td>
    <td><strong>Lot</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_sprh->lot; ?></td>
  </tr>
  <tr>
    <td width="19%"><strong>Tipe rumah</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $row_sprh->tipe; ?></td>
    <td><strong>No SPR</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $this->lib->no_spr($row_sprh->sprno); ?></td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="19%"><strong>Telah Terima Dari</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="80%"><strong><?php echo $this->lib->disp_namusr($row_kwt->crby); ?></strong></td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="19%"><strong>Total</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="80%">Rp. <?php echo $this->lib->format_idr($row_kwt->total*-1); ?></td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="19%"><strong>Terbilang</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="80%"><?php echo $this->lib->terbilang($row_kwt->total*-1); ?> Rupiah</td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="19%"><strong>Untuk Pembayaran</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="80%"><?php echo $this->lib->tipe_byr_txt($row_kwt->tipe); ?> <?php if($row_kwt->ang <> 0){ echo '( '.$row_kwt->ang.' )'; } ?></td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="19%"><strong>Keterangan</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="80%"><?php echo $row_kwt->ket; ?></td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="65%" rowspan="5" valign="top"><strong>Jenis Pembayaran 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :
    <?php if($row_kwt->byr=='T')
	{
		echo 'Transfer';
	}
	else
	{
		echo 'Cash';
	}
	?>
    <br />
    <br />
    Pembayaran di transfer ke Rekening : <br />
    <br />
    <?php echo $this->lib->get_nambank($row_sprh->bankid) ?> <?php echo $row_sprh->accno; ?> A/N <?php echo $row_sprh->namacc; ?>    </strong></td>
    <td align="center"><strong>Tanggal</strong> <?php echo $this->lib->disp_date($row_kwt->tgl); ?></td>
  </tr>
  <tr>
    <td align="center">Hormat Saya</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td width="65%" valign="top"><strong>Catatan : Kwitansi ini berlaku apabila disertai bukti transfer dari pihak developer</strong></td>
    <td width="35%" align="center"><strong><?php echo $row_sprh->nama; ?></strong></td>
  </tr>
</table>