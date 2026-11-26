<?php $this->load->view('header'); ?>
<style>
.akad-heading{display:flex;align-items:center;justify-content:space-between;gap:20px}.akad-heading h2{float:none;margin:0;white-space:normal;overflow:visible;flex:1}.akad-unit-total{display:flex;align-items:baseline;gap:8px;white-space:nowrap;color:#2a3f54}.akad-unit-total strong{font-size:30px;line-height:1.2}.akad-unit-total span{font-size:15px;color:#73879c}.akad-toolbar{margin:8px 0 20px}.akad-filters{display:flex;align-items:flex-end;gap:18px}.akad-filter-field{flex:1;min-width:0}.akad-filter-field label{display:block;margin-bottom:7px}.akad-filter-field .form-control{width:100%;height:38px;border-radius:5px}.akad-links{display:flex;gap:6px}.akad-links .btn{margin:0}@media(max-width:767px){.akad-filters{flex-wrap:wrap;gap:12px}.akad-filter-field{flex-basis:100%}.akad-heading h2{font-size:17px}.akad-unit-total strong{font-size:25px}.akad-links{flex-wrap:wrap}}
</style>
<div class="right_col" role="main">
  <div class="page-title"><div class="title_left"><h3>List Akad</h3></div></div>
  <div class="clearfix"></div>
  <div class="x_panel">
    <div class="x_title akad-heading"><h2><?php echo html_escape($title); ?></h2><div class="akad-unit-total" role="status"><strong id="akad-total">&hellip;</strong><span>unit</span></div></div>
    <div class="x_content">
      <?php $this->lib->disp_msg(); ?>
      <div class="akad-toolbar">
        <form id="akad-filter" method="get" action="<?php echo site_url('akad'); ?>" class="akad-filters">
          <?php if (!empty($akad_menu)): ?>
          <div class="akad-filter-field"><label for="akad-project">Lokasi Proyek</label>
            <select id="akad-project" name="projid" class="form-control" onchange="this.form.submit()">
              <?php foreach ($projects as $project): ?><option value="<?php echo (int)$project->projid; ?>" <?php echo (int)$project->projid===(int)$projid?'selected':''; ?>><?php echo html_escape($project->namproj); ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="akad-filter-field"><label for="akad-type">Jenis Akad</label>
            <select id="akad-type" name="jenis" class="form-control" onchange="this.form.submit()">
              <?php foreach (array('akad'=>'Semua Akad','subsidi'=>'Akad Subsidi','komersial'=>'Akad Komersial') as $type=>$label): ?><option value="<?php echo $type; ?>" <?php echo $metric===$type?'selected':''; ?>><?php echo $label; ?></option><?php endforeach; ?>
            </select>
          </div>
          <?php else: ?>
          <div class="akad-links">
            <a class="btn btn-default" href="<?php echo site_url('home'); ?>">Kembali ke Home</a>
            <a class="btn <?php echo $metric==='subsidi'?'btn-primary':'btn-default'; ?>" href="<?php echo site_url('home/dashboard_detail/'.$projid.'/subsidi'); ?>">Akad Subsidi</a>
            <a class="btn <?php echo $metric==='komersial'?'btn-primary':'btn-default'; ?>" href="<?php echo site_url('home/dashboard_detail/'.$projid.'/komersial'); ?>">Akad Komersial</a>
          </div>
          <?php endif; ?>
          <div class="akad-filter-field"><label for="akad-status">Status Pencairan</label>
            <select class="form-control" id="akad-status" name="status_pencairan">
              <option value="">Semua</option>
              <option value="lunas" <?php echo $payment_status==='lunas'?'selected':''; ?>>Sudah Lunas</option>
              <option value="belum" <?php echo $payment_status==='belum'?'selected':''; ?>>Belum Lunas / Masih Ada Retensi</option>
            </select>
          </div>
        </form>
      </div>
      <div id="akad-export-buttons" class="text-right" style="margin-bottom:16px"></div>
      <div class="table-responsive">
        <table id="akad-table" class="table table-striped table-bordered table-hover" data-title="<?php echo html_escape($title); ?>" data-url="<?php echo site_url('home/akad_data/' . $projid . '/' . $metric); ?>" data-projid="<?php echo (int)$projid; ?>">
          <thead><tr><th>No SPR</th><th>Lot</th><th>Blok/No</th><th>Konsumen</th><th>Jumlah Plafon</th><th>KKP</th><th>Jumlah Pencairan</th><th>Jumlah Retensi</th><th>Sisa Pencairan</th><th>Pilihan</th></tr></thead>
          <tbody></tbody>
        </table>
      </div>

    </div>
  </div>
</div>
<?php $this->load->view('modal_pencairan'); ?>
<?php $this->load->view('footer'); ?>
<script src="<?php echo $this->config->item('assets'); ?>/js/pencairan.js?v=8"></script>

<script src="<?php echo $this->config->item('assets'); ?>/js/akad-export.js?v=2"></script>
