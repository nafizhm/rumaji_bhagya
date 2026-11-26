<?php $dashboard_totals = array_fill_keys(array_keys($dashboard_metrics), 0); ?>
<section class="x_panel">
  <div class="x_title"><h2>Dashboard Perumahan <small>Data per <?php echo date('d/m/Y H:i'); ?></small></h2><div class="clearfix"></div></div>
  <div class="x_content">
    <p class="text-muted">Klik nama proyek atau angka untuk melihat rincian data.</p>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover dashboard-summary">
        <colgroup><col style="width:48px"><col class="dashboard-project-col"><?php foreach ($dashboard_metrics as $key => $label): ?><col class="<?php echo in_array($key, array('jatuh_tempo','uang_masuk'), TRUE) ? 'dashboard-money-col' : 'dashboard-unit-col'; ?>"><?php endforeach; ?></colgroup>
        <thead><tr><th class="text-center">No</th><th>Proyek</th><?php foreach ($dashboard_metrics as $key => $label): ?><th class="text-center <?php echo $key === 'uang_masuk' ? 'dashboard-income' : ''; ?>"><?php echo html_escape($label); ?></th><?php endforeach; ?></tr></thead>
        <tbody>
        <?php $dashboard_number=0; foreach ($dashboard_rows as $row): ?>
          <tr>
            <td class="text-center"><?php echo ++$dashboard_number; ?></td>
            <th><a href="<?php echo site_url('home/dashboard_detail/' . $row->projid . '/total'); ?>"><?php echo html_escape(mb_strtoupper($row->namproj, 'UTF-8')); ?></a></th>
            <?php foreach ($dashboard_metrics as $key => $label): ?>
              <?php
                $detail_url = site_url('home/dashboard_detail/' . $row->projid . '/' . $key);
                $dashboard_totals[$key] += (float) $row->$key;
                $is_money = in_array($key, array('jatuh_tempo','uang_masuk'), TRUE);
                if (in_array($key, array('batal', 'spr'), TRUE)) {
                    $detail_url = site_url('spr/do_search_spr/' . $row->projid) . '?' . http_build_query(array('status' => $key === 'batal' ? 'batal' : 'proses'));
                }
              ?>
              <td class="<?php echo $is_money ? 'text-right' : 'text-center'; ?> <?php echo $key === 'uang_masuk' ? 'dashboard-income' : ''; ?>"><a style="display:block;padding:6px;" aria-label="<?php echo html_escape($label . ' — ' . $row->namproj); ?>" href="<?php echo html_escape($detail_url); ?>"><?php echo $is_money ? 'Rp ' . number_format($row->$key, 0, ',', '.') : number_format($row->$key, 0, ',', '.'); ?></a></td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($dashboard_rows)): ?><tr><td colspan="<?php echo count($dashboard_metrics)+2; ?>">Belum ada proyek aktif.</td></tr><?php endif; ?>
        </tbody>
        <tfoot><tr><th scope="row" colspan="2">Jumlah Total</th>
          <?php foreach ($dashboard_metrics as $key => $label): $is_money = in_array($key, array('jatuh_tempo','uang_masuk'), TRUE); ?>
            <th class="<?php echo $is_money ? 'text-right' : 'text-center'; ?> <?php echo $key === 'uang_masuk' ? 'dashboard-income' : ''; ?>"><?php echo ($is_money ? 'Rp ' : '') . number_format($dashboard_totals[$key], 0, ',', '.'); ?></th>
          <?php endforeach; ?>
        </tr></tfoot>
      </table>
    </div>
  </div>
</section>
