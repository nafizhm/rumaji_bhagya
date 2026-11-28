<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>Siteplan</title>
<style>
@page { size:A4 landscape; margin:10mm; }
body { font:10px Helvetica,sans-serif; color:#111; margin:0; }
h1 { font-size:18px; margin:0 0 4px; }
.subtitle { margin-bottom:8px; }
.legend { margin-bottom:7px; }
.legend span { display:inline-block; margin-right:12px; }
.swatch { display:inline-block; width:10px; height:10px; vertical-align:middle; border:1px solid #333; }
.map { width:100%; height:155mm; text-align:center; }
.map svg { width:100%; height:155mm; }
.toolbar { margin:12px 0; text-align:center; }
.toolbar button { padding:10px 20px; cursor:pointer; }
@media print { .toolbar { display:none; } * { print-color-adjust:exact; -webkit-print-color-adjust:exact; } }
</style></head><body>
<div class="toolbar"><button type="button" onclick="window.print()">Cetak / Simpan PDF</button></div>
<h1>SITEPLAN — <?php echo html_escape($project->namproj); ?></h1>
<div class="subtitle"><?php echo $lot?'Lot Release: '.html_escape($lot->kode_lot):'Semua Lot Release Aktif'; ?> | Dicetak <?php echo (new DateTimeImmutable('now',new DateTimeZone('Asia/Jakarta')))->format('d-m-Y H:i'); ?> WIB</div>
<div class="legend"><?php foreach ($legend as $state=>$item): ?><span><i class="swatch" style="background:<?php echo html_escape($item[1]); ?>"></i> <?php echo html_escape($item[0]); ?> (<?php echo (int)$counts[$state]; ?>)</span><?php endforeach; ?></div>
<div class="map"><?php echo $svg; ?></div>
</body></html>
