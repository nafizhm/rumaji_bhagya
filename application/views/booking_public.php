<?php defined('BASEPATH') OR exit('No direct script access allowed'); $assets=$this->config->item('assets'); ?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Booking Hunian | Bhagya Group</title><link rel="stylesheet" href="<?php echo $assets; ?>/css/booking.css?v=3"></head>
<body class="booking-public">
 <main class="booking-shell" data-url="<?php echo site_url('booking'); ?>">
  <header class="booking-hero"><img src="<?php echo $assets; ?>/img/bhagya-white.svg" alt="Bhagya Group"><div><span>PORTAL BOOKING MARKETING</span><h1>Form Booking Konsumen</h1><p>Formulir ini diisi oleh marketing untuk mengajukan booking calon konsumen. Lengkapi profil pemohon, pilih unit hunian, dan unggah dokumen pendukung konsumen.</p></div></header>
  <div class="booking-tools"><a id="booking-siteplan" class="booking-siteplan-link" href="<?php echo site_url('booking/siteplan'); ?>" target="_blank" rel="noopener">Siteplan</a></div>
  <form id="booking-form" action="<?php echo site_url('booking/store'); ?>" method="post" enctype="multipart/form-data">
   <input type="hidden" name="token" value="<?php echo html_escape($this->session->userdata('booking_token')); ?>"><input type="hidden" name="request_key" value="<?php echo $request_key; ?>"><input type="hidden" name="blok" id="booking-blok"><input type="hidden" name="no" id="booking-no">
   <div id="booking-error" class="booking-alert" role="alert" hidden></div>
   <section class="booking-section"><h2>Profil Pemohon</h2><p class="section-note">Lengkapi data calon konsumen sesuai dokumen identitas. Kolom bertanda * wajib diisi.</p><div class="booking-grid">
   <?php foreach ($fields as $name=>$field): ?>
    <div class="booking-field <?php echo $field[1]==='textarea'?'full-width':''; ?>"><label for="<?php echo $name; ?>"><?php echo html_escape($field[0]); ?><?php echo $field[2]?' <span>*</span>':''; ?></label>
     <?php if (is_array($field[1])): ?><select name="<?php echo $name; ?>" id="<?php echo $name; ?>" <?php echo $field[2]?'required':''; ?>><option value="">Pilih <?php echo html_escape($field[0]); ?></option><?php foreach ($field[1] as $option): ?><option><?php echo html_escape($option); ?></option><?php endforeach; ?></select>
     <?php elseif ($field[1]==='textarea'): ?><textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" rows="3" maxlength="2000" required></textarea>
     <?php else: ?><input type="<?php echo $field[1]; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" <?php echo $field[2]?'required':''; ?> <?php if (in_array($name,array('nik','nik_p'),TRUE)): ?>inputmode="numeric" pattern="[0-9]{16}" maxlength="16"<?php else: ?>maxlength="150"<?php endif; ?> <?php if ($name==='tanggal'): ?>value="<?php echo date('Y-m-d'); ?>"<?php endif; ?> <?php if ($name==='tgl_lahir'): ?>max="<?php echo date('Y-m-d'); ?>"<?php endif; ?>><?php endif; ?>
    </div>
   <?php endforeach; ?>
     <?php foreach (array('kontak_darurat'=>array('No. Kontak Darurat','tel'),'nama_pemilik_kontak_darurat'=>array('Nama Pemilik Kontak Darurat','text'),'keterangan_kontak_darurat'=>array('Hubungan dengan Pemohon','text')) as $prefix=>$field): $name=$prefix.'_1'; ?><div class="booking-field"><label for="<?php echo $name; ?>"><?php echo $field[0]; ?></label><input id="<?php echo $name; ?>" name="<?php echo $name; ?>" type="<?php echo $field[1]; ?>" maxlength="255"></div><?php endforeach; ?>
    </div>
   </section>
   <section class="booking-section"><h2>Pilihan Hunian</h2><div class="booking-grid">
    <div class="booking-field full-width"><label for="booking-project">Lokasi Perumahan *</label><select name="projid" id="booking-project" required><option value="">Pilih perumahan</option><?php foreach ($projects as $project): ?><option value="<?php echo (int)$project->projid; ?>"><?php echo html_escape($project->namproj); ?></option><?php endforeach; ?></select></div>
    <div class="booking-field full-width"><label for="booking-lot">Lot Release *</label><select name="lot_release_id" id="booking-lot" required disabled><option value="">Pilih perumahan terlebih dahulu</option></select><p class="section-note" style="margin:8px 0 0">Hanya Lot Release yang diaktifkan pada Siteplan dan memiliki unit ready yang tersedia.</p></div>
    <div class="booking-field"><label for="booking-unit">Blok / Kavling *</label><select id="booking-unit" required disabled><option value="">Pilih Lot Release terlebih dahulu</option></select></div>
    <div class="booking-field"><label for="id_marketing">Marketing *</label><select name="id_marketing" id="id_marketing" required><option value="">Pilih marketing</option><option value="0">Non Marketing</option><?php foreach ($marketing as $sales): ?><option value="<?php echo html_escape($sales->kodeslsman); ?>"><?php echo html_escape($sales->namaslsman); ?></option><?php endforeach; ?></select></div>
    <div class="booking-field"><label for="jenis_perumahan">Jenis Perumahan *</label><select id="jenis_perumahan" name="jenis_perumahan" required><option value="">Pilih jenis perumahan</option><option>Subsidi</option><option>Komersil</option></select></div>
    <div class="booking-field"><label for="jenis_pembelian">Jenis Pembelian *</label><select id="jenis_pembelian" name="jenis_pembelian" required><option value="">Pilih jenis pembelian</option><option>Pembelian Cash</option><option>Cash Bertahap</option><option>KPR</option></select></div>
    <div class="booking-price full-width" aria-live="polite"><div>Harga Jual <strong id="booking-price">Rp 0</strong></div><div>Biaya Lain-lain <strong id="booking-cost">Rp 0</strong></div><div class="booking-price-total">Total Harga <strong id="booking-total">Rp 0</strong></div></div>
    <div class="booking-field full-width"><label for="booking_fee">Booking Fee (Rp) *</label><input type="text" name="booking_fee" id="booking_fee" inputmode="numeric" maxlength="17" required placeholder="Masukkan jumlah booking fee"></div>
   </div></section>
   <section class="booking-section"><h2>Dokumen Pendukung</h2><p class="section-note">JPG, PNG, WEBP atau PDF. Maksimal 10 MB per berkas, mengikuti batas unggahan server. Foto KTP wajib diunggah.</p><div class="booking-grid">
    <?php foreach ($attachments as $name=>$label): ?><div class="booking-field"><label for="<?php echo $name; ?>"><?php echo html_escape($label); ?><?php echo $name==='foto_ktp'?' *':''; ?></label><input type="file" name="<?php echo $name; ?>" id="<?php echo $name; ?>" accept=".jpg,.jpeg,.png,.webp,.pdf" <?php echo $name==='foto_ktp'?'required':''; ?>></div><?php endforeach; ?>
   </div></section>
   <div class="booking-submit"><p>Periksa kembali data konsumen, pilihan unit, dan dokumen sebelum mengajukan booking.</p><button type="submit" id="booking-submit"><span class="booking-spinner" hidden></span><span id="booking-submit-label">Kirim Booking</span></button></div>
  </form><footer>&copy; <?php echo date('Y'); ?> Bhagya Group · Portal Booking Perumahan</footer>
 </main>
 <dialog id="booking-success"><div class="success-mark" aria-hidden="true">✓</div><h2>Booking berhasil dikirim</h2><p id="booking-success-message"></p><p>Nomor registrasi booking</p><strong id="booking-registration"></strong><p>Simpan nomor registrasi ini untuk memantau dan menindaklanjuti pengajuan konsumen.</p><a href="<?php echo site_url('booking'); ?>">Ajukan Booking Berikutnya</a></dialog>
 <script src="<?php echo $assets; ?>/js/booking-public.js?v=3"></script>
</body></html>
