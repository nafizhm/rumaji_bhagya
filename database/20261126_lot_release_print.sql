ALTER TABLE mlot ADD COLUMN release_saved_at DATETIME NULL DEFAULT NULL;
UPDATE mlot SET release_saved_at=CURRENT_TIMESTAMP
WHERE release_saved_at IS NULL AND (
 tanggal_release IS NOT NULL OR release_type<>'' OR harga_jual<>0 OR uang_muka<>0 OR diskon_uang_muka<>0
 OR biaya_lain<>0 OR kpr<>0 OR perkiraan_angsuran<>0 OR biaya_hook<>0 OR biaya_kelebihan_tanah<>0
);
