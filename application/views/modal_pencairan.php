<div class="modal fade" id="pencairan-modal" tabindex="-1" role="dialog" aria-labelledby="pencairan-title" data-backdrop="static" data-keyboard="false" data-url="<?php echo site_url('pencairan'); ?>">
 <div class="modal-dialog modal-lg" role="document"><div class="modal-content">
  <div class="modal-header"><button class="close" type="button" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="pencairan-title">Pencairan Akad</h4></div>
  <div class="modal-body">
   <p id="pencairan-customer" class="text-muted"></p>
   <div id="pencairan-loading" role="status"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Memuat data pencairan…</div>
   <div class="alert alert-danger" id="pencairan-error" role="alert" hidden></div>
   <div class="alert alert-success" id="pencairan-success" role="status" hidden></div>
   <form id="pencairan-form" hidden>
    <input type="hidden" name="projid"><input type="hidden" name="sprno"><input type="hidden" name="token"><input type="hidden" name="request_key"><input type="hidden" name="base_id">
    <p id="pencairan-stage-note" class="alert alert-info" role="status"></p><fieldset id="pencairan-fields">
     <div class="row"><div class="col-xs-12 form-group"><label for="pencairan-plafon">Nilai Plafon (Rp)</label><input id="pencairan-plafon" name="plafon" type="text" inputmode="numeric" autocomplete="off" maxlength="17" class="form-control pencairan-amount" required></div>
     <div class="col-xs-12 form-group"><label for="pencairan-kpp">KKP Proyek (Rp)</label><input id="pencairan-kpp" name="kpp" readonly type="text" inputmode="numeric" autocomplete="off" maxlength="17" class="form-control pencairan-amount" value="0" required></div></div>
     <h4 id="pencairan-retensi-title">Retensi yang Ditahan</h4><div id="pencairan-categories" class="row"></div>
     <div class="well" aria-live="polite"><div>Total retensi: <strong id="pencairan-retensi-total">Rp 0</strong></div><h4>Jumlah Pencairan: <strong id="pencairan-net">Rp 0</strong></h4><small>Awal: plafon dikurangi KKP dan retensi. Lanjutan: jumlah nominal pencairan retensi yang diisi.</small></div>
     <button type="submit" id="pencairan-save" class="btn btn-success"><i class="fa fa-save" aria-hidden="true"></i> Simpan Pencairan</button>
    </fieldset>
   </form>
   <hr>
   <h4>Histori Pencairan</h4>
   <p class="text-muted">Pencairan dapat dilakukan bertahap hingga saldo habis. Nilai retensi pada histori adalah sisa yang masih ditahan setelah transaksi.</p>
   <div class="table-responsive"><table class="table table-striped table-bordered"><thead><tr><th>Tanggal</th><th>Jumlah Retensi Tersisa</th><th>Rincian Retensi Tersisa</th><th>Jumlah Pencairan</th></tr></thead><tbody id="pencairan-history"></tbody></table></div>
  </div>
  <div class="modal-footer"><button class="btn btn-default" type="button" id="pencairan-close" data-dismiss="modal">Tutup</button></div>
 </div></div>
</div>
<style>#pencairan-modal [hidden]{display:none!important}#pencairan-modal .modal-content{border-radius:12px}#pencairan-modal .well{margin-top:16px}#pencairan-modal td{vertical-align:top}#pencairan-history td:nth-child(2),#pencairan-history td:nth-child(4){white-space:nowrap;text-align:right}.pencairan-category-row:has(.retensi-balance){display:flex;align-items:center;gap:24px}.retensi-balance,.retensi-entry{flex:1;min-width:0}.retensi-balance strong,.retensi-balance span{display:block}.retensi-entry label{font-size:14px}@media(max-width:480px){.pencairan-category-row:has(.retensi-balance){gap:12px}.retensi-balance{font-size:13px}}
#pencairan-fields .form-group{display:flex;align-items:center;gap:24px;width:100%;margin-bottom:18px}
#pencairan-fields .form-group>label,#pencairan-fields .retensi-balance{flex:0 0 40%;margin:0;min-width:0}
#pencairan-fields .form-group>.form-control,#pencairan-fields .retensi-entry{flex:1;min-width:0;width:auto}
#pencairan-fields .retensi-entry .form-control{width:100%}
#pencairan-fields .retensi-balance label{display:block;margin:0 0 4px}
@media(max-width:480px){#pencairan-fields .form-group{gap:12px}#pencairan-fields .form-group>label,#pencairan-fields .retensi-balance{flex-basis:44%;font-size:13px}#pencairan-fields .retensi-balance span{font-size:12px}}
</style>
