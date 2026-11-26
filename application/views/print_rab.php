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
      <h1><u>RENCANA ANGGARAN BIAYA</u></h1>
      <h3>Berdasarkan penawaran serta perhitungan bersama dilapangan, <br> Mohon di Buatkan SPK/WO/ PAI untuk pekerjaan dibawah ini :</h3>
    </td>
    <td width="20%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="2" cellspacing="2" style='border-collapse: collapse;'>
  <tr>
    <td width="16%"><strong>Proyek</strong></td>
    <td width="1%" align="center"><strong>:</strong></td>
    <td width="48%"><?php echo $namproj; ?></td>
    <td width="8%">&nbsp;</td>
    <td width="1%" align="center">&nbsp;</td>
    <td width="26%">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Pekerjaan</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $kerja; ?></td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Tanggal</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $tgl; ?></td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="100%" border="1" cellpadding="2" cellspacing="0" bordercolor="#000000">
  <tr>
    <th>No</th>
    <th>Kontraktor</th>
    <th>Uraian Pekerjaan</th>
    <th>Volume</th>
    <th>Satuan</th>
    <th>Harga Satuan ( Rp )</th>
    <th>Total ( Rp )</th>
    <th>Keterangan</th>
  </tr>
  <?php
  $total = 0;
  $no = 0;
  foreach ($rabd as $row) {
    $total = $total + $row->total;
  ?>
    <tr>
      <td style="text-align:center"><?= ++$no; ?></td>
      <td><?= $row->namkon; ?></td>
      <td><?= $row->namitm; ?></td>
      <td><?= $this->lib->format_qty($row->vol); ?></td>
      <td style="text-align:center"><?= $row->namsat; ?></td>
      <td style="text-align:right"><?= $this->lib->format_idr($row->hrg); ?></td>
      <td style="text-align:right"><?= $this->lib->format_idr($row->total); ?></td>
      <td><?= $row->ket; ?></td>
    <?php } ?>
    <tr>
      <th colspan="5"></th>
      <th style="text-align:right">TOTAL</th>
      <th style="text-align:right"><?= $this->lib->format_idr($total); ?></th>
      <th></th>
    </tr>
    <tr>
      <th colspan="8" style="text-align:center">Terbilang # <?= $this->lib->terbilang($total); ?> Rupiah</th>
    </tr>
</table>
<br />
<br />
<table width="100%" height="114" border="0">
  <tr>
    <td width="35%" align="center" valign="top">Disetujui oleh,<br />
      <br />
      <br />
      <br />
      <br />
      ( Direksi )</td>
    <td width="33%" align="center" valign="top">Diajukan oleh,<br />
      <br />
      <br />
      <br />
      <br />
      ( Planner Manager )</td>
  </tr>
</table>