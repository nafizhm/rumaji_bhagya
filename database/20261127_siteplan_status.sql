CREATE TABLE IF NOT EXISTS msiteplan_status (
 kode VARCHAR(30) NOT NULL PRIMARY KEY,
 nama VARCHAR(80) NOT NULL,
 warna CHAR(7) NOT NULL,
 urutan INT NOT NULL,
 updated_by VARCHAR(10) NULL DEFAULT NULL,
 updated_at DATETIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
INSERT IGNORE INTO msiteplan_status(kode,nama,warna,urutan) VALUES
 ('tersedia','Ready','#ffffff',1),('belum_rilis','Belum Ready','#cbd5e1',2),('tidak_tersedia','Tidak Tersedia','#a8b0ba',3),
 ('booking','Booking','#f6cc5c',4),('spr','Proses SPR','#6db4ee',5),('akad','Sudah Akad','#76cb9a',6);
CREATE TABLE IF NOT EXISTS msiteplan_status_akses_user (
 userid VARCHAR(10) NOT NULL PRIMARY KEY,
 c TINYINT(1) NOT NULL DEFAULT 0,r TINYINT(1) NOT NULL DEFAULT 0,u TINYINT(1) NOT NULL DEFAULT 0,d TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
INSERT INTO mmenu(moid,mid,mdesc)
SELECT 'PJ','M009','Pengaturan Siteplan' WHERE NOT EXISTS(SELECT 1 FROM mmenu WHERE moid='PJ' AND mid='M009');
INSERT INTO mauth(roleid,moid,mid,c,r,u,d,crby,crdat,chby,chdat)
SELECT roleid,'PJ','M009',0,0,0,0,'system',CURRENT_DATE,'system',CURRENT_DATE FROM mrole roles
WHERE NOT EXISTS(SELECT 1 FROM mauth a WHERE a.roleid=roles.roleid AND a.moid='PJ' AND a.mid='M009');
INSERT INTO msiteplan_status_akses_user(userid,c,r,u,d)
SELECT userid,1,1,1,1 FROM muser WHERE userid='faizal'
ON DUPLICATE KEY UPDATE c=1,r=1,u=1,d=1;
