<?php $assets=$this->config->item('assets'); if (!$publicView) { $this->load->view('header'); } else { ?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Siteplan Unit Ready</title><link rel="stylesheet" href="<?php echo $assets; ?>/gentelella-master/vendors/bootstrap/dist/css/bootstrap.min.css"><link rel="stylesheet" href="<?php echo $assets; ?>/gentelella-master/vendors/font-awesome/css/font-awesome.min.css"><style>body{background:#f1f5f2;color:#263d33;padding:20px}.x_panel{background:#fff;border:1px solid #dce5de;border-radius:12px;padding:20px}.btn{margin-bottom:5px}</style></head><body>
<?php } ?>
<link rel="stylesheet" href="<?php echo $assets; ?>/css/siteplan.css?v=3">
<div class="right_col" role="main" id="siteplan-page" data-ready-only="<?php echo $publicView ? '1' : '0'; ?>">
 <h3>Siteplan</h3>
 <div class="x_panel">
  <div class="x_content"><div class="siteplan-layout">
   <aside class="siteplan-sidebar" aria-label="Pengaturan dan keterangan siteplan">
    <div class="siteplan-sidebar-heading"><h2><?php echo $project?html_escape(mb_strtoupper($project->namproj,'UTF-8')):'Pilih Proyek'; ?></h2><span class="siteplan-count"><?php echo count($units); ?> unit</span></div>
   <form method="get" action="<?php echo $formAction; ?>" class="siteplan-toolbar">
    <div><label for="siteplan-project">Lokasi Proyek</label><select id="siteplan-project" name="projid" class="form-control" onchange="document.getElementById('siteplan-lot').value='0';this.form.submit()"><option value="">Pilih proyek</option><?php foreach ($projects as $item): ?><option value="<?php echo (int)$item->projid; ?>" <?php echo (int)$item->projid===$projid?'selected':''; ?>><?php echo html_escape(mb_strtoupper($item->namproj,'UTF-8')); ?><?php echo $item->has_siteplan===NULL?' — Belum ada siteplan':''; ?></option><?php endforeach; ?></select></div>
    <div><label for="siteplan-lot">Lot Release</label><select id="siteplan-lot" name="lot_release_id" class="form-control" onchange="this.form.submit()">
    <option value="0" <?php echo !$lotid ? 'selected' : ''; ?>>Semua Lot Release Aktif</option>
    <?php if ($lotid && !$selectedLot): ?><option value="<?php echo $lotid; ?>" selected>Lot Release tidak memiliki unit ready</option><?php endif; ?>
    <?php foreach ($lots as $lot): ?><option value="<?php echo (int)$lot->id; ?>" <?php echo (int)$lot->id===$lotid ? 'selected' : ''; ?>><?php echo html_escape($lot->kode_lot); ?> (<?php echo (int)$lot->jumlah_unit; ?> ready)</option><?php endforeach; ?>
    </select></div>
    <?php if ($template): ?><div><label for="siteplan-search">Cari Unit</label><input id="siteplan-search" class="form-control" type="search" placeholder="Contoh: A01 atau J12A" autocomplete="off"></div><div><label for="siteplan-status">Status</label><select id="siteplan-status" class="form-control"><?php if ($publicView): ?><option value="tersedia">Unit Ready</option><?php else: ?><option value="">Semua status</option><?php foreach ($legend as $state=>$item): ?><option value="<?php echo $state; ?>" <?php echo $this->input->get('status')===$state ? 'selected' : ''; ?>><?php echo html_escape($item[0]); ?></option><?php endforeach; endif; ?></select></div><?php endif; ?>
   </form>
   <?php if (!$counts['tersedia']): ?><div class="alert alert-info">Belum ada unit yang bisa dijual untuk pilihan Lot Release ini.</div><?php endif; ?>
   <?php if ($publicView): ?><p><a href="<?php echo site_url('booking'); ?>">Kembali ke Booking</a></p><?php endif; ?>
   <?php if ($template): ?>
   <?php if (!$publicView): ?><button type="button" class="btn btn-primary" id="siteplan-pdf"><i class="fa fa-file-pdf-o"></i> Cetak PDF</button><div id="siteplan-pdf-error" class="text-danger" role="alert" hidden></div><?php endif; ?>
   <div class="siteplan-legend"><?php foreach ($legend as $state=>$item): ?><span><i style="background:<?php echo $item[1]; ?>"></i><?php echo html_escape($item[0]); ?> <strong><?php echo $counts[$state]; ?></strong></span><?php endforeach; ?></div>
   <div class="siteplan-map-toolbar"><span id="siteplan-result" role="status">Klik unit untuk melihat detail. Geser peta atau gunakan tombol zoom.</span><div><button type="button" class="btn btn-default" id="siteplan-minus" aria-label="Perkecil"><i class="fa fa-minus"></i></button><button type="button" class="btn btn-default" id="siteplan-plus" aria-label="Perbesar"><i class="fa fa-plus"></i></button><button type="button" class="btn btn-default" id="siteplan-reset">Reset</button></div></div>
   <?php endif; ?>
   </aside>
   <div class="siteplan-main">
   <?php if (!$template): ?><div class="alert alert-info">Siteplan untuk proyek ini belum tersedia.</div><?php else: ?>
   <div id="siteplan-canvas" aria-label="Peta unit perumahan">
    <?php echo $template->header_svg; ?>
    <?php $unitData=array();foreach ($units as $unit): $unitData[$unit->kode_unit]=array('kode'=>$unit->kode_unit,'blok'=>$unit->blok,'no'=>$unit->no,'lot'=>$unit->release_kode_lot ?: '-','luas'=>$unit->ltstd,'tipe'=>$unit->tipe,'jenis'=>$unit->jenis,'status'=>$legend[$unit->state][0],'status_stok'=>$unit->namstat,'stock_url'=>$unit->stock_url,'spr_url'=>$unit->spr_url); ?>
    <g class="siteplan-unit" role="button" tabindex="0" data-code="<?php echo html_escape($unit->kode_unit); ?>" data-state="<?php echo $unit->state; ?>" aria-label="<?php echo html_escape($unit->kode_unit.' — '.$legend[$unit->state][0]); ?>"><title><?php echo html_escape($unit->kode_unit.' — '.$legend[$unit->state][0]); ?></title><?php echo str_replace('[[warna]]',$legend[$unit->state][1],$unit->shape_svg); ?><?php echo $unit->label_svg; ?></g>
    <?php endforeach;echo $template->footer_svg; ?>
   </div>
   <script type="application/json" id="siteplan-unit-data"><?php echo json_encode($unitData,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?></script>
   <?php endif; ?>
   </div>
  </div></div>
 </div>
</div>
<div class="modal fade" id="siteplan-detail" tabindex="-1" role="dialog" aria-labelledby="siteplan-detail-title"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Tutup">&times;</button><h4 class="modal-title" id="siteplan-detail-title">Detail Unit</h4></div><div class="modal-body"><table class="table table-striped table-bordered"><tbody id="siteplan-detail-body"></tbody></table></div><div class="modal-footer"><a id="siteplan-stock-link" class="btn btn-primary">Detail Stok</a><a id="siteplan-spr-link" class="btn btn-success" hidden>Lihat SPR</a><button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button></div></div></div></div>
<?php if (!$publicView) { $this->load->view('footer'); } else { ?>
<script src="<?php echo $assets; ?>/gentelella-master/vendors/jquery/dist/jquery.min.js"></script><script src="<?php echo $assets; ?>/gentelella-master/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<?php } ?>
<script src="<?php echo $assets; ?>/js/siteplan.js?v=3"></script>
<?php if ($publicView): ?></body></html><?php endif; ?>
