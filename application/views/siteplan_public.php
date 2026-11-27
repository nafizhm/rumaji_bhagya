<?php defined('BASEPATH') OR exit('No direct script access allowed'); $assets=$this->config->item('assets'); ?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover"><meta name="theme-color" content="#174d87"><title>Siteplan Penjualan</title><link rel="stylesheet" href="<?php echo $assets; ?>/css/siteplan-public.css?v=4"></head>
<body class="public-siteplan">
<main class="sp-shell">
 <div class="sp-topline"><a href="<?php echo site_url('booking'); ?>"><span aria-hidden="true">&larr;</span> Kembali ke Booking</a><span>RUMAJI</span></div>
 <section class="sp-card" aria-labelledby="sp-title">
  <header class="sp-header"><h1 id="sp-title">Siteplan Penjualan</h1><p>Pilih lokasi perumahan, lalu ketuk kavling untuk melihat detail.</p></header>
  <div class="sp-body">
   <nav class="sp-projects" aria-label="Lokasi perumahan">
    <?php foreach ($projects as $item): ?><a href="<?php echo html_escape(site_url('booking/siteplan').'?projid='.(int)$item->projid); ?>" class="sp-project <?php echo (int)$item->projid===$projid ? 'is-active' : ''; ?>" <?php echo (int)$item->projid===$projid ? 'aria-current="page"' : ''; ?>><?php echo html_escape($item->namproj); ?></a><?php endforeach; ?>
   </nav>
   <div class="sp-filters">
    <?php if ($template): ?><div class="sp-field"><label for="sp-search">Cari kavling</label><input id="sp-search" type="search" placeholder="Blok / nomor kavling" autocomplete="off"></div><?php endif; ?>
   </div>
   <?php if (!$template): ?><div class="sp-empty">Siteplan untuk proyek ini belum tersedia.</div><?php else: ?>
   <div class="sp-map-wrap">
    <div class="sp-map" id="sp-map" aria-label="Siteplan interaktif. Geser untuk berpindah, gunakan dua jari untuk memperbesar.">
     <?php echo $template->header_svg; ?>
     <?php $unitData=array(); foreach ($units as $unit):
      $ready=$unit->state==='tersedia';
      $hex=substr($legend[$unit->state][1],1);$brightness=hexdec(substr($hex,0,2))*.299+hexdec(substr($hex,2,2))*.587+hexdec(substr($hex,4,2))*.114;
      $labelColor=$brightness>150 ? '#1f2937' : '#ffffff';
      $unitData[$unit->kode_unit]=array('kode'=>$unit->kode_unit,'blok'=>$unit->blok,'no'=>$unit->no,'luas'=>$unit->ltstd,'tipe'=>$unit->release_type ?: $unit->tipe,'jenis'=>$unit->jenis,'state'=>$unit->state,'status'=>$legend[$unit->state][0],'color'=>$legend[$unit->state][1], 'harga'=>$ready ? (float)$unit->release_harga_jual+(float)$unit->release_biaya_lain : NULL);
     ?>
     <g class="sp-unit<?php echo $ready ? '' : ' is-disabled'; ?>" role="<?php echo $ready ? 'button' : 'img'; ?>" tabindex="<?php echo $ready ? '0' : '-1'; ?>" aria-disabled="<?php echo $ready ? 'false' : 'true'; ?>" data-code="<?php echo html_escape($unit->kode_unit); ?>" data-state="<?php echo $unit->state; ?>" style="--sp-status-color:<?php echo html_escape($legend[$unit->state][1]); ?>;--sp-label-color:<?php echo $labelColor; ?>" aria-label="<?php echo html_escape('Kavling '.$unit->blok.'/'.$unit->no.', '.$legend[$unit->state][0]); ?>"><title><?php echo html_escape($unit->kode_unit.' - '.$legend[$unit->state][0]); ?></title><?php echo str_replace('[[warna]]',$legend[$unit->state][1],$unit->shape_svg); ?><?php echo $unit->label_svg; ?></g>
     <?php endforeach; echo $template->footer_svg; ?>
    </div>
    <button type="button" class="sp-reset" id="sp-reset" aria-label="Kembalikan tampilan awal peta"><span aria-hidden="true">&#8635;</span> Reset Siteplan</button>
    <div class="sp-zoom" aria-label="Kontrol zoom"><button type="button" id="sp-plus" aria-label="Perbesar peta">+</button><button type="button" id="sp-minus" aria-label="Perkecil peta">&minus;</button></div>
    <div class="sp-map-hint" id="sp-result" role="status">Ketuk kavling untuk melihat detail</div>
   </div>
   <div class="sp-summary"><div class="sp-legend" aria-label="Keterangan warna">
    <?php foreach ($legend as $item): ?><span><i style="background:<?php echo html_escape($item[1]); ?>"></i><?php echo html_escape($item[0]); ?></span><?php endforeach; ?>
   </div><span class="sp-ready-count"><?php echo (int)$counts['tersedia']; ?> unit <?php echo html_escape($legend['tersedia'][0]); ?></span></div>
   <?php if (!$counts['tersedia']): ?><p class="sp-notice">Belum ada unit yang tersedia untuk dijual di proyek ini.</p><?php endif; ?>
   <script type="application/json" id="sp-unit-data"><?php echo json_encode($unitData,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?></script>
   <?php endif; ?>
  </div>
 </section>
</main>
<section class="sp-popover" id="sp-popover" role="dialog" aria-modal="false" aria-labelledby="sp-popover-title" hidden>
 <button type="button" class="sp-close" id="sp-close" aria-label="Tutup detail kavling">&times;</button>
 <h2 id="sp-popover-title">Detail Kavling</h2>
 <dl><div><dt>Status</dt><dd><span id="sp-detail-status" class="sp-badge"></span></dd></div>
 <div><dt>Blok</dt><dd id="sp-detail-blok"></dd></div>
 <div><dt>Kode Kavling</dt><dd id="sp-detail-code"></dd></div>
 <div><dt>Tipe</dt><dd id="sp-detail-type"></dd></div>
 <div><dt>Luas Tanah</dt><dd id="sp-detail-area"></dd></div></dl>
 <div class="sp-price"><span id="sp-price-label">Total Harga</span><strong id="sp-detail-price"></strong></div>
</section>
<script src="<?php echo $assets; ?>/js/siteplan-public.js?v=5"></script>
</body></html>
