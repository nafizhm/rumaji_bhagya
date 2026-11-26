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
//$pt = $this->lib->get_pt($this->uri->segment(4));
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
      <h1><u>BUKTI BANK KELUAR</u></h1>
      <h2><?= $nama_project; ?> </h2>
    </td>
    <td width="20%">
        <table width="100%" border="1" bordercolor="#000000" cellpadding="2" cellspacing="0">
          <tr>
            <th width="50%" align="center"><div>No.</div><br></th>
            <th width="100%" align="center"><div>BK</div><br></th>
            <th width="100%" align="center"></th>
            <th width="100%" align="center"></th>
          </tr>
          <tr>
            <td width="50%" align="center"><div>Tgl.</div><br></td>
            <td width="100%"></td>
            <td width="100%"></td>
            <td width="100%"><div>20.......</div><br></td>
          </tr>
        </table>
    </td>
  </tr>
</table>
<br />
<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
  <tr>
    <th width="4%" align="center">NO</th>
    <th width="4%" align="center">NO. REQUEST</th>
    <th width="29%" align="center">KETERANGAN</th>
    <th width="10%" align="center">JUMLAH</th>
  </tr>
  <?php
  $no = 0;
  $total = 0;
  foreach ($query->result() as $row) {
    $total = $total + $row->total;
  ?>
    <tr>
      <td align="center"><?= ++$no; ?></td>
      <td align="center"><?= $this->lib->no_req($row->reqno); ?></td>
      <td><?= $row->ket; ?></td>
      <td align="right"><?= $this->lib->format_idr($row->total); ?></td>
    </tr>
  <?php } ?>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="right"><strong>TOTAL</strong></td>
    <td align="right"><b><?= $this->lib->format_idr($total); ?></b></td>
  </tr>
</table>
<br />
<br />
<table width="100%" height="114" border="0">
  <tr>
    <td width="35%" align="center" valign="top">Dibuat,<br />
      <br />
      <br />
      <br />
      <br />
      ( ____________________ )</td>
    <td width="33%" align="center" valign="top">Diperiksa,<br />
      <br />
      <br />
      <br />
      <br />
      ( ____________________ )</td>
    <td width="32%" align="center" valign="top">Disetujui,<br />
      <br />
      <br />
      <br />
      <br />
      ( ____________________ )
    </td>
  </tr>
</table>