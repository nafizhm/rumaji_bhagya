<?php foreach($sprh as $row_sprh); ?>
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
    <td align="center" width="40%"><h2>SURAT PEMBATALAN</h2></td>
    <td width="20%">&nbsp;</td>
  </tr>
</table>
<br />
Pada hari ini <?php echo $this->lib->tanggal_indo($row_sprh->btldat,TRUE); ?><br />
Yang bertandatangan di bawah ini :<br />
<br />
<table width="100%" border="0">
  <tr>
    <td width="1%">1.</td>
    <td width="13%">Nama </td>
    <td width="1%" align="center">:</td>
    <td width="85%">LIQQA FAIZAL YUNIAR</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Jabatan </td>
    <td align="center">:</td>
    <td>DIREKTUR UTAMA <?php echo $nama_pt; ?><br /></td>
  </tr>
  <tr>
    <td colspan="4"><br />
    Dalam hal ini bertindak dalam jabatannya di atas dan karenanya untuk dan atas nama pribadi, selanjutnya disebut <strong>PIHAK PERTAMA</strong>.<br />
    <br /></td>
  </tr>
  <tr>
    <td>2.</td>
    <td>Nama</td>
    <td align="center">:</td>
    <td> <?php echo strtoupper($row_sprh->nama); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Alamat</td>
    <td align="center">:</td>
    <td><?php echo $row_sprh->alm; ?> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>No. Telepon</td>
    <td align="center">:</td>
    <td><?php echo $row_sprh->telp; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">Dalam hal ini bertindak untuk dan atas nama pemesan/pembeli dengan fasilitas KPR/Cash yang selanjutnya disebut <strong>PIHAK KEDUA</strong>.<br />
      <br />
    PIHAK KEDUA terlebih dahulu menerangkan sebagai berikut :</td>
  </tr>
  <tr>
    <td valign="top">1.</td>
    <td colspan="3">Bahwa PIHAK KEDUA telah menyetujui dan menandatangani Surat Pesanan dan konfirmasi Pembelian (S.P.K.P) No. <?php echo $this->lib->no_spr($row_sprh->sprno);?> tertanggal <?php echo $this->lib->no_spr($this->lib->tanggal_indo($row_sprh->crdat),FALSE);?> dengan No. Kwitansi. <?php echo $kwtno; ?> atas pemesanan pembelian satu unit rumah yang berlokasi di perumahan <?php echo $namproj;  ?> Blok.<?php echo $row_sprh->blok; ?> No .<?php echo $row_sprh->no; ?> Type .<?php echo $tipe; ?>/<?php echo $luas; ?>M&sup2;.</td>
  </tr>
  <tr>
    <td valign="top">2.</td>
    <td colspan="3">Dengan ditanda tanganinya Surat Pembatalan ini, PIHAK KEDUA menyatakan membatalkan Pembelian/Pemesanan rumah tersebut diatas PIHAK PERTAMA berdasarkan pembatalan ini berkewajiban mengembalikan sejumlah uang yang telah diserahkan setelah dikurangi biaya administrasi dengan perincian sebagai berikut :</td>
  </tr>
  <tr>
    <td colspan="4"><br />
      <table width="42%" border="0" align="center">
      <tr>
        <td width="47%">Uang  yang telah disetor </td>
        <td width="2%" align="center">:</td>
        <td width="51%">Rp. <?php echo $this->lib->format_idr($row_sprh->totsetor); ?></td>
      </tr>
      <tr>
        <td>Biaya  Administrasi</td>
        <td align="center">:</td>
        <td>Rp. <?php echo $this->lib->format_idr($row_sprh->byadm); ?></td>
      </tr>
      <tr>
        <td>Diterima</td>
        <td align="center">:</td>
        <td>Rp. <?php echo $this->lib->format_idr($row_sprh->totbyr); ?></td>
      </tr>
      <tr>
        <td>Ditransfer  Ke Norek</td>
        <td align="center">:</td>
        <td><?php echo $row_sprh->accno; ?></td>
      </tr>
      <tr>
        <td>Bank</td>
        <td align="center">:</td>
        <td><?php echo $this->lib->get_nambank($row_sprh->bankid); ?></td>
      </tr>
      <tr>
        <td>A/N</td>
        <td align="center">:</td>
        <td><?php echo $row_sprh->namacc; ?></td>
      </tr>
    </table>
    <br /></td>
  </tr>
  <tr>
    <td colspan="4"><p>Uang akan ditransfer maksimal 14 hari kerja setelah tanda tangan Surat Pembatalan dan dengan penerimaan uang tersebut diatas maka antara PIHAK PERTAMA dan PIHAK KEDUA tidak ada hubungan hukum juga berkenan dengan rumah di Perumahan  <?php echo $namproj;  ?> Blok.<?php echo $row_sprh->blok; ?> No .<?php echo $row_sprh->no; ?> Type .<?php echo $tipe; ?>/<?php echo $luas; ?>M&sup2;.  Sehingga rumah tersebut sepenuhnya menjadi hak milik PIHAK PERTAMA kembali.</p>
    <p>Demikian Surat Pembatalan ini dibuat dengan sebenarnya.</p></td>
  </tr>
  <tr>
    <td colspan="4" align="right"><p><u></u></p>
      <table width="100%" border="0">
        <tr>
          <td width="31%">&nbsp;</td>
          <td width="32%">&nbsp;</td>
          <td width="37%">..................., <?php echo $this->lib->tanggal_indo($row_sprh->btldat,FALSE); ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>PIHAK  PERTAMA,</td>
          <td>PIHAK  KEDUA,</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td valign="bottom"><br />
          <br />
          <br />
          <br />
          <br />
           ( <u>LIQQA FAIZAL YUNIAR</u> )   <br />
          <br />
          <br /></td>
          <td valign="bottom"><br />
          <br />
          <br />
          <br />
          <br />
           ( <u><?php echo strtoupper($row_sprh->nama); ?></u> )   <br />
          <br />
          <br /></td>
        </tr>
    </table></td>
  </tr>
</table>
