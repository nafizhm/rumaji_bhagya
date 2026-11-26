<?php $this->load->view('header'); ?>
<div class="right_col" role="main">
  <h3><?php echo html_escape($title); ?></h3>
  <a class="btn btn-default" href="<?php echo site_url('home'); ?>">Kembali ke Home</a>
  <div class="x_panel"><div class="x_content">
    <p>Total <?php echo number_format($total, 0, ',', '.'); ?> data · Halaman <?php echo $page; ?> dari <?php echo $pages; ?></p>
    <div class="table-responsive"><table class="table table-striped table-bordered">
      <thead><tr>
        <?php if ($metric === 'jatuh_tempo'): ?>
          <th>No SPR</th><th>Konsumen</th><th>Tipe</th><th>Angsuran</th><th>Jatuh Tempo</th><th>Jumlah</th>
        <?php elseif ($metric === 'uang_masuk'): ?>
          <th>No Kwitansi</th><th>No SPR</th><th>Tanggal</th><th>Tipe</th><th>Angsuran</th><th>Jumlah</th>
        <?php else: ?>
          <th>Lot</th><th>Blok/No</th><th>Jenis</th><th>Status</th>
        <?php endif; ?>
        <th>Detail</th>
      </tr></thead><tbody>
      <?php foreach ($rows as $row): ?><tr>
        <?php if ($metric === 'jatuh_tempo'): ?>
          <td><?php echo $this->lib->no_spr($row->sprno); ?></td><td><?php echo html_escape($row->nama); ?></td><td><?php echo html_escape($row->tipe); ?></td><td><?php echo (int) $row->ang; ?></td><td><?php echo html_escape($row->jtmp); ?></td><td class="text-right">Rp <?php echo number_format($row->total, 0, ',', '.'); ?></td>
        <?php elseif ($metric === 'uang_masuk'): ?>
          <td><?php echo (int) $row->kwtno; ?></td><td><?php echo $this->lib->no_spr($row->sprno); ?></td><td><?php echo html_escape($row->tgl); ?></td><td><?php echo html_escape($row->tipe); ?></td><td><?php echo (int) $row->ang; ?></td><td class="text-right">Rp <?php echo number_format($row->total, 0, ',', '.'); ?></td>
        <?php else: ?>
          <td><?php echo html_escape($row->lot); ?></td><td><?php echo html_escape($row->blok . '/' . $row->no); ?></td><td><?php echo html_escape($row->jenis); ?></td><td><?php echo html_escape($row->status); ?></td>
        <?php endif; ?>
        <td><a class="btn btn-primary btn-sm" href="<?php echo in_array($metric, array('jatuh_tempo','uang_masuk'), TRUE) ? site_url('byr/list_byr/' . $projid . '/' . $row->sprno) : site_url('kav/edit_kav/' . $projid . '/' . rawurlencode($row->blok) . '/' . rawurlencode($row->no)); ?>">Lihat detail</a></td>
      </tr><?php endforeach; ?>
      <?php if (empty($rows)): ?><tr><td colspan="7">Belum ada data untuk kategori ini.</td></tr><?php endif; ?>
      </tbody></table></div>
      <nav aria-label="Halaman detail"><ul class="pagination">
      <?php for ($p = max(1, $page - 2); $p <= min($pages, $page + 2); $p++): ?>
        <li class="<?php echo $p === $page ? 'active' : ''; ?>"><a href="<?php echo site_url('home/dashboard_detail/' . $projid . '/' . $metric) . '?page=' . $p; ?>"><?php echo $p; ?></a></li>
      <?php endfor; ?>
      <?php if ($page < $pages): ?><li><a href="<?php echo site_url('home/dashboard_detail/' . $projid . '/' . $metric) . '?page=' . ($page + 1); ?>">Berikutnya</a></li><?php endif; ?>
      </ul></nav>
  </div></div>
</div>
<?php $this->load->view('footer'); ?>
