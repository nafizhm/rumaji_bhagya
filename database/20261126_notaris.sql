CREATE TABLE IF NOT EXISTS mnotaris (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 nama_notaris VARCHAR(150) NOT NULL,
 keterangan TEXT NOT NULL,
 created_at DATETIME NOT NULL,
 updated_at DATETIME NOT NULL,
 UNIQUE KEY uq_notaris_nama (nama_notaris)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE tsprh ADD COLUMN notaris_id INT UNSIGNED NULL DEFAULT NULL, ADD INDEX idx_spr_notaris (notaris_id);
ALTER TABLE tkavlog ADD COLUMN notaris_id INT UNSIGNED NULL DEFAULT NULL, ADD COLUMN nama_notaris VARCHAR(150) NULL DEFAULT NULL;

INSERT INTO mmenu (moid,mid,mdesc)
SELECT 'PJ','M007','Notaris' WHERE NOT EXISTS (SELECT 1 FROM mmenu WHERE moid='PJ' AND mid='M007');
INSERT INTO mauth (roleid,moid,mid,c,r,u,d,crby,crdat,chby,chdat)
SELECT roleid,'PJ','M007',0,0,0,0,'system',CURRENT_DATE,'system',CURRENT_DATE FROM mrole roles
WHERE NOT EXISTS (SELECT 1 FROM mauth a WHERE a.roleid=roles.roleid AND a.moid='PJ' AND a.mid='M007');
