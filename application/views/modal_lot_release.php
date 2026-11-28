<div class="modal fade" id="lot-release-modal" tabindex="-1" role="dialog" aria-labelledby="lot-release-title">
<div class="modal-dialog modal-lg" role="document"><div class="modal-content">
<?php echo form_open('lot/release',array('id'=>'lot-release-form','class'=>'form-horizontal')); ?>
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="lot-release-title">Release Lot</h4></div>
<div class="modal-body">
<div id="lot-release-error" class="alert alert-danger" role="alert" style="display:none"></div>
<input type="hidden" name="id" id="release-lot-id">
<input type="hidden" name="lot_token" value="<?php echo html_escape($this->session->userdata('lot_token')); ?>">
<div class="form-group"><div class="col-sm-8 col-sm-offset-4"><label for="release-siteplan"><input type="checkbox" id="release-siteplan" name="siteplan_active" value="1"> Siteplan</label><p class="help-block">Aktifkan unit Lot Release ini untuk penjualan di Siteplan dan Booking.</p></div></div>
<div class="form-group"><label class="control-label col-sm-4" for="release-nomor-surat">Nomor Surat</label><div class="col-sm-8"><input type="text" class="form-control" id="release-nomor-surat" name="nomor_surat" maxlength="150"></div></div>
<div class="form-group"><label class="control-label col-sm-4" for="release-tanggal">Tanggal Release</label><div class="col-sm-8"><input type="date" class="form-control" id="release-tanggal" name="tanggal_release"></div></div>
<div class="form-group"><label class="control-label col-sm-4" for="release-type">Type</label><div class="col-sm-8"><input class="form-control" id="release-type" name="release_type" maxlength="100"></div></div>
<?php
$releaseFields=array('harga_jual'=>'Harga Jual','uang_muka'=>'Uang Muka','diskon_uang_muka'=>'Diskon Uang Muka','uang_muka_setelah_diskon'=>'Uang Muka Setelah Diskon','biaya_lain'=>'Biaya Lain-lain','kpr'=>'KPR','perkiraan_angsuran'=>'Perkiraan Angsuran','biaya_hook'=>'Biaya Hook','biaya_kelebihan_tanah'=>'Biaya Kelebihan Tanah (per meter)');
foreach ($releaseFields as $field=>$label): ?>
<div class="form-group"><label class="control-label col-sm-4" for="release-<?php echo $field; ?>"><?php echo $label; ?></label><div class="col-sm-8"><div class="input-group"><span class="input-group-addon">Rp</span><input type="text" inputmode="decimal" autocomplete="off" class="form-control release-money" id="release-<?php echo $field; ?>" name="<?php echo $field; ?>" value="0" required <?php echo $field==='uang_muka_setelah_diskon' ? 'readonly' : ''; ?>></div>
<?php if ($field==='diskon_uang_muka'): ?><small>Diskon dalam nominal rupiah.</small><?php endif; ?>
<?php if ($field==='uang_muka_setelah_diskon'): ?><small>Dihitung otomatis dari uang muka dikurangi diskon.</small><?php endif; ?>
</div></div>
<?php endforeach; ?>
<hr><h4>STANDARD / Cicilan</h4>
<div class="form-group"><label class="control-label col-sm-4" for="release-utj">Total UTJ</label><div class="col-sm-8"><div class="input-group"><span class="input-group-addon">Rp</span><input type="text" inputmode="decimal" class="form-control release-money" id="release-utj" name="utj" value="0" required></div></div></div>
<?php foreach (array('utj_cicilan'=>'Cicilan UTJ','um_cicilan'=>'Cicilan UM') as $field=>$label): ?>
<div class="form-group"><label class="control-label col-sm-4" for="release-<?php echo $field; ?>"><?php echo $label; ?></label><div class="col-sm-8"><div class="input-group"><input type="number" min="1" max="999" step="1" class="form-control" id="release-<?php echo $field; ?>" name="<?php echo $field; ?>" value="1" required><span class="input-group-addon">kali</span></div></div></div>
<?php endforeach; ?>
<div class="form-group"><div class="col-sm-8 col-sm-offset-4"><p class="help-block">UM = uang muka setelah diskon dikurangi UTJ. Nominal per cicilan dihitung dari total dibagi jumlah kali. Biaya lain-lain (BLL) ditampilkan sesuai nominal pada form.</p><div id="release-cicilan-preview" class="well well-sm" aria-live="polite"></div></div></div>
</div>
<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-success" id="lot-release-submit">Simpan Release</button></div>
<?php echo form_close(); ?>
</div></div></div>
