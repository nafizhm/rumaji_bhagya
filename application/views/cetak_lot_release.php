<?php
$money=function ($value) { return number_format((float)$value,2,',','.'); };
$months=array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
$releaseDate='';
if ($row->tanggal_release) {
    $time=strtotime($row->tanggal_release);
    $releaseDate=date('j',$time).' '.$months[(int)date('n',$time)].' '.date('Y',$time);
}
// Repeat the sheet's three groups of unit columns; extend onto more pages when needed.
$rowsPerGroup=20;
$pages=array_chunk($units,$rowsPerGroup*3);
if (!$pages) $pages=array(array());
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rilis Jual - <?php echo html_escape($row->kode_lot); ?></title>
<style>
@page { size:A4 portrait; margin:12mm 9mm; }
* { box-sizing:border-box; }
body { margin:0; color:#111; background:#eee; font:11px Arial,sans-serif; }
.toolbar { padding:14px; text-align:center; }
.toolbar button { padding:9px 20px; cursor:pointer; }
.sheet { width:192mm; min-height:270mm; margin:0 auto 15px; padding:8mm 4mm; background:#fff; }
h1 { margin:0 0 6px; font-size:21px; text-align:center; }
.document-no { text-align:center; margin-bottom:22px; }
.meta { border-collapse:collapse; margin-bottom:15px; font-size:12px; }
.meta td { padding:4px 6px 4px 0; vertical-align:top; }
.meta td:first-child { width:150px; font-weight:bold; }
.section-title { font-weight:bold; margin:12px 0 5px; }
table.grid { width:100%; border-collapse:collapse; table-layout:fixed; }
.grid th,.grid td { border:1px solid #222; padding:6px 3px; text-align:center; overflow-wrap:anywhere; }
.grid th { background:#ededed; font-size:9px; }
.pricing td { font-size:9px; padding:9px 2px; }
.units th,.units td { font-size:10px; height:21px; padding:3px 2px; }
.units .sequence { width:5%; }
.units .block { width:7%; }
.units .number { width:9%; }
.units .lot { width:12.33%; }
.bottom { page-break-inside:avoid; }
.signatures { width:100%; margin:22px 0 18px; border-collapse:collapse; }
.signatures td { text-align:center; width:50%; }
.signatures .space td { height:58px; }
.signature-line { display:inline-block; width:170px; border-bottom:1px solid #222; }
.features p { margin:7px 0; }
.notes { white-space:pre-wrap; margin-top:14px; overflow-wrap:anywhere; }
.page-number { text-align:right; font-size:9px; margin-top:15px; color:#555; }
@media print {
 body { background:white; }
 .toolbar { display:none; }
 .sheet { margin:0; padding:0; width:100%; min-height:0; break-after:page; page-break-after:always; }
 .sheet:last-child { break-after:auto; page-break-after:auto; }
 h1 { font-size:18px; }
 .document-no { margin-bottom:15px; }
 .meta { margin-bottom:10px; font-size:11px; }
 .meta td { padding:3px 6px 3px 0; }
 .section-title { margin:8px 0 4px; }
 .units th,.units td { font-size:9px; line-height:1.1; height:19px; padding:2px; }
 .signatures { margin:14px 0 12px; }
 .signatures .space td { height:40px; }
 .features p { margin:5px 0; }
 tr { break-inside:avoid; page-break-inside:avoid; }
 th { print-color-adjust:exact; -webkit-print-color-adjust:exact; }
}
</style></head><body>
<div class="toolbar"><button type="button" onclick="window.print()">Cetak / Simpan PDF</button></div>
<?php foreach ($pages as $pageIndex=>$pageUnits): ?>
<article class="sheet">
<h1>RILIS JUAL</h1>
<div class="document-no">NO : &nbsp; <?php echo trim($row->nomor_surat)!=='' ? html_escape($row->nomor_surat) : '______________________________'; ?></div>
<table class="meta">
<tr><td>NAMA PERUMAHAN</td><td>: <?php echo $project ? html_escape($project->namproj) : ''; ?></td></tr>
<tr><td>TANGGAL RILIS</td><td>: <?php echo html_escape($releaseDate); ?></td></tr>
<tr><td>JUMLAH UNIT</td><td>: <?php echo count($units); ?></td></tr>
</table>
<div class="section-title">HARGA JUAL</div>
<table class="grid pricing"><thead><tr>
<th>LOT</th><th>TYPE</th><th>HARGA JUAL</th><th>UANG MUKA</th><th>DISKON UM</th><th>UANG MUKA SETELAH DISKON</th><th>BIAYA LAIN-LAIN</th><th>KPR</th><th>PERKIRAAN ANGSURAN</th>
</tr></thead><tbody><tr>
<td><?php echo html_escape($row->kode_lot); ?></td><td><?php echo html_escape($row->release_type); ?></td>
<?php foreach (array('harga_jual','uang_muka','diskon_uang_muka','uang_muka_setelah_diskon','biaya_lain','kpr','perkiraan_angsuran') as $field): ?><td><?php echo $money($row->$field); ?></td><?php endforeach; ?>
</tr></tbody></table>
<div class="section-title">DAFTAR UNIT</div>
<table class="grid units"><colgroup><?php for ($g=0;$g<3;$g++): ?><col class="sequence"><col class="block"><col class="number"><col class="lot"><?php endfor; ?></colgroup>
<thead><tr><?php for ($g=0;$g<3;$g++): ?><th>NO.</th><th>BLOK</th><th>NO. KAV</th><th>LOT</th><?php endfor; ?></tr></thead>
<tbody><?php for ($r=0;$r<$rowsPerGroup;$r++): ?><tr>
<?php for ($g=0;$g<3;$g++): $position=$g*$rowsPerGroup+$r; $unit=isset($pageUnits[$position]) ? $pageUnits[$position] : NULL; ?>
<td><?php echo $unit ? $pageIndex*60+$position+1 : '&nbsp;'; ?></td><td><?php echo $unit ? html_escape($unit->blok) : '&nbsp;'; ?></td><td><?php echo $unit ? html_escape($unit->no) : '&nbsp;'; ?></td><td><?php echo $unit ? html_escape($row->kode_lot) : '&nbsp;'; ?></td>
<?php endfor; ?></tr><?php endfor; ?></tbody></table>
<div class="bottom">
<table class="signatures"><tr><td>Dibuat oleh,</td><td>Disetujui oleh,</td></tr><tr class="space"><td></td><td></td></tr><tr><td><span class="signature-line"></span></td><td><span class="signature-line"></span></td></tr></table>
<div class="features"><strong>TAMBAHAN FITUR (bila ada)</strong>
<p>BIAYA HOOK = Rp <?php echo $money($row->biaya_hook); ?></p>
<p>BIAYA KELEBIHAN TANAH = Rp <?php echo $money($row->biaya_kelebihan_tanah); ?> per meter</p>
</div>
<?php if (trim($row->keterangan)!==''): ?><div class="notes"><strong>CATATAN:</strong><br><?php echo html_escape($row->keterangan); ?></div><?php endif; ?>
</div>
<?php if (count($pages)>1): ?><div class="page-number">Halaman <?php echo $pageIndex+1; ?> / <?php echo count($pages); ?></div><?php endif; ?>
</article>
<?php endforeach; ?>
</body></html>
