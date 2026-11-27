<?php $this->load->view('header'); $canUpdate=$this->mod_lot->permission('u'); $unitsLocked=!empty($row->release_saved_at); $canEditUnits=$canUpdate && !$unitsLocked; ?>
<div class="right_col" role="main"><div class="x_panel">
<div class="x_title"><h2>Detail Lot Release</h2><div class="clearfix"></div></div>
<div class="x_content">
<?php $this->lib->disp_msg(); ?>
<dl class="dl-horizontal">
<dt>Lokasi Proyek</dt><dd><?php echo $project ? html_escape($project->namproj) : 'Belum dipilih'; ?></dd>
<dt>Kode Lot</dt><dd><?php echo html_escape($row->kode_lot); ?></dd>
<dt>Tanggal Release</dt><dd><?php echo $row->tanggal_release ? date('d-m-Y',strtotime($row->tanggal_release)) : '-'; ?></dd>
<dt>Keterangan</dt><dd><?php echo nl2br(html_escape($row->keterangan)); ?></dd>
<dt>Jumlah Unit Tersimpan</dt><dd><?php echo (int)$jumlah_unit; ?></dd>
</dl>
<?php if (!$project): ?><div class="alert alert-warning">Pilih lokasi proyek melalui Edit Lot terlebih dahulu.</div>
<?php else: ?>
<h3><?php echo $unitsLocked ? 'Daftar Unit Kavling' : 'Pilih Unit Kavling'; ?></h3>
<?php if ($unitsLocked): ?><div class="alert alert-info">Release sudah disimpan. Pilihan unit dikunci dan tidak dapat ditambah atau dikurangi.</div>
<?php else: ?><p>Centang unit yang akan dimasukkan ke Lot Release ini, kemudian klik Simpan Unit. Unit pada Lot Release lain tidak ditampilkan. Hapus centang untuk mengeluarkan unit dari Lot Release ini.</p><?php endif; ?>
<?php echo form_open('lot/save_units',array('id'=>'lot-units-form')); ?>
<input type="hidden" name="id" value="<?php echo (int)$row->id; ?>">
<input type="hidden" name="lot_token" value="<?php echo html_escape($this->session->userdata('lot_token')); ?>">
<input type="hidden" name="units_json" id="units-json" value="">
<div class="form-group"><label for="unit-search">Cari unit</label><input type="search" id="unit-search" class="form-control" placeholder="Cari blok, nomor, tipe, atau status"></div>
<p><strong id="selected-count">0</strong> unit dicentang</p>
<div class="table-responsive"><table id="lot-unit-table" class="table table-striped table-bordered">
<thead><tr><th><?php if ($canEditUnits): ?><input type="checkbox" id="select-visible" aria-label="Pilih semua unit yang ditampilkan"><?php endif; ?> Pilih</th><th>Blok</th><th>Nomor</th><th>Tipe</th><th>Status</th></tr></thead>
<tbody><?php foreach ($units as $unit): ?><tr>
<td><input type="checkbox" class="lot-unit" value="<?php echo html_escape(base64_encode(json_encode(array($unit->blok,$unit->no)))); ?>" aria-label="<?php echo html_escape('Pilih '.$unit->blok.' / '.$unit->no); ?>" <?php echo $unit->lot_id ? 'checked' : ''; ?> <?php echo $canEditUnits ? '' : 'disabled'; ?>></td>
<td><?php echo html_escape($unit->blok); ?></td><td><?php echo html_escape($unit->no); ?></td><td><?php echo html_escape($unit->tipe); ?></td><td><?php echo html_escape($unit->namstat ?: $unit->status); ?></td>
</tr><?php endforeach; ?></tbody>
</table></div>
<?php if (!$units): ?><p>Tidak ada unit yang tersedia di proyek ini.</p><?php endif; ?>
<?php if ($canEditUnits): ?><button class="btn btn-success" type="submit">Simpan Unit</button><?php endif; ?>
<?php echo form_close(); endif; ?>
<a class="btn btn-default" href="<?php echo site_url('lot'); ?>">Kembali</a>
<?php if ($canUpdate): ?><a class="btn btn-primary" href="<?php echo site_url('lot/edit/'.$row->id); ?>">Edit Lot</a><?php endif; ?>
</div></div></div>
<?php $this->load->view('footer'); ?>
<script>
(function () {
    var form=document.getElementById('lot-units-form');
    if (!form) return;
    var boxes=Array.prototype.slice.call(form.querySelectorAll('.lot-unit'));
    var all=document.getElementById('select-visible');
    function visible() { return boxes.filter(function (box) { return !box.closest('tr').hidden; }); }
    function update() {
        document.getElementById('selected-count').textContent=boxes.filter(function (box) { return box.checked; }).length;
        if (all) {
            var shown=visible(), count=shown.filter(function (box) { return box.checked; }).length;
            all.checked=shown.length>0 && count===shown.length;
            all.indeterminate=count>0 && count<shown.length;
        }
    }
    boxes.forEach(function (box) { box.addEventListener('change',update); });
    if (all) all.addEventListener('change',function () { visible().forEach(function (box) { box.checked=all.checked; }); update(); });
    document.getElementById('unit-search').addEventListener('input',function () {
        var search=this.value.toLowerCase().trim();
        boxes.forEach(function (box) { var tr=box.closest('tr'); tr.hidden=tr.textContent.toLowerCase().indexOf(search)===-1; });
        update();
    });
    form.addEventListener('submit',function () {
        document.getElementById('units-json').value=JSON.stringify(boxes.filter(function (box) { return box.checked; }).map(function (box) { return box.value; }));
    });
    update();
}());
</script>
