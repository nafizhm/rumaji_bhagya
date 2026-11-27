CREATE TABLE IF NOT EXISTS mlot (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 kode_lot VARCHAR(3) NOT NULL,
 tanggal_release DATE NOT NULL,
 keterangan TEXT NOT NULL,
 UNIQUE KEY uq_lot_kode (kode_lot)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO mmenu (moid,mid,mdesc)
SELECT 'PJ','M008','Pengaturan Lot' WHERE NOT EXISTS (SELECT 1 FROM mmenu WHERE moid='PJ' AND mid='M008');
INSERT INTO mauth (roleid,moid,mid,c,r,u,d,crby,crdat,chby,chdat)
SELECT roleid,'PJ','M008',0,0,0,0,'system',CURRENT_DATE,'system',CURRENT_DATE FROM mrole roles
WHERE NOT EXISTS (SELECT 1 FROM mauth a WHERE a.roleid=roles.roleid AND a.moid='PJ' AND a.mid='M008');
