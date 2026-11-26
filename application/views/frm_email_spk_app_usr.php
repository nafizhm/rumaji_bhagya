Dear <?php echo $txt_nama; ?>,<br />
<br />
Dokumen di bawah ini telah di <?php echo $txt_status; ?><br />
<br />
<?php if($txt_status[0] == 'R')
{
?>
Alasan reject : <?php echo $txt_ket_reject; ?>
<br />
<br />
<?php
}
?>
<table width="488" border="0">
  <table width="488" border="0">
  <tr>
    <td width="203"><strong>Project</strong></td>
    <td width="10" align="center"><strong>:</strong></td>
    <td width="266"><?php echo $txt_namproj; ?></td>
  </tr>
  <tr>
    <td><strong>Pos Anggaran</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_nampa; ?></td>
  </tr>
  <tr>
    <td><strong>Biaya</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_namby; ?></td>
  </tr>
  <tr>
    <td><strong>No. Dokumen</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_spkno; ?></td>
  </tr>
  <tr>
    <td><strong>Tipe</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_tipe; ?></td>
  </tr>
  <tr>
    <td><strong>Total</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_total; ?></td>
  </tr>
  <tr>
    <td><strong>Keterangan</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_ket; ?></td>
  </tr>
  <tr>
    <td><strong>Di <?php echo $txt_status; ?> oleh</strong></td>
    <td align="center"><strong>:</strong></td>
    <td><?php echo $txt_app; ?></td>
  </tr>
</table>
