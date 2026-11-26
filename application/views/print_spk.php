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
    <td align="center" width="40%"><blockquote>
      <h1><u>SURAT PERJANJIAN KERJA</u></h1>
    </blockquote>      <h2>No : <?php echo $txt_nodok; ?> </h2></td>
    <td width="20%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="17%"><strong>Proyek</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="36%"><?php echo $txt_namproj; ?></td>
    <td width="15%"><strong>Tanggal Kontrak</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="30%"><?php echo $txt_tgl; ?></td>
  </tr>
  <tr>
    <td><strong>Jenis Pekerjaan</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php if($txt_spk_jp == 'RMH')
						  {
						  	echo 'RUMAH';
						  }
						  elseif($txt_spk_jp == 'INFRA')
						  {
							 echo 'INFRASTRUKTUR';
						  }
						  ?></td>
    <td><strong>Total Kontrak</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_total; ?></td>
  </tr>
  <tr>
    <td><strong>Penerima Pekerjaan</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_spk_vendor; ?></td>
    <td><strong>Keterangan</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_ket; ?></td>
  </tr>
</table>
<br />
<?php echo $txt_spk_isi; ?>
