CREATE TABLE IF NOT EXISTS mretensi_kategori (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama_retensi VARCHAR(150) NOT NULL,
  keterangan TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_retensi_nama (nama_retensi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO mmenu (moid, mid, mdesc)
SELECT 'PJ', 'M006', 'Kategori Retensi'
WHERE NOT EXISTS (SELECT 1 FROM mmenu WHERE moid='PJ' AND mid='M006');

INSERT INTO mauth (roleid, moid, mid, c, r, u, d, crby, crdat, chby, chdat)
SELECT roleid, 'PJ', 'M006', 0, 0, 0, 0, 'system', CURRENT_DATE, 'system', CURRENT_DATE
FROM mrole r WHERE NOT EXISTS (SELECT 1 FROM mauth a WHERE a.roleid=r.roleid AND a.moid='PJ' AND a.mid='M006');
