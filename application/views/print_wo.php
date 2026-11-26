<?php
/*
if($this->uri->segment(4) == 4 || $this->uri->segment(4) == 8 || $this->uri->segment(4) == 9)
{
	$pt = 'PT. Mitra Bangun Samaji';
}
else
{
	$pt = 'PT. Karsa Samana Imaji';
}*/
$pt = $this->lib->get_pt($this->uri->segment(4));
?>
<style type="text/css">
  hr {
    border: none;
    height: 1px;
    /* Set the hr color */
    color: #333;
    /* old IE */
    background-color: #333;
    /* Modern Browsers */
  }

  body,
  td,
  th {
    font-size: 12px;
  }
</style>
<table width="100%" border="0">
  <tr>
    <td width="20%"><img src="<?php echo $this->config->item('assets'); ?>/img/logo.jpg" alt="" /></td>
    <td align="center" width="40%">
      <h1><u>WORK ORDER</u></h1>
      <h2>No : <?php echo $txt_nodok; ?> </h2>
    </td>
    <td width="20%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="16%"><strong>Proyek</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="48%"><?php echo $txt_namproj; ?></td>
    <td width="8%">&nbsp;</td>
    <td width="1%" align="center">&nbsp;</td>
    <td width="26%">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Tanggal</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_tgl; ?></td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Penerima Pekerjaan</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_wo_mandor; ?></td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
  <tr>
    <th width="4%" align="center">NO</th>
    <th width="29%" align="center">JENIS PEKERJAAN</th>
    <th width="10%" align="center">VOL</th>
    <th width="6%" align="center">SAT</th>
    <th width="13%" align="center">HRG SAT (Rp)</th>
    <th width="11%" align="center">TOTAL (Rp)</th>
    <th width="27%" align="center">Syarat/Spek</th>
  </tr>
  <tr>
    <td align="center">1</td>
    <td><?php echo $txt_ket; ?></td>
    <td align="center"><?php echo $txt_wo_vol; ?></td>
    <td align="center"><?php echo $txt_wo_sat; ?></td>
    <td align="right"><?php echo $txt_wo_hrg; ?></td>
    <td align="right"><?php echo $txt_total; ?></td>
    <td><?php echo $txt_wo_spek; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="4" align="center"><strong>Nilai Total Pekerjaan</strong></td>
    <td align="right"><b><?php echo $txt_total; ?></b></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<!---
<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
  <tr>
    <th align="center" bordercolor="">&nbsp;</th>
    <th align="center">Tgl</th>
    <th align="center">Progress</th>
    <th align="center">Jumlah</th>
    <th align="center">Paraf</th>
    <th align="center">Keterangan</th>
  </tr>
  <tr>
    <td>Pembayaran I</td>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
  </tr>
  <tr>
    <td>Pembayaran II</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Pembayaran III</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="33%">Pembayaran IV</td>
    <td width="10%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
---->
<br />
<table width="100%" height="114" border="0">
  <tr>
    <td width="35%" align="center" valign="top">Penerima Pekerjaan<br />
      <br />
      <br />
      <br />
      <br />
      ( <u><?php echo $txt_wo_mandor; ?></u> )</td>
    <td width="33%" align="center" valign="top"><?php echo $pt; ?><br />
      <br />
      <br />
      <br />
      <br />
      ( ____________________ )</td>
    <td width="32%" align="center" valign="top"><br />
      <br />
      <br />
      <br />
      <table width="100" height="15" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="167" height="15" align="center" bgcolor="#FF0000">
            <font color="#FFFFFF">Copy 1 dari 3 - SubKon </font>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<p style="page-break-after: always;">&nbsp;</p>
<table width="100%" border="0">
  <tr>
    <td width="20%"><img src="<?php echo $this->config->item('assets'); ?>/img/logo.jpg" alt="" /></td>
    <td align="center" width="40%">
      <h1><u>WORK ORDER</u></h1>
      <h2>No : <?php echo $txt_nodok; ?> </h2>
    </td>
    <td width="20%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="16%"><strong>Proyek</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="48%"><?php echo $txt_namproj; ?></td>
    <td width="8%">&nbsp;</td>
    <td width="1%" align="center">&nbsp;</td>
    <td width="26%">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Tanggal</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_tgl; ?></td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Penerima Pekerjaan</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_wo_mandor; ?></td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
  <tr>
    <th width="4%" align="center">NO</th>
    <th width="29%" align="center">JENIS PEKERJAAN</th>
    <th width="10%" align="center">VOL</th>
    <th width="6%" align="center">SAT</th>
    <th width="13%" align="center">HRG SAT (Rp)</th>
    <th width="11%" align="center">TOTAL (Rp)</th>
    <th width="27%" align="center">Syarat/Spek</th>
  </tr>
  <tr>
    <td align="center">1</td>
    <td><?php echo $txt_ket; ?></td>
    <td align="center"><?php echo $txt_wo_vol; ?></td>
    <td align="center"><?php echo $txt_wo_sat; ?></td>
    <td align="right"><?php echo $txt_wo_hrg; ?></td>
    <td align="right"><?php echo $txt_total; ?></td>
    <td><?php echo $txt_wo_spek; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="4" align="center"><strong>Nilai Total Pekerjaan</strong></td>
    <td align="right"><b><?php echo $txt_total; ?></b></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<!---
<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
  <tr>
    <th align="center" bordercolor="">&nbsp;</th>
    <th align="center">Tgl</th>
    <th align="center">Progress</th>
    <th align="center">Jumlah</th>
    <th align="center">Paraf</th>
    <th align="center">Keterangan</th>
  </tr>
  <tr>
    <td>Pembayaran I</td>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
  </tr>
  <tr>
    <td>Pembayaran II</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Pembayaran III</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="33%">Pembayaran IV</td>
    <td width="10%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
---->
<table width="100%" height="114" border="0">
  <tr>
    <td width="35%" align="center" valign="top">Penerima Pekerjaan<br />
      <br />
      <br />
      <br />
      <br />
      ( <u><?php echo $txt_wo_mandor; ?></u> )</td>
    <td width="33%" align="center" valign="top"><?php echo $pt; ?><br />
      <br />
      <br />
      <br />
      <br />
      ( ____________________ )</td>
    <td width="32%" align="center" valign="top"><br />
      <br />
      <br />
      <br />
      <table width="100" height="15" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="167" height="15" align="center" bgcolor="#00FF00">
            <font color="#FFFFFF">Copy 2 dari 3 - Pengawas</font>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<p style="page-break-after: always;">&nbsp;</p>
<table width="100%" border="0">
  <tr>
    <td width="20%"><img src="<?php echo $this->config->item('assets'); ?>/img/logo.jpg" alt="" /></td>
    <td align="center" width="40%">
      <h1><u>WORK ORDER</u></h1>
      <h2>No : <?php echo $txt_nodok; ?> </h2>
    </td>
    <td width="20%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="16%"><strong>Proyek</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="48%"><?php echo $txt_namproj; ?></td>
    <td width="8%">&nbsp;</td>
    <td width="1%" align="center">&nbsp;</td>
    <td width="26%">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Tanggal</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_tgl; ?></td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Penerima Pekerjaan</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_wo_mandor; ?></td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
  <tr>
    <th width="4%" align="center">NO</th>
    <th width="29%" align="center">JENIS PEKERJAAN</th>
    <th width="10%" align="center">VOL</th>
    <th width="6%" align="center">SAT</th>
    <th width="13%" align="center">HRG SAT (Rp)</th>
    <th width="11%" align="center">TOTAL (Rp)</th>
    <th width="27%" align="center">Syarat/Spek</th>
  </tr>
  <tr>
    <td align="center">1</td>
    <td><?php echo $txt_ket; ?></td>
    <td align="center"><?php echo $txt_wo_vol; ?></td>
    <td align="center"><?php echo $txt_wo_sat; ?></td>
    <td align="right"><?php echo $txt_wo_hrg; ?></td>
    <td align="right"><?php echo $txt_total; ?></td>
    <td><?php echo $txt_wo_spek; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="4" align="center"><strong>Nilai Total Pekerjaan</strong></td>
    <td align="right"><b><?php echo $txt_total; ?></b></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<!---
<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
  <tr>
    <th align="center" bordercolor="">&nbsp;</th>
    <th align="center">Tgl</th>
    <th align="center">Progress</th>
    <th align="center">Jumlah</th>
    <th align="center">Paraf</th>
    <th align="center">Keterangan</th>
  </tr>
  <tr>
    <td>Pembayaran I</td>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
    <th align="center">&nbsp;</th>
  </tr>
  <tr>
    <td>Pembayaran II</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Pembayaran III</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="33%">Pembayaran IV</td>
    <td width="10%">&nbsp;</td>
    <td width="5%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="11%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
  </tr>
</table>
---->
<table width="100%" height="114" border="0">
  <tr>
    <td width="35%" align="center" valign="top">Penerima Pekerjaan<br />
      <br />
      <br />
      <br />
      <br />
      ( <u><?php echo $txt_wo_mandor; ?></u> )</td>
    <td width="33%" align="center" valign="top"><?php echo $pt; ?><br />
      <br />
      <br />
      <br />
      <br />
      ( ____________________ )</td>
    <td width="32%" align="center" valign="top"><br />
      <br />
      <br />
      <br />
      <table width="100" height="15" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="167" height="15" align="center" bgcolor="#0000FF">
            <font color="#FFFFFF">Copy 3 dari 3 - Finance
            </font>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />