CREATE TABLE IF NOT EXISTS tpengajuan_akad (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
 kode_akad VARCHAR(50) NOT NULL,
 tanggal_akad DATE NOT NULL,
 keterangan TEXT NOT NULL,
 created_by VARCHAR(10) NOT NULL,
 created_at DATETIME NOT NULL,
 updated_at DATETIME NOT NULL,
 UNIQUE KEY uq_pengajuan_akad_kode (kode_akad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS tpengajuan_akad_peserta (
 pengajuan_id INT UNSIGNED NOT NULL,
 projid INT NOT NULL,
 sprno INT NOT NULL,
 hadir TINYINT(1) NOT NULL DEFAULT 0,
 biaya_akad DECIMAL(14,2) NOT NULL DEFAULT 0,
 PRIMARY KEY (pengajuan_id,projid,sprno),
 KEY idx_pengajuan_akad_spr (projid,sprno),
 CONSTRAINT fk_pengajuan_akad_peserta FOREIGN KEY (pengajuan_id) REFERENCES tpengajuan_akad(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS mpengajuan_akad_akses_user (
 userid VARCHAR(10) NOT NULL PRIMARY KEY,
 c TINYINT(1) NOT NULL DEFAULT 0,
 r TINYINT(1) NOT NULL DEFAULT 0,
 u TINYINT(1) NOT NULL DEFAULT 0,
 d TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO mmenu (moid,mid,mdesc)
SELECT 'PJ','T002','Pengajuan Akad' WHERE NOT EXISTS (SELECT 1 FROM mmenu WHERE moid='PJ' AND mid='T002');
INSERT INTO mauth (roleid,moid,mid,c,r,u,d,crby,crdat,chby,chdat)
SELECT roleid,'PJ','T002',0,0,0,0,'system',CURRENT_DATE,'system',CURRENT_DATE FROM mrole roles
WHERE NOT EXISTS (SELECT 1 FROM mauth a WHERE a.roleid=roles.roleid AND a.moid='PJ' AND a.mid='T002');
INSERT INTO mpengajuan_akad_akses_user (userid,c,r,u,d)
SELECT userid,1,1,1,1 FROM muser WHERE userid='faizal'
ON DUPLICATE KEY UPDATE c=1,r=1,u=1,d=1;
